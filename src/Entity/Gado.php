<?php

namespace App\Entity;

use App\Repository\GadoRepository;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GadoRepository::class)]
class Gado
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(nullable: true)]
    #[Assert\PositiveOrZero(
        message: 'Valores acima de 0(zero).'
    )]
    private ?float $leite = null;

    #[ORM\Column]
    #[Assert\PositiveOrZero(
        message: 'Insira valor positivo para ração.'
    )]
    private ?float $racao = null;

    #[ORM\Column]
    #[Assert\PositiveOrZero(
        message: 'Insira valor positivo para peso.'
    )]
    private ?float $peso = null;

    #[ORM\Column]
    private ?int $situacao = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\LessThanOrEqual('today',
    message:"Datas futuras são inapropriadas para inserção.")
    ]
    private ?\DateTimeInterface $nascimento = null;

    #[ORM\Column(nullable: true)]
    private ?int $codigo = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLeite(): ?float
    {
        return $this->leite;
    }

    public function setLeite(?float $leite): static
    {
        $this->leite = $leite;

        return $this;
    }

    public function getRacao(): ?float
    {
        return $this->racao;
    }

    public function setRacao(float $racao): static
    {
        $this->racao = $racao;

        return $this;
    }

    public function getPeso(): ?float
    {
        return $this->peso;
    }

    public function setPeso(float $peso): static
    {
        $this->peso = $peso;

        return $this;
    }

    public function getSituacao(): ?int
    {
        return $this->situacao;
    }

    public function setSituacao(int $situacao): static
    {
        $this->situacao = $situacao;

        return $this;
    }

    public function getNascimento(): ?\DateTimeInterface
    {
        return $this->nascimento;
    }

    public function setNascimento(\DateTimeInterface $nascimento): static
    {
        $this->nascimento = $nascimento;

        return $this;
    }

    public function getCodigo(): ?int
    {
        return $this->codigo;
    }

    public function setCodigo(?int $codigo): static
    {
        $this->codigo = $codigo;

        return $this;
    }
}
