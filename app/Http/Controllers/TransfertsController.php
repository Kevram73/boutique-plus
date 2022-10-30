<?php

namespace App\Http\Controllers;

use App\Transfert;
use App\Categorie;
use App\Modele;
use App\Historique;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class TransfertsController extends Controller
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
    public function indexTransfert()
    {
        $transfert=Transfert::with('magasin_transfert')->with('magasin_reception')->where('magasin_transfert_id', Auth::user()->boutique->id)->get();
        return datatables()->of($transfert)
            ->addColumn('action', function ($clt){

                $boutons = ' <a class="btn btn-info " onclick="showtransfert('.$clt->id.')" ><i class="fa  fa-info"></i></a>';
                if($clt->status == 0){
                    $boutons = $boutons.'    <a class="btn btn-danger" onclick="deletetransfert('.$clt->id.')"><i class="fa fa-trash-o"></i></a> ';
                }
                return $boutons;
            })
            ->make(true) ;
    }

    public function indexReception()
    {
        $transfert=Transfert::with('magasin_transfert')->with('magasin_reception')->with('magasin_transfert')->with('magasin_reception')->where('magasin_reception_id', Auth::user()->boutique->id)->get();
        return datatables()->of($transfert)
            ->addColumn('action', function ($clt){

                $boutons = ' <a class="btn btn-info " onclick="showtransfert2('.$clt->id.')" ><i class="fa  fa-info"></i></a>';
                if($clt->status == 0){
                    $boutons = $boutons.'    <a class="btn btn-success" onclick="showreception('.$clt->id.')"><i class="fa fa-check"></i></a> ';
                }
                return $boutons;
            })
            ->make(true) ;
    }

    public function liste()
    {
        $transfert=Transfert::with('magasin_transfert')->with('magasin_reception')->where('magasin_transfert_id', Auth::user()->boutique->id)->get();
        $reception=Transfert::with('magasin_transfert')->with('magasin_reception')->where('magasin_reception_id', Auth::user()->boutique->id)->get();
        $categorie=Categorie::all();
        $magasins=DB::table('boutiques')->where('id', '<>', Auth::user()->boutique->id)->get();
        $historique=new Historique();
        $historique->actions = "liste";
        $historique->cible = "Transferts";
        $historique->user_id =Auth::user()->id;
        $historique->save();
        return view('transfert',compact('transfert', 'reception', 'categorie', 'magasins'));

    }

    public function transfert()
    {
        $transfert=Transfert::with('magasin_transfert')->with('magasin_reception')->where('magasin_transfert_id', Auth::user()->boutique->id)->get();
        return $transfert;

    }
    public function reception()
    {
        $reception=Transfert::with('magasin_transfert')->with('magasin_reception')->where('magasin_reception_id', Auth::user()->boutique->id)->get();
        return $reception;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id=DB::table('transferts')->max('id');
        
        DB::beginTransaction();
        $transfert = new Transfert();
        $transfert->code = "TSF".now()->format('Y')."-".($id+1);
        $transfert->status = 0;
        $transfert->magasin_transfert_id = Auth::user()->boutique->id;
        $transfert->magasin_reception_id = $request->input('idmagasin');
        $transfert->save();
        
        $formdata= explode('|', $request->input('produitTransfertData') );
        for ($i =0 ;$i<count($formdata);$i+=4) {
            $modele= DB::table('modeles')->find($formdata[$i]);

            if(!$modele){
                DB::rollback();
                Alert::warning("Une erreur est survenu", "Récuperation de ".$formdata[$i+1]);
                return $modele;
            }

            if($modele->quantite < $formdata[$i+3])
            {
                DB::rollback();
                Alert::warning("Quantité de transfert supérieure au stock", $formdata[$i+1]);
                return $modele;
            }

            DB::table('transfert_lignes')->insert([
                'transfert_id' => $transfert->id,
                'modele_libelle' => $formdata[$i+1],
                'modele_qte' => $formdata[$i+3],
                'modele_transfert_id' => $formdata[$i],
                'modele_reception_id' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::table('modeles')->where('id', $formdata[$i])->decrement('quantite', $formdata[$i+3]);
        }
        DB::commit();
        return $transfert;
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
        $historique->actions = "detail";
        $historique->cible = "Transferts";
        $historique->user_id =Auth::user()->id;
        $historique->save();
        $transfert= Transfert::with('magasin_transfert')->with('magasin_reception')->findOrFail($id);
        $transfert_lignes = DB::table('transfert_lignes')->where('transfert_id', $id)->get();
        return [
            'transfert' => $transfert,
            'transfert_lignes' => $transfert_lignes,
        ];
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
        // not implemented
    }

    public function indexUpdate($id)
    {
        $historique=new Historique();
        $historique->actions = "details reception";
        $historique->cible = "Transferts";
        $historique->user_id =Auth::user()->id;
        $historique->save();

        $familles = DB::table('produits')->get();
        $famillesOptions = "";
        foreach ($familles as $key => $famille) {
            $famillesOptions = $famillesOptions . '<option value="'.$famille->id.'">'.$famille->nom.'</option>';
        }
        $transfert_lignes = DB::table('transfert_lignes')->where('transfert_id', $id)->get();
        return datatables()->of($transfert_lignes)
        ->addColumn('action', function ($tl) use ($famillesOptions) {

            $boutons = '
                <div class="col-sm-12">
                    <select id="famille'.$tl->id.'"   class="form-control populate">
                    <option disabled="disabled" selected="selected" >Choisir une famille</option>
                        <optgroup>'.
                            $famillesOptions
                        .'</optgroup>
                    </select>
                </div>
                <div class="col-sm-12">
                    <select id="modele'.$tl->id.'"   class="form-control populate">
                    <option disabled="disabled" selected="selected" >Choisir un produit</option>
                        <optgroup></optgroup>
                    </select>
                </div>
            ';
            return $boutons;
        })
        ->make(true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        $transfert_lignes = DB::table('transfert_lignes')->where('transfert_id', $id)->get();
        for ($i =0 ;$i<count($transfert_lignes);$i++) {
            $modele= DB::table('modeles')->find($transfert_lignes[$i]->modele_transfert_id);
            if(!$modele){
                DB::rollback();
                Alert::warning("Une erreur est survenu", "Opération sur ".$transfert_lignes[$i]->modele_libelle);
                return $modele;
            }
            DB::table('modeles')
            ->where('id', $transfert_lignes[$i]->modele_transfert_id)
            ->increment('quantite', $transfert_lignes[$i]->modele_qte);
            DB::table('transfert_lignes')->where('id', $transfert_lignes[$i]->id)->delete();
        }

        $transfert= Transfert::findOrFail($id);
        $transfert->delete();
        DB::commit();

        $historique=new Historique();
        $historique->actions = "supprimer";
        $historique->cible = "les transferts";
        $historique->user_id =Auth::user()->id;
        $historique->save();
        return [];
    }


    public function showBoutiqueProduit($famille)
    {
        $boutique = Auth::user()->boutique->id;
        $modeles= Modele::where('boutique_id', $boutique)
        ->where('produit_id', $famille)
        ->get();
        return $modeles;
    }
}
