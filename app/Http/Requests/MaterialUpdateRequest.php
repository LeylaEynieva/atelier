<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MaterialUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role->name === 'admin';
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('materials', 'name')->ignore($this->material)],
            'unit' => 'required|string|max:20',
            'price_per_unit' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Название материала обязательно.',
            'name.unique' => 'Материал с таким названием уже существует.',
            'unit.required' => 'Укажите единицу измерения.',
            'price_per_unit.required' => 'Укажите цену за единицу.',
            'price_per_unit.min' => 'Цена не может быть отрицательной.',
            'stock_quantity.min' => 'Количество не может быть отрицательным.',
        ];
    }
}