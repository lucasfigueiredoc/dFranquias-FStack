<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;

class GadoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigo', NumberType::class, [
                'invalid_message' => 'Entrada invlálida, apenas números.',
                'attr' => [
                    'placeholder' => 'Código | Apenas vivos',
                ],
                'required' => false,
                'empty_data' => null, // Permitir valor nulo

            ])

            ->add('leite', NumberType::class,[ 
                'invalid_message' => 'Entrada invlálida, apenas números.',
                'attr' => array('placeholder' => 'Produção Leite | Litros'
            )])

            ->add('racao', NumberType::class,[
                'invalid_message' => 'Entrada invlálida, apenas números.', 
                'attr' => array('placeholder' => 'Consumo Ração | Kg'
            )])

            ->add('peso', NumberType::class,[
                'invalid_message' => 'Entrada invlálida, apenas números.',
                'attr' => array('placeholder' => 'Peso do Animal | Kg')
            ])

            ->add('situacao', ChoiceType::class, [
                'placeholder' => 'Situação',
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