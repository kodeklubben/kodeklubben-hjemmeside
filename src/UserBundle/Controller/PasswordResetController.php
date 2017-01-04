<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use UserBundle\Entity\PasswordReset;
use UserBundle\Form\Type\NewPasswordType;
use UserBundle\Form\Type\PasswordResetType;

/**
 * Class PasswordResetController.
 */
class PasswordResetController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * Shows the request new password page
     */
    public function showAction(Request $request)
    {
        // Check if user is already logged in
        if (!is_null($this->getUser())) {
            return $this->redirect('/');
        }

        //Creates new PasswordReset entity
        $passwordReset = new PasswordReset();

        //Creates new PasswordResetType Form
        $form = $this->createForm(PasswordResetType::class, $passwordReset, array(
            'validation_groups' => array('password_reset'),
        ));

        $form->handleRequest($request);

        //Checks if the form is valid
        if ($form->isValid()) {
            //Creates a reset password-Entity and sends a reset url by Email to the user. if the username and email is correct
            if ($this->createResetPasswordEntity($form, $passwordReset)) {
                return $this->render('@CodeClub/reset_password/confirmation.html.twig', array('email' => $form->get('email')->getData()));
            }
        }
        //Render reset_password twig with the form.
        return $this->render('@CodeClub/reset_password/reset_password.html.twig', array('form' => $form->createView()));
    }

    /**
     * @param Form          $form
     * @param PasswordReset $passwordReset
     *
     * @return bool
     *
     * Creates a resetPassword field in the resetPassword entity, with a reset code, date and the user who want to reset the password.
     * The function sends an email with an url to the user where the user can reset the password
     */
    private function createResetPasswordEntity(Form $form, PasswordReset $passwordReset)
    {

        //Connects with the User Entity
        $repositoryUser = $this->getDoctrine()->getRepository('UserBundle:User');

        //Gets the email that is typed in the text-field
        $email = $form->get('email')->getData();

        //Finds the user based on the email
        $user = $repositoryUser->findUserByEmail($email);

        if (is_null($user)) {
            //Error message
            $this->get('session')->getFlashBag()->add('errorMessage', '<em>Ingen brukere er registrert med <span class="text-danger">'.$email.'</span></em>');

            return false;
        }

        //Creates a random hex-string as reset code
        $resetCode = bin2hex(openssl_random_pseudo_bytes(12));

        //Hashes the random reset code to store in the database
        $hashedResetCode = hash('sha512', $resetCode, false);

        //creates a DateTime objekt for the table, this is to have a expiration time for the reset code
        $time = new \DateTime();

        //Delete old resetcodes from the database
        $repositoryPasswordReset = $this->getDoctrine()->getRepository('UserBundle:PasswordReset');
        $repositoryPasswordReset->deletePasswordResetsByUser($user);

        //Adds the info in the passwordReset entity
        $passwordReset->setUser($user);
        $passwordReset->setResetTime($time);
        $passwordReset->setHashedResetCode($hashedResetCode);
        $em = $this->getDoctrine()->getManager();
        $em->persist($passwordReset);
        $em->flush();

        //Sends a email with the url for resetting the password
        /*
         * @var \Swift_Mime_Message
         */
        $emailMessage = \Swift_Message::newInstance()
            ->setSubject('Tilbakestill passord for kodeklubben.no')
            ->setFrom('ikkesvar@kodeklubben.no')
            ->setTo($email)
            ->setBody($this->renderView('@CodeClub/reset_password/new_password_email.txt.twig', array('reseturl' => $this->generateUrl('password_new', array('code' => $resetCode), UrlGeneratorInterface::ABSOLUTE_URL))));
        $this->get('mailer')->send($emailMessage);

        return true;
    }

    /**
     * @param $code
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * This function resets stores the new password when the user goes to the url for resetting the password
     */
    public function resetPasswordAction($code, Request $request)
    {
        // Check if user is already logged in
        if (!is_null($this->getUser())) {
            return $this->redirect('/');
        }

        $repositoryPasswordReset = $this->getDoctrine()->getRepository('UserBundle:PasswordReset');

        //Creates a DateTime to know the current time
        $currentTime = new \DateTime();

        //Stores the resetcode that was sent from the url
        $resetCode = $code;
        //hashes the resetcode with the same hash that is stored in the database.
        $hashedResetCode = hash('sha512', $resetCode, false);
        //Retrieves the PasswordReset object with the hashed reset code
        $passwordReset = $repositoryPasswordReset->findPasswordResetByHashedResetCode($hashedResetCode);

        if (is_null($passwordReset)) {
            //If the resetcode that is provided does not exist in the database, the user is redirected to home
            return $this->redirect('/');
        }

        //Finds the user based on the provided reset code.
        $user = $passwordReset->getUser();

        //Creates a new newPasswordType form, and send in user so that it is the password for the correct user that is changed.
        $form = $this->createForm(NewPasswordType::class);

        //Handles the request from the form
        $form->handleRequest($request);

        //Finds the time difference from when the resetcode was collected, and now.
        $timeDifference = date_diff($passwordReset->getResetTime(), $currentTime);

        //Checks if the reset code is more than one day old(24 hours)
        if ($timeDifference->d < 1) {
            //checks if the form is valid(the information is stored correctly in the user object)
            if ($form->isValid()) {
                //Deletes the resetcode, so it can only be used one time.
                $repositoryPasswordReset->deletePasswordResetByHashedResetCode($hashedResetCode);
                $plainPassword = $form->get('password')->getData();
                $encoder = $this->get('security.password_encoder');
                $hashedPassword = $encoder->encodePassword($user, $plainPassword);
                $user->setPassword($hashedPassword);
                //Updates the database
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                //renders the login page
                return $this->render('@User/login.html.twig', array(
                    'last_username' => $user->getEmail(),
                    'error' => null,
                ));
            }
        } //If the reset code is more than 1 day old.
        else {
            //Deletes the resetcode
            $repositoryPasswordReset->deletePasswordResetByHashedResetCode($hashedResetCode);
            //creates a message that states the problem
            $feedback = 'Denne linken er utløpt. Skriv inn E-post for å få tilsendt en ny.';
            //Render the reset_password twig with the message, so the user can get a new reset code.
            return $this->render('@CodeClub/reset_password/reset_password.html.twig', array('message' => $feedback));
        }

        return $this->render('@CodeClub/reset_password/new_password.html.twig', array('form' => $form->createView()));
    }
}
