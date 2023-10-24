<?php
declare(strict_types=1);

namespace App\Interface;
use App\Entity\Images;
use Doctrine\Common\Collections\Collection;

interface ImagesInterface
{
    /**
     * @return Collection<int, Images>
     */
    public function getImages();
}