<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ActivityViewController extends AbstractController
{
    /**
     * Esta es la ruta que abrirás en el navegador para usar la APP
     */
    #[Route('/activities', name: 'app_activity_view')]
    public function index(): Response
    {
        return $this->render('activity/index.html.twig');
    }
}