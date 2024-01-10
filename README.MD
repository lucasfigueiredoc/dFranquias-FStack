
# Teste-FullStack_DFranquias

    Software web responsável por gerenciar o gado em uma fazenda.

Esta aplicação tem como requisito o [teste](https://drive.google.com/file/d/1yP_Ld1zXsgh8FXGRk8lODTsN48BusBHh/view?usp=sharing) para a vaga na DFranquias.

## Stack
* Implementação feita em PHP >=8.0.*;
* Utiliza o framework Symfony >=6.0*;
* Banco de dados MySQL >=8.0*;
* Maiores detalhes podem ser encontrados no arquivo composer.json na raiz do projeto.

 Links das dependências:

   [Paginator](https://github.com/KnpLabs/KnpPaginatorBundle)

    Form
        \
         composer require symfony/form

    Doctrine ORM
        \
         composer require symfony/orm-pack

    Bootstrap
        \
         composer require twbs/bootstrap:5.3.2

## Execução do projeto


Execução do projeto
Após verificar as dependências, configure o banco de dados. Execute o primeiro comando para criá-lo:

       php bin/console doctrine:database:create

Na pasta raiz do projeto, há um banco chamado fazenda.sql pronto. Basta importá-lo para iniciar os testes. Também está disponível um script em Python que gera animais com números variados aleatórios para preencher o banco. Este script pode ser editado com os parâmetros desejados. No arquivo arquivo.txt, já existe uma query pronta, caso precise de um script pronto.

Com o banco conectado e preenchido, na pasta raiz execute:


    symfony serve:start

# Entidade


Animais podem ser adicionados, editados e excluídos. Todo animal vivo deve ter um código; animais abatidos e mortos não precisam.

![Alt text](image.png)