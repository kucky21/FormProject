<?php

namespace App\Controller;


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
        if(!empty($user)){
            $userName = $user->getName();
            $userEmail = $user->getEmail();
        }
        return $this->render('profile/index.html.twig', [
            'name' => $userName,
            'email' => $userEmail,
        ]);
    }
}
