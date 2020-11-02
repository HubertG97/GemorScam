<?php

namespace App\Http\Controllers;

use App\Rating;
use App\RatingCount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Integer;
use RealRashid\SweetAlert\Facades\Alert;

class RatingController extends Controller
{

    //creating a new rating and rating counter

    public function create(){
        $all_ratings = Rating::all();
        $checkRating = Rating::where([
            ['user_id', '=', Auth::id()],
            ['crypto_id', '=', request('crypto_id')],
        ]);
        $checkRatingCount = RatingCount::where([
            ['crypto_id', '=', request('crypto_id')],
        ]);

        //check if rating and rating count exists if not, creating one
        if ($checkRating->exists() && $checkRatingCount->exists()) {


            $this->update($checkRating->first());

        }else if (!($checkRating->exists()) && !$checkRatingCount->exists()){
            $rating = new Rating();
            $rating->crypto_id = request('crypto_id');
            $rating->user_id = Auth::id();
            $rating->rating = request('checker');
            $rating->save();
            $this->createRatingCount();

        }else if (!($checkRating->exists())){
            $rating = new Rating();
            $rating->crypto_id = request('crypto_id');
            $rating->user_id = Auth::id();
            $rating->rating = request('checker');
            $rating->save();
            $this->updateCount($rating);
        }



        return redirect()->back();
    }
    //creating the rating count
    public function createRatingCount(){
        $ratingCount = new RatingCount();
        $ratingCount->crypto_id = request('crypto_id');
        $gemResults = Rating::where([
            ['crypto_id', '=', request('crypto_id')],
            ['rating', '=', true],
        ])->get();
        $scamResults = Rating::where([
            ['crypto_id', '=', request('crypto_id')],
            ['rating', '=', false],
        ])->get();

        //update the count with the first rating given
        if($gemResults){
            $gemCount = count($gemResults);
        } else {
            $gemCount = 0;
        }

        if ($scamResults){
            $scamCount = count($scamResults);
        } else {
            $scamCount = 0;
        }

        $ratingCount->gem_count = $gemCount;
        $ratingCount->scam_count = $scamCount;
        $ratingCount->save();
        $rating_boolean = request('rating');
        if($rating_boolean === 1){
            $rating_name = 'gem';
        }else {
            $rating_name = 'scam';
        }

        toast('Rated crypto as '.$rating_name. '','success')->position('top-end')->autoClose(2000);
    }

    //update an existing rating

    public function update(Rating $rating){
        $rating->rating = request('checker');
        $rating->update();
        $this->updateCount($rating);


        return $rating;
    }

    //update an existing rating count

    public function updateCount(Rating $rating){
        $promotionChecker = new RoleController();

        //check if user is being promoted to author
        if($promotionChecker->rolePromotion() === true){
            Alert::success('You are now an author', 'Go to add crypto to add crypto yourself')->autoClose(5000);
        };
        $gemResults = Rating::where([
            ['crypto_id', '=', request('crypto_id')],
            ['rating', '=', true],
        ])->get();
        $scamResults = Rating::where([
            ['crypto_id', '=', request('crypto_id')],
            ['rating', '=', false],
        ])->get();


        $gemCount = count($gemResults);
        $scamCount = count($scamResults);

        RatingCount::where([
            ['crypto_id', '=', request('crypto_id')],
        ])->update(['gem_count' => $gemCount, 'scam_count' => $scamCount] );

        $rating_boolean = $rating->rating;
        if($rating_boolean == 1){
            $rating_name = 'gem';
        }else {
            $rating_name = 'scam';
        }
        toast('Rated crypto as '.$rating_name. '','success')->position('top-end')->autoClose(2000);
        return redirect()->back();
    }

}
