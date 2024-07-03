<?php
/**
 * Tag fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

/**
 * Class TagFixtures.
 */
class TagFixtures extends Fixture
{
    private $faker;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->faker = Factory::create();
    }

    /**
     * Load.
     *
     * @param ObjectManager $manager Persistence object manager
     */
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 100; ++$i) {
            $tag = new Tag();
            $tag->setTitle($this->faker->word);
            $tag->setCreatedAt(
                \DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $tag->setUpdatedAt(
                \DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );

            $manager->persist($tag);

            $this->addReference('tags_'.$i, $tag);
        }

        $manager->flush();
    }
}
