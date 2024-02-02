<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class EntryRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'entry_id',
        'form_field_id',
        'field_value'
    ];
    protected $casts = [
        'options' => 'array',
    ];

    public function getOptionsAttribute($value)
    {
        return json_decode($value, true);
    }

    public function entry()
    {
        return $this->belongsTo(Entry::class);
    }
    public function form_field()
    {
        return $this->belongsTo(FormField::class);
    }
}
