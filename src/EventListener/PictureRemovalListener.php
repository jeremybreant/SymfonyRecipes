<?php
declare(strict_types=1);


namespace App\EventListener;

use Doctrine\ORM\Events;
use App\Service\PictureService;
use App\Interface\PictureServiceInterface;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: Events::class, method: 'preRemove')]
class PictureRemovalListener
{
    private $pictureService;

    public function __construct(PictureService $pictureService)
    {
        $this->pictureService = $pictureService;
    }

    public function __invoke(PreRemoveEventArgs $preRemoveEventArgs)
    {
        $entity = $preRemoveEventArgs->getObject();

        if (!$entity instanceof PictureServiceInterface) {
            return;
        }

        $this->pictureService->delete($entity->getPictureName(), $entity->getPictureDirectory(), $entity->getPictureWidth(), $entity->getPictureHeight());
    }
}