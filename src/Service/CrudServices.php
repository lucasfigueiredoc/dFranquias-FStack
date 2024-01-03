<?php

namespace App\Service;


use App\Entity\Gado;

use App\Form\Type\GadoType;
use App\Repository\GadoRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Controller\Exception;

class CrudService
{
    private $entityManager;

    public function __construct( EntityManagerInterface $em)
    {
        $this->entityManager = $em;
    }

    public function adicionar(Request $request,EntityManagerInterface $em): Response
    {
                


        if($form->isSubmitted() && $form->isValid()){
            $em->persist($gado);
            $em->flush();
        }


    }

}