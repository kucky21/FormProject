<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractApiController
{
    /** @var UserPasswordEncoderInterface $passwordEncoder */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder){
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/register", name="register", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function registerAction(Request $request): Response
    {
        $form = $this->buildForm(UserType::class);

        $form->handleRequest($request);

        $repository = $this->getDoctrine()->getRepository(User::class);

        if($repository->findOneBy([
            "email" => $form->get("email")->getNormData()
        ])){
            return $this->respond($form, Response::HTTP_CONFLICT);
        }

        if(!$form->isSubmitted() || !$form->isValid()){
            return $this->respond($form, Response::HTTP_BAD_REQUEST);
        }

        $user = new User();
        $email = $user->setEmail($form->get("email")->getNormData());
        $password = $user->setPassword(
            $this->passwordEncoder->encodePassword($user, $form->get("password")->getNormData())
        );

        $role = $user->setRoles(["ROLE_USER"]);
        $name = $user->setName($form->get("name")->getNormData());


        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();
        return $this->respond($user);
    }

    /**
     * @Route("/registration", name="registration")
     * @param Request $request
     * @return Response
     */
    function registerIndex(Request $request)
    {
        $user = new User();

        $messege="";

        $form = $this->createForm(UserType::class, $user);

        $repository = $this->getDoctrine()->getRepository(User::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            $messege = "ZAdej správné údaje";
        }elseif($form->isSubmitted() && $form->isValid()){
            if($repository->findOneBy([
                "email" => $form->get("email")->getNormData()
            ])){
                $messege = "Uživatel s tímto emailem již existuje";
                return $this->render('registration/index.html.twig', [
                    'form' => $form->createView(),
                    'messege' => $messege
                ]);
            }
            $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));

            $user->setRoles(['ROLE_USER']);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/index.html.twig', [
            'form' => $form->createView(),
            'messege' => $messege
        ]);
    }
}
