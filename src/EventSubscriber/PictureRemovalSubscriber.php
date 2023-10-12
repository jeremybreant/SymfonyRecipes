<?php
declare(strict_types=1);


namespace App\EventSubscriber;

use App\Interface\ImagesInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use App\Service\PictureService;
use App\Interface\PictureServiceInterface;
use Doctrine\ORM\Event\PreRemoveEventArgs;

class PictureRemovalSubscriber implements EventSubscriber
{
    private $pictureService;

    public function __construct(PictureService $pictureService)
    {
        $this->pictureService = $pictureService;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::preRemove
        ];
    }

    public function preRemove(PreRemoveEventArgs $preRemoveEventArgs)
    {
        $entity = $preRemoveEventArgs->getObject();

        if (!$entity instanceof PictureServiceInterface) {
            return;
        }

        $this->pictureService->delete($entity->getPictureName(), $entity->getPictureDirectory(), 300, 300);
    }
}