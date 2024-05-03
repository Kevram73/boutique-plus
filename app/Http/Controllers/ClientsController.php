<?php


namespace App\Http\Controllers;

use App\Client;
use App\Avoir;
use App\Boutique;

use App\InventoryDebtor;
use App\Historique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $client =Client::with('boutique')->where ('boutique_id', '=',Auth::user()->boutique->id )->orderBy('created_at', 'DESC')->get();
        return datatables()->of($client)
            ->addColumn('action', function ($clt) {

                return ' <a class="btn btn-info " onclick="showclt(' . $clt->id . ')" ><i class="fa  fa-info"></i></a>
                                    <a class="btn btn-success" onclick="editclt(' . $clt->id . ')"> <i class="fa fa-pencil"></i></a>
                                    <a class="btn btn-danger" onclick="deleteclt(' . $clt->id . ')"><i class="fa fa-trash-o"></i></a>
                                    <a class="btn btn-danger" onclick="avoirclt(' . $clt->id . ')"><i class="fa fa-money"></i></a> ';
            })
            ->make(true);
    }

    public function liste()
    {
        $historique = new Historique();
        $historique->actions = "liste";
        $historique->cible = "Clients";
        $historique->user_id = Auth::user()->id;
        $historique->save();
        return view('client');
    }

 public function storeDEBITEUR(Request $request)
    {
        $client = new InventoryDebtor();
        $client->nom = $request->input('nom');
        $client->prenom = $request->input('prenom');
        $client->contact = $request->input('contact');
        $client->solde= $request->input('solde');
        $client->boutique_id = Auth::user()->boutique->id;
        $client->save();
        $historique = new Historique();
        $historique->actions = "Creer";
        $historique->cible = "Débiteur inventeur";
        $historique->user_id = Auth::user()->id;
        $historique->save();
        return $request->input();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function save_avoir(Request $request){
        $avoir = new Avoir();
        $avoir->client_id = $request->input('client_id');
        $avoir->amount = $request->input('amount');
        $avoir->date_ajout = now();
        $avoir->user_id = Auth::user()->id;
        $avoir->save();

        $client = Client::find($request->client_id);
        $client->avoir += $request->amount;
        $client->save();

        return $request->input();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $client = new Client;
        $client->nom = $request->input('nom');
        $client->email = $request->input('email');
        $client->contact = $request->input('contact');
        $client->adresse = $request->input('adresse');
        $client->avoir= $request->input('avoir');

        $client->solde= $request->input('solde');
        $client->boutique_id = Auth::user()->boutique->id;
        $client->save();
        $historique = new Historique();
        $historique->actions = "Creer";
        $historique->cible = "Clients";
        $historique->user_id = Auth::user()->id;
        $historique->save();
    //        return $request->input();
        return view('client');
    }

    public function clientAjout(){
        return view('clientAjout');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $historique = new Historique();
        $historique->actions = "Detail";
        $historique->cible = "Clients";
        $historique->user_id = Auth::user()->id;
        $historique->save();
        $client = Client::findOrFail($id);

        return $client;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $client = Client::findOrFail($request->input('idclient'));
        $client->nom = $request->input('nom');
        $client->email = $request->input('email');
        $client->contact = $request->input('contact');
        $client->adresse = $request->input('adresse');
        $client->avoir = $request->input('avoir');

        $client->solde = $request->input('solde');
        $client->update();
        $historique = new Historique();
        $historique->actions = "Modifier";
        $historique->cible = "Clients";
        $historique->user_id = Auth::user()->id;
        $historique->save();
        return [];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();
        $historique = new Historique();
        $historique->actions = "Supprimer";
        $historique->cible = "Clients";
        $historique->user_id = Auth::user()->id;
        $historique->save();
        return [];
    }
}
