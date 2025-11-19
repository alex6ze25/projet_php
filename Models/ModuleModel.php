<?php
class ModuleModel {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    public function getModulesByTheme($themeName) {
        try {
            $query = "SELECT m.*, t.titre as theme_titre 
                     FROM modules m 
                     JOIN themes t ON m.theme_id = t.id 
                     WHERE t.titre = :theme_name 
                     ORDER BY m.id";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute(['theme_name' => $themeName]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            return [];
        }
    }
    
    public function getThemeInfo($themeName) {
        try {
            $query = "SELECT * FROM themes WHERE titre = :theme_name";
            $stmt = $this->db->prepare($query);
            $stmt->execute(['theme_name' => $themeName]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            return null;
        }
    }
    
    public function getModuleContent($moduleId) {
        try {
            $query = "SELECT * FROM modules WHERE id = :module_id";
            $stmt = $this->db->prepare($query);
            $stmt->execute(['module_id' => $moduleId]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            return null;
        }
    }
}
?>