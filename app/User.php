<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Psy\Util\Str;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function cryptos(){
        return $this->hasMany(Crypto::class);
    }

    public function classifications(){
        return $this->hasMany(Classification::class);
    }

    public function Rating(){
        return $this->hasMany(Rating::class);
    }

    public function Role(){
        return $this->hasOne(Role::class);
    }

    //check if role matches in middleware
    public function checkRole(String $role){
        $user_role = Role::where('id', $this->role_id)->value('name');
        if($role === $user_role){
            return true;
        } else {
            return false;
        }


    }

    //get role rame

    public function roleName(int $role_id){
        $user_role = Role::where('id', $role_id)->value('name');
        return $user_role;
    }

    //get amount of posts by user
    public function postCount(){
        $posted_crypto = Crypto::where('user_id', $this->id)->get();
        $posted_count = count($posted_crypto);
        return $posted_count;
    }
}
