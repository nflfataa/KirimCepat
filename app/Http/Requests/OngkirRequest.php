<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\NormalizesPackageInput;
use Illuminate\Foundation\Http\FormRequest;

class OngkirRequest extends FormRequest
{
    use NormalizesPackageInput;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->normalizePackageInput();
    }

    public function rules(): array
    {
        return $this->packageRules();
    }

    public function messages(): array
    {
        return $this->packageMessages();
    }

    public function attributes(): array
    {
        return $this->packageAttributes();
    }
}
