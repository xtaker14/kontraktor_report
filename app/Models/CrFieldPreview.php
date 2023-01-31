<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrFieldPreview extends Model
{
    use HasFactory;

    protected $table = 'cr_field_preview';
    public $fillable = [
        'table_id',
        'name',
        'slug_name',
        'is_mandatory',
        'type',
        'option_id',
        'data_type',
        'disabled',
        'disabled_source',
        'order',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
