<?php


namespace entrepotBundle\Entity;
Use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 *
 */
class Reclamation
{
    /**
     * @var int
     * @ORM\Column(name="Id",type="integer")
     *@ORM\Id
     *@ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="date")
     */
    private $date;
    /**
     * @ORM\Column(type="string")
     */
    private $motif;
    /**
     * @ORM\Column(type="string")
     */
    private $contenu;
    /**
     * @ORM\Column(type="string")
     */
    private $etat;
    /**
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\JoinColumn(name="idclient",referencedColumnName="Id")
     */
    private  $idclient;
    /**
     * @ORM\ManyToOne(targetEntity="Responsable")
     * @ORM\JoinColumn(name="idresponsable",referencedColumnName="Id")
     */
    private  $idresponsable;

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
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getMotif()
    {
        return $this->motif;
    }

    /**
     * @param mixed $motif
     */
    public function setMotif($motif)
    {
        $this->motif = $motif;
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
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * @param mixed $etat
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;
    }

    /**
     * @return mixed
     */
    public function getIdclient()
    {
        return $this->idclient;
    }

    /**
     * @param mixed $idclient
     */
    public function setIdclient($idclient)
    {
        $this->idclient = $idclient;
    }

    /**
     * @return mixed
     */
    public function getIdresponsable()
    {
        return $this->idresponsable;
    }

    /**
     * @param mixed $idresponsable
     */
    public function setIdresponsable($idresponsable)
    {
        $this->idresponsable = $idresponsable;
    }
}