<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VueArticleController extends AbstractController
{
    #[Route('/vue/article/{id}', name: 'app_vue_article')]
    public function index(ArticleRepository $repoArticle, $id): Response
    {

        $article = $repoArticle->find($id);

        return $this->render('vue_article/index.html.twig', [
            'controller_name' => 'VueArticleController',
            'article' => $article
        ]);
    }
}
