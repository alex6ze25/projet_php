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

    // Ajoutez ces méthodes à la classe ModuleModel existante

public function getFirstModuleOfTheme($themeId) {
    try {
        $query = "SELECT * FROM modules WHERE theme_id = :theme_id ORDER BY id ASC LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['theme_id' => $themeId]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) {
        return null;
    }
}

public function getPreviousModule($themeId, $currentModuleId) {
    try {
        $query = "SELECT * FROM modules 
                 WHERE theme_id = :theme_id AND id < :current_module_id 
                 ORDER BY id DESC LIMIT 1";
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

public function getModuleWithTheme($moduleId) {
    try {
        $query = "SELECT m.*, t.titre as theme_titre, t.id as theme_id 
                 FROM modules m 
                 JOIN themes t ON m.theme_id = t.id 
                 WHERE m.id = :module_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['module_id' => $moduleId]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) {
        return null;
    }
}
}
