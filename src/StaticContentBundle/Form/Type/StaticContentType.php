<?php

namespace StaticContentBundle\Form\Type;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StaticContentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('content', CKEditorType::class, array(
            'required' => false,
            'config' => array(
                'height' => 250,
                ),
            'label' => $options['label'],
            'attr' => array('class' => 'hide'), // Graceful loading, hides the textarea that is replaced by ckeditor
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'StaticContentBundle\Entity\StaticContent',
        ));
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_static_content_type';
    }
}
