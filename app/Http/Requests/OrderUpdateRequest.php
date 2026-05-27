<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'client_id' => 'required|exists:users,id',
            'employee_id' => 'nullable|exists:users,id',
            'order_status_id' => 'required|exists:order_statuses,id',
            'order_date' => 'required|date',
            'deadline' => 'nullable|date|after_or_equal:order_date',
            'completed_at' => 'nullable|date|after_or_equal:order_date',
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
    }

    public function messages(): array
    {
        return [
            'client_id.required' => 'Необходимо выбрать клиента.',
            'client_id.exists' => 'Выбранный клиент не существует.',
            'order_status_id.required' => 'Необходимо указать статус заказа.',
            'order_date.required' => 'Укажите дату заказа.',
            'deadline.after_or_equal' => 'Срок выполнения не может быть раньше даты заказа.',
            'completed_at.after_or_equal' => 'Дата завершения не может быть раньше даты заказа.',
        ];
    }
}