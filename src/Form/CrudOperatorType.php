<?php

namespace App\Form;

use App\Entity\Operateur;
use App\Entity\Groupe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class CrudOperatorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entityManager = $options['entity_manager'];
        
        $builder
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class)
            ->add('idGroupe', ChoiceType::class, [
                'choices'  => [
                    '1' => $entityManager->getRepository(Groupe::class)->findOneById('1'),
                    '2' => $entityManager->getRepository(Groupe::class)->findOneById('2'),
                ],
            ])
           ->add('envoyer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Operateur::class,
        ]);
        $resolver->setRequired('entity_manager');
    }
}