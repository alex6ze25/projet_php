<?php
class QuizModel {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    public function getQuizByModule($moduleId) {
        try {
            $query = "SELECT * FROM quiz WHERE module_id = :module_id";
            $stmt = $this->db->prepare($query);
            $stmt->execute(['module_id' => $moduleId]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            return null;
        }
    }
    
    public function verifyAnswer($quizId, $userAnswer) {
        try {
            $query = "SELECT reponse_correcte FROM quiz WHERE id = :quiz_id";
            $stmt = $this->db->prepare($query);
            $stmt->execute(['quiz_id' => $quizId]);
            $quiz = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($quiz) {
                return trim(strtolower($userAnswer)) === trim(strtolower($quiz['reponse_correcte']));
            }
            
            return false;
            
        } catch (PDOException $e) {
            return false;
        }
    }
}
