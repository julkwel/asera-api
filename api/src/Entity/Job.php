<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\JobRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: JobRepository::class)]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false)]
#[ApiResource(
    normalizationContext: ['groups' => ['job:read']],
    denormalizationContext: ['groups' => ['job:write']],
    mercure: true
)]
class Job
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['job:read'])]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['job:read', 'job:write'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['job:read', 'job:write'])]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['job:read', 'job:write'])]
    private ?string $salary = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['job:read', 'job:write'])]
    private ?string $diploma = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['job:read', 'job:write'])]
    private ?string $experiences = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'jobs')]
    #[Groups(['job:read', 'job:write'])]
    private Collection $candidates;

    public function __construct()
    {
        $this->candidates = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getSalary(): ?string
    {
        return $this->salary;
    }

    public function setSalary(?string $salary): static
    {
        $this->salary = $salary;

        return $this;
    }

    public function getDiploma(): ?string
    {
        return $this->diploma;
    }

    public function setDiploma(?string $diploma): static
    {
        $this->diploma = $diploma;

        return $this;
    }

    public function getExperiences(): ?string
    {
        return $this->experiences;
    }

    public function setExperiences(?string $experiences): static
    {
        $this->experiences = $experiences;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getCandidates(): Collection
    {
        return $this->candidates;
    }

    public function addCandidate(User $candidate): static
    {
        if (!$this->candidates->contains($candidate)) {
            $this->candidates->add($candidate);
        }

        return $this;
    }

    public function removeCandidate(User $candidate): static
    {
        $this->candidates->removeElement($candidate);

        return $this;
    }
}
