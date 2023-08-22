<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[UniqueEntity(
    fields: ['slug'],
    message: 'Cette catégorie à un nom déjà utilisé',
    errorPath: 'name'
)]
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
            $childCategory->addParentCategory($this);
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

    public function getSubCatRecurcive(): ?array
    {
        $totalSubCat = array();

        $subCats = $this->getChildCategories()->toArray();
        if(empty($subCats))
        {
            return null;
        }
        
        foreach($subCats as $subCat){
            array_push($totalSubCat, $subCat);
            $subSubCats = $subCat->getSubCatRecurcive();
            if(empty($subSubCats))
            {
                continue;
            }
            foreach($subSubCats as $subSubCat){
                array_push($totalSubCat, $subSubCat);
            }
        }

        return $totalSubCat;
    }

    public function getParentCatRecurcive(): ?array
    {
        $totalParentCats = array();

        $parentCats = $this->getParentCategories()->toArray();
        if(empty($parentCats))
        {
            return null;
        }
        
        foreach($parentCats as $parentCat){
            array_push($totalParentCats, $parentCat);
            $parentParentCats = $parentCat->getParentCatRecurcive();
            if(empty($parentParentCats))
            {
                continue;
            }
            foreach($parentParentCats as $parentParentCat){
                array_push($totalParentCats, $parentParentCat);
            }
        }

        return $totalParentCats;
    }

    public function getRootCat(){

        /** @var Category[] */
        $parentCats = $this->getParentCategories()->toArray();
        if(empty($parentCats))
        {
            return $this;
        }

        while(!empty($parentCats)){ 
            $previousCats = $parentCats;  
            $parentCats = $parentCats[0]->getParentCategories()->toArray();
        }
        
        return $previousCats[0];
    }

    public function getPublicRecipes(): array
    {
        $publicRecipes = array();
        foreach($this->recipes as $recipe){
            if($recipe->getIsPublic()){
                array_push($publicRecipes, $recipe);
            }
        }
        return $publicRecipes;
    }
}
