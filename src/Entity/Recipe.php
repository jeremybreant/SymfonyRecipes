<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


#[ORM\Entity(repositoryClass: RecipeRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[Vich\Uploadable]
#[UniqueEntity(
    fields: ['user','name'],
    message: 'Cet utilisateur a déjà créé cette recette',
    errorPath: 'name'
)]
class Recipe
{
    public const PRICE_VERY_LOW = "Très bon marché";
    public const PRICE_LOW = "Bon marché";
    public const PRICE_MEDIUM = "Moyen";
    public const PRICE_HIGH = "Assez cher";
    public const PRICE_VERY_HIGH = "Très cher";

    public static function getAvailablePrices()
    {
        return [
            Recipe::PRICE_VERY_LOW => Recipe::PRICE_VERY_LOW,
            Recipe::PRICE_LOW => Recipe::PRICE_LOW,
            Recipe::PRICE_MEDIUM => Recipe::PRICE_MEDIUM,
            Recipe::PRICE_HIGH => Recipe::PRICE_HIGH,
            Recipe::PRICE_VERY_HIGH => Recipe::PRICE_VERY_HIGH
        ];
    }

    public const DIFFICULTY_VERY_EASY = "Très facile";
    public const DIFFICULTY_EASY = "Facile";
    public const DIFFICULTY_MEDIUM = "Moyen";
    public const DIFFICULTY_HARD = "Difficile";
    public const DIFFICULTY_VERY_HARD = "Très difficile";

    public static function getAvailableDifficulties()
    {
        return [
            Recipe::DIFFICULTY_VERY_EASY => Recipe::DIFFICULTY_VERY_EASY,
            Recipe::DIFFICULTY_EASY => Recipe::DIFFICULTY_EASY,
            Recipe::DIFFICULTY_MEDIUM => Recipe::DIFFICULTY_MEDIUM,
            Recipe::DIFFICULTY_HARD => Recipe::DIFFICULTY_HARD,
            Recipe::DIFFICULTY_VERY_HARD => Recipe::DIFFICULTY_VERY_HARD
        ];
    }

    public const QUANTITY_TYPE_PEOPLE = "personnes";
    public const QUANTITY_TYPE_PIECE = "pieces";

    public static function getAvailableQuantityType()
    {
        return [
            Recipe::QUANTITY_TYPE_PEOPLE => Recipe::QUANTITY_TYPE_PEOPLE,
            Recipe::QUANTITY_TYPE_PIECE => Recipe::QUANTITY_TYPE_PIECE
        ];
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 50)]
    private string $name;

    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'recipe_images', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;


    #[ORM\Column(nullable: true)]
    #[Assert\LessThan(1441)]
    #[Assert\Positive()]
    private ?int $preparationTime = null;

    #[ORM\Column(nullable: true)]
    #[Assert\LessThan(1441)]
    #[Assert\PositiveOrZero()]
    private ?int $cookingTime = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull()]
    private ?string $difficulty;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank()]
    private string $description;

    #[ORM\Column(nullable: false)]
    #[Assert\NotNull()]
    private string $price;

    #[ORM\Column]
    #[Assert\NotNull()]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column]
    #[Assert\NotNull()]
    private \DateTimeImmutable $updatedAt;

    #[ORM\Column]
    #[Assert\NotNull()]
    private bool $isFavorite;

    #[ORM\Column]
    #[Assert\NotNull()]
    private bool $isPublic;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: Mark::class, orphanRemoval: true)]
    private Collection $marks;

    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: RecipeIngredient::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $recipeIngredients;

    #[ORM\Column]
    private ?int $foodQuantity = null;

    #[ORM\Column(length: 255)]
    private ?string $foodQuantityType = null;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->marks = new ArrayCollection();
        $this->recipeIngredients = new ArrayCollection();
    }

    #[ORM\PrePersist]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function getPreparationTime(): ?int
    {
        return $this->preparationTime;
    }

    public function setPreparationTime(?int $preparationTime): self
    {
        $this->preparationTime = $preparationTime;

        return $this;
    }

    public function getCookingTime(): ?int
    {
        return $this->cookingTime;
    }

    public function setCookingTime(?int $cookingTime): self
    {
        $this->cookingTime = $cookingTime;

        return $this;
    }

    public function getFoodQuantity(): ?int
    {
        return $this->foodQuantity;
    }

    public function setFoodQuantity(?int $foodQuantity): self
    {
        $this->foodQuantity = $foodQuantity;

        return $this;
    }

    public function getFoodQuantityType(): ?string
    {
        return $this->foodQuantityType;
    }

    public function setFoodQuantityType(string $foodQuantityType): self
    {
        $this->foodQuantityType = $foodQuantityType;

        return $this;
    }

    public function getDifficulty(): ?string
    {
        return $this->difficulty;
    }

    public function setDifficulty(?string $difficulty): self
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(?string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getIsFavorite(): ?bool
    {
        return $this->isFavorite;
    }

    public function setIsFavorite(bool $isFavorite): self
    {
        $this->isFavorite = $isFavorite;

        return $this;
    }

    public function getIsPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): self
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Mark>
     */
    public function getMarks(): Collection
    {
        return $this->marks;
    }

    public function addMark(Mark $mark): self
    {
        if (!$this->marks->contains($mark)) {
            $this->marks->add($mark);
            $mark->setRecipe($this);
        }

        return $this;
    }

    public function removeMark(Mark $mark): self
    {
        if ($this->marks->removeElement($mark)) {
            // set the owning side to null (unless already changed)
            if ($mark->getRecipe() === $this) {
                $mark->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * @return float|null
     */
    public function getAverage(): ?float
    {
        $total = 0;
        $count = count($this->marks);

        if ($count === 0) {
            return null;
        }

        foreach ($this->marks as $mark) {
            $total += $mark->getMark();
        }

        return $total / $count;
    }

    /**
     * @return float|null
     */
    public function getRoundedAverage(): ?float
    {
        $averageMark = $this->getAverage();

        if ($averageMark === null) {
            return null;
        }

        return round($averageMark,2);
    }

    /**
     * @return Collection<int, RecipeIngredient>
     */
    public function getRecipeIngredients(): Collection
    {
        return $this->recipeIngredients;
    }

    public function addRecipeIngredient(RecipeIngredient $recipeIngredient): self
    {
        if (!$this->recipeIngredients->contains($recipeIngredient)) {
            $this->recipeIngredients->add($recipeIngredient);
            $recipeIngredient->setRecipe($this);
        }

        return $this;
    }

    public function removeRecipeIngredient(RecipeIngredient $recipeIngredient): self
    {
        if ($this->recipeIngredients->removeElement($recipeIngredient)) {
            // set the owning side to null (unless already changed)
            if ($recipeIngredient->getRecipe() === $this) {
                $recipeIngredient->setRecipe(null);
            }
        }

        return $this;
    }

}
