<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Utilisateur non connecté']);
    exit;
}

require_once __DIR__ . '/../Config/Database.php';
require_once __DIR__ . '/../Models/QuizModel.php';

class QuizController {
    private $quizModel;
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->quizModel = new QuizModel($this->db);
    }
    
    public function handleRequest() {
        header('Content-Type: application/json');
        $action = $_GET['action'] ?? '';
        
        switch ($action) {
            case 'get_questions':
                $this->getQuizQuestions();
                break;
            case 'submit_quiz':
                $this->submitQuiz();
                break;
            default:
                echo json_encode(['error' => 'Action non reconnue']);
        }
    }
    
   private function getQuizQuestions() {
    if (!isset($_GET['module_id'])) {
        echo json_encode(['error' => 'Module ID manquant']);
        return;
    }

    $module_id = intval($_GET['module_id']);
    
    try {
        $questions = $this->quizModel->getQuizByModule($module_id);
        
        if (empty($questions)) {
            echo json_encode(['error' => 'Aucune question trouvée pour le module ID: ' . $module_id]);
            return;
        }
        echo json_encode($questions);
        
    } catch (Exception $e) {
        error_log("Erreur getQuizQuestions: " . $e->getMessage());
        echo json_encode(['error' => 'Erreur base de données: ' . $e->getMessage()]);
    }
}
  private function submitQuiz() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['error' => 'Méthode non autorisée']);
            return;
        }

        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        
        $user_id = $_SESSION['user_id'];
        $module_id = $data['module_id'] ?? null;
        $reponses = $data['reponses'] ?? [];

        if (!$module_id) {
            echo json_encode(['error' => 'Module ID manquant']);
            return;
        }

        try {
            $score = 0;
            $total = count($reponses);
            $results = [];

            foreach ($reponses as $quiz_id => $reponse_utilisateur) {
                $question = $this->quizModel->getQuizQuestion($quiz_id);
                if (!$question) continue;

                $is_correct = (strtoupper(trim($reponse_utilisateur)) === strtoupper(trim($question['reponse_correcte'])));
                if ($is_correct) $score++;

                $this->quizModel->saveUserAnswer($user_id, $quiz_id, $reponse_utilisateur, $is_correct);
                
                // C'EST ICI QUE CA CHANGE :
                // On prépare le feedback personnalisé
                $feedback = $is_correct 
                    ? ($question['feedback_succes'] ?? "Bravo, c'est correct !") 
                    : ($question['feedback_erreur'] ?? "Indice : La réponse se trouve dans la vidéo.");

                $results[] = [
                    'quiz_id' => $quiz_id,
                    'question' => $question['question'],
                    'reponse_utilisateur' => $reponse_utilisateur,
                    'is_correct' => $is_correct,
                    'feedback' => $feedback // On envoie l'indice ou le bravo
                    // On N'ENVOIE PAS 'reponse_correcte' pour ne pas l'afficher
                ];
            }

            $pourcentage = $total > 0 ? ($score / $total) * 100 : 0;
            $is_success = ($pourcentage >= 70); 

            if ($is_success) {
                $this->quizModel->markModuleCompleted($user_id, $module_id);
            }

            echo json_encode([
                'success' => true,
                'score' => $score,
                'total' => $total,
                'pourcentage' => $pourcentage,
                'is_success' => $is_success,
                'results' => $results
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['error' => 'Erreur lors de la soumission: ' . $e->getMessage()]);
        }
    }
}

$controller = new QuizController();
$controller->handleRequest();