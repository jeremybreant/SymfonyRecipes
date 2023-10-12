<?php
declare(strict_types=1);

namespace App\Interface;
use App\Entity\Images;
use Doctrine\Common\Collections\Collection;

interface PictureServiceInterface
{
    /**
     * @return string
     */
    public function getPictureName();

    /**
     * @return string
     */
    public function getPictureDirectory();

    /**
     * @return int
     */
    public function getPictureWidth();

    /**
     * @return int
     */
    public function getPictureHeight();
}