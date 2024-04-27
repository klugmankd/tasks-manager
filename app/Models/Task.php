<?php

namespace App\Models;

use App\Traits\HasDatesSerialization;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Reliese\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $author_id
 * @property int $assignee_id
 * @property string $name
 * @property string $status
 * @property string $description
 * @property Carbon $deadline_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property User $author
 * @property User $assignee
 */
class Task extends Model
{
    use HasFactory, HasDatesSerialization;

    protected $fillable = [
        'author_id',
        'assignee_id',
        'name',
        'status',
        'description',
        'deadline_at',
    ];

    protected array $dates = [
        'created_at',
        'updated_at',
        'deadline_at',
    ];

    protected function casts(): array
    {
        return [
            'author_id'   => 'int',
            'assignee_id' => 'int',
            'name'        => 'string',
            'status'      => 'string',
            'description' => 'string',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }
}
