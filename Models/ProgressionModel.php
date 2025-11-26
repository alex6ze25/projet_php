<?php
class ProgressionModel {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    public function getModuleProgression($userId, $moduleId) {
        try {
            $query = "SELECT * FROM user_progression 
                     WHERE user_id = :user_id AND module_id = :module_id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'user_id' => $userId,
                'module_id' => $moduleId
            ]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            return null;
        }
    }
    
    public function startModule($userId, $moduleId) {
        try {
            $query = "INSERT INTO user_progression (user_id, module_id, is_completed, score) 
                     VALUES (:user_id, :module_id, FALSE, 0)
                     ON DUPLICATE KEY UPDATE is_completed = FALSE, score = 0";
            
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                'user_id' => $userId,
                'module_id' => $moduleId
            ]);
            
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function completeModule($userId, $moduleId, $score) {
        try {
            $query = "UPDATE user_progression 
                     SET is_completed = TRUE, score = :score, completed_at = NOW()
                     WHERE user_id = :user_id AND module_id = :module_id";
            
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                'user_id' => $userId,
                'module_id' => $moduleId,
                'score' => $score
            ]);
            
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function getNextModule($themeId, $currentModuleId) {
        try {
            $query = "SELECT * FROM modules 
                     WHERE theme_id = :theme_id AND id > :current_module_id 
                     ORDER BY id ASC LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'theme_id' => $themeId,
                'current_module_id' => $currentModuleId
            ]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            return null;
        }
    }
    
    public function getUserProgressInTheme($userId, $themeId) {
        try {
            $query = "SELECT m.id, m.titre, up.is_completed, up.score,
                             (SELECT COUNT(*) FROM modules WHERE theme_id = :theme_id) as total_modules,
                             (SELECT COUNT(*) FROM user_progression up2 
                              JOIN modules m2 ON up2.module_id = m2.id 
                              WHERE up2.user_id = :user_id AND m2.theme_id = :theme_id2 AND up2.is_completed = TRUE) as completed_modules
                      FROM modules m
                      LEFT JOIN user_progression up ON m.id = up.module_id AND up.user_id = :user_id2
                      WHERE m.theme_id = :theme_id3
                      ORDER BY m.id";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'user_id' => $userId,
                'user_id2' => $userId,
                'theme_id' => $themeId,
                'theme_id2' => $themeId,
                'theme_id3' => $themeId
            ]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            return [];
        }
    }
}
