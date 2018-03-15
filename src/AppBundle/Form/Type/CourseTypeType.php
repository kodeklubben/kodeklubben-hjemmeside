<?php

namespace AppBundle\Form\Type;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseTypeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Navn',
            ))
            ->add('description', CKEditorType::class, array(
                'label' => 'Beskrivelse',
                'config' => array(
                    'height' => 350,
                ),
            ))
            ->add('challengesUrl', TextType::class, array(
                'label' => 'Link til oppgaver',
                'required' => false,
            ))
            ->add('hideOnHomepage', CheckboxType::class, array(
                'label' => 'Skjul kurset på forsiden',
                'required' => false,
            ))
            ->add('image', ImageType::class, array(
                'label' => 'Bilde',
                'required' => false,
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\CourseType',
        ));
    }

    public function getBlockPrefix()
    {
        return 'course_type';
    }
}
