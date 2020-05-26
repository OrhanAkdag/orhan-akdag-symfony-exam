<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email de l\'utilisateur',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('plainPassword', RepeatedType::class, array(
                'type'              => PasswordType::class,
                'mapped'            => false,
                'first_options'     => array('label' => 'New password'),
                'second_options'    => array('label' => 'Confirm new password'),
                'invalid_message' => 'The password fields must match.',
            ))
            ->add('nom', TextType::class, [
                'label' => 'Nom de l\'utilisateur',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom de l\'utilisateur',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('secteur', ChoiceType::class, [
                'choices'  => [
                    'Recrutement' => 'Recrutement',
                    'Informatique' => 'Informatique',
                    'Comptabilité' => 'Comptabilité',
                ]
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'label' => 'Image de l\'utilisateur',
                'allow_delete' => false,
                'download_label' => '...',
                'download_uri' => false,
                'image_uri' => true,
                'imagine_pattern' => 'edit_thumb',

            ])
            ->add('save', SubmitType::class)   

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
