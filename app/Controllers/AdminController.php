<?php

namespace App\Controllers;

use App\Models\Account;
use Araecy\Framework\Controllers\AbstractController;
use Araecy\Framework\Http\Response;

class AdminController extends AbstractController
{
    public function index(): Response
    {
        if (empty($_SESSION['is_admin'])) {
            return Response::redirect('/login');
        }

        $accounts = Account::getAll();

        return $this->render('admin.html.twig', [
            'accounts' => $accounts,
            'total'    => count($accounts),
        ]);
    }

    public function destroy(string $id): Response
    {
        if (empty($_SESSION['is_admin'])) {
            return Response::redirect('/login');
        }

        $account = Account::findById((int) $id);
        if ($account !== null) {
            $account->delete();
        }

        return Response::redirect('/admin');
    }

    public function approveTicket(string $id): Response
    {
        if (empty($_SESSION['is_admin'])) {
            return Response::redirect('/login');
        }

        $account = Account::findById((int) $id);
        if ($account !== null) {
            $account->setHasTicket(true);
            $account->save();
        }

        return Response::redirect('/admin');
    }
}
