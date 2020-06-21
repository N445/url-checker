<?php

namespace App\Form;

use App\Entity\Serveur;
use App\Entity\Site;
use App\Utils\SiteProtocoles;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('protocol', ChoiceType::class, [
                'label'   => 'Protocol',
                'choices' => [
                    SiteProtocoles::HTTP  => SiteProtocoles::HTTP,
                    SiteProtocoles::HTTPS => SiteProtocoles::HTTPS,
                ],
            ])
            ->add('domain', TextType::class, [
                'label' => 'Nom de domaine',
            ])
            ->add('serveur', EntityType::class, [
                'class' => Serveur::class,
            ])
            ->add('urls', CollectionType::class, [
                'entry_type'    => UrlType::class,
                'allow_add' => true,
                'prototype' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'entry_options' => [
                    'attr' => ['class' => 'url-box'],
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Site::class,
        ]);
    }
}
