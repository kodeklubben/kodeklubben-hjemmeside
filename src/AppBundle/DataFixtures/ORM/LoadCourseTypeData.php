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
        $courseType1 = new CourseType();
        $courseType1->setName('Scratch');
        $courseType1->setDescription('Dette kurset er for nybegynnere og er anbefalt fra 4. klasse og oppover. Et flott kurs å starte på for å få følelsen av hva programmering går ut på. Scratch er et blokkbasert programmeringsspråk hvor vi kommer til å fokusere på å lage enkle spill.

Scratch er et gratis visuelt programmeringsspråk, som kan bli brukt av elever, studenter, interesserte, lærere og foreldre for lett å lære å skape spill, animasjoner og andre programmer. Scratch er designet og vedligeholdt av "the Lifelong Kindergarten group" på "the MIT Media Lab". Målet med Scratch er å bruke det som et trinn mot en mer avansert programmeringsverden.');
        $courseType1->setImgUrl('/img/scratch.png');
        $courseType1->setChallengesUrl('http://kodeklubben.github.io/scratch/index.html');
        $manager->persist($courseType1);


        $courseType2 = new CourseType();
        $courseType2->setName('Python');
        $courseType2->setDescription('Python er et tekstbasert programmeringsspråk som er svært nyttig å lære. Dette kurset vil fokusere på de grunnleggende elementene i Python ved hjelp av blant annet noe som heter Turtle. Dette kurset er anbefalt for de som er ferdig med Scratch-kurset eller går på ungdomsskolen og oppover.

Python er et objektorientert programmeringsspråk startet på av Guido van Rossum i 1989. Van Rossum valgte navnet «Python» fordi han er fan av Monty Python, og fordi han mener at programmering skal være gøy.

Python var opprinnelig et scriptspråk for Amoeba OS for å lage systemendringer. Perl, Ruby, Tcl, Scheme og tildels Java blir ofte sett på som alternativer til Python. Python er utviklet som et fri programvare-prosjekt.

Python har en lettlest og klar syntaks. I Python deles koden opp etter innrykk, ikke etter spesialtegn som }. Dette er trekk ved programmeringsspråket som skal gjøre det lettere og raskere å skrive programmer. Mange ser på Python som en nyere, men strengere og en mer striglet versjon av Perl.');
        $courseType2->setImgUrl('/img/python-logo.png');
        $courseType2->setChallengesUrl('http://kodeklubben.github.io/python/index.html');
        $manager->persist($courseType2);

        $courseType3 = new CourseType();
        $courseType3->setName('Minecraft');
        $courseType3->setDescription('I dette kurset skal vi bli kjent med å lage plugins til Minecraft ved hjelp av programmering i Python. Vi kommer til å starte med gjennomgang og undervisning i hva man kan gjøre med Python i Minecraft også kommer vi til å legge opp til egne prosjekter hvor man kan jobbe sammen. For å ta dette kurset må du ha hatt Python 1 og føle du har kontroll på programmering i Python. Dette er ikke et nybegynnerkurs i Python og kan derfor være litt vanskelig. Anbefalt for 7. klasse og oppover.

Minecraft er et sandkasse-/overlevelsesspill for PC, mobil og spillkonsoller, som lar spilleren bygge og rive ned konstruksjoner av kuber i en 3D-verden.

Minecraft er utviklet av Mojang AB, men startet som et fritidsprosjekt av Markus «Notch» Persson i 2009. Minecraft er inspirert av spillene Dwarf Fortress, RollerCoaster Tycoon, Dungeon Keeper, og spesielt Infiniminer. Jens Bergensten, eller Jeb, har siden desember 2011 vært den ledende utvikleren av spillet, etter at Markus Persson forlot rollen. Det kreves en Mojang-konto (tidligere Minecraft-konto) med betalt fullversjon (ca. 166 NOK) for å spille fullversjonen. ');
        $courseType3->setImgUrl('/img/minecraft-logo.png');
        $courseType3->setChallengesUrl('http://kodeklubben.github.io/computercraft/index.html');
        $manager->persist($courseType3);

        $courseType4 = new CourseType();
        $courseType4->setName('Java');
        $courseType4->setDescription('Java er et populært og nyttig tekstbasert programmeringsspråk. Her vil du lære objektsorientert programmering ved å lage diverse spill og andre grafiske programmer. Dette er det mest avanserte kurset vi har og derfor anbefaler vi at du har god forståelse av Python før du tar dette kurset.

Java er et objektorientert programmeringsspråk, utviklet av James Gosling og andre utviklere hos Sun Microsystems. I november 2006 kunngjorde Sun at selskapet ville frigi Javakoden som åpen kildekode og dermed bli en av de største bidragsyterne innen dette globale miljøet.

I motsetning til f.eks. C, kompileres ikke Java til maskinkode, men til plattformuavhengig bytekode som kjøres av et underliggende lag programvare kalt Java Virtual Machine (JVM). Javaprogrammer kan derfor kjøre på alle operativsystemer hvor det finnes en Java Virtual Machine.

For å kjøre vanlige Javaprogrammer trenger man en Java Runtime Environment (JRE). Denne består av JVM samt de grunnleggende bibliotekene. For utvikling av Javaprogrammer må man ha Java Development Kit (JDK), som i tillegg til en fullverdig JRE inneholder Javakompilatoren og andre sentrale verktøy for Javautvikling.');
        $courseType4->setImgUrl('/img/java-logo.png');
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