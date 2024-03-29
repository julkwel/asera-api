<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\State\UserPasswordHasher;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\PasswordStrength;

#[ORM\Entity]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(processor: UserPasswordHasher::class),
        new Get(),
        new Put(processor: UserPasswordHasher::class),
        new Patch(processor: UserPasswordHasher::class),
        new Delete(),
    ],
    normalizationContext: ['groups' => ['user:read']],
    denormalizationContext: ['groups' => ['user:write']],
    mercure: true,
)]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false)]
#[ORM\Table(name:"`user`")]
#[UniqueEntity(fields: 'username', message: 'There is already an account with this username')]
#[Delete(security: "is_granted('ROLE_ADMIN') or object.getOwner() == user")]
#[Put(security: "is_granted('ROLE_ADMIN') or object.getOwner() == user")]
#[Patch(security: "is_granted('ROLE_ADMIN') or object.getOwner() == user")]
#[Get(security: "is_granted('ROLE_ADMIN') or object.getOwner() == user")]
#[GetCollection(security: "is_granted('ROLE_ADMIN')")]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['user:read'])]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['user:read', 'user:write'])]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:read', 'user:write'])]
    private ?string $lastname = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[Groups(['user:read', 'user:write'])]
    #[Assert\Valid]
    private ?Contact $contact = null;

    #[ORM\Column(length: 100)]
    #[Groups(['user:read', 'user:write'])]
    private ?string $username = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $password = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['user:read', 'user:write'])]
    private ?int $sex = null;

    #[ORM\Column(type: Types::JSON)]
    #[Groups(['user:read', 'user:write'])]
    private array $roles = ['ROLE_USER'];

    private ?string $salt = null;

    #[Assert\PasswordStrength([
        'minScore' => PasswordStrength::STRENGTH_WEAK,
        'message' => 'Your password is too easy to guess. Asera\'s security policy requires to use a stronger password.'
    ])]
    #[Groups(['user:write'])]
    private ?string $plainPassword = null;

    #[ORM\ManyToMany(targetEntity: Job::class, mappedBy: 'candidates')]
    private Collection $jobs;

    #[ORM\OneToOne(inversedBy: 'owner', cascade: ['persist', 'remove'])]
    #[Groups(['user:read', 'user:write'])]
    private ?UserMedia $media = null;

    public function __construct()
    {
        $this->jobs = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function setContact(?Contact $contact): static
    {
        $this->contact = $contact;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getSex(): ?int
    {
        return $this->sex;
    }

    public function setSex(int $sex): static
    {
        $this->sex = $sex;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;

        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @return Collection<int, Job>
     */
    public function getJobs(): Collection
    {
        return $this->jobs;
    }

    public function addJob(Job $job): static
    {
        if (!$this->jobs->contains($job)) {
            $this->jobs->add($job);
            $job->addCandidate($this);
        }

        return $this;
    }

    public function removeJob(Job $job): static
    {
        if ($this->jobs->removeElement($job)) {
            $job->removeCandidate($this);
        }

        return $this;
    }

    public function getOwner(): User
    {
        return $this;
    }

    public function getMedia(): ?UserMedia
    {
        return $this->media;
    }

    public function setMedia(?UserMedia $media): static
    {
        $this->media = $media;

        return $this;
    }
}
