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
use App\Controller\Exception;
class GadoController extends AbstractController
{

        #### Tela index

    #[Route("/",name: "index")]
    public function index(Request $request, GadoRepository $gadoRepository,PaginatorInterface $pg ):Response{
        
        $data['titulo'] = "Home";
        $query = $gadoRepository->listAnimais(false);
        $data['leiteTotal'] = $gadoRepository->sumValues('leite');
        $data['racaoTotal'] = $gadoRepository->sumValues('racao');
        $data['animaisConsumo1500'] = $gadoRepository->findAnimaisUmQuinhentos();
        $data['listagemASCDESC'] = $query;
        $data['animaisVivos'] = $gadoRepository->countAnimais(1);
        $data['animaisAbatidos']  = $gadoRepository->countAnimais(0);
        $data['animaisMorteOutras']  = $gadoRepository->countAnimais(2);

        return $this->render('/home/index.html.twig',$data);
    }

    ###### Route listagem, responsavel por uma listagem geral dos animais da fazenda
    #[Route("/gado/listagem", name: "listagem_gado")]
    public function listagem(Request $request, GadoRepository $gadoRepository,PaginatorInterface $pg ): Response{
        $data['titulo'] = "Listagem";

        $query = $gadoRepository->findAll();
        
        $data['gados'] = $pg->paginate(
            $query,
            $request->query->get('page',1),
            10
        );

        return $this->render('gado/listagem.html.twig',$data);
    }


    ##$#### Controler para ver animais com dados especificos para o abate
    #[Route("/gado/listagemAbate", name: "listagemAbate_gado")]
    public function listagemParaAbate(Request $request, GadoRepository $gadoRepository,PaginatorInterface $pg ): Response{
    
        $data['titulo'] = "Listagem animais para o abate";
        $data['gadoAbate'] = $gadoRepository->findAnimaisParaAbate();

        return $this->render('gado/listagemabate.html.twig',$data);
    }

    # ##### Controler para renderizar apenas animais já abatidos
    #[Route("/gado/listagemAbatidos", name: "listagemAbatidos_gado")]
    public function listagemAbatidos(Request $request, GadoRepository $gadoRepository,PaginatorInterface $pg ): Response{
    

        $data['titulo'] = "Listagem animais Abatidos";
        $data['animaisAbatidos']  = $gadoRepository->countAnimais(0);
        $query = $gadoRepository->findAnimaisAbatidos();
        $data['titulo'] = "Listagem";
        $data['gados'] = $pg->paginate(
            $query,
            $request->query->get('page',1),
            10
        );

        return $this->render('gado/listagem.html.twig',$data);
    }

     ##### Controller adicionar gado
    #[Route("/gado/adicionar", name: 'adicionar_gado')]
    public function adicionar(Request $request, EntityManagerInterface $em): Response{
        
        $data['titulo'] = "Adicionar animal";
        
        $gado = new Gado();

        $form = $this->createForm(GadoType::class,$gado);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($gado);
            $em->flush();

            ##Flash
            $this->addFlash(
                'commit',
                'Animal adicionado com sucesso!'
            );

        }

        $data['form'] = $form;
        return $this->renderForm('gado/form.html.twig',$data);
    }

    ##### Controller editar gado
   #[Route("/gado/editar/{id}", name:"editar_gado")]
    public function editar($id,Request $request, EntityManagerInterface $em, GadoRepository $gadoRepository): Response {

        $data['titulo'] = "Editar Animal";

        $gado = $gadoRepository->find($id);
        $form = $this->createForm(GadoType::class,$gado);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($gado);
            $em->flush();

            ##Flash
            $this->addFlash(
                'commit',
                'Animal editado com sucesso!'
            );
            
        
            return $this->redirectToRoute("listagem_gado");
        }
        $data['form'] = $form;
        return $this->renderForm("gado/form.html.twig",$data);
        }

        ##### Controller excluir gado
        #[Route('gado/excluir/{id}', name: 'excluir_gado')]
        public function excluir(GadoRepository $gadoRepository, $id, EntityManagerInterface $em, Request $request): Response{
    
            $gado = $gadoRepository->find($id);
            $em->remove($gado);
            $em->flush();

            $this->addFlash(
                'commit',
                'Animal excluido do sistema!'
            );
    
            return $this->redirectToRoute('listagem_gado');
    
        }

        ## Rota para enviar animal para o abate
            #[Route('gado/abater/{id}', name: 'abater_gado')]
        public function abater(GadoRepository $gadoRepository, $id, EntityManagerInterface $em, Request $request): Response{
    
            $gado = $gadoRepository->find($id);
            $gado->setSituacao(0);
            try { 
                $em->persist($gado);
                $em->flush();

                $this->addFlash(
                    'commit',
                    'Animal registrado como abatido!'
                );

            } catch (Exception $e) {
                echo "Exceção capturada: " . $e->getMessage();
            }

            return $this->redirectToRoute('listagem_gado');
    
        }
    
}

