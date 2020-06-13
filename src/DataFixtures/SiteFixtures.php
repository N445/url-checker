<?php

namespace App\DataFixtures;

use App\Entity\Site;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class SiteFixtures extends Fixture
{
    const REFERENCE = 'site-%s';
    const LOOP      = 20;

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($i = 1; $i <= self::LOOP; $i++) {
            $site = (new Site())
                ->setName($faker->realText(40))
                ->setDomain($faker->domainName)
                ->setServeur($this->getReference(sprintf(ServeurFixtures::REFERENCE, rand(1, ServeurFixtures::LOOP))))
            ;
            $manager->persist($site);
            $this->addReference(sprintf(self::REFERENCE, $i), $site);
        }
        $manager->flush();
    }
}
