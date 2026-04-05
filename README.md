<p align="center">
  <img src="public/logo-lg-100-44.svg" alt="LG Electronics" height="44" />
</p>

# Desafio Técnico — LG Electronics

Dashboard de eficiência de produção da **Planta A · Manaus** para o mês de **janeiro/2026**.

> Nota: não tenho muita familiaridade com Laravel, mas busquei aplicar boas práticas e uma estrutura de código organizada, considerando o tempo disponível para o desafio.

## Visão geral

Linhas de produção atendidas:

- Geladeira
- Máquina de Lavar
- TV
- Ar-Condicionado

O dashboard permite:

- Visualizar **todas** as linhas
- Filtrar por **uma linha específica**

## Stack

- **Backend:** Laravel 7
- **Banco:** MySQL 8
- **Frontend:** Blade + JavaScript (Tailwind CDN + Chart.js)
- **PHP:** 7.4.33

## Funcionalidades implementadas

- **Login simples (teste)** com sessão
- **Dashboard protegido** (só acessa logado)
- **Seeder** que gera dados para **01/01/2026 a 31/01/2026**
- Dashboard consome dados do MySQL e exibe:
  - Linha
  - Quantidade produzida
  - Quantidade de defeitos
  - Eficiência (%)
- **Tooltip** no dashboard explicando a regra de eficiência

## Regra de eficiência

Este projeto utiliza a regra:

```text
Eficiência (%) = (produzida - defeitos) / produzida * 100
```

## Como rodar localmente (Windows + Laragon)

### Pré-requisitos

- Laragon com **PHP 7.4** selecionado
- MySQL iniciado no Laragon
- Composer

### 1) Instalar dependências

Na pasta do projeto:

```bash
composer install
php artisan key:generate
```

### 2) Criar o banco de dados

Crie o banco no MySQL:

```sql
CREATE DATABASE desafio_lge CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Configure o `.env` (exemplo Laragon):

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=desafio_lge
DB_USERNAME=root
DB_PASSWORD=
```

### 3) Criar tabelas e gerar dados (seeders)

```bash
php artisan migrate --seed
```

Se quiser recriar tudo do zero:

```bash
php artisan migrate:fresh --seed
```

### 4) Subir a aplicação

```bash
php artisan serve
```

URLs:

- Login:
  - `http://127.0.0.1:8000/login`
- Dashboard:
  - `http://127.0.0.1:8000/dashboard`

Credenciais de teste:

- Email: `test@test.com`
- Senha: `test123`

## Estrutura do banco

### Tabela: `produtividades`

Campos:

- `id` (BIGINT, PK, auto increment)
- `linha` (VARCHAR(50))
- `data_producao` (DATE)
- `quantidade_produzida` (INT UNSIGNED)
- `quantidade_defeitos` (INT UNSIGNED)

Índice:

- `(data_producao, linha)`

### SQL (CREATE TABLE + INSERTs de exemplo)

Caso queira simular o banco manualmente (sem seeders), segue um exemplo completo:

```sql
CREATE TABLE produtividades (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  linha VARCHAR(50) NOT NULL,
  data_producao DATE NOT NULL,
  quantidade_produzida INT UNSIGNED NOT NULL,
  quantidade_defeitos INT UNSIGNED NOT NULL,
  PRIMARY KEY (id),
  INDEX idx_data_linha (data_producao, linha)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;

INSERT INTO produtividades (linha, data_producao, quantidade_produzida, quantidade_defeitos) VALUES
('geladeira',      '2026-01-03', 320,  9),
('maquina',        '2026-01-03', 280, 12),
('tv',             '2026-01-03', 410, 18),
('arcondicionado', '2026-01-03', 260, 15),
('geladeira',      '2026-01-04', 305,  7),
('maquina',        '2026-01-04', 295, 10),
('tv',             '2026-01-04', 430, 22),
('arcondicionado', '2026-01-04', 240, 14);
```

## INSERTs de exemplo

```sql
INSERT INTO produtividades (linha, data_producao, quantidade_produzida, quantidade_defeitos) VALUES
('geladeira', '2026-01-03', 320, 9),
('maquina', '2026-01-03', 280, 12),
('tv', '2026-01-03', 410, 18),
('arcondicionado', '2026-01-03', 260, 15);
```

## Seeds: o que é gerado

O seeder `ProdutividadeSeeder` gera **31 dias** (01/01 a 31/01) para **4 linhas**, totalizando:

- **124 registros** (`31 * 4`)

Checagem rápida no MySQL:

```sql
USE desafio_lge;
SELECT COUNT(*) AS total FROM produtividades;
SELECT * FROM produtividades ORDER BY data_producao, linha LIMIT 10;
```

## Endpoints

- `GET /login`
- `POST /login`
- `POST /logout`
- `GET /dashboard`
