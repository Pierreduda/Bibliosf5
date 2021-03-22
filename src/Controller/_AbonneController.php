<?php

namespace App\Controller;

use App\Entity\Abonne;
use App\Form\AbonneType;
use App\Repository\AbonneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/", name="accueil")
 */

class _AbonneController extends AbstractController
{
    /**
     * @Route("/abonne", name="abonne")
     */
    public function index(AbonneRepository $abonneRepository): Response
    {
        $liste_abonnes = $abonneRepository->findAll();
        return $this->render('abonne/index.html.twig',  compact("liste_abonnes"));
    }

    /**
     * @Route("/abonne/ajouter", name="abonne_ajouter")
     */
    public function ajouter(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $abonne = new Abonne;
        $formAbonne = $this->createForm(AbonneType::class, $abonne);

        $formAbonne->handleRequest($request);
        if ($formAbonne->isSubmitted() && $formAbonne->isValid()) {

            /*
            $password = $abonne->getPassword(); // on recupere le mdp
            dd($abonne);
            dump($password);
            */
            $password = $encoder->encodePassword($abonne, $abonne->getPassword());
            //dump($password);

            // recupere $password encoder, pour reinjecter dans l'obj abonne
            $abonne->setPassword($password);




            $em->persist($abonne);
            $em->flush();
            $this->addFlash("success", "Le nouveau abonne a bien été enregistré");
            return $this->redirectToRoute("abonne");
        }
        return $this->render("abonne/ajouter.html.twig", [
            "formAbonne" => $formAbonne->createView()
        ]);
    }

    /**
     * @Route("/abonne/modifier/{id}", name="abonne_modifier", requirements={"id"="\d+"})
     */
    public function modifier(AbonneRepository $lr, Request $rq, EntityManagerInterface $em,UserPasswordEncoderInterface $encoder, $id)
    {
        $abonne = $lr->find($id);
        $formAbonne = $this->createForm(AbonneType::class, $abonne);
        $formAbonne->handleRequest($rq);
        if ($formAbonne->isSubmitted() && $formAbonne->isValid()) {

            /* 
            $password = $abonne->getPassword(); // on recupere le mdp
            //dump($abonne);
            //dump($password);
            */
            $password = $encoder->encodePassword($abonne, $abonne->getPassword());
            //dd($password);

            // recupere $password encoder, pour reinjecter dans l'obj abonne
            $abonne->setPassword($password);


            $em->flush();
            $this->addFlash("success", "La modification a bien été prise en compte");
            return $this->redirectToRoute("abonne");
        }
        return $this->render("abonne/ajouter.html.twig", [
            "formAbonne" => $formAbonne->createView() // createView (formCreate)
        ]);
    }

    /**
     * @Route("/abonne/supprimer/{id}", name="abonne_supprimer", requirements={"id"="\d+"})
     */

    public function supprimer(Request $rq, EntityManagerInterface $em, Abonne $abonne)
    {
        //dd($abonne);
        //dump($abonne);
        if ($rq->isMethod("POST")) {
            $em->remove($abonne); // methode remove() prepare une requete DELETE
            $em->flush();
            $this->addFlash("success", "La suppression a bien été supprimée");
            return $this->redirectToRoute("abonne");
        }

        return $this->render("abonne/supprimer.html.twig", compact("abonne"));
    }

    /**
     * @Route("/abonne/affichage/{id}", name="affichage", requirements={"id"="\d+"})
     */
    public function detailLivre(Abonne $abonne)
    {
        return $this->render("abonne/affichage.html.twig", compact("abonne"));
    }
}
