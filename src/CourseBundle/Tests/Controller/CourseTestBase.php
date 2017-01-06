<?php

namespace CourseBundle\Tests\Controller;

use CodeClubBundle\Tests\CodeClubWebTestCase;

class CourseTestBase extends CodeClubWebTestCase
{
    protected function countCourseTypesOnCourseTypePage()
    {
        $client = $this->getAdminClient();

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/kurs/type');

        return $crawler->filter('div.course')->count();
    }

    protected function countCoursesOnCoursePage()
    {
        $client = $this->getAdminClient();

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/kurs');

        return $crawler->filter('div.box-course-admin')->count();
    }

    protected function countCoursesOnSignupPage()
    {
        $client = $this->getParticipantClient();

        $crawler = $this->goToSuccessful($client, '/pamelding');

        return $crawler->filter('div.box')->count();
    }

    protected function countCoursesOnCourseInfoPage()
    {
        $client = $this->getAdminClient();

        $crawler = $this->goToSuccessful($client, '/kurs');

        return $crawler->filter('div.container>div.row')->count();
    }

    protected function countCourseTypesOnHomePage()
    {
        $client = $this->getAdminClient();

        $crawler = $this->goToSuccessful($client, '/');

        return $crawler->filter('div.course')->count();
    }

    protected function countCoursesInTimeTable()
    {
        $client = $this->getAdminClient();

        $crawler = $this->goToSuccessful($client, '/');

        return $crawler->filter('table#timeTable')->filter('tr')->count() - 1;
    }

    protected function countParticipantsOnParticipantsPage()
    {
        $client = $this->getAdminClient();

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/deltakere');

        return $crawler->filter('tr')->count() - 1;
    }

    protected function countTutorsOnTutorsPage()
    {
        $client = $this->getAdminClient();

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/veiledere');

        return $crawler->filter('tr')->count() - 1;
    }
}
