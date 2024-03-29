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
use App\Service\CrudService;

class GadoController extends AbstractController
{

    #### Tela index

    #[Route("/", name: "index")]
    public function index(Request $request, GadoRepository $gadoRepository, PaginatorInterface $pg): Response
    {

        $data['titulo'] = "Home";
        $query = $gadoRepository->listAnimais(false);
        $data['leiteTotal'] = $gadoRepository->sumValues('leite');
        $data['racaoTotal'] = $gadoRepository->sumValues('racao');
        $data['animaisConsumo1500'] = $gadoRepository->findAnimaisUmQuinhentos();
        $data['listagemASCDESC'] = $query;
        $data['animaisVivos'] = $gadoRepository->countAnimais(1);
        $data['animaisAbatidos']  = $gadoRepository->countAnimais(0);
        $data['animaisMorteOutras']  = $gadoRepository->countAnimais(2);

        return $this->render('/home/index.html.twig', $data);
    }

    ###### Route listagem, responsavel por uma listagem geral dos animais da fazenda
    #[Route("/gado/listagem", name: "listagem_gado")]
    public function listagem(Request $request, GadoRepository $gadoRepository, PaginatorInterface $pg): Response
    {
        $data['titulo'] = "Listagem dos animais.";

        $query = $gadoRepository->findAll(); ##Encontrar todos os animais listados
        ## Gerar paginador baseando na quantidade total de animais, colocando 10 por página
        $data['gados'] = $pg->paginate(
            $query,
            $request->query->get('page', 1),
            10
        );

        return $this->render('gado/listagem.html.twig', $data);
    }


    ##$#### Controler para ver animais com dados especificos para o abate
    #[Route("/gado/listagemAbate", name: "listagemAbate_gado")]
    public function listagemParaAbate(Request $request, GadoRepository $gadoRepository, PaginatorInterface $pg): Response
    {

        $data['titulo'] = "Listagem animais para o abate.";

        $query = $gadoRepository->findAnimaisParaAbate(); 
        $data['gadoAbate'] = $pg->paginate(
            $query,
            $request->query->get('page',1),
            10
        );

        return $this->render('gado/listagemabate.html.twig', $data);
    }

    # ##### Controler para renderizar apenas animais já abatidos
    #[Route("/gado/listagemAbatidos", name: "listagemAbatidos_gado")]
    public function listagemAbatidos(Request $request, GadoRepository $gadoRepository, PaginatorInterface $pg): Response
    {


        $data['titulo'] = "Listagem animais abatidos";
        
        $query = $gadoRepository->findAnimaisAbatidos();
        $data['titulo'] = "Listagem";
        $data['gados'] = $pg->paginate(
            $query,
            $request->query->get('page', 1),
            10
        );

        return $this->render('gado/listagem.html.twig', $data);
    }

    ##### Controller adicionar gado
    #[Route("/gado/adicionar", name: 'adicionar_gado')]
    public function adicionar(Request $request, EntityManagerInterface $em): Response
    {

        $data['titulo'] = "Adicionar animal";

        $gado = new Gado();

        $form = $this->createForm(GadoType::class, $gado);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())## Verifica se as condiçoes são verdadeiras, se o form tentou ser enviado e se é válido
        {

            $codigo = $gado->getCodigo(); ##captura o código digitado no formulário
            ## Esta estrutura verifica se a leitura do form é nula, caso seja ela adiciona mesmo assim, caso não, ela vai para verificação de numeros repetidos no banco                                                    
            if($codigo == null){
                $em->persist($gado);
                $em->flush();

                 $this->addFlash('commit', 'Animal adicionado ao sistema!');
                return $this->redirectToRoute('adicionar_gado');
            }
            elseif($codigo !== null){
                $validacao  = $em->getRepository(Gado::class)->findOneBy([
                    'codigo' =>$codigo,
                    'situacao' => 1
                ]); ##Execulta busca no banco se existe já o mesmo valor registrado no banco.
                
                if($validacao){
                    $this->addFlash('commit', 'Codigo já existente no banco.');##Caso sim, não é comitado e exibe mensagem
                }else{
                    #caso valor não seja encontrado, persiste e carrega no banco.
                    $em->persist($gado);
                    $em->flush();

                $this->addFlash('commit', 'Animal adicionado ao sistema!');
                return $this->redirectToRoute('adicionar_gado');
                }
            }
        }

        $data['form'] = $form;
        return $this->renderForm('gado/form.html.twig', $data);
    }

    ##### Controller editar gado
    #[Route("/gado/editar/{id}", name: "editar_gado")]
    public function editar($id, Request $request, EntityManagerInterface $em, GadoRepository $gadoRepository): Response
    {

        $data['titulo'] = "Editar Animal";
        $data['idVariavel'] = $id; ##captura id do animal presente para o front
        $gado = $gadoRepository->find($id); ## busca o animal relativo ao id para carregar no db
        $codigo = $gado->getCodigo(); ##Captura id do animal em especifico
        $form = $this->createForm(GadoType::class, $gado); ## Gera  formulario com os dados do respectivo animal
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()) ##validação e submitt
        {
            $codigo = $gado->getCodigo(); ##captura o código digitado no formulário
            ## Esta estrutura verifica se a leitura do form é nula, caso seja ela adiciona mesmo assim, caso não, ela vai para verificação de numeros repetidos no banco                                                    
            if($codigo == null){
                $em->persist($gado);
                $em->flush();

                 $this->addFlash('commit', 'Animal editado no sistema!');
                return $this->redirectToRoute('adicionar_gado');
            }
            elseif($codigo !== null){
                $validacao  = $em->getRepository(Gado::class)->findOneBy([
                    'codigo' =>$codigo,
                    'situacao' => 1
                ]); ##Execulta busca no banco se existe já o mesmo valor registrado no banco.
                
                if($validacao){
                    $this->addFlash('commit', 'Codigo já existente no banco.');##Caso sim, não é comitado e exibe mensagem
                }else{
                    #caso valor não seja encontrado, persiste e carrega no banco.
                    $em->persist($gado);
                    $em->flush();

                $this->addFlash('commit', 'Animal adicionado ao sistema!');
                return $this->redirectToRoute('adicionar_gado');
                }
            }
            


        }
        $data['form'] = $form;
        return $this->renderForm("gado/formEditar.html.twig", $data);
    }
    ##### Controller excluir gado
    #[Route('gado/excluir/{id}', name: 'excluir_gado')]
    public function excluir(GadoRepository $gadoRepository, $id, EntityManagerInterface $em, Request $request): Response
    {

        $gado = $gadoRepository->find($id);
        $gado->setCodigo(NULL); #### Seta o código do a
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
    public function abater(GadoRepository $gadoRepository, $id, EntityManagerInterface $em, Request $request): Response
    {

        $gado = $gadoRepository->find($id);
        $gado->setSituacao(0);
        $gado->setCodigo(NULL); #### Seta o codigo como nulo para poder ser reultilizado em um animal válido

        $em->persist($gado);
        $em->flush();

        $this->addFlash(
            'commit',
            'Animal registrado como abatido!'
        );

        return $this->redirectToRoute('listagemAbate_gado');
    }

    ##Rota responsavel por plotar os gráficos, aqui são pegos os resultados das querys e armazenadas em var, enviadas a view
    #[Route("/graficos", name: "graficos")]
    public function graficos(GadoRepository $gadoRepository,): Response
    {

        $data['leiteTotal'] = $gadoRepository->sumValues('leite');
        $data['racaoTotal'] = $gadoRepository->sumValues('racao');
        $data['animaisConsumo1500'] = $gadoRepository->findAnimaisUmQuinhentos();

        $data['animaisVivos'] = $gadoRepository->countAnimais(1);
        $data['animaisAbatidos']  = $gadoRepository->countAnimais(0);
        $data['animaisMorteOutras']  = $gadoRepository->countAnimais(2);


        $data['titulo'] = "Gráficos";
        return $this->render('graficos/graficos.html.twig', $data);
    }


}
