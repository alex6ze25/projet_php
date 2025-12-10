<?php
class GoogleUserModel {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    // Vérifier si l'utilisateur existe déjà via son ID Google
    public function checkUserByGoogleId($googleId) {
        $query = "SELECT * FROM users WHERE google_id = :google_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['google_id' => $googleId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Vérifier si l'email existe déjà (cas d'un utilisateur classique qui se connecte via Google ensuite)
    public function checkUserByEmail($email) {
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lier un compte Google à un email existant
    public function linkGoogleToEmail($email, $googleId, $avatar) {
        $query = "UPDATE users SET google_id = :google_id, avatar = :avatar WHERE email = :email";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['google_id' => $googleId, 'avatar' => $avatar, 'email' => $email]);
    }

    // Créer un nouvel utilisateur Google
    public function createGoogleUser($userInfo) {
        $query = "INSERT INTO users (nom, prenom, email, google_id, avatar, password) 
                  VALUES (:nom, :prenom, :email, :google_id, :avatar, NULL)";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'nom' => $userInfo['familyName'],
            'prenom' => $userInfo['givenName'],
            'email' => $userInfo['email'],
            'google_id' => $userInfo['id'],
            'avatar' => $userInfo['picture']
        ]);
        
        return $this->db->lastInsertId();
    }
}