<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {

        $finder = new Finder();
        $finder->directories()->in('Pictures');

        return $this->render('home/index.html.twig', [
            'dossiers' => $finder,
        ]);
    }

    public function menu(): Response
    {

        $finder = new Finder();
        $finder->directories()->in('Pictures');

        return $this->render('home/_menu.html.twig', [
            'dossiers' => $finder,
        ]);
    }

}
