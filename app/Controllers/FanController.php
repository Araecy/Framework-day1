<?php

namespace App\Controllers;

use App\Models\Account;
use Araecy\Framework\Controllers\AbstractController;
use Araecy\Framework\Http\Response;

class FanController extends AbstractController
{
    public function show(): Response
    {
        if (empty($_SESSION['user_id'])) {
            return Response::redirect('/login');
        }

        $account = Account::findById((int) $_SESSION['user_id']);
        if ($account === null) {
            session_destroy();
            return Response::redirect('/login');
        }

        return $this->render('profile.html.twig', ['account' => $account]);
    }

    public function update(): Response
    {
        if (empty($_SESSION['user_id'])) {
            return Response::redirect('/login');
        }

        $account = Account::findById((int) $_SESSION['user_id']);
        if ($account === null) {
            session_destroy();
            return Response::redirect('/login');
        }

        $naam           = trim($this->request->getPostParams('naam'));
        $adres          = trim($this->request->getPostParams('adres'));
        $woonplaats     = trim($this->request->getPostParams('woonplaats'));
        $telefoonnummer = trim($this->request->getPostParams('telefoonnummer'));
        $geboortedatum  = trim($this->request->getPostParams('geboortedatum'));
        $geslacht       = trim($this->request->getPostParams('geslacht'));

        $errors = [];

        if (empty($naam))           $errors[] = 'Naam is verplicht.';
        if (empty($adres))          $errors[] = 'Adres is verplicht.';
        if (empty($woonplaats))     $errors[] = 'Woonplaats is verplicht.';
        if (empty($telefoonnummer)) $errors[] = 'Telefoonnummer is verplicht.';
        if (empty($geboortedatum))  $errors[] = 'Geboortedatum is verplicht.';
        if (!in_array($geslacht, ['man', 'vrouw', 'anders'])) $errors[] = 'Kies een geldig geslacht.';

        if (!empty($errors)) {
            return $this->render('profile.html.twig', ['account' => $account, 'errors' => $errors]);
        }

        $account->setNaam($naam);
        $account->setAdres($adres);
        $account->setWoonplaats($woonplaats);
        $account->setTelefoonnummer($telefoonnummer);
        $account->setGeboortedatum($geboortedatum);
        $account->setGeslacht($geslacht);
        $account->save();

        return $this->render('profile.html.twig', [
            'account' => $account,
            'success' => 'Je gegevens zijn opgeslagen.',
        ]);
    }

    public function destroy(): Response
    {
        if (empty($_SESSION['user_id'])) {
            return Response::redirect('/login');
        }

        $account = Account::findById((int) $_SESSION['user_id']);
        if ($account !== null) {
            $account->delete();
        }

        session_destroy();
        return Response::redirect('/');
    }
}
