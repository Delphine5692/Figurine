<?php

namespace Figurine\Model;

class ContactMessage {
    // Chemin vers le fichier de stockage 
    private $file = 'data/contacts.json';
    
    /**
     * Sauvegarde un message de contact.
     */
    public function save($email, $message) {
        if (file_exists($this->file)) {
            $data = json_decode(file_get_contents($this->file), true) ?? [];
        } else {
            $data = [];
        }
        $data[] = [
            'email'     => $email,
            'message'   => $message,
            'timestamp' => time()
        ];
        file_put_contents($this->file, json_encode($data, JSON_PRETTY_PRINT));
        return true;
    }
    
    /**
     * Récupère tous les messages.
     */
    public function getMessages() {
        if (file_exists($this->file)) {
            return json_decode(file_get_contents($this->file), true) ?? [];
        }
        return [];
    }
}
?>