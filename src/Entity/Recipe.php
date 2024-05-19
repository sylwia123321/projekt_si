<?php
/**
 * Recipe entity.
 */

namespace App\Entity;

use App\Repository\RecipeRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
* Class Recipe.
*/
#[ORM\Entity(repositoryClass: RecipeRepository::class)]
#[ORM\Table(name: 'recipes')]
#[ORM\HasLifecycleCallbacks]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $ingredients = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $instructions = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?DateTimeImmutable $updatedAt = null;

    /**
     * Category.
     *
     * @var Category
     */
    #[ORM\ManyToOne(targetEntity: Category::class, fetch: 'EXTRA_LAZY')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    /**
    * Getter for Id.
    */
    public function getId(): ?int
    {
    return $this->id;
    }

    /**
    * Getter for title.
    */
    public function getTitle(): ?string
    {
    return $this->title;
    }

    /**
    * Setter for title.
    */
    public function setTitle(?string $title): void
    {
    $this->title = $title;
    }

    /**
    * Getter for description.
    */
    public function getDescription(): ?string
    {
    return $this->description;
    }

    /**
    * Setter for description.
    */
    public function setDescription(?string $description): void
    {
    $this->description = $description;
    }

    /**
    * Getter for ingredients.
    */
    public function getIngredients(): ?string
    {
    return $this->ingredients;
    }

    /**
    * Setter for ingredients.
    */
    public function setIngredients(?string $ingredients): void
    {
    $this->ingredients = $ingredients;
    }

    /**
    * Getter for instructions.
    */
    public function getInstructions(): ?string
    {
    return $this->instructions;
    }

    /**
    * Setter for instructions.
    */
    public function setInstructions(?string $instructions): void
    {
    $this->instructions = $instructions;
    }

    /**
    * Getter for created at.
    */
    public function getCreatedAt(): ?DateTimeImmutable
    {
    return $this->createdAt;
    }

    /**
    * Setter for created at.
    */
    public function setCreatedAt(?DateTimeImmutable $createdAt): void
    {
    $this->createdAt = $createdAt;
    }

    /**
    * Getter for updated at.
    */
    public function getUpdatedAt(): ?DateTimeImmutable
    {
    return $this->updatedAt;
    }

    /**
    * Setter for updated at.
    */
    public function setUpdatedAt(?DateTimeImmutable $updatedAt): void
    {
    $this->updatedAt = $updatedAt;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }
}
