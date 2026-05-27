<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role->name === 'admin';
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:services,name',
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