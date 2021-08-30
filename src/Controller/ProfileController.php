<?php

namespace App\Controller;


use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{

    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

/**
     * @Route("/profile", name="profile")
     */
    public function index(): Response
    {
        $user = $this->security->getUser();
        $repository = $this->getDoctrine()->getRepository(User::class);
        $users = $repository->findAll();

        if($user !== null){
            $userName = $user->getName();
            $userEmail = $user->getEmail();
        }
        return $this->render('profile/index.html.twig', [
            'name' => $userName,
            'email' => $userEmail,
            'users' => $users
        ]);
    }
}
