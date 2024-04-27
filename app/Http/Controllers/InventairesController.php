<?php

namespace App\Http\Controllers;

use App\Categorie;
use App\Inventaire_modeles;
use App\InventoryDebtor;
use App\InventoryDebtorBalance;
use App\Modele;
use App\Inventor_Debitor_Payement;
use Illuminate\Support\Facades\DB;
use App\Historique;
use App\Inventaires;
use App\Boutique;
use App\User;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PDF;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\View;
class InventairesController extends Controller
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
        $inventaire = DB::table('inventaires')
            ->where(['inventaires.etat' => 1, 'inventaires.boutique_id' => Auth::user()->boutique->id])
            ->join('users', function ($join) {
                $join->on('inventaires.user_id', '=', 'users.id');
            })
            ->select('inventaires.*',
                'users.id as user_id',
                'users.nom',
                'users.prenom')
            ->get();
        return datatables()->of($inventaire)
            ->addColumn('action', function ($clt){
                return ' <a class="btn btn-info " onclick="showinventaire('.$clt->id.')" ><i class="fa  fa-info"></i></a>
            
               ';
            })
            ->make(true) ;
    }
    // Return the list of all pending inventaires.
    public function indexPending()
    {
        $inventaire = DB::table('inventaires')
        ->where(['inventaires.etat' => 0, 'inventaires.boutique_id' => Auth::user()->boutique->id])
        ->join('users', function ($join) {
            $join->on('inventaires.user_id', '=', 'users.id');
        })
        ->select('inventaires.*', 'users.id as user_id', 'users.nom', 'users.prenom')
        ->get();
        return datatables()->of($inventaire)
            ->addColumn('action', function ($clt){
                return ' <a class="btn btn-success " href="/new2inventaire-'.$clt->id.'" ><i class="fa fa-check"></i></a>
                <a class="btn btn-info" href="/inventaires/pending/'. $clt->pdf_pending .'" ><i class="fa fa-print"></i></a>
                <a class="btn btn-danger " onclick="deleteinventaire('.$clt->id.')" ><i class="fa fa-trash-o"></i></a>
                ';
            })
            ->make(true) ;
    }

    // Return the list of all inventaires non regulated.
    public  function list_non_regulated(){
        $inventaire = DB::table('inventaires')
            ->where(['inventaires.etat' => 1, 'inventaires.boutique_id' => Auth::user()->boutique->id])
            ->join('users', function ($join) {
                $join->on('inventaires.user_id', '=', 'users.id');
            })
            ->select('inventaires.*', 'users.id as user_id', 'users.nom', 'users.prenom')
            ->get();
        return datatables()->of($inventaire)
            ->addColumn('action', function ($clt){
                return '
                 <a class="btn btn-success " href="/inventaire_non_reg-'.$clt->id.'" ><i class="fa fa-info"></i></a>

                ';
            })
            ->make(true) ;
    }
    // regulate an inventaire
    public function regulate_defined_inventaire()
    {
        $inventaire = DB::table('inventaires')
            ->where(['inventaires.etat' => 1, 'inventaires.boutique_id' => Auth::user()->boutique->id])
            ->join('users', function ($join) {
                $join->on('inventaires.user_id', '=', 'users.id');
            })
            ->join('inventaire_modeles',function ($join){
                $join->on('inventaire_modeles.inventaire_id','=','inventaire.id') ;
            })
            ->select('inventaires.*', 'users.id as user_id', 'users.nom', 'users.prenom','')
            ->get();
        return datatables()->of($inventaire)
            ->addColumn('action', function ($clt){
                return '
                 <a class="btn btn-success " href="/inventaire_non_reg-'.$clt->id.'" ><i class="fa fa-check"></i></a>

                ';
            })
            ->make(true) ;
    }

  // Apply a change  to inventaire regulate.
    public function  change_inventaire_regulate(Request $request)
    {
        $inventaire_id = $request->input('inv_id');
        $debtor = $request->input('fournisseur');
        $montant = $request->input('donne') ;
        $inventor_debitor_payements = new Inventor_Debitor_Payement();

            $inventor_debitor_payements->montant_rembourser = $request->input('donne') ;
           $inventor_debitor_payements->user_id=Auth::user()->id;
           $inventor_debitor_payements->boutique_id=Auth::user()->boutique->id;
           $inventor_debitor_payements->inventor_debitor_id=$request->input('fournisseur');
            $inventor_debitor_payements->save();

        $fournisseur = InventoryDebtor::find($inventor_debitor_payements->inventor_debitor_id);

        // Mise à jour des informations de l'utilisateur
        $fournisseur->solde = $fournisseur->solde -  $request->input('donne');

        // Sauvegarde des modifications
        $fournisseur->save();
        error_log("inventaire id ".$inventaire_id);
        error_log("debtor ".$debtor);
        error_log("montant ".$montant);

        // Make the payment.
        //$inv= InventoryDebtorBalance::where([
            //'inventory_id','=',$inventaire_id,
          //  'inventory_debtor_id','=',$debtor
        //])->get();

       //$inv->montant=$montant;
       //$inv->save();
        error_log("montant: ".$montant);
        return redirect()->back();
    }
    public function liste()
    {
        $inventaire=Inventaires::all();
        $historique=new Historique();
        $historique->actions = "liste";
        $historique->cible = "Inventaire";
        $historique->user_id =Auth::user()->id;
        $historique->save();
        return view('inventaire',compact('inventaire'));

    }
    
     public function calculate($id)
        {
            // Effectuez une recherche dans la base de données en fonction de l'ID sélectionné
            $result =  DB::table('boutiques')
            ->join('modeles', function ($join) {
                $join->on('modeles.boutique_id', '=', 'boutiques.id');
            })
           /*  ->join('produits', function ($join) {
                $join->on('modeles.produit_id', '=', 'produits.id');
            })
            ->join('categories', function ($join) {
                $join->on('produits.categorie_id', '=', 'categories.id');
            }) */
             ->where ('modeles.boutique_id', '=',$id)
            //->select(DB::raw('SUM(FORMAT(modeles.prix_achat * modeles.quantite, 2)) as total'))
             ->SUM(DB::raw('modeles.prix_achat * modeles.quantite'))
            //->first()
            ;
            // Vérifiez si un résultat a été trouvé
            if ($result) {
               // $total = $result->total;
                $total = round($result, 2);
                // Retournez le résultat au format JSON
                return response()->json(['total' => $total]);
            } else {
                // Si aucun résultat n'a été trouvé, retournez un message d'erreur au format JSON
                return response()->json(['error' => 'Aucun résultat trouvé pour cet ID.']);
            }
        }
    public function listesuper()
    {
        $inventaires=Modele::all();
        $boutiques = Boutique::all();
        $produitsInventaire =  DB::table('boutiques')
        ->join('modeles', function ($join) {
            $join->on('modeles.boutique_id', '=', 'boutiques.id');
        })
        ->join('produits', function ($join) {
            $join->on('modeles.produit_id', '=', 'produits.id');
        }) 
        ->join('categories', function ($join) {
            $join->on('produits.categorie_id', '=', 'categories.id');
        })
        ->selectRaw('modeles.qte_tonne * modeles.prix_tonne as valeur, modeles.* ,boutiques.nom as boutique,categories.nom,produits.nom as famille')
          ->groupBy('modeles.id','categories.nom','modeles.condi_modele','modeles.ref_modele','boutique','famille','modeles.libelle','modeles.numero','modeles.quantite','modeles.qte_tonne','modeles.prix','modeles.prix_de_gros','modeles.prix_achat',
        'modeles.prix_tonne','modeles.created_at','modeles.updated_at','modeles.seuil','modeles.boutique_id','modeles.produit_id')
        ->get();
         $ValeurTotal =  DB::table('boutiques')
        ->join('modeles', function ($join) {
            $join->on('modeles.boutique_id', '=', 'boutiques.id');
        })
       /*  ->join('produits', function ($join) {
            $join->on('modeles.produit_id', '=', 'produits.id');
        })
        ->join('categories', function ($join) {
            $join->on('produits.categorie_id', '=', 'categories.id');
        }) */
        ->select(DB::raw('SUM(FORMAT(modeles.prix_achat / modeles.condi_modele * modeles.quantite / modeles.condi_modele, 2)) as total'))
        ->first();
                    //dd($ValeurTotal->total);
        return view('inventaire.inventairesuper',compact('inventaires','produitsInventaire','ValeurTotal','boutiques'));
    }
    
    public function listeglobal()
    {
        $inventaires = DB::table('boutiques')
        ->join('modeles', 'boutiques.id', '=', 'modeles.boutique_id')
        ->join('produits', 'produits.id', '=', 'modeles.produit_id')
        ->select(
            'boutiques.nom as NomBoutique',
            'modeles.libelle as NomProduit',
            'modeles.quantite as QuantiteEnStock',
            'modeles.prix_achat as PrixAchat',
            'modeles.prix as PrixVente',
            DB::raw('modeles.prix_achat * modeles.quantite as ValeurEnMagasin')
        )
        ->get();

    // Grouper par NomBoutique et ajouter la valeur totale à chaque groupe
    $boutiques = $inventaires->groupBy('NomBoutique')->map(function ($group, $key) {
        $totalValeur = $group->sum('ValeurEnMagasin');
        return [
            'produits' => $group,
            'totalValeur' => $totalValeur
        ];
    });
    $total = 0;
    foreach ($boutiques as $nomBoutique => $data){
        $total += $data['totalValeur'];
    }
                    //dd($ValeurTotal->total);
        return view('inventaire.inventaireglobal',compact('boutiques', 'total'));
    }
    
    public function print_inventory(Request $request){
        $name = "inventaire_".date('Y-m-d_H-i-s').".pdf";
        $outputPath = public_path("inventaires/global/" . $name);
    
        try{
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true);
    
            $dompdf = new Dompdf($options);
    
            $inventaires = DB::table('boutiques')
        ->join('modeles', 'boutiques.id', '=', 'modeles.boutique_id')
        ->join('produits', 'produits.id', '=', 'modeles.produit_id')
        ->select(
            'boutiques.nom as NomBoutique',
            'modeles.libelle as NomProduit',
            'modeles.quantite as QuantiteEnStock',
            'modeles.prix_achat as PrixAchat',
            'modeles.prix as PrixVente',
            DB::raw('modeles.prix_achat * modeles.quantite as ValeurEnMagasin')
        )
        ->get();

    // Grouper par NomBoutique et ajouter la valeur totale à chaque groupe
    $boutiques = $inventaires->groupBy('NomBoutique')->map(function ($group, $key) {
        $totalValeur = $group->sum('ValeurEnMagasin');
        return [
            'produits' => $group,
            'totalValeur' => $totalValeur
        ];
    });
    $total = 0;
    foreach ($boutiques as $nomBoutique => $data){
        $total += $data['totalValeur'];
    }
            
            // Chargez la vue dans Dompdf
            $view = view('inventaire.print', compact('boutiques', 'total'))->render();
            $dompdf->loadHtml($view);
    
            // Définissez la taille du papier
            $dompdf->setPaper('A4', 'portrait');
    
            // Rendez le PDF
            $dompdf->render();
    
            // Assurez-vous que le répertoire cible existe
            if (!file_exists(dirname($outputPath))) {
                mkdir(dirname($outputPath), 0777, true);
            }
    
            // Enregistrez le PDF dans le répertoire
            file_put_contents($outputPath, $dompdf->output());
    
        }catch(\Exception $e){
            // Gérez l'exception et retournez un message d'erreur approprié
            return response()->json(['error' => $e->getMessage()], 500);
        }
    
        // Retournez le PDF en téléchargement
        return response()->download($outputPath);
    }

    public function indexsuper($id)
    {
        $produitsInventaire =  DB::table('boutiques')
        ->join('modeles', function ($join) {
            $join->on('modeles.boutique_id', '=', 'boutiques.id');
        })
        ->join('produits', function ($join) {
            $join->on('modeles.produit_id', '=', 'produits.id');
        }) 
        ->join('categories', function ($join) {
            $join->on('produits.categorie_id', '=', 'categories.id');
        })
        ->where ('modeles.boutique_id', '=',$id)

        ->selectRaw('modeles.condi_modele * modeles.prix_achat as valeur,modeles.quantite,FORMAT(modeles.quantite / modeles.condi_modele, 2) as qte_tonne,modeles.condi_modele,modeles.libelle, modeles.ref_modele ,boutiques.nom as boutique,categories.nom,produits.nom as famille')
         /*  ->groupBy('modeles.id','categories.nom','modeles.condi_modele','modeles.ref_modele','boutique','famille'
         ,'modeles.libelle','modeles.numero','modeles.quantite','modeles.qte_tonne','modeles.prix','modeles.prix_de_gros','modeles.prix_achat',
        'modeles.prix_tonne','modeles.created_at','modeles.updated_at','modeles.seuil','modeles.boutique_id','modeles.produit_id') */
        ->orderByDesc('modeles.ref_modele')
        ->get();
        if($id == 0)
        {
            $produitsInventaire =  DB::table('boutiques')
        ->join('modeles', function ($join) {
            $join->on('modeles.boutique_id', '=', 'boutiques.id');
        })
        ->join('produits', function ($join) {
            $join->on('modeles.produit_id', '=', 'produits.id');
        }) 
        ->join('categories', function ($join) {
            $join->on('produits.categorie_id', '=', 'categories.id');
        })
        ->selectRaw('modeles.condi_modele * modeles.prix_achat as valeur,modeles.quantite,FORMAT(modeles.quantite / modeles.condi_modele, 2) as qte_tonne,modeles.condi_modele,modeles.libelle, modeles.ref_modele ,boutiques.nom as boutique,categories.nom,produits.nom as famille')
         /*  ->groupBy('modeles.id','categories.nom','modeles.condi_modele','modeles.ref_modele','boutique','famille'
         ,'modeles.libelle','modeles.numero','modeles.quantite','modeles.qte_tonne','modeles.prix','modeles.prix_de_gros','modeles.prix_achat',
        'modeles.prix_tonne','modeles.created_at','modeles.updated_at','modeles.seuil','modeles.boutique_id','modeles.produit_id') */
        ->orderByDesc('modeles.ref_modele')
        ->get();

        }
        return datatables()->of($produitsInventaire)
           /*  ->addColumn('action', function ($clt){

                return ' <a class="btn btn-info " onclick="showproduit('.$clt->id.')" ><i class="fa  fa-info"></i></a>
                                    <a class="btn btn-success" onclick="editproduit('.$clt->id.')"> <i class="fa fa-pencil"></i></a>
                                    <a class="btn btn-danger" onclick="deleteproduit('.$clt->id.')"><i class="fa fa-trash-o"></i></a> ';
            }) */
            ->make(true) ;
    }
    public function allreportventsuper(Request $request)
    {
        
         $inventaire =  DB::table('modeles')
        ->join('produits', function ($join) {
            $join->on('modeles.produit_id', '=', 'produits.id');
        })
        ->join('categories', function ($join) {
            $join->on('produits.categorie_id', '=', 'categories.id');
        })
        ->selectRaw('modeles.condi_modele * modeles.prix_achat as valeur,modeles.quantite,modeles.quantite / modeles.condi_modele as qte_tonne,modeles.condi_modele,modeles.libelle, modeles.ref_modele ,boutiques.nom as boutique,categories.nom,produits.nom as famille')
         /*  ->groupBy('modeles.id','categories.nom','modeles.condi_modele','modeles.ref_modele','boutique','famille'
         ,'modeles.libelle','modeles.numero','modeles.quantite','modeles.qte_tonne','modeles.prix','modeles.prix_de_gros','modeles.prix_achat',
        'modeles.prix_tonne','modeles.created_at','modeles.updated_at','modeles.seuil','modeles.boutique_id','modeles.produit_id') */
        ->orderByDesc('modeles.ref_modele')
        ;

        if($request->produit > 0)
        {
            $inventaire
            ->where ('modeles.boutique_id', '=', $request->produit);
        }

       

        $inventaire = $inventaire
                ->selectRaw('SUM(modeles.condi_modele * modeles.prix_achat) as valeur,modeles.quantite ,modeles.quantite / modeles.condi_modele as qte_tonne,modeles.condi_modele,modeles.libelle,boutiques.nom as boutique,categories.nom,produits.nom as famille')

                    ->orderBy('modeles.ref_modele', 'Desc')
                    ->get();

        return datatables()->of($inventaire)
        // ->addColumn('numero', function ($clt) {
        //     return  '<a href="/showvente-' . $clt->vente_id . '">'. $clt->num .'</a>';
        // })
        ->make(true) ;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateModeleSuperInventaire(Request $request)
    {
        $historique=new Historique();
        $historique->actions = "modifier";
        $historique->cible = "Inventaire super";
        $historique->user_id =Auth::user()->id;
        $historique->save();

        $boutique= Modele::findOrFail($request->input('boutique'));
       // dd($'boutique');
            //$boutique->nom = $request->input('nom');
        $boutique->prix_tonne = $request->input('nom');
        $boutique->qte_tonne = $request->input('telephone');
        $boutique->update();
        return [];
    }










    public function inventairesuper()
    {

        $inventaire =  DB::table('modeles')
        ->join('produits', function ($join) {
            $join->on('modeles.produit_id', '=', 'produits.id');
        })
        ->join('categories', function ($join) {
            $join->on('produits.categorie_id', '=', 'categories.id');
        })
        ->selectRaw('SUM(modeles.quantite * modeles.prix_tonne) as valeur, modeles.*')
          ->groupBy('boutiques.id','boutiques.nom','boutiques.adresse','boutiques.telephone','boutiques.is_stock',
        'boutiques.contact','boutiques.created_at','boutiques.updated_at','boutiques.is_central')
        ->get();


                // dd($boutique);

        return datatables()->of($inventaire)
            ->addColumn('action', function ($clt){
                return ' <a class="btn btn-info " onclick="showboutique('.$clt->id.')" ><i class="fa  fa-info"></i></a>
                            <a class="btn btn-warning " onclick="showvaleur('.$clt->id.')" ><i class="fa fa-money"></i></a>
                                    <a class="btn btn-primary" href="/settings-'.$clt->id.'"> <i class="fa fa-cog"></i></a>
                                    <a class="btn btn-primary" onclick="changeState('.$clt->id.')"><i class="fa  fa-unlock"></i></a>
                                    <a class="btn btn-success" onclick="editboutique('.$clt->id.')"> <i class="fa fa-pencil"></i></a>
                                    <a class="btn btn-danger" onclick="deleteboutique('.$clt->id.')"><i class="fa fa-trash-o"></i></a> ';

            });
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $id=DB::table('inventaires')->max('id');
        if ($id==null){
            $inventaire=new Inventaires();
            $inventaire->numero ="INVENT".now()->format('Y')."-1";
            $inventaire->user_id =Auth::user()->id;
            $inventaire->boutique_id = Auth::user()->boutique->id;
            $inventaire->save();
            return view('newinventaire');
        }
        else{
            $ed=1+$id;
            $inventaire=new Inventaires();
            $inventaire->numero ="INVENT".now()->format('Y')."-".$ed;
            $inventaire->user_id =Auth::user()->id;
            $inventaire->boutique_id = Auth::user()->boutique->id;
            $inventaire->save();
            return view('newinventaire');
        }
    }

    public function create2invt($id)
    {
        $data = Inventaires::find($id);
        if(is_null($data))
            return back();
        else
        {
            return view('new2inventaire', compact('data'));
        }
    }

    //Show the detail of a regulated inventaire.

    public function regulate_inventaire($id)
    {
        /* $result = DB::table('inventaires')
            ->where('inventaires.id','=',$id)
            ->join('inventory_debtor_balances',function ($join){
            $join->on('inventaires.id','=',
                'inventory_debtor_balances.inventory_id');
        })
            ->join('inventory_debtor_balances',function ($join){
            $join->on('inventory_debtors.id','=','inventory_debtor_balances.inventory_debtor_id');
            })
            ->select('inventaires.numero as code_inventaire',
                'inventory_debtor_balances.id as b_id',
                'inventaires.id as id',
                'inventory_debtors.nom as nom',
                'inventory_debtors.id as debtor_id',
                'inventory_debtors.prenom as prenom',
                'inventory_debtor_balances.montant as montant_a_rembourser',
                'inventory_debtor_balances.montant_rembourser as montant_rembourser')
            ->get(); */
            $inventaire = DB::table('inventory_debtors')
            ->join('inventory_debtor_balances','inventory_debtors.id','inventory_debtor_balances.inventory_debtor_id')
            ->join('inventaires','inventory_debtor_balances.inventory_id','inventaires.id')

            ->join('inventor__debitor__payements','inventor__debitor__payements.inventor_debitor_id','inventory_debtors.id')
            ->where('inventor__debitor__payements.boutique_id',Auth::user()->boutique->id)
            ->where('inventory_debtors.boutique_id',Auth::user()->boutique->id)
            //->where('inventory_debtor_balances.inventory_id',$id)
            ->selectRaw('inventory_debtors.id,inventory_debtors.nom,inventaires.numero,inventory_debtors.prenom ,inventory_debtors.solde, SUM(inventor__debitor__payements.montant_rembourser)as montant_rembourser , inventory_debtor_balances.montant as dette')
            ->groupBy('inventory_debtors.id','inventory_debtors.nom','inventaires.numero','inventory_debtors.prenom','inventory_debtors.solde','dette')
            ->get();

       //dd($inventaire);
        $result = InventoryDebtor::where('boutique_id',Auth::user()->boutique->id)->get();

        //dd($result);
        error_log($result);
        error_log("le id de inv: ".$result[0]->id);
        $data = Inventaires::find($id) ;
        // regulate inventory.
        if (empty($result))
        {
            return back();
        }

        if(is_null($data) ) {
                return back() ;
            }
        return view('inventaire_regulate',compact('data','result','inventaire','id')) ;
    }
    public function totalachat($id)
    {
        $totale=DB::table('inventory_debtors')
        ->join('inventory_debtor_balances', function ($join) {
            $join->on('inventory_debtor_balances.inventory_debtor_id', '=', 'inventory_debtors.id');
        })
        -> where ('inventory_debtor_balances.inventory_debtor_id', '=',$id)
        -> sum('montant');
       /*  $total=DB::table('inventory_debtors')
        ->where('id','=',$id)
        ->sum('solde'); */
        $total =InventoryDebtor::where('id',$id)->sum('solde');
       // dd($total);
        /* $totalachat=DB::table('commandes')
        -> where (['commandes.inventory_debtor_id' => $id, 'commandes.credit' => true ])
        -> sum('totaux'); */
        //dd($total);
        return response() ->json($total);
    }
    public function create2(Request $request)
    {
        $id=DB::table('inventaires')->max('id');
        if ($id==null){
            $inventaire=new Inventaires();
            $inventaire->numero ="INVENT".now()->format('Y')."-1";
            $inventaire->user_id =Auth::user()->id;
        }
        else{
            $ed=1+$id;
            $inventaire=new Inventaires();
            $inventaire->numero ="INVENT".now()->format('Y')."-".$ed;
            $inventaire->user_id =Auth::user()->id;
        }
        $inventaire->boutique_id = Auth::user()->boutique->id;
        $inventaire->date_inventaire = now();
        $inventaire->date_inventaire_prevu = $request->input('date_prev');
        $modele = $request->input('choix');
        $cate = $request->input('categorie');
        $inventaire->categorie_id = $modele == "categorie" ? $cate != null ? $cate : 0 : 0;
        $inventaire->save();


        $user = User::find($inventaire->user_id);

        $modeles = $inventaire->categorie_id == 0 ? DB::table('modeles')
            ->join('produits', function ($join) {
                $join->on('modeles.produit_id', '=', 'produits.id');
            })
            ->join('categories', function ($join) {
                $join->on('produits.categorie_id', '=', 'categories.id');
            })
            ->where ('modeles.boutique_id', '=',Auth::user()->boutique->id )
            ->select('modeles.id as id',
                'modeles.libelle as modele',
                'modeles.quantite as quantite',
                'produits.nom as produit',
                'categories.nom as categorie'
            )
            ->get()
            :
            DB::table('modeles')
            ->join('produits', function ($join) {
                $join->on('modeles.produit_id', '=', 'produits.id');
            })
            ->join('categories', function ($join) {
                $join->on('produits.categorie_id', '=', 'categories.id');
            })
            ->where('categories.id','=',$inventaire->categorie_id)
            ->where ('modeles.boutique_id', '=',Auth::user()->boutique->id )
            ->select('modeles.id as id',
                'modeles.libelle as modele',
                'modeles.quantite as quantite',
                'produits.nom as produit',
                'categories.nom as categorie'
            )
            ->get();
        // Gene PDF
       // dd($modele);



 $dompdf = new Dompdf();
    
       // Rendre la vue de facture à l'aide de View
       $html = View::make('prints/inventairepending',compact('inventaire','modeles', 'user'))->render();
   
       // Charger le HTML dans Dompdf
       $dompdf->loadHtml($html);
   
       // Définir le format du papier et de l'orientation
       $dompdf->setPaper('A4', 'landscape');
   
       // Générer le PDF
       $dompdf->render();
   
       // Nom du fichier PDF
       $name = "inventaire_".date('Y-m-d_H-i-s', strtotime(now())).".pdf";
   
       // Enregistrez le PDF dans un dossier public
       $output = $dompdf->output();
       file_put_contents(public_path("inventaires/pending/".$name), $output);
   
       $pdfPath = public_path("inventaires/pending/".$name);
       DB::table('inventaires')->where('id',$inventaire->id)->update(['pdf_pending' => $name]);



        /*    $name = "inventaire_".date('Y-m-d_H-i-s', strtotime(now())).".pdf";
            $pdf = null;
            try{
                $pdf = PDF::loadView('prints/inventairepending',compact('inventaire','modeles', 'user'))
                        ->setPaper('a4')
                        ->save(public_path("inventaires/pending/".$name));
                DB::table('inventaires')->where('id',$inventaire->id)->update(['pdf_pending' => $name]);
            }catch(Exception $e)
            {}*/
            
            
            
            return [];
    }

    /*
    public function rembourse_inventaire(Request $request,$id)
    {
    $client_inventaire = DB::table('inventaire');
    }

     */

    public function get_client_amount_by_inventory()
    {
        $debtor_id = request('debtor_id');
        $inv_id = request('inv_id');
        error_log(" le debtor id est : ".$debtor_id);
        error_log(" le inv id est : ".$inv_id);
        $data = DB::table('inventory_debtor_balances')
            ->where('inventory_id','=',$inv_id)
            ->where('inventory_debtor_id','=',$debtor_id)
            ->get();
        error_log("le data est: ".$data);
        return $data ;

    }
    /**
     *
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $modele = DB::table('modeles')
            ->join('produits', function ($join) {
                $join->on('modeles.produit_id', '=', 'produits.id');
            })
            ->join('categories', function ($join) {
                $join->on('produits.categorie_id', '=', 'categories.id');
            })
            ->select('modeles.id as id',
                'modeles.libelle as modele',
                'modeles.quantite as quantite',
                'produits.nom as produit',
                'categories.nom as categorie'
            )
            ->where('modeles.id','=',$id)
            ->get();

            //dd($modele);

        return $modele;
    }
    public function show2($id)
    {

        $inventaire = DB::table('inventaires')
        ->join('inventaire_modeles', function ($join) {
        $join->on('inventaire_modeles.inventaire_id', '=', 'inventaires.id');
        })
        ->join('modeles', function ($join) {
        $join->on('inventaire_modeles.modele_id', '=', 'modeles.id');
        })
        ->join('produits', function ($join) {
        $join->on('modeles.produit_id', '=', 'produits.id');
        })
        ->join('categories', function ($join) {
        $join->on('produits.categorie_id', '=', 'categories.id');
        })
        ->join('users', function ($join) {
        $join->on('inventaires.user_id', '=', 'users.id');
        })
        ->select('modeles.id as id',
        'modeles.libelle as modele',
        'modeles.prix as prix_unitaire',
        'inventaire_modeles.quantite as quantite',
        'inventaire_modeles.quantite_reelle as quantiteR',
        'inventaire_modeles.justify as justify',
        'produits.nom as produit',
        'inventaires.numero as numero',
        'inventaires.date_inventaire as date',
        'inventaires.id as inventaire_id',
        'inventaires.pdf_pending as pdf',
        'inventaires.observation as observation',
        'users.nom as utilisateur',
        'users.prenom as prenom',
        'categories.nom as categorie'
        )
        ->where(
            'inventaires.id','=',$id
        )
        ->get();

        // Montant total
        // Return all the debtors of inventory.
        $debtors = Db::table('inventory_debtors')->where('boutique_id',Auth::user()->boutique->id)->get();
        $detbtors_table = DB::table('inventory_debtors')
            ->join('inventory_debtor_balances',function ($join){
                $join->on('inventory_debtor_balances.inventory_debtor_id','=','inventory_debtors.id');
            })
            ->where('inventory_debtor_balances.inventory_id','=',$id)
            ->get();

        error_log($detbtors_table);
        $montant_total_debiteur_inventair= 0 ;
        foreach ($detbtors_table as $t){
            $montant_total_debiteur_inventair += $t->montant ;
        }
        $montant_total_maquant = 0 ;
        $counter = count($inventaire) ;
        foreach ($inventaire as $inv){
            $montant_total_maquant += $inv->prix_unitaire * ($inv->quantite - $inv->quantiteR) ;
        }
        return view('detailinventaire',compact('inventaire',
            'montant_total_maquant','debtors','detbtors_table',
            'montant_total_debiteur_inventair'));
    }

    // Create a new inventory debtor balances..
    public function create_inventory_debtor(Request $request )
    {   //
        $clien_id = $request->input('inventaire_debitor');
        $montant = $request->input('inv_montant');
        $motif = $request->input('inv_motif');
        $id = $request->input('inven_id');

        // inventory debtor object.
        $inventory_debtor_balances = new InventoryDebtorBalance();
        $inventory_debtor_balances->inventory_id = $id;
        $inventory_debtor_balances->motif = $motif;
        $inventory_debtor_balances->montant =$montant ;
        $inventory_debtor_balances->solded=0 ;
        $inventory_debtor_balances->inventory_debtor_id =$clien_id;
        // Save the inventory debtor.
        $inventory_debtor_balances->save();

        $fournisseur = InventoryDebtor::find($inventory_debtor_balances->inventory_debtor_id);

        // Mise à jour des informations de l'utilisateur
        $fournisseur->solde = $inventory_debtor_balances->montant + $fournisseur->solde;

        // Sauvegarde des modifications
        $fournisseur->save();
        error_log($inventory_debtor_balances);


        return redirect('/detailinventaire-'.$id) ;
    }
        //
    // Return the lists  of alls inventory debtors
    public function list_inventory_debtors()
    {
        $inventory_debtor = InventoryDebtor::all() ;
            return response()->json($inventory_debtor) ;
    }

   /* public function show3($id)
    {
        $inventaire = DB::table('inventaires')
            ->join('inventaire_modeles', function ($join) {
                $join->on('inventaire_modeles.inventaire_id', '=', 'inventaires.id');
            })
            ->join('modeles', function ($join) {
                $join->on('inventaire_modeles.modele_id', '=', 'modeles.id');
            })
            ->join('produits', function ($join) {
                $join->on('modeles.produit_id', '=', 'produits.id');
            })
            ->join('categories', function ($join) {
                $join->on('produits.categorie_id', '=', 'categories.id');
            })
            ->join('users', function ($join) {
                $join->on('inventaires.user_id', '=', 'users.id');
            })
            ->select('modeles.id as id',
                'modeles.libelle as modele',
                'inventaire_modeles.quantite as quantite',
                'inventaire_modeles.quantite_reelle as quantiteR',
                'inventaire_modeles.justify as justify',
                'produits.nom as produit',
                'inventaires.numero as numero',
                'inventaires.date_inventaire as date',
                'inventaires.date_inventaire_valider as dateF',
                'inventaires.observation as observation',
                'users.nom as utilisateur',
                'users.prenom as prenom',
                'categories.nom as categorie'
            )
            ->where('inventaires.id','=',$id)
            ->get();

            $name = "inventaire_".date('Y-m-d_H-i-s', strtotime( $inventaire && $inventaire[0] ? $inventaire[0]->date : now())).".pdf";
            $pdf = null;
            try{
                $pdf = PDF::loadView('prints/inventairevalider',compact('inventaire'))
                        ->setPaper('a4');
            }catch(Exception $e)
            {}

            if($pdf == null) return view('detailinventaire',compact('inventaire'));
            return $pdf->download();
    }*/

 public function show3($id)
    {
        $inventaire = DB::table('inventaires')
            ->join('inventaire_modeles', function ($join) {
                $join->on('inventaire_modeles.inventaire_id', '=', 'inventaires.id');
            })
            ->join('modeles', function ($join) {
                $join->on('inventaire_modeles.modele_id', '=', 'modeles.id');
            })
            ->join('produits', function ($join) {
                $join->on('modeles.produit_id', '=', 'produits.id');
            })
            ->join('categories', function ($join) {
                $join->on('produits.categorie_id', '=', 'categories.id');
            })
            ->join('users', function ($join) {
                $join->on('inventaires.user_id', '=', 'users.id');
            })
            ->select('modeles.id as id',
                'modeles.libelle as modele',
                'inventaire_modeles.quantite as quantite',
                'inventaire_modeles.quantite_reelle as quantiteR',
                'inventaire_modeles.justify as justify',
                'produits.nom as produit',
                'inventaires.numero as numero',
                'inventaires.date_inventaire as date',
                'inventaires.date_inventaire_valider as dateF',
                'inventaires.observation as observation',
                'users.nom as utilisateur',
                'users.prenom as prenom',
                'categories.nom as categorie'
            )
            ->where('inventaires.id','=',$id)
            ->get();

            $name = "inventaire_".date('Y-m-d_H-i-s', strtotime( $inventaire && $inventaire[0] ? $inventaire[0]->date : now())).".pdf";
            $pdf = null;
            try{
             
            
                $options = new Options();
                $options->set('isHtml5ParserEnabled', true);
                $options->set('isRemoteEnabled', TRUE);
                $options->set('isPhpEnabled', true);
    
                $dompdf = new Dompdf($options);
    
                // Chargez la vue dans Dompdf
                $view = view('prints/inventairevalider', compact('inventaire'))->render();
                $dompdf->loadHtml($view);
    
                // Définissez la taille du papier
                $dompdf->setPaper('a4');
    
                // Rendez le PDF
                $dompdf->render();
    
                // Enregistrez le PDF dans un répertoire
                file_put_contents(public_path("prints/" . $name), $dompdf->output());
        
                
            }catch(Exception $e)
            {}

        //if($pdfPath == null) return view('detailinventaire',compact('inventaire'));
        return response()->download(public_path("prints/" . $name));
    }

    public function update(Request $request)
    {
        $id=DB::table('inventaires')->max('id');
        $modele =Modele::findOrFail($request->input('id'));
        $inventaire=new Inventaire_modeles();
        $inventaire->modele_id =$modele->id;
        $inventaire->quantite =$modele->quantite;
        $inventaire->quantite_reelle =$request->input('quantiteR');
        $inventaire->inventaire_id =$id;
        $inventaire->save();
        $modele->quantite= $request->input('quantiteR');
        $modele->update();
        return [];
    }

    public function update2(Request $request, $_id)
    {
        $id=Inventaires::find($_id)->id;
        $modele =Modele::findOrFail($request->input('id'));
        $inventaire=new Inventaire_modeles();
        $inventaire->modele_id =$modele->id;
        $inventaire->quantite =$modele->quantite;
        $inventaire->quantite_reelle =$request->input('quantiteR');
        $inventaire->justify =$request->input('justify');
        $inventaire->inventaire_id =$id;
        $inventaire->save();
        $modele->quantite= $request->input('quantiteR');
        $modele->update();
        return [];
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


   public function fermer()
    {
        $id=DB::table('inventaires')->max('id');
        $inventaire =Inventaires::findOrFail($id);
        $inventaire->etat=true;
        $inventaire->update();

    }

    public function fermerbydata(Request $request, $id)
    {
        $inventaire =Inventaires::findOrFail($id);
        $inventaire->etat= 1;
        $inventaire->observation= $request->input('obs');
        $inventaire->date_inventaire_valider = now();
        $inventaire->user_valide_id = Auth::user()->id;
        $inventaire->update();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Inventaires::find($id);
        if(is_null($data))
            return response()->json(['error' => 'Resource introuvable'], 404);
        else
        {
            $data->delete();
            return $data;
        }
    }

    public function categorie()
    {
        $categorie=Categorie::all();
        return$categorie;
    }
    public function toutinventaire()
    {
        $modele = DB::table('modeles')
            ->join('produits', function ($join) {
                $join->on('modeles.produit_id', '=', 'produits.id');
            })
            ->join('categories', function ($join) {
                $join->on('produits.categorie_id', '=', 'categories.id');
            })
            ->select('modeles.id as id',
                'modeles.libelle as modele',
                'modeles.quantite as quantite',
                'produits.nom as produit',
                'categories.nom as categorie'
            )
            ->where('modeles.boutique_id', '=', Auth::user()->boutique->id)
            ->get();
        return datatables()->of($modele)
            ->addColumn('action', function ($clt){
                return '
<a class="modal-with-form btn btn-success" onclick="editinventaire('.$clt->id.')"> <i class="fa fa-pencil"></i></a>';
            })
            ->make(true) ;
    }
    public function inventairecategorie($id)
    {
        $modele = DB::table('modeles')
            ->join('produits', function ($join) {
                $join->on('modeles.produit_id', '=', 'produits.id');
            })
            ->join('categories', function ($join) {
                $join->on('produits.categorie_id', '=', 'categories.id');
            })
            ->where('categories.id','=',$id)
            ->where('modeles.boutique_id','=', Auth::user()->boutique->id)
            ->select('modeles.id as id',
                'modeles.libelle as modele',
                'modeles.quantite as quantite',
                'produits.nom as produit',
                'categories.nom as categorie'
            )
            ->get();
        return datatables()->of($modele)
            ->addColumn('action', function ($clt){
                return '
<a class="modal-with-form btn btn-success" onclick="editinventaire('.$clt->id.')"> <i class="fa fa-pencil"></i></a>';
            })
            ->make(true) ;
    }
}
