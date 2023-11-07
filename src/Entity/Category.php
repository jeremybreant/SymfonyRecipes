<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

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

    #[ORM\ManyToMany(targetEntity: Recipe::class, mappedBy: 'categories')]
    private Collection $recipes;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'childCategories')]
    private ?self $parentCategory = null;

    #[ORM\OneToMany(mappedBy: 'parentCategory', cascade: ['persist'], targetEntity: self::class)]
    private Collection $childCategories;

    public function __construct()
    {
        $this->childCategories = new ArrayCollection();
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

    //Get full tree of subs categories
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

    //Get all intermediate category from actual category to the root category
    public function getParentCatRecurcive(): ?array
    {
        $totalParentCats = array();

        $parentCat = $this->getParentCategory();
        if($parentCat === null)
        {
            return null;
        }
        array_push($totalParentCats, $parentCat);
        $parentParentCats = $parentCat->getParentCatRecurcive();
        if(null != $parentParentCats)
        {
            foreach($parentParentCats as $parentParentCat){
                array_push($totalParentCats, $parentParentCat);
            }
        }

        return $totalParentCats;
    }

    //get the root category
    public function getRootCat(): Category
    {

        /** @var Category */
        $parentCat = $this->getParentCategory();
        if(empty($parentCat))
        {
            return $this;
        }

        while(!empty($parentCat)){ 
            $previousCat = $parentCat;  
            $parentCat = $parentCat->getParentCategory();
        }
        
        return $previousCat;
    }

    public function getParentCategory(): ?self
    {
        return $this->parentCategory;
    }

    public function setParentCategory(?self $parentCategory): static
    {
        if ($this->parentCategory != $parentCategory) {
            if($this->parentCategory != null)
            {
                //removing child from previous parent
                $this->parentCategory->removeChildCategory($this);
            }
            //defining new parent
            $this->parentCategory = $parentCategory;
            
            if($this->parentCategory != null)
            {
                //adding child to new parent
                $this->parentCategory->addChildCategory($this);
            }
        }

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

    public function addChildCategory(self $childCategory): static
    {
        if (!$this->childCategories->contains($childCategory)) {
            $this->childCategories->add($childCategory);
            $childCategory->setParentCategory($this);
        }

        return $this;
    }

    public function removeChildCategory(self $childCategory): static
    {
        if ($this->childCategories->removeElement($childCategory)) {
            // set the owning side to null (unless already changed)
            if ($childCategory->getParentCategory() === $this) {
                $childCategory->setParentCategory(null);
            }
        }

        return $this;
    }
}
