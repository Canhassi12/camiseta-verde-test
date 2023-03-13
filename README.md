# Desafio Back-end Picpay

## Objetivo

- Temos 2 tipos de usuários, os comuns e lojistas, ambos têm carteira com dinheiro e realizam transferências entre eles. 

- Ambos devem ter: 
    > Nome Completo, CPF, e-mail e Senha. CPF/CNPJ e e-mails devem ser únicos no sistema.
- Usuários podem enviar dinheiro (efetuar transferência) para lojistas e entre usuários. 

- Lojistas **só recebem** transferências, não enviam dinheiro para ninguém.

- Validar se o usuário tem saldo antes da transferência.

- Antes de finalizar a transferência, deve-se consultar um serviço autorizador externo.

- A operação de transferência deve ser uma transação (ou seja, revertida em qualquer caso de inconsistência) e o dinheiro deve voltar para a carteira do usuário que envia. 

- No recebimento de pagamento, o usuário ou lojista precisa receber notificação (envio de email, sms) enviada por um serviço de terceiro. 

- Este serviço deve ser RESTFul.
  
## Resolução

- ### Modelagem de software
    - Foram aplicados conceitos de ***DDD (Domain Driven Design),*** cujo objetivo é facilitar a implementação de regras de negócio e processos complexos, onde visa a divisão de responsabilidades por camadas.
    <img style="width: 200px; height: 250px;" src="https://imgur.com/REp1CgQ.png">

    - Além de oferecer uma maior escalabilidade, organização no projeto e trazer uma linguagem ubíqua para os que se relacionarem com o código e suas idéias.


- ### Modelagem de dados
  - Por serem tratados dados sensíveis, tanto os dados dos usuarios/lojista tanto informações da carteira e transações, decidi usar `UUID` por proporcionar uma maior segurança.

  - Usando o `Unique()` ja não temos os problemas de usuarios e lojistas com e-mail, CPF/CNPJ repetidos.
  - Temos camadas de repositório para lidar com as queries(consultas) no banco de dados.
  - Foram aplicados também conceitos de [SOLID](https://www.digitalocean.com/community/conceptual-articles/s-o-l-i-d-the-first-five-principles-of-object-oriented-design). Como Single Responsibility Principle, Dependency Inversion Principle, Open-Closed Principle. 
  - Para o serviço de envios notificações foi utilizado [Outbox Pattern](https://medium.com/event-driven-utopia/sending-reliable-event-notifications-with-transactional-outbox-pattern-7a7c69158d1b), para não ocorrer falhas nos processos de transações. Com a solução de serviço assíncrono, utilizando Jobs e queues.
  - Em ambos os serviços temos uma camada para tentativas(Retry pattern), para caso as requisições falharem por alguma inconsistência na API. Usando conceitos de [Back-Off](https://aws.amazon.com/pt/blogs/architecture/exponential-backoff-and-jitter/) e [Jitter](https://aws.amazon.com/pt/blogs/architecture/exponential-backoff-and-jitter/), será feito 3 tentativas e conforme haver falhas terá um tempo de aguardo para as proximas requisições, e não sobrecarregar mais as API's.
  - Tratamentos de erros e Logs dos processos.  

## Como rodar o projeto
    1 - git clone https://github.com/Canhassi12/camiseta-verde-test.git
    2 - composer install
    3 - Renomear .env.example para .env
    4 - php artisan key:generate
    5 - Crie um banco de dados para o projeto
    6 - Dentro do arquivo .env, coloque o nome do banco no campo DB_DATABASE="".

- ### Testes
  - Os testes feitos, cobrem com eficácia os códigos escritos e os possíveis erros, usando também ***`MOCK`*** de dados para os testes de repositório, e nas requisições tanto do autorizador externo, tanto o de notificação.
  - #### Como rodar os testes
    ``` bash 
    $ php artisan test
    ```
  - Para enviar os emails na fila é necessario rodar os seguites comandos:
    ``` bash 
    $ php artisan schedule:run  
    ```
## Referêcias
[GuzzleHttp](https://docs.guzzlephp.org/en/stable/) <br>
[Laravel Docs](https://laravel.com/docs/9.x) <br>
[DDD](https://fullcycle.com.br/domain-driven-design/) <br>
[DDD](https://medium.com/saga-do-programador/camada-de-aplicação-domain-driven-design-e-isolamento-do-domínio-55348fbf1a26) <br>
[Onion Architecture](https://medium.com/expedia-group-tech/onion-architecture-deed8a554423) <br>
[Onion Architecture](https://medium.com/expedia-group-tech/onion-architecture-deed8a554423) <br>
[Outbox Pattern](https://medium.com/event-driven-utopia/sending-reliable-event-notifications-with-transactional-outbox-pattern-7a7c69158d1b)
