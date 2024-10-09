# ESTRUTURA PARA DESENVOLVIMENTO DO SISTEMA DA SYSTEM

Os únicos arquivos php no diretório raiz serão o arquivo index (página principal do sistema), o arquivo de login e o arquivo sair. Todos os demais arquivos (de view, isto é, de páginas) estarão dispostos no diretório `cadastros` (páginas administrativas) ou `cadastros_livre`, seguindo uma estrutura similar a:
 ```
  - cadastros
    - usuarios
 ```

## Sobre os diretórios

 ### `/gerenciamento/cadastros`
  Este diretório é onde estarão contidas as páginas do sistema (como já mencionado, exceto a de login, a principal e o arquivo de sair).

  Ele será dividido de acordo com as categorias do sistema, visando ter definições de rotas mais interessante.

  NOTA: o objetivo dos arquivos deste diretório é possibilitar a configuração de rotas mais interessantes, é importante lembrar de que o conteúdo das páginas, em si, caso seja possível, será modulado (como cabeçalhos, rodapés, imports...) e estará contido no diretório `/src/componentes`

 ### `/assets`
  Essa pasta deve conter todos os arquivos estáticos do sistema, como CSS, JS e imagens. 
  IMPORTANTE: **arquivos estáticos do sistema**

  **NOTA:** o nome dessa pasta é alterado de acordo com as atualizações feitas, para evitar o cache. As alterações de nome seguem o formato:
  ```php
    $nome_assets = "assets_" . date('d') . date('m') . date('Y') . date('H') . date('i');
  ```
  Este nome é definido apenas na pasta, em si, e na constante `ASSETS_PATH` no arquivo `/src/config.php` (ambos precisam ser editados manualmente após modificação).

 ### `/uploads`
  Esta pasta é apenas para upload de arquivos dos usuários, como, por exemplo, comprovantes e documentos. Vale salientar que este diretório é adicionado ao gitignore, portanto não existirá, por padrão, no repositório, mas haverá um diretório exemplo: `uploads_ref`.

  é interessante que o upload de arquivos seja feito considerando a estrutura de pastas das categorias do sistema.

  ```
   - uploads
    - usuarios
  ```

 ### `/api/get`
  Caso, em alguma página, seja necessário aplicar uma requisição fetch para puxar informações, a requisição deverá ser feita para este diretório.

  Vale salientar que o interessante é dispor os arquivos de acordo com as categorias do sistema

  ```
   - api
    - get
      - usuarios
  ```

 ### `/api/post`
  Os formulários de cadastro, edição, ou exclusão do sistema serão todos enviados para cá.

  Vale salientar que o interessante é dispor os arquivos de acordo com as categorias do sistema

  ```
   - api
    - post
      - usuarios
  ```

 ### `/src/Model`
  Este diretório é, basicamente, a representação, através das classes, do banco de dados.

 ### `/src/Controller`
  Este diretório é, basicamente, a representação, através das classes, dos casos de uso do sistema.

 ### `/src/Componentes`
  Este diretório visa conter os módulos do sistema: cabeçalhos, rodapés, imports, modais, etc.

 ### `/src/resources`
  Aqui estarão contidos arquivos que são necessários para auxiliar o funcionamento do sistema, principalmente arquivos com funções para auxílio das funcionalidades do sistema.

## Sobre arquivos importantes
  Existem alguns arquivos que valem ser destacados aqui, por suas funcionalidades ou por cuidados necessários.

  ### `/src/resources/constantes.php`
   Nesse arquivo são definidas as constantes de conexão com o banco de dados e a constante que define o ambiente da aplicação (development, production, ...)
   Ele não existe, por padrão, pois está contido no gitignore, mas há um arquivo referência para ser utilizado: `/src/resources/constantes_ref.php`.

  ### `/src/resources/system_functions.php`
   Este arquivo é importante pois é fundamental a sua chamada em todos os arquivos php, pois faz alguns carregamentos importantes, como:
   - rodar o session_start();
   - carregar o `/vendor/autoload.php`;
   - verificar se o usuário está logado ou não, e se precisa bloquear ou não;
   - verificar se o arquivo acessado corresponde a um arquivo acessível pelo sistema ou não (baseando-se nas rotas do `routes.json`);
   - contem funções importantes, como a `returnsPathFromHost()` que recebe as partes do caminho a partir do diretório raiz, unindo as partes com o `DIRECTORY_SEPARATOR`.

  ### `routes.json`
   Este arquivo define as rotas acessíveis do sistema, bem como a relação entre os arquivos não acessíveis diretamente, com o arquivo pelo qual é chamado. Explicando melhor isso: suponha que a rota é `/gerenciamento/cadastros/usuarios`, mas a requisição está sendo feita para `/gerenciamento/cadastros/usuarios/cadastrar.php`, esta não é uma rota acessível, mas é uma rota associada a `/gerenciamento/cadastros/usuarios`, então faremos o redirecionamento.

## SOBRE O ROUTES
  ### title
    Título da rota

  ### parent
    Rotas anteriores que são necessárias para chegar nesta

  ### requirements
   - auth || not_auth
    Checa se o usuário está ou não logado

   - post || get
    Checa o método de requisição

   - development || production
    Checa se o ambiente de acesso está autorizado

   - geral || developer || privilege
    Checa os privilégios do usuário

  ### alternative
    O que fazer quando o requisito (requirements) não for atendido

  ### related
    Páginas afiliadas