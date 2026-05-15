# GestSchool — Design System

> Documento de referência para o visual do GestSchool, inspirado no template
> [BootstrapDash Connect Plus](https://demo.bootstrapdash.com/connect-plus/jquery/template/demo_1/).
> Stack: **Laravel + Blade + Tailwind CSS** (via Breeze) com Nunito + Lucide icons.
> Todo o sistema visual está implementado e em uso em todas as 29 rotas activas.

---

## 1. Filosofia

- **Limpo e funcional** — interface de gestão escolar, optimizada para uso diário
- **Flat** — sem sombras pesadas, sem gradientes desnecessários
- **Densidade controlada** — muito whitespace em cards (padding 40px), tabelas compactas
- **Sidebar fixa dark** como ancora — sempre visível em desktop, escondível em mobile
- **Hierarquia por tipografia + cor**, não por sombras
- **Sem CSS inline** — todas as classes são semânticas via `@layer components`

---

## 2. Arquitectura do CSS

```
resources/css/app.css
├── @tailwind base                 — reset + tipografia base
├── @tailwind components           — Tailwind components layer
├── @tailwind utilities            — utility classes
│
├── @layer base                    — html, body, headings, links
│
└── @layer components              — classes semânticas do design system
    ├── Layout shell               .app-shell .app-main .app-content
    ├── Navbar                     .navbar .navbar-brand .navbar-inner .navbar-icon-btn
    ├── Sidebar                    .sidebar .sidebar-link .sidebar-section
    ├── Card                       .card .card-title .card-section .card-compact
    ├── Buttons                    .btn .btn-{variant} .btn-sm .btn-lg .btn-icon
    ├── Badges                     .badge .badge-{variant}
    ├── Forms                      .form-label .form-input .form-select .form-textarea .form-check
    ├── Tables                     .table .table-wrapper .table-actions .table-empty
    ├── Stat cards                 .stat-card .stat-label .stat-value .stat-icon-{variant}
    ├── Page header                .page-header .page-title .page-subtitle .page-breadcrumb
    ├── Alerts                     .alert .alert-{variant}
    ├── Dropdown                   .dropdown .dropdown-item .dropdown-divider
    ├── Filter bar                 .filter-bar
    └── Empty state                .empty .empty-icon .empty-title .empty-text
```

**Tokens** vivem em `tailwind.config.js`. Tudo o resto compõe esses tokens via `@apply`.

---

## 3. Tokens

### 3.1 Cores (`tailwind.config.js → theme.colors`)

```js
primary:   { DEFAULT: '#0062ff', soft: '#e0edff', 600: '#0050d4' }
success:   { DEFAULT: '#44ce42', soft: '#e3f8e3' }
danger:    { DEFAULT: '#fc5a5a', soft: '#feebeb' }
warning:   { DEFAULT: '#ffc542', soft: '#fff5d5' }
info:      { DEFAULT: '#a461d8', soft: '#f1e4fa' }
accent:    '#f2a654'      // atraso (laranja)
navy:      '#001737'      // títulos
body:      '#a7afb7'      // texto corrente
muted:     '#76838f'      // texto auxiliar
sidebar:   { DEFAULT: '#181824', hover: '#161621', text: '#bfbfd0' }
```

### 3.2 Mapeamento semântico do domínio

| Conceito | Cor / Variante |
|---|---|
| Presente | `success` |
| Falta | `danger` |
| Falta justificada | `warning` |
| Atraso | `accent` (#f2a654) |
| Matrícula activa | `success` |
| Aluno aprovado | `success` |
| Aluno reprovado | `danger` |
| Aluno transferido | `info` |
| Ano lectivo activo | `success` |
| Trimestre aberto | `success` |
| Trimestre fechado | `muted` |

### 3.3 Tipografia

**Nunito** (300, 400, 600, 700) carregada via Bunny Fonts:

```html
<link href="https://fonts.bunny.net/css?family=nunito:300,400,600,700" rel="stylesheet">
```

| Elemento | Tamanho | Peso | Cor |
|---|---|---|---|
| Body | 1rem (16px) | 400 | `body` |
| Card title | 1.125rem | 600, UPPERCASE | `navy` |
| Page title | 1.5rem | 700 | `navy` |
| Stat value | 1.875rem | 700 | `navy` |
| h1–h6 | 2.5rem → 1rem | 700/600 | `navy` |

### 3.4 Spacing / Border-radius

```js
spacing.sidebar  = '258px'   // largura sidebar
spacing.navbar   = '64px'    // altura navbar
borderRadius.btn = '3px'     // botões (quase rectos)
borderRadius.input = '2px'   // inputs
```

### 3.5 Shadows

```js
boxShadow.card       = '0 1px 3px 0 rgba(0,23,55,0.04)'
boxShadow.card-hover = '0 4px 12px 0 rgba(0,23,55,0.08)'
```
(usados raramente — design é "flat")

---

## 4. Layout

### 4.1 Estrutura

```
┌────────────┬─────────────────────────────────┐
│  GestSch.  │  NAVBAR (64px)                  │
│ navbar-    │  search · locale · 🔔 · perfil  │
│ brand      │                                 │
├────────────┼─────────────────────────────────┤
│ SIDEBAR    │                                 │
│ (258px)    │  <x-page-header>                │
│ #181824    │  <x-card>...                    │
│            │  <x-data-table>...              │
│ Pessoas    │                                 │
│ • Users    │                                 │
│ • …        │                                 │
│            │                                 │
│ Estrutura  │                                 │
│ • Anos     │                                 │
│ • …        │                                 │
└────────────┴─────────────────────────────────┘
```

### 4.2 Layouts Blade

- **`layouts/app.blade.php`** — layout autenticado: `<x-navbar>` + `<x-sidebar>` + `<x-flash>` + slot
- **`layouts/guest.blade.php`** — layout de auth: lado esquerdo dark (`bg-sidebar`) + form centrado à direita

---

## 5. Componentes Blade

Todos em `resources/views/components/`. Usam apenas classes semânticas.

### 5.1 `<x-card>`

```blade
<x-card title="Lista de Alunos" subtitle="Ano lectivo activo">
    <x-slot name="actions">
        <x-btn variant="primary">Acção</x-btn>
    </x-slot>

    Conteúdo aqui…
</x-card>
```

**Props:** `title`, `subtitle`, `compact` (boolean — padding mais pequeno), `actions` (slot).

### 5.2 `<x-btn>` / `<x-btn-link>`

```blade
<x-btn variant="primary" icon="plus">Adicionar</x-btn>
<x-btn variant="success" size="sm">OK</x-btn>
<x-btn variant="danger" type="submit">Eliminar</x-btn>
<x-btn variant="secondary" :href="route('back')">Voltar</x-btn>

<x-btn-link :href="route('users.edit', $u)">Editar</x-btn-link>
<x-btn-link variant="danger">Eliminar</x-btn-link>
```

**Variants:** `primary` · `success` · `danger` · `warning` · `info` · `dark` · `secondary` · `ghost` · `outline-primary` · `outline-danger` · `outline-success`.
**Sizes:** default · `sm` · `lg` · `icon`.
**Icon:** nome Lucide sem prefixo (`plus`, `pencil`, `check`, etc.).

### 5.3 `<x-badge>`

```blade
<x-badge variant="success">{{ __('Active') }}</x-badge>
<x-badge variant="danger">{{ __('Inactive') }}</x-badge>
<x-badge variant="muted">director geral</x-badge>
```

**Variants:** `primary` · `success` · `danger` · `warning` · `info` · `muted` · `dark`.

### 5.4 `<x-input>` / `<x-select>` / `<x-textarea>` / `<x-checkbox>`

```blade
<x-input name="email" :label="__('Email')" type="email" required />
<x-input name="data_nascimento" :label="__('Birth Date')" type="date" :value="$user?->birthday" />

<x-select name="estado" :label="__('Status')" required :placeholder="null">
    <option value="activa">Activa</option>
    <option value="aprovado">Aprovado</option>
</x-select>

<x-textarea name="observacoes" label="Observações" :rows="3" />

<x-checkbox name="is_active" :label="__('Active')" :checked="$user->is_active" />
```

Erros do `$errors` são automaticamente mostrados.

### 5.5 `<x-page-header>`

```blade
<x-page-header title="Alunos" subtitle="Gestão de alunos da escola">
    <x-slot name="actions">
        <x-btn variant="primary" icon="plus" :href="route('alunos.create')">{{ __('New') }}</x-btn>
    </x-slot>
</x-page-header>
```

### 5.6 `<x-stat-card>` (KPI)

```blade
<x-stat-card
    :label="__('Students')"
    :value="$stats['alunos']"
    icon="graduation-cap"
    variant="primary"
    trend="+12% este mês"
    :href="route('alunos.index')" />
```

**Variants:** `primary` · `success` · `info` · `warning` · `danger`.

### 5.7 `<x-data-table>`

Tabela com pesquisa, filtros, botão novo, paginação. **Usada em todas as listagens.**

```blade
<x-data-table
    :searchPlaceholder="__('Search') . ' nome'"
    :searchValue="$q"
    :createUrl="route('alunos.create')">

    <x-slot name="filters">
        <div>
            <label class="form-label">{{ __('Class Groups') }}</label>
            <select name="turma_id" class="form-select">…</select>
        </div>
    </x-slot>

    <thead>
        <tr>
            <th>{{ __('Name') }}</th>
            <th class="text-right">{{ __('Actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse($items as $item)
            <tr>
                <td class="font-semibold text-navy">{{ $item->name }}</td>
                <td class="table-actions">
                    <x-btn-link :href="route('items.show', $item)">Ver</x-btn-link>
                    <x-btn-link variant="muted" :href="route('items.edit', $item)">Editar</x-btn-link>
                </td>
            </tr>
        @empty
            <tr><td colspan="2" class="table-empty">Sem registos.</td></tr>
        @endforelse
    </tbody>

    <x-slot name="footer">{{ $items->links() }}</x-slot>
</x-data-table>
```

### 5.8 `<x-empty>`

```blade
<x-empty icon="inbox" title="Sem dados" description="Crie o primeiro registo.">
    <x-btn variant="primary" :href="route('items.create')">Novo</x-btn>
</x-empty>
```

### 5.9 `<x-sidebar>` / `<x-sidebar-link>` / `<x-navbar>` / `<x-flash>`

Já no `layouts/app.blade.php`. Sidebar mostra secções diferentes consoante o role do utilizador (director/secretário, professor, encarregado).

---

## 6. Ícones

[Lucide](https://lucide.dev) via `mallardduck/blade-lucide-icons`:

```blade
<x-lucide-users class="w-5 h-5" />
<x-lucide-graduation-cap class="w-6 h-6 text-primary" />
<x-lucide-check />
```

**Ícones em uso:** users, briefcase, user-cog, graduation-cap, user-check, calendar, calendar-clock, layers, users-round, book-open, file-text, link, clipboard-check, clipboard-list, table-2, megaphone, layout-dashboard, menu, search, bell, chevron-down, plus, pencil, check, printer, book, user, clock, inbox.

---

## 7. Convenções de uso

### 7.1 Estrutura de página padrão

```blade
<x-app-layout>
    <x-page-header :title="$title" :subtitle="$subtitle">
        <x-slot name="actions">…</x-slot>
    </x-page-header>

    <x-card title="Secção 1">…</x-card>
    <x-card title="Secção 2">…</x-card>
</x-app-layout>
```

### 7.2 Forms

```blade
<x-app-layout>
    <x-page-header :title="__('New') . ' — ' . __('Student')" />
    <x-card>
        <form method="POST" action="{{ route('alunos.store') }}">
            @csrf
            @include('alunos._form', ['aluno' => null])
        </form>
    </x-card>
</x-app-layout>
```

O `_form.blade.php` é partial usado em `create` e `edit`. Recebe `$resource` (pode ser null no create).

### 7.3 NUNCA

❌ Não usar `class="bg-white shadow rounded-lg p-6"` directamente em páginas — usar `<x-card>`
❌ Não usar `class="px-4 py-2 bg-gray-800 text-white"` — usar `<x-btn>`
❌ Não usar `class="px-2 py-0.5 bg-green-100 text-green-800 rounded"` — usar `<x-badge>`
❌ Não usar `style="…"` inline excepto para cores fora da paleta padrão (justificado por comentário)
❌ Não duplicar layout shell (sidebar/navbar) — está no `layouts/app.blade.php`

### 7.4 SEMPRE

✅ Usar componentes Blade quando existirem (`<x-card>`, `<x-btn>`, etc.)
✅ Para classes novas — adicionar a `app.css` em `@layer components`
✅ Para cores novas — adicionar a `tailwind.config.js` antes de usar
✅ Texto em PT (Portugal/Angola). Strings reutilizadas em inglês via `__()` (suporta i18n)

---

## 8. Estado actual

### 8.1 Foundation
- ✅ `tailwind.config.js` com todos os tokens
- ✅ `resources/css/app.css` com 15 famílias de classes semânticas
- ✅ Nunito carregada via Bunny Fonts
- ✅ Lucide icons instalado

### 8.2 Componentes Blade (em `resources/views/components/`)
- ✅ `card`, `flash`, `btn`, `btn-link`, `badge`
- ✅ `input`, `select`, `textarea`, `checkbox`
- ✅ `page-header`, `stat-card`, `data-table`, `empty`
- ✅ `sidebar`, `sidebar-link`, `navbar`

### 8.3 Layouts
- ✅ `layouts/app.blade.php` — shell autenticado (sidebar + navbar)
- ✅ `layouts/guest.blade.php` — auth pages (split layout)

### 8.4 Páginas refactor (29 rotas, todas 200)
- ✅ Welcome · 6 páginas auth · Profile (3 partials)
- ✅ 3 dashboards (admin / professor / encarregado)
- ✅ CRUD users · professores · alunos · encarregados · funcionarios
- ✅ CRUD anos · classes · disciplinas · turmas · matriculas · atribuicoes · trimestres
- ✅ Aulas (CRUD + folha presenças com radio + contadores ao vivo)
- ✅ Avaliações (CRUD) + folha de notas
- ✅ Pautas (index + show com média)
- ✅ Boletim (com impressão)
- ✅ Comunicados (CRUD)
- ✅ Encarregado: meus-educandos + aluno-perfil

### 8.5 Bundle final
- CSS: **63.6 KB** (10.1 KB gzipped) — `public/build/assets/app-*.css`
- JS: 88.5 KB (32.7 KB gzipped) — Alpine.js

---

## 9. Próximos passos

- Configurar `darkMode` no Tailwind quando suportarmos modo escuro
- Adicionar `<x-modal>` (modal genérico, hoje só temos em delete-user)
- Adicionar `<x-tabs>` para páginas com várias secções (`profile`)
- Adicionar `<x-pagination>` quando o default do Laravel não chegar
- Snapshot visual de cada página (Playwright + Percy) para garantir não-regressão visual

---

## 10. Referências

- Template original: <https://demo.bootstrapdash.com/connect-plus/jquery/template/demo_1/>
- Nunito: <https://fonts.google.com/specimen/Nunito>
- Lucide: <https://lucide.dev>
- Tailwind config: `/Users/macbook/DEV/gestSchool/tailwind.config.js`
- CSS layer: `/Users/macbook/DEV/gestSchool/resources/css/app.css`
- Componentes: `/Users/macbook/DEV/gestSchool/resources/views/components/`
