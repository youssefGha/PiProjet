<?php

namespace entrepotBundle\Controller;


use entrepotBundle\Data\SearchData;
use entrepotBundle\Entity\Categorie;
use entrepotBundle\Entity\Commandeachat;
use entrepotBundle\Entity\Fournisseur;
use entrepotBundle\Entity\Produit;
use entrepotBundle\Entity\Responsable;
use entrepotBundle\Form\ProduitType;
use entrepotBundle\Form\SearchForm;
use entrepotBundle\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class ProduitController extends Controller
{
    public function indexAction(Request $request)
    {
        $del_val=[];
        $data=new SearchData();
        $form=$this->createForm(SearchForm::class,$data);
        $form->handleRequest($request);
        $produits=$this->getDoctrine()->getRepository(Produit::class)->findAll();
        if ( (!empty($data->categories)) AND (!empty($data->max)) And (!empty($data->min))){
            $produits=$this->getDoctrine()->getRepository(Produit::class)->findBy(['categorie'=>$data->categories[0]->getId()]);

            for ($i=0;$i<sizeof($produits);$i++){
                if (($produits[$i]->getPrixvente()>$data->max)){
                    unset($produits[$i]);
                }
            }
            foreach ($produits as $produit){
                if (($produit->getPrixvente()<$data->min)){
                    $key=array_search($produit,$produits);
                    unset($produits[$key]);
                }
            }
        }
        elseif ((!empty($data->categories)) AND (!empty($data->max))){
            $produits=$this->getDoctrine()->getRepository(Produit::class)->findBy(['categorie'=>$data->categories[0]->getId()]);

            for ($i=0;$i<sizeof($produits);$i++){
                if (($produits[$i]->getPrixvente()>$data->max)){
                    unset($produits[$i]);
                }
            }
        }
        elseif ((!empty($data->categories)) AND (!empty($data->min))){
            $produits=$this->getDoctrine()->getRepository(Produit::class)->findBy(['categorie'=>$data->categories[0]->getId()]);

            foreach ($produits as $produit){
                if (($produit->getPrixvente()<$data->min)){
                    $key=array_search($produit,$produits);
                    unset($produits[$key]);
                }
            }
        }
        elseif ((!empty($data->max)) AND (!empty($data->min))){
            for ($i=0;$i<sizeof($produits);$i++){
                if (($produits[$i]->getPrixvente()>$data->max)){
                    unset($produits[$i]);
                }
            }
            foreach ($produits as $produit){
                if (($produit->getPrixvente()<$data->min)){
                    $key=array_search($produit,$produits);
                    unset($produits[$key]);
                }
            }
        }




        return $this->render('@entrepot/Default/Produits/produits.html.twig',array('produits'=>$produits,'form'=>$form->createView()));
    }


    public function indexfournisseurAction($id,Request $request)
    {
        $session=$request->getSession();
        $session->set('idfournisseur',$id);
        $fournisseur=$this->getDoctrine()->getRepository(Fournisseur::class)->find($id);
        $produits=new Produit();
        $produits=$fournisseur->getProduits();
        $idf=$session->get('idfournisseur');
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $fournisseur->getProduits(),
            $request->query->getInt('page', 1), /*page number*/
            $request->query->getInt('limit', 8)
        );
        return $this->render('@entrepot/Default/Produits/produits_fournisseur.html.twig',array('produits'=>$produits,'idfournisseur'=>$idf,'pagination'=>$pagination));
    }
    public function ajoutPAction(Request $request,$id)
    {
        $produit=$this->getDoctrine()->getRepository(Produit::class)->find($id);
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $idu=$user->getId();
        $fournisseur=$this->getDoctrine()->getRepository(Fournisseur::class)->findOneBy(['iduser'=>$idu]);
        $fournisseur->getProduits()->add($produit);
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $idu=$user->getId();
        $fournisseur=$this->getDoctrine()->getRepository(Fournisseur::class)->findOneBy(['iduser'=>$idu]);
        $produits=$fournisseur->getProduits();
        $allproduits=$this->getDoctrine()->getRepository(Produit::class)->findAll();
        $notproduit=[];
        $j=0;
        foreach ($allproduits as $p){
            $trouve=0;
            foreach ($produits as $p1){
                if ($p1->getId()==$p->getId()){
                    $trouve=1;
                }
            }
            if ($trouve==0){
                $notproduit[$j]=$p;
            }
            $j++;
        }
        $nbproduit=0;
        foreach ($fournisseur->getProduits() as $produit ){
            $nbproduit++;
        }
        $totalvente=0;
        $commandeachats=$this->getDoctrine()->getRepository(Commandeachat::class)->findAll();
        foreach ($commandeachats as $commandeachat)
        {
            if ($commandeachat->getIdfournisseur()==$fournisseur)
            {
                $totalvente+=$commandeachat->getTotal();
            }
        }
        return $this->render('@entrepot/Default/Homes/fournisseur_home.html.twig',['produits'=>$produits,'notproduits'=>$notproduit,'fournisseur'=>$fournisseur,'nbproduit'=>$nbproduit,'totalvente'=>$totalvente]);
    }

    public function affichePAction(Request $request)
    {

        //$user = $this->container->get('security.token_storage')->getToken()->getUser();
        //$id=$user->getId();
        $fournisseur = $this->getDoctrine()->getRepository(Fournisseur::class)->find(10);
        $produits = new Produit();
        $produits = $fournisseur->getProduits();
        /*if ($request->isMethod("POST")) {

            $nom = $request->get("nom");
            $Produit = $em->getRepository("entrepotBundle:Produit")->findBy(array("nom" => $nom));

        }*/


        //   var_dump($candidat);
        return $this->render("@entrepot/Default/Produits/viewfile.html.twig", array("Produit" => $produits));
    }

    public function supprimerPAction($id)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $idu=$user->getId();
        $fournisseur=$this->getDoctrine()->getRepository(Fournisseur::class)->findOneBy(['iduser'=>$idu]);
        $produit=$this->getDoctrine()->getRepository(Produit::class)->find($id);
        $fournisseur->getProduits()->remove($id);
        $produits = $fournisseur->getProduits();
        $em = $this
            ->getDoctrine()
            ->getManager();
        $em->persist($fournisseur);
        $em->flush();
        $allproduits=$this->getDoctrine()->getRepository(Produit::class)->findAll();
        $notproduit=[];
        $j=0;
        foreach ($allproduits as $p){
            $trouve=0;
            foreach ($produits as $p1){
                if ($p1->getId()==$p->getId()){
                    $trouve=1;
                }
            }
            if ($trouve==0){
                $notproduit[$j]=$p;
            }
            $j++;
        }
        $nbproduit=0;
        foreach ($fournisseur->getProduits() as $produit ){
            $nbproduit++;
        }

        $totalvente=0;
        $commandeachats=$this->getDoctrine()->getRepository(Commandeachat::class)->findAll();
        foreach ($commandeachats as $commandeachat)
        {
            if ($commandeachat->getIdfournisseur()==$fournisseur)
            {
                $totalvente+=$commandeachat->getTotal();
            }
        }
        return $this->render('@entrepot/Default/Homes/fournisseur_home.html.twig',['produits'=>$produits,'notproduits'=>$notproduit,'fournisseur'=>$fournisseur,'nbproduit'=>$nbproduit,'totalvente'=>$totalvente]);

    }

    public function updatePAction(Request $request, $id)
    {
        $Produit = $this->getDoctrine()->getRepository(Produit::class)->find($id);
        $form = $this->createForm(ProduitType::class, $Produit);
        $form = $form->handleRequest($request);
        $Produit = $this->getDoctrine()->getRepository(Produit::class)->find($id);
        $form = $this->createForm(ProduitType::class, $Produit);
        $form = $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($Produit);
            $em->flush();
            return $this->redirectToRoute("Produit_affiche");
        }
        return $this->render('@entrepot/Default/Produits/updatep.html.twig', array('form' => $form->createView()));

    }


    public function indexproduitAction()
    {
        $Produit = $this->getDoctrine()->getRepository(Produit::class)->find(5);
        dump($Produit);
        $em = $this->getDoctrine()->getManager();

        $produits = $em->getRepository('entrepotBundle:Produit')->findAll();

        return $this->render('produit/index.html.twig', array(
            'produits' => $produits,
        ));
    }

    public function newAction(Request $request)
    {
        $produit = new Produit();
        $form = $this->createForm('entrepotBundle\Form\ProduitType', $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $produit->uploadProfilePicture($this->getParameter('image_directory'));
            $em->persist($produit);
            $em->flush();
            //$this->addFlash('Succes','Produit ajouté avec succes');

            return $this->redirectToRoute('produit_show', array('id' => $produit->getId()));
        }

        return $this->render('produit/new.html.twig', array(
            'produit' => $produit,
            'form' => $form->createView(),
        ));
    }

    public function showAction(Produit $produit)
    {
        $deleteForm = $this->createDeleteForm($produit);

        return $this->render('produit/show.html.twig', array(
            'produit' => $produit,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function editAction(Request $request, Produit $produit)
    {
        $deleteForm = $this->createDeleteForm($produit);
        $editForm = $this->createForm('entrepotBundle\Form\ProduitType', $produit);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('produit_edit', array('id' => $produit->getId()));
        }

        return $this->render('produit/edit.html.twig', array(
            'produit' => $produit,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }


    public function deleteAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $variable=$em->getRepository(Produit::class)->findOneBy(['id'=>$id]);
        $em->remove($variable);
        $em->flush();
        //$this->addFlash('Succes','Produit supprimé avec succes');

        return $this->redirectToRoute('produit_index');
    }


    /**
     * Creates a form to delete a produit entity.
     *
     * @param Produit $produit The produit entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Produit $produit)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('produit_delete', array('id' => $produit->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $libelle = $request->get('q');
        $rec = $em->getRepository('entrepotBundle:Produit')->SearchCours($libelle);
        if (!$rec) {
            $result['cours']['error'] = "Produit Not found :( ";
        } else {
            $result['cours'] = $this->getRealEntities($rec);
        }
        return new Response(json_encode($result));
    }

    public function getRealEntities($rec)
    {
        foreach ($rec as $rec) {
            $realEntities[$rec->getId()] = [$rec->getlib(),$rec->getPrixVente(),$rec->getPrixAchat(),$rec->getDisponibilite(),$rec->getDescription(),$rec->getNomImage(),$rec->getCategorie()->getLib()];

        }
        return $realEntities;
    }
}
