<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class TaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_completed' => ['boolean'],
        ];
    }

    /**
     * Вот эта часть не оптимальная, так как с реализацией пошёл на поводу у сгенерированных методов
     * в контроллере. Если бы использовал Route Model Binding то избежали бы двойного запроса
     *
     * @param Validator $validator
     * @return void
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $taskId = $this->route('task');
            $task = $taskId ? Task::find($taskId) : null;

            // Правило нужно только для обновления существующей задачи
            if (!$task) {
                return;
            }

            // Если задача уже завершена — title менять нельзя
            if ($task->is_completed === true && $this->has('title') && $this->input('title') !== $task->title) {
                $validator->errors()->add('title', 'Нельзя изменить title у завершённой задачи.');
            }
        });
    }
}
