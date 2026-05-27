<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role->name === 'admin';
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('services', 'name')->ignore($this->service)],
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Название услуги обязательно.',
            'name.unique' => 'Услуга с таким названием уже существует.',
            'price.required' => 'Укажите цену услуги.',
            'price.min' => 'Цена не может быть отрицательной.',
        ];
    }
}