<?php

namespace CourseBundle\Form\Type;

use ImageBundle\Form\Type\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseTypeType extends AbstractType
{
    private $isCreate;

    /**
     * CourseTypeType constructor.
     *
     * @param $isCreate
     */
    public function __construct($isCreate)
    {
        $this->isCreate = $isCreate;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Navn',
            ))
            ->add('description', TextareaType::class, array(
                'label' => 'Beskrivelse',
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
    }

    public function getName()
    {
        return 'app_bundle_course_type';
    }
}
