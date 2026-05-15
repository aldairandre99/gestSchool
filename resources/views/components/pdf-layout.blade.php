@props(['titulo' => null, 'subtitulo' => null])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>{{ $titulo ?? config('app.name') }}</title>
    <style>
        @page { margin: 1cm 1.2cm; }
        * { box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #001737;
            margin: 0;
            padding: 0;
        }
        .header { border-bottom: 2px solid #001737; padding-bottom: 8px; margin-bottom: 14px; }
        .header h1 { margin: 0; font-size: 14pt; color: #001737; }
        .header .meta { font-size: 9px; color: #76838f; margin-top: 4px; }
        .header .subtitle { font-size: 11pt; color: #76838f; margin-top: 2px; }

        .info-row { font-size: 9px; color: #76838f; margin-bottom: 8px; }
        .info-row strong { color: #001737; }

        table { width: 100%; border-collapse: collapse; }
        table.data { font-size: 9px; }
        table.data th, table.data td {
            border: 1px solid #999;
            padding: 4px 6px;
            text-align: center;
        }
        table.data thead { background: #f0f1f6; }
        table.data thead th { color: #001737; font-weight: 600; }
        table.data tbody td.name { text-align: left; font-weight: 600; color: #001737; }
        table.data tbody td.number { font-family: monospace; font-size: 8px; }

        .neg { color: #b91c1c; font-weight: 600; }
        .approved { color: #166534; font-weight: 600; }
        .second { color: #92400e; font-weight: 600; }
        .failed { color: #b91c1c; font-weight: 600; }
        .pending { color: #76838f; }

        .footer-note { margin-top: 14px; font-size: 8px; color: #76838f; line-height: 1.4; }
        .footer-note p { margin: 2px 0; }
        .footer-note strong { color: #001737; }

        .stat-grid { width: 100%; margin-bottom: 12px; }
        .stat-grid td { border: 1px solid #ccc; padding: 6px; text-align: center; width: 25%; }
        .stat-grid .label { font-size: 8px; color: #76838f; text-transform: uppercase; }
        .stat-grid .value { font-size: 14pt; font-weight: bold; }
        .stat-grid .ok td { background: #f0fdf4; }
        .stat-grid .warn td { background: #fffbeb; }
        .stat-grid .bad td { background: #fef2f2; }

        .sig-block { margin-top: 30px; padding-top: 10px; border-top: 1px solid #ccc; font-size: 9px; color: #76838f; display: table; width: 100%; }
        .sig-cell { display: table-cell; width: 50%; text-align: center; padding: 0 20px; }
        .sig-line { border-top: 1px solid #444; margin-top: 30px; padding-top: 4px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name', 'GestSchool') }}</h1>
        @isset($subtitulo)<div class="subtitle">{{ $subtitulo }}</div>@endisset
        <div class="meta">
            @isset($titulo)<strong>{{ $titulo }}</strong> · @endisset
            {{ __('Generated on') }} {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>

    {{ $slot }}
</body>
</html>
