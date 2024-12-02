# mineco-finance-ci4 

Este projeto tem como objetivo oferecer uma plataforma para controle financeiro pessoal.

## Tecnologias Utilizadas
- PHP 8.1
- CodeIgniter 4.5.5
- MySQL

## Instalação
1. Clone o repositório:
   ```bash
   git clone https://github.com/sandrolor/mineco-finance-ci4.git
   ```
2. Instale as dependências:
   ```bash
   composer install
   ```
3. Configure o arquivo `.env` com suas credenciais do MySQL.
  ```bash
  # Modo de desenvolvimento
  CI_ENVIRONMENT = development

  # Configuração do banco de dados
  database.default.hostname = localhost
  database.default.database = nome_do_banco
  database.default.username = seu_usuario
  database.default.password = sua_senha
  database.default.DBDriver = MySQLi

  # URL base da aplicação
  app.baseURL = 'http://localhost/seu_projeto'
  ```
4. Configure o Banco de Dados.
Use o script sql do arquivo 'mineco-finance-ci4-estrutura.sql'

## Uso
Use a URL da aplicação para executar o projeto.

## Contribuição
Sinta-se à vontade para contribuir!

## Licença
Este projeto está licenciado sob a GNU General Public License - GPL.
