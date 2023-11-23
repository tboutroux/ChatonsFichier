<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DossierController extends AbstractController
{
    #[Route('/chatons/{nomDuDossier}', name: 'app_dossier')]
    public function index($nomDuDossier, Request $request): Response
    {

        $chemin = "Pictures/$nomDuDossier";

        // On vérirife que le dossier existe
        $fs = new Filesystem();
        if (!$fs->exists($chemin)) {
            // Je renvoie une erreur 404
            throw $this->createNotFoundException("Le dossier $nomDuDossier n'existe pas");
        }

        // Je vais créer un formulaire pour ajouter de nouvelles images
        $form = $this->createFormBuilder()
            ->add('images', FileType::class, ['label' => 'Ajouter des images', 'multiple' => true, 'mapped' => false,])
            ->add('submit', SubmitType::class, ['label' => 'Ajouter',])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Je récupère les images
            $images = $form->get('images')->getData();
            // Je les déplace dans le dossier
            foreach ($images as $image) {
                $image->move($chemin, $image->getClientOriginalName());
            }
            $this->addFlash('success', 'Les images ont bien été ajoutées');
            // Je redirige vers la page du dossier
            return $this->redirectToRoute('app_dossier', ['nomDuDossier' => $nomDuDossier]);
        }

        // Je vais constituer le modèle à envoyer à la vue
        $finder = new Finder();
        $finder->files()->in($chemin);

        return $this->render('dossier/index.html.twig', [
            'nomDuDossier' => $nomDuDossier,
            'fichiers' => $finder,
            'formulaire' => $form->createView(),
        ]);
    }
}
