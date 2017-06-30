<?php

namespace UserBundle\Tests\Controller;

use AppBundle\Tests\AppWebTestCase;

class AdminUserControllerTest extends AppWebTestCase
{
    private $participant = array(
        'name' => 'Participant Participant',
        'id' => 2,
    );
    private $parent = array(
        'name' => 'Parent 10',
        'id' => 35,
        'childrenNames' => array('Child 20', 'Child 21'),
    );
    private $tutor = array(
        'name' => 'Tutor Tutor',
        'id' => 4,
    );

    public function testDeleteParticipantUser()
    {
        $this->assertThatParticipantIsParticipatingInCourse($this->participant);

        $this->assertSuccessfulDeleteUser($this->participant);

        $this->assertThatParticipantIsNotParticipationInAnyCourse($this->participant);

        \TestDataManager::restoreDatabase();
    }

    public function testDeleteTutorUser()
    {
        $this->assertThatTutorIsTutoringInCourse($this->tutor);

        $this->assertSuccessfulDeleteUser($this->tutor);

        $this->assertThatTutorIsNotTutoringInAnyCourse($this->tutor);

        \TestDataManager::restoreDatabase();
    }

    public function testDeleteParentUser()
    {
        $this->assertThatChildrenAreParticipatingInCourse($this->parent);

        $this->assertSuccessfulDeleteUser($this->parent);

        $this->assertThatChildrenAreNotParticipatingInAnyCourse($this->parent);

        \TestDataManager::restoreDatabase();
    }

    public function testAdminDeleteHimself()
    {
        $client = $this->getAdminClient();

        $response = $this->post($client, '/kontrollpanel/bruker/slett', array(
           'userId' => 1,
        ));

        $this->assertEquals(403, $response->getStatusCode());

        \TestDataManager::restoreDatabase();
    }

    public function testAdminChangeOwnRole()
    {
        $client = $this->getAdminClient();

        $response = $this->post($client, '/kontrollpanel/bruker/type', array(
            'userId' => 1,
            'role' => 'participant',
        ));

        $this->assertEquals(400, $response->getStatusCode());

        \TestDataManager::restoreDatabase();
    }

    public function testChangeUserRolesFromParticipant()
    {
        $this->assertSuccessfulChangeUserRoleFromParticipantTo('Foresatt');
        \TestDataManager::restoreDatabase();

        $this->assertSuccessfulChangeUserRoleFromParticipantTo('Veileder');
        \TestDataManager::restoreDatabase();

        $this->assertSuccessfulChangeUserRoleFromParticipantTo('Admin');
        \TestDataManager::restoreDatabase();
    }

    public function testChangeUserRolesFromParent()
    {
        $this->assertSuccessfulChangeUserRoleFromParentTo('Deltaker');
        \TestDataManager::restoreDatabase();

        $this->assertSuccessfulChangeUserRoleFromParentTo('Veileder');
        \TestDataManager::restoreDatabase();

        $this->assertSuccessfulChangeUserRoleFromParentTo('Admin');
        \TestDataManager::restoreDatabase();
    }

    public function testChangeUserRolesFromTutor()
    {
        $this->assertSuccessfulChangeUserRoleFromTutorTo('Deltaker');
        \TestDataManager::restoreDatabase();

        $this->assertSuccessfulChangeUserRoleFromTutorTo('Foresatt');
        \TestDataManager::restoreDatabase();

        $this->assertSuccessfulChangeUserRoleFromTutorTo('Admin');
        \TestDataManager::restoreDatabase();
    }

    private function assertSuccessfulDeleteUser($user)
    {
        $client = $this->getAdminClient();

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/brukere');

        $pagesCount = $crawler->filter('.pagination')->children()->count() - 2;

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/brukere?page='.$pagesCount);

        $userCountBefore = $crawler->filter('.content')->first()->filter('tr')->count();

        // "Click" the delete button. Can't execute javascript so we have to create the POST request manually.
        $response = $this->post($client, '/kontrollpanel/bruker/slett', array('userId' => $user['id']));

        $this->assertTrue($response->isSuccessful());

        // Refresh page
        $crawler = $this->goToSuccessful($client, '/kontrollpanel/brukere?page='.$pagesCount);

        $userCountAfter = $crawler->filter('.content')->first()->filter('tr')->count();

        // Assert that there is one less user in the list
        $this->assertEquals(1, $userCountBefore - $userCountAfter);
    }

    private function assertSuccessfulChangeUserRoleFromParticipantTo($toRole)
    {
        $this->assertThatParticipantIsParticipatingInCourse($this->participant);

        $this->assertChangeUserRoleIsSuccessful($this->participant, 'Deltaker', $toRole);

        $this->assertThatParticipantIsNotParticipationInAnyCourse($this->participant);
    }

    private function assertSuccessfulChangeUserRoleFromParentTo($toRole)
    {
        $this->assertThatChildrenAreParticipatingInCourse($this->parent);

        $this->assertChangeUserRoleIsSuccessful($this->parent, 'Foresatt', $toRole);

        $this->assertThatChildrenAreNotParticipatingInAnyCourse($this->parent);
    }

    private function assertSuccessfulChangeUserRoleFromTutorTo($toRole)
    {
        $this->assertThatTutorIsTutoringInCourse($this->tutor);

        $this->assertChangeUserRoleIsSuccessful($this->tutor, 'Veileder', $toRole);

        if ($toRole === 'Admin') {
            $this->assertThatTutorIsTutoringInCourse($this->tutor);
        } else {
            $this->assertThatTutorIsNotTutoringInAnyCourse($this->tutor);
        }
    }

    private function assertChangeUserRoleIsSuccessful($user, $fromRole, $toRole)
    {
        $client = $this->getAdminClient();

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/brukere?search='.$user['name']);

        // Assert that user exists
        $this->assertEquals(1, $crawler->filter('tr:contains("'.$user['name'].'")')->count());

        // Assert that user is '$fromRole'
        $this->assertEquals(1, $crawler->filter('tr:contains("'.$user['name'].'")')->filter('td:contains("'.$fromRole.'")')->count());

        $response = $this->post($client, '/kontrollpanel/bruker/type', array(
            'userId' => $user['id'],
            'role' => $this->translateRole($toRole),
        ));

        $this->assertTrue($response->isSuccessful());

        // Refresh page
        $crawler = $this->goToSuccessful($client, '/kontrollpanel/brukere?search='.$user['name']);

        // Assert that the user still exists
        $this->assertEquals(1, $crawler->filter('tr:contains("'.$user['name'].'")')->count());

        // Assert that user is 'toRole'
        $this->assertEquals(0, $crawler->filter('tr:contains("'.$user['name'].'")')->filter('td:contains("'.$fromRole.'")')->count());
        $this->assertEquals(1, $crawler->filter('tr:contains("'.$user['name'].'")')->filter('td:contains("'.$toRole.'")')->count());
    }

    private function assertThatChildrenAreParticipatingInCourse($user)
    {
        $client = $this->getAdminClient();

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/deltakere');

        // Assert that the children are participating in at least one course
        foreach ($user['childrenNames'] as $childName) {
            $this->assertGreaterThanOrEqual(1, $crawler->filter('tr:contains("'.$childName.'")')->count());
        }
    }

    private function assertThatChildrenAreNotParticipatingInAnyCourse($user)
    {
        $client = $this->getAdminClient();

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/deltakere');

        // Assert that the children are not participating in any course
        foreach ($user['childrenNames'] as $childName) {
            $this->assertEquals(0, $crawler->filter('tr:contains("'.$childName.'")')->count());
        }
    }

    private function assertThatParticipantIsParticipatingInCourse($user)
    {
        $client = $this->getAdminClient();

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/deltakere');

        // Assert that the user is participating in at least one course
        $this->assertGreaterThanOrEqual(1, $crawler->filter('tr:contains("'.$user['name'].'")')->count());
    }

    private function assertThatParticipantIsNotParticipationInAnyCourse($user)
    {
        $client = $this->getAdminClient();

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/deltakere');

        // Assert that the user no longer is participating in at any courses
        $this->assertEquals(0, $crawler->filter('tr:contains("'.$user['name'].'")')->count());
    }

    private function assertThatTutorIsTutoringInCourse($user)
    {
        $client = $this->getAdminClient();

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/veiledere');

        // Assert that the user is tutoring in at least one course
        $this->assertGreaterThanOrEqual(1, $crawler->filter('tr:contains("'.$user['name'].'")')->count());
    }

    private function assertThatTutorIsNotTutoringInAnyCourse($user)
    {
        $client = $this->getAdminClient();

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/veiledere');

        // Assert that the user no longer is tutoring in at any courses
        $this->assertEquals(0, $crawler->filter('tr:contains("'.$user['name'].'")')->count());
    }

    private function translateRole($role)
    {
        switch ($role) {
            case 'Deltaker':
                return 'participant';
            case 'Foresatt':
                return 'parent';
            case 'Veileder':
                return 'tutor';
            case 'Admin':
                return 'admin';
        }
    }
}
