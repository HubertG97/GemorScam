<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RatingCount extends Model
{
    public function rating(){
        return $this->belongsTo(Rating::class);
    }

    public function crypto(){
        return $this->belongsTo(Crypto::class);
    }
}
