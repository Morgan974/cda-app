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
//        $level = $levelRepository->findOneBy(array('codeNum' => 'LVL-0001'));

        $levels = $levelRepository->findAll();

//        dump($levels);

        /** @var StatusRepository $statusRepository */
        $statusRepository = $manager->getRepository(Status::class);
        $status = $statusRepository->findOneBy(array('isEnabled'  => true));

        for ($i = 1; $i <= 20; $i++) {

            /** @var Level $level */
            $index = array_rand($levels, $num = 1);

            dump('$index');
            dump($index);

            $trek = new Trek();
            $trek
                ->setName("Test " . $i)
                ->setDescription("Description test")
                ->setPrice(rand(120, 12000))
                ->setDuration(rand(1, 12))
                ->setLevel($levels[$index])
                ->setStatus($status)
            ;
            $manager->persist($trek);
        }
    }
}
