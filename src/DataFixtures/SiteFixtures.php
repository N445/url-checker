<?php

namespace App\DataFixtures;

use App\Entity\Site;
use App\Entity\Url;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class SiteFixtures extends Fixture
{
    const REFERENCE = 'site-%s';
    const LOOP      = 1;

    /**
     * @var Generator
     */
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager)
    {
        $manager->persist($this->getOzangesSite());
        for ($i = 1; $i <= self::LOOP; $i++) {
            $site = (new Site())
                ->setName($this->faker->realText(40))
                ->setDomain($this->faker->domainName)
                ->setServeur($this->getReference(sprintf(ServeurFixtures::REFERENCE, rand(1, ServeurFixtures::LOOP))))
                ->setCreatedAt($this->faker->dateTimeBetween('-30 days', '+30 days'))
            ;
            $this->setUrls($site);
            $manager->persist($site);
            $this->addReference(sprintf(self::REFERENCE, $i), $site);
        }
        $manager->flush();
    }

    private function getOzangesSite()
    {
        return  (new Site())
            ->setName('Ozanges')
            ->setDomain('ozanges.fr')
            ->setServeur($this->getReference(sprintf(ServeurFixtures::REFERENCE, rand(1, ServeurFixtures::LOOP))))
            ->addUrl((new Url())->setUrl('/agence-web-communication'))
        ;
    }

    private function setUrls(Site &$site)
    {
        foreach (range(1, rand(1, 2)) as $i) {
            $url = (new Url())->setUrl($this->faker->slug())
                              ->setCode($this->faker->randomElement([200, 302]))
            ;
            $site->addUrl($url);
        }
    }
}
