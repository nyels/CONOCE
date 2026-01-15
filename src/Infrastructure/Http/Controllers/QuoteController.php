<?php

namespace Src\Infrastructure\Http\Controllers;

use App\Http\Controllers\Controller; // Heredamos del controlador base de Laravel
use Illuminate\Http\JsonResponse;
use Src\Application\Quote\CreateQuoteAction;
use Src\Infrastructure\Http\Requests\StoreQuoteRequest;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Src\Domain\Quote\Models\Quote;

class QuoteController extends Controller
{
    public function __construct(
        private readonly CreateQuoteAction $createQuoteAction
    ) {}

    public function store(StoreQuoteRequest $request): JsonResponse
    {
        // 1. Ejecutamos la acción con los datos validados
        // Usamos el ID del usuario logueado (o 1 por defecto si probamos sin login)
        $agentId = Auth::id() ?? 1;

        $quote = $this->createQuoteAction->execute(
            $request->validated(),
            $agentId
        );

        // 2. Devolvemos respuesta JSON
        return response()->json([
            'message' => 'Cotización creada exitosamente',
            'quote_uuid' => $quote->uuid,
            'folio' => $quote->folio,
            'pdf_url' => url("/api/quotes/{$quote->uuid}/pdf") // Futura URL del PDF
        ], 201);
    }

    public function downloadPdf(string $uuid)
    {
        // Buscamos la cotización por UUID y cargamos las relaciones necesarias
        $quote = Quote::with(['customer', 'options.insurer'])
            ->where('uuid', $uuid)
            ->firstOrFail();

        // Generamos el PDF usando la vista que creamos
        $pdf = Pdf::loadView('pdf.quote', ['quote' => $quote]);

        // Lo mostramos en el navegador (stream) o lo descargamos (download)
        return $pdf->stream("Cotizacion-{$quote->folio}.pdf");
    }
}
