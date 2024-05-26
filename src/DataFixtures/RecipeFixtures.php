<?php
/**
 * Recipe fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Recipe;
use App\Entity\Tag;
use App\Repository\TagRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class RecipeFixtures.
 */
class RecipeFixtures extends Fixture implements DependentFixtureInterface
{
    private $faker;
    private $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->faker = Factory::create();
        $this->tagRepository = $tagRepository;
    }

    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullPropertyFetch
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function load(ObjectManager $manager): void
    {
        // Pobierz wszystkie tagi z bazy danych
        $tags = $this->tagRepository->findAll();

        for ($i = 0; $i < 100; $i++) {
            $recipe = new Recipe();
            $recipe->setTitle($this->faker->sentence);
            $recipe->setDescription($this->faker->paragraph);
            $recipe->setIngredients($this->faker->paragraph);
            $recipe->setInstructions($this->faker->paragraph);
            $recipe->setCreatedAt(
                \DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $recipe->setUpdatedAt(
                \DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );

            /** @var Category $category */
            $category = $this->getReference('categories_' . $this->faker->numberBetween(0, 19));
            $recipe->setCategory($category);

            // Dodaj kilka losowych tagów do przepisu
            $randomTags = $this->faker->randomElements($tags, $this->faker->numberBetween(1, 5));
            foreach ($randomTags as $tag) {
                $recipe->addTag($tag);
            }

            $manager->persist($recipe);
        }

        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return string[] of dependencies
     *
     * @psalm-return array{0: CategoryFixtures::class, 1: TagFixtures::class}
     */
    public function getDependencies(): array
    {
        return [CategoryFixtures::class, TagFixtures::class];
    }
}
