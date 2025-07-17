## 📦 Sistema de Pedidos - Montink
🔍 Sobre o Projeto
Este é um sistema de pedidos desenvolvido com Laravel, utilizando MySQL como banco de dados. O sistema oferece funcionalidades como:

    * Gerenciamento de produtos com variações

    * Controle de estoque

    * Processamento de pedidos

    * Aplicação de cupons de desconto

    * Cálculo automático de frete

## ✅ Pré-requisitos
Antes de iniciar a instalação, verifique se os seguintes requisitos estão atendidos:

PHP 8.0 ou superior

Composer

MySQL 5.7 ou superior

Node.js (opcional, para build de assets frontend)

## 🔧 Instalação 

**Siga os passos abaixo para configurar o projeto localmente:**

1. Clone o repositório
```bash
    git clone https://github.com/seu-usuario/montink.git
    cd montink
```
1. Instale as dependências PHP
```bash
    composer install
```
1. Configure o ambiente
```bash
    cp .env.example .env
```
Edite o arquivo .env com as informações do seu banco de dados:
```bash
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nome_do_banco
    DB_USERNAME=usuario_do_banco
    DB_PASSWORD=senha_do_banco
```
**Gere a chave da aplicação**
```bash
    php artisan key:generate
```

**🚀 Executando o Projeto**
Para iniciar o servidor de desenvolvimento, execute:

```bash
    php artisan serve
```

Acesse no navegador: http://localhost:8000

## 🗂 Estrutura do Banco de Dados
O sistema utiliza as seguintes tabelas principais:

products - Produtos

variations - Variações dos produtos

stocks - Estoque

coupons - Cupons de desconto

orders - Pedidos realizados

## ⚙️ Configurações Adicionais
Variáveis de Ambiente Importantes

APP_NAME=Montink
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
