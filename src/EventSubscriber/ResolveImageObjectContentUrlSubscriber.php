<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use ApiPlatform\Core\Util\RequestAttributesExtractor;
use App\Entity\Image;
use App\Entity\Product;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Vich\UploaderBundle\Storage\StorageInterface;

final class ResolveImageObjectContentUrlSubscriber implements EventSubscriberInterface
{
    private StorageInterface $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['onPreSerialize', EventPriorities::PRE_SERIALIZE],
        ];
    }

    public function onPreSerialize(ViewEvent $event): void
    {
        $controllerResult = $event->getControllerResult();
        $request = $event->getRequest();

        if ($controllerResult instanceof Response || !$request->attributes->getBoolean('_api_respond', true)) {
            return;
        }

        $attributes = RequestAttributesExtractor::extractAttributes($request);
        if (!$attributes) {
            return;
        }

        if (\is_a($attributes['resource_class'], Image::class, true)) {
            $this->fill($controllerResult);
        } elseif (\is_a($attributes['resource_class'], Product::class, true)) {
            if (\is_iterable($controllerResult)) {
                foreach ($controllerResult as $controllerResultItem) {
                    $this->fill($controllerResultItem->image);
                }
            } else {
                $this->fill($controllerResult->image);
            }
        }
    }

    private function fill($mediaObjects): void
    {
        if (!\is_iterable($mediaObjects)) {
            $mediaObjects = [$mediaObjects];
        }

        foreach ($mediaObjects as $mediaObject) {
            if (!$mediaObject instanceof Image) {
                continue;
            }

            $mediaObject->contentUrl = $this->storage->resolveUri($mediaObject, 'file');
        }
    }
}
