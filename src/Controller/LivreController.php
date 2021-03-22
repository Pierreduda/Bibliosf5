<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Form\LivreType;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 *@Route("/admin")
 *
 *Toutes les routes de ce controleur vont commencer par "/admin"   ==> 
 *voir config/package/security.yaml
 */

class LivreController extends AbstractController
{
    /**
     * @Route("/livre", name="livre")
     * 
     */
    public function index(LivreRepository $livreRepository): Response
    {
        $liste_livres = $livreRepository->findAll();
        $liste_indisponibles = $livreRepository->findLivresIndisponibles();
        return $this->render('livre/index.html.twig', compact("liste_livres", "liste_indisponibles"));
    }

    /**
     * @Route("/livre/ajouter", name="livre_ajouter")
     * 
     */
    public function ajouter(Request $request, EntityManagerInterface $em)
    {
        //dd($_FILES);
        $livre = new Livre;
        /* je crée un obj $formlIVRE  avec la methode createForm qui va representer le formulaire genere grace à la class livreType ce formulaire est lié à l'obj $livre*/
        $formLivre = $this->createForm(LivreType::class, $livre);

        /* avec la methode "handleRequest",le $formLivre va gener les données qui viennent du formulaire on va aussi pv savoir si le formulaire a été soumis et si il est valide */
        $formLivre->handleRequest($request);
        if( $formLivre->isSubmitted() && $formLivre->isValid()){

            $fichier = $formLivre->get("couverture")->getData(); //pour recuperer les info du fichier uploadé
            if($fichier){
                /* pathinfo: fonction qui permet de recuperer des info sur le fichier, par exp le nom du fichier sans le chemin complet cad sans l'extention et getClientOriginalName(): recupere le nom du fichier uploadé (methode est exécuté à partir de l'obj instancié par getData() que l'on a exécuté sur l'obj formulaire) */
                $nomFichier = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                $nomFichier .= "_" . time();
                $nomFichier .= "." . $fichier->guessExtension();
                $nomFichier = str_replace(" ", "_", $nomFichier);
                $destination = $this->getParameter("dossier_images"). "livres"; // voir config/services.yaml
                /* la methode move va copier le fichier uploadé dans le dossier $destination avec le $nomFichier*/
                $fichier->move($destination, $nomFichier);
                $livre->setCouverture($nomFichier); // depose dans $livre la couverture 
            }

            $em->persist($livre); // la méthode persist() prépare la requete INSERT INTO à partir de l'objet passé en parametre
            $em->flush(); // methode flush exécute les requetes en attente
            //$message ="Le nouveau livre a bien été enregistré";
            $this->addFlash("success", "Le nouveau livre a bien été enregistré");
            return $this->redirectToRoute("livre");
        }
        return $this->render("livre/ajouter.html.twig", [
            "formLivre"=> $formLivre->createView() // createView (formCreate)
        ]);
    }

    /**
     * @Route("/livre/modifier/{id}", name="livre_modifier", requirements={"id"="\d+"})
     */
    public function modifier(LivreRepository $lr,Request $rq, EntityManagerInterface $em, $id)
    {
        $livre = $lr->find($id);
        $formLivre = $this->createForm(LivreType::class, $livre);
        $formLivre->handleRequest($rq);
        if($formLivre->isSubmitted() && $formLivre->isValid())
        {
            $fichier = $formLivre->get("couverture")->getData(); 
            if($fichier){
                $nomFichier = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                $nomFichier .= "_" . time();
                $nomFichier .= "." . $fichier->guessExtension();
                $nomFichier = str_replace(" ", "_", $nomFichier);
                $destination = $this->getParameter("dossier_images"). "livres"; 
                $fichier->move($destination, $nomFichier);

                $ancienFichier = $this->getParameter("dossier_images"). "livres/". $livre->getCouverture();
                if(file_exists($ancienFichier) && $livre->getCouverture()){
                    unlink($ancienFichier);
                }
                $livre->setCouverture($nomFichier); // depose dans $livre la couverture 
            }
            //$em->persist($livre); // dès qu'1 objet entity a 1 id non null, l'EntityManager $em va mettre la BDD à jour
            // avec les informations de cet objet quand la méthode flush sera exécutée
            $em->flush();
            $this->addFlash("success", "La modification a bien été prise en compte");
            return $this->redirectToRoute("livre");
        }
        return $this->render("livre/ajouter.html.twig", [
            "formLivre"=> $formLivre->createView() // createView (formCreate)
        ]);
    }

    /**
     * @Route("/livre/supprimer/{id}", name="livre_supprimer", requirements={"id"="\d+"})
     */

     // EntityManagerInterface = fait les 3 requetes UPDATE/DELETE/INSERT INTO  != LivreRepository fait requete SELECT*FROM ==>affichage
    public function supprimer(Request $rq, EntityManagerInterface $em, Livre $livre)
    {
        //dd($livre);
        //dump($livre);
        if( $rq->isMethod("POST")){

            $nomFichier = $livre->getCouverture();

            $em->remove($livre); // methode remove() prepare une requete DELETE
            $em->flush();

            $ancienFichier = $this->getParameter("dossier_images"). "livres/". $nomFichier;
            if(file_exists($ancienFichier) && $livre->getCouverture()){
                unlink($ancienFichier);
            }

            $this->addFlash("success", "La suppression a bien été supprimée");
            return $this->redirectToRoute("livre");
        }

        return $this->render("livre/supprimer.html.twig", compact("livre"));
    }

    //exo : 
    //Route pour afficher un livre: livre_afficher
    // ajouter un lien sur la liste des livres

    /**
     * @Route("/livre/detailLivre/{id}", name="detailLivre", requirements={"id"="\d+"})
     */
    public function detailLivre(Livre $livre)
    {
        return $this->render("livre/detailLivre.html.twig", compact("livre"));
       
    }

   
   
}
