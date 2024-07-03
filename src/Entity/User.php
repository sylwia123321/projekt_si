<?php
/**
 * User entity.
 */

namespace App\Entity;

use App\Entity\Enum\UserRole;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User.
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
#[ORM\UniqueConstraint(name: 'email_idx', columns: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * Email.
     */
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email = null;

    /**
     * Roles.
     *
     * @var array<int, string>
     */
    #[ORM\Column(type: 'json')]
    private array $roles = [];

    /**
     * Password.
     */
    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank]
    private ?string $password = null;

    /**
     * Avatar.
     *
     * @var Avatar|null Avatar
     */
    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Avatar $avatar = null;

    /**
     * Get the primary key ID.
     *
     * @return int|null int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the user's email address.
     *
     * @return string|null string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Set the user's email address.
     *
     * @param string $email Email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Get the user identifier (email address).
     *
     * @return string string
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * Get the username (email address).
     *
     * @return string string
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * Get the user roles.
     *
     * @return array|string[] Array
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = UserRole::ROLE_USER->value;

        return array_unique($roles);
    }

    /**
     * Set the user roles.
     *
     * @param array $roles Roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * Get the user password hash.
     *
     * @return string|null string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Set the user password hash.
     *
     * @param string $password Password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * Get the salt used for hashing the password (not used in plaintext password).
     *
     * @return string|null string
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * Erase sensitive data from the user.
     */
    public function eraseCredentials(): void
    {
    }

    /**
     * Get the user's avatar.
     *
     * @return Avatar|null Avatar
     */
    public function getAvatar(): ?Avatar
    {
        return $this->avatar;
    }

    /**
     * Set the user's avatar.
     *
     * @param Avatar $avatar Avatar
     *
     * @return $this
     */
    public function setAvatar(Avatar $avatar): static
    {
        if ($avatar->getUser() !== $this) {
            $avatar->setUser($this);
        }

        $this->avatar = $avatar;

        return $this;
    }
}
