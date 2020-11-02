<?php

namespace App\Http\Controllers;

use App\Rating;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class RoleController extends Controller
{
    //check if user has rated 3 crypto's to become author excluding admin

    public function rolePromotion(){
        $ratingResults = Rating::where([
            ['user_id', '=', Auth::id()],
        ])->get();
        $promotedUser = User::find(Auth::id());
        $ratingCount = count($ratingResults);

        if($promotedUser->role_id === 1){
            $isAdmin = true;
        }else{
            $isAdmin = false;
        }

        if($ratingCount === 3 && !$isAdmin) {

            User::where([
                ['id', '=', Auth::id()],
            ])->update(['role_id' => 2]);
            return true;
        }else if ($ratingCount === 1 && !$isAdmin){
            Alert::info('2 more ratings to become author')->autoClose(2000);
        }else if ($ratingCount === 2 && !$isAdmin) {
            Alert::info('1 more rating to become author')->autoClose(2000);
        }
    }
}
