<?php

namespace App\Http\Controllers;

use App\Models\Matricula;
use App\Services\BoletimService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class BoletimController extends Controller
{
    public function __construct(private readonly BoletimService $service) {}

    public function show(Request $request, Matricula $matricula)
    {
        $this->service->ensureCanView($request->user(), $matricula);

        return view('boletim.show', $this->service->build($matricula));
    }

    public function pdf(Request $request, Matricula $matricula)
    {
        $this->service->ensureCanView($request->user(), $matricula);

        $data = $this->service->build($matricula);
        $filename = sprintf(
            'boletim-%s-%s.pdf',
            str($matricula->numero_matricula)->slug(),
            str($matricula->anoLectivo->codigo)->slug()
        );

        return Pdf::loadView('pdf.boletim.show', $data)
            ->setPaper('a4', 'portrait')
            ->download($filename);
    }
}
