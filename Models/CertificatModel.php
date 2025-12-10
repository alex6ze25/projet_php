<?php
class CertificatModel {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    // Vérifie si l'utilisateur a complété 100% du thème
    public function verifierEligibilite($userId, $themeId) {
        try {
            // 1. Compter le nombre total de modules du thème
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM modules WHERE theme_id = :theme_id");
            $stmt->execute(['theme_id' => $themeId]);
            $totalModules = $stmt->fetchColumn();

            // 2. Compter le nombre de modules complétés par l'user dans ce thème
            $query = "SELECT COUNT(*) FROM user_progression up 
                      JOIN modules m ON up.module_id = m.id 
                      WHERE up.user_id = :user_id 
                      AND m.theme_id = :theme_id 
                      AND up.is_completed = 1";
            $stmt = $this->db->prepare($query);
            $stmt->execute(['user_id' => $userId, 'theme_id' => $themeId]);
            $completedModules = $stmt->fetchColumn();

            return ($totalModules > 0 && $totalModules == $completedModules);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Enregistre le certificat s'il n'existe pas déjà
    public function genererCertificat($userId, $themeId) {
        try {
            // Vérifier si déjà existant
            $check = $this->db->prepare("SELECT id FROM certificats WHERE user_id = :u AND theme_id = :t");
            $check->execute(['u' => $userId, 't' => $themeId]);
            
            if ($check->rowCount() == 0) {
                $code = strtoupper(uniqid('CERT-')); // Génère un code unique
                $stmt = $this->db->prepare("INSERT INTO certificats (user_id, theme_id, code_unique) VALUES (:u, :t, :c)");
                $stmt->execute(['u' => $userId, 't' => $themeId, 'c' => $code]);
            }
        } catch (PDOException $e) {
            // Erreur silencieuse
        }
    }

    // Pour le profil : Compter les certificats (Stats)
    public function getNombreCertificats($userId) {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM certificats WHERE user_id = :u");
            $stmt->execute(['u' => $userId]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    // --- C'EST CETTE MÉTHODE QUI MANQUAIT ET QUI CAUSAIT L'ERREUR ---
    // Récupérer la liste complète des certificats d'un user pour l'affichage
    public function getCertificatsUser($userId) {
        try {
            $query = "SELECT c.*, t.titre as theme_titre 
                      FROM certificats c
                      JOIN themes t ON c.theme_id = t.id
                      WHERE c.user_id = :user_id
                      ORDER BY c.date_obtention DESC";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute(['user_id' => $userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
}