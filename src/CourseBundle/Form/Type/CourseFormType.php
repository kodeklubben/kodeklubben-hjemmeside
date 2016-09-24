<?php

namespace CourseBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseFormType extends AbstractType
{
    private $showAllSemesters;

    /**
     * CourseFormType constructor.
     *
     * @param $showAllSemesters
     */
    public function __construct($showAllSemesters)
    {
        $this->showAllSemesters = $showAllSemesters;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label' => 'Navn',
                'attr' => ['placeholder' => 'eks.: Scratch'],
            ))
            ->add('description', 'text', array(
                'label' => 'Kort Beskrivelse',
                'attr' => ['placeholder' => 'eks.: Scratch Mandag kl 18.00 i R1'],

            ))
            ->add('courseType', 'entity', array(
                'label' => 'Kurstype',
                'class' => 'CourseBundle\Entity\CourseType',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ct')
                        ->select('ct')
                        ->where('ct.deleted = false');
                },
            ))
            ->add('participantLimit', 'number', array(
                'label' => 'Maks antall deltakere',
                'attr' => ['placeholder' => 'eks.: 20'],
            ));
        if ($this->showAllSemesters) {
            $builder
                    ->add('semester', 'entity', array(
                        'class' => 'AppBundle\Entity\Semester',
                        'query_builder' => function (EntityRepository $er) {
                            return $er->allSemestersQuery();
                        },
                    ));
        } else {
            $builder
                ->add('semester', 'entity', array(
                    'class' => 'AppBundle\Entity\Semester',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->thisAndNextSemesterQuery();
                    },
                ));
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }

    public function getName()
    {
        return 'app_bundle_course_series_type';
    }
}
