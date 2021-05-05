<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Operateur;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ResetPasswordFormType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Repository\OperateurRepository;

class ResetPasswordController extends AbstractController
{
    #[Route('/resetpassword/{token}', name: 'resetpassword')]
    public function index($token, Request $request, OperateurRepository $userRepo, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = $userRepo->findOneBy(['reset_token'=>$token]);
        $form = $this->createForm(ResetPasswordFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                    )
                );
            $entityManager = $this->getDoctrine()->getManager();
            $user->setResetToken(null);
            $entityManager->persist($user);
            $entityManager->flush();
        }
        return $this->render('reset_password/index.html.twig', [
            'form' => $form->createView(),
            'controller_name' => 'ResetPasswordController',
        ]);
    }
}