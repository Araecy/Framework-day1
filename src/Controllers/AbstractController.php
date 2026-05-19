<?php

namespace Araecy\Framework\Controllers;

use Araecy\Framework\Http\Request;
use Araecy\Framework\Http\Response;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class AbstractController
{
    protected ?Request $request = null;
    public function render(string $template, ?array $vars = []): Response
    {
        $loader = new FilesystemLoader(BASE_PATH . '/views/');
        $twig   = new Environment($loader);

        $vars = array_merge([
            'is_logged_in' => !empty($_SESSION['user_id']),
            'is_admin'     => !empty($_SESSION['is_admin']),
        ], $vars ?? []);

        $content = $twig->render($template, $vars);

        return new Response($content);
    }

    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }
}