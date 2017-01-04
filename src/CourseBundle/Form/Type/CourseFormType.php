<?php

namespace CourseBundle\Form\Type;

use CodeClubBundle\Entity\Club;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseFormType extends AbstractType
{
    private $showAllSemesters;
    /**
     * @var Club
     */
    private $club;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->showAllSemesters = $options['showAllSemesters'];
        $this->club = $options['club'];
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Navn',
                'attr' => ['placeholder' => 'eks.: Scratch'],
            ))
            ->add('description', TextType::class, array(
                'label' => 'Kort Beskrivelse',
                'attr' => ['placeholder' => 'eks.: Scratch Mandag kl 18.00 i R1'],

            ))
            ->add('courseType', EntityType::class, array(
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
            ->add('participantLimit', NumberType::class, array(
                'label' => 'Maks antall deltakere',
                'attr' => ['placeholder' => 'eks.: 20'],
            ));
        if ($this->showAllSemesters) {
            $builder
                    ->add('semester', EntityType::class, array(
                        'class' => 'AppBundle\Entity\Semester',
                        'query_builder' => function (EntityRepository $er) {
                            return $er->allSemestersQuery();
                        },
                    ));
        } else {
            $builder
                ->add('semester', EntityType::class, array(
                    'class' => 'AppBundle\Entity\Semester',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->thisAndNextSemesterQuery();
                    },
                ));
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CourseBundle\Entity\Course',
            'club' => null,
            'showAllSemesters' => false,
        ));
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_course_series_type';
    }
}
