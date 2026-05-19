<?php

namespace App\Controllers;

use App\Models\Account;
use Araecy\Framework\Controllers\AbstractController;
use Araecy\Framework\Http\Response;

class HomeController extends AbstractController
{
    private const MAX_REGISTRATIONS = 1000;

    public function index(): Response
    {
        $taken = Account::count();
        return $this->render('home.html.twig', [
            'beschikbaar' => max(0, self::MAX_REGISTRATIONS - $taken),
            'totaal'      => self::MAX_REGISTRATIONS,
        ]);
    }

    public function test(): Response
    {
        return $this->render('test.html.twig');
    }
} 