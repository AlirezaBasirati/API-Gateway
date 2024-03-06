<?php

namespace App\Services\RouterService\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $title
 * @property string $slug
 * @property string $base_url
 * @property integer $status
 */
class Microservice extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'url',
    ];
}
