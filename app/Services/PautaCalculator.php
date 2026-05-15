<?php

namespace App\Services;

/**
 * Lógica de cálculo de médias e situação final.
 *
 * Recebe pesos dos trimestres configuráveis. Mantém-se puro: não toca em models,
 * apenas opera sobre arrays de valores numéricos.
 */
class PautaCalculator
{
    /**
     * @var array<int> pesos por trimestre (numero => peso)
     */
    public array $pesos;

    public int $notaMinima;
    public int $maxNegativasRecurso;

    public function __construct(array $pesos = null, int $notaMinima = null, int $maxRecurso = null)
    {
        $this->pesos = $pesos ?: config('escola.pesos_trimestres', [1, 1, 1]);
        $this->notaMinima = $notaMinima ?? config('escola.nota_minima_aprovacao', 10);
        $this->maxNegativasRecurso = $maxRecurso ?? config('escola.max_negativas_recurso', 2);
    }

    /**
     * Média de UM trimestre — soma ponderada das avaliações desse trimestre.
     *
     * @param array $itens [['valor' => 15, 'peso' => 2], …]
     */
    public function mediaTrimestre(array $itens): ?float
    {
        $somaPond = 0;
        $somaPesos = 0;
        foreach ($itens as $i) {
            $valor = $i['valor'] ?? null;
            if ($valor === null || $valor === '') continue;
            $peso = (float) ($i['peso'] ?? 1);
            $somaPond += (float) $valor * $peso;
            $somaPesos += $peso;
        }
        return $somaPesos > 0 ? round($somaPond / $somaPesos, 2) : null;
    }

    /**
     * Média anual de UMA disciplina — combinação ponderada das médias trimestrais.
     *
     * @param array<int, float|null> $medias [1 => 12.5, 2 => 14, 3 => 13.2]
     */
    public function mediaAnual(array $medias): ?float
    {
        $somaPond = 0;
        $somaPesos = 0;
        foreach ([1, 2, 3] as $t) {
            $valor = $medias[$t] ?? null;
            $peso = $this->pesos[$t - 1] ?? 1;
            if ($valor === null) continue;
            $somaPond += $valor * $peso;
            $somaPesos += $peso;
        }
        return $somaPesos > 0 ? round($somaPond / $somaPesos, 2) : null;
    }

    /**
     * Média geral do aluno — média simples das médias anuais de todas as disciplinas.
     *
     * @param array<float> $mediasDisciplinas
     */
    public function mediaGeral(array $mediasDisciplinas): ?float
    {
        $vals = array_filter($mediasDisciplinas, fn ($v) => $v !== null);
        if (empty($vals)) return null;
        return round(array_sum($vals) / count($vals), 2);
    }

    /**
     * Situação final do aluno baseado nas médias anuais.
     *
     * @param array<float|null> $mediasAnuais
     * @return 'aprovado'|'recurso'|'reprovado'|'em_curso'
     */
    public function situacao(array $mediasAnuais): string
    {
        $vals = array_filter($mediasAnuais, fn ($v) => $v !== null);
        if (count($vals) < count($mediasAnuais)) {
            // Há disciplinas sem nota — sem dados suficientes
            // Trata como em curso (não decidir ainda)
            return 'em_curso';
        }

        $negativas = count(array_filter($vals, fn ($v) => $v < $this->notaMinima));

        if ($negativas === 0) return 'aprovado';
        if ($negativas <= $this->maxNegativasRecurso) return 'recurso';
        return 'reprovado';
    }

    /**
     * Descrição da fórmula em uso (para mostrar no rodapé das pautas).
     */
    public function formulaDescricao(): string
    {
        $p = $this->pesos;
        if ($p === [1, 1, 1]) {
            return '(MT1 + MT2 + MT3) / 3 — média simples';
        }
        return sprintf(
            '(MT1×%d + MT2×%d + MT3×%d) / %d — média ponderada',
            $p[0], $p[1], $p[2], array_sum($p)
        );
    }
}
