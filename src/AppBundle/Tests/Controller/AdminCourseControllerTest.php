<?php

namespace AppBundle\Tests\Controller;

class AdminCourseControllerTest extends CourseTestBase
{
    public function testCreate()
    {
        $client = $this->getAdminClient();

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/kurs');

        // Assert that the new course does not exists
        $courseCountBefore = $crawler->filter('div.box-course-admin')->count();

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/kurs/ny');

        $form = $crawler->selectButton('Lagre')->form();

        // Create new course
        $form['course_form[name]'] = 'test';
        $form['course_form[description]'] = 'test';
        $form['course_form[courseType]'] = '1';
        $form['course_form[participantLimit]'] = 20;
        $form['course_form[semester]'] = '1';

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/kontrollpanel/kurs'));

        $crawler = $client->followRedirect();

        // Assert that the new course now exists
        $this->assertEquals(1, $crawler->filter('div.box-header>h3:contains("test")')->count());

        $courseCountAfter = $crawler->filter('div.box-course-admin')->count();

        // Assert that there is one more course
        $this->assertEquals(1, $courseCountAfter - $courseCountBefore);

        \TestDataManager::restoreDatabase();
    }

    public function testEdit()
    {
        $client = $this->getAdminClient();

        $courseCountBefore = $this->countCoursesOnCoursePage();

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/kurs');

        // Assert that the new course does not exists
        $this->assertEquals(0, $crawler->filter('div.box-header>h3:contains("test")')->count());

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/kurs/1');

        $form = $crawler->selectButton('Lagre')->form();

        // Create new course
        $form['course_form[name]'] = 'test';
        $form['course_form[description]'] = 'test';
        $form['course_form[courseType]'] = '1';
        $form['course_form[participantLimit]'] = 20;
        $form['course_form[semester]'] = '1';

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/kontrollpanel/kurs'));

        $crawler = $client->followRedirect();

        // Assert that the new course now exists
        $this->assertEquals(1, $crawler->filter('div.box-header>h3:contains("test")')->count());

        $courseCountAfter = $this->countCoursesOnCoursePage();

        // Assert that no new course has been added
        $this->assertEquals($courseCountAfter, $courseCountBefore);

        \TestDataManager::restoreDatabase();
    }

    public function test404errorsAfterDeletingCourse()
    {
        $client = $this->getAdminClient();

        $this->deleteFirstCourse();

        $this->goToNotFound($client, '/kurs/1');

        $this->goToNotFound($client, '/kontrollpanel/kurs/1');
        $this->goToNotFound($client, '/kontrollpanel/kurs/timeplan/1');
        $this->goToNotFound($client, '/kontrollpanel/kurs/deltakere/1');
        $this->goToNotFound($client, '/kontrollpanel/kurs/veiledere/1');

        \TestDataManager::restoreDatabase();
    }

    public function testCoursePageAfterDeletingCourse()
    {
        $courseCountBefore = $this->countCoursesOnCoursePage();

        $response = $this->deleteFirstCourse();

        $this->assertTrue($response->isRedirect('/kontrollpanel/kurs'));

        $courseCountAfter = $this->countCoursesOnCoursePage();

        // Assert that there is one less course type after deleting
        $this->assertEquals(1, $courseCountBefore - $courseCountAfter);

        \TestDataManager::restoreDatabase();
    }

    public function testParticipantsPageAfterDeletingCourse()
    {
        $participantCountBefore = $this->countParticipantsOnParticipantsPage();

        $this->deleteFirstCourse();

        $participantCountAfter = $this->countParticipantsOnParticipantsPage();

        // Assert that there are less courses after deleting the first courseType
        $this->assertLessThan($participantCountBefore, $participantCountAfter);

        \TestDataManager::restoreDatabase();
    }

    public function testTutorsPageAfterDeletingCourse()
    {
        $tutorCountBefore = $this->countTutorsOnTutorsPage();

        $this->deleteFirstCourse();

        $tutorCountAfter = $this->countTutorsOnTutorsPage();

        // Assert that there are less courses after deleting the first courseType
        $this->assertLessThan($tutorCountBefore, $tutorCountAfter);

        \TestDataManager::restoreDatabase();
    }

    public function testSignupPageAfterDeletingCourse()
    {
        $courseCountBefore = $this->countCoursesOnSignupPage();

        $this->deleteFirstCourse();

        $courseCountAfter = $this->countCoursesOnSignupPage();

        // Assert that there are less courses after deleting the first courseType
        $this->assertLessThan($courseCountBefore, $courseCountAfter);

        \TestDataManager::restoreDatabase();
    }

    public function testTimetableAfterDeletingCourse()
    {
        $courseCountBefore = $this->countCoursesInTimeTable();

        $this->deleteFirstCourse();

        $courseCountAfter = $this->countCoursesInTimeTable();

        // Assert that there are less courses after deleting the first courseType
        $this->assertLessThan($courseCountBefore, $courseCountAfter);

        \TestDataManager::restoreDatabase();
    }

    private function deleteFirstCourse()
    {
        $client = $this->getAdminClient();

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/kurs');

        $form = $crawler->filter('button>i.fa-trash')->first()->parents()->form();

        $client->submit($form);

        return $client->getResponse();
    }
}
