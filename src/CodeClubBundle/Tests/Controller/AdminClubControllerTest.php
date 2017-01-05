<?php

namespace CodeClubBundle\Tests\Controller;

use CodeClubBundle\Tests\CodeClubWebTestCase;

class AdminClubControllerTest extends CodeClubWebTestCase
{
    public function testChangeName()
    {
        $client = $this->getAdminClient();

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/info');

        $form = $crawler->selectButton('Oppdater')->form();
        $form['code_club[name]'] = 'test123';

        $client->submit($form);

        $crawler = $this->goToSuccessful($client, '/');

        $this->assertGreaterThanOrEqual(1, $crawler->filter('h1:contains("test123")')->count());

        \TestDataManager::restoreDatabase();
    }

    public function testChangeRegion()
    {
        $client = $this->getAdminClient();

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/info');

        $form = $crawler->selectButton('Oppdater')->form();
        $form['code_club[region]'] = 'test123';

        $client->submit($form);

        $crawler = $this->goToSuccessful($client, '/');

        $this->assertGreaterThanOrEqual(1, $crawler->filter('div.logo:contains("test123")')->count());

        \TestDataManager::restoreDatabase();
    }

    public function testChangeEmail()
    {
        $client = $this->getAdminClient();

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/info');

        $form = $crawler->selectButton('Oppdater')->form();
        $form['code_club[email]'] = 'test123@test123.test123';

        $client->submit($form);

        $crawler = $this->goToSuccessful($client, '/');

        $this->assertGreaterThanOrEqual(1, $crawler->filter('footer:contains("test123@test123.test123")')->count());

        \TestDataManager::restoreDatabase();
    }

    public function testChangeFacebook()
    {
        $client = $this->getAdminClient();

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/info');

        $form = $crawler->selectButton('Oppdater')->form();
        $form['code_club[facebook]'] = 'test123';

        $client->submit($form);

        $crawler = $this->goToSuccessful($client, '/');

        $this->assertGreaterThanOrEqual(1, $crawler->filter('footer')->filterXPath('//a[@href="https://www.facebook.com/test123"]')->count());

        \TestDataManager::restoreDatabase();
    }
}
