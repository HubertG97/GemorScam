<?php

namespace App\Http\Controllers;

use App\Classification;
use App\Crypto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class ClassificationController extends Controller
{
    //show the classiciations at the timeline for filtering
    public function index(){
        $classifications = Classification::all();
        return view('home', compact('classifications'));
    }

    //redirect to the create classification page
    public function create()
    {
        $all_classifications = Classification::all();
        return view('classifications.create', compact('all_classifications'));
    }

    //store new classiciation in database
    public function store(){

        $data = request()->validate([
            'classification' => 'required',
            'description' => 'required',

        ]);

        $classification = new Classification();
        $classification->classification = request('classification');
        $classification->description = request('description');
        $classification->user_id = Auth::id();
        $classification->save();
        alert()->success('Classification successfully created!');
        return redirect('/home');
    }

    //load classifications inside create crypto page
    public function load(){
        $classifications = Classification::all();
        return view('cryptos.create', compact('classifications'));
    }


}
