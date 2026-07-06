<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class UploadFile extends Model
{
    use SoftDeletes, HasUuids;

    protected $table = 'upload_files';
    protected $primaryKey = 'id_file';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'path',
        'type',
        'size',
    ];
}
