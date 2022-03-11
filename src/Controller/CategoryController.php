<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\ArticleType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/", name: "category_")]
class CategoryController extends AbstractController
{
    /**
     * Render all categories
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    #[Route("/", name: "index")]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        return $this->render("home/index.html.twig",[
            "categories" => $categories,
        ]);
    }

    /**
     * Render single category and show articles related to it
     * @param Category $category
     * @return Response
     */
    #[Route("/category/{id}", name: "single")]
    public function single(Category $category){
        return $this->render("category/expanded.html.twig", [
            "category" => $category,
            "user" => $this->getUser()
        ]);
    }
}
