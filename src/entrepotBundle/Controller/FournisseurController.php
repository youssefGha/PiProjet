<?php

namespace entrepotBundle\Controller;

use entrepotBundle\Entity\Fournisseur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FournisseurController extends Controller
{
    public function indexAction()
    {
        $fournisseurs=$this->getDoctrine()->getRepository(Fournisseur::class)->findAll();
        return $this->render('@entrepot/Default/fournisseurs.html.twig',array('fournisseurs'=>$fournisseurs));
    }
}
