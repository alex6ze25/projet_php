<?php
class UserModel {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    public function createUser($userData) {
    try {
        // Vérifier si l'email existe déjà
        $checkQuery = "SELECT id FROM users WHERE email = :email";
        $checkStmt = $this->db->prepare($checkQuery);
        $checkStmt->execute(['email' => $userData['email']]);
        
        if ($checkStmt->fetch()) {
            return ['success' => false, 'message' => 'Cet email est déjà utilisé'];
        }
        
        // Hasher le mot de passe
        $hashedPassword = password_hash($userData['password'], PASSWORD_DEFAULT);
        
        // Insérer l'utilisateur
        $query = "INSERT INTO users (nom, prenom, email, password) 
                 VALUES (:nom, :prenom, :email, :password)";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'nom' => $userData['nom'],
            'prenom' => $userData['prenom'],
            'email' => $userData['email'],
            'password' => $hashedPassword
        ]);
        
        // Récupérer l'ID de l'utilisateur créé
        $user_id = $this->db->lastInsertId();
        
        return [
            'success' => true, 
            'message' => 'Inscription réussie !',
            'user_id' => $user_id
        ];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur lors de l\'inscription'];
    }
}
 public function verifyUser($email, $password) {
        try {
            // Récupérer l'utilisateur par email
            $query = "SELECT id, nom, prenom, email, password FROM users WHERE email = :email";
            $stmt = $this->db->prepare($query);
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user) {
                return ['success' => false, 'message' => 'Email ou mot de passe incorrect'];
            }
            
            // Vérifier le mot de passe
            if (password_verify($password, $user['password'])) {
                // Retourner les infos utilisateur (sans le mot de passe)
                unset($user['password']);
                return [
                    'success' => true, 
                    'message' => 'Connexion réussie !',
                    'user' => $user
                ];
            } else {
                return ['success' => false, 'message' => 'Email ou mot de passe incorrect'];
            }
            
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erreur lors de la connexion'];
        }
    }
}
