<?php

//namespace UserBundle\Tests\Controller;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SignupControllerTest extends WebTestCase
{
    public function testSignupParticipant()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'participant@mail.no',
            'PHP_AUTH_PW' => '1234',
        ));

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

        restoreDatabase();
    }

    public function testSignupTutor()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'tutor@mail.no',
            'PHP_AUTH_PW' => '1234',
        ));

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

        restoreDatabase();
    }

    public function testSignupChild()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'parent@mail.no',
            'PHP_AUTH_PW' => '1234',
        ));

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

        restoreDatabase();
    }

    public function testSignupChildSameCourseTwice()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'parent@mail.no',
            'PHP_AUTH_PW' => '1234',
        ));

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

        restoreDatabase();
    }

    public function testWithdrawParticipant()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'participant@mail.no',
            'PHP_AUTH_PW' => '1234',
        ));

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

        restoreDatabase();
    }

    public function testWithdrawTutor()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'tutor@mail.no',
            'PHP_AUTH_PW' => '1234',
        ));

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

        restoreDatabase();
    }

    public function testWithdrawChild()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'parent@mail.no',
            'PHP_AUTH_PW' => '1234',
        ));

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

        restoreDatabase();
    }
}
