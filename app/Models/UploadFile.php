<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UploadFile extends Model
{
    protected $table = 'upload_files';
    protected $primaryKey = 'id_file';

    protected $fillable = [
        'name',
        'path',
        'type',
        'size',
    ];
}
