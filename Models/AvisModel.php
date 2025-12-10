<?php
class AvisModel {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    // Récupérer tous les avis d'un module avec les infos de l'utilisateur
    public function getAvisByModule($moduleId) {
        try {
            $query = "SELECT a.*, u.prenom, u.nom 
                      FROM avis_modules a 
                      JOIN users u ON a.user_id = u.id 
                      WHERE a.module_id = :module_id 
                      ORDER BY a.date_creation DESC";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute(['module_id' => $moduleId]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Calculer la moyenne des notes
    public function getMoyenne($moduleId) {
        try {
            $query = "SELECT AVG(note) as moyenne, COUNT(*) as total FROM avis_modules WHERE module_id = :module_id";
            $stmt = $this->db->prepare($query);
            $stmt->execute(['module_id' => $moduleId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ['moyenne' => 0, 'total' => 0];
        }
    }

    public function soumettreAvis($userId, $moduleId, $note, $commentaire) {
        try {
            // On vérifie si l'avis existe déjà pour éviter les doublons
            $checkQuery = "SELECT id FROM avis_modules WHERE user_id = :user_id AND module_id = :module_id";
            $stmt = $this->db->prepare($checkQuery);
            $stmt->execute(['user_id' => $userId, 'module_id' => $moduleId]);
            
            if ($stmt->fetch()) {
                // Mise à jour si existe déjà
                $query = "UPDATE avis_modules SET note = :note, commentaire = :commentaire, date_creation = NOW() 
                          WHERE user_id = :user_id AND module_id = :module_id";
            } else {
                // Insertion si nouveau
                $query = "INSERT INTO avis_modules (user_id, module_id, note, commentaire) 
                          VALUES (:user_id, :module_id, :note, :commentaire)";
            }

            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                'user_id' => $userId,
                'module_id' => $moduleId,
                'note' => $note,
                'commentaire' => $commentaire
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
}