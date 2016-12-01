<?php

namespace AppBundle\Twig;

class DomainExtension extends \Twig_Extension
{
    private $baseHost;

    /**
     * DomainExtension constructor.
     *
     * @param string $baseHost
     */
    public function __construct($baseHost)
    {
        $this->baseHost = $baseHost;
    }

    public function getName()
    {
        return 'DomainExtension';
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('remove_subdomain', array($this, 'removeSubdomain')),
        );
    }

    public function removeSubdomain($url)
    {
        $pos = strpos($url, $this->baseHost);
        $schemePos = strpos($url, '://');
        if ($pos > 0 && $pos > $schemePos) {
            return substr($url, 0, $schemePos + 3).substr($url, $pos);
        }

        return $pos;
    }
}
