<?php

namespace App\DataFixtures;

use App\Entity\Level;
use App\Entity\Status;
use App\Entity\Trek;
use App\Repository\LevelRepository;
use App\Repository\StatusRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->loadLevels($manager);
        $this->loadStatus($manager);

        $manager->flush();

        $this->loadTreks($manager);

        $manager->flush();
    }

    private function loadLevels(ObjectManager $manager)
    {
        $level = new Level();
        $level
            ->setLevel("marcheur débutant")
            ->setCodeNum("LVL-0001")
        ;
        $manager->persist($level);

        $level = new Level();
        $level
            ->setLevel("marcheur occasionnel")
            ->setCodeNum("LVL-0002")
        ;
        $manager->persist($level);

        $level = new Level();
        $level
            ->setLevel("marcheur confirmé")
            ->setCodeNum("LVL-0003")
        ;
        $manager->persist($level);

        $level = new Level();
        $level
            ->setLevel("trek sportif")
            ->setCodeNum("LVL-0004")
        ;
        $manager->persist($level);
    }

    private function loadStatus(ObjectManager $manager)
    {
        $status = new Status();
        $status
            ->setIsEnabled(true)
        ;
        $manager->persist($status);

        $status = new Status();
        $status
            ->setIsEnabled(false)
        ;
        $manager->persist($status);
    }

    private function loadTreks(ObjectManager $manager)
    {
        /** @var LevelRepository $levelRepository */
        $levelRepository = $manager->getRepository(Level::class);

        /** @var StatusRepository $statusRepository */
        $statusRepository = $manager->getRepository(Status::class);
        $statut = $statusRepository->findOneBy(array('isEnabled'  => true));

        $trek = new Trek();
        $trek
            ->setName("De Hell-Bourg à Bélouve")
            ->setDescription(
                "Le Cirque de Salazie est renommé pour ses randonnées. Celle qui relie Hell-Bourg à Bélouve est un grand classique. Elle grimpe le long d'un rempart pour environ 500 m de dénivelé. La montée est souvent en lacets et rarement difficile bien que la pente soit présente presque tout le long. Une grande partie de la randonnée est en sous-bois. Cette montée permet en revanche des points des vues somptueux sur tout le cirque, avec en feu d'artifice le point de vue final au gîte"
            )
            ->setPrice('120')
            ->setDuration('4')
            ->setDistance('5.22')
            ->setLevel($levelRepository->findOneBy(['codeNum' => 'LVL-0002']))
            ->setStatus($statut)
        ;
        $manager->persist($trek);

        $trek = new Trek();
        $trek
            ->setName("Cayenne - Roche Plate")
            ->setDescription(
                "Le passage auprès de la caldera de la Ravine Grand Mère est saisissant"
            )
            ->setPrice('120')
            ->setDuration('5')
            ->setDistance('6.99')
            ->setLevel($levelRepository->findOneBy(['codeNum' => 'LVL-0002']))
            ->setStatus($statut)
        ;
        $manager->persist($trek);

        $trek = new Trek();
        $trek
            ->setName("Fenêtre des Makes Piton Cabri")
            ->setDescription(
                "Une jolie randonnée en partant de la Fenêtre des Makes qui permet, en plus des magnifiques points de vues sur Cilaos, de découvrir des végétations complètement différentes selon l'altitude."
            )
            ->setPrice('120')
            ->setDuration('3.30')
            ->setDistance('5.75')
            ->setLevel($levelRepository->findOneBy(['codeNum' => 'LVL-0002']))
            ->setStatus($statut)
        ;
        $manager->persist($trek);

        $trek = new Trek();
        $trek
            ->setName("Le tour du Grand Étang et les cascades du Bras d'Annette")
            ->setDescription(
                "Cette retenue d'eau naturelle du Grand Étang permet de passer quelques heures à marcher en sous-bois et d'admirer un des rares lacs de l'île. Pas de difficulté si ce n'est un passage étroit entre falaise et bord du lac sécurisé par une main courante (et contournable), et l'accès aux cascades du Bras d'Annette avec son chemin étroit et quelques marches un peu hautes. Mais quel régal pour les yeux, plusieurs cascades et un bassin de réception qui appelle à la méditation et au pique-nique."
            )
            ->setPrice('120')
            ->setDuration('3.20')
            ->setDistance('7.11')
            ->setLevel($levelRepository->findOneBy(['codeNum' => 'LVL-0001']))
            ->setStatus($statut)
        ;
        $manager->persist($trek);

        $trek = new Trek();
        $trek
            ->setName("Le Grand Bénare")
            ->setDescription(
                "Cette retenue d'eau naturelle du Grand Étang permet de passer quelques heures à marcher en sous-bois et d'admirer un des rares lacs de l'île. Pas de difficulté si ce n'est un passage étroit entre falaise et bord du lac sécurisé par une main courante (et contournable), et l'accès aux cascades du Bras d'Annette avec son chemin étroit et quelques marches un peu hautes. Mais quel régal pour les yeux, plusieurs cascades et un bassin de réception qui appelle à la méditation et au pique-nique."
            )
            ->setPrice('160')
            ->setDuration('6')
            ->setDistance('18')
            ->setLevel($levelRepository->findOneBy(['codeNum' => 'LVL-0003']))
            ->setStatus($statut)
        ;
        $manager->persist($trek);

        $trek = new Trek();
        $trek
            ->setName("Terre Rouge, le Cap Jaune et le Plateau")
            ->setDescription(
                "Situé dans le Sud de l'île, le Cap Jaune est une curiosité naturelle qu'il faut absolument voir si vous venez à Vincendo. Ce site doit son nom à sa couleur jaune ! Le sentier longe le littoral et permet donc de superbes panoramas ! De plus il traverse des paysages spécifiques au Sud Sauvage (paysages déchiquetés, vackoas, roches volcaniques, souffleurs etc)."
            )
            ->setPrice('90')
            ->setDuration('1')
            ->setDistance('2.89')
            ->setLevel($levelRepository->findOneBy(['codeNum' => 'LVL-0001']))
            ->setStatus($statut)
        ;
        $manager->persist($trek);

        $trek = new Trek();
        $trek
            ->setName("Le Piton des Neiges et le Coteau Kervéguen")
            ->setDescription(
                "L'Ascension du Piton des Neiges, point culminant de l'île de La Réunion (et de toutes les terres de l'Océan Indien), offre un panorama unique à 360°. Une randonnée fréquentée pour laquelle on propose un itinéraire de descente moins parcouru."
            )
            ->setPrice('260')
            ->setDuration('9.50')
            ->setDistance('16.2')
            ->setLevel($levelRepository->findOneBy(['codeNum' => 'LVL-0004']))
            ->setStatus($statut)
        ;
        $manager->persist($trek);
    }
}
