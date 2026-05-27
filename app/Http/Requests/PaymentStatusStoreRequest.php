<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentStatusStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role->name === 'admin';
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100|unique:payment_statuses,name',
            'color' => 'nullable|string|max:7|regex:/^#[a-fA-F0-9]{6}$/',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Название статуса платежа обязательно.',
            'name.unique' => 'Статус с таким названием уже существует.',
            'color.regex' => 'Цвет должен быть в формате HEX (#RRGGBB).',
        ];
    }
}