<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/comment", name: "comment_")]
class CommentController extends AbstractController
{
    private EntityManagerInterface $em;
    private UserRepository $userRepository;

    public function __construct(EntityManagerInterface $em, UserRepository $userRepo){
        $this->userRepository = $userRepo;
        $this->em = $em;
    }

    /**
     * Edit comment if user match
     * @param Comment $comment
     * @param Request $req
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    #[Route("/edit/{id}", name: "edit")]
    public function editComment(Comment $comment, Request $req){
        if($comment->getUser()->getId() === $this->getUser()->getId()){
            $form = $this->createForm(CommentType::class, $comment);
            $form->handleRequest($req);
            if($form->isSubmitted() && $form->isValid()){
                $this->em->persist($comment);
                $this->em->flush();
                return $this->redirectToRoute("article_single", ["id" => $comment->getArticle()->getId()]);
            }

            return $this->render("comment/edit.html.twig", [
                "form" => $form->createView(),
            ]);
        }
        else{
            $this->addFlash("error", "Vous ne pouvez pas editer ce commentaire");
        }
        return $this->redirectToRoute("article_single", ["id" => $comment->getArticle()->getId()]);
    }

    /**
     * Delete comment if user match
     * @param Comment $comment
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    #[Route("/delete/{id}", name: "delete")]
    public function deleteComment(Comment $comment){
        if($comment->getUser()->getId() === $this->getUser()->getId()){
            $this->em->remove($comment);
            $this->em->flush();
        }
        return $this->redirectToRoute("article_single", ["id" => $comment->getArticle()->getId()]);
    }
}
