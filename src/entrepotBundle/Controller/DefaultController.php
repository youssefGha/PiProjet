<?php

namespace entrepotBundle\Controller;

use entrepotBundle\Entity\Fournisseur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $authChecker=$this->container->get('security.authorization_checker');
        if ($authChecker->isGranted('ROLE_ADMIN')){
            $fournisseurs=$this->getDoctrine()->getRepository(Fournisseur::class)->findAll();
            return $this->render('@entrepot/Default/fournisseurs.html.twig',array('fournisseurs'=>$fournisseurs));
    }
        elseif ($authChecker->isGranted('ROLE_FOURNISSEUR')){
            return $this->render('@entrepot/Default/Homes/fournisseur_home.html.twig');
        }
        elseif ($authChecker->isGranted('ROLE_LIVREUR')){
            return $this->render('@entrepot/Default/Homes/Livreur_home.html.twig');
        }
        else {
            return $this->render('@entrepot/Default/index.html.twig');
        }

    }
    public function offreAction()
    {

        return $this->render('@entrepot/Default/offre.html.twig');
    }
}
