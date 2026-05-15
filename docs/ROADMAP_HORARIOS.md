# Roadmap — Melhorias ao Horário Escolar

> **Documento vivo.** Actualizamos sempre que uma fase muda de estado.
> Cada fase é um PR isolado. Última actualização → ver secção [Changelog](#changelog) no fim.

---

## 🚦 Estado das fases

| # | Feature | Estado | Esforço | Valor | Risco |
|---|---|---|---|---|---|
| 1 | [Copy-paste de slots](#fase-1--copy-paste-de-slots) | ✅ Concluída (2026-05-15) | 🟢 1-2h | 🟢 Alto | 🟢 Baixo |
| 2 | [Bulk editor por professor](#fase-2--bulk-editor-por-professor) | 🟦 Em curso | 🟡 2-3h | 🟡 Médio | 🟡 Médio |
| 3 | [Drag & drop](#fase-3--drag--drop-de-slots) | ⬜ Pendente | 🔴 4-6h | 🟡 Médio | 🟡 Médio |
| 4 | [Sugestões IA / lacunas](#fase-4--sugestões-automáticas-ia--heurísticas) | ⬜ Pendente | 🔴 4-8h | 🟢 Alto | 🔴 Alto |

**Legenda:** ⬜ Pendente · 🟦 Em curso · ✅ Concluída · 🟥 Bloqueada · ⏸ Adiada

---

## ✅ Estado actual (baseline — já implementado)

- `horarios(atribuicao_id, dia_semana, tempo, sala, observacao)` — chave única `(atribuicao, dia, tempo)`
- `HorarioController` com CRUD individual + `bulkTurma` (planilha por turma)
- Validação de conflitos: professor não tem 2 aulas no mesmo slot · turma não tem 2 aulas no mesmo slot
- Views: grelha turma · grelha professor · bulk turma · PDF (turma e professor)
- Permissões: visualização para direcção + professores; CRUD só direcção

---

## Como manter este documento

Quando começarmos uma fase:
1. Mudar o estado para **🟦 Em curso** na tabela acima
2. Marcar tarefas concluídas com `- [x]` na fase respectiva
3. Adicionar nota na secção [Changelog](#changelog) com data e descrição curta

Quando terminamos uma fase:
1. Mudar estado para **✅ Concluída** com data
2. Adicionar entrada de changelog com link para os commits/PRs
3. Notar quaisquer decisões diferentes do plano original em "Notas de implementação"

Se uma fase ficar bloqueada ou adiada, mudar estado para **🟥** ou **⏸** e explicar o porquê no changelog.

---

## Fase 1 — Copy-paste de slots

### Objectivo
No editor bulk da turma, permitir **copiar uma linha (tempo) ou coluna (dia) inteira** e colar noutro lugar — reduz cliques quando o horário tem padrões repetidos (ex: Matemática segunda, terça e quinta no 1º tempo).

### User stories
- *Como secretário, quero copiar a coluna "Segunda" e colá-la em "Quarta" para acelerar a entrada de horários.*
- *Como secretário, quero "limpar coluna" para libertar todos os slots de um dia.*
- *Como secretário, quero "duplicar linha" para um padrão fixo (ex: Ed. Física no 6º tempo de todos os dias).*

### Plano técnico
- **100% client-side**: Alpine.js manipula os `<select>` da grelha
- Adicionar **dropdown de acções** no topo de cada coluna (dia) e no início de cada linha (tempo):
  - `Copiar coluna` · `Colar` · `Limpar` · `Inverter com…`
- O state Alpine guarda em `localStorage.gestschool_horario_clipboard` para sobreviver a refresh
- Toolbar global: "Limpar tudo" · "Restaurar do servidor" · "Aplicar mesmo padrão à semana toda"

### Tarefas
- [x] Adicionar `x-data="bulkTurmaEditor()"` ao card principal de `bulk-turma.blade.php`
- [x] Cada select com `x-model="slots[dia][tempo].atribuicao_id"` (state em Alpine)
- [x] Botões `Copiar` / `Colar` / `Limpar` por dia e por tempo
- [x] Persistir clipboard em `localStorage` com TTL 24h
- [x] Botão "Aplicar a toda a semana" — copia a coluna actual para todos os dias lectivos
- [x] Confirmar antes de "Limpar tudo"
- [x] i18n das novas strings (16 chaves PT/EN)

### Critérios de aceitação
- ✓ Copiar segunda + colar em quarta → 8 selects e 8 inputs de sala preenchidos correctamente
- ✓ Refresh da página perde os dados não gravados (esperado — clipboard sobrevive, slots não)
- ✓ "Aplicar à semana toda" produz horário fixo (mesma disciplina em todos os dias do mesmo tempo) — útil para padrões repetidos
- ✓ Submit final ainda passa pela detecção de conflitos do servidor

### Riscos
- O clipboard em localStorage pode acumular dados antigos → adicionar timestamp e expirar após 24h
- Conflitos só validados no servidor → mostrar toast de aviso *após* paste se duas células do mesmo dia ficarem com mesmo professor

### Notas de implementação

**Decisões:**
- Optou-se por `x-model` em vez de `x-bind:value`+`@change` — Alpine sincroniza automaticamente e mantém o `name=` para o submit funcionar igual.
- `$initialSlots` é pré-inicializado **no controller** com todos os pares dia/tempo (mesmo vazios) — evita ter de criar propriedades dinamicamente no Alpine.
- Clipboard usa `JSON.stringify` para deep copy (suficiente; objectos são planos).
- TTL do clipboard é 24h. Após expirar, é apagado silenciosamente no `init()`.

**Componentes adicionados:**
- 2 dropdowns por célula de cabeçalho (coluna e linha) com Alpine `x-data="{ open: false }"` local
- 1 toolbar global no topo do card (status do clipboard + "Limpar tudo")
- 1 contador "X / Y slots preenchidos" no rodapé

**Riscos não materializados:**
- Conflitos após paste — decidiu-se **não** validar client-side; o servidor já valida no submit. UX simples > aviso preventivo.

**Não implementado (deferido):**
- Aviso visual quando 2 células do mesmo dia ficam com mesmo professor após paste (cosmético, esperar feedback)

---

## Fase 2 — Bulk editor por professor

### Objectivo
Espelho do bulk turma mas **organizado por professor**: o utilizador vê a semana completa do professor e atribui directamente turmas a cada slot. Útil quando se planeia a carga horária de um docente que lecciona em várias turmas.

### User stories
- *Como director pedagógico, quero ver toda a semana da prof. Ana e gravar tudo de uma só vez.*
- *Como secretário, quero ver visualmente quantas horas o prof. tem por semana e equilibrar.*

### Plano técnico
- Nova rota `GET /horarios/professor/{professor}/bulk` + `POST .../bulk`
- View `horarios/bulk-professor.blade.php` — mesma grelha, mas as opções dos selects são as **atribuições deste professor** (em todas as turmas)
- A célula mostra `[Turma] Disciplina` em vez de `[Disciplina] Professor`
- Cor de fundo da célula pode reflectir a turma (gera mapa de cores estável por turma)
- **Detecção de conflitos** mais subtil: dentro do submit do professor, naturalmente não há conflito do mesmo prof; mas é preciso verificar que a turma alvo não tem outro prof no mesmo slot
- Card lateral com **contador**: nº de tempos/semana, distribuição por turma

### Tarefas
- [ ] `HorarioController::bulkProfessor(Professor $professor)` — devolve view com atribuições e horários actuais
- [ ] `HorarioController::bulkProfessorStore(...)` — análoga à `bulkTurmaStore`, mas:
  - clean slate: apaga só horários **deste professor** (não da turma)
  - valida conflito por **turma** em vez de por professor
- [ ] View `bulk-professor.blade.php` (reutilizar `_grid` com modo='professor')
- [ ] Helper de cor por turma (hash do ID → palette fixa de 10 cores)
- [ ] Botão "Editar horário do professor" em `/horarios/professor/{professor}`
- [ ] Card de stats: horas/semana, breakdown por disciplina e por turma
- [ ] Rotas + i18n

### Critérios de aceitação
- ✓ Director vê grelha do prof. com slots já preenchidos
- ✓ Adicionar slot que conflita com turma já marcada (outra prof.) → erro claro
- ✓ Apagar slot do bulk professor não afecta os slots de outras turmas
- ✓ Contador de horas actualiza correctamente após gravar

### Riscos
- Decidir comportamento quando duas turmas precisam do mesmo professor no mesmo tempo (1ª A e 2ª A à mesma hora — impossível para 1 prof). Já bloqueado pela validação actual; documentar bem.
- Performance: a grelha pode renderizar 1 select com centenas de opções se o prof tem muitas atribuições. → limitar a atribuições do ano lectivo activo

### Notas de implementação
_(preencher durante/após implementação)_

---

## Fase 3 — Drag & drop de slots

### Objectivo
Substituir os `<select>` por **células arrastáveis** — o utilizador agarra uma aula e atira para outro slot livre. Reordenação visual sem editar dropdowns.

### User stories
- *Como secretário, quero arrastar a aula de matemática de 2ª/3º para 4ª/2º com um gesto.*
- *Quero ver visualmente quando estou a sobrepor 2 aulas no mesmo slot (drop alvo a vermelho).*

### Plano técnico
- **Dependência**: [SortableJS](https://github.com/SortableJS/Sortable) (≈30KB) — adicionar via `resources/js`
- 2 modos de UI no bulk editor (toggle):
  1. **Modo formulário** (actual) — selects e inputs
  2. **Modo visual** — cada slot é uma "carta" arrastável; lista lateral de "atribuições disponíveis"
- Drop targets: células vazias da grelha (verde) · células ocupadas (vermelho — substitui)
- Lista lateral: atribuições não usadas (cinza) e usadas (com badge)
- Estado mantido em Alpine como no copy-paste
- Submit serializa as posições e usa o mesmo `bulkTurmaStore`

### Tarefas
- [ ] `npm install sortablejs`
- [ ] Importar e inicializar em `resources/js/horario-editor.js`
- [ ] Marker visual quando célula está a ser arrastada
- [ ] Validação client-side: mostrar warning antes de drop em célula ocupada
- [ ] Toggle "Modo formulário / Modo visual" no topo
- [ ] Animação suave (Sortable tem por default)
- [ ] i18n

### Critérios de aceitação
- ✓ Arrastar aula entre slots vazios funciona em desktop e mobile (toque)
- ✓ Largar em slot ocupado mostra aviso e troca slots
- ✓ Lista lateral sempre reflecte estado actual
- ✓ Submit funciona normalmente, conflitos validados no servidor

### Riscos
- Touch UX é tricky — testar em iOS/Android
- SortableJS multi-grid (drag entre dia 1 e dia 5) requer configuração extra
- Acessibilidade: providenciar fallback teclado (Tab + Enter para "agarrar", arrows para mover)
- Bundle JS aumenta ~30KB (não-crítico)

### Notas de implementação
_(preencher durante/após implementação)_

---

## Fase 4 — Sugestões automáticas (IA / heurísticas)

### Objectivo
Sistema sugere/avisa sobre **lacunas** e **má distribuição** no horário, e pode **propor um horário inicial** equilibrado.

### Sub-features (do mais simples ao mais inteligente)

#### 4.1 — Detector de lacunas
- Identifica **furos** no horário (slots vazios entre slots ocupados no mesmo dia) → avisa
- Identifica **atribuições não escaladas** (atribuições sem nenhum slot no horário)
- Identifica **carga horária semanal** vs. `disciplina.carga_horaria_semanal` (config) → assinala quando difere

#### 4.2 — Análise de distribuição
- Detectar **disciplinas pesadas** (Mat, Port) concentradas num só dia → sugerir espalhar
- Detectar **prof. sobrecarregado** (>X tempos consecutivos) → avisar
- Detectar **disciplina + prof. em horas más** (ex: Mat no 8º tempo de 6ª) → assinalar

#### 4.3 — Auto-gerador inicial (greedy)
- Algoritmo guloso simples:
  1. Para cada atribuição, calcular `slots_alvo = disciplina.carga_horaria_semanal`
  2. Distribuir nos dias, dando prioridade a não pôr a mesma disciplina 2× seguido
  3. Evitar conflitos professor (já tem aula esse slot) e turma
  4. Apresentar como **proposta** que o utilizador aceita/edita antes de gravar

#### 4.4 — (Futuro) Solver real
- Constraint solver (CSP) com biblioteca PHP ou chamada à OR-Tools via micro-serviço
- Considera: pesos por dia, manhãs vs tardes, disciplinas que requerem dias alternados, etc.
- **Não fazer no MVP**, deixar para v2

### Plano técnico (4.1 + 4.2)
- Novo service `App\Services\HorarioAnalyser`
  - `lacunas(Turma|Professor $entity): Collection<Issue>`
  - `cargaHoraria(Atribuicao $a): array{esperada, actual}`
  - `distribuicaoDisciplina(...)`
- View bulk editor mostra **painel lateral "Diagnóstico"** com badges:
  - 🔴 3 furos · 🟡 2 disciplinas em excesso · 🟢 Carga horária OK
- Cada issue tem link "Ver na grelha" que destaca a célula

### Plano técnico (4.3 — auto-gerador)
- `App\Services\HorarioGenerator`
- Action `POST /horarios/turma/{turma}/auto-generate` (só direcção)
- Devolve JSON com proposta → o JS popula a grelha sem gravar
- Botão "Aplicar proposta e gravar" submete

### Tarefas
- [ ] Service `HorarioAnalyser` com testes unitários
- [ ] Painel "Diagnóstico" no bulk editor
- [ ] Service `HorarioGenerator` (greedy)
- [ ] Botão "Sugerir horário inicial"
- [ ] Modal de confirmação antes de aplicar
- [ ] Documentar pesos e regras heurísticas em `config/escola.php`
- [ ] i18n + testes feature

### Critérios de aceitação
- ✓ Editor mostra "0 furos" para horário sem lacunas
- ✓ Adicionar 1 furo → diagnóstico mostra "1 furo" com link para a célula
- ✓ Auto-gerar para turma vazia produz horário sem conflitos professor/turma
- ✓ Auto-gerar para turma com horário existente pergunta se quer sobrescrever

### Riscos
- Heurísticas mal calibradas geram propostas ruins → ter botão "Não obrigado, faço manual"
- Performance do greedy em turmas grandes (>10 atribuições) — provavelmente OK, mas profilar
- Definir o "óptimo" é subjectivo → começar simples e iterar com feedback

### Notas de implementação
_(preencher durante/após implementação)_

---

## Dependências cruzadas

```
        Fase 1 (Copy-paste)
            ↓
        Fase 2 (Bulk professor)  ← reusa Alpine state pattern
            ↓
        Fase 3 (Drag & drop)     ← reusa grelha e validação cliente
            ↓
        Fase 4 (Sugestões IA)    ← consome o estado do editor
```

Cada fase é "shippable" individualmente — não há bloqueios técnicos rígidos.

---

## Critérios transversais (todas as fases)

- 📐 **Design system**: usar `<x-btn>`, `<x-card>`, classes semânticas; nada de CSS inline novo
- 🌍 **i18n**: todas as strings via `__()`, com chaves em `pt.json` e `en.json`
- 🛡️ **Permissões**: apenas direcção (`director_geral|director_pedagogico|secretario`) pode gravar; professores e encarregados consultam
- 🧪 **Smoke tests**: cada fase termina com curl test das rotas principais (200 OK) e verificação de pelo menos 1 conflito detectado
- 📋 **Atualizar `DESIGN_SYSTEM.md`** se introduzir componente novo
- ⚡ **Performance**: queries `with()` para evitar N+1

---

## Métricas de sucesso

| Métrica | Hoje | Após Fase 1 | Após Fase 2 | Após Fase 3 | Após Fase 4 |
|---|---|---|---|---|---|
| Cliques para horário completo (40 slots) | ~120 | ~30 | ~30 | ~25 | ~5 |
| Erros de horário detectados pelo sistema | Conflitos prof/turma | + furos | + distribuição | + visual | + proposta automática |
| Tempo médio para preencher horário de 1 turma | ~10 min | ~3 min | ~3 min | ~2 min | ~30 seg |

---

## Decisões em aberto

1. **Fase 2 (bulk professor)**: o clean-slate apaga **só** os horários do professor, ou apaga todos os slots de todas as turmas onde ele lecciona? Recomendo: só os do professor — mais previsível.
2. **Fase 3 (drag & drop)**: o "modo visual" substitui ou coexiste com o "modo formulário"? Recomendo: coexistir com toggle (alguns utilizadores podem preferir o teclado).
3. **Fase 4 (auto-gerar)**: regras de distribuição (manhãs vs. tardes, dias preferidos por disciplina, etc.) ficam em `config/escola.php` ou são editáveis na UI? MVP: em config; v2: UI.

---

## Próximo passo

Decidir qual fase começar. Recomendação: **Fase 1 (Copy-paste)** — quick win, baixo risco, demonstra valor imediato e ensina os utilizadores a usar atalhos no editor.

---

## Changelog

> Cada entrada: **DATA — TÍTULO**, seguido de bullets curtos.
> Adicionar no topo (entrada mais recente em cima).

### 2026-05-15 — Fase 1 (Copy-paste) ✅ Concluída
- **Alpine state** completo no editor bulk de turma — todos os slots reactivos
- **Acções por coluna** (cabeçalho dos dias): Copiar · Colar · Aplicar a todos os dias · Limpar
- **Acções por linha** (cabeçalho dos tempos): Copiar · Colar · Limpar
- **Toolbar global**: estado do clipboard + "Limpar tudo" (com confirmação)
- **Clipboard persistente** em `localStorage.gestschool_horario_clipboard` com TTL 24h
- **Contador** ao vivo "X / Y slots preenchidos"
- 16 chaves novas em `pt.json` e `en.json`
- Smoke test: `/horarios/turma/1/bulk` retorna 200, todas as classes/acções renderizam, x-model bindings correctos
- Próximo: Fase 2 (Bulk editor por professor)

### 2026-05-15 — Roadmap criado
- Documento inicial com 4 fases planeadas
- Baseline registado: bulk turma, conflitos, PDFs
- Estados todos a ⬜ Pendente

<!-- TEMPLATE — copiar quando iniciar/terminar uma fase:

### YYYY-MM-DD — Fase N — Título (status)
- O que mudou (em 2-3 bullets)
- Decisões diferentes do plano (se houver)
- Commits/PRs relacionados (opcional)
- Próximo: …

-->

