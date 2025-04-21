<?php

namespace Figurine\Controller;

use Figurine\Model\ContactMessage;
use Figurine\Lib\View;
use Figurine\Lib\FlashMessage;

class ContactController
{

    // Affiche le formulaire de contact
    public function showContact()
    {
        View::render('contact');
    }

    // Traite le formulaire de contact
    public function sendContact()
    {
        error_log("SendContact appelé");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $message = trim($_POST['message'] ?? '');

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                FlashMessage::addMessage('error', 'Adresse email invalide.');
                View::render('contact');
                exit();
            }
            if (empty($message)) {
                FlashMessage::addMessage('error', 'Veuillez saisir votre message.');
                View::render('contact');
                exit();
            }

            $contactModel = new ContactMessage();
            $contactModel->save($email, $message);

            FlashMessage::addMessage('success', 'Votre message a bien été envoyé.');
            View::render('contact');
            exit();
        }
    }
}
