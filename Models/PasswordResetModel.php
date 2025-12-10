<?php
class PasswordResetModel {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    // Créer un token pour un email
    public function createToken($email) {
        // 1. On supprime les anciennes demandes pour cet email
        $stmt = $this->db->prepare("DELETE FROM password_resets WHERE email = :email");
        $stmt->execute(['email' => $email]);

        // 2. Générer un token unique
        $token = bin2hex(random_bytes(32)); // 64 caractères hexadécimaux
        $expireAt = date('Y-m-d H:i:s', strtotime('+1 hour')); // Expire dans 1h

        // 3. Insérer
        $query = "INSERT INTO password_resets (email, token, expire_at) VALUES (:email, :token, :expire)";
        $stmt = $this->db->prepare($query);
        
        if ($stmt->execute(['email' => $email, 'token' => $token, 'expire' => $expireAt])) {
            return $token;
        }
        return false;
    }

    // Vérifier si le token est valide
    public function verifyToken($token) {
        $query = "SELECT * FROM password_resets WHERE token = :token AND expire_at > NOW()";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['token' => $token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Supprimer le token après utilisation
    public function deleteToken($email) {
        $stmt = $this->db->prepare("DELETE FROM password_resets WHERE email = :email");
        $stmt->execute(['email' => $email]);
    }

    // Mettre à jour le mot de passe de l'user
    public function updateUserPassword($email, $newPassword) {
        // Hachage sécurisé du mot de passe
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $stmt = $this->db->prepare("UPDATE users SET password = :pass WHERE email = :email");
        return $stmt->execute(['pass' => $hashed, 'email' => $email]);
    }
}