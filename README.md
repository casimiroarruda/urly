Duodraco\Urly
===

Encurtador de URLs
---

> Este projeto implementa um encurtador de URLs escrito em PHP.

### Estrutura

#### Diretórios:

* `application`: Arquivos de configuração, setup de rotas e inicialização
* `build`: contém artefatos para a montagem do projeto - atualmente com os migrations
* `library`: O código fonte reside aqui
* `web`: entrypoint web

#### Projeto:

O projeto usa componentes do Symfony para toda a parte HTTP, Injeção de Dependências e
configuração. Uso também uma biblioteca para facilitar o acesso ao banco de dados,
desenvolvida principalmente por mim.

Em `Duodraco\Urly\Context` estão os Commands da aplicação. Porque não usar Controllers tradicionais?
O padrão de projeto Command define uma organização de classes de maneira que um objeto
encapsule tudo o necessário para que um método seja executado em outro objeto.
Você encontrará uma classe `Commandee` que tem diversos métodos. Os objetos responsáveis
por tratar as requisições web, `Commands`, têm um só método que se utiliza daqueles
declarados em `Commandee` - essa classe pode ser facilmente submetida a uma suíte de testes.

Em `Duodraco\Urly\Data` há as classes que são hidratados com os dados persistidos em
banco de dados, `Url` e `User`.

Em `Duodraco\Urly\Services` há classes acessórias servindo de base para o "framework"

#### Instalação

Este projeto se utiliza do Composer para resgatar as dependências externas.
Ele é todo autocontido e funciona desde o embed web server do PHP a uma máquina virtualizada.

O banco de dados deve ser compatível com MySQL e o servidor web deve suportar o PHP 5.5 pelo menos.

O servidor web deve ser configurado de maneira que o *DOCUMENT_ROOT* seja o diretório `web` dentro
do projeto. Também é necessário configurar as reescritas de rotas para que qualquer url seja direcionada
para o `web/index.php`

O primeiro passo então é fazer a instalação. A partir da raiz instale o composer como
mostrado em https://getcomposer.org/download/. Após isso execute o instalador:

`php composer.phar update --optimize-autoloader`

Para configurar o acesso ao banco de dados basta declarar as seguintes variáveis de ambiente:

* `DB_DSN`: mysql:host=<host>;dbname=<nome_do_schema>
* `DB_USER`: usuário de acesso a esse banco
* `DB_PASS`: senha

Ao configurar essas variáveis e o servidor web para o diretório `web` só nos falta
configurar o banco de dados. Para isso usamos o `phinx`.

há um arquivo `phinx.php.sample` na raiz. copie-o ou renomeie-o para `phinx.php`,
 edite-o de acordo com o acesso de banco de dados e execute o seguinte comando
 (considerando que está na raiz do projeto):

`./vendor/bin/phinx migrate`

E ele se encarregará de criar as tabelas.

Nesse ponto, o projeto já deve estar ativo e pronto para o uso.