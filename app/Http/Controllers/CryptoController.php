<?php

namespace App\Http\Controllers;

use App\Classification;
use App\Crypto;
use App\Rating;
use App\RatingCount;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Filters\CryptoFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use RealRashid\SweetAlert\Facades\Alert;

class CryptoController extends Controller
{
    //show crypto's that have been accepted by admin.

    public function index(){

        $visible_cryptos = Crypto::where([
            ['visible', '=', 1],
        ])->latest()->get();

        $classifications = Classification::all();

        return view('home', ['visible_cryptos' => $visible_cryptos, 'classifications' => $classifications]);
    }

    //admin page that shows all cryptos ordered by visibility.

    public function review(){

        $all_cryptos = Crypto::orderBy('visible')->latest()->get();

        return view('cryptos.review', ['all_cryptos' => $all_cryptos]);
    }

    //view all cryptos that belong to the logged in user.

    public function userCrypto(){

        $classifications = Classification::all();
        $user_cryptos = Crypto::where([
            ['user_id', '=', Auth::id()],
        ])->latest()->get();

        return view('cryptos.own', ['all_cryptos' => $user_cryptos, 'classifications' => $classifications]);
    }

    //show crypto's of specific user.

    public function otherCrypto(User $user){

        $classifications = Classification::all();
        $other_crypto = Crypto::where([
            ['user_id', '=', $user->id], ['visible', '=', 1],
        ])->latest()->get();

        return view('cryptos.other', ['all_cryptos' => $other_crypto, 'classifications' => $classifications]);
    }

    //toggle visibility of cryptos.

    public function visibility(){

        $crypto_id = request('crypto_id');
        $crypto = Crypto::where([
            ['id', '=', $crypto_id],
        ])->first();
        $crypto_visibility = !($crypto->visible);
        $crypto->visible = $crypto_visibility;
        $crypto->save();
        if ($crypto_visibility == 0){
            $visibility_name = 'invisible';
        }else{
            $visibility_name = 'visible';
        }

        toast('Crypto successfully made '.$visibility_name. '','success')->position('top-end')->autoClose(3000);

        return $this->review();

    }

    //create page of crypto's.

    public function create()
    {
        $allcryptos = Crypto::all();
        return view('cryptos.create', compact('allcryptos'));
    }

    //store crypto in database also checks if already exists.

    public function store(){

        $data = request()->validate([
            'name' => 'required',
            'ticker' => 'required',
            'price' => 'required',
            'description' => 'required',
            'website' => 'required',
            'image' => 'mimes:jpeg,png|max:1024',

        ]);

        $crypto = new Crypto();
        $crypto->name = request('name');
        $crypto->ticker = request('ticker');
        $crypto->price = request('price');
        $crypto->description = request('description');
        $crypto->website = request('website');
        $crypto->classification_id = request('classification');
        $crypto->user_id = Auth::id();

        if (request()->hasFile('image')){
            $image_name = time().'.'.request('image')->extension();
            request()->file('image')->move(public_path('image/logo'), $image_name);
            $crypto->logo_url = $image_name;

        }else{
            $crypto->logo_url = 'no_image.png';
        }

       $existing_cryptos = Crypto::where([
            ['name', '=', request('name')],
        ])->get();

        if (count($existing_cryptos) === 0){
            $crypto->save();
            toast('Crypto successfully submitted!','success')->position('top-end')->autoClose(3000);
            return redirect('/home');
        }else{
            alert()->error('Already exists');
            return redirect()->back();
        }
    }

    //show crypto details page.

    public function show(Crypto $crypto){

        return view('cryptos.show', compact('crypto'));
    }

    //show crypto edit page.

    public function edit(Crypto $crypto){

        $classifications = Classification::all();
        return view('cryptos.edit', compact('crypto', 'classifications'));

    }

    //update database when posting the edited crypto.

    public function update(Crypto $crypto){
        $data = request()->validate([
            'name' => 'required',
            'ticker' => 'required',
            'price' => 'required',
            'description' => 'required',
            'website' => 'required',
            'logo_url' => 'mimes:jpeg,png|max:1024',

        ]);
        $crypto->name = request('name');
        $crypto->ticker = request('ticker');
        $crypto->price = request('price');
        $crypto->description = request('description');
        $crypto->website = request('website');
        $crypto->classification_id = request('classification');

        if (request()->hasFile('image')){
            $image_name = time().'.'.request('image')->extension();
            request()->file('image')->move(public_path('image/logo'), $image_name);
            $crypto->logo_url = $image_name;
        }

        $crypto->update($data);
        toast('Crypto successfully updated!','success')->position('top-end')->autoClose(3000);
        return redirect('cryptos/' . $crypto->id);
    }

    //deleting crypto and it's rating and ratingCount table

    public function delete(Crypto $crypto){
        Rating::where('crypto_id', $crypto->id)->delete();
        RatingCount::where('crypto_id', $crypto->id)->delete();
        Crypto::where('id', $crypto->id)->delete();

        return redirect()->back();
    }

    //filter crypto on classifications

    public function cryptoFilter(Request $request) {
        $classifications = Classification::all();
        $filteredcryptos = Crypto::filter($request)->where([
            ['visible', '=', 1],
        ])->get();
        if (count($filteredcryptos) === 0){
            alert()->error('Nothing found');
        };
        return view ('cryptos.results', compact('filteredcryptos', 'classifications'));
    }

    //search on crypto's with search field

    public function cryptoSearch(Request $request){
        $classifications = Classification::all();
        $searchedcryptos = Crypto::query()
            ->where('name', 'LIKE', "%{$request->q}%")
            ->orWhere('ticker', 'LIKE', "%{$request->q}%")
            ->get();

        if (count($searchedcryptos) === 0){
            alert()->error('Nothing found');
        };
        return view ('cryptos.results', compact('searchedcryptos', 'classifications'));
    }

}
