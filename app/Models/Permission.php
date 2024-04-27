<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Reliese\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property string $name
 */
class Permission extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
    ];
}
