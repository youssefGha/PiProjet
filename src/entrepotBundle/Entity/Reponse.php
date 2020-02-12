<?php


namespace entrepotBundle\Entity;

Use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 *
 */
class Reponse
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
    private $contenu;
    /**
     * @ORM\Column(type="string")
     */
    private $type;
    /**
     * @ORM\ManyToOne(targetEntity="Reclamation")
     * @ORM\JoinColumn(name="idreclamation",referencedColumnName="Id")
     */
    private  $idreclamation;

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
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * @param mixed $contenu
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getIdreclamation()
    {
        return $this->idreclamation;
    }

    /**
     * @param mixed $idreclamation
     */
    public function setIdreclamation($idreclamation)
    {
        $this->idreclamation = $idreclamation;
    }

}