<?php

namespace Figurine\Lib;

class FlashMessage {

    /**
     * Ajoute un message flash à la session.
     *
     * - Vérifie si le tableau 'flash_messages' existe dans la session.
     * - Si non, l'initialise en tant que tableau.
     * - Ajoute un nouveau tableau associatif contenant le type et le message.
     *
     * @param string $type Le type de message (par exemple, 'success', 'error', etc.).
     * @param string $message Le message à afficher.
     * @return void
     */
    public static function addMessage(string $type, string $message): void {
        // Si le tableau des messages flash n'existe pas, l'initialise.
        if (!isset($_SESSION['flash_messages'])) {
            $_SESSION['flash_messages'] = [];
        }
        // Ajoute le nouveau message flash au tableau.
        $_SESSION['flash_messages'][] = ['type' => $type, 'message' => $message];
    }

    /**
     * Récupère et vide les messages flash stockés dans la session.
     *
     * - Récupère le tableau 'flash_messages' s'il existe, sinon retourne un tableau vide.
     * - Supprime ensuite les messages stockés dans la session pour éviter de les afficher à nouveau.
     *
     * @return array Un tableau contenant tous les messages flash.
     */
    public static function getMessages(): array {
        // Récupère les messages flash de la session ou un tableau vide si non définis.
        $messages = $_SESSION['flash_messages'] ?? [];
        // Supprime les messages flash de la session après récupération.
        unset($_SESSION['flash_messages']);
        return $messages;
    }
}