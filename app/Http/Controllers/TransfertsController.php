<?php

namespace App\Http\Controllers;

use App\Transfert;
use App\Categorie;
use App\Modele;
use App\Historique;
use App\Livraison;
use App\livraisonCommande;
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

                $boutons = ' <a class="btn '.($clt->status == 0 ? 'btn-info' : 'btn-success').' " onclick="showtransfert('.$clt->id.')" ><i class="fa  fa-info"></i></a>';
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

                $boutons = ' <a class="btn '.($clt->status == 0 ? 'btn-info' : 'btn-success').' " onclick="showtransfert2('.$clt->id.')" ><i class="fa  fa-info"></i></a>';
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
        DB::beginTransaction();
        try {
            $transfert = new Transfert([
                'code' => "TSF" . now()->format('Y') . "-" . (DB::table('transferts')->max('id') + 1),
                'status' => 0,
                'magasin_transfert_id' => Auth::user()->boutique->id,
                'magasin_reception_id' => $request->input('idmagasin'),
                'livraison' => $request->input('livraison')
            ]);
            $transfert->save();



            $livraison = new Livraison([
                'numero' => "LIV" . now()->format('Y') . "-" . (DB::table('livraisons')->max('id') + 1),
                'date_livraison' => now(),
                'boutique_id' => $request->input('idmagasin'),
                'transfert_id' => $transfert->id,
            ]);
            $livraison->save();

            $produitTransfertData = explode('|', $request->input('produitTransfertData'));
            for ($i = 0; $i < count($produitTransfertData); $i += 4) {
                $modele = Modele::find($produitTransfertData[$i]);

                $quantiteToTransfer = min($produitTransfertData[$i+3], $modele->quantite); // Ensuring we do not transfer more than available
                if ($quantiteToTransfer <= 0 || !$modele) {
                    throw new \Exception("Stock insuffisant ou modèle introuvable pour " . $produitTransfertData[$i+1]);
                }

                DB::table('transfert_lignes')->insert([
                    'transfert_id' => $transfert->id,
                    'modele_libelle' => $produitTransfertData[$i+1],
                    'modele_qte' => $quantiteToTransfer,
                    'modele_transfert_id' => $produitTransfertData[$i],
                    'modele_reception_id' => null,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                $modele->decrement('quantite', $quantiteToTransfer);

                $livraisoncommande = new LivraisonCommande([
                    'commande_modele_id' => $produitTransfertData[$i],
                    'livraison_id' => $livraison->id,
                    'modele_id' => null,
                    'quantite_livre' => $quantiteToTransfer, // Use the calculated quantity to transfer
                    'quantite_restante' => 0,
                ]);
                $livraisoncommande->save();

                $livraison_a_prelever = Livraison::where('numero', $request->input('livraison'))->get()->first();
                $livraisons_commande = LivraisonCommande::where('livraison_id', $livraison_a_prelever->id)->where('modele_id', $produitTransfertData[$i])->get()->first();
                $livraisons_commande->update([
                    'quantite_livre' => $livraisons_commande->quantite_livre - $quantiteToTransfer,
                ]);
                $livraisons_commande->save();
            }

            DB::commit();
            return $transfert;
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 400);
        }
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
        $data = json_decode($request->data);
        if (empty($data)) {
            return response()->json(['error' => 'No data provided'], 400);
        }

        try {
            $transfertId = $data[0]->transfert_id;
            $livraison = Livraison::where('transfert_id', $transfertId)->firstOrFail();
            DB::beginTransaction();

            foreach ($data as $item) {
                TransfertLigne::where('id', $item->id)
                    ->update(['modele_reception_id' => $item->modele_reception_id]);

                LivraisonCommande::where('livraison_id', $livraison->id)
                    ->update(['modele_id' => $item->modele_reception_id]);

                Modele::where('id', $item->modele_reception_id)
                    ->increment('quantite', $item->modele_qte);
            }

            Transfert::where('id', $transfertId)
                    ->update(['status' => 1]);

            DB::commit();
            return response()->json($data);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'An error occurred', 'message' => $e->getMessage()], 500);
        }
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
                <script>
                    $("#famille'.$tl->id.'").on("change",function ( ) {
                        $.ajax({
                            url: "/recuperermodeleboutique-" + $("#famille'.$tl->id.'").val(),
                            type: "get",
                            success: function (data) {
                                $("#modele'.$tl->id.'").empty();
                                $("#modele'.$tl->id.'").append("<option disabled=\'disabled\' selected=\'selected\' value=\'\'></option>");
                                for (var i = 0; i < data.length; i++) {
                                    $("#modele'.$tl->id.'").append("<option value="+data[i].id+">"+data[i].libelle+"</option>");
                                }

                            },
                            error: function (data) {
                                console.log("erreur")
                            },
                        })
                    })
                </script>
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
