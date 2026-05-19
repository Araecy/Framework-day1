<?php

namespace App\Controllers;

use App\Models\Account;
use Araecy\Framework\Controllers\AbstractController;
use Araecy\Framework\Http\Response;

class AuthController extends AbstractController
{
    private const ADMIN_USERNAME = 'admin';
    private const ADMIN_PASSWORD = '#1Geheim';

    public function create(): Response
    {
        return $this->render('login.html.twig');
    }

    public function store(): Response
    {
        $email      = trim($this->request->getPostParams('email'));
        $wachtwoord = $this->request->getPostParams('wachtwoord');

        if ($email === self::ADMIN_USERNAME && $wachtwoord === self::ADMIN_PASSWORD) {
            $_SESSION['is_admin'] = true;
            return Response::redirect('/admin');
        }

        $account = Account::findByEmail($email);

        if ($account === null || !password_verify($wachtwoord, $account->getWachtwoordHash())) {
            return $this->render('login.html.twig', [
                'error' => 'Ongeldig e-mailadres of wachtwoord.',
            ]);
        }

        $_SESSION['user_id'] = $account->getId();
        return Response::redirect('/profile');
    }

    public function destroy(): Response
    {
        session_destroy();
        return Response::redirect('/');
    }
}
