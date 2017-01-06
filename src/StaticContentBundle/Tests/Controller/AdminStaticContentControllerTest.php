<?php

namespace StaticContentBundle\Test\Controller;

use CodeClubBundle\Tests\CodeClubWebTestCase;

class AdminStaticContentControllerTest extends CodeClubWebTestCase
{
    public function testChangeHeader()
    {
        $client = $this->getAdminClient();

        $crawler = $this->goToSuccessful($client, '/');

        // Assert that new content does not already exist on home page
        $this->assertEquals(0, $crawler->filter('p:contains("test123")')->count());

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/statisk_innhold/header');

        $form = $crawler->selectButton('Lagre')->first()->form();

        $form['static_content[content]'] = 'test123';

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/kontrollpanel/statisk_innhold/header'));

        $crawler = $this->goToSuccessful($client, '/');

        // Assert that new content now exists on home page
        $this->assertEquals(1, $crawler->filter('p:contains("test123")')->count());

        \TestDataManager::restoreDatabase();
    }

    public function testChangeTagLine()
    {
        $client = $this->getAdminClient();

        $crawler = $this->goToSuccessful($client, '/');

        // Assert that new content does not already exist on home page
        $this->assertEquals(0, $crawler->filter('div.well:contains("test123")')->count());

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/statisk_innhold/tagline');

        $form = $crawler->selectButton('Lagre')->first()->form();

        $form['static_content[content]'] = 'test123';

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/kontrollpanel/statisk_innhold/tagline'));

        $crawler = $this->goToSuccessful($client, '/');

        // Assert that new content now exists on home page
        $this->assertEquals(1, $crawler->filter('div.well:contains("test123")')->count());

        \TestDataManager::restoreDatabase();
    }

    public function testChangeParticipant()
    {
        $client = $this->getAdminClient();

        $crawler = $this->goToSuccessful($client, '/');

        // Assert that new content does not already exist on home page
        $this->assertEquals(0, $crawler->filter('div.box-text:contains("test123")')->count());

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/statisk_innhold/deltaker');

        $form = $crawler->selectButton('Lagre')->first()->form();

        $form['static_content[content]'] = 'test123';

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/kontrollpanel/statisk_innhold/deltaker'));

        $crawler = $this->goToSuccessful($client, '/');

        // Assert that new content now exists on home page
        $this->assertEquals(1, $crawler->filter('div.box-text:contains("test123")')->count());

        \TestDataManager::restoreDatabase();
    }

    public function testChangeTutor()
    {
        $client = $this->getAdminClient();

        $crawler = $this->goToSuccessful($client, '/');

        // Assert that new content does not already exist on home page
        $this->assertEquals(0, $crawler->filter('div.box-text:contains("test123")')->count());

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/statisk_innhold/veileder');

        $form = $crawler->selectButton('Lagre')->first()->form();

        $form['static_content[content]'] = 'test123';

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/kontrollpanel/statisk_innhold/veileder'));

        $crawler = $this->goToSuccessful($client, '/');

        // Assert that new content now exists on home page
        $this->assertEquals(1, $crawler->filter('div.box-text:contains("test123")')->count());

        \TestDataManager::restoreDatabase();
    }

    public function testChangeAboutParticipant()
    {
        $client = $this->getAdminClient();

        $crawler = $this->goToSuccessful($client, '/om');

        // Assert that new content does not already exist on about page
        $this->assertEquals(0, $crawler->filter('p:contains("test123")')->count());

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/statisk_innhold/om_deltakere');

        $form = $crawler->selectButton('Lagre')->first()->form();

        $form['static_content[content]'] = 'test123';

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/kontrollpanel/statisk_innhold/om_deltakere'));

        $crawler = $this->goToSuccessful($client, '/om');

        // Assert that new content now exists on about page
        $this->assertEquals(1, $crawler->filter('p:contains("test123")')->count());

        \TestDataManager::restoreDatabase();
    }

    public function testChangeAboutAboutTutor()
    {
        $client = $this->getAdminClient();

        $crawler = $this->goToSuccessful($client, '/om');

        // Assert that new content does not already exist on about page
        $this->assertEquals(0, $crawler->filter('p:contains("test123")')->count());

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/statisk_innhold/om_veiledere');

        $form = $crawler->selectButton('Lagre')->first()->form();

        $form['static_content[content]'] = 'test123';

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/kontrollpanel/statisk_innhold/om_veiledere'));

        $crawler = $this->goToSuccessful($client, '/om');

        // Assert that new content now exists on about page
        $this->assertEquals(1, $crawler->filter('p:contains("test123")')->count());

        \TestDataManager::restoreDatabase();
    }
}
