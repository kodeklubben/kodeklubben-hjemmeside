<?php

namespace StaticContentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StaticContentType extends AbstractType
{
    private $label;

    /**
     * StaticContentType constructor.
     *
     * @param string $label
     */
    public function __construct($label)
    {
        $this->label = $label;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('content', 'ckeditor', array(
            'required' => false,
            'config' => array(
                'height' => 250,
                ),
            'label' => $this->label,
            'attr' => array('class' => 'hide'), // Graceful loading, hides the textarea that is replaced by ckeditor
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }

    public function getName()
    {
        return 'app_bundle_static_content_type';
    }
}
