<?php

namespace App\Http\Controllers;

use App\Categorie;
use App\Historique;
use App\Produit;
use App\produitProvision;
use App\Provision;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function MongoDB\BSON\toJSON;

class ProvisionsController extends Controller
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
    public function fournisseur($id)
    {
        $fournisseur=Provision::with('fournisseur','produit')->where ('produit_id', '=', $id)->get();
        return $fournisseur;
    }
    public function provision($id,$ed)
    {
        $provision=DB::table('provisions')
            ->where ('fournisseur_id', '=', $id)
            ->where ('produit_id', '=', $ed)

            ->get();
        return $provision;
    }


    public function index()
    {
        $provision = DB::table('produit_provisions')
            ->join('provisions', function ($join) {
                $join->on('produit_provisions.provision_id', '=', 'provisions.id');

            })
            ->join('fournisseurs', function ($join) {
                $join->on('fournisseurs.id', '=', 'provisions.fournisseur_id');
            })
            ->join('produits', function ($join) {
                $join->on('produits.id', '=', 'provisions.produit_id');
            })
            ->select ('produit_provisions.*','fournisseurs.nom as fournisseur','produits.nom as produit')
            ->get();
        return datatables()->of($provision)
            ->addColumn('action', function ($clt) {
                          return  '<a class="btn btn-info " onclick="show(' . $clt->id . ')" ><i class="fa  fa-info"></i></a>
                                    <a class="btn btn-success" onclick="edit(' . $clt->id . ')"> <i class="fa fa-pencil"></i></a>
                                    <a class="btn btn-danger" onclick="deletepro(' . $clt->id . ')"><i class="fa fa-trash-o"></i></a> ';
            })
            ->make(true);
    }


    public function liste()
    {
        $categorie=Categorie::all();
        $historique=new Historique();
        $historique->actions= "Liste";
        $historique->cible = "Commandes";
        $historique->user_id =Auth::user()->id;
        $historique->save();
        return view('provision',compact('categorie'));
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $provision = new produitProvision();
        $provision->quantite = $request->input('quantite');
        $provision->prix_achat = $request->input('prix');
        $provision->date_provision = $request->input('dateprovision');
        $provision->provision_id = $request->input('provision');
        $provision->save();
        return $request ->input();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $provision = produitProvision::findOrFail($id);
        $pro = DB::table('produit_provisions')
            ->join('provisions', function ($join) {
                $join->on('produit_provisions.provision_id', '=', 'provisions.id');
            })
            ->join('fournisseurs', function ($join) {
                $join->on('fournisseurs.id', '=', 'provisions.fournisseur_id');
            })
            ->join('produits', function ($join) {
                $join->on('produits.id', '=', 'provisions.produit_id');
            })
            ->where('produit_provisions.id','=',$id)
            ->select ('fournisseurs.nom as fournisseur','fournisseurs.id as idfournisseur','produits.nom as produit','produits.id as idproduit')
            ->get();
        return ['produitProvision'=>$provision,'produit_fournisseur'=>$pro];

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $provision= produitProvision::findOrFail($request->input('idprovision'));
        $provision->quantite = $request->input('quantite');
        $provision->prix_achat = $request->input('prix');
        $provision->date_provision = $request->input('dateprovision');
        $provision->provision_id = $request->input('provision');
        $provision->update();
        return [];
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $provision= produitProvision::findOrFail($id);
        $provision ->delete();
        return [];
    }
}
