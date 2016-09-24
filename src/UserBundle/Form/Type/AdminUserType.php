<?php

namespace UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AdminUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('role', ChoiceType::class, array(
                'choices' => array(
                    'ROLE_PARENT' => 'Foresatt',
                    'ROLE_PARTICIPANT' => 'Deltaker',
                    'ROLE_TUTOR' => 'Veileder',
                    'ROLE_ADMIN' => 'Admin',
                    ),
                'label' => 'Brukertype',
                'multiple' => false,
                'mapped' => false,
            ))
            ->add('firstName', TextType::class, array(
                'label' => 'Fornavn',
            ))
            ->add('lastName', TextType::class, array(
                'label' => 'Etternavn',
            ))
            ->add('email', EmailType::class, array(
                'label' => 'E-post',
            ))
            ->add('phone', TextType::class, array(
                'label' => 'Telefon',
                'data' => '-',
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UserBundle\Entity\User',
        ));
    }
}
