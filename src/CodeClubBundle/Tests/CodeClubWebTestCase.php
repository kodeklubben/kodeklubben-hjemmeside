<?php

namespace CodeClubBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CodeClubWebTestCase extends WebTestCase
{
    private $anonClient;
    private $participantClient;
    private $parentClient;
    private $tutorClient;
    private $adminClient;

    /**
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function getAnonClient()
    {
        if ($this->anonClient === null) {
            $this->anonClient = self::createClient(array(), array(
                'HTTP_HOST' => 'trondheim.localhost',
            ));
        }

        return $this->anonClient;
    }

    /**
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function getParticipantClient()
    {
        if ($this->participantClient === null) {
            $this->participantClient = self::createClient(array(), array(
                'HTTP_HOST' => 'trondheim.localhost',
                'PHP_AUTH_USER' => 'participant@mail.no',
                'PHP_AUTH_PW' => '1234',
            ));
        }

        return $this->participantClient;
    }

    /**
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function getParentClient()
    {
        if ($this->parentClient === null) {
            $this->parentClient = self::createClient(array(), array(
                'HTTP_HOST' => 'trondheim.localhost',
                'PHP_AUTH_USER' => 'parent@mail.no',
                'PHP_AUTH_PW' => '1234',
            ));
        }

        return $this->parentClient;
    }

    /**
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function getTutorClient()
    {
        if ($this->tutorClient === null) {
            $this->tutorClient = self::createClient(array(), array(
                'HTTP_HOST' => 'trondheim.localhost',
                'PHP_AUTH_USER' => 'tutor@mail.no',
                'PHP_AUTH_PW' => '1234',
            ));
        }

        return $this->tutorClient;
    }

    /**
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function getAdminClient()
    {
        if ($this->adminClient === null) {
            $this->adminClient = self::createClient(array(), array(
                'HTTP_HOST' => 'trondheim.localhost',
                'PHP_AUTH_USER' => 'admin@mail.no',
                'PHP_AUTH_PW' => '1234',
            ));
        }

        return $this->adminClient;
    }

    protected function post($uri, array $data, Client $client = null)
    {
        if ($client === null) {
            $client = $this->getAnonClient();
        }

        $headers = array('CONTENT_TYPE' => 'application/json');
        $content = json_encode($data);
        $client->request('POST', $uri, $data, array(), $headers, $content);

        return $client->getResponse();
    }

    protected function goToSuccessful(Client $client, string $path)
    {
        $crawler = $client->request('GET', $path);

        $this->assertTrue($client->getResponse()->isSuccessful());

        return $crawler;
    }

    protected function goToNotFound(Client $client, string $path)
    {
        $crawler = $client->request('GET', $path);

        $this->assertEquals(404, $client->getResponse()->getStatusCode());

        return $crawler;
    }
}
