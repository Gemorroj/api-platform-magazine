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
 * @ApiResource(
 *     iri="http://schema.org/ItemList",
 *     collectionOperations={
 *         "get",
 *         "post": {"security": "is_granted('ROLE_ADMIN')", "validation_groups": {"write"}}
 *     },
 *     itemOperations={
 *         "get",
 *         "put": {"security": "is_granted('ROLE_ADMIN')", "validation_groups": {"write"}},
 *         "delete": {"security": "is_granted('ROLE_ADMIN')"}
 *     },
 *     attributes={
 *         "force_eager": false,
 *         "normalization_context": {"groups": {"read"}},
 *         "denormalization_context": {"groups": {"write"}}
 *     },
 *     graphql={
 *         "item_query",
 *         "collection_query",
 *         "delete"={"security"="is_granted('ROLE_ADMIN')"},
 *         "update"={"security"="is_granted('ROLE_ADMIN')", "validation_groups": {"write"}},
 *         "create"={"security"="is_granted('ROLE_ADMIN')", "validation_groups": {"write"}}
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer", nullable=false, options={"unsigned": true})
     * @Groups({"read"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false, length=255)
     * @ApiProperty(iri="http://schema.org/name", required=true)
     * @Assert\NotBlank(groups={"write"})
     * @Groups({"read", "write"})
     */
    public $name;

    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity="Product", mappedBy="category", cascade={"persist", "remove"})
     * @Groups({"read", "write"})
     */
    public $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function addProduct(Product $product): void
    {
        $product->category->add($this);
        $this->products->add($product);
    }

    public function removeProduct(Product $product): void
    {
        $product->category->removeElement($this);
        $this->products->removeElement($product);
    }
}
