<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\StaticContent;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadStaticContentData extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $sc1 = new StaticContent();
        $sc1->setIdString('region');
        $sc1->setContent('Trondheim');
        $sc1->setLastEditedBy($this->getReference('user-admin'));

        $manager->persist($sc1);

        $sc2 = new StaticContent();
        $sc2->setIdString('email');
        $sc2->setContent('trondheim@kodeklubben.no');
        $sc2->setLastEditedBy($this->getReference('user-admin'));

        $manager->persist($sc2);

        $sc_facebook = new StaticContent();
        $sc_facebook->setIdString('facebook');
        $sc_facebook->setContent('kodeklubbentrondheim');
        $sc_facebook->setLastEditedBy($this->getReference('user-admin'));

        $manager->persist($sc_facebook);

        $sc_header = new StaticContent();
        $sc_header->setIdString('header');
        $sc_header->setContent('Påmeldingen er åpen og det er nå mulig å melde seg som deltakere, veiledere og reserve på de forskjellige kursene. Under er mer detaljer over de forskjellige kursene. Alle kursene blir holdt på Realfagsbygget ved NTNU fra klokken 1815-2000.');
        $sc_header->setLastEditedBy($this->getReference('user-admin'));

        $manager->persist($sc_header);

        $sc_tagline = new StaticContent();
        $sc_tagline->setIdString('tagline');
        $sc_tagline->setContent('Lær programmering med artige oppgaver fra Kodeklubben Trondheim!');
        $sc_tagline->setLastEditedBy($this->getReference('user-admin'));

        $manager->persist($sc_tagline);

        $sc_participant_info = new StaticContent();
        $sc_participant_info->setIdString('participant_info');
        $sc_participant_info->setContent('Tekst om deltaker.

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer non felis laoreet sem tempor tincidunt id non enim. Mauris finibus imperdiet tempor. Fusce non ex in sapien tincidunt faucibus sit amet id nulla. Vivamus eget finibus libero, sed tincidunt turpis. Aenean ac est id lorem dignissim scelerisque vel aliquam mi. Pellentesque hendrerit porttitor quam, quis fermentum nisl accumsan varius. Etiam ultricies lectus at augue bibendum, sit amet suscipit libero dictum. In ut arcu enim. Praesent bibendum massa justo, ut eleifend nibh facilisis at. Fusce nec est imperdiet, accumsan nulla eget, lacinia ante. Suspendisse eros ipsum, congue ac facilisis sit amet, euismod in turpis. Quisque mattis vehicula est quis pellentesque. Proin sit amet finibus felis. Vivamus id porta mauris.');
        $sc_participant_info->setLastEditedBy($this->getReference('user-admin'));

        $manager->persist($sc_participant_info);

        $sc_tutor_info = new StaticContent();
        $sc_tutor_info->setIdString('tutor_info');
        $sc_tutor_info->setContent('Tekst om veileder.

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer non felis laoreet sem tempor tincidunt id non enim. Mauris finibus imperdiet tempor. Fusce non ex in sapien tincidunt faucibus sit amet id nulla. Vivamus eget finibus libero, sed tincidunt turpis. Aenean ac est id lorem dignissim scelerisque vel aliquam mi. Pellentesque hendrerit porttitor quam, quis fermentum nisl accumsan varius. Etiam ultricies lectus at augue bibendum, sit amet suscipit libero dictum. In ut arcu enim. Praesent bibendum massa justo, ut eleifend nibh facilisis at. Fusce nec est imperdiet, accumsan nulla eget, lacinia ante. Suspendisse eros ipsum, congue ac facilisis sit amet, euismod in turpis. Quisque mattis vehicula est quis pellentesque. Proin sit amet finibus felis. Vivamus id porta mauris.');
        $sc_tutor_info->setLastEditedBy($this->getReference('user-admin'));

        $manager->persist($sc_tutor_info);

        $manager->flush();

        $sc_about_participant = new StaticContent();
        $sc_about_participant->setIdString('about_participant');
        $sc_about_participant->setContent('Tekst om deltakere.

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer non felis laoreet sem tempor tincidunt id non enim. Mauris finibus imperdiet tempor. Fusce non ex in sapien tincidunt faucibus sit amet id nulla. Vivamus eget finibus libero, sed tincidunt turpis. Aenean ac est id lorem dignissim scelerisque vel aliquam mi. Pellentesque hendrerit porttitor quam, quis fermentum nisl accumsan varius. Etiam ultricies lectus at augue bibendum, sit amet suscipit libero dictum. In ut arcu enim. Praesent bibendum massa justo, ut eleifend nibh facilisis at. Fusce nec est imperdiet, accumsan nulla eget, lacinia ante. Suspendisse eros ipsum, congue ac facilisis sit amet, euismod in turpis. Quisque mattis vehicula est quis pellentesque. Proin sit amet finibus felis. Vivamus id porta mauris.');
        $sc_about_participant->setLastEditedBy($this->getReference('user-admin'));

        $manager->persist($sc_about_participant);

        $sc_about_tutor = new StaticContent();
        $sc_about_tutor->setIdString('about_tutor');
        $sc_about_tutor->setContent('Tekst om veiledere.

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer non felis laoreet sem tempor tincidunt id non enim. Mauris finibus imperdiet tempor. Fusce non ex in sapien tincidunt faucibus sit amet id nulla. Vivamus eget finibus libero, sed tincidunt turpis. Aenean ac est id lorem dignissim scelerisque vel aliquam mi. Pellentesque hendrerit porttitor quam, quis fermentum nisl accumsan varius. Etiam ultricies lectus at augue bibendum, sit amet suscipit libero dictum. In ut arcu enim. Praesent bibendum massa justo, ut eleifend nibh facilisis at. Fusce nec est imperdiet, accumsan nulla eget, lacinia ante. Suspendisse eros ipsum, congue ac facilisis sit amet, euismod in turpis. Quisque mattis vehicula est quis pellentesque. Proin sit amet finibus felis. Vivamus id porta mauris.');
        $sc_about_tutor->setLastEditedBy($this->getReference('user-admin'));

        $manager->persist($sc_about_tutor);

        $manager->flush();

        $this->setReference('sc-region', $sc1);
        $this->setReference('sc-email', $sc2);
        $this->setReference('sc-header', $sc_header);
        $this->setReference('sc-tagline', $sc_tagline);
    }

    public function getOrder()
    {
        return 5;
    }
}