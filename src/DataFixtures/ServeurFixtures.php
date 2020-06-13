<?php

namespace App\DataFixtures;

use App\Entity\Serveur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class ServeurFixtures extends Fixture
{
    const REFERENCE = 'server-%s';
    const LOOP      = 2;

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($i = 1; $i <= self::LOOP; $i++) {
            $server = (new Serveur())
                ->setName($faker->realText(40))
                ->setIp($faker->ipv4)
                ->setCreatedAt($faker->dateTimeBetween('-30 days', '+30 days'))
            ;
            $manager->persist($server);
            $this->addReference(sprintf(self::REFERENCE, $i), $server);
        }
        $manager->flush();
    }
}
