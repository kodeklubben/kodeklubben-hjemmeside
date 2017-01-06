<?php

namespace UserBundle\Tests\Controller;

use CodeClubBundle\Tests\CodeClubWebTestCase;

class AdminCourseTypeControllerTest extends CodeClubWebTestCase
{
    public function testCreate()
    {
        $client = $this->getAdminClient();

        $courseTypeCountBefore = $this->countCourseTypes();

        $response = $this->submitCourseType('test123', 'test123description', 'http://test123.com');

        $this->assertTrue($response->isRedirect('/kontrollpanel/kurs/type'));

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/kurs/type');

        $this->assertEquals(1, $crawler->filter('div.course h3:contains("test123")')->count());

        $courseTypeCountAfter = $this->countCourseTypes();

        $this->assertEquals(1, $courseTypeCountAfter - $courseTypeCountBefore);

        \TestDataManager::restoreDatabase();
    }

    public function testErrorWhenCreateCourseTypeWithSameNameTwice()
    {
        $client = $this->getAdminClient();

        $courseTypeCountBefore = $this->countCourseTypes();

        $response = $this->submitCourseType('test123', 'test123description', 'http://test123.com');
        $this->assertTrue($response->isRedirect('/kontrollpanel/kurs/type'));

        $response = $this->submitCourseType('test123', 'test2description', 'http://test2.com');
        $this->assertTrue(!$response->isRedirection());

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/kurs/type');

        $this->assertEquals(1, $crawler->filter('div.course h3:contains("test123")')->count());

        $courseTypeCountAfter = $this->countCourseTypes();

        $this->assertEquals(1, $courseTypeCountAfter - $courseTypeCountBefore);

        \TestDataManager::restoreDatabase();
    }

    public function testUpdate()
    {
        $client = $this->getAdminClient();

        $courseTypeCountBefore = $this->countCourseTypes();

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/kurs/type/1');

        $form = $crawler->selectButton('Lagre')->first()->form();

        $form['course_type[name]'] = 'edited course type';
        $form['course_type[description]'] = 'edited description';
        $form['course_type[challengesUrl]'] = 'http://edited.url';

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/kontrollpanel/kurs/type'));

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/kurs/type');

        $this->assertEquals(1, $crawler->filter('div.course h3:contains("edited course type")')->count());

        $courseTypeCountAfter = $this->countCourseTypes();

        $this->assertEquals($courseTypeCountBefore, $courseTypeCountAfter);

        \TestDataManager::restoreDatabase();
    }

    public function test404errorsAfterDeletingCourseType()
    {
        $client = $this->getAdminClient();

        $this->deleteFirstCourseType();

        $this->goToNotFound($client, '/kurs/1');

        $this->goToNotFound($client, '/kontrollpanel/kurs/1');
        $this->goToNotFound($client, '/kontrollpanel/kurs/timeplan/1');
        $this->goToNotFound($client, '/kontrollpanel/kurs/deltakere/1');
        $this->goToNotFound($client, '/kontrollpanel/kurs/veiledere/1');
    }

    public function testCourseTypePageAfterDeletingCourseType()
    {
        $courseTypeCountBefore = $this->countCourseTypes();

        $response = $this->deleteFirstCourseType();

        $this->assertTrue($response->isRedirect('/kontrollpanel/kurs/type'));

        $courseTypeCountAfter = $this->countCourseTypes();

        // Assert that there is one less course type after deleting
        $this->assertEquals(1, $courseTypeCountBefore - $courseTypeCountAfter);

        \TestDataManager::restoreDatabase();
    }

    public function testCoursesAdminPageAfterDeletingCourseType()
    {
        $courseCountBefore = $this->countCourses();

        $this->deleteFirstCourseType();

        $courseCountAfter = $this->countCourses();

        // Assert that there are less courses after deleting the first courseType
        $this->assertLessThan($courseCountBefore, $courseCountAfter);

        \TestDataManager::restoreDatabase();
    }

    public function testParticipantsPageAfterDeletingCourseType()
    {
        $participantCountBefore = $this->countParticipantsOnParticipantsPage();

        $this->deleteFirstCourseType();

        $participantCountAfter = $this->countParticipantsOnParticipantsPage();

        // Assert that there are less courses after deleting the first courseType
        $this->assertLessThan($participantCountBefore, $participantCountAfter);

        \TestDataManager::restoreDatabase();
    }

    public function testTutorsPageAfterDeletingCourseType()
    {
        $tutorCountBefore = $this->countTutorsOnTutorsPage();

        $this->deleteFirstCourseType();

        $tutorCountAfter = $this->countTutorsOnTutorsPage();

        // Assert that there are less courses after deleting the first courseType
        $this->assertLessThan($tutorCountBefore, $tutorCountAfter);

        \TestDataManager::restoreDatabase();
    }

    public function testSignupPageAfterDeletingCourseType()
    {
        $courseCountBefore = $this->countCoursesOnSignupPage();

        $this->deleteFirstCourseType();

        $courseCountAfter = $this->countCoursesOnSignupPage();

        // Assert that there are less courses after deleting the first courseType
        $this->assertLessThan($courseCountBefore, $courseCountAfter);

        \TestDataManager::restoreDatabase();
    }

    public function testCourseInfoPageAfterDeletingCourseType()
    {
        $courseCountBefore = $this->countCoursesOnCourseInfoPage();

        $this->deleteFirstCourseType();

        $courseCountAfter = $this->countCoursesOnCourseInfoPage();

        // Assert that there are less courses after deleting the first courseType
        $this->assertEquals(1, $courseCountBefore - $courseCountAfter);

        \TestDataManager::restoreDatabase();
    }

    public function testHomePageAfterDeletingCourseType()
    {
        $courseCountBefore = $this->countCourseTypesOnHomePage();

        $this->deleteFirstCourseType();

        $courseCountAfter = $this->countCourseTypesOnHomePage();

        // Assert that there are less courses after deleting the first courseType
        $this->assertEquals(1, $courseCountBefore - $courseCountAfter);

        \TestDataManager::restoreDatabase();
    }

    public function testTimetableAfterDeletingCourseType()
    {
        $courseCountBefore = $this->countCoursesInTimeTable();

        $this->deleteFirstCourseType();

        $courseCountAfter = $this->countCoursesInTimeTable();

        // Assert that there are less courses after deleting the first courseType
        $this->assertLessThan($courseCountBefore, $courseCountAfter);

        \TestDataManager::restoreDatabase();
    }

    private function submitCourseType(string $name, string $description, string $challengesUrl)
    {
        $client = $this->getAdminClient();

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/kurs/type/ny');

        $form = $crawler->selectButton('Lagre')->first()->form();

        $form['course_type[name]'] = $name;
        $form['course_type[description]'] = $description;
        $form['course_type[challengesUrl]'] = $challengesUrl;

        $client->submit($form);

        return $client->getResponse();
    }

    private function deleteFirstCourseType()
    {
        $client = $this->getAdminClient();

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/kurs/type');

        $form = $crawler->filter('button>i.fa-trash')->first()->parents()->form();

        $client->submit($form);

        return $client->getResponse();
    }

    private function countCourseTypes()
    {
        $client = $this->getAdminClient();

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/kurs/type');

        return $crawler->filter('div.course')->count();
    }

    private function countCourses()
    {
        $client = $this->getAdminClient();

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/kurs');

        return $crawler->filter('div.box-course-admin')->count();
    }

    private function countCoursesOnSignupPage()
    {
        $client = $this->getParticipantClient();

        $crawler = $this->goToSuccessful($client, '/pamelding');

        return $crawler->filter('div.box')->count();
    }

    private function countCoursesOnCourseInfoPage()
    {
        $client = $this->getAdminClient();

        $crawler = $this->goToSuccessful($client, '/kurs');

        return $crawler->filter('div.container>div.row')->count();
    }

    private function countCourseTypesOnHomePage()
    {
        $client = $this->getAdminClient();

        $crawler = $this->goToSuccessful($client, '/');

        return $crawler->filter('div.course')->count();
    }

    private function countCoursesInTimeTable()
    {
        $client = $this->getAdminClient();

        $crawler = $this->goToSuccessful($client, '/');

        return $crawler->filter('table#timeTable')->filter('tr')->count() - 1;
    }

    private function countParticipantsOnParticipantsPage()
    {
        $client = $this->getAdminClient();

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/deltakere');

        return $crawler->filter('tr')->count() - 1;
    }

    private function countTutorsOnTutorsPage()
    {
        $client = $this->getAdminClient();

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/veiledere');

        return $crawler->filter('tr')->count() - 1;
    }
}
