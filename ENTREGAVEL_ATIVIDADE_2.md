# Relatório de Testes de Integração (Atividade Avaliativa 2)

### 1. Cobertura de Testes
Foram implementados testes de integração (Feature Tests) utilizando o banco de dados em memória (SQLite), cobrindo todos os cenários válidos (operações de CRUD bem-sucedidas), validações de dados (entradas inválidas ou ausentes) e as respectivas respostas HTTP, abrangendo os seguintes Controladores:
- **AutorController**: Testes para listagem, criação (com falhas em validação), edição, e falhas em exclusão.
- **LivroController**: Testes para fluxos de listagem, exibição, criação, edição e exclusão de Livros.
- **PessoaController**: Testes cobrindo listagem, criação (com validação de senhas correspondentes e flash messages), atualização e fluxo de exclusão.
- **BibliotecasController**: Testes para os fluxos CRUD. Detectado e validado o redirecionamento correto com mensagens de erro nos casos onde exceções ocorrem.

### 2. Problemas Identificados e Corrigidos na Aplicação
Durante a confecção dos testes, foram encontrados e **corrigidos** alguns problemas no código fonte original da aplicação para que o fluxo funcionasse corretamente:
- **`livros.show`**: Ao tentar visualizar um livro, a API retornava Erro 500 pois a view `resources/views/livros/show.blade.php` não existia. Ela foi criada.
- **`BibliotecasController`**: O redirecionamento no bloco `catch` dos métodos `store` e `update` tentava redirecionar para uma rota chamada `bibliotecas.new`, mas a rota correta configurada em `web.php` era `bibliotecas.create`. O código do controller foi corrigido.

### 3. Problemas e Falhas Mantidos (Bugs da API)
Os seguintes problemas foram identificados nos endpoints da aplicação e documentados/validados em forma de testes que expõem o problema, conforme o comportamento atual (para demonstrar que os dados inválidos não quebram o teste silenciosamente, ou seja, o erro foi coberto pelo teste):
- **O método `destroy` no `AutorController` não está implementado**. Isso gera um erro 500 (`BadMethodCallException`) ao tentar excluir um autor via rota `DELETE /autores/{id}`. (Validado pelo teste `test_destroy_autor_fails_because_not_implemented`).
- **O método `destroy` no `PessoaController` está vazio e não realiza exclusão**. Ele retorna um status HTTP 200 em branco, mas o registro permanece no banco de dados. (Validado pelo teste `test_destroy_pessoa_is_empty_and_does_not_delete`).

### 4. Configuração CI/CD (GitHub Actions)
Um workflow do GitHub Actions foi configurado no arquivo `.github/workflows/tests.yml` para executar todos os testes automatizados a cada Pull Request para a branch `develop`. A rotina executa o setup completo de ambiente, instala dependências e executa o `php artisan test --coverage` com segurança de não necessitar interação do usuário (ex: a criação prévia do banco `database/database.sqlite` foi provisionada na pipeline).

<img width="746" height="352" alt="Captura de tela 2026-06-24 095957" src="https://github.com/user-attachments/assets/dd05e2c8-52df-4069-8641-757dbc81f90c" />


<img width="796" height="392" alt="Captura de tela 2026-06-24 095934" src="https://github.com/user-attachments/assets/258b8ad0-7339-49a5-9806-3e6da942abfb" />
