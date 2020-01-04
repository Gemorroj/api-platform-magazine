<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @see https://developers.google.com/search/docs/data-types/product
 *
 * @ApiResource(
 *     iri="http://schema.org/Product",
 *     collectionOperations={
 *         "get",
 *         "post": {"security": "is_granted('ROLE_ADMIN')"}
 *     },
 *     itemOperations={
 *         "get",
 *         "put": {"security": "is_granted('ROLE_ADMIN')"},
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
     * @Groups({"Product:read"})
     */
    public $createdAt;

    /**
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
     * @Assert\NotBlank
     * @Groups({"Product:read", "Product:write"})
     */
    public $name;

    /**
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
}
