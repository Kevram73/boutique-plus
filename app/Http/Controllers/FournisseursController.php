<?php

namespace App\Http\Controllers;

use App\Categorie;
use App\Fournisseur;
use App\Historique;
use App\modeleFournisseur;
use App\Produit;
use App\livraisonCommande;
use App\Livraison;
use App\Provision;
use App\Decharge;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;
use Dompdf\Dompdf;
use Dompdf\Options;

class FournisseursController extends Controller
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
        $fournisseur=Fournisseur::all();
        return datatables()->of($fournisseur)
            ->addColumn('action', function ($clt){

                return ' <a class="btn btn-info " onclick="showfournisseur('.$clt->id.')" ><i class="fa  fa-info"></i></a>
                                    <a class="btn btn-success" onclick="editfournisseur('.$clt->id.')"> <i class="fa fa-pencil"></i></a>
                                    <a class="btn btn-danger" onclick="deletefournisseur('.$clt->id.')"><i class="fa fa-trash-o"></i></a> ';
            })
            ->make(true) ;
    }
    public function index2()
    {
        $fournisseurP = DB::table('modele_fournisseurs')
            ->join('fournisseurs', function ($join) {
                $join->on('fournisseurs.id', '=', 'modele_fournisseurs.fournisseur_id');
            })
            ->join('modeles', function ($join) {
                $join->on('modele_fournisseurs.modele_id', '=', 'modeles.id');
            })
            ->join('produits', function ($join) {
                $join->on('produits.id', '=', 'modeles.produit_id');
            })
            ->select ('modele_fournisseurs.id as id','modele_fournisseurs.prix as prix','fournisseurs.nom as fournisseur','modeles.libelle as modele','produits.nom as produit','produits.id as idproduit')
            ->get();
        return datatables()->of($fournisseurP)
            ->addColumn('action', function ($fourni){

                return ' <a class="btn btn-info " onclick="showfourni('.$fourni->id.')" ><i class="fa  fa-info"></i></a>
                                    <a class="btn btn-success" onclick="editfourni('.$fourni->id.')"> <i class="fa fa-pencil"></i></a>
                                    <a class="btn btn-danger" onclick="deletefourni('.$fourni->id.')"><i class="fa fa-trash-o"></i></a> ';
            })
            ->make(true) ;
    }

    public function liste()
    {
        $fournisseur=Fournisseur::all();
        $categorie=Categorie::all();
        $historique=new Historique();
        $historique->actions = "Liste";
        $historique->cible = "Fournisseurs";
        $historique->user_id =Auth::user()->id;
        $historique->save();
        return view('fournisseur',compact('fournisseur','categorie'));
    }
    public function fournisseurP()
    {
        $fournisseur=Fournisseur::all();
        return $fournisseur;
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
        $fournisseur = new Fournisseur;
        $fournisseur->nom = $request->input('nom');
        $fournisseur->adresse = $request->input('adresse');
        $fournisseur->description = $request->input('description');
        $fournisseur->email = $request->input('email');
        $fournisseur->contact = $request->input('contact');
        $fournisseur->save();
        $historique=new Historique();
        $historique->actions = "Creer";
        $historique->cible = "Fournisseurs";
        $historique->user_id =Auth::user()->id;
        $historique->save();
        return $request ->input();
    }
    public function store2(Request $request)
    {
        $fournisseurP = new modeleFournisseur();
        $fournisseurP->fournisseur_id = $request->input('fournisseurP');
        $fournisseurP->modele_id = $request->input('modele');
        $fournisseurP->prix = $request->input('prix') * $request->input('currency');
        $fournisseurP->save();
        $historique=new Historique();
        $historique->actions = "Creer";
        $historique->cible = "Fournisseurs";
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
        $fournisseur= Fournisseur::findOrFail($id);
        $historique=new Historique();
        $historique->actions = "Detail";
        $historique->cible = "Fournisseurs";
        $historique->user_id =Auth::user()->id;
        $historique->save();
        return $fournisseur;
    }
    public function show2($id)
    {
        $fournisseur=DB::table('fournisseurs')
            ->join('modele_fournisseurs', function ($join) {
                $join->on('modele_fournisseurs.fournisseur_id', '=', 'fournisseurs.id');
            })
            ->join('modeles', function ($join) {
                $join->on('modele_fournisseurs.modele_id', '=', 'modeles.id');
            })
            ->join('produits', function ($join) {
                $join->on('modeles.produit_id', '=', 'produits.id');
            })
            ->join('categories', function ($join) {
                $join->on('produits.categorie_id', '=', 'categories.id');
            })
            ->select('fournisseurs.id as idfournisseur',
                'fournisseurs.nom as fournisseur',
                'modeles.libelle as modele',
                'modeles.id as idmodele',
                'produits.nom as produit',
                'produits.id as idproduit',
                'modele_fournisseurs.prix as prix',
                'modele_fournisseurs.id as id',
                'modele_fournisseurs.created_at as created',
                'modele_fournisseurs.updated_at as updated',
                'categories.id as idcategorie',
                'categories.nom as categorie'
            )
            ->where('modele_fournisseurs.id','=',$id)
            ->get();
        return $fournisseur;
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
        $fournisseur= Fournisseur::findOrFail($request->input('idfournisseur'));
        $fournisseur->nom = $request->input('nom');
        $fournisseur->adresse = $request->input('adresse');
        $fournisseur->description = $request->input('description');
        $fournisseur->email = $request->input('email');
        $fournisseur->contact = $request->input('contact');
        $fournisseur->update();
        $historique=new Historique();
        $historique->actions = "Modifier";
        $historique->cible = "Fournisseurs";
        $historique->user_id =Auth::user()->id;
        $historique->save();
        return [];
    }
    public function update2(Request $request)
    {
        $fournisseurP= modeleFournisseur::findOrFail($request->input('idfournisseur_produit'));
        $fournisseurP->fournisseur_id = $request->input('fournisseurP');
        $fournisseurP->modele_id = $request->input('modele');
        $fournisseurP->prix = $request->input('prix');
        $fournisseurP->update();
        $historique=new Historique();
        $historique->actions = "Modifier";
        $historique->cible = "Fournisseurs";
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
        $fournisseur= Fournisseur::findOrFail($id);
        $fournisseur ->delete();
        $historique=new Historique();
        $historique->actions = "Supprimer";
        $historique->cible = "Fournisseurs";
        $historique->user_id =Auth::user()->id;
        $historique->save();
        return [];
    }
    public function destroy2($id)
    {
        $fournisseurP= modeleFournisseur::findOrFail($id);
        $fournisseurP ->delete();
        $historique=new Historique();
        $historique->actions = "Supprimer";
        $historique->cible = "Fournisseurs";
        $historique->user_id =Auth::user()->id;
        $historique->save();
        return [];
    }
    public function produit($id)
    {
        $produit=DB::table('produits')
            ->where ('categorie_id', '=', $id)
            ->get();
        return $produit;
    }
    public function modele($id)
    {
        // Récupération du $result
        $result = livraisonCommande::join('livraisons AS l', 'livraison_commandes.livraison_id', '=', 'l.id')
            ->where('l.boutique_id', Auth::user()->boutique_id)
            ->whereColumn('livraison_commandes.quantite_vendue', '<', 'livraison_commandes.quantite_livre')
            ->select('livraison_modeles.modele') // Extraction du champ 'modele'
            ->groupBy('livraison_modeles.modele')
            ->get();

        // Obtenir les IDs des modèles
        $modeles_ids = $result->pluck('modele'); // Pluck 'modele' from $result

        // Filtrer $modele en utilisant whereIn()
        $modele = DB::table('modeles')
            ->where('produit_id', $id)
            ->whereIn('modeles.id', $modeles_ids)
            ->get(); // Obtenir les données


        return $modele;
    }

    public function modele2($id)
    {
        $modele=DB::table('modeles')
            ->where ('modeles.boutique_id', '=',Auth::user()->boutique->id)
            ->where ('produit_id', '=', $id)
            ->get();
        return $modele;
    }

    public function fournisseurmodele($modele, $fournisseur)
    {
        if($fournisseur == 0) {
            $data1 = DB::table('modeles')->where('id', $modele)->first();

            $data3 = array();
            $data3['fournisseur'] = null;
            $data3['id'] = null;
            $data3['prix'] = $data1->prix;
            $data3['stock'] = $data1->quantite;
            $data3['modele'] = $data1->id;
            $data3['prix_vente'] = $data1->prix;

            $result = [];
            $result[] = $data3;
            return $result;

        } else {
           $result = DB::table('modele_fournisseurs')
            ->join('fournisseurs', function ($join) {
                $join->on('fournisseurs.id', '=', 'modele_fournisseurs.fournisseur_id');
            })
            ->join('modeles', function ($join) {
                $join->on('modeles.id', '=', 'modele_fournisseurs.modele_id');
            })
            ->where ('modele_fournisseurs.modele_id', '=', $modele)
            ->where ('modele_fournisseurs.fournisseur_id', '=', $fournisseur)
            ->select (
                'fournisseurs.nom as fournisseur',
                'fournisseurs.id as id',
                'modele_fournisseurs.prix as prix',
                'modeles.quantite as stock',
                'modeles.id as modele',
                'modeles.prix as prix_vente')
            ->get();

            if(count($result) === 0){
                $data1 = DB::table('modeles')->where('id', $modele)->first();
                $data2 = DB::table('fournisseurs')->where('id', $fournisseur)->first();

                $data3 = array();
                $data3['fournisseur'] = $data2->nom;
                $data3['id'] = $data2->id;
                $data3['prix'] = $data1->prix;
                $data3['stock'] = $data1->quantite;
                $data3['modele'] = $modele;
                $data3['prix_vente'] = $data1->prix;

                $result = [];
                $result[] = $data3;
            }
            return $result;
        }
    }

    public function fournisseurmodeleOld($modele, $fournisseur)
    {
        $result = DB::table('modele_fournisseurs')
            ->join('fournisseurs', function ($join) {
                $join->on('fournisseurs.id', '=', 'modele_fournisseurs.fournisseur_id');
            })
            ->join('modeles', function ($join) {
                $join->on('modele_fournisseurs.modele_id', '=', 'modeles.id');
            })
            ->where ('modele_fournisseurs.modele_id', '=', $modele)
            ->where ('fournisseurs.id', '=', $fournisseur)
            ->select ('fournisseurs.nom as fournisseur',
                'fournisseurs.id as id',
                'modele_fournisseurs.prix as prix',
                'modeles.quantite as stock',
                'modeles.id as modele',
                'modeles.prix as prix_vente')
            ->get();

        if(count($result) === 0) {
            $data1 = DB::table('modeles')->where('id', $modele)->first();
            $data2 = DB::table('fournisseurs')->where('id', $fournisseur)->first();

            $data3 = array();
            $data3['fournisseur'] = $data2->nom;
            $data3['id'] = $data2->id;
            $data3['prix'] = $data1->prix;
            $data3['stock'] = $data1->quantite;
            $data3['modele'] = $modele;
            $data3['prix_vente'] = $data1->prix;

            $result = [];
            $result[] = $data3;
        }
        return $result;
    }

    public function fournisseur($id)
    {
        $fournisseur = DB::table('modeles')
            ->where ('id', '=', $id)
            ->select ('numero as fournisseur',
                'id as id',
                'prix as prix',
                'quantite as stock',
                'id as modele',
                'prix as prix_vente')
            ->get();
        return $fournisseur;
    }
    public function fournisseurgros($id)
    {
        $fournisseur = DB::table('modeles')
            ->where ('id', '=', $id)
            ->select ('numero as fournisseur',
                'id as id',
                'prix_de_gros as prix',
                'quantite as stock',
                'id as modele',
                'prix_de_gros as prix_vente')
            ->get();
        return $fournisseur;
    }
    public function produit2($id)
    {
        $produit = DB::table('produits')
            ->select('produits.nom as produit', 'produits.id as id')
            ->groupBy('produits.id', 'produits.nom')
            ->get();
        return $produit;
        // $produit = DB::table('modele_fournisseurs')
        //     ->join('modeles', function ($join) {
        //         $join->on('modeles.id', '=', 'modele_fournisseurs.modele_id');
        //     })
        //     ->join('produits', function ($join) {
        //         $join->on('produits.id', '=', 'modeles.produit_id');
        //     })
        //     // ->where('modele_fournisseurs.fournisseur_id', '=', $id)
        //     ->select('produits.nom as produit', 'produits.id as id')
        //     ->groupBy('produits.id', 'produits.nom')
        //     ->get();
        // return $produit;
    }

    public function decharge_liste(Request $request){
        $decharges = Decharge::all();
        $fournisseurs = Fournisseur::all();
        return view("decharges", compact('decharges', 'fournisseurs'));
    }

    public function generate_decharge(Request $request){

        $name = "decharge_".date('Y-m-d_H-i-s', strtotime(now())).".pdf";

            try{
                $options = new Options();
                $options->set('isHtml5ParserEnabled', true);
                $options->set('isRemoteEnabled', TRUE);
                $options->set('isPhpEnabled', true);

                $dompdf = new Dompdf($options);

                // Chargez la vue dans Dompdf
                $view = view('decharge')->render();
                $dompdf->loadHtml($view);

                // Définissez la taille du papier
                $dompdf->setPaper('a4');

                // Rendez le PDF
                $dompdf->render();

                // Enregistrez le PDF dans un répertoire
                file_put_contents(public_path("decharges/" . $name), $dompdf->output());

            }catch(Exception $e)
            {}

            //return $pdf->download($name);
            return response()->download(public_path("decharges/" . $name));
    }

    public function save_decharge(Request $request){
        $request->validate([
            'nom' => 'required|max:255',
            'prenoms' => 'required|max:255',
            'motif' => 'required',
            'tel' => 'required',
            'montant' => 'required',
            'cni' => 'required',
            'document' => 'required'
        ]);

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('decharge', $filename, 'public');
        }

        // Create a new post using the validated data
        $decharge = new Decharge();
        $decharge->nom = $request->nom;
        $decharge->prenoms = $request->prenoms;
        $decharge->motif = $request->motif;
        $decharge->montant = $request->montant;
        $decharge->tel = $request->tel;
        $decharge->cni = $request->cni;
        if($request->fournisseur_id){
            $decharge->fournisseur_id = $request->fournisseur_id;
        }
        $decharge->filename = $filename;
        $decharge->save();

        // Redirect to a specified route with a success message
        return back()->with('success', 'Décharge créée avec succès!');
    }

    public function delete_decharge(int $id){
        $decharge = Decharge::findOrFail($id);
        $decharge->delete();

        return back()->with('success', 'Décharge supprimée avec succès!');
    }

    public function edit_decharge(Request $request, $id)
    {
        $request->validate([
            'nom' => 'required|max:255',
            'prenoms' => 'required|max:255',
            'motif' => 'required',
            'montant' => 'required',
            'cni' => 'required',
            'tel' => 'required',
            'document' => 'required'
        ]);

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('decharge', $filename, 'public');
        }

        $decharge = Decharge::findOrfail($id);
        $decharge->nom = $request->nom;
        $decharge->prenoms = $request->prenoms;
        $decharge->motif = $request->motif;
        $decharge->montant = $request->montant;
        $decharge->tel = $request->tel;
        $decharge->cni = $request->cni;
        if($request->fournisseur_id){
            $decharge->fournisseur_id = $request->fournisseur_id;
        }
        $decharge->filename = $filename;
        $decharge->save();

        return back()->with('success', 'Décharge modifiée avec succès!');
    }

}
