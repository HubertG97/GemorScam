<?php

namespace App;

use App\Filters\ClassificationFilter;
use App\Filters\CryptoFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Classification extends Model
{
    //classification belongs to a crypto
    public function cryptos(){
        return $this->hasMany(Crypto::class);
    }

    //a classiciation is made by an user
    public function Classification(){
        return $this->belongsTo(User::class);

    }

    //scope filter of crypto model

    public function scopeFilter(Builder $builder, $request)
    {
        return (new CryptoFilter($request))->filter($builder);
    }
}
