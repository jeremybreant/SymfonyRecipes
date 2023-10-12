<?php
declare(strict_types=1);

namespace App\Entity;

use App\Interface\ImagesInterface;
use App\Repository\RecipeRepository;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Constraints as Assert;



#[ORM\Entity(repositoryClass: RecipeRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(
    fields: ['user', 'name'],
    message: 'Cet utilisateur a déjà créé cette recette',
    errorPath: 'name'
)]
class Recipe implements ImagesInterface
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

    public const STATUS_NOT_APPROVED = "Non approuvée";
    public const STATUS_IN_APPROBATION = "En cours d'approbation";
    public const STATUS_APPROVED = "Approuvée";
    public const STATUS_REFUSED = "Refusée";

    public static function getAvailableStatus()
    {
        return [
            Recipe::STATUS_NOT_APPROVED => Recipe::STATUS_NOT_APPROVED,
            Recipe::STATUS_IN_APPROBATION => Recipe::STATUS_IN_APPROBATION,
            Recipe::STATUS_APPROVED => Recipe::STATUS_APPROVED,
            Recipe::STATUS_REFUSED => Recipe::STATUS_REFUSED
        ];
    }

    public const PICTURE_SIZE_WIDTH = 600;
    public const PICTURE_SIZE_HEIGHT = 600;
    public const PICTURE_DIRECTORY = "recettes/";

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['recipe'])]
    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "Le nom est obligatoire")]
    #[Assert\Length(min: 2, max: 50,minMessage: "Le nom doit faire une minimum de 2 caractères" ,maxMessage: "le nom ne peut pas dépasser 50 caractères" )]
    private string $name;

    #[Groups(['recipe'])]
    #[ORM\Column(nullable: true)]
    #[Assert\LessThan(1441,message: "Le temps de préparation doit être inférieur à 1441")]
    #[Assert\Positive(message: "Le temps de préparation doit être une valeur positive")]
    private ?int $preparationTime = null;

    #[Groups(['recipe'])]
    #[ORM\Column(nullable: true)]
    #[Assert\LessThan(1441,message: "Le temps de cuisson doit être inférieur à 1441")]
    #[Assert\PositiveOrZero(message: "Le temps de cuisson doit être de 0 ou plus")]
    private ?int $cookingTime = null;

    #[Groups(['recipe'])]
    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message: "Définir la difficulté est obligatoire")]
    private ?string $difficulty;

    #[Groups(['recipe'])]
    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "La description est obligatoire")]
    private string $description;

    #[Groups(['recipe'])]
    #[ORM\Column(nullable: false)]
    #[Assert\NotNull(message: "Le prix est obligatoire")]
    private string $price;

    #[Groups(['recipe'])]
    #[ORM\Column]
    #[Assert\NotNull()]
    private \DateTimeImmutable $createdAt;

    #[Groups(['recipe'])]
    #[ORM\Column]
    #[Assert\NotNull()]
    private \DateTimeImmutable $updatedAt;

    #[Groups(['recipe'])]
    #[ORM\Column]
    #[Assert\NotNull(message: "Le choix est obligatoire")]
    private bool $isPublic;

    #[Groups(['recipe_user'])]
    #[ORM\ManyToOne(inversedBy: 'recipes')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[Groups(['recipe_marks'])]
    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: Mark::class, cascade: ['remove'], orphanRemoval: true)]
    private Collection $marks;

    #[Groups(['recipe_recipeIngredients'])]
    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: RecipeIngredient::class, cascade: ['persist','remove'], orphanRemoval: true)]
    #[Assert\Valid()]
    private Collection $recipeIngredients;

    #[Groups(['recipe'])]
    #[ORM\Column]
    #[Assert\LessThan(120,message: "La quantité de nourriture doit être inférieur à 120")]
    #[Assert\Positive(message: "La quantité de nourriture doit être une valeur positive")]
    #[Assert\NotBlank(message: "La quantité est obligatoire")]
    private ?int $foodQuantity = null;

    #[Groups(['recipe'])]
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le type de quantité est obligatoire")]
    private ?string $foodQuantityType = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'favoriteRecipes')]
    private Collection $usersLikingThisRecipe;

    #[ORM\Column(length: 50, options: ["default" => self::STATUS_NOT_APPROVED])]
    private ?string $status = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'recipes')]
    private Collection $categories;

    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: Images::class, cascade:['persist','remove'], orphanRemoval: true)]
    private Collection $images;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->marks = new ArrayCollection();
        $this->recipeIngredients = new ArrayCollection();
        $this->usersLikingThisRecipe = new ArrayCollection();
        $this->status = self::STATUS_NOT_APPROVED;
        $this->categories = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
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
            return 0;
        }

        return round($averageMark, 2);
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

    public function toJSONString(): string
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $serializer = new Serializer([$normalizer]);

        $array = $serializer->normalize($this, null, ['groups' => ['recipe','recipe_recipeIngredients','recipeIngredients_recipe','recipeIngredient','recipeIngredient_ingredient','ingredient']]);
        return json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

    }

    public function getTotalTime(): int
    {
        return $this->preparationTime + $this->cookingTime;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsersLikingThisRecipe(): Collection
    {
        return $this->usersLikingThisRecipe;
    }

    public function addUsersLikingThisRecipe(User $user): self
    {
        if (!$this->usersLikingThisRecipe->contains($user)) {
            $this->usersLikingThisRecipe->add($user);
            $user->addFavoriteRecipe($this);
        }

        return $this;
    }

    public function removeUsersLikingThisRecipe(User $user): self
    {
        if ($this->usersLikingThisRecipe->removeElement($user)) {
            $user->removeFavoriteRecipe($this);
        }

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    public static function isModificationThatRequireStatusReset(Recipe $oldRecipe, Recipe $newRecipe, bool $isExternalRequirement = false):  bool
    {
        //Si des prérequis externe nécessite un reset de status
        if($isExternalRequirement){
            return true;
        }

        //Equalizing non required fields to not raise flag
        $or = clone $oldRecipe;
        $nr = clone $newRecipe;

        $or->setIsPublic(false);
        $nr->setIsPublic(false);


        if($or == $nr)
        {
            return false;
        }
        return true;
    }

    /**
     * If modficiation are required this method is called
     */
    public function statusResetAfterModification(): self
    {
        $this->setStatus(Recipe::STATUS_NOT_APPROVED);

        return $this;
    }

    /**
     * @return Collection<int, Images>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setRecipe($this);
        }

        return $this;
    }

    public function removeImage(Images $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getRecipe() === $this) {
                $image->setRecipe(null);
            }
        }

        return $this;
    }

    public function isAccessibleByPublic(): bool
    {
        if(!$this->isPublic) return false;
        if($this->getStatus() !== Recipe::STATUS_APPROVED) return false;
        return true;
    }
}
