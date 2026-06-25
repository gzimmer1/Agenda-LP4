# 📅 Agenda de Compromissos

Sistema web de gerenciamento de compromissos desenvolvido com **PHP + MySQL + Bootstrap 5**, seguindo o padrão **MVC**.

---

## 🛠️ Tecnologias

| Camada | Tecnologia |
|--------|-----------|
| Back-end | PHP 8.1+ |
| Banco de dados | MySQL 8+ |
| Front-end | Bootstrap 5.3, Bootstrap Icons |
| Gráficos | Chart.js 4 |
| Padrão de projeto | MVC (Model-View-Controller) |
| Servidor | Apache com mod_rewrite |

---

## 📁 Estrutura do Projeto (MVC)

```
agenda/
├── app/
│   ├── controllers/          # Camada Controller
│   │   ├── Controller.php    # Controller base (abstract)
│   │   ├── AuthController.php
│   │   ├── CompromissoController.php
│   │   ├── DashboardController.php
│   │   └── ApiController.php
│   ├── models/               # Camada Model
│   │   ├── Database.php      # Singleton PDO
│   │   ├── Usuario.php
│   │   └── Compromisso.php
│   └── views/                # Camada View
│       ├── layouts/
│       │   ├── main.php      # Layout principal (navbar, footer)
│       │   └── auth.php      # Layout de autenticação
│       ├── auth/
│       │   ├── login.php
│       │   └── cadastro.php
│       ├── compromissos/
│       │   ├── index.php     # Listagem com filtros
│       │   └── form.php      # Criação/edição
│       └── dashboard/
│           └── index.php     # Painel com gráficos
├── config/
│   ├── app.php               # Constantes da aplicação
│   └── database.php          # Configuração MySQL
├── database/
│   └── schema.sql            # Schema + dados de exemplo
├── public/                   # Web root (acessível pelo navegador)
│   ├── index.php             # Front Controller
│   ├── .htaccess             # Rewrite rules
│   ├── css/style.css
│   └── js/app.js
├── routes/
│   └── router.php            # Roteamento de URLs
└── README.md
```

---

## ⚙️ Instalação

### Pré-requisitos
- PHP 8.1+
- MySQL 8+
- Apache com `mod_rewrite` habilitado
- XAMPP, WAMP ou servidor equivalente

### Passo a passo

**1. Clone o repositório**
```bash
git clone https://github.com/seu-usuario/agenda-compromissos.git
cd agenda-compromissos
```

**2. Configure o banco de dados**

Crie o banco e importe o schema:
```bash
mysql -u root -p < database/schema.sql
```

Ou pelo phpMyAdmin: importe o arquivo `database/schema.sql`.

**3. Configure a conexão**

Edite `config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'agenda_db');
define('DB_USER', 'root');
define('DB_PASS', 'sua_senha');
```

Edite `config/app.php` se necessário:
```php
define('BASE_URL', 'http://localhost/agenda/public');
```

**4. Coloque o projeto na pasta do servidor**

Copie a pasta `agenda/` para `htdocs/` (XAMPP) ou `www/` (WAMP).

**5. Acesse no navegador**
```
http://localhost/agenda/public
```

**Credenciais de demo:**
- E-mail: `admin@agenda.com`
- Senha: `password`

---

## 🔗 REST API

A aplicação expõe endpoints JSON para consumo externo:

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| GET | `/api/compromissos` | Lista compromissos |
| GET | `/api/compromissos/{id}` | Detalhe de um compromisso |
| POST | `/api/compromissos` | Cria compromisso (JSON body) |
| GET | `/api/stats` | Estatísticas para gráficos |

**Exemplo de requisição:**
```bash
curl http://localhost/agenda/public/api/compromissos
```

**Resposta:**
```json
{
  "sucesso": true,
  "total": 5,
  "dados": [
    {
      "id": 1,
      "titulo": "Reunião com equipe",
      "data_hora": "2025-08-15 14:00:00",
      "categoria": "trabalho",
      "status": "pendente"
    }
  ]
}
```

---

## ✨ Funcionalidades

- [x] **Autenticação** — login, cadastro e logout com senha criptografada (bcrypt)
- [x] **CRUD completo** — criar, listar, editar e excluir compromissos
- [x] **Filtros** — por categoria, status e busca por texto
- [x] **Dashboard** — painel com 3 gráficos interativos (Chart.js):
  - Rosca: compromissos por status
  - Barras: compromissos por categoria
  - Linha: distribuição por dia da semana
- [x] **REST API** — endpoints JSON para consumo externo
- [x] **Design responsivo** — Bootstrap 5 com layout mobile-first
- [x] **Validação** — no cliente (JavaScript) e no servidor (PHP)
- [x] **Flash messages** — feedback visual para todas as ações
- [x] **Modal de confirmação** — antes de excluir registros
- [x] **Segurança** — prepared statements (SQL Injection), XSS, hash de senha

---

## 🗄️ Banco de Dados

**Tabela `usuarios`**
| Campo | Tipo | Descrição |
|-------|------|-----------|
| id | INT PK | Chave primária |
| nome | VARCHAR(100) | Nome do usuário |
| email | VARCHAR(150) UNIQUE | E-mail único |
| senha | VARCHAR(255) | Hash bcrypt |
| criado_em | TIMESTAMP | Data de cadastro |

**Tabela `compromissos`**
| Campo | Tipo | Descrição |
|-------|------|-----------|
| id | INT PK | Chave primária |
| usuario_id | INT FK | Dono do compromisso |
| titulo | VARCHAR(200) | Título |
| descricao | TEXT | Descrição opcional |
| data_hora | DATETIME | Data e hora do evento |
| local | VARCHAR(200) | Local opcional |
| categoria | ENUM | pessoal/trabalho/saude/estudo/outro |
| status | ENUM | pendente/concluido/cancelado |

---

## 📋 Commits sugeridos

```
1. chore: estrutura inicial do projeto (MVC, config, autoload)
2. feat: banco de dados MySQL + models (Database, Usuario, Compromisso)
3. feat: CRUD de compromissos + autenticação + views Bootstrap
4. feat: dashboard com gráficos Chart.js + REST API JSON
```

---

## 👨‍💻 Desenvolvido por

Projeto desenvolvido para a disciplina de Desenvolvimento Web — IFRS.
