<?php

namespace App\Http\Controllers;

use App\Category;
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
use Codenixsv\CoinGeckoApi\CoinGeckoClient;

class CryptoController extends Controller
{
    //show crypto's that have been accepted by admin.

    public function index(){

        $visible_cryptos = Crypto::where([
            ['visible', '=', 1],
        ])->orderBy('market_cap', 'desc')->paginate(25);

        $classifications = Classification::all();

        return view('home', ['visible_cryptos' => $visible_cryptos, 'classifications' => $classifications]);
    }

    //admin page that shows all cryptos ordered by visibility.

    public function review(){

        $all_cryptos = Crypto::orderBy('visible')->orderBy('updated_at', 'desc')->paginate(25);

        return view('cryptos.review', ['all_cryptos' => $all_cryptos]);
    }

    //view all cryptos that belong to the logged in user.

    public function userCrypto(){

        $classifications = Classification::all();
        $user_cryptos = Crypto::where([
            ['user_id', '=', Auth::id()],
        ])->latest()->paginate(25);

        return view('cryptos.own', ['all_cryptos' => $user_cryptos, 'classifications' => $classifications]);
    }

    //show crypto's of specific user.

    public function otherCrypto(User $user){

        $classifications = Classification::all();
        $other_crypto = Crypto::where([
            ['user_id', '=', $user->id], ['visible', '=', 1],
        ])->latest()->paginate(25);

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

        if($crypto->user_id === Auth::id() || Auth::user()->checkRole('admin')) {
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

            if (request()->hasFile('image')) {
                $image_name = time() . '.' . request('image')->extension();
                request()->file('image')->move(public_path('image/logo'), $image_name);
                $crypto->logo_url = $image_name;
            }

            $crypto->update($data);
            toast('Crypto successfully updated!', 'success')->position('top-end')->autoClose(3000);
        }else{
            toast('Not authorized','Error')->position('top-end')->autoClose(3000);
        }
        return redirect('cryptos/' . $crypto->id);
    }

    //deleting crypto and it's rating and ratingCount table

    public function delete(Crypto $crypto){

        if($crypto->user_id === Auth::id() || Auth::user()->checkRole('admin')){
            Rating::where('crypto_id', $crypto->id)->delete();
            RatingCount::where('crypto_id', $crypto->id)->delete();
            Crypto::where('id', $crypto->id)->delete();
            toast('Crypto successfully deleted','success')->position('top-end')->autoClose(3000);
        }else{
            toast('Not authorized','Error')->position('top-end')->autoClose(3000);
        }


        return redirect()->back();
    }

    //filter crypto on classifications

    public function cryptoFilter(Request $request) {
        $classifications = Classification::all();
        $filteredcryptos = Crypto::filter($request)->where([
            ['visible', '=', 1],
        ])->paginate(25);
        if (count($filteredcryptos) === 0){
            alert()->error('Nothing found');
        };
        return view ('cryptos.results', compact('filteredcryptos', 'classifications'));
    }

    //search on crypto's with search field

    public function cryptoSearch(Request $request){
        $classifications = Classification::all();
        $searchedcryptos = Crypto::query()
            ->where('visible', '=', 1)
            ->where('name', 'LIKE', "%{$request->q}%")
            ->orWhere('ticker', 'LIKE', "%{$request->q}%")
            ->paginate(25);

        if (count($searchedcryptos) === 0){
            alert()->error('Nothing found');
        };


        return view ('cryptos.results', compact('searchedcryptos', 'classifications'));
    }

    public function invisibleSearch(Request $request){
        $classifications = Classification::all();
        $searchedcryptos = Crypto::query()
            ->where('name', 'LIKE', "%{$request->q}%")
            ->orWhere('ticker', 'LIKE', "%{$request->q}%")
            ->paginate(25);

        if (count($searchedcryptos) === 0){
            alert()->error('Nothing found');
        };


        return view ('cryptos.invisible-results', compact('searchedcryptos', 'classifications'));
    }

    public function getInfoCoin(){
        $cryptos = Crypto::where([
            ['visible', '=', 1],
        ])->orderBy('market_cap', 'desc')->get();

        foreach ($cryptos as $crypto){
            $response = file_get_contents('https://api.coingecko.com/api/v3/coins/'.$crypto->api_id.'?localization=false&tickers=false&market_data=false&community_data=false&developer_data=false&sparkline=false
');
            $response = json_decode($response ,true);

            $description = strip_tags($response['description']['en']);
            $crypto->description = $description;
            $crypto->website = $response['links']['homepage'][0];

            $category = $response['categories'];
           // preg_match_all('/".*?"|\'.*?\'/', $category, $matches);
            if (!(empty($category))){
                $crypto->category = $category[0];
                $crypto->update();
            }




            foreach ($response['categories'] as $category){

                if (!(Category::where('name', '=', $category)->exists())) {
                    $newCategory = new Category();
                    $newCategory->name = $category;
                    $newCategory->save();
                }



            }
            sleep(1);
        }
    }

    public function updateCoingecko(){

        $client = new CoinGeckoClient();
        $data = $client->coins()->getList();
        // https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&order=market_cap_desc
        for ($x = 1; $x <= 21; $x++) {

//            $response = $data;
            $response = file_get_contents('https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&order=market_cap_desc&per_page=100&page=' . $x . '');
            $response = json_decode($response, true);


            foreach ($response as $crypto) {
                $existingC = Crypto::where([
                    ['api_id', '=', $crypto['id']]
                ])->first();
//



                $existingC->price = $crypto['current_price'];



//                $extension = pathinfo(parse_url($crypto['image'], PHP_URL_PATH), PATHINFO_EXTENSION);
//                $image_name = 'a2' . rand() . '.' . $extension;
//
//                $url = $crypto['image'];
//
//                $path = public_path('image/logo/');
//                $imgpath = $path . $image_name;
//                file_put_contents($imgpath, file_get_contents($url));


//
              //  $existingC->logo_url = $image_name;
                $existingC->market_cap = $crypto['market_cap'];
                $existingC->ath = $crypto['ath'];
                $existingC->atl = $crypto['atl'];
//                $existingC->total_supply = $crypto['total_supply'];


                $existingC->save();

//
            }
            sleep(60);
        }
        return view('cryptos.coingecko', ['response' => $response]);

    }

}
