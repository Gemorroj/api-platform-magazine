<?php

namespace App\Resolver;

use App\Entity\Image;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use ApiPlatform\Core\GraphQl\Resolver\MutationResolverInterface;

final class CreateImageObjectResolver implements MutationResolverInterface
{
    public function __invoke($item, array $context): Image
    {
        $uploadedFile = $context['args']['input']['file'];
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $image = new Image();
        $image->file = $uploadedFile;

        return $image;
    }
}
