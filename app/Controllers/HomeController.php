<?php

namespace App\Controllers;

use Araecy\Framework\Controllers\AbstractController;
use Araecy\Framework\Http\Response;

class HomeController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('home.html.twig');
    }
} 