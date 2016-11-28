<?php

namespace UserBundle\Tests\Controller;

use CodeClubBundle\Tests\CodeClubWebTestCase;

class SignupControllerTest extends CodeClubWebTestCase
{
    public function testSignupParticipant()
    {
        $client = $this->getParticipantClient();

        $crawler = $client->request('GET', '/pamelding');
        $this->assertTrue($client->getResponse()->isSuccessful());

        $signedUpCourseCountBefore = $crawler->selectButton('Meld av')->count();
        $signupButton = $crawler->selectButton('Meld på')->first();
        $form = $signupButton->form();
        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/pamelding'));
        $crawler = $client->followRedirect();

        $signedUpCourseCountAfter = $crawler->selectButton('Meld av')->count();
        $this->assertEquals(1, $signedUpCourseCountAfter - $signedUpCourseCountBefore);

        \TestDataManager::restoreDatabase();
    }

    public function testSignupTutor()
    {
        $client = $this->getTutorClient();

        $crawler = $client->request('GET', '/pamelding');
        $this->assertTrue($client->getResponse()->isSuccessful());

        $signedUpCourseCountBefore = $crawler->selectButton('Meld av')->count();
        $signupButton = $crawler->selectButton('Meld på')->first();
        $form = $signupButton->form();
        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/pamelding'));
        $crawler = $client->followRedirect();

        $signedUpCourseCountAfter = $crawler->selectButton('Meld av')->count();
        $this->assertEquals(1, $signedUpCourseCountAfter - $signedUpCourseCountBefore);

        \TestDataManager::restoreDatabase();
    }

    public function testSignupChild()
    {
        $client = $this->getParentClient();

        $crawler = $client->request('GET', '/pamelding');
        $this->assertTrue($client->getResponse()->isSuccessful());

        $signedUpCourseCountBefore = $crawler->selectButton('Meld av')->count();
        $signupButton = $crawler->selectButton('Meld på')->first();
        $form = $signupButton->form();
        $form['child'] = '1';
        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/pamelding'));
        $crawler = $client->followRedirect();

        $signupButton = $crawler->selectButton('Meld på')->first();
        $form = $signupButton->form();
        $form['child'] = '2';
        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/pamelding'));
        $crawler = $client->followRedirect();

        $signedUpCourseCountAfter = $crawler->selectButton('Meld av')->count();
        $this->assertEquals(2, $signedUpCourseCountAfter - $signedUpCourseCountBefore);

        \TestDataManager::restoreDatabase();
    }

    public function testSignupChildSameCourseTwice()
    {
        $client = $this->getParentClient();

        $crawler = $client->request('GET', '/pamelding');
        $this->assertTrue($client->getResponse()->isSuccessful());

        $signedUpCourseCountBefore = $crawler->selectButton('Meld av')->count();
        $signupButton = $crawler->selectButton('Meld på')->first();
        $form = $signupButton->form();
        $form['child'] = '1';
        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/pamelding'));
        $crawler = $client->followRedirect();

        $signupButton = $crawler->selectButton('Meld på')->first();
        $form = $signupButton->form();
        $form['child'] = '1';
        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/pamelding'));
        $crawler = $client->followRedirect();

        $signedUpCourseCountAfter = $crawler->selectButton('Meld av')->count();
        $this->assertEquals(1, $signedUpCourseCountAfter - $signedUpCourseCountBefore);

        \TestDataManager::restoreDatabase();
    }

    public function testWithdrawParticipant()
    {
        $client = $this->getParticipantClient();

        $crawler = $client->request('GET', '/pamelding');
        $this->assertTrue($client->getResponse()->isSuccessful());

        $signedUpCourseCountBefore = $crawler->selectButton('Meld av')->count();
        $signupButton = $crawler->selectButton('Meld av')->first();
        $form = $signupButton->form();
        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirection());
        $crawler = $client->followRedirect();

        $signedUpCourseCountAfter = $crawler->selectButton('Meld av')->count();
        $this->assertEquals(1, $signedUpCourseCountBefore - $signedUpCourseCountAfter);

        \TestDataManager::restoreDatabase();
    }

    public function testWithdrawTutor()
    {
        $client = $this->getTutorClient();

        $crawler = $client->request('GET', '/pamelding');
        $this->assertTrue($client->getResponse()->isSuccessful());

        $signedUpCourseCountBefore = $crawler->selectButton('Meld av')->count();
        $signupButton = $crawler->selectButton('Meld av')->first();
        $form = $signupButton->form();
        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirection());
        $crawler = $client->followRedirect();

        $signedUpCourseCountAfter = $crawler->selectButton('Meld av')->count();
        $this->assertEquals(1, $signedUpCourseCountBefore - $signedUpCourseCountAfter);

        \TestDataManager::restoreDatabase();
    }

    public function testWithdrawChild()
    {
        $client = $this->getParentClient();

        $crawler = $client->request('GET', '/pamelding');
        $this->assertTrue($client->getResponse()->isSuccessful());

        $signedUpCourseCountBefore = $crawler->selectButton('Meld av')->count();
        $signupButton = $crawler->selectButton('Meld av')->first();
        $form = $signupButton->form();
        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirection());
        $crawler = $client->followRedirect();

        $signedUpCourseCountAfter = $crawler->selectButton('Meld av')->count();
        $this->assertEquals(1, $signedUpCourseCountBefore - $signedUpCourseCountAfter);

        \TestDataManager::restoreDatabase();
    }
}
