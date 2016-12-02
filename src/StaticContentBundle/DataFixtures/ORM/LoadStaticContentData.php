<?php

namespace StaticContentBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use StaticContentBundle\Entity\StaticContent;

class LoadStaticContentData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $sc_header = new StaticContent();
        $sc_header->setClub($this->getReference('club-trondheim'));
        $sc_header->setIdString('header');
        $sc_header->setContent('<p>Påmeldingen er åpen og det er nå mulig å melde seg som deltakere, veiledere og reserve på de forskjellige kursene. Under er mer detaljer over de forskjellige kursene. Alle kursene blir holdt på Realfagsbygget ved NTNU fra klokken 1815-2000.</p>');
        $sc_header->setLastEditedBy($this->getReference('user-admin'));

        $manager->persist($sc_header);

        $sc_tagline = new StaticContent();
        $sc_tagline->setClub($this->getReference('club-trondheim'));
        $sc_tagline->setIdString('tagline');
        $sc_tagline->setContent('Lær programmering med artige oppgaver fra Kodeklubben Trondheim!');
        $sc_tagline->setLastEditedBy($this->getReference('user-admin'));

        $manager->persist($sc_tagline);

        $sc_participant_info = new StaticContent();
        $sc_participant_info->setClub($this->getReference('club-trondheim'));
        $sc_participant_info->setIdString('participant_info');
        $sc_participant_info->setContent('<p>Tekst om deltaker.

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer non felis laoreet sem tempor tincidunt id non enim. Mauris finibus imperdiet tempor. Fusce non ex in sapien tincidunt faucibus sit amet id nulla. Vivamus eget finibus libero, sed tincidunt turpis. Aenean ac est id lorem dignissim scelerisque vel aliquam mi. Pellentesque hendrerit porttitor quam, quis fermentum nisl accumsan varius. Etiam ultricies lectus at augue bibendum, sit amet suscipit libero dictum. In ut arcu enim. Praesent bibendum massa justo, ut eleifend nibh facilisis at. Fusce nec est imperdiet, accumsan nulla eget, lacinia ante. Suspendisse eros ipsum, congue ac facilisis sit amet, euismod in turpis. Quisque mattis vehicula est quis pellentesque. Proin sit amet finibus felis. Vivamus id porta mauris.</p>');
        $sc_participant_info->setLastEditedBy($this->getReference('user-admin'));

        $manager->persist($sc_participant_info);

        $sc_tutor_info = new StaticContent();
        $sc_tutor_info->setClub($this->getReference('club-trondheim'));
        $sc_tutor_info->setIdString('tutor_info');
        $sc_tutor_info->setContent('<p>Tekst om veileder.

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer non felis laoreet sem tempor tincidunt id non enim. Mauris finibus imperdiet tempor. Fusce non ex in sapien tincidunt faucibus sit amet id nulla. Vivamus eget finibus libero, sed tincidunt turpis. Aenean ac est id lorem dignissim scelerisque vel aliquam mi. Pellentesque hendrerit porttitor quam, quis fermentum nisl accumsan varius. Etiam ultricies lectus at augue bibendum, sit amet suscipit libero dictum. In ut arcu enim. Praesent bibendum massa justo, ut eleifend nibh facilisis at. Fusce nec est imperdiet, accumsan nulla eget, lacinia ante. Suspendisse eros ipsum, congue ac facilisis sit amet, euismod in turpis. Quisque mattis vehicula est quis pellentesque. Proin sit amet finibus felis. Vivamus id porta mauris.</p>');
        $sc_tutor_info->setLastEditedBy($this->getReference('user-admin'));

        $manager->persist($sc_tutor_info);

        $sc_about_participant = new StaticContent();
        $sc_about_participant->setClub($this->getReference('club-trondheim'));
        $sc_about_participant->setIdString('about_participant');
        $sc_about_participant->setContent('<p>Tekst om deltakere.

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer non felis laoreet sem tempor tincidunt id non enim. Mauris finibus imperdiet tempor. Fusce non ex in sapien tincidunt faucibus sit amet id nulla. Vivamus eget finibus libero, sed tincidunt turpis. Aenean ac est id lorem dignissim scelerisque vel aliquam mi. Pellentesque hendrerit porttitor quam, quis fermentum nisl accumsan varius. Etiam ultricies lectus at augue bibendum, sit amet suscipit libero dictum. In ut arcu enim. Praesent bibendum massa justo, ut eleifend nibh facilisis at. Fusce nec est imperdiet, accumsan nulla eget, lacinia ante. Suspendisse eros ipsum, congue ac facilisis sit amet, euismod in turpis. Quisque mattis vehicula est quis pellentesque. Proin sit amet finibus felis. Vivamus id porta mauris.</p>');
        $sc_about_participant->setLastEditedBy($this->getReference('user-admin'));

        $manager->persist($sc_about_participant);

        $sc_about_tutor = new StaticContent();
        $sc_about_tutor->setClub($this->getReference('club-trondheim'));
        $sc_about_tutor->setIdString('about_tutor');
        $sc_about_tutor->setContent('<p>Tekst om veiledere.</p>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer non felis laoreet sem tempor tincidunt id non enim. Mauris finibus imperdiet tempor. Fusce non ex in sapien tincidunt faucibus sit amet id nulla. Vivamus eget finibus libero, sed tincidunt turpis. Aenean ac est id lorem dignissim scelerisque vel aliquam mi. Pellentesque hendrerit porttitor quam, quis fermentum nisl accumsan varius. Etiam ultricies lectus at augue bibendum, sit amet suscipit libero dictum. In ut arcu enim. Praesent bibendum massa justo, ut eleifend nibh facilisis at. Fusce nec est imperdiet, accumsan nulla eget, lacinia ante. Suspendisse eros ipsum, congue ac facilisis sit amet, euismod in turpis. Quisque mattis vehicula est quis pellentesque. Proin sit amet finibus felis. Vivamus id porta mauris.</p>');
        $sc_about_tutor->setLastEditedBy($this->getReference('user-admin'));

        $manager->persist($sc_about_tutor);

        $sc_participant_info = new StaticContent();
        $sc_participant_info->setClub($this->getReference('club-default'));
        $sc_participant_info->setIdString('participant_info');
        $sc_participant_info->setContent('<p>Tekst om deltaker. [<a href="kontrollpanel/statisk_innhold/deltaker">Endre</a>]</p>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer non felis laoreet sem tempor tincidunt id non enim. Mauris finibus imperdiet tempor. Fusce non ex in sapien tincidunt faucibus sit amet id nulla. Vivamus eget finibus libero, sed tincidunt turpis. Aenean ac est id lorem dignissim scelerisque vel aliquam mi. Pellentesque hendrerit porttitor quam, quis fermentum nisl accumsan varius. Etiam ultricies lectus at augue bibendum, sit amet suscipit libero dictum. In ut arcu enim. Praesent bibendum massa justo, ut eleifend nibh facilisis at. Fusce nec est imperdiet, accumsan nulla eget, lacinia ante. Suspendisse eros ipsum, congue ac facilisis sit amet, euismod in turpis. Quisque mattis vehicula est quis pellentesque. Proin sit amet finibus felis. Vivamus id porta mauris.</p>');
        $sc_participant_info->setLastEditedBy($this->getReference('user-admin'));

        $manager->persist($sc_participant_info);

        $sc_tagline = new StaticContent();
        $sc_tagline->setClub($this->getReference('club-default'));
        $sc_tagline->setIdString('tagline');
        $sc_tagline->setContent('Lær programmering med artige oppgaver fra Kodeklubben Trondheim! [<a href="kontrollpanel/statisk_innhold/tagline">Endre</a>]');
        $sc_tagline->setLastEditedBy($this->getReference('user-admin'));

        $manager->persist($sc_tagline);

        $sc_header = new StaticContent();
        $sc_header->setClub($this->getReference('club-default'));
        $sc_header->setIdString('header');
        $sc_header->setContent('<p>Header tekst [<a href="kontrollpanel/statisk_innhold/header">Endre</a>]</p>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi hendrerit ligula eget cursus faucibus. Nulla ut felis vitae magna vestibulum vulputate congue at sapien. 
Fusce ornare diam at sem pellentesque vehicula. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas</p>');
        $sc_header->setLastEditedBy($this->getReference('user-admin'));

        $manager->persist($sc_header);

        $sc_tutor_info = new StaticContent();
        $sc_tutor_info->setClub($this->getReference('club-default'));
        $sc_tutor_info->setIdString('tutor_info');
        $sc_tutor_info->setContent('<p>Tekst om veileder. [<a href="kontrollpanel/statisk_innhold/veileder">Endre</a>] </p>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer non felis laoreet sem tempor tincidunt id non enim. Mauris finibus imperdiet tempor. Fusce non ex in sapien tincidunt faucibus sit amet id nulla. Vivamus eget finibus libero, sed tincidunt turpis. Aenean ac est id lorem dignissim scelerisque vel aliquam mi. Pellentesque hendrerit porttitor quam, quis fermentum nisl accumsan varius. Etiam ultricies lectus at augue bibendum, sit amet suscipit libero dictum. In ut arcu enim. Praesent bibendum massa justo, ut eleifend nibh facilisis at. Fusce nec est imperdiet, accumsan nulla eget, lacinia ante. Suspendisse eros ipsum, congue ac facilisis sit amet, euismod in turpis. Quisque mattis vehicula est quis pellentesque. Proin sit amet finibus felis. Vivamus id porta mauris.</p>');
        $sc_tutor_info->setLastEditedBy($this->getReference('user-admin'));

        $manager->persist($sc_tutor_info);

        $sc_about_participant = new StaticContent();
        $sc_about_participant->setClub($this->getReference('club-default'));
        $sc_about_participant->setIdString('about_participant');
        $sc_about_participant->setContent('<p>Tekst om deltakere. [<a href="kontrollpanel/statisk_innhold/om_deltakere">Endre</a>] </p>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer non felis laoreet sem tempor tincidunt id non enim. Mauris finibus imperdiet tempor. Fusce non ex in sapien tincidunt faucibus sit amet id nulla. Vivamus eget finibus libero, sed tincidunt turpis. Aenean ac est id lorem dignissim scelerisque vel aliquam mi. Pellentesque hendrerit porttitor quam, quis fermentum nisl accumsan varius. Etiam ultricies lectus at augue bibendum, sit amet suscipit libero dictum. In ut arcu enim. Praesent bibendum massa justo, ut eleifend nibh facilisis at. Fusce nec est imperdiet, accumsan nulla eget, lacinia ante. Suspendisse eros ipsum, congue ac facilisis sit amet, euismod in turpis. Quisque mattis vehicula est quis pellentesque. Proin sit amet finibus felis. Vivamus id porta mauris.</p>');
        $sc_about_participant->setLastEditedBy($this->getReference('user-admin'));

        $manager->persist($sc_about_participant);

        $sc_about_tutor = new StaticContent();
        $sc_about_tutor->setClub($this->getReference('club-default'));
        $sc_about_tutor->setIdString('about_tutor');
        $sc_about_tutor->setContent('<p>Tekst om veiledere. [<a href="kontrollpanel/statisk_innhold/om_veiledere">Endre</a>]</p>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer non felis laoreet sem tempor tincidunt id non enim. Mauris finibus imperdiet tempor. Fusce non ex in sapien tincidunt faucibus sit amet id nulla. Vivamus eget finibus libero, sed tincidunt turpis. Aenean ac est id lorem dignissim scelerisque vel aliquam mi. Pellentesque hendrerit porttitor quam, quis fermentum nisl accumsan varius. Etiam ultricies lectus at augue bibendum, sit amet suscipit libero dictum. In ut arcu enim. Praesent bibendum massa justo, ut eleifend nibh facilisis at. Fusce nec est imperdiet, accumsan nulla eget, lacinia ante. Suspendisse eros ipsum, congue ac facilisis sit amet, euismod in turpis. Quisque mattis vehicula est quis pellentesque. Proin sit amet finibus felis. Vivamus id porta mauris.</p>');
        $sc_about_tutor->setLastEditedBy($this->getReference('user-admin'));

        $manager->persist($sc_about_tutor);

        $manager->flush();

        $this->setReference('sc-header', $sc_header);
        $this->setReference('sc-tagline', $sc_tagline);
    }

    public function getOrder()
    {
        return 5;
    }
}
