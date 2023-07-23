<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');


        // On Crée un utilisateur
        $user = new User();
        $user->setUsername($faker->name);
        $user->setEmail("admin1@p8.com");
        $user->setPassword("password");

        // On Crée des tasks
        for ($t = 1; $t <= mt_rand(30, 50); $t++) {
            $tasks = new Task();
            $tasks->setTitle($faker->title());
            $tasks->setContent($faker->paragraph);
            $tasks->setCreatedAt($faker->dateTimeBetween('-1 months'));

            $tasks->persist();
        }

        $manager->flush();
    }
}
