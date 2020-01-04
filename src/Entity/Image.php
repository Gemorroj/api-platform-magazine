<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\CreateImageObjectAction;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 * @ApiResource(
 *     iri="http://schema.org/ImageObject",
 *     attributes={
 *         "normalization_context": {"groups": {"Image:read"}},
 *         "denormalization_context": {"groups": {"Image:write"}}
 *     },
 *     collectionOperations={
 *         "post": {
 *             "controller": CreateImageObjectAction::class,
 *             "deserialize": false,
 *             "security": "is_granted('ROLE_ADMIN')",
 *             "validation_groups": {"Image:write"},
 *             "openapi_context": {
 *                 "requestBody": {
 *                     "content": {
 *                         "multipart/form-data": {
 *                             "schema": {
 *                                 "type": "object",
 *                                 "properties": {
 *                                     "file": {
 *                                         "type": "string",
 *                                         "format": "binary"
 *                                     }
 *                                 }
 *                             }
 *                         }
 *                     }
 *                 }
 *             }
 *         },
 *         "get"
 *     },
 *     itemOperations={
 *         "get",
 *         "delete": {"security": "is_granted('ROLE_ADMIN')"}
 *     }
 * )
 * @Vich\Uploadable
 */
class Image
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer", nullable=false, options={"unsigned": true})
     */
    private $id;

    /**
     * @var string|null
     *
     * @ApiProperty(iri="http://schema.org/contentUrl")
     * @Groups({"Image:read", "Product:read"})
     */
    public $contentUrl;

    /**
     * @var File|null
     *
     * @Assert\NotNull(groups={"Image:write"})
     * @Assert\Image(groups={"Image:write"})
     * @Vich\UploadableField(mapping="images", fileNameProperty="filePath")
     * @Groups({"Image:write"})
     */
    public $file;

    /**
     * @var string|null
     *
     * @ORM\Column(nullable=true)
     */
    public $filePath;

    public function getId(): ?int
    {
        return $this->id;
    }
}
