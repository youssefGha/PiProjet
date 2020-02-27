<?php

namespace entrepotBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('lib')->add('prixVente')
            ->add('prixAchat')
            ->add('disponibilite', ChoiceType::class,[
                    'choices' => [
                'yes' => 'Math',
                'no'   => 'tech',
            ]
            ] )

            ->add('description')
            ->add('file')
            ->add('categorie',EntityType::class,array(
                'class'=>'entrepotBundle\Entity\Categorie',
                'choice_label'=>'lib',
                 "multiple"=>false
    ));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'entrepotBundle\Entity\Produit'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'entrepotBundle_produit';
    }


}
