<?php


namespace entrepotBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
Use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity
 *
 */
class Fournisseur
{
    /**
     * @var int
     * @ORM\Column(name="Id",type="integer")
     *@ORM\Id
     *@ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string")
     */
    private $description;
    /**
     * @ORM\Column(type="string")
     */
    private $image;

    /**
    * @ORM\Column(type="string", nullable=true)
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "min 3 characteres",
     *      maxMessage = "max 10 characteres"
     * )
     */
    private $nom;
    /**
     * @ORM\Column(type="string")
     *
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "min 3 characteres",
     *      maxMessage = "max 10 characteres"
     * )
     */
    private $prenom;
    /**
     * @ORM\Column(type="string")
     * @Assert\Length(
     *      min = 8,
     *      max = 8,
     *      minMessage = "min 8 chiffres",
     *      maxMessage = "max 8 chiffres"
     * )
     *
     */
    private $numerotel;
    /**
     * @ORM\Column(type="string")
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "min 3 characteres",
     *      maxMessage = "max 10 characteres"
     * )
     */
    private $matriculefiscale;
    /**
     * @ORM\Column(type="string")
     */
    private $email;
    /**
     * @ORM\ManyToMany(targetEntity="Produit", mappedBy="fournisseurs",inversedBy="produits")
     */
    private $produits;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="iduser",referencedColumnName="id")
     */
    private  $iduser;
    /**
     * @return mixed
     */
    public function getNumerotel()
    {
        return $this->numerotel;
    }

    /**
     * @param mixed $numerotel
     */
    public function setNumerotel($numerotel)
    {
        $this->numerotel = $numerotel;
    }

    /**
     * @return mixed
     */
    public function getMatriculefiscale()
    {
        return $this->matriculefiscale;
    }

    /**
     * @param mixed $matriculefiscale
     */
    public function setMatriculefiscale($matriculefiscale)
    {
        $this->matriculefiscale = $matriculefiscale;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }


    /**
     * @return mixed
     */
    public function getIduser()
    {
        return $this->iduser;
    }

    /**
     * @param mixed $iduser
     */
    public function setIduser($iduser)
    {
        $this->iduser = $iduser;
    }

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getCin()
    {
        return $this->cin;
    }

    /**
     * @param mixed $cin
     */
    public function setCin($cin)
    {
        $this->cin = $cin;
    }

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return mixed
     */
    public function getMdp()
    {
        return $this->mdp;
    }

    /**
     * @param mixed $mdp
     */
    public function setMdp($mdp)
    {
        $this->mdp = $mdp;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param mixed $prenom
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return ArrayCollection
     */
    public function getProduits()
    {
        return $this->produits;
    }

    /**
     * @param mixed $produits
     */
    public function setProduits($produits)
    {
        $this->produits = $produits;
    }


}