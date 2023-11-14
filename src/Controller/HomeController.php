<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(Request $request): Response
    {

        // Ajouter un formulaire pour créer un dossier
        $form = $this->createFormBuilder()
            ->add('nom', TextType::class, ['label' => 'Nom du dossier',])
            ->add('ok', SubmitType::class, ['label' => 'OK',])
            ->getForm();

        // Gestion du retour de formulaire
        // 1 - Récupérer les données du formulaire
        $form->handleRequest($request);
        // 2 - Vérifier que le formulaire est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // 3 - Récupérer les données du formulaire
            $nomDuDossier = $form->getData()['nom'];
            // 4 - Créer le dossier
            $fs = new Filesystem();
            $fs->mkdir("Pictures/$nomDuDossier");
            // 5 - Rediriger vers la page d'accueil
            return $this->redirectToRoute('app_dossier', ['nomDuDossier' => $nomDuDossier]);
        }

        $finder = new Finder();
        $finder->directories()->in('Pictures');

        return $this->render('home/index.html.twig', [
            'dossiers' => $finder,
            'formulaire' => $form->createView(),
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
