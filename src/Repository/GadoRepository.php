<?php

namespace App\Repository;

use App\Entity\Gado;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Gado>
 *
 * @method Gado|null find($id, $lockMode = null, $lockVersion = null)
 * @method Gado|null findOneBy(array $criteria, array $orderBy = null)
 * @method Gado[]    findAll()
 * @method Gado[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GadoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gado::class);
    }


    public function findAnimaisParaAbate()
    ## Escolha deste tipo de formataçao do Builder por ser facilmente modificavel, podendo ultilizar até variaveis externas da funçao chamada.
    {
        $queryBuilder = $this->createQueryBuilder('e');
        $consumoRacao = $queryBuilder->expr()->quot('e.racao', 7); ## Dividindo o valor produzido pelos dias da semana
        $pesoArrobaBR = $queryBuilder->expr()->prod('e.peso', 15); ## Separar o peso do animal pelo arroba BR, que é 15

        #Query para produçao de leite menor que 40
        $queryBuilder
            ->andWhere('e.leite <= :producaoleite')
            ->setParameter('producaoleite', 40);

        #Query para consumo maior que 50 quilos de raçao diario e produza menos que 70 litros semanal
        $queryBuilder
            ->andWhere('e.racao > :consumoracao AND e.leite > :producaoleite')
            ->setParameter('consumoracao', $consumoRacao)
            ->setParameter('producaoleite', 70);

        #Query para listar apenas animais vivos
        $queryBuilder
            ->andWhere('e.situacao = :estado')
            ->setParameter('estado', 1);

        #Query para separar animais com peso maior que 18 arrobas brasileiro.
        $queryBuilder
            ->andWhere('e.situacao > :param2')
            ->setParameter('param2', $pesoArrobaBR);

        #Query para animais com mais de 5 anos
        $dataAtual = new \DateTime();
        $dataNascimentoMinima = $dataAtual->modify('-5 years');

        $queryBuilder
            ->andWhere('e.nascimento < :dataNascimentoMinima')
            ->setParameter('dataNascimentoMinima', $dataNascimentoMinima);

        return $queryBuilder->getQuery()->getResult();
    }
    ###########################################################
    #Query responsavel por separar animais com consummo maior que 500 e que tenha menos de um ano de idade
    public function findAnimaisUmQuinhentos()
    {

        $queryBuilder = $this->createQueryBuilder('e');

        
        $queryBuilder
            ->andWhere('e.racao > :param2')
            ->setParameter('param2', 500);

        $dataAtual = new \DateTime();
        $dataNascimentoMaxima = (new \DateTime())->modify('-1 year');
        $queryBuilder->setMaxResults(5);

        $queryBuilder
            ->andWhere('e.nascimento >= :dataNascimentoMaxima')
            ->andWhere('e.nascimento <= :dataAtual')
            ->setParameter('dataNascimentoMaxima', $dataNascimentoMaxima)
            ->setParameter('dataAtual', $dataAtual);

        return $queryBuilder->getQuery()->getResult();
    }

    ## Função para selecionar animais abatidos
    public function findAnimaisAbatidos()
    {
        $queryBuilder = $this->createQueryBuilder('e');

        $queryBuilder
            ->andWhere('e.situacao = :estado')
            ->setParameter('estado', 0);

        return $queryBuilder->getQuery()->getResult();
    }

    ## Função para listar animais em ordem decrescente

    public function listAnimais($ordenacao)
    {
        $queryBuilder = $this->createQueryBuilder('e');
        $ordem = $ordenacao ? 'ASC' : 'DESC';

        $queryBuilder
            ->andWhere('e.situacao = :estado')
            ->setParameter('estado', 1);

        $queryBuilder->setMaxResults(5);

        $queryBuilder
            ->orderBy('e.leite', $ordem);

        return $queryBuilder->getQuery()->getResult();
    }

    #Funçao para somar produçao de leite e consumo de ração total dos animais vivos
    public function sumValues($tipo)
    {
        $queryBuilder = $this->createQueryBuilder('e');

        $sum = $queryBuilder
            ->select('SUM(e.' . $tipo . ') as total')
            ->andWhere('e.situacao = :situacao')
            ->setParameter('situacao', 1)
            ->getQuery()
            ->getSingleScalarResult();

        return $sum;
    }
    
    #Função para contar os animais de acordo com seu estado sendo eles: vivo, abatido ou
    public function countAnimais($situacao)
    {
        $queryBuilder = $this->createQueryBuilder('e');

        $count = $queryBuilder
            ->select('COUNT(e.id) as total')
            ->andWhere('e.situacao = :situacao')
            ->setParameter('situacao', $situacao)
            ->getQuery()
            ->getSingleScalarResult();
        return $count;
    }







    //    /**
    //     * @return Gado[] Returns an array of Gado objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('g.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Gado
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
