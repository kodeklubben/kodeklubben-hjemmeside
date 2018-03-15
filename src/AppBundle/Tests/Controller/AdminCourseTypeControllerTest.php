<?php

namespace AppBundle\Tests\Controller;

class AdminCourseTypeControllerTest extends CourseTestBase
{
    public function testCreate()
    {
        $client = $this->getAdminClient();

        $courseTypeCountBefore = $this->countCourseTypesOnCourseTypePage();

        $response = $this->submitCourseType('test123', 'test123description', 'http://test123.com');

        $this->assertTrue($response->isRedirect('/kontrollpanel/kurs/type'));

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/kurs/type');

        $this->assertEquals(1, $crawler->filter('div.course h3:contains("test123")')->count());

        $courseTypeCountAfter = $this->countCourseTypesOnCourseTypePage();

        $this->assertEquals(1, $courseTypeCountAfter - $courseTypeCountBefore);

        \TestDataManager::restoreDatabase();
    }

    public function testErrorWhenCreateCourseTypeWithSameNameTwice()
    {
        $client = $this->getAdminClient();

        $courseTypeCountBefore = $this->countCourseTypesOnCourseTypePage();

        $response = $this->submitCourseType('test123', 'test123description', 'http://test123.com');
        $this->assertTrue($response->isRedirect('/kontrollpanel/kurs/type'));

        $response = $this->submitCourseType('test123', 'test2description', 'http://test2.com');
        $this->assertTrue(!$response->isRedirection());

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/kurs/type');

        $this->assertEquals(1, $crawler->filter('div.course h3:contains("test123")')->count());

        $courseTypeCountAfter = $this->countCourseTypesOnCourseTypePage();

        $this->assertEquals(1, $courseTypeCountAfter - $courseTypeCountBefore);

        \TestDataManager::restoreDatabase();
    }

    public function testUpdate()
    {
        $client = $this->getAdminClient();

        $courseTypeCountBefore = $this->countCourseTypesOnCourseTypePage();

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/kurs/type/1');

        $form = $crawler->selectButton('Lagre')->first()->form();

        $form['course_type[name]'] = 'edited course type';
        $form['course_type[description]'] = 'edited description';
        $form['course_type[challengesUrl]'] = 'http://edited.url';

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/kontrollpanel/kurs/type'));

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/kurs/type');

        $this->assertEquals(1, $crawler->filter('div.course h3:contains("edited course type")')->count());

        $courseTypeCountAfter = $this->countCourseTypesOnCourseTypePage();

        $this->assertEquals($courseTypeCountBefore, $courseTypeCountAfter);

        \TestDataManager::restoreDatabase();
    }

    public function test404errorsAfterDeletingCourseType()
    {
        $client = $this->getAdminClient();

        $this->deleteFirstCourseType();

        $this->goToNotFound($client, '/kurs/1');

        $this->goToNotFound($client, '/kontrollpanel/kurs/1');
        $this->goToNotFound($client, '/kontrollpanel/kurs/type/1');
        $this->goToNotFound($client, '/kontrollpanel/kurs/deltakere/1');
        $this->goToNotFound($client, '/kontrollpanel/kurs/veiledere/1');
        $this->goToNotFound($client, '/kontrollpanel/kurs/veiledere/1');

        \TestDataManager::restoreDatabase();
    }

    public function testCourseTypePageAfterDeletingCourseType()
    {
        $courseTypeCountBefore = $this->countCourseTypesOnCourseTypePage();

        $response = $this->deleteFirstCourseType();

        $this->assertTrue($response->isRedirect('/kontrollpanel/kurs/type'));

        $courseTypeCountAfter = $this->countCourseTypesOnCourseTypePage();

        // Assert that there is one less course type after deleting
        $this->assertEquals(1, $courseTypeCountBefore - $courseTypeCountAfter);

        \TestDataManager::restoreDatabase();
    }

    public function testCoursesAdminPageAfterDeletingCourseType()
    {
        $courseCountBefore = $this->countCoursesOnCoursePage();

        $this->deleteFirstCourseType();

        $courseCountAfter = $this->countCoursesOnCoursePage();

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
}
