<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Entity\Abonne;
use App\Entity\Emprunt;
use App\Form\EmpruntType;
use App\Repository\AbonneRepository;
use App\Repository\EmpruntRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ProfilController extends AbstractController
{
    /**
     * @Route("/profil", name="profil")
     */
    public function index(): Response
    {
        /* recuper l'utilisateur connecté, on peut utilser la methode getUser
        $utilisateurConnecte = $this->getUser()
        Mais on peut aussi le récuper directement dans un fichier 
        */
        return $this->render('profil/index.html.twig');
    }

    /**
     * @Route("/profil/emprunter/{id}", name="emprunter_livre")
     */
    public function emprunter(Request $request, EntityManagerInterface $em, Livre $livre): Response
    {
        $emprunt = new Emprunt;
        $emprunt->setAbonne($this->getUser());
        $emprunt->setLivre($livre);
        $now = new DateTime();
        $emprunt->setDateEmprunt($now);

        $em->persist($emprunt);
        $em->flush();

        $this->addFlash("success", "emprunt bien enregisté");
        return $this->redirectToRoute("profil");
    }

}
