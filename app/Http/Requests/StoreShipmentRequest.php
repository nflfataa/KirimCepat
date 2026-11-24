<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\NormalizesPackageInput;
use Illuminate\Foundation\Http\FormRequest;

class StoreShipmentRequest extends FormRequest
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
        return array_merge($this->packageRules(), [
            'sender_name' => ['required', 'string', 'max:100'],
            'sender_phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+()\-\s]{7,20}$/'],
            'sender_address' => ['required', 'string', 'max:500'],
            'receiver_name' => ['required', 'string', 'max:100'],
            'receiver_phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+()\-\s]{7,20}$/'],
            'receiver_address' => ['required', 'string', 'max:500'],
        ]);
    }

    public function messages(): array
    {
        return array_merge($this->packageMessages(), [
            'sender_name.required' => 'Nama pengirim wajib diisi.',
            'sender_phone.required' => 'Telepon pengirim wajib diisi.',
            'sender_phone.regex' => 'Telepon pengirim tidak valid. Gunakan angka, spasi, tanda hubung, atau awalan +.',
            'sender_address.required' => 'Alamat pengirim wajib diisi.',
            'receiver_name.required' => 'Nama penerima wajib diisi.',
            'receiver_phone.required' => 'Telepon penerima wajib diisi.',
            'receiver_phone.regex' => 'Telepon penerima tidak valid. Gunakan angka, spasi, tanda hubung, atau awalan +.',
            'receiver_address.required' => 'Alamat penerima wajib diisi.',
            'sender_name.max' => 'Nama pengirim maksimal 100 karakter.',
            'sender_phone.max' => 'Telepon pengirim maksimal 20 karakter.',
            'sender_address.max' => 'Alamat pengirim maksimal 500 karakter.',
            'receiver_name.max' => 'Nama penerima maksimal 100 karakter.',
            'receiver_phone.max' => 'Telepon penerima maksimal 20 karakter.',
            'receiver_address.max' => 'Alamat penerima maksimal 500 karakter.',
        ]);
    }

    public function attributes(): array
    {
        return array_merge($this->packageAttributes(), [
            'sender_name' => 'nama pengirim',
            'sender_phone' => 'telepon pengirim',
            'sender_address' => 'alamat pengirim',
            'receiver_name' => 'nama penerima',
            'receiver_phone' => 'telepon penerima',
            'receiver_address' => 'alamat penerima',
        ]);
    }
}
