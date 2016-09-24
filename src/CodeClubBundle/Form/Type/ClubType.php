<?php

namespace CodeClubBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClubType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
            'label' => 'Navn',
            ))
            ->add('region', 'text', array(
                'label' => 'Region',
            ))
            ->add('email', 'email', array(
                'label' => 'E-post',
            ))
            ->add('facebook', 'text', array(
                'label' => 'facbook.com/',
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }

    public function getName()
    {
        return 'code_club_bundle_club_type';
    }
}
