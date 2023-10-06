<?php

namespace App\Controller;

use DateTime;
use App\Entity\Article;
use App\Form\ArticleType;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CreerArticleController extends AbstractController
{
    #[Route('dashboard/creer-article', name: 'app_creer_article')]
    public function index(EntityManagerInterface $manager, Request $request, UserInterface $user): Response
    {
        $article = new Article();
        //j'associe le formtype à mon article.
        $form_article = $this->createForm(ArticleType::class, $article);
        // on écoute le formulaire avec la classe Request -> ne pas oublier de l'importer de la classe!
        $form_article->handleRequest($request);
        // si le formulaire est soumis et si les données sont valides
        if($form_article->isSubmitted() &&  $form_article->isValid()){
           
            $slugify = new Slugify();
            $slug = $slugify->slugify($article->getTitreArticle());
             // fixer le slug
            $article->setSlugArticle('');
            // ajouter une date de création
            $article->setDateCreation(new DateTime());
            // ajouter l'id de l'utilisateur grace à UserInterface 
            $article->setIdUtilisateur($user);
            // mettre dans la mémoire
            $manager->persist($article);
            // envoyer en base de donnée
            $manager->flush();
            // rediriger vers la page en utilisant le name de la route
            return $this->redirectToRoute('app_creer_article');
        }

        return $this->render('creer_article/index.html.twig', [
            'controller_name' => $user->getNom() . '' . $user->getPrenom(),
            'form_article' => $form_article->createView()
        ]);
    }
}
