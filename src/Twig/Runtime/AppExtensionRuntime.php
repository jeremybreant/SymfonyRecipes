<?php

namespace App\Twig\Runtime;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Twig\Extension\RuntimeExtensionInterface;

class AppExtensionRuntime implements RuntimeExtensionInterface
{
    public $categoryRepository;
    public function __construct(
        CategoryRepository $categoryRepository
    )
    {
        // Inject dependencies if needed
        $this->categoryRepository = $categoryRepository;
    }

    public function minutesToHour($value): string
    {
        if ($value < 60) {
            return sprintf('%s min', $value);
        }

        $minutes = $value % 60;
        $hours = floor(($value-$minutes) / 60);

        if ($minutes < 10) {
            $minutes = '0' . $minutes;
        }

        $time = sprintf('%s h %s min', $hours, $minutes);

        return $time;
    }

    public function displayDate($value): string
    {
        return sprintf('%s',$value->format('Y/m/d H:i:s'));
    }

    public function getRootCategory($slug): string
    {
        return $this->categoryRepository->findOneBySlug($slug)->getRootCat()->getName();
    }

}
