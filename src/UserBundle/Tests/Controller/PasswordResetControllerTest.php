<?php

namespace UserBundle\Tests\Controller;

use AppBundle\Tests\AppWebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class PasswordResetControllerTest extends AppWebTestCase
{
    const email = 'admin@mail.no';
    const newPassword = 'resetpassword123';

    public function testResetPassword()
    {
        $client = $this->getAnonClient();

        $code = $this->requestPasswordResetAndGetResetCode($client, self::email);

        $this->resetPassword($code, self::email, self::newPassword);

        $this->assertLoginSuccessful(self::email, self::newPassword);

        \TestDataManager::restoreDatabase();
    }

    public function testResetPasswordWithInvalidEmail()
    {
        $client = $this->getAnonClient();

        $crawler = $this->requestPasswordReset($client, 'fakeemail@notin.db');

        $this->assertNotTrue($client->getResponse()->isRedirection());

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Ingen brukere er registrert med")')->count());

        \TestDataManager::restoreDatabase();
    }

    private function requestPasswordResetAndGetResetCode(Client $client, string $email)
    {
        $enable_profiler = true;
        $this->requestPasswordReset($client, $email, $enable_profiler);

        $mailCollector = $client->getProfile()->getCollector('swiftmailer');

        // Check that an email was sent
        $this->assertEquals(1, $mailCollector->getMessageCount());
        $message = $mailCollector->getMessages()[0];

        $body = $message->getBody();

        return $this->extractResetCodeFromMessageBody($body);
    }

    private function requestPasswordReset(Client $client, string $email, bool $enableProfiler = false)
    {
        $crawler = $this->goToSuccessful($client, '/reset_passord');

        $form = $crawler->selectButton('Tilbakestill passord')->first()->form();

        $form['passwordReset[email]'] = $email;

        if ($enableProfiler) {
            $client->enableProfiler();
        }

        return $client->submit($form);
    }

    private function extractResetCodeFromMessageBody(string $body)
    {
        $start = strpos($body, 'reset_passord/') + 14;

        $messageStartingWithCode = substr($body, $start);

        $end = strpos($messageStartingWithCode, "\n");

        return substr($body, $start, $end);
    }

    private function resetPassword(string $code, string $email, string $newPassword)
    {
        $client = $this->getAnonClient();

        $this->submitNewPassword($client, $code, $newPassword);

        $crawler = $client->getCrawler();

        $this->assertEquals(1, $crawler->filter('h1:contains("Innlogging")')->count());

        $form = $crawler->selectButton('Logg inn')->first()->form();

        // Assert that the email is already filled in on form
        $this->assertEquals($email, $form['_username']->getValue());
    }

    private function submitNewPassword(Client $client, string $code, string $newPassword)
    {
        $crawler = $this->goToSuccessful($client, "/reset_passord/$code");

        $form = $crawler->selectButton('Lagre nytt passord')->first()->form();

        $form['newPassword[password][first]'] = $newPassword;
        $form['newPassword[password][second]'] = $newPassword;

        $client->submit($form);
    }

    private function assertLoginSuccessful(string $email, string $password)
    {
        $client = $this->createClient(array(), array(
            'PHP_AUTH_USER' => $email,
            'PHP_AUTH_PW' => $password,
        ));

        $crawler = $this->goToSuccessful($client, '/');

        $this->assertEquals(0, $crawler->filter('nav:contains("Logg inn")')->count());
    }
}
