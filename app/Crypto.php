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

    public function checkRating($user_id, $crypto_id){

        $rating = Rating::where([
            ['user_id', '=', $user_id], ['crypto_id', '=', $crypto_id],
        ])->pluck('rating');

        if (isset($rating)){
            return intval($rating[0]);
        }

        return null;
    }

    public function scopeFilter(Builder $builder, $request)
    {
        return (new CryptoFilter($request))->filter($builder);
    }

}
