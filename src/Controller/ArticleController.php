<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/article", name: "article_")]
class ArticleController extends AbstractController
{

    private EntityManagerInterface $em;
    private UserRepository $userRepository;

    public function __construct(EntityManagerInterface $em, UserRepository $userRepo){
        $this->userRepository = $userRepo;
        $this->em = $em;
    }

    /**
     * Article add formulaire render
     * @param Request $req
     * @return Response
     */
    #[Route("/add", name: "add")]
    #[IsGranted("ROLE_AUTHOR")]
    public function add(Request $req): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()){
            $article->setUser($this->userRepository->find($this->getUser()->getId()));
            $this->em->persist($article);
            $this->em->flush();
        }

        return $this->render("article/add.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * Single article render
     * @param Article $article
     * @param Request $req
     * @return Response
     */
    #[Route("/single/{id}", name: "single")]
    public function single(Article $article, Request $req){
        $form = false;
        if($this->getUser()){
            $comment = new Comment();
            $comment
                ->setArticle($article)
                ->setUser($this->userRepository->find($this->getUser()->getId()));

            $form = ($this->createForm(CommentType::class, $comment));
            $form->handleRequest($req);

            if($form->isSubmitted() && $form->isValid()){
                $this->em->persist($comment);
                $this->em->flush();
            }

            $form = $form->createView();

        }
        return $this->render("article/expanded.html.twig", [
            "article" => $article,
            "user" => $this->getUser(),
            "form" => $form,
        ]);
    }

    /**
     * Delete article if connected user match article's user
     * @param Article $article
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    #[Route("/delete/{id}", name: "delete")]
    public function delete(Article $article){
        if($article->getUser()->getId() === $this->getUser()->getId()){
            $this->em->remove($article);
            $this->em->flush();
            return $this->redirectToRoute("category_single", ["id" => $article->getCategory()->getId()]);
        }
        $this->addFlash("error", "Vous ne pouvez pas supprimer cet article");
        return $this->redirectToRoute("article_single", ["id" => $article->getId()]);
    }

    /**
     * Edit article form
     * @param Article $article
     * @return void
     */
    #[Route("/edit/{id}", name: "edit")]
    public function edit(Article $article){

    }
}
