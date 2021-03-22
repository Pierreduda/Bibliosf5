<?php

namespace App\Controller;

use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     * 
     * une route est une correspondance entre URL et une méthode d'1 controleur
     * par exp, qd ds la barre d'adresse de navigateur, apres le 'nom de domaine',
     * il y '/test', la methode qui sera execute sera la methode index() de TestController
     * symfony utilise les annotations pour definir les routes. 
     * la fonction @Route a un parametre obligatoire: l'url relative de la route
     * 
     * Affichage de la page test
     */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/TestController.php',
        ]);
    }

    /**
     * @Route("/test/calcul/{a}/{b}", requirements={"b"="\d+","a"="[0-9]+"})
     * 
     * j'utilse les expressions regulierres (regex) pour obliger à ce que les parametres de la routes soit uniquement composés de chiffres:  \d ou [0-9]  veut dire un caractere numeriq
     *                                       +           veut dire le caractere preceddent doit etre present au moins 1 fois                       
     */
    public function calcul($a,$b){
        //$a = 5;
        //$b = 6;
        $resultat = $a + $b;

        //return $this->json([
        //    "calcul"=>"$a + $b",
        //    "resultat"=>$resultat
        //]);

        /*
         la methode render construit l'affichage. le 1er parametre est le nom de la vue à utiliser.
         le nom de la vue est le chemein du fichier à partir du dossier "templates"
        */
        return $this->render("/test/calcul.html.twig",[
            "calcul"=>"$a + $b",
            "result"=>$resultat,
            "a"=>$a,
            "b"=>$b
        ]);
        /* exo: 1.affichez le resultat du calcul 5+6 est egal à 11 */
        /*      2.modifier la route et la methode pour que la valeur $b soit recupere dans l'url ne doit plus utiliser "base.thml.twig"
                3. la route "calcul" ne doit plus utiliser "base.html.twig" pour son affichage et si "base.html.twig" est utilisé dans 1 render, on ne doit pas etre obligé d'envoyer des variables pour qu'elle s'affiche
        */
    }

    /**
     * @Route("/test/salutation/{prenom}")
     * une route parametrée permet de récuperer 1 valeur dans l'URL. L'URL n'est pas fixe, la valeur de parametre peut changer
     * 
     */
    public function salut($prenom)
    {
        //$prenom = "Jean";
        return $this->render("test/salut.html.twig", [
            "prenom"=>$prenom
            
            ]);
    }

    /**
     * @Route("/test/tableau")
     */
    public function tableau()
    {
        $tab = ["nom"=>"Cérien", "prenom"=>"jean"];
        return $this->render("test/tableau.html.twig", ["tableau"=>$tab]);
    }

     /**
     * @Route("/test/objet")
     */
    public function objet()
    {
        $objet = new \stdClass;
        $objet->nom = "Mentor";
        $objet->prenom = "Gérard";
        return $this->render("test/tableau.html.twig", ["tableau"=>$objet]);
    }

    /**
     * @Route("/test/boucles")
     */
    public function boucles()
    {
        $tableau = ["bjr","je","suis","en","cours","de","symfony"];
        $chiffres =[];
        for($i=0; $i<10;$i++){
            $chiffres[] = $i *12;
        }
        return $this->render("test/boucles.html.twig",[
            "chiffres"=> $chiffres,
            "tableau"=> $tableau

        ]);
    }

    /**
     * @Route("/test/condition")
     */
    public function condition()
    {
        $a = 12;
        return $this->render("test/condition.html.twig", [
            "a"=>$a,
            //"b"=> ""
        ]);
    }

    /* EXO: 
    1.créer un controlleur Acceuil avec une route qui va afficher "la bibliotheque est vide pour l'instant"
    2. la route doit correspondre à la racine du site*/


    /*3. Dans le contrôleur Test, ajouter 2 routes : 
    une route (/test/affiche-formulaire) qui affiche un formulaire html (POST) 
    l'autre (/test/affiche-donnees) qui affiche les données tapées dans ce formulaire (avec $_POST)*/

    /**
     * @Route("/test/affichage-formulaire", name="test_affichage_formulaire")
     */
    public function formulaire()
    {
        return $this->render("test/affichage-formulaire.html.twig");
    }

    /**
     * @Route("/test/affichage-donnees", name="test_affichage_donnees") 
     */
    public function afficheDonnees()
    {
        /* if(!empty($_POST)) revient à dire la ligne endessous if($_POST) = $_POST non vide
        if($_POST){
            extract($_POST); //extract() va créer autant de variables qu'il y a d'indices dans 1 tableau associatif
            return $this->render("test/affichage-donnees.html.twig", compact(""))

        }*/

        return $this->render("test/affichage-donnees.html.twig", [
            "post" => $_POST

        ]);
    }

     /**
     * @Route("/test/donnees", name="test_donnees") 
     * 
     * attention ==> changer aussi url sinon conflit avec celui d'audessus
     * 
     * On ne peut pas instancier un objet de la classe Request, donc pour pouvoir l'utiliser, on va utiliser ce qu'on appelle l'injection de dépendance (vous verrez aussi parfois : autowiring) : en passant par les 
     * paramètres d'une méthode d'un contrôleur, l'objet de la classe est 
     * automatiquement instancié et remplit (si besoin)
     * Les classes que l'on peut utiliser avec l'injection de dépendances sont
     * appelés des services (dans Symfony)
     * 
     * la classe Request contient ttes les valeurs des variables
     * superglobales de PHP, et qq info supplementaires concernant la requete HTTP 
     * $_
     */
    public function affichageDonnees(Request $request){
        dump($request);
        //dd($request); // Dump and Die :  arrete l'execution du php apres la var_dump

        if($request->isMethod("POST")){
            $email = $request->request->get("email"); 
            // l'obj $request a une propriete 'request' qui contient $_POST
            // cette propriete est un objet qui a une methode get() pour récuperer
            // une valeur
            // pour le contenu de $_GET, on utilisera, de la meme facon, la propriete 'query' de l'objet $request (remplacer  $mdp = $request->request->get("mdp");  par    $mdp = $request->query->get("mdp");)
            $mdp = $request->request->get("mdp");
            $adresse = $request->request->get("adresse");
            $ville = $request->request->get("ville");
            $cp = $request->request->get("cp");
            return $this->render("test/affichage-donnees.html.twig", compact("email","mdp","adresse","ville","cp"));
        }
    }
}
