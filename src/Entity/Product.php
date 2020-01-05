<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @see https://developers.google.com/search/docs/data-types/product
 * @see https://yandex.ru/support/webmaster/supported-schemas/goods-prices.html
 * @see https://search.google.com/structured-data/testing-tool/
 * @see https://webmaster.yandex.ru/tools/microtest/
 *
 * @ApiResource(
 *     iri="http://schema.org/Product",
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
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer", nullable=false, options={"unsigned": true})
     * @Groups({"Product:read"})
     */
    private $id;

    /**
     * @var \DateTimeImmutable
     *
     * @ORM\Column(type="datetimetz_immutable", nullable=false)
     */
    public $createdAt;

    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity="Image", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="products_images",
     *     joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="image_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     * @ApiProperty(iri="http://schema.org/image")
     * @Groups({"Product:read", "Product:write"})
     */
    public $image;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false, length=255)
     * @ApiProperty(iri="http://schema.org/name", required=true)
     * @Assert\NotBlank(groups={"Product:write"})
     * @Groups({"Product:read", "Product:write"})
     */
    public $name;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true, length=5000)
     * @ApiProperty(iri="http://schema.org/description", required=false)
     * @Groups({"Product:read", "Product:write"})
     */
    public $description;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Offer", mappedBy="product", cascade={"persist", "remove"})
     * @ApiProperty(iri="http://schema.org/Offer")
     * @Groups({"Product:read", "Product:write"})
     * @Assert\Valid(groups={"Product:write"})
     */
    public $offers;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Property", mappedBy="product", cascade={"persist", "remove"})
     * @ApiProperty(iri="http://schema.org/additionalProperty")
     * @Groups({"Product:read", "Product:write"})
     * @Assert\Valid(groups={"Product:write"})
     */
    public $additionalProperty;

    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="products")
     * @ORM\JoinTable(name="products_categories")
     * @ApiProperty(iri="http://schema.org/category")
     * @Groups({"Product:read", "Product:write"})
     */
    public $category;

    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->image = new ArrayCollection();
        $this->additionalProperty = new ArrayCollection();
        $this->offers = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function addCategory(Category $category): void
    {
        $category->products->add($this);
        $this->category->add($category);
    }

    public function removeCategory(Category $category): void
    {
        $category->products->removeElement($this);
        $this->category->removeElement($category);
    }

    public function addImage(Image $image): void
    {
        $this->image->add($image);
    }

    public function removeImage(Image $image): void
    {
        $this->image->removeElement($image);
    }

    public function addAdditionalProperty(Property $property): void
    {
        $property->product = $this;
        $this->additionalProperty->add($property);
    }

    public function removeAdditionalProperty(Property $property): void
    {
        $property->product = null;
        $this->additionalProperty->removeElement($property);
    }

    public function addOffer(Offer $offer): void
    {
        $offer->product = $this;
        $this->offers->add($offer);
    }

    public function removeOffer(Offer $offer): void
    {
        $offer->product = null;
        $this->offers->removeElement($offer);
    }
}
