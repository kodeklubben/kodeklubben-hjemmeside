<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\AppWebTestCase;

class AdminCourseClassTest extends AppWebTestCase
{
    public function testAddCourseClass()
    {
        $client = $this->getAdminClient();

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/kurs/timeplan/1');

        $classCountBefore = $crawler->filter('tr')->count() - 1;

        $form = $crawler->selectButton('Legg til kurstid')->first()->form();

        $currentTime = new \DateTime();

        $form['course_class[time][date][year]'] = $currentTime->format('Y');
        $form['course_class[time][date][month]'] = 1;
        $form['course_class[time][date][day]'] = 1;
        $form['course_class[time][time][hour]'] = 3;
        $form['course_class[time][time][minute]'] = 23;
        $form['course_class[place]'] = 'Room 123';

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/kontrollpanel/kurs/timeplan/1'));

        $crawler = $client->followRedirect();

        $this->assertEquals(1, $crawler->filter('tr:contains("03:23")')->count());

        $classCountAfter = $crawler->filter('tr')->count() - 1;

        $this->assertEquals(1, $classCountAfter - $classCountBefore);

        \TestDataManager::restoreDatabase();
    }

    public function testDeleteClass()
    {
        $client = $this->getAdminClient();

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/kurs/timeplan/1');

        $classCountBefore = $crawler->filter('tr')->count() - 1;

        $form = $crawler->filter('button.text-danger')->first()->form();

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/kontrollpanel/kurs/timeplan/1'));

        $crawler = $client->followRedirect();

        $classCountAfter = $crawler->filter('tr')->count() - 1;

        $this->assertEquals(1, $classCountBefore - $classCountAfter);

        \TestDataManager::restoreDatabase();
    }
}
