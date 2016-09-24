<?php

namespace CodeClubBundle\Tests\Availability;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AvailabilityFunctionalTest extends WebTestCase
{
    /**
     * @dataProvider publicUrlProvider
     *
     * @param $url
     */
    public function testPublicPageIsSuccessful($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    /**
     * @dataProvider participantUrlProvider
     *
     * @param $url
     */
    public function testParticipantPageIsSuccessful($url)
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'participant@mail.no',
            'PHP_AUTH_PW' => '1234',
        ));
        $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isSuccessful());
    }
    /**
     * @dataProvider participantUrlProvider
     *
     * @param $url
     */
    public function testParticipantPageIsDenied($url)
    {
        //Check if anonymous users gets denied
        $client = self::createClient();
        $client->request('GET', $url);
        $this->assertFalse($client->getResponse()->isSuccessful());
    }

    /**
     * @dataProvider tutorUrlProvider
     *
     * @param $url
     */
    public function testTutorPageIsSuccessful($url)
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'tutor@mail.no',
            'PHP_AUTH_PW' => '1234',
        ));
        $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isSuccessful());
    }
    /**
     * @dataProvider tutorUrlProvider
     *
     * @param $url
     */
    public function testTutorPageIsDenied($url)
    {
        //Check if anonymous users gets denied
        $client = self::createClient();
        $client->request('GET', $url);
        $this->assertFalse($client->getResponse()->isSuccessful());
    }

    /**
     * @dataProvider adminUrlProvider
     *
     * @param $url
     */
    public function testAdminPageIsSuccessful($url)
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin@mail.no',
            'PHP_AUTH_PW' => '1234',
        ));
        $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isSuccessful());
    }
    /**
     * @dataProvider adminUrlProvider
     *
     * @param $url
     */
    public function testAdminPageIsDenied($url)
    {
        //Check if anonymous users gets denied
        $client = self::createClient();
        $client->request('GET', $url);
        $this->assertFalse($client->getResponse()->isSuccessful());

        //Check if participant gets denied
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'participant@mail.no',
            'PHP_AUTH_PW' => '1234',
        ));
        $client->request('GET', $url);
        $this->assertFalse($client->getResponse()->isSuccessful());

        //Check if tutor gets denied
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'tutor@mail.no',
            'PHP_AUTH_PW' => '1234',
        ));
        $client->request('GET', $url);
        $this->assertFalse($client->getResponse()->isSuccessful());
    }
    public function publicUrlProvider()
    {
        return array(
            array('/'),
            array('/om'),
            array('/kurs'),
            array('/kurs/1'),
            array('/login'),
            array('/registrer/deltaker'),
            array('/registrer/veileder'),
            array('/registrer/foresatt'),
        );
    }
    public function participantUrlProvider()
    {
        return array(
            array('/pamelding'),
        );
    }
    public function tutorUrlProvider()
    {
        return array(
            array('/pamelding'),
        );
    }
    public function adminUrlProvider()
    {
        return array(
            array('/kontrollpanel/'),
            array('/kontrollpanel/brukere'),
            array('/kontrollpanel/brukere/2'),
            array('/kontrollpanel/bruker/ny'),
            array('/kontrollpanel/pamelding/2'),
            array('/kontrollpanel/kurs'),
            array('/kontrollpanel/kurs/ny'),
            array('/kontrollpanel/kurs/1'),
            array('/kontrollpanel/kurs/timeplan/1'),
            array('/kontrollpanel/kurs/deltakere/1'),
            array('/kontrollpanel/kurs/veiledere/1'),
            array('/kontrollpanel/kurs/type'),
            array('/kontrollpanel/kurs/type/ny'),
            array('/kontrollpanel/kurs/type/1'),
            array('/kontrollpanel/veiledere'),
            array('/kontrollpanel/deltakere'),
            array('/kontrollpanel/epost'),
            array('/kontrollpanel/melding'),
            array('/kontrollpanel/info'),
            array('/kontrollpanel/statisk_innhold/header'),
            array('/kontrollpanel/statisk_innhold/tagline'),
            array('/kontrollpanel/statisk_innhold/deltaker'),
            array('/kontrollpanel/statisk_innhold/veileder'),
            array('/kontrollpanel/statisk_innhold/om'),
        );
    }
}
