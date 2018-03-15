<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\CourseType;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCourseTypeData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $club = $this->getReference('club-trondheim');

        $courseType1 = new CourseType();
        $courseType1->setClub($club);
        $courseType1->setName('Scratch');
        $courseType1->setDescription('<p>Dette kurset er for nybegynnere og er anbefalt fra 4. klasse og oppover. Et flott kurs &aring; starte p&aring; for &aring; f&aring; f&oslash;lelsen av hva programmering g&aring;r ut p&aring;. Scratch er et blokkbasert programmeringsspr&aring;k hvor vi kommer til &aring; fokusere p&aring; &aring; lage enkle spill.</p>
<p>Scratch er et gratis visuelt programmeringsspr&aring;k, som kan bli brukt av elever, studenter, interesserte, l&aelig;rere og foreldre for lett &aring; l&aelig;re &aring; skape spill, animasjoner og andre programmer.</p>
<p>Scratch er designet og vedligeholdt av &quot;the Lifelong Kindergarten group&quot; p&aring; &quot;the MIT Media Lab&quot;. M&aring;let med Scratch er &aring; bruke det som et trinn mot en mer avansert programmeringsverden.</p>');
        $courseType1->setImage($this->getReference('img-scratch'));
        $courseType1->setChallengesUrl('http://kodeklubben.github.io/scratch/index.html');
        $manager->persist($courseType1);

        $courseType2 = new CourseType();
        $courseType2->setClub($club);
        $courseType2->setName('Python');
        $courseType2->setDescription('<p>Python er et tekstbasert programmeringsspr&aring;k som er sv&aelig;rt nyttig &aring; l&aelig;re. Dette kurset vil fokusere p&aring; de grunnleggende elementene i Python ved hjelp av blant annet noe som heter Turtle. Dette kurset er anbefalt for de som er ferdig med Scratch-kurset eller g&aring;r p&aring; ungdomsskolen og oppover.</p>
<p>Python er et objektorientert programmeringsspr&aring;k startet p&aring; av Guido van Rossum i 1989. Van Rossum valgte navnet &laquo;Python&raquo; fordi han er fan av Monty Python, og fordi han mener at programmering skal v&aelig;re g&oslash;y.</p>
<p>Python var opprinnelig et scriptspr&aring;k for Amoeba OS for &aring; lage systemendringer. Perl, Ruby, Tcl, Scheme og tildels Java blir ofte sett p&aring; som alternativer til Python. Python er utviklet som et fri programvare-prosjekt.</p>
<p>Python har en lettlest og klar syntaks. I Python deles koden opp etter innrykk, ikke etter spesialtegn som }. Dette er trekk ved programmeringsspr&aring;ket som skal gj&oslash;re det lettere og raskere &aring; skrive programmer. Mange ser p&aring; Python som en nyere, men strengere og en mer striglet versjon av Perl.</p>');
        $courseType2->setImage($this->getReference('img-python'));
        $courseType2->setChallengesUrl('http://kodeklubben.github.io/python/index.html');
        $manager->persist($courseType2);

        $courseType3 = new CourseType();
        $courseType3->setClub($club);
        $courseType3->setName('Minecraft');
        $courseType3->setDescription('<p>I dette kurset skal vi bli kjent med &aring; lage plugins til Minecraft ved hjelp av programmering i Python. Vi kommer til &aring; starte med gjennomgang og undervisning i hva man kan gj&oslash;re med Python i Minecraft ogs&aring; kommer vi til &aring; legge opp til egne prosjekter hvor man kan jobbe sammen. For &aring; ta dette kurset m&aring; du ha hatt Python 1 og f&oslash;le du har kontroll p&aring; programmering i Python. Dette er ikke et nybegynnerkurs i Python og kan derfor v&aelig;re litt vanskelig. Anbefalt for 7. klasse og oppover.</p>
<p>Minecraft er et sandkasse-/overlevelsesspill for PC, mobil og spillkonsoller, som lar spilleren bygge og rive ned konstruksjoner av kuber i en 3D-verden.</p>
<p>Minecraft er utviklet av Mojang AB, men startet som et fritidsprosjekt av Markus &laquo;Notch&raquo; Persson i 2009. Minecraft er inspirert av spillene Dwarf Fortress, RollerCoaster Tycoon, Dungeon Keeper, og spesielt Infiniminer. Jens Bergensten, eller Jeb, har siden desember 2011 v&aelig;rt den ledende utvikleren av spillet, etter at Markus Persson forlot rollen. Det kreves en Mojang-konto (tidligere Minecraft-konto) med betalt fullversjon (ca. 166 NOK) for &aring; spille fullversjonen.</p>');
        $courseType3->setImage($this->getReference('img-minecraft'));
        $courseType3->setChallengesUrl('http://kodeklubben.github.io/computercraft/index.html');
        $manager->persist($courseType3);

        $courseType4 = new CourseType();
        $courseType4->setClub($club);
        $courseType4->setName('Java');
        $courseType4->setDescription('<p>Java er et popul&aelig;rt og nyttig tekstbasert programmeringsspr&aring;k. Her vil du l&aelig;re objektsorientert programmering ved &aring; lage diverse spill og andre grafiske programmer. Dette er det mest avanserte kurset vi har og derfor anbefaler vi at du har god forst&aring;else av Python f&oslash;r du tar dette kurset.</p>
<p>Java er et objektorientert programmeringsspr&aring;k, utviklet av James Gosling og andre utviklere hos Sun Microsystems. I november 2006 kunngjorde Sun at selskapet ville frigi Javakoden som &aring;pen kildekode og dermed bli en av de st&oslash;rste bidragsyterne innen dette globale milj&oslash;et.</p>
<p>I motsetning til f.eks. C, kompileres ikke Java til maskinkode, men til plattformuavhengig bytekode som kj&oslash;res av et underliggende lag programvare kalt Java Virtual Machine (JVM). Javaprogrammer kan derfor kj&oslash;re p&aring; alle operativsystemer hvor det finnes en Java Virtual Machine.</p>
<p>For &aring; kj&oslash;re vanlige Javaprogrammer trenger man en Java Runtime Environment (JRE). Denne best&aring;r av JVM samt de grunnleggende bibliotekene. For utvikling av Javaprogrammer m&aring; man ha Java Development Kit (JDK), som i tillegg til en fullverdig JRE inneholder Javakompilatoren og andre sentrale verkt&oslash;y for Javautvikling.</p>');
        $courseType4->setImage($this->getReference('img-java'));
        $courseType4->setChallengesUrl('http://kodeklubben.github.io/javafx/index.html');
        $manager->persist($courseType4);

        $manager->flush();

        $this->setReference('courseType-scratch', $courseType1);
        $this->setReference('courseType-python', $courseType2);
        $this->setReference('courseType-minecraft', $courseType3);
        $this->setReference('courseType-java', $courseType4);
    }

    public function getOrder()
    {
        return 4;
    }
}
