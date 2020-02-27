<?php


namespace entrepotBundle\Entity;
Use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Categorie
 *
 * @ORM\Table(name="categorie")
 * @ORM\Entity(repositoryClass="entrepotBundle\Repository\CategorieRepository")
 */
class Categorie
{
    /**
     * @var int
     * @ORM\Column(name="Id",type="integer")
     *@ORM\Id
     *@ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     */
    private $lib;
    /**
     * @ORM\Column(type="string")
     */
    private $description;
    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $nomImage;

    /**
     * @Assert\File(maxSize="1000k")
     */
    private $file;

    /**
     * @return string
     */
    public function getNomImage()
    {
        return $this->nomImage;
    }

    /**
     * @param string $nomImage
     */
    public function setNomImage($nomImage)
    {
        $this->nomImage = $nomImage;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getLib()
    {
        return $this->lib;
    }

    /**
     * @param mixed $lib
     */
    public function setLib($lib)
    {
        $this->lib = $lib;
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
    public function __toString()
    {
       return $this->description;
    }
    public function getWebPath(){
        return null==$this->nomImage ? null :$this->getUploadDir.'/'.$this->nomImage;
    }
    protected  function getUploadRootDir(){
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }
    protected function getUploadDir(){
        return 'images';
    }
    public function uploadProfilePicture(){
        $this->file->move($this->getUploadRootDir(),$this->file->getClientOriginalName());
        $this->nomImage=$this->file->getClientOriginalName();
        $this->file=null;
    }
}