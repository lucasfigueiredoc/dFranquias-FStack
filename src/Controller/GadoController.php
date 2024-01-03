<?php

namespace App\Controller;

use App\Repository\GadoRepository;
use App\Entity\Gado;
use App\Form\Type\GadoType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

class GadoController extends AbstractController
{
    ## Route listagem, responsavel por uma listagem geral dos animais da fazenda
    #[Route("/gado/listagem", name: "listagem_gado")]
    public function listagem(Request $request, GadoRepository $gadoRepository,PaginatorInterface $pg ): Response{
        
        $query = $gadoRepository->findAll();
        $data['titulo'] = "Listagem";
        $data['gados'] = $pg->paginate(
            $query,
            $request->query->get('page',1),
            5
        );

        return $this->render('gado/listagem.html.twig',$data);
    }

    #[Route("/gado/adicionar", name: 'adicionar_gado')]
    public function adicionar(Request $request, EntityManagerInterface $em): Response{
        
        $data['titulo'] = "Adicionar animal";
        
        $gado = new Gado();

        $form = $this->createForm(GadoType::class,$gado);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($gado);
            $em->flush();
        }
        $data['form'] = $form;
        return $this->renderForm('gado/form.html.twig',$data);
    }

    #[Route("/gado/editar/{id}", name:"editar_gado")]
    public function editar($id,Request $request, EntityManagerInterface $em, GadoRepository $gadoRepository): Response {

        $data['titulo'] = "Editar Animal";

        $gado = $gadoRepository->find($id);
        $form = $this->createForm(GadoType::class,$gado);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($gado);
            $em->flush();
        
            return $this->redirectToRoute("listagem_gado");
        }
        $data['form'] = $form;
        return $this->renderForm("gado/form.html.twig",$data);
        }

        #[Route('gado/excluir/{id}', name: 'excluir_gado')]
        public function excluir(GadoRepository $gadoRepository, $id, EntityManagerInterface $em, Request $request): Response{
    
            $gado = $gadoRepository->find($id);
            $em->remove($gado);
            $em->flush();
    
            return $this->redirectToRoute('listagem_gado');
    
        }
    
}

