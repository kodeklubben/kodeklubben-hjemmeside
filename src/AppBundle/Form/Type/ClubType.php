<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClubType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
            'label' => 'Navn på kodeklubb',
            ))
            ->add('region', TextType::class, array(
                'label' => 'Region',
            ))
            ->add('email', EmailType::class, array(
                'label' => 'E-post til kodeklubb',
            ))
            ->add('facebook', TextType::class, array(
                'label' => '(valgfri) facebook.com/',
                'required' => false,
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }

    public function getBlockPrefix()
    {
        return 'code_club';
    }
}
