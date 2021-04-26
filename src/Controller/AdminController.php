<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\CrudOperatorType;
use App\Entity\Operateur;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AdminController extends AbstractController
{

    #[Route('/admin', name: 'admin')]
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer): Response
    {
        $crudOperator = new Operateur();
        $doctrine = $this->getDoctrine()->getManager();
        $operateurs = $this->getDoctrine()->getRepository(Operateur::class)->findAll();
        $form = $this->createForm(CrudOperatorType::class, $crudOperator, ['entity_manager' => $doctrine]);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $crudOperator->setPassword(
                $passwordEncoder->encodePassword(
                    $crudOperator,
                    $form->get('password')->getData()
                    )
                );
            $token = md5(uniqid());
            $url = $this->generateUrl('resetpassword',['token'=> $token],UrlGeneratorInterface::ABSOLUTE_URL);
            
            try{
                $crudOperator->setResetToken($token);
                $doctrine->persist($crudOperator); //persiste l’info dans le temps
                $doctrine->flush(); //envoie les info à la BDD
            }catch(\Exception $e){
                $this->addFlash('warning', 'Une erreur est survenue: '.$e->getMessage());
                return $this->redirectToRoute('resetpassword');
            }
            
            // generate a signed url and email it to the user
            $message = (new \Swift_Message('Creation de votre compte'))
            ->setFrom('inscription.lyceecapou@gmail.com')
            ->setTo($crudOperator->getEmail())
            ->setBody(
                $this->renderView(
                    // templates/emails/registration.html.twig
                    'registration/email.html.twig',
                    ['user'=>$crudOperator->getEmail(),'path'=>$url]
                    ),
                'text/html'
                );
            
            $mailer->send($message);
        }
        
        return $this->render('admin/index.html.twig', [
            'form' => $form->createView(),
            'controller_name' => 'AdminController',
            'operateurs' => $operateurs,
        ]);
    }
    
    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Request $request, Operateur $crudOperator, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer){
        $doctrine = $this->getDoctrine()->getManager();
        $form = $this->createForm(CrudOperatorType::class, $crudOperator, ['entity_manager' => $doctrine]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $crudOperator->setPassword(
                $passwordEncoder->encodePassword(
                    $crudOperator,
                    $form->get('password')->getData()
                    )
                );
            $token = md5(uniqid());
            $url = $this->generateUrl('resetpassword',['token'=> $token],UrlGeneratorInterface::ABSOLUTE_URL);
            try{
                $crudOperator->setResetToken($token);
                $doctrine->flush(); //envoie les info à la BDD
            }catch(\Exception $e){
                $this->addFlash('warning', 'Une erreur est survenue: '.$e->getMessage());
                return $this->redirectToRoute('resetpassword');
            }
    }
    return $this->render('logs/index.html.twig', [
        'form' => $form->createView(),
        'controller_name' => 'AdminController',
    ]);
    }
    
    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Request $request, Operateur $crudOperator, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer){
        $doctrine = $this->getDoctrine()->getManager();
        $doctrine->remove($crudOperator);
        $doctrine->flush();
        return $this->redirectToRoute('admin');
    }
}