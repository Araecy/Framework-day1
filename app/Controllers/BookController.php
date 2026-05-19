<?php

namespace App\Controllers;

use Araecy\Framework\Http\Request;
use Araecy\Framework\Controllers\AbstractController;
use Araecy\Framework\Http\Response;
use App\Models\book;

class BookController extends AbstractController
{
    public function show(int $id): Response
    {
        return $this->render('book.html.twig', [
            'id' => $id
        ]);
    }

    public function create(): Response
    {
        return $this->render('create-book.html.twig');
    }

    public function store(): void
    {
        // dd($this->request);
        $book = new Book();
        $book->setType($this->request->getPostParams('type'));
        $book->setTitle($this->request->getPostParams('title'));
        $book->setContent($this->request->getPostParams('content'));

        dd($book);
    }
}