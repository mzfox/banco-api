# 💳 API Bancária - Desafio Técnico

Este projeto fornece uma **API RESTful** em **PHP puro** com **Docker**, para gerenciar contas e transações, além de documentação Swagger e testes automatizados.

---

## 🚀 Como executar

1. **Pré-requisitos**  
   - Docker  
   - Docker Compose  

2. **Clone o repositório**  
   ```bash
   git clone https://github.com/mzfox/banco-api.git
   cd banco-api
   ```

3. **Suba a stack**  
   ```bash
   docker-compose down
   docker-compose up --build -d
   ```

   - A API ficará disponível em `http://localhost:8000`  

4. ## 🗄️ Criar a tabela no banco
   O MySQL já cria o **banco** (`banco_digital`) automaticamente, mas você precisa criar a **tabela** `contas`. Siga estes passos:

   1. Acesse o Adminer em:  
      - O Adminer (MySQL) em `http://localhost:8080` (user: `root` / pass: `root`)
   2. Preencha:
      - **Servidor**: `db`  
      - **Usuário**: `root`  
      - **Senha**: `root`  
      - **Banco de dados**: `banco_digital`
        
   3. Clique em **Login** → **SQL Command** e cole:
      ```sql
         CREATE TABLE IF NOT EXISTS contas (
         id INT AUTO_INCREMENT PRIMARY KEY,
         numero_conta INT NOT NULL UNIQUE,
           saldo DECIMAL(10,2) NOT NULL
         );
---

## 🧪 Testando a API

Use Postman, Insomnia ou `curl`:

### 1. Criar conta  
```http
POST http://localhost:8000/conta
Content-Type: application/json

{
  "numero_conta": 123,
  "saldo": 100.00
}
```

- **201** → `{ "numero_conta": 123, "saldo": 100.00 }`  
- **409** → `{ "erro":"Conta já existe" }`

### 2. Consultar conta  
```http
GET  http://localhost:8000/conta?numero_conta=123
```
- **200** → `{ "numero_conta":123, "saldo":100.00 }`  
- **404** → `{ "erro":"Conta não encontrada" }`

### 3. Fazer transação  
```http
POST http://localhost:8000/transacao
Content-Type: application/json

{
  "numero_conta": 123,
  "valor": 10,
  "forma_pagamento": "D"
}
```
- **201** → `{ "numero_conta":123, "saldo":89.70 }` (10 + 3% débito)  
- **404** → `{ "erro":"Conta não encontrada" }` ou `{ "erro":"Saldo insuficiente" }`

---

## 📖 Documentação Swagger

- Abra no navegador:  
  ```
  http://localhost:8000/docs/
  ```
- Arquivo YAML cru:  
  ```
  http://localhost:8000/swagger.yaml
  ```

---

## 🧪 Testes Automatizados

1. Após subir os containers:
   ```bash
   docker exec -it banco-api composer install
   ```
2. Rode os testes:
   ```bash
   docker exec -it banco-api ./vendor/bin/phpunit --testdox
   ```

---

## 📁 Estrutura do projeto

```
banco-api/
├── public/
│   ├── index.php
│   ├── swagger.yaml
│   └── docs/
│       └── index.html
├── src/
├── tests/
├── Dockerfile
├── docker-compose.yml
├── composer.json
├── phpunit.xml
├── README.md
```

---

## 📬 Contato

Desenvolvido por Matheus Visoto Dias para o desafio técnico PHP Pleno/Sênior.
