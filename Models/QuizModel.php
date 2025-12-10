<?php
class QuizModel {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    public function getQuizByModule($module_id) {
        try {
            $query = "SELECT * FROM quiz WHERE module_id = :module_id ORDER BY id";
            $stmt = $this->db->prepare($query);
            $stmt->execute(['module_id' => $module_id]);
            $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Décoder les propositions JSON pour chaque question
            foreach ($questions as &$question) {
                if (!empty($question['propositions'])) {
                    $question['propositions'] = json_decode($question['propositions'], true);
                }
            }
            
            return $questions;
            
        } catch (PDOException $e) {
            error_log("Erreur getQuizByModule: " . $e->getMessage());
            return [];
        }
    }

    public function getQuizQuestion($quiz_id) {
        try {
            $query = "SELECT * FROM quiz WHERE id = :quiz_id";
            $stmt = $this->db->prepare($query);
            $stmt->execute(['quiz_id' => $quiz_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Erreur getQuizQuestion: " . $e->getMessage());
            return null;
        }
    }

    public function saveUserAnswer($user_id, $quiz_id, $reponse, $is_correct) {
        try {
            $query = "INSERT INTO quiz_reponses (user_id, quiz_id, reponse, is_correct) 
                     VALUES (:user_id, :quiz_id, :reponse, :is_correct)";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'user_id' => $user_id,
                'quiz_id' => $quiz_id,
                'reponse' => $reponse,
                'is_correct' => $is_correct
            ]);
            
            return true;
            
        } catch (PDOException $e) {
            error_log("Erreur saveUserAnswer: " . $e->getMessage());
            return false;
        }
    }

    public function getUserQuizResults($user_id, $module_id) {
        try {
            $query = "SELECT qr.quiz_id, qr.reponse, qr.is_correct, q.reponse_correcte, q.question
                     FROM quiz_reponses qr
                     JOIN quiz q ON qr.quiz_id = q.id
                     WHERE qr.user_id = :user_id AND q.module_id = :module_id";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'user_id' => $user_id,
                'module_id' => $module_id
            ]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Erreur getUserQuizResults: " . $e->getMessage());
            return [];
        }
    }

    
   public function markModuleCompleted($user_id, $module_id) {
    try {
        // On insère ou on met à jour si ça existe déjà
        // On force is_completed à 1 (TRUE)
        $query = "INSERT INTO user_progression (user_id, module_id, is_completed, score, completed_at) 
                  VALUES (:user_id, :module_id, 1, 100, NOW())
                  ON DUPLICATE KEY UPDATE 
                  is_completed = 1, 
                  score = GREATEST(score, 100), 
                  completed_at = NOW()";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'user_id' => $user_id,
            'module_id' => $module_id
        ]);
        
    } catch (PDOException $e) {
        error_log("Erreur markModuleCompleted: " . $e->getMessage());
        return false;
    }

}
}
