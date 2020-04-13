<?php

namespace App\Form;

use App\Entity\Auteur;
use App\Entity\Categorie;
use App\Entity\Magasin;
use App\Entity\Produit;
use App\Entity\Licence;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle')
            ->add('description')
            ->add('synopsis')
            ->add('prixht')
            ->add('stock')
            ->add('idCategorie', EntityType::class, [
                'required' => false,
                'label' => false,
                'class' => Categorie::class,
                'multiple' => true
            ])
            ->add('idLicence', EntityType::class, [
                'required' => false,
                'label' => false,
                'class' => Licence::class,
                'multiple' => true
            ])
            ->add('idAuteur', EntityType::class, [
                'required' => false,
                'label' => false,
                'class' => Auteur::class,
                'multiple' => true
            ])
            ->add('idMagasin', EntityType::class, [
                'required' => false,
                'label' => false,
                'class' => Magasin::class,
                'multiple' => true
            ])
            ->add('imageFile', FileType::class, [
                'required' => false
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
