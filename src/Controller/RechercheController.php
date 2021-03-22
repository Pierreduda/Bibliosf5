<?php

namespace App\Controller;

use App\Form\RechType;
use App\Repository\LivreRepository;
use App\Repository\AbonneRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RechercheController extends AbstractController
{
    /**
     * @Route("/recherche", name="recherche")
     */
    public function index(Request $rq, LivreRepository $lr): Response
    {

        /*
            $rq->query :$_GET
            $rq->request : $_POST
            $rq->cookies : $_COOKIE...
        
        */
        $mot = $rq->query->get("mot");
        $liste_livres = $lr->recherche($mot);
        $liste_indisponibles = $lr->findLivresIndisponibles();
        return $this->render('recherche/index.html.twig', compact("liste_livres", "mot"));
    }


    /**
     * @Route("/RechMultiple", name="recherche_multiple")
     */

    public function rechercheMultiple(Request $rq, AbonneRepository $ar, LivreRepository $lr)
    {

        $formRech = $this->createForm(RechType::class);
        $formRech->handleRequest($rq);
        if ($formRech->isSubmitted() && $formRech->isValid()) {
            $mot = $formRech->get("search")->getData();
            $liste_abonnes = $ar->recherche($mot);
            $liste_livres = $lr->recherche($mot);
            $liste_indisponibles = $lr->findLivresIndisponibles();
            $recherche = $formRech->createView();
            return $this->render('rechMultiple/index.html.twig', compact("liste_livres", "mot", "liste_abonnes", "liste_indisponibles", "recherche"));
        }
        return $this->render("rechMultiple/index.html.twig", [
            "formRech" => $formRech->createView() // createView (formCreate)
        ]);
    }


    // /**
    //  * @Route("/RechMultiple", name="recherche_multiple")
    //  */

    /*
    public function rechercheMultiple(Request $rq, AbonneRepository $ar, LivreRepository $lr){

        $formRech = $this->createForm(RechType::class);
        // verifie affichage 
        //$formSearch = $formRech->createView();
        //        return $this->render("rechMultiple/index.html.twig", [
        //    "formRech"=> $formRech->createView()
        $formRech->handleRequest($rq);
        if($formRech->isSubmitted() && $formRech->isValid())
        {
            $livres = $abonnes = $liste_indisponibles = null;

            $mot = $formRech->get("search")->getData(); 
            $liste_abonnes = $ar->recherche($mot);
            $liste_livres = $lr->recherche($mot);
            $liste_indisponibles = $lr->findLivresIndisponibles();
        }
        $form = $formRech->createView();
        return $this->render("rechMultiple/index.html.twig", compact("formRech","liste_livres", "mot", "liste_abonnes","liste_indisponibles")
        );
    }
*/
}
