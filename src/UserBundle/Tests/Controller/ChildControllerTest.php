<?php

//namespace UserBundle\Tests\Controller;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ChildControllerTest extends WebTestCase
{
    public function testCreateChild()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'parent@mail.no',
            'PHP_AUTH_PW' => '1234',
        ));

        $crawler = $client->request('GET', '/pamelding');
        $this->assertTrue($client->getResponse()->isSuccessful());

        $link = $crawler->selectLink('Nytt barn')->link();

        $crawler = $client->click($link);
        $this->assertEquals(1, $crawler->filter('h3:contains("Nytt Barn")')->count());

        $form = $crawler->selectButton('Lagre')->form();
        $form['app_bundlecreate_child_type[firstName]']->setValue('TestChildFirst');
        $form['app_bundlecreate_child_type[lastName]']->setValue('TestChildLast');
        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/pamelding'));
        $crawler = $client->followRedirect();

        $this->assertEquals(1, $crawler->filter('td:contains("TestChildFirst")')->count());
        $this->assertEquals(1, $crawler->filter('td:contains("TestChildLast")')->count());

        restoreDatabase();
    }

    public function testDeleteChild()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'parent@mail.no',
            'PHP_AUTH_PW' => '1234',
        ));

        $crawler = $client->request('GET', '/pamelding');
        $this->assertTrue($client->getResponse()->isSuccessful());

        // Count children
        $childrenCountBefore = $crawler->selectButton('Slett')->count();

        $deleteButton = $crawler->selectButton('Slett')->first();
        $form = $deleteButton->form();
        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirection());
        $crawler = $client->followRedirect();

        $childrenCountAfter = $crawler->filter('button:contains("Slett")')->count();

        $this->assertEquals(1, $childrenCountBefore - $childrenCountAfter);

        restoreDatabase();
    }
}
