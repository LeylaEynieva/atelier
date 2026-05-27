<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $user = auth()->user();
        
        $rules = [
            'employee_id' => 'nullable|exists:users,id',
            'order_status_id' => 'required|exists:order_statuses,id',
            'order_date' => 'required|date',
            'deadline' => 'nullable|date|after_or_equal:order_date',
            'description' => 'nullable|string|max:1000',
            'services' => 'nullable|array',
            'services.*.selected' => 'nullable|in:1',
            'services.*.quantity' => 'nullable|integer|min:1',
            'services.*.price' => 'nullable|numeric|min:0',
            'materials' => 'nullable|array',
            'materials.*.selected' => 'nullable|in:1',
            'materials.*.quantity' => 'nullable|integer|min:1',
            'materials.*.price' => 'nullable|numeric|min:0',
        ];
        
        if (in_array($user->role->name, ['admin', 'employee'])) {
            $rules['client_id'] = 'required|exists:users,id';
        }
        
        return $rules;
    }

    public function messages(): array
    {
        return [
            'client_id.required' => 'Необходимо выбрать клиента.',
            'client_id.exists' => 'Выбранный клиент не существует.',
            'order_status_id.required' => 'Необходимо указать статус заказа.',
            'order_status_id.exists' => 'Выбранный статус не существует.',
            'order_date.required' => 'Укажите дату заказа.',
            'order_date.date' => 'Дата заказа должна быть корректной датой.',
            'deadline.after_or_equal' => 'Срок выполнения не может быть раньше даты заказа.',
            'services.*.quantity.min' => 'Количество услуги должно быть не менее 1.',
            'services.*.price.min' => 'Цена услуги не может быть отрицательной.',
            'materials.*.quantity.min' => 'Количество материала должно быть не менее 1.',
            'materials.*.price.min' => 'Цена материала не может быть отрицательной.',
        ];
    }
}