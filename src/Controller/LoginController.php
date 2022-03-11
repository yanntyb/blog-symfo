<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    /**
     * Login formulaire
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
             return $this->redirectToRoute('category_index');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        try{
            $lastUsername = $authenticationUtils->getLastUsername();
        }
        catch(Exception $e){
            $lastUsername = "";
        }

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * Logout route
     * @return void
     */
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * Show register page
     * @param EntityManagerInterface $em
     * @param Request $req
     * @param UserPasswordHasherInterface $hasher
     * @param UserRepository $repository
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    #[Route('/register', name: "app_register")]
    public function register(EntityManagerInterface $em, Request $req, UserPasswordHasherInterface $hasher, UserRepository $repository,AuthenticationUtils $authenticationUtils){
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()){

            if($repository->checkMailExist($user->getEmail())){
                $this->addFlash("error", "Le mail est deja pris");
                return $this->render("user/register.html.twig", [
                    "form" => $form->createView(),
                ]);
            }
            $user->setPassword($hasher->hashPassword($user,$user->getPassword()));
            $em->persist($user);
            $em->flush();
            return $this->login($authenticationUtils);
        }

        return $this->render("user/register.html.twig", [
            "form" => $form->createView(),
        ]);
    }
}
