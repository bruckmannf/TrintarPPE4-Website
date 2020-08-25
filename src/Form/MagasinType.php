<?php

namespace App\Form;

use App\Entity\Magasin;
use App\Entity\Masque;
use App\Entity\optionMagasin;
use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;


class MagasinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('horaireOuverture')
            ->add('adresse')
            ->add('lng', HiddenType::class)
            ->add('lat', HiddenType::class)
            ->add('codePostal')
            ->add('nom')
            ->add('telephone')
            ->add('courriel')
            ->add('idOptionMagasin', EntityType::class, [
                'required' => false,
                'label' => false,
                'class' => optionMagasin::class,
                'multiple' => true
            ])
            ->add('idMasque', EntityType::class, [
                'required' => false,
                'label' => false,
                'class' => Masque::class,
                'multiple' => true
            ])
            ->add('idProduit', EntityType::class, [
                'required' => false,
                'label' => false,
                'class' => Produit::class,
                'multiple' => true
            ])
            ->add('imageFile', FileType::class, [
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Magasin::class,
        ]);
    }
}
