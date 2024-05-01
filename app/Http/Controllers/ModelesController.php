<?php

namespace App\Http\Controllers;

use App\Categorie;
use App\Client;
use App\Historique;
use App\Modele;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Console\Input\Input;
use Illuminate\Support\Facades\Storage;


class ModelesController extends Controller
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
        $modele=Modele::with(['produit','boutique'])->where ('boutique_id', '=',Auth::user()->boutique->id )->get();
        return datatables()->of($modele)
            ->addColumn('action', function ($clt){

                return ' <a class="btn btn-info " onclick="showmodele('.$clt->id.')" ><i class="fa  fa-info"></i></a>
                                    <a class="btn btn-success" onclick="editmodele('.$clt->id.')"> <i class="fa fa-pencil"></i></a>
                                    <a class="btn btn-danger" onclick="deletemodele('.$clt->id.')"><i class="fa fa-trash-o"></i></a>
                                    <a class="btn btn-primary" href="{{ route('show_modele_livraison', '.$clt->id.') }"><i class="fa fa-eye"></i></a> ';
            })
            ->make(true) ;
    }


    public function allmodelvent($id)
    {
        $modele=Modele::with(['produit','boutique'])
        /* ->join('modele_fournisseurs', function ($join) {
            $join->on('modeles.id', '=', 'modele_fournisseurs.modele_id');
        }) */
        ->join('preventes', function ($join) {
            $join->on('preventes.modele_fournisseur_id', '=', 'modeles.id');
        })
       ->join('ventes', function ($join) {
            $join->on('ventes.id', '=', 'preventes.vente_id');
        })
        ->join('users', function ($join) {
            $join->on('ventes.user_id', '=', 'users.id');
        })
       ->where ('modeles.boutique_id', '=',Auth::user()->boutique->id )
        ->where ('modeles.id', '=', $id)
        ->selectRaw('ventes.date_vente as date, preventes.quantite as quantite, preventes.prixtotal as montant, ventes.numero as numero, CONCAT(users.nom, " ", users.prenom) as user')
        // ->select('ventes.date_vente as date','preventes.quantite as quantite','preventes.prixtotal as montant', 'users.nom as user')
        ->get();
        //dd($modele);
        return datatables()->of($modele)
        // ->addColumn('numero', function ($clt) {
        //     return  '<a href="/showvente-' . $clt->vente_id . '">'. $clt->num .'</a>';
        // })
        ->make(true) ;
    }

    public function invent()
    {  $historique=new Historique();
        $historique->actions = "Liste";
        $historique->cible = "Inventaire";
        $historique->user_id =Auth::user()->id;
        $historique->save();
        return view('newinventaire');
    }
    public function liste()
    {
        $historique=new Historique();
        $historique->actions = "Liste";
        $historique->cible = "Modeles";
        $historique->user_id =Auth::user()->id;
        $historique->save();
        $categorie=Categorie::all();

        return view('produit',compact('categorie'));
    }

    public function liste_reporting()
    {
        $historique=new Historique();
        $historique->actions = "Rapport";
        $historique->cible = "Modeles";
        $historique->user_id =Auth::user()->id;
        $historique->save();

        $produits = Modele::
            join('produits', function ($join) {
                $join->on('modeles.produit_id', '=', 'produits.id');
            })
            ->where ('modeles.boutique_id', '=',Auth::user()->boutique->id )
            ->select('modeles.*', 'produits.nom')
            ->get();

        $clients = Client::all();

        $categorie=Categorie::all();
        return view('produit_report',compact('categorie', 'clients', 'produits'));
    }

    public function allreportvent(Request $request)
    {
        // dd($request->all());
        $modele=Modele::with(['produit','boutique'])
        /* ->join('modele_fournisseurs', function ($join) {
            $join->on('modeles.id', '=', 'modele_fournisseurs.modele_id');
        }) */
        ->join('preventes', function ($join) {
            $join->on('preventes.modele_fournisseur_id', '=', 'modeles.id');
        })
        ->join('ventes', function ($join) {
            $join->on('ventes.id', '=', 'preventes.vente_id');
        })
        ->join('users', function ($join) {
            $join->on('ventes.user_id', '=', 'users.id');
        });
        //dd($modele);

        if($request->client > 0)
        {
            $modele->join('clients', function ($join) {
                $join->on('ventes.client_id', '=', 'clients.id');
            });
        }

        $modele
        ->where ('modeles.boutique_id', '=',Auth::user()->boutique->id );

        if($request->produit > 0)
        {
            $modele
            ->where ('modeles.id', '=', $request->produit);
        }

        if($request->type > 0)
        {
            $modele
            ->where ('ventes.type_vente', '=', $request->type);
        }

        if($request->client > 0)
        {
            $modele
            ->where ('clients.id', '=', $request->client);
        }

        if(!empty($request->debut))
        {
            $modele
            ->where ('ventes.created_at', '>=', $request->debut);
        }

        if(!empty($request->fin))
        {
            $modele
            ->where ('ventes.created_at', '<=', $request->fin);
        }

        $modele = $modele
                    ->selectRaw('ventes.date_vente as date, preventes.quantite as quantite, preventes.prixtotal as montant, ventes.numero as numero, ventes.id as vente_id, CONCAT(users.nom, " ", users.prenom) as user')
                    ->orderBy('ventes.created_at', 'Desc')
                    ->get();

        return datatables()->of($modele)
        // ->addColumn('numero', function ($clt) {
        //     return  '<a href="/showvente-' . $clt->vente_id . '">'. $clt->num .'</a>';
        // })
        ->make(true) ;
    }

    public function allreportventsum(Request $request)
    {
        $modele=Modele::with(['produit','boutique'])
        /* ->join('modele_fournisseurs', function ($join) {
            $join->on('modeles.id', '=', 'modele_fournisseurs.modele_id');
        }) */
        ->join('preventes', function ($join) {
            $join->on('preventes.modele_fournisseur_id', '=', 'modeles.id');
        })
        ->join('ventes', function ($join) {
            $join->on('ventes.id', '=', 'preventes.vente_id');
        });
        if($request->client > 0)
        {
            $modele->join('clients', function ($join) {
                $join->on('ventes.client_id', '=', 'clients.id');
            });
        }

        $modele
        ->where ('modeles.boutique_id', '=',Auth::user()->boutique->id );

        if($request->produit > 0)
        {
            $modele
            ->where ('modeles.id', '=', $request->produit);
        }

        if($request->type > 0)
        {
            $modele
            ->where ('ventes.type_vente', '=', $request->type);
        }

        if($request->client > 0)
        {
            $modele
            ->where ('clients.id', '=', $request->client);
        }

        if(!empty($request->debut))
        {
            $modele
            ->where ('ventes.created_at', '>=', $request->debut);
        }

        if(!empty($request->fin))
        {
            $modele
            ->where ('ventes.created_at', '<=', $request->fin);
        }

        $modele
        ->selectRaw('SUM(preventes.quantite) as quantite, SUM(preventes.prixtotal) as montant');

        $modele = $modele
        ->first();


        return $modele;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {$id=DB::table('modeles')->max('id');
        $ed=1+$id;
        $modele = new Modele();
        $modele->libelle = $request->input('modele');
        $modele->quantite = $request->input('quantite');
        $modele->prix = $request->input('prix');
        $modele->prix_de_gros = $request->input('prixDeGros');
        $modele->prix_achat = $request->input('prixAchat') ?? 0;
        $modele->seuil = $request->input('seuil');
        $modele->numero ="MOD".now()->format('Y')."-".$ed;
        $modele->produit_id =$request->input('famille');
        $modele->boutique_id = Auth::user()->boutique->id;
        $modele->condi_modele = $request->input('condi_modele');
        $modele->save();
        $historique=new Historique();
        $historique->actions = "Creer";
        $historique->cible = "Modeles";
        $historique->user_id =Auth::user()->id;
        $historique->save();
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
        $historique=new Historique();
        $historique->actions = "Detail";
        $historique->cible = "Modeles";
        $historique->user_id =Auth::user()->id;
        $historique->save();
        $modele= Modele::with('produit')->findOrFail($id);
        return $modele;
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
        $modele = Modele::findOrFail($request->input('idmodele'));
        $modele->libelle = $request->input('modele');
        $modele->quantite = $request->input('quantite');
        $modele->prix = $request->input('prix');
        $modele->prix_de_gros = $request->input('prixDeGros');
        $modele->prix_achat = $request->input('prixAchat') ?? 0;
        $modele->seuil = $request->input('seuil');
        $modele->produit_id =$request->input('famille');
        $modele->update();
        $historique=new Historique();
        $historique->actions = "Modifier";
        $historique->cible = "Modeles";
        $historique->user_id =Auth::user()->id;
        $historique->save();
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
        $modele= Modele::findOrFail($id);
        $modele ->delete();
        $historique=new Historique();
        $historique->actions = "Supprimer";
        $historique->cible = "Modeles";
        $historique->user_id =Auth::user()->id;
        $historique->save();
        return [];
    }
}
