<?php

namespace App\Service;


use App\Entity\Gado;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\Type\GadoType;

class CrudService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function executarDB(EntityManagerInterface $em): void
    {


        if($form->isSubmitted() && $form->isValid()){
            $em->persist($gado);
            $em->flush();
        }
        $data['form'] = $form;
    }

}