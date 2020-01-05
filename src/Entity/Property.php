<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     iri="http://schema.org/PropertyValue",
 *     collectionOperations={
 *         "get",
 *         "post": {"security": "is_granted('ROLE_ADMIN')", "validation_groups": {"Product:write"}}
 *     },
 *     itemOperations={
 *         "get",
 *         "put": {"security": "is_granted('ROLE_ADMIN')", "validation_groups": {"Product:write"}},
 *         "delete": {"security": "is_granted('ROLE_ADMIN')"}
 *     },
 *     attributes={
 *         "normalization_context": {"groups": {"Product:read"}},
 *         "denormalization_context": {"groups": {"Product:write"}}
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\PropertyRepository")
 */
class Property
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer", nullable=false, options={"unsigned": true})
     * @Groups({"Product:read"})
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false, length=255)
     * @ApiProperty(iri="http://schema.org/value", required=true)
     * @Groups({"Product:read", "Product:write"})
     * @Assert\NotNull(groups={"Product:write"})
     */
    public $value;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false, length=255)
     * @ApiProperty(iri="http://schema.org/propertyID", required=true)
     * @Groups({"Product:read", "Product:write"})
     * @Assert\NotBlank(groups={"Product:write"})
     */
    public $propertyID;

    /**
     * @var Product
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="additionalProperty")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    public $product;

    public function getId(): ?int
    {
        return $this->id;
    }
}
