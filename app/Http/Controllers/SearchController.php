<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Classe;
use App\Models\Comunicado;
use App\Models\Curso;
use App\Models\Disciplina;
use App\Models\Encarregado;
use App\Models\Funcionario;
use App\Models\Matricula;
use App\Models\Professor;
use App\Models\Turma;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    private const LIMIT_PER_GROUP = 5;
    private const MIN_LENGTH = 2;

    public function index(Request $request): JsonResponse
    {
        $term = trim((string) $request->query('q', ''));

        if (mb_strlen($term) < self::MIN_LENGTH) {
            return response()->json(['groups' => []]);
        }

        $user = $request->user();
        $isAdmin = $user->hasAnyRole(['director_geral', 'director_pedagogico', 'secretario']);
        $isProf = $user->hasAnyRole(['professor', 'professor_assistente']);
        $isEnc = $user->hasRole('encarregado');

        $like = '%'.$term.'%';
        $groups = [];

        if ($isAdmin) {
            $groups[] = $this->alunos($like);
            $groups[] = $this->professores($like);
            $groups[] = $this->encarregados($like);
            $groups[] = $this->funcionarios($like);
            $groups[] = $this->turmas($like);
            $groups[] = $this->disciplinas($like);
            $groups[] = $this->classes($like);
            $groups[] = $this->cursos($like);
            $groups[] = $this->matriculas($like);
            $groups[] = $this->comunicados($like);
        } elseif ($isProf) {
            $groups[] = $this->alunos($like);
            $groups[] = $this->turmas($like);
            $groups[] = $this->disciplinas($like);
            $groups[] = $this->comunicados($like);
        } else {
            $groups[] = $this->comunicados($like);
        }

        $groups = array_values(array_filter($groups, fn ($g) => ! empty($g['results'])));

        return response()->json(['groups' => $groups]);
    }

    private function alunos(string $like): array
    {
        $rows = Aluno::query()
            ->join('users', 'users.id', '=', 'alunos.user_id')
            ->where(function ($q) use ($like) {
                $q->where('users.name', 'like', $like)
                    ->orWhere('alunos.bi', 'like', $like)
                    ->orWhere('alunos.numero_processo', 'like', $like);
            })
            ->select('alunos.id', 'users.name', 'alunos.numero_processo', 'alunos.classe', 'alunos.turma')
            ->orderBy('users.name')
            ->limit(self::LIMIT_PER_GROUP)
            ->get();

        return [
            'type' => 'alunos',
            'label' => __('Students'),
            'icon' => 'graduation-cap',
            'results' => $rows->map(fn ($r) => [
                'title' => $r->name,
                'subtitle' => $this->joinParts(['Nº '.$r->numero_processo, $r->classe, $r->turma]),
                'url' => route('alunos.show', $r->id),
            ])->all(),
        ];
    }

    private function professores(string $like): array
    {
        $rows = Professor::query()
            ->join('users', 'users.id', '=', 'professores.user_id')
            ->where(function ($q) use ($like) {
                $q->where('users.name', 'like', $like)
                    ->orWhere('professores.bi', 'like', $like)
                    ->orWhere('professores.numero_professor', 'like', $like)
                    ->orWhere('professores.especialidade', 'like', $like);
            })
            ->select('professores.id', 'users.name', 'professores.numero_professor', 'professores.especialidade')
            ->orderBy('users.name')
            ->limit(self::LIMIT_PER_GROUP)
            ->get();

        return [
            'type' => 'professores',
            'label' => __('Teachers'),
            'icon' => 'user-cog',
            'results' => $rows->map(fn ($r) => [
                'title' => $r->name,
                'subtitle' => $this->joinParts([$r->numero_professor, $r->especialidade]),
                'url' => route('professores.show', $r->id),
            ])->all(),
        ];
    }

    private function encarregados(string $like): array
    {
        $rows = Encarregado::query()
            ->join('users', 'users.id', '=', 'encarregados.user_id')
            ->where(function ($q) use ($like) {
                $q->where('users.name', 'like', $like)
                    ->orWhere('encarregados.bi', 'like', $like)
                    ->orWhere('encarregados.profissao', 'like', $like);
            })
            ->select('encarregados.id', 'users.name', 'encarregados.bi', 'encarregados.profissao')
            ->orderBy('users.name')
            ->limit(self::LIMIT_PER_GROUP)
            ->get();

        return [
            'type' => 'encarregados',
            'label' => __('Guardians'),
            'icon' => 'user-check',
            'results' => $rows->map(fn ($r) => [
                'title' => $r->name,
                'subtitle' => $this->joinParts([$r->bi ? 'BI '.$r->bi : null, $r->profissao]),
                'url' => route('encarregados.show', $r->id),
            ])->all(),
        ];
    }

    private function funcionarios(string $like): array
    {
        $rows = Funcionario::query()
            ->join('users', 'users.id', '=', 'funcionarios.user_id')
            ->where(function ($q) use ($like) {
                $q->where('users.name', 'like', $like)
                    ->orWhere('funcionarios.bi', 'like', $like)
                    ->orWhere('funcionarios.numero_funcionario', 'like', $like)
                    ->orWhere('funcionarios.cargo', 'like', $like)
                    ->orWhere('funcionarios.departamento', 'like', $like);
            })
            ->select('funcionarios.id', 'users.name', 'funcionarios.cargo', 'funcionarios.departamento')
            ->orderBy('users.name')
            ->limit(self::LIMIT_PER_GROUP)
            ->get();

        return [
            'type' => 'funcionarios',
            'label' => __('Staff'),
            'icon' => 'briefcase',
            'results' => $rows->map(fn ($r) => [
                'title' => $r->name,
                'subtitle' => $this->joinParts([$r->cargo, $r->departamento]),
                'url' => route('funcionarios.show', $r->id),
            ])->all(),
        ];
    }

    private function turmas(string $like): array
    {
        $rows = Turma::query()
            ->with(['classe:id,nome', 'curso:id,sigla', 'anoLectivo:id,codigo'])
            ->where(function ($q) use ($like) {
                $q->where('turmas.nome', 'like', $like)
                    ->orWhere('turmas.sala', 'like', $like);
            })
            ->orderByDesc('turmas.ano_lectivo_id')
            ->limit(self::LIMIT_PER_GROUP)
            ->get();

        return [
            'type' => 'turmas',
            'label' => __('Class Groups'),
            'icon' => 'users-round',
            'results' => $rows->map(fn ($t) => [
                'title' => $t->nome_completo,
                'subtitle' => $this->joinParts([
                    $t->anoLectivo?->codigo,
                    $t->sala ? __('Room').' '.$t->sala : null,
                    $t->turno,
                ]),
                'url' => route('turmas.show', $t->id),
            ])->all(),
        ];
    }

    private function disciplinas(string $like): array
    {
        $rows = Disciplina::query()
            ->where(function ($q) use ($like) {
                $q->where('nome', 'like', $like)
                    ->orWhere('sigla', 'like', $like);
            })
            ->orderBy('nome')
            ->limit(self::LIMIT_PER_GROUP)
            ->get();

        return [
            'type' => 'disciplinas',
            'label' => __('Subjects List'),
            'icon' => 'book-open',
            'results' => $rows->map(fn ($d) => [
                'title' => $d->nome,
                'subtitle' => $d->sigla,
                'url' => route('disciplinas.show', $d->id),
            ])->all(),
        ];
    }

    private function classes(string $like): array
    {
        $rows = Classe::query()
            ->where('nome', 'like', $like)
            ->orderBy('ordem')
            ->limit(self::LIMIT_PER_GROUP)
            ->get();

        return [
            'type' => 'classes',
            'label' => __('Classes'),
            'icon' => 'layers',
            'results' => $rows->map(fn ($c) => [
                'title' => $c->nome,
                'subtitle' => $c->nivel ? __(str_replace('_', ' ', $c->nivel)) : null,
                'url' => route('classes.show', $c->id),
            ])->all(),
        ];
    }

    private function cursos(string $like): array
    {
        $rows = Curso::query()
            ->where(function ($q) use ($like) {
                $q->where('nome', 'like', $like)
                    ->orWhere('sigla', 'like', $like);
            })
            ->orderBy('nome')
            ->limit(self::LIMIT_PER_GROUP)
            ->get();

        return [
            'type' => 'cursos',
            'label' => __('Courses'),
            'icon' => 'award',
            'results' => $rows->map(fn ($c) => [
                'title' => $c->nome,
                'subtitle' => $c->sigla,
                'url' => route('cursos.show', $c->id),
            ])->all(),
        ];
    }

    private function matriculas(string $like): array
    {
        $rows = Matricula::query()
            ->join('alunos', 'alunos.id', '=', 'matriculas.aluno_id')
            ->join('users', 'users.id', '=', 'alunos.user_id')
            ->leftJoin('turmas', 'turmas.id', '=', 'matriculas.turma_id')
            ->leftJoin('classes', 'classes.id', '=', 'turmas.classe_id')
            ->leftJoin('anos_lectivos', 'anos_lectivos.id', '=', 'matriculas.ano_lectivo_id')
            ->where(function ($q) use ($like) {
                $q->where('matriculas.numero_matricula', 'like', $like)
                    ->orWhere('users.name', 'like', $like);
            })
            ->select(
                'matriculas.id',
                'matriculas.numero_matricula',
                'matriculas.estado',
                'users.name as aluno_nome',
                'classes.nome as classe_nome',
                'turmas.nome as turma_nome',
                'anos_lectivos.codigo as ano_codigo'
            )
            ->orderByDesc('matriculas.created_at')
            ->limit(self::LIMIT_PER_GROUP)
            ->get();

        return [
            'type' => 'matriculas',
            'label' => __('Enrollments'),
            'icon' => 'file-text',
            'results' => $rows->map(fn ($m) => [
                'title' => $m->numero_matricula.' · '.$m->aluno_nome,
                'subtitle' => $this->joinParts([
                    $m->classe_nome.($m->turma_nome ? ' '.$m->turma_nome : ''),
                    $m->ano_codigo,
                    $m->estado ? __(ucfirst($m->estado)) : null,
                ]),
                'url' => route('matriculas.show', $m->id),
            ])->all(),
        ];
    }

    private function comunicados(string $like): array
    {
        $rows = Comunicado::query()
            ->where('titulo', 'like', $like)
            ->orderByDesc('publicado_em')
            ->limit(self::LIMIT_PER_GROUP)
            ->get();

        return [
            'type' => 'comunicados',
            'label' => __('Announcements'),
            'icon' => 'megaphone',
            'results' => $rows->map(fn ($c) => [
                'title' => $c->titulo,
                'subtitle' => $c->publicado_em?->format('d/m/Y'),
                'url' => route('comunicados.show', $c->id),
            ])->all(),
        ];
    }

    private function joinParts(array $parts): ?string
    {
        $filtered = array_filter(array_map('trim', array_filter($parts)));

        return $filtered ? implode(' · ', $filtered) : null;
    }
}
