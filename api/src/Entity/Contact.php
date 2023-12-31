<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ApiResource(
    normalizationContext: ['groups' => ['user:read', 'default', 'company:read']],
    denormalizationContext: ['groups' => ['user:write', 'contact:write', 'default', 'company:write']],
    mercure: true
)]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false)]
#[UniqueEntity(
    fields: 'email',
    message: 'This email is already used.'
)]
class Contact
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['user:read', 'company:read'])]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:read', 'user:write', 'contact:write', 'company:write'])]
    private ?string $email = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    #[Groups(['user:read', 'user:write', 'contact:write', 'company:write'])]
    private ?array $phones = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['user:read', 'user:write', 'contact:write'])]
    private ?string $linkedin = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['user:read', 'user:write', 'contact:write'])]
    private ?string $github = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['user:read', 'user:write', 'contact:write'])]
    private ?string $stackoverflow = null;

    #[ORM\ManyToOne(inversedBy: 'contact')]
    private ?Company $company = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['user:read', 'user:write', 'contact:write', 'company:write'])]
    private ?string $web = null;

    /**
     * When this object is called to be a string, force to return email
     * Do not give an error
     */
    public function __toString(): string
    {
        return $this->email;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhones(): ?array
    {
        return $this->phones;
    }

    public function setPhones(?array $phones): static
    {
        $this->phones = $phones;

        return $this;
    }

    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    public function setLinkedin(?string $linkedin): static
    {
        $this->linkedin = $linkedin;

        return $this;
    }

    public function getGithub(): ?string
    {
        return $this->github;
    }

    public function setGithub(?string $github): static
    {
        $this->github = $github;

        return $this;
    }

    public function getStackoverflow(): ?string
    {
        return $this->stackoverflow;
    }

    public function setStackoverflow(?string $stackoverflow): static
    {
        $this->stackoverflow = $stackoverflow;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function getWeb(): ?string
    {
        return $this->web;
    }

    public function setWeb(?string $web): static
    {
        $this->web = $web;

        return $this;
    }
}
