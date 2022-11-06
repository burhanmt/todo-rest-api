<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Task
 *
 * @package App\Models\Task
 *
 * @property string $name
 * @property bool $is_done
 * @property int $user_id
 */
class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_done',
        'user_id',
    ];

    protected $casts = [
        'is_done' => 'boolean',
    ];
}
