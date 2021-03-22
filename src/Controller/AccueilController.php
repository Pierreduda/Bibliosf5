<?php

namespace App\Controller;
 
use App\Repository\LivreRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(LivreRepository $livreRepository): Response
    {
        $liste_livres = $livreRepository->findAll();
        $liste_indisponibles = $livreRepository->findLivresIndisponibles();
        return $this->render('accueil/index.html.twig', compact("liste_livres", "liste_indisponibles"));
    }
}
