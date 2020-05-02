<?php

namespace App\Form;

use App\Entity\Commande;
use App\Entity\infoCommande;
use App\Entity\Magasin;
use App\Entity\Produit;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateCde')
            ->add('facturePdf')
            ->add('dateLivraison')
            ->add('prixTotal')
            ->add('quantiteTotale')
            ->add('idMagasin', EntityType::class, [
                'required' => false,
                'label' => false,
                'class' => Magasin::class,
                'multiple' => false
            ])
            ->add('idProduit', EntityType::class, [
                'required' => false,
                'label' => false,
                'class' => Produit::class,
                'multiple' => true
            ])
            ->add('idUtilisateur', EntityType::class, [
                'required' => false,
                'label' => false,
                'class' => Utilisateur::class,
                'multiple' => false
            ])
            ->add('idInfoCommande', EntityType::class, [
                'required' => false,
                'label' => false,
                'class' => infoCommande::class,
                'multiple' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
