<?php

namespace CourseBundle\Form\Type;

use ImageBundle\Form\Type\ImageType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseTypeType extends AbstractType
{
    private $isCreate;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->isCreate = $options['isCreate'];
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
                'required' => $this->isCreate,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CourseBundle\Entity\CourseType',
            'isCreate' => false
        ));
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_course_type';
    }
}
