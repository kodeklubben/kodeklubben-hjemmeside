<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label' => 'Navn',
            ))
            ->add('description', 'textarea', array(
                'label' => 'Kort Beskrivelse',
            ))
            ->add('courseType', 'entity', array(
                'label' => 'Kurstype',
                'class' => 'AppBundle\Entity\CourseType',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ct')
                        ->select('ct')
                        ->where('ct.deleted = false');
                }
            ))
            ->add('participantLimit', 'number', array(
                'label' => 'Maks antall deltakere'
            ))
            ->add('semester', 'entity', array(
                'class' => 'AppBundle\Entity\Semester'
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getName()
    {
        return 'app_bundle_course_series_type';
    }
}
