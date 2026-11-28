<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TrackController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $input = $this->normalize((string) $request->query('resi', ''));

        if ($input === '') {
            return view('track.index');
        }

        if ($this->hasValidFormat($input)) {
            return redirect()->route('track.show', $input);
        }

        return view('track.not-found', [
            'resi' => $input,
            'invalidFormat' => true,
        ]);
    }

    public function show(string $resi): View
    {
        $resi = $this->normalize($resi);
        $shipment = $this->hasValidFormat($resi)
            ? Shipment::query()->with(['zone', 'histories.courier'])->where('tracking_number', $resi)->first()
            : null;

        if ($shipment === null) {
            return view('track.not-found', [
                'resi' => $resi,
                'invalidFormat' => ! $this->hasValidFormat($resi),
            ]);
        }

        return view('track.show', ['shipment' => $shipment]);
    }

    private function normalize(string $resi): string
    {
        return preg_replace('/\s+/', '', strtoupper(trim($resi)));
    }

    private function hasValidFormat(string $resi): bool
    {
        return (bool) preg_match('/^KC[0-9]{14}$/', $resi);
    }
}
