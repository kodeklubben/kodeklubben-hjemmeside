<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\AppWebTestCase;

class MessageControllerTest extends AppWebTestCase
{
    const NEW_MESSAGE_CONTENT = 'new_test_message';

    public function testCreateMessage()
    {
        $this->assertEquals(0, $this->countMessagesOnHomepageContaining(self::NEW_MESSAGE_CONTENT));

        $expirationDate = (new \DateTime())->modify('+1 day');

        $this->submitNewMessage(self::NEW_MESSAGE_CONTENT, $expirationDate);

        $this->assertEquals(1, $this->countMessagesOnHomepageContaining(self::NEW_MESSAGE_CONTENT));

        \TestDataManager::restoreDatabase();
    }

    public function testCreateExpiredMessage()
    {
        $this->assertEquals(0, $this->countMessagesOnHomepageContaining(self::NEW_MESSAGE_CONTENT));

        $expirationDate = (new \DateTime())->modify('-1 day');

        $this->submitNewMessage(self::NEW_MESSAGE_CONTENT, $expirationDate);

        $this->assertEquals(0, $this->countMessagesOnHomepageContaining(self::NEW_MESSAGE_CONTENT));

        \TestDataManager::restoreDatabase();
    }

    public function testDeleteMessage()
    {
        $messageCountBefore = $this->countMessagesOnHomepageContaining('');

        // First message fixture is set to expire in the future
        $this->deleteFirstMessage();

        $messageCountAfter = $this->countMessagesOnHomepageContaining('');

        $this->assertEquals(1, $messageCountBefore - $messageCountAfter);

        \TestDataManager::restoreDatabase();
    }

    private function countMessagesOnHomepageContaining(string $messageContent)
    {
        $client = $this->getAnonClient();

        $crawler = $this->goToSuccessful($client, '/');

        return $crawler->filter('p.message:contains("'.$messageContent.'")')->count();
    }

    private function submitNewMessage(string $messageContent, \DateTime $expirationDateTime)
    {
        $client = $this->getAdminClient();

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/melding');

        $form = $crawler->selectButton('Lagre melding')->first()->form();

        $form['message[message]'] = $messageContent;
        $form['message[expireDate][date][year]'] = intval($expirationDateTime->format('Y'));
        $form['message[expireDate][date][month]'] = intval($expirationDateTime->format('n'));
        $form['message[expireDate][date][day]'] = intval($expirationDateTime->format('j'));
        $form['message[expireDate][time][hour]'] = intval($expirationDateTime->format('G'));
        $form['message[expireDate][time][minute]'] = intval($expirationDateTime->format('i'));

        $client->submit($form);
    }

    private function deleteFirstMessage()
    {
        $client = $this->getAdminClient();

        $crawler = $this->goToSuccessful($client, '/kontrollpanel/melding');

        $form = $crawler->filter('button.btn-danger')->first()->form();

        $client->submit($form);
    }
}
