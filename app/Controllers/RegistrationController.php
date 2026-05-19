<?php

namespace App\Controllers;

use App\Models\Account;
use Araecy\Framework\Controllers\AbstractController;
use Araecy\Framework\Http\Response;

class RegistrationController extends AbstractController
{
    private const MAX_REGISTRATIONS = 1000;

    public function create(): Response
    {
        if (!empty($_SESSION['user_id'])) {
            return Response::redirect('/profile');
        }
        if (!empty($_SESSION['is_admin'])) {
            return Response::redirect('/admin');
        }
        return $this->render('register.html.twig');
    }

    public function store(): Response
    {
        if (!empty($_SESSION['user_id'])) {
            return Response::redirect('/profile');
        }
        if (!empty($_SESSION['is_admin'])) {
            return Response::redirect('/admin');
        }

        if (Account::count() >= self::MAX_REGISTRATIONS) {
            return $this->render('register.html.twig', [
                'errors' => ['Inschrijving gesloten: het maximumaantal van 1.000 is bereikt.'],
            ]);
        }

        $naam               = trim($this->request->getPostParams('naam'));
        $adres              = trim($this->request->getPostParams('adres'));
        $woonplaats         = trim($this->request->getPostParams('woonplaats'));
        $telefoonnummer     = trim($this->request->getPostParams('telefoonnummer'));
        $email              = trim($this->request->getPostParams('email'));
        $geboortedatum      = trim($this->request->getPostParams('geboortedatum'));
        $geslacht           = trim($this->request->getPostParams('geslacht'));
        $wachtwoord         = $this->request->getPostParams('wachtwoord');
        $wachtwoord_herhaal = $this->request->getPostParams('wachtwoord_herhaal');

        $old = compact('naam', 'adres', 'woonplaats', 'telefoonnummer', 'email', 'geboortedatum', 'geslacht');

        $errors = [];

        if (empty($naam))           $errors[] = 'Naam is verplicht.';
        if (empty($adres))          $errors[] = 'Adres is verplicht.';
        if (empty($woonplaats))     $errors[] = 'Woonplaats is verplicht.';
        if (empty($telefoonnummer)) $errors[] = 'Telefoonnummer is verplicht.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Vul een geldig e-mailadres in.';
        if (empty($geboortedatum))  $errors[] = 'Geboortedatum is verplicht.';
        if (!in_array($geslacht, ['man', 'vrouw', 'anders'])) $errors[] = 'Kies een geldig geslacht.';
        if (strlen($wachtwoord) < 8) $errors[] = 'Wachtwoord moet minimaal 8 tekens lang zijn.';
        if ($wachtwoord !== $wachtwoord_herhaal) $errors[] = 'Wachtwoorden komen niet overeen.';

        if (!empty($errors)) {
            return $this->render('register.html.twig', ['errors' => $errors, 'old' => $old]);
        }

        if (Account::findByEmail($email) !== null) {
            return $this->render('register.html.twig', [
                'errors' => ['Dit e-mailadres is al geregistreerd.'],
                'old'    => $old,
            ]);
        }

        $account = new Account(
            naam: $naam,
            email: $email,
            adres: $adres,
            woonplaats: $woonplaats,
            telefoonnummer: $telefoonnummer,
            geboortedatum: $geboortedatum,
            geslacht: $geslacht,
            wachtwoord_hash: password_hash($wachtwoord, PASSWORD_BCRYPT),
        );
        $account->save();

        $_SESSION['flash_registration'] = $email;
        return Response::redirect('/login');
    }
}
