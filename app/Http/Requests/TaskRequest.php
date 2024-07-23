<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => auth()->id(),
        ]);
    }

    public function rules(): array
    {
        return [
            'title' => ['required'],
            'description' => ['nullable'],
            'status' => ['required'],
            'due_date' => ['nullable', 'date'],
            'user_id' => ['required', 'exists:users,id'],
//            'task_list_id' => ['required', 'exists:task_lists'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
