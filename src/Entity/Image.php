<?php

namespace App\Entity;

use ApiPlatform\Core\Action\NotFoundAction;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Resolver\CreateImageObjectResolver;
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
 *         "normalization_context": {"groups": {"read"}},
 *         "denormalization_context": {"groups": {"write"}}
 *     },
 *     collectionOperations={},
 *     itemOperations={"get": {"controller": NotFoundAction::class, "read": false, "output": false}},
 *     graphql={
 *         "upload": {
 *             "security": "is_granted('ROLE_ADMIN')",
 *             "validation_groups": {"write"},
 *             "mutation": CreateImageObjectResolver::class,
 *             "deserialize": false,
 *             "args": {
 *                 "file": {"type": "Upload!", "description": "The file to upload"}
 *             }
 *         },
 *         "item_query",
 *         "collection_query",
 *         "delete": {"security": "is_granted('ROLE_ADMIN')"},
 *         "create": {"security": "is_granted('ROLE_ADMIN')", "validation_groups": {"write"}}
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
     * @Groups({"read"})
     */
    private $id;

    /**
     * @var string|null
     *
     * @ApiProperty(iri="http://schema.org/contentUrl")
     * @Groups({"read"})
     */
    public $contentUrl;

    /**
     * @var File|null
     *
     * @Assert\NotNull(groups={"write"})
     * @Assert\Image(groups={"write"})
     * @Vich\UploadableField(mapping="images", fileNameProperty="filePath")
     * @Groups({"write"})
     */
    public $file;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    public $filePath;

    public function getId(): ?int
    {
        return $this->id;
    }
}
