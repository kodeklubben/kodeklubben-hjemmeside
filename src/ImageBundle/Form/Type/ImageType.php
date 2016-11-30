<?php

namespace ImageBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class, array(
                'label' => ' ',
                'attr' => array(
                    'accept' => 'image/png, image/jpg, image/jpeg',
                    'onchange' => 'previewImage(event)',
                ),
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ImageBundle\Entity\Image',
        ));
    }

    public function getBlockPrefix()
    {
        return 'image_bundle_image_type';
    }
}
