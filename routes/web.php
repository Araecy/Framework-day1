<?php

use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\BookController;
use App\Controllers\FanController;
use App\Controllers\HomeController;
use App\Controllers\RegistrationController;

return [
    ['GET', '/', [HomeController::class, 'index']],
    ['GET', '/books/{id:\d+}', [BookController::class, 'show']],
    ['GET', '/books/create', [BookController::class, 'create']],
    ['POST', '/books', [BookController::class, 'store']],

    ['GET', '/register', [RegistrationController::class, 'create']],
    ['POST', '/register', [RegistrationController::class, 'store']],

    ['GET', '/login', [AuthController::class, 'create']],
    ['POST', '/login', [AuthController::class, 'store']],
    ['POST', '/logout', [AuthController::class, 'destroy']],

    ['GET', '/profile', [FanController::class, 'show']],
    ['POST', '/profile/update', [FanController::class, 'update']],
    ['POST', '/profile/delete', [FanController::class, 'destroy']],

    ['GET', '/admin', [AdminController::class, 'index']],
    ['POST', '/admin/accounts/{id:\d+}/delete', [AdminController::class, 'destroy']],
];
