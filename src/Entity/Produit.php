<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[UniqueEntity('nom')]
#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\Length(min:5,max:50,minMessage:'produit.nom.minLen', maxMessage:'produit.nom.maxLen')]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\Positive(message:'produit.prix.error')]
    private ?float $prix = null;

    #[ORM\Column]
    #[Assert\PositiveOrZero()]
    private ?int $stock = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $img = null;

    #[ORM\ManyToMany(targetEntity: ContenuPanier::class, mappedBy: 'produit')]
    private Collection $contenuPaniers;

    public function __construct()
    {
        $this->contenuPaniers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(?string $img): self
    {
        $this->img = $img;

        return $this;
    }

    /**
     * @return Collection<int, ContenuPanier>
     */
    public function getContenuPaniers(): Collection
    {
        return $this->contenuPaniers;
    }

    // public function addContenuPanier(ContenuPanier $contenuPanier): self
    // {
    //     if (!$this->contenuPaniers->contains($contenuPanier)) {
    //         $this->contenuPaniers->add($contenuPanier);
    //         $contenuPanier->addProduit($this);
    //     }

    //     return $this;
    // }

    // public function removeContenuPanier(ContenuPanier $contenuPanier): self
    // {
    //     if ($this->contenuPaniers->removeElement($contenuPanier)) {
    //         $contenuPanier->removeProduit($this);
    //     }

    //     return $this;
    // }
}
