<?php

namespace App\Models;

use App\Models\UploadFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasOne;


class Division extends Model
{
    use SoftDeletes, HasUuids;
    protected $table = 'divisions';
    protected $primaryKey = 'id_division';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'description',
        'status',
        'logo_url',
    ];

    public function logo_url(): HasOne
    {
        return $this->hasOne(UploadFile::class);
    }
}
