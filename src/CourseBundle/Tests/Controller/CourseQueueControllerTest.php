<?php

namespace UserBundle\Tests\Controller;

use CodeClubBundle\Tests\CodeClubWebTestCase;

class CourseQueueControllerTest extends CodeClubWebTestCase
{
    public function testParticipantSignUpToCourseQueue()
    {
        $client = $this->getParticipantClient();

        $crawler = $client->request('GET', '/pamelding');
        $this->assertTrue($client->getResponse()->isSuccessful());

        $withdrawButtonCountBefore = $crawler->selectButton('Meld av')->count();

        // Assumes that the user is not yet signed up to any queues
        // Assert that the queue list doesn't exists on page
        $this->assertEquals(0, $crawler->filter('h4:contains("Ventelister")')->count());

        // Sign up to the first available queue
        $button = $crawler->selectButton('Venteliste')->first();
        $form = $button->form();
        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/pamelding'));
        $crawler = $client->followRedirect();

        // Assert that the queue list now exists on page
        $this->assertEquals(1, $crawler->filter('h4:contains("Ventelister")')->count());

        $withdrawButtonCountAfter = $crawler->selectButton('Meld av')->count();

        // Assert that there are one more 'Meld av' button on the page
        $this->assertEquals(1, $withdrawButtonCountAfter - $withdrawButtonCountBefore);

        \TestDataManager::restoreDatabase();
    }

    public function testParticipantSignUpTwiceToSameCourseQueue()
    {
        $client = $this->getParticipantClient();

        $crawler = $client->request('GET', '/pamelding');
        $this->assertTrue($client->getResponse()->isSuccessful());

        // Sign up to the first available queue
        $button = $crawler->selectButton('Venteliste')->first();
        $form = $button->form();
        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/pamelding'));
        $crawler = $client->followRedirect();

        $withdrawButtonCountBefore = $crawler->selectButton('Meld av')->count();

        // Sign up to the first available queue
        $button = $crawler->selectButton('Venteliste')->first();
        $form = $button->form();
        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/pamelding'));
        $crawler = $client->followRedirect();

        $withdrawButtonCountAfter = $crawler->selectButton('Meld av')->count();

        // Assert that there are no more 'Meld av' button on the page after signing up second time
        $this->assertEquals(0, $withdrawButtonCountAfter - $withdrawButtonCountBefore);

        \TestDataManager::restoreDatabase();
    }

    public function testParentSignUpToCourseQueue()
    {
        $client = $this->getParentClient();

        $crawler = $client->request('GET', '/pamelding');
        $this->assertTrue($client->getResponse()->isSuccessful());

        $withdrawButtonCountBefore = $crawler->selectButton('Meld av')->count();

        // Sign up to the first available queue
        $button = $crawler->selectButton('Venteliste')->first();
        $form = $button->form();
        $form['child'] = '2';
        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/pamelding'));
        $crawler = $client->followRedirect();

        $withdrawButtonCountAfter = $crawler->selectButton('Meld av')->count();

        // Assert that there are one more 'Meld av' button on the page
        $this->assertEquals(1, $withdrawButtonCountAfter - $withdrawButtonCountBefore);

        \TestDataManager::restoreDatabase();
    }

    public function testParentSignUpTwiceToSameCourseQueue()
    {
        $client = $this->getParentClient();

        $crawler = $client->request('GET', '/pamelding');
        $this->assertTrue($client->getResponse()->isSuccessful());

        // Sign up to the first available queue
        $button = $crawler->selectButton('Venteliste')->first();
        $form = $button->form();
        $form['child'] = '2';
        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/pamelding'));
        $crawler = $client->followRedirect();

        $withdrawButtonCountBefore = $crawler->selectButton('Meld av')->count();

        // Sign up to the first available queue
        $button = $crawler->selectButton('Venteliste')->first();
        $form = $button->form();
        $form['child'] = '2';
        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/pamelding'));
        $crawler = $client->followRedirect();

        $withdrawButtonCountAfter = $crawler->selectButton('Meld av')->count();

        // Assert that there are no more 'Meld av' buttons on the page
        $this->assertEquals(0, $withdrawButtonCountAfter - $withdrawButtonCountBefore);

        \TestDataManager::restoreDatabase();
    }

    public function testSignUpToQueueWhenAlreadyInCourse()
    {
        $client = $this->getParentClient();

        $client->followRedirects(true);

        $crawler = $client->request('GET', '/pamelding');

        // Assert that the first course has room for two more participants
        $this->assertEquals(1, $crawler
            ->filter('footer')
            ->first()
            ->filter('p:contains("2 ledige plasser")')
            ->count()
        );

        $button = $crawler->selectButton('Meld på')->first();
        $form = $button->form();
        $form['child'] = '1';
        $client->submit($form);

        $crawler = $client->getCrawler();

        // Assert that the first course has room for two more participants
        $this->assertEquals(1, $crawler
            ->filter('footer')
            ->first()
            ->filter('p:contains("1 ledig plass")')
            ->count()
        );

        $button = $crawler->selectButton('Meld på')->first();
        $form = $button->form();
        $form['child'] = '2';
        $client->submit($form);

        $crawler = $client->getCrawler();

        // Assert that the first course is full
        $this->assertEquals(1, $crawler
            ->filter('footer')
            ->first()
            ->filter('p:contains("0 ledige plasser")')
            ->count()
        );

        // Assert that the first course contains a button to sign up to the queue
        $this->assertEquals(1, $crawler
            ->filter('footer')
            ->first()
            ->filter('button:contains("Venteliste")')
            ->count()
        );

        $withdrawButtonCountBefore = $crawler->selectButton('Meld av')->count();

        $button = $crawler->selectButton('Venteliste')->first();
        $form = $button->form();
        $form['child'] = '2';
        $client->submit($form);

        $crawler = $client->getCrawler();

        $withdrawButtonCountAfter = $crawler->selectButton('Meld av')->count();

        $this->assertEquals(0, $withdrawButtonCountBefore - $withdrawButtonCountAfter);

        \TestDataManager::restoreDatabase();
    }

    public function testAdminCourseQueueList()
    {
        $client = $this->getAdminClient();

        $crawler = $client->request('GET', '/kontrollpanel/kurs/venteliste/2');
        $this->assertTrue($client->getResponse()->isSuccessful());

        $queueLengthBefore = $crawler->filter('ol>li')->count();

        $client = $this->getParticipantClient();

        $crawler = $client->request('GET', '/pamelding');

        // Sign up to the second course's queue
        $button = $crawler->filter('footer')->eq(1)->selectButton('Venteliste');
        $form = $button->form();
        $client->submit($form);

        $client = $this->getAdminClient();

        $crawler = $client->request('GET', '/kontrollpanel/kurs/venteliste/2');

        $queueLengthAfter = $crawler->filter('ol>li')->count();

        $this->assertEquals(1, $queueLengthAfter - $queueLengthBefore);

        \TestDataManager::restoreDatabase();
    }

    public function testTransferParticipantFromQueueToCourse()
    {
        $client = $this->getAdminClient();

        $crawler = $client->request('GET', '/kontrollpanel/kurs/venteliste/2');
        $this->assertTrue($client->getResponse()->isSuccessful());

        $queueLengthBefore = $crawler->filter('ol>li')->count();

        $crawler = $client->request('GET', '/kontrollpanel/kurs/deltakere/2');
        $this->assertTrue($client->getResponse()->isSuccessful());

        // Assert that the course has 25 of 25 participants
        $this->assertEquals(1, $crawler->filter('h3:contains("antall: 25")')->count());

        // Assert that Child1 Child1-last is not in course
        $this->assertEquals(0, $crawler->filter('td:contains("Child1 Child1-last")')->count());

        // Remove first participant from the course
        $button = $crawler->selectButton('Fjern')->first();
        $form = $button->form();
        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirection());
        $crawler = $client->followRedirect();

        // Assert that the course still has 25 of 25 participants
        $this->assertEquals(1, $crawler->filter('h3:contains("antall: 25")')->count());

        // Assert that Child1 Child1-last is now in course
        $this->assertEquals(1, $crawler->filter('td:contains("Child1 Child1-last")')->count());

        $crawler = $client->request('GET', '/kontrollpanel/kurs/venteliste/2');

        $queueLengthAfter = $crawler->filter('ol>li')->count();

        // Assert that there are one less participant in the queue
        $this->assertEquals(1, $queueLengthBefore - $queueLengthAfter);

        \TestDataManager::restoreDatabase();
    }
}
