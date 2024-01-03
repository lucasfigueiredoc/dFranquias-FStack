<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class GadoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('leite', TextType::class)
            ->add('racao', TextType::class)
            ->add('peso', TextType::class)
            ->add('situacao', ChoiceType::class, [
                'label' => 'Ativo',
                'choices' => [
                    'Vivo' => 1,
                    'Abatido' => 0,
                    'Morte, outra razão' => 2
                ],
            ])
            ->add('nascimento',DateType::class,[ 
                'widget' => 'single_text',
            ])
            ->add('Salvar', SubmitType::class);;
    }

}