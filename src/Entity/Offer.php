<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     iri="http://schema.org/Offer",
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
 * @ORM\Entity(repositoryClass="App\Repository\OfferRepository")
 */
class Offer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer", nullable=false, options={"unsigned": true})
     * @Groups({"Product:read"})
     */
    private $id;

    /**
     * @var Product
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="offers")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    public $product;

    /**
     * @var float|null
     * @ORM\Column(type="decimal", nullable=true, scale=2, options={"unsigned": true})
     * @ApiProperty(iri="http://schema.org/price")
     * @Groups({"Product:read", "Product:write"})
     * @Assert\Type(type="numeric", groups={"Product:write"})
     * @Assert\PositiveOrZero(groups={"Product:write"})
     */
    public $price;
    /**
     * ISO 4217.
     *
     * @var string|null
     * @ORM\Column(type="string", nullable=true, length=3)
     * @ApiProperty(iri="http://schema.org/priceCurrency")
     * @Groups({"Product:read", "Product:write"})
     * @Assert\Currency(groups={"Product:write"})
     */
    public $priceCurrency;
    /**
     * @var string
     * @ORM\Column(type="string", nullable=false, length=255)
     * @ApiProperty(
     *     iri="http://schema.org/availability",
     *     attributes={
     *         "openapi_context": {
     *             "type": "string",
     *             "enum": {
     *                 "http://schema.org/Discontinued",
     *                 "http://schema.org/InStock",
     *                 "http://schema.org/InStoreOnly",
     *                 "http://schema.org/LimitedAvailability",
     *                 "http://schema.org/OnlineOnly",
     *                 "http://schema.org/OutOfStock",
     *                 "http://schema.org/PreOrder",
     *                 "http://schema.org/PreSale",
     *                 "http://schema.org/SoldOut"
     *             }
     *         }
     *     }
     * )
     * @Groups({"Product:read", "Product:write"})
     * @Assert\Choice(choices={
     *     "http://schema.org/Discontinued",
     *     "http://schema.org/InStock",
     *     "http://schema.org/InStoreOnly",
     *     "http://schema.org/LimitedAvailability",
     *     "http://schema.org/OnlineOnly",
     *     "http://schema.org/OutOfStock",
     *     "http://schema.org/PreOrder",
     *     "http://schema.org/PreSale",
     *     "http://schema.org/SoldOut"
     * }, groups={"Product:write"})
     */
    public $availability;

    public function getId(): ?int
    {
        return $this->id;
    }
}
