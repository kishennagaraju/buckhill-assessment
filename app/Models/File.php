<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    public $exists = true;

    protected $table = "media";

    protected $fillable = [
        'uuid',
        'name',
        'path',
        'file_name',
        'mime_type',
        'size'
    ];

    protected $hidden = [
        'id'
    ];
}
