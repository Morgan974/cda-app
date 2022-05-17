<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\Level;
use App\Entity\Reservation;
use App\Entity\Status;
use App\Entity\Trek;
use App\Entity\User;
use App\Repository\LevelRepository;
use App\Repository\StatusRepository;
use App\Repository\TrekRepository;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder =$encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadLevels($manager);
        $this->loadStatus($manager);

        $manager->flush();

        $this->loadTreks($manager);

        $manager->flush();

        $this->loadUsers($manager);

        $manager->flush();
    }

    private function loadLevels(ObjectManager $manager)
    {
        $levelsJson = file_get_contents('src/Data/Json/levelJson.json', true);
        $levels = json_decode($levelsJson);

        foreach($levels as $levelData) {
            $level = new Level();
            $level
                ->setLevel($levelData->level)
                ->setCodeNum($levelData->codeNum)
            ;
            $manager->persist($level);
        }
    }

    private function loadStatus(ObjectManager $manager)
    {
        $statusJson = file_get_contents('src/Data/Json/statusJson.json', true);
        $status = json_decode($statusJson);

        foreach ($status as $state) {
            $status = new Status();
            $status
                ->setIsEnabled($state->isEnabled)
            ;
            $manager->persist($status);
        }
    }

    private function loadTreks(ObjectManager $manager)
    {
        $treksJson = file_get_contents('src/Data/Json/trekJson.json', true);
        $treks = json_decode($treksJson);

        /** @var LevelRepository $levelRepository */
        $levelRepository = $manager->getRepository(Level::class);

        /** @var StatusRepository $statusRepository */
        $statusRepository = $manager->getRepository(Status::class);

        foreach ($treks as $trekData) {
            $status = $statusRepository->findOneBy(array('isEnabled'  => $trekData->status));

            $trek = new Trek();
            $trek
                ->setName($trekData->name)
                ->setDescription($trekData->description)
                ->setPrice($trekData->price)
                ->setDuration($trekData->duration)
                ->setDistance($trekData->distance)
                ->setLevel($levelRepository->findOneBy(['codeNum' => $trekData->level]))
                ->setStatus($status)
            ;
            $manager->persist($trek);
        }
    }

    private function loadUsers(ObjectManager $manager)
    {
        $faker = Factory::create("fr_FR");

        for($i = 0; $i < 50; $i++) {
            $user = new User();

            $firstname = $faker->firstname();
            $lastname = $faker->lastName;
            $email = strtolower($firstname) . '.' . strtolower($lastname) . '@gmail.com';
            $hash = $this->encoder->hashPassword($user, "password");

            $user
                ->setFirstname($firstname)
                ->setLastname($lastname)
                ->setEmail(str_replace(' ', '', $email))
                ->setPassword($hash)
            ;
            $manager->persist($user);
        }
    }
}
