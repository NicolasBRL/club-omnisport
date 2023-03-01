<?php

namespace App\Entity;

use App\Repository\LicencieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LicencieRepository::class)]
class Licencie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageUrl = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateEntree = null;

    #[ORM\Column(length: 10)]
    private ?string $status = null;

    #[ORM\ManyToMany(targetEntity: Equipe::class, inversedBy: 'licencies')]
    private Collection $equipe;

    private ?string $statusLabel = null;
    private ?string $statusColor = null;

    public function __construct()
    {
        $this->equipe = new ArrayCollection();
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): self
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    public function getDateEntree(): ?\DateTimeInterface
    {
        return $this->dateEntree;
    }

    public function setDateEntree(\DateTimeInterface $dateEntree): self
    {
        $this->dateEntree = $dateEntree;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        $this->statusLabel = $this->getStatusLabel();
        $this->statusColor = $this->getStatusColor();

        return $this;
    }

    public function getStatusLabel(): ?string
    {
        switch ($this->status) {
            case 'waiting':
                $this->statusColor = 'yellow';
                return 'En attente';
            case 'validated':
                $this->statusColor = 'green';
                return 'Validé';
            case 'removed':
                $this->statusColor = 'pink';
                return 'Retiré';
            default:
                return $this->status;
        }
    }

    public function getStatusColor(): ?string
    {
        switch ($this->status) {
            case 'waiting':
                return 'yellow';
            case 'validated':
                return 'green';
            case 'removed':
                return 'pink';
            default:
                return $this->status;
        }
    }

    /**
     * @return Collection<int, Equipe>
     */
    public function getEquipe(): Collection
    {
        return $this->equipe;
    }

    public function addEquipe(Equipe $equipe): self
    {
        if (!$this->equipe->contains($equipe)) {
            $this->equipe->add($equipe);
        }

        return $this;
    }

    public function removeEquipe(Equipe $equipe): self
    {
        $this->equipe->removeElement($equipe);

        return $this;
    }
}
