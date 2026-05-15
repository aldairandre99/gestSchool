<?php

namespace App\Http\Controllers;

use App\Models\AnoLectivo;
use App\Models\Classe;
use App\Models\Evento;
use App\Models\Turma;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EventoController extends Controller
{
    public function index(Request $request)
    {
        $ano = $request->integer('ano') ?: now()->year;
        $mes = max(1, min(12, $request->integer('mes') ?: now()->month));

        $inicio = Carbon::create($ano, $mes, 1)->startOfMonth();
        $fim = $inicio->copy()->endOfMonth();

        // Para a grelha 7×6 — começa no domingo (ou segunda?) antes do dia 1
        $gridStart = $inicio->copy()->startOfWeek(Carbon::MONDAY);
        $gridEnd = $gridStart->copy()->addDays(41);  // 6 semanas

        $eventos = Evento::with(['classe', 'turma.classe', 'autor'])
            ->visivelPara($request->user())
            ->noIntervalo($gridStart->toDateString(), $gridEnd->toDateString())
            ->orderBy('data_inicio')->get();

        $porDia = [];
        foreach ($eventos as $ev) {
            $cursor = $ev->data_inicio->copy();
            $end = $ev->data_fim_efectiva;
            while ($cursor->lte($end)) {
                $porDia[$cursor->toDateString()][] = $ev;
                $cursor->addDay();
            }
        }

        // Eventos do mês (lista cronológica)
        $mesEventos = $eventos->filter(function ($e) use ($inicio, $fim) {
            return $e->data_inicio->between($inicio, $fim)
                || ($e->data_fim && $e->data_fim->between($inicio, $fim))
                || ($e->data_inicio->lt($inicio) && ($e->data_fim ?? $e->data_inicio)->gte($inicio));
        })->sortBy('data_inicio');

        // Grelha 6×7 pré-calculada (evita @for no Blade que confunde o parser)
        $semanas = [];
        $cursor = $gridStart->copy();
        for ($w = 0; $w < 6; $w++) {
            $dias = [];
            for ($d = 0; $d < 7; $d++) {
                $dias[] = $cursor->copy();
                $cursor->addDay();
            }
            $semanas[] = $dias;
        }

        $podeGerir = $request->user()->hasAnyRole(['director_geral', 'director_pedagogico', 'secretario']);

        return view('eventos.index', compact(
            'inicio', 'fim', 'semanas',
            'eventos', 'porDia', 'mesEventos',
            'ano', 'mes', 'podeGerir'
        ));
    }

    public function create(Request $request)
    {
        return view('eventos.create', $this->options());
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['criado_por'] = $request->user()->id;
        Evento::create($data);
        return redirect()->route('eventos.index')->with('status', __('Resource created successfully.'));
    }

    public function show(Evento $evento)
    {
        $evento->load(['anoLectivo', 'classe', 'turma.classe', 'autor']);
        return view('eventos.show', compact('evento'));
    }

    public function edit(Evento $evento)
    {
        return view('eventos.edit', array_merge(['evento' => $evento], $this->options()));
    }

    public function update(Request $request, Evento $evento)
    {
        $evento->update($this->validateData($request, $evento));
        return redirect()->route('eventos.index')->with('status', __('Resource updated successfully.'));
    }

    public function destroy(Evento $evento)
    {
        $evento->delete();
        return redirect()->route('eventos.index')->with('status', __('Resource deleted successfully.'));
    }

    protected function options(): array
    {
        return [
            'anos' => AnoLectivo::orderBy('codigo', 'desc')->get(),
            'classes' => Classe::orderBy('ordem')->get(),
            'turmas' => Turma::with(['classe', 'curso', 'anoLectivo'])->get(),
            'tipos' => config('escola.tipos_evento'),
        ];
    }

    protected function validateData(Request $request, ?Evento $e = null): array
    {
        return $request->validate([
            'ano_lectivo_id' => ['required', Rule::exists('anos_lectivos', 'id')],
            'titulo' => ['required', 'string', 'max:200'],
            'descricao' => ['nullable', 'string'],
            'tipo' => ['required', Rule::in(array_keys(config('escola.tipos_evento')))],
            'data_inicio' => ['required', 'date'],
            'data_fim' => ['nullable', 'date', 'after_or_equal:data_inicio'],
            'hora_inicio' => ['nullable', 'date_format:H:i'],
            'hora_fim' => ['nullable', 'date_format:H:i', 'after:hora_inicio'],
            'dia_inteiro' => ['nullable', 'boolean'],
            'cor' => ['nullable', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'classe_id' => ['nullable', Rule::exists('classes', 'id')],
            'turma_id' => ['nullable', Rule::exists('turmas', 'id')],
        ]);
    }
}
