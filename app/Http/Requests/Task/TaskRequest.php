<?php

namespace App\Http\Requests\Task;

use App\DataTransferObjects\TaskDTO;
use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{

    public function toDTO(): TaskDTO
    {
        return new TaskDTO($this->all());
    }
}
