<?php

namespace App\Entity;



use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 * 
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email(
     *     message = "merci de fournir une adresse email valide."
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    
    /**
     * Cette props sert a la mise en place d'un select option pour le choix de role au niveau du formulaire de creation ou de modification d'un user
     */
    public $role;

    /**
     * @Assert\Length(
     *      min = 6,
     *      max = 50,
     *      minMessage = "Votre mot de passe doit contenir 6 caractéres au minimum",
     *      maxMessage = "Votre mot de passe ne doit pas depasser 50 caractéres"
     * )
     * @Assert\IdenticalTo(propertyPath="confirmPassword",
     * message="Le mot de passe est différent ")
     */
    private $plainPassword;

    /**
     * @Assert\Length(
     *      min = 6,
     *      max = 50,
     *      minMessage = "Votre mot de passe doit contenir 6 caractéres au minimum",
     *      maxMessage = "Votre mot de passe ne doit pas depasser 50 caractéres"
     * )
     * @Assert\IdenticalTo(propertyPath="plainPassword",
     * message="Le mot de passe est différent ")
     * 
     */   
    private $confirmPassword;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pseudo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $country;

    /**
     * @ORM\OneToMany(targetEntity=Photo::class, mappedBy="users")
     */
    private $photoList;


    /**
     * @ORM\OneToOne(targetEntity=Avatar::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $avatar;

    /**
     * @ORM\OneToMany(targetEntity=Video::class, mappedBy="users")
     */
    private $videoList;

    /**
     * @ORM\OneToMany(targetEntity=Planning::class, mappedBy="user")
     */
    private $plannings;

    /**
     * @ORM\OneToMany(targetEntity=PostLike::class, mappedBy="user")
     */
    private $likes;

    public function __construct()
    {
        $this->photoList = new ArrayCollection();
        $this->videoList = new ArrayCollection();
        $this->plannings = new ArrayCollection();
        $this->likes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        //  garantis que chaque utilisateur aura un ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * Get cette props sert a la mise en place d'un select option pour le choix de role au niveau du formulaire de creation ou de modification d'un user
     */ 
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set cette props sert a la mise en place d'un select option pour le chois de role au niveau du formulaire de creation ou de modification d'un user
     *
     * @return  self
     */ 
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(?string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get min = 6,
     */ 
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * Set min = 6,
     *
     * @return  self
     */ 
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * Get the value of confirmPassword
     */ 
    public function getConfirmPassword()
    {
        return $this->confirmPassword;
    }

    /**
     * Set the value of confirmPassword
     *
     * @return  self
     */ 
    public function setConfirmPassword($confirmPassword)
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }

    /**
     * @return Collection|Photo[]
     */
    public function getPhotoList(): Collection
    {
        return $this->photoList;
    }

    public function addPhotoList(Photo $photoList): self
    {
        if (!$this->photoList->contains($photoList)) {
            $this->photoList[] = $photoList;
            $photoList->setUsers($this);
        }

        return $this;
    }

    public function removePhotoList(Photo $photoList): self
    {
        if ($this->photoList->removeElement($photoList)) {
            // set the owning side to null (unless already changed)
            if ($photoList->getUsers() === $this) {
                $photoList->setUsers(null);
            }
        }

        return $this;
    }


    public function getAvatar(): ?Avatar
    {
        return $this->avatar;
    }

    public function setAvatar(?Avatar $avatar): self
    {
        // unset the owning side of the relation if necessary
        if ($avatar === null && $this->avatar !== null) {
            $this->avatar->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($avatar !== null && $avatar->getUser() !== $this) {
            $avatar->setUser($this);
        }

        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return Collection|Video[]
     */
    public function getVideoList(): Collection
    {
        return $this->videoList;
    }

    public function addVideoList(Video $videoList): self
    {
        if (!$this->videoList->contains($videoList)) {
            $this->videoList[] = $videoList;
            $videoList->setUsers($this);
        }

        return $this;
    }

    public function removeVideoList(Video $videoList): self
    {
        if ($this->videoList->removeElement($videoList)) {
            // set the owning side to null (unless already changed)
            if ($videoList->getUsers() === $this) {
                $videoList->setUsers(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Planning[]
     */
    public function getPlannings(): Collection
    {
        return $this->plannings;
    }

    public function addPlanning(Planning $planning): self
    {
        if (!$this->plannings->contains($planning)) {
            $this->plannings[] = $planning;
            $planning->setUser($this);
        }

        return $this;
    }

    public function removePlanning(Planning $planning): self
    {
        if ($this->plannings->removeElement($planning)) {
            // set the owning side to null (unless already changed)
            if ($planning->getUser() === $this) {
                $planning->setUser(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return (string)$this->getName();
    }

    /**
     * @return Collection|PostLike[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(PostLike $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setUser($this);
        }

        return $this;
    }

    public function removeLike(PostLike $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getUser() === $this) {
                $like->setUser(null);
            }
        }

        return $this;
    }
    

}
