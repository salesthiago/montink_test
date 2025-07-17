<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About LaraveSistema de Pedidos - Montink
Sobre o Projeto
Este é um sistema de pedidos desenvolvido com Laravel, utilizando MySQL como banco de dados. O sistema inclui funcionalidades para:

Gerenciamento de produtos com variações

Controle de estoque

Processamento de pedidos

Aplicação de cupons de desconto

Cálculo automático de frete

Pré-requisitos
Antes de começar, certifique-se de ter instalado em sua máquina:

PHP 8.0 ou superior

Composer

MySQL 5.7 ou superior

Node.js (opcional para assets frontend)

Instalação
Siga estes passos para configurar o projeto localmente:

Clone o repositório:

bash
git clone https://github.com/seu-usuario/montink.git
cd montink
Instale as dependências do PHP:

bash
composer install
Configure o ambiente:

Copie o arquivo .env.example para .env

Edite o arquivo .env com as configurações do seu banco de dados:

text
DB_CONNECTION=mysql
DB_HOST=127.0.0.1 (ou IP do seu servidor MySQL)
DB_PORT=3306
DB_DATABASE=nome_do_banco
DB_USERNAME=usuario_do_banco
DB_PASSWORD=senha_do_banco (deixe em branco se não houver senha)
Gere a chave da aplicação:

bash
php artisan key:generate
Execute as migrations e seeders:

bash
php artisan migrate --seed
(Opcional) Instale as dependências frontend:

bash
npm install && npm run dev
Executando o Projeto
Para iniciar o servidor de desenvolvimento:

bash
php artisan serve
O sistema estará disponível em: http://localhost:8000

Estrutura do Banco de Dados
O sistema utiliza as seguintes tabelas principais:

products - Armazena os produtos

variations - Variações dos produtos

stocks - Controle de estoque

coupons - Cupons de desconto

orders - Pedidos realizados

Configurações Adicionais
Variáveis de Ambiente Importantes
Além da configuração do banco de dados, você pode ajustar:

text
APP_NAME=Montink
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
Configuração de E-mail (opcional)
Para funcionalidades de notificação, configure no .env:

text
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@montink.com"
MAIL_FROM_NAME="Montink"
Comandos Úteis
Gerar link simbólico para storage:

bash
php artisan storage:link
Limpar cache:

bash
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan config:clear
