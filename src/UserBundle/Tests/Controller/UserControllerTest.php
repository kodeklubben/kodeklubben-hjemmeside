<?php

namespace UserBundle\Tests\Controller;

use CodeClubBundle\Tests\CodeClubWebTestCase;

class UserControllerTest extends CodeClubWebTestCase
{
    public function testRegisterParticipant()
    {
        $client = $this->getAnonClient();

        $crawler = $client->request('GET', '/registrer/deltaker');
        $this->assertTrue($client->getResponse()->isSuccessful());

        $email = 'participant@testmail.com';
        $password = 'secret123456';

        $form = $crawler->selectButton('Registrer')->form();
        $form = $this->fillForm($form, $email, $password);
        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirection());

        $this->login($email, $password);

        restoreDatabase();
    }

    public function testRegisterParent()
    {
        $client = $this->getAnonClient();

        $crawler = $client->request('GET', '/registrer/foresatt');
        $this->assertTrue($client->getResponse()->isSuccessful());

        $email = 'parent@testmail.com';
        $password = 'secret123456';

        $form = $crawler->selectButton('Registrer')->form();
        $form = $this->fillForm($form, $email, $password);
        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirection());

        $this->login($email, $password);

        restoreDatabase();
    }

    public function testRegisterTutor()
    {
        $client = $this->getAnonClient();

        $crawler = $client->request('GET', '/registrer/veileder');
        $this->assertTrue($client->getResponse()->isSuccessful());

        $email = 'tutor@testmail.com';
        $password = 'secret123456';

        $form = $crawler->selectButton('Registrer')->form();
        $form = $this->fillForm($form, $email, $password);
        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirection());

        $this->login($email, $password);

        restoreDatabase();
    }

    public function testUniqueEmail()
    {
        $client = $this->getAnonClient();

        $crawler = $client->request('GET', '/registrer/deltaker');
        $this->assertTrue($client->getResponse()->isSuccessful());

        $email = 'participant@testmail.com';
        $password = 'secret123456';

        $form = $crawler->selectButton('Registrer')->form();
        $form = $this->fillForm($form, $email, $password);
        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirection());

        // Register second indentical user
        $crawler = $client->request('GET', '/registrer/deltaker');

        $form = $crawler->selectButton('Registrer')->form();
        $form = $this->fillForm($form, $email, $password);
        $client->submit($form);

        $this->assertFalse($client->getResponse()->isRedirection());

        restoreDatabase();
    }

    private function fillForm(\Symfony\Component\DomCrawler\Form $form, $email, $password)
    {
        $form['user[firstName]']->setValue('Test');
        $form['user[lastName]']->setValue('User');
        $form['user[email]']->setValue($email);
        $form['user[phone]']->setValue('12345678');
        $form['user[password][first]']->setValue($password);
        $form['user[password][second]']->setValue($password);

        return $form;
    }

    private function login($email, $password)
    {
        $client = $this->getAnonClient();

        $crawler = $client->request('GET', '/login');
        $this->assertTrue($client->getResponse()->isSuccessful());

        $form = $crawler->selectButton('Logg inn')->form();
        $form['_username']->setValue($email);
        $form['_password']->setValue($password);

        $client->submit($form);

        // Check if user is logged in by trying to go to login page
        $client->request('GET', '/login');
        $this->assertTrue($client->getResponse()->isRedirect('/'));
    }
}
