<?php

namespace CodeClubBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CodeClubWebTestCase extends WebTestCase
{
    /**
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function getAnonClient()
    {
        return self::createClient(array(), array(
            'HTTP_HOST' => 'trondheim.localhost',
        ));
    }

    /**
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function getParticipantClient()
    {
        return static::createClient(array(), array(
            'HTTP_HOST' => 'trondheim.localhost',
            'PHP_AUTH_USER' => 'participant@mail.no',
            'PHP_AUTH_PW' => '1234',
        ));
    }

    /**
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function getParentClient()
    {
        return static::createClient(array(), array(
            'HTTP_HOST' => 'trondheim.localhost',
            'PHP_AUTH_USER' => 'parent@mail.no',
            'PHP_AUTH_PW' => '1234',
        ));
    }

    /**
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function getTutorClient()
    {
        return static::createClient(array(), array(
            'HTTP_HOST' => 'trondheim.localhost',
            'PHP_AUTH_USER' => 'tutor@mail.no',
            'PHP_AUTH_PW' => '1234',
        ));
    }

    /**
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function getAdminClient()
    {
        return static::createClient(array(), array(
            'HTTP_HOST' => 'trondheim.localhost',
            'PHP_AUTH_USER' => 'admin@mail.no',
            'PHP_AUTH_PW' => '1234',
        ));
    }
}
