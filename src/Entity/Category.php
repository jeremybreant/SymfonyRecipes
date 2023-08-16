<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    private ?string $slug = null;

    #[ORM\ManyToMany(targetEntity: self::class, cascade: ['persist'], inversedBy: 'parentCategories')]
    private Collection $childCategories;

    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'childCategories')]
    private Collection $parentCategories;

    #[ORM\ManyToMany(targetEntity: Recipe::class, mappedBy: 'categories')]
    private Collection $recipes;

    public function __construct()
    {
        $this->childCategories = new ArrayCollection();
        $this->parentCategories = new ArrayCollection();
        $this->recipes = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getChildCategories(): Collection
    {
        return $this->childCategories;
    }

    public function addMultipleChildCategories(array $childCategories): self
    {
        foreach($childCategories as $childCategory)
        {
            $this->addChildCategory($childCategory);
        }
        return $this;
    }
    public function addChildCategory(self $childCategory): self
    {
        if (!$this->childCategories->contains($childCategory)) {
            $this->childCategories->add($childCategory);
        }

        return $this;
    }

    public function removeChildCategory(self $childCategory): self
    {
        $this->childCategories->removeElement($childCategory);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getParentCategories(): Collection
    {
        return $this->parentCategories;
    }

    public function addParentCategory(self $parentCategory): self
    {
        if (!$this->parentCategories->contains($parentCategory)) {
            $this->parentCategories->add($parentCategory);
            $parentCategory->addChildCategory($this);
        }

        return $this;
    }

    public function removeParentCategory(self $parentCategory): self
    {
        if ($this->parentCategories->removeElement($parentCategory)) {
            $parentCategory->removeChildCategory($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Recipe>
     */
    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    public function addRecipe(Recipe $recipe): self
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes->add($recipe);
            $recipe->addCategory($this);
        }

        return $this;
    }

    public function removeRecipe(Recipe $recipe): self
    {
        if ($this->recipes->removeElement($recipe)) {
            $recipe->removeCategory($this);
        }

        return $this;
    }
}
