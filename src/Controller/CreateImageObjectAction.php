<?php

namespace App\Controller;

use App\Entity\Image;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class CreateImageObjectAction
{
    public function __invoke(Request $request): Image
    {
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $image = new Image();
        $image->file = $uploadedFile;

        return $image;
    }
}
