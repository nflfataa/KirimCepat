<?php

namespace App\Http\Requests\Concerns;

trait NormalizesPackageInput
{
    protected function normalizePackageInput(): void
    {
        $normalized = [];

        foreach (['actual_weight', 'length', 'width', 'height'] as $field) {
            $value = $this->input($field);

            if (is_string($value)) {
                $normalized[$field] = str_replace(',', '.', trim($value));
            }
        }

        $this->merge($normalized);
    }

    protected function packageRules(): array
    {
        return [
            'zone_id' => ['required', 'integer', 'exists:zones,id'],
            'actual_weight' => ['required', 'numeric', 'gt:0', 'max:1000'],
            'length' => ['required', 'numeric', 'gt:0', 'max:1000'],
            'width' => ['required', 'numeric', 'gt:0', 'max:1000'],
            'height' => ['required', 'numeric', 'gt:0', 'max:1000'],
        ];
    }

    protected function packageMessages(): array
    {
        return [
            'zone_id.required' => 'Zona tujuan wajib dipilih.',
            'zone_id.exists' => 'Zona tujuan yang dipilih tidak tersedia.',
            'actual_weight.required' => 'Berat aktual wajib diisi.',
            'actual_weight.numeric' => 'Berat aktual harus berupa angka.',
            'actual_weight.gt' => 'Berat aktual harus lebih dari 0.',
            'actual_weight.max' => 'Berat aktual maksimal 1000 kg.',
            'length.required' => 'Panjang wajib diisi.',
            'length.numeric' => 'Panjang harus berupa angka.',
            'length.gt' => 'Panjang harus lebih dari 0.',
            'length.max' => 'Panjang maksimal 1000 cm.',
            'width.required' => 'Lebar wajib diisi.',
            'width.numeric' => 'Lebar harus berupa angka.',
            'width.gt' => 'Lebar harus lebih dari 0.',
            'width.max' => 'Lebar maksimal 1000 cm.',
            'height.required' => 'Tinggi wajib diisi.',
            'height.numeric' => 'Tinggi harus berupa angka.',
            'height.gt' => 'Tinggi harus lebih dari 0.',
            'height.max' => 'Tinggi maksimal 1000 cm.',
        ];
    }

    protected function packageAttributes(): array
    {
        return [
            'actual_weight' => 'berat aktual',
            'length' => 'panjang',
            'width' => 'lebar',
            'height' => 'tinggi',
        ];
    }
}
