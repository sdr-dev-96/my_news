<?php

namespace App\Form;


use App\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('objet', TextType::class, [
                'label'     =>  'Objet',
                'attr'      =>  array(
                    'class' =>  'form-control'
                )
            ])
            ->add('nom', TextType::class, [
                'label'     =>  'Votre nom',
                'attr'      =>  array(
                    'class' =>  'form-control'
                )
            ])
            ->add('email', EmailType::class, [
                'label'     =>  'Votre adresse mail',
                'attr'      =>  array(
                    'class' =>  'form-control'
                )
            ])
            ->add('contenu', TextareaType::class, [
                'label'     =>  'Votre message',
                'attr'      =>  array(
                    'class' =>  'form-control'
                )
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
