<?php

namespace App;

use App\Filters\CryptoFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Crypto extends Model
{
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function Classification(){
        return $this->belongsTo(Classification::class);
    }

    public function Rating(){
        return $this->hasMany(Rating::class);
    }

    public function RatingCount(){
        return $this->hasOne(RatingCount::class);
    }

    public function scopeFilter(Builder $builder, $request)
    {
        return (new CryptoFilter($request))->filter($builder);
    }

}
