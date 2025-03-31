# 🛠️ Helpdesk System

Sistema de Helpdesk avançado desenvolvido em Laravel para empresas de informática. Permite abertura e acompanhamento de tickets por clientes, e gerenciamento completo por administradores.

## 🚀 Tecnologias Utilizadas

- PHP (Laravel)
- MySQL
- jQuery
- Bootstrap/Tailwind CSS
- XAMPP
- Git + GitHub

## 🔧 Funcionalidades

- Autenticação de usuários (clientes e admins)
- Abertura e gerenciamento de tickets
- Sistema de categorias, prioridades e status
- Respostas e interações nos tickets
- Painel administrativo com relatórios
- Notificações e base de conhecimento (futuro)

## 📦 Instalação (Desenvolvimento)

1. Clone o repositório:
   ```bash
   git clone https://github.com/AndreFernandes37/helpdesk-system.git
   cd helpdesk-system
   ```

2. Instale as dependências:
   ```bash
   composer install
   ```

3. Copie e configure o `.env`:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Configure o banco de dados e rode as migrations:
   ```bash
   php artisan migrate
   ```

5. Suba o servidor local:
   ```bash
   php artisan serve
   ```

## ✅ Roadmap

- [x] Planejamento
- [x] Setup inicial
- [ ] Migrations e Models
- [ ] Autenticação
- [ ] Sistema de tickets
- [ ] Painéis (admin e cliente)
- [ ] Notificações
- [ ] Base de conhecimento
- [ ] Deploy

---

Feito com ❤️ por [@AndreFernandes37](https://github.com/AndreFernandes37)
