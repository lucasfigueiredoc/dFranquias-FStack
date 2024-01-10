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
    private $gadoRepository;
    private $paginator;

    public function __construct()
    {

    }

    public function listar( PaginatorInterface $pg,Request $request,EntityManagerInterface $em,GadoRepository $gadoRepository): Response
    {
        $data['titulo'] = "Listagem dos animais.";

        $query = $gadoRepository->findAll();

        $data['gados'] = $pg->paginate(
            $query,
            $request->query->get('page', 1),
            10
        );

        return $this->$data;

    }

}