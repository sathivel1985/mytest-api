<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'value', 'updated_at'];

    public $timestamps = false;

    public function scopeSearch($query, $name)
    {
        return $query->when(
            request('timestamp') && !empty(request('timestamp')),
            fn ($query) => $query->where('updated_at',  request('timestamp'))
        )
            ->when(
                !empty($name),
                fn ($query) => $query->where('name',  $name)
            );
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::createFromTimestamp($value)->format('Y-m-d H:i:s');
    }
}
