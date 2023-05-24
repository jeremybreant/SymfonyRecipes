<?php

namespace App\Entity;

use App\Repository\RecipeIngredientRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[UniqueEntity(
    fields: ['recipe','ingredient'],
    message: 'L\'ingredient est déjà lié à cette recette',
    errorPath: 'recipe'
)]
#[ORM\Entity(repositoryClass: RecipeIngredientRepository::class)]
class RecipeIngredient
{
    public const UNIT_LITER = "l";
    public const UNIT_CENTILITER = "cl";
    public const UNIT_MILLILITER = "ml";
    public const UNIT_KILOGRAM = "kg";
    public const UNIT_GRAM = "g";
    public const UNIT_SOUP_SPOON = "cs";
    public const UNIT_COFFEE_SPOON = "cc";
    public const UNIT_NONE = "";

    public static function getAvailableUnits()
    {
        return [
            RecipeIngredient::UNIT_LITER => RecipeIngredient::UNIT_LITER,
            RecipeIngredient::UNIT_CENTILITER => RecipeIngredient::UNIT_CENTILITER,
            RecipeIngredient::UNIT_MILLILITER => RecipeIngredient::UNIT_MILLILITER,
            RecipeIngredient::UNIT_KILOGRAM => RecipeIngredient::UNIT_KILOGRAM,
            RecipeIngredient::UNIT_GRAM => RecipeIngredient::UNIT_GRAM,
            RecipeIngredient::UNIT_SOUP_SPOON => RecipeIngredient::UNIT_SOUP_SPOON,
            RecipeIngredient::UNIT_COFFEE_SPOON => RecipeIngredient::UNIT_COFFEE_SPOON,
            RecipeIngredient::UNIT_NONE => RecipeIngredient::UNIT_NONE
        ];
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $quantity = null;

    #[ORM\Column(length: 10)]
    private ?string $unitType = null;

    #[ORM\ManyToOne(inversedBy: 'recipeIngredients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Recipe $recipe = null;

    #[ORM\ManyToOne(inversedBy: 'recipeIngredients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ingredient $ingredient = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getUnitType(): ?string
    {
        return $this->unitType;
    }

    public function setUnitType(string $unitType): self
    {
        $this->unitType = $unitType;

        return $this;
    }

    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(?Recipe $recipe): self
    {
        $this->recipe = $recipe;

        return $this;
    }

    public function getIngredient(): ?Ingredient
    {
        return $this->ingredient;
    }

    public function setIngredient(?Ingredient $ingredient): self
    {
        $this->ingredient = $ingredient;

        return $this;
    }
}
