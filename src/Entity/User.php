<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"pseudo"}, message="Un utilisateur existe déjà avec ce pseudo")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20, unique=true)
     * @Assert\NotBlank(message="Le pseudo est obligatoire")
     * @Assert\Length(
     *     min="8", minMessage="Le pseudo doit au minimum contenir {{ limit }} caractères.",
     *     max="20", maxMessage="Le pseudo doit au maximum contenir {{ limit }} caractères."
     * )
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z0-9\-]+$/",
     *     message="Le pseudo ne peut contenir uniquement des caractères alphabéthétique et numérique ainsi que le tiret -."
     * )
     */
    private $pseudo;

    /**
     * @ORM\Column(type="json")
     */
    // private $roles = [];

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $role = 'ROLE_USER';

    /**
     * @var array
     */
    private $rolesAccepted = ['ROLE_ADMIN', 'ROLE_USER'];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * Mot de passe en clair pour interagir avec le formulaire d'inscription
     *
     * @var string
     * @Assert\NotBlank(message="Le mot de passe est obligatoire", groups={"registration"})
     * @Assert\Length(min="8", minMessage="Le mot de passe doit faire au moins {{ limit }} caractères", groups={"registration"})
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateRegistration;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastLogin;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastLogOut;


    public function __construct()
    {
        $this->setDateRegistration(new \DateTime());
    }

    /**
     * @return string
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     * @return User
     */
    public function setPlainPassword(string $plainPassword): User
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->pseudo;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        // $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        // $roles[] = 'ROLE_USER';

        // return array_unique($roles);
        return [$this->role];
    }

    /*public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
    */

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): User
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @return array
     */
    public function getRolesAccepted(): array
    {
        return $this->rolesAccepted;
    }


    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getDateRegistration(): ?\DateTimeInterface
    {
        return $this->dateRegistration;
    }

    public function setDateRegistration(\DateTimeInterface $dateRegistration): self
    {
        $this->dateRegistration = $dateRegistration;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * @param mixed $lastLogin
     * @return User
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastLogOut()
    {
        return $this->lastLogOut;
    }

    /**
     * @param mixed $lastLogOut
     * @return User
     */
    public function setLastLogOut($lastLogOut)
    {
        $this->lastLogOut = $lastLogOut;
        return $this;
    }

    /**
     * @return mixed
     */
    public function connectionTime()
    {
        $lastLogin = $this->getLastLogin();
        $lastlogout = $this->getLastLogOut();

        if ( $lastLogin && $lastlogout )
        {
            $dif = $lastLogin->diff( $lastlogout );

            if ($lastLogin > $lastlogout)
                // lastLogout antérieur à lasLogin => utilisateur connecté mais pas déconnecté
            {
                return 'connecté(e)';
            } else
                // utilisateur déconnecté
            {
                $result = $dif->format('%i') <=1 ? 1 : $dif->format('%i');
                return $result . ' minute(s)';
            }
        } else
            // utilisateur uniquement inscrit mais jamais connecté,
            // ou vient de se connecté pour la première fois mais pas encore déconnecté
        {
            return "-";
        }
    }

}
