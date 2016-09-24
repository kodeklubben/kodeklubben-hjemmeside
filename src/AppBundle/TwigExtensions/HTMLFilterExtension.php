<?php

namespace AppBundle\TwigExtensions;

class HTMLFilterExtension extends \Twig_Extension
{
    private $blacklistedTags = ['script', 'iframe'];

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('safe_html', array($this, 'htmlFilter'), array(
                'is_safe' => array('html'),
            )),
        );
    }

    public function htmlFilter($html)
    {
        foreach ($this->blacklistedTags as $tag) {
            $html = preg_replace('/<'.$tag.'\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/'.$tag.'>/i', '', $html);
            $html = str_replace('<script', '', $html);
        }

        return $html;
    }

    public function getName()
    {
        return 'html_extension';
    }
}
