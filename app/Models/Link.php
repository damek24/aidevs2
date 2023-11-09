<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $uuid
 * @property string $title
 * @property string $info
 * @property string $url
 * @property string $date
 */
class Link extends Model
{
    use HasUuids;

    protected $primaryKey = 'uuid';
}
