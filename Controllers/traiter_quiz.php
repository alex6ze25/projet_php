<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: afficher_inscription.php');
    exit;
}

require_once __DIR__ . '/../Models/QuizModel.php';
require_once __DIR__ . '/../Models/ProgressionModel.php';

class QuizController {
    private $quizModel;
    private $progressionModel;
    private $db;
    
    public function __construct() {
        $this->connectDB();
        $this->quizModel = new QuizModel($this->db);
        $this->progressionModel = new ProgressionModel($this->db);
    }
    
    private function connectDB() {
        try {
            $this->db = new PDO(
                "mysql:host=localhost;dbname=elearning;charset=utf8mb4",
                "root",
                ""
            );
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Erreur de connexion: " . $e->getMessage());
        }
    }
    
    public function processQuiz() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $moduleId = $_POST['module_id'];
            $quizId = $_POST['quiz_id'];
            $userAnswer = trim($_POST['user_answer']);
            
            // Vérifier la réponse
            $isCorrect = $this->quizModel->verifyAnswer($quizId, $userAnswer);
            
            if ($isCorrect) {
                // Marquer le module comme complété
                $this->progressionModel->completeModule($userId, $moduleId, 100);
                header('Location: afficher_modules.php?theme=cyberharcelement&message=module_reussi');
            } else {
                header('Location: afficher_modules.php?module_id=' . $moduleId . '&message=erreur_quiz');
            }
            exit;
        } else {
            header('Location: afficher_modules.php?theme=cyberharcelement');
            exit;
        }
    }
}

$controller = new QuizController();
$controller->processQuiz();
