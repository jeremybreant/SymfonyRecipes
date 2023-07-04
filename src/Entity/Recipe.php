<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Config\TwigExtra\StringConfig;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


#[ORM\Entity(repositoryClass: RecipeRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[Vich\Uploadable]
#[UniqueEntity(
    fields: ['user', 'name'],
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

    #[Groups(['recipe'])]
    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "Le nom est obligatoire")]
    #[Assert\Length(min: 2, max: 50,minMessage: "Le nom doit faire une minimum de 2 caractères" ,maxMessage: "le nom ne peut pas dépasser 50 caractères" )]
    private string $name;

    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'recipe_images', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

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
    private bool $isFavorite;

    #[Groups(['recipe'])]
    #[ORM\Column]
    #[Assert\NotNull(message: "Le choix est obligatoire")]
    private bool $isPublic;

    #[Groups(['recipe_user'])]
    #[ORM\ManyToOne(inversedBy: 'recipes')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[Groups(['recipe_marks'])]
    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: Mark::class, orphanRemoval: true)]
    private Collection $marks;

    #[Groups(['recipe_recipeIngredients'])]
    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: RecipeIngredient::class, cascade: ['persist'], orphanRemoval: true)]
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


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->marks = new ArrayCollection();
        $this->recipeIngredients = new ArrayCollection();
        $this->users = new ArrayCollection();
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

    public function getTotalTime(): string
    {
        return $this->preparationTime + $this->cookingTime;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsersLikingThisRecipe(): Collection
    {
        return $this->users;
    }

    public function addUsersLikingThisRecipe(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addFavoriteRecipe($this);
        }

        return $this;
    }

    public function removeUsersLikingThisRecipe(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeFavoriteRecipe($this);
        }

        return $this;
    }
}
