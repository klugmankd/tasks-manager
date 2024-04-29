<?php

namespace App\DataTransferObjects;

/**
 * @property int    $assignee_id
 * @property string $name
 * @property string $status
 * @property string $description
 * @property string $deadline_at
 */
class TaskDTO
{

    private array $keys;

    public function __construct(array $data)
    {
        $this->keys = array_keys($data);
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function toRepositoryArray(): array
    {
        $fields = [];
        foreach ($this->keys as $key) {
            $fields[$key] = $this->{$key};
        }

        return $fields;
    }
}
