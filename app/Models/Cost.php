<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cost extends Model
{
    use HasFactory;

    protected $fillable=['origin','destination','courier','service','description','cost'];


    public function getCostAttribute($value)
    {
        return unserialize($value);
    }
}
