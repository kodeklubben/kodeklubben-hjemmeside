<?php

namespace CourseBundle\Form\Type;

use CodeClubBundle\Entity\Club;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseFormType extends AbstractType
{
    private $showAllSemesters;
    /**
     * @var Club
     */
    private $club;

    /**
     * CourseFormType constructor.
     *
     * @param $showAllSemesters
     * @param Club $club
     */
    public function __construct($showAllSemesters, Club $club)
    {
        $this->showAllSemesters = $showAllSemesters;
        $this->club = $club;
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
                        ->where('ct.deleted = false')
                        ->andWhere('ct.club = :club')
                        ->setParameter('club', $this->club);
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
