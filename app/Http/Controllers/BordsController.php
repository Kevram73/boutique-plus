<?php

namespace App\Http\Controllers;
use App\Boutique;
use App\Caisse;
use App\Client;
use App\Employe;
use App\Historique;
use App\Modele;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class BordsController extends Controller
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
    public function admin()
    {
        $boutique=Boutique::all()->count ();
        return view('bord.admin',compact('boutique'));
    }

    public function index()
    {

        $client=Client::with('boutique')->where ('boutique_id', '=',Auth::user()->boutique->id )->count ();
        $fournisseur=DB::table('fournisseurs')->count ();
        $employe=User::with('boutique')->where ('boutique_id', '=',Auth::user()->boutique->id )->count ();
        $categorie=DB::table('categories')->count ();
        $produit=Modele::with('boutique')->where ('boutique_id', '=',Auth::user()->boutique->id )->count ();
        $commande=DB::table('commandes')->count ();
        $livraison=DB::table('livraisons')->count ();
        $caisse=DB::table('caisses')->count ();
        $role =DB::table('model_has_roles')
            ->join('roles', function ($join) {
                $join->on('roles.id', '=', 'model_has_roles.role_id');
            })
            ->where('model_has_roles.model_id','=',Auth::user()->id)
            ->select('roles.name')
            ->get();

        $boutique= User::with('boutique')->where ('id', '=',Auth::user()->id )->get();
        $id=DB::table('journals')->max('id');

        $vente=DB::table('ventes')
            ->join('journals', function ($join) {
                $join->on('ventes.journal_id', '=','journals.id');
            })
            ->join('users', function ($join) {
                $join->on('ventes.user_id', '=','users.id');
            })
            ->where('ventes.journal_id','=',$id)
            ->where('ventes.user_id','=',Auth::user()->id)
            ->count ();

        $modele2=DB::table('modeles')
            ->join('produits', function ($join) {
                $join->on('modeles.produit_id', '=', 'produits.id');
            })
            ->where ('modeles.boutique_id', '=',Auth::user()->boutique->id )
            ->whereColumn('modeles.seuil','>=','modeles.quantite')
            ->get();
        $mod=count($modele2);
        $clients=DB::table('clients')
            ->join('ventes', function ($join) {
                $join->on('ventes.client_id', '=', 'clients.id');
            })
            ->join('reglements', function ($join) {
                $join->on('reglements.vente_id', '=', 'ventes.id');
            })
            ->where ('ventes.boutique_id', '=',Auth::user()->boutique->id )
            ->where('reglements.montant_restant', '>', 0)
            ->select('clients.nom as nom','clients.id as id')
            ->groupBy('id', 'clients.nom')
            ->get();
        $credit=array();
        for ($i =0 ;$i<count($clients);$i++) {
            $total = DB::table('reglements')
                ->join('ventes', function ($join) {
                    $join->on('reglements.vente_id', '=', 'ventes.id');
                })
                ->where ('ventes.boutique_id', '=',Auth::user()->boutique->id )
                ->where('ventes.client_id', '=', $clients[$i]->id)
                ->SUM('reglements.montant_restant');
            $credit[$i] = $total;
        }
        $cre=count($clients);
        $historique=new Historique();
        $historique->actions = "Connecté";
        $historique->cible = "Compte";
        $historique->user_id =Auth::user()->id;
        $historique->save();
            //dd($role);
        if($role[0]->name == 'SUPER ADMINISTRATEUR'){

            $client=Client::with('boutique')->count ();
            $employe=User::with('boutique')->count ();
            $produit=Modele::with('boutique')->count ();
        }

        return view('bord.index',compact('vente','client','caisse','fournisseur', 'employe','mod','modele2','categorie','produit','commande','livraison','role','cre','clients','credit','boutique'));
    }


    public function magasin()
    {
        if (Auth::user()->flag_etat==true){
            Alert::warning('Attention.....Compte bloqué','Veuillez vous référer a votre administrateur');
            return view('connexion');
        }
        $fournisseur=DB::table('fournisseurs')->count ();
        $categorie=DB::table('categories')->count ();
        $produit=DB::table('modeles')->count ();
        $commande=DB::table('commandes')->count ();
        $livraison=DB::table('livraisons')->count ();
        $modele2=DB::table('modeles')
            ->join('produits', function ($join) {
                $join->on('modeles.produit_id', '=', 'produits.id');
            })
            ->whereColumn('modeles.seuil','>=','modeles.quantite')
            ->get();
        $mod=count($modele2);
        return view('bord.magasin',compact(/*'caisse',*/'fournisseur','categorie','produit','commande','livraison','mod','modele2'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function caisse()
    {
        if (Auth::user()->flag_etat==true){
            Alert::warning('Attention.....Compte bloqué','Veuillez vous référer a votre administrateur');
            return view('connexion');
        }
        $client=DB::table('clients')->count ();
        $id=DB::table('journals')->max('id');

        $vente=DB::table('ventes')
            ->join('journals', function ($join) {
                $join->on('ventes.journal_id', '=','journals.id');
            })
            ->join('users', function ($join) {
                $join->on('ventes.user_id', '=','users.id');
            })
            ->where('ventes.journal_id','=',$id)
            ->where('ventes.user_id','=',Auth::user()->id)
            ->count ();

        $somme=DB::table('preventes')
            ->join('ventes', function ($join) {
                $join->on('preventes.vente_id', '=','ventes.id');
            })
            ->join('users', function ($join) {
                $join->on('ventes.user_id', '=','users.id');
            })
            ->join('journals', function ($join) {
                $join->on('ventes.journal_id', '=','journals.id');
            })
            ->where('ventes.journal_id','=',$id)
            ->where('ventes.user_id','=',Auth::user()->id)
            ->SUM('preventes.prixtotal');

        $modele2=DB::table('modeles')
            ->join('produits', function ($join) {
                $join->on('modeles.produit_id', '=', 'produits.id');
            })
            ->where ('modeles.boutique_id', '=',Auth::user()->boutique->id )
            ->whereColumn('modeles.seuil','>=','modeles.quantite')
            ->get();
        $mod=count($modele2);
        $clients=DB::table('clients')
            ->join('ventes', function ($join) {
                $join->on('ventes.client_id', '=', 'clients.id');
            })
            ->join('reglements', function ($join) {
                $join->on('reglements.vente_id', '=', 'ventes.id');
            })
            ->where ('ventes.boutique_id', '=',Auth::user()->boutique->id )
            ->where('reglements.montant_restant', '>', 0)
            ->select('clients.nom as nom','clients.id as id')
            ->groupBy('id', 'clients.nom')
            ->get();
        $credit=array();
        for ($i =0 ;$i<count($clients);$i++) {
            $total = DB::table('reglements')
                ->join('ventes', function ($join) {
                    $join->on('reglements.vente_id', '=', 'ventes.id');
                })
                ->where ('ventes.boutique_id', '=',Auth::user()->boutique->id )
                ->where('ventes.client_id', '=', $clients[$i]->id)
                ->SUM('reglements.montant_restant');
            $credit[$i] = $total;
        }
        $cre=count($clients);
        return view('bord.caisse',compact('client','mod','modele2','vente','somme','cre','credit','clients'));
    }

    public function allseuil()
    {
        $models =DB::table('modeles')
        ->join('produits', function ($join) {
            $join->on('modeles.produit_id', '=', 'produits.id');
        })
        ->join('categories', function ($join) {
            $join->on('produits.categorie_id', '=', 'categories.id');
        })
        ->whereColumn('modeles.seuil','>=','modeles.quantite')
        ->where('modeles.boutique_id', Auth::user()->boutique->id)
        ->selectRaw("modeles.*,CONCAT(categories.nom, ' - ', produits.nom) as produit ,produits.numero as numero")
        ->orderByDesc('modeles.created_at')
        ->get();

        return datatables()->of($models)
            // ->addColumn('action', function ($clt) {

            //     return ' <a class="btn btn-info " onclick="showclt(' . $clt->id . ')" ><i class="fa  fa-info"></i></a>
            //                         <a class="btn btn-success" onclick="editclt(' . $clt->id . ')"> <i class="fa fa-pencil"></i></a>
            //                         <a class="btn btn-danger" onclick="deleteclt(' . $clt->id . ')"><i class="fa fa-trash-o"></i></a> ';
            // })
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
