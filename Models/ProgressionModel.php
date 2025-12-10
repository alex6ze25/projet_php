<?php
class ProgressionModel {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    // Récupère l'état d'un module spécifique pour un user
    public function getModuleProgression($userId, $moduleId) {
        try {
            $query = "SELECT * FROM user_progression 
                      WHERE user_id = :user_id AND module_id = :module_id";
            $stmt = $this->db->prepare($query);
            $stmt->execute(['user_id' => $userId, 'module_id' => $moduleId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }
    
    // Marque le début d'un module (Statut : En cours)
    public function startModule($userId, $moduleId) {
        try {
            // On insère seulement si ça n'existe pas. Si ça existe, on ne touche à rien
            $query = "INSERT IGNORE INTO user_progression (user_id, module_id, is_completed, score, completed_at) 
                      VALUES (:user_id, :module_id, 0, 0, NULL)";
            
            $stmt = $this->db->prepare($query);
            return $stmt->execute(['user_id' => $userId, 'module_id' => $moduleId]);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    // Récupère la progression détaillée pour tout un thème
    public function getUserProgressInTheme($userId, $themeId) {
        try {
            $query = "SELECT m.id, m.titre, m.contenu,
                             up.is_completed, up.score, up.completed_at,
                             CASE 
                                WHEN up.is_completed = 1 THEN 'completed'
                                WHEN up.id IS NOT NULL THEN 'in_progress'
                                ELSE 'pending'
                             END as status
                      FROM modules m
                      LEFT JOIN user_progression up ON m.id = up.module_id AND up.user_id = :user_id
                      WHERE m.theme_id = :theme_id
                      ORDER BY m.id ASC";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'user_id' => $userId,
                'theme_id' => $themeId
            ]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Récupère les statistiques globales pour le Profil (CORRIGÉ)
    public function getGlobalStats($userId) {
        try {
            // 1. Nombre de modules complétés
            $queryCount = "SELECT COUNT(*) as total FROM user_progression WHERE user_id = :user_id AND is_completed = 1";
            $stmt = $this->db->prepare($queryCount);
            $stmt->execute(['user_id' => $userId]);
            $completed = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // 2. Nombre total de modules existants sur la plateforme
            $queryTotal = "SELECT COUNT(*) as total FROM modules";
            $stmtTotal = $this->db->query($queryTotal);
            $totalModules = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];

            // 3. Compter les certificats (AJOUTÉ ICI AVANT LE RETURN)
            $queryCert = "SELECT COUNT(*) FROM certificats WHERE user_id = :user_id";
            $stmtCert = $this->db->prepare($queryCert);
            $stmtCert->execute(['user_id' => $userId]);
            $nbCertificats = $stmtCert->fetchColumn();

            // Calcul du pourcentage
            $percent = ($totalModules > 0) ? round(($completed / $totalModules) * 100) : 0;

            return [
                'completed_modules' => $completed,
                'total_modules' => $totalModules,
                'global_percentage' => $percent,
                'certificates' => $nbCertificats // On renvoie la vraie valeur
            ];

        } catch (PDOException $e) {
            // En cas d'erreur, on renvoie des zéros
            return [
                'completed_modules' => 0, 
                'total_modules' => 0,
                'global_percentage' => 0, 
                'certificates' => 0
            ];
        }
    }
}
