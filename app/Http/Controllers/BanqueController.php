<?php

namespace App\Http\Controllers;
use DateTime;

use App\AgenceBanque;
use App\Banque;
use App\Journal;
use App\Historique;
use App\CompteBancaire;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class BanqueController extends Controller
{
    // Create banque
    public function create_banque(Request $request)
    {
    $new_banque = new Banque();
    $new_banque->nom = $request->input('bName');
    $new_banque->description = $request->input('bDesc');
    $new_banque->contact = $request->input('contact');
    $new_banque->save();
    error_log($new_banque);
    return $request->input();
    }

    public function create_agence(Request $request)
    {
    $new_banque = new AgenceBanque();
    $new_banque->nom = $request->input('nom');
    $new_banque->ville = $request->input('ville');
    $new_banque->banque_id = $request->input('banque_id');
    $new_banque->quartier = $request->input('quartier');
    $new_banque->contact = $request->input('contact');
    //dd($new_banque);
    $new_banque->save();
    error_log($new_banque);
    return $request->input();
    }
    // update banques
    public function update_banque(Request $request)
    {
        $modele = Banque::findOrFail($request->input('idnomb'));
        $modele->nom = $request->input('bName');
        $modele->description = $request->input('bDesc');
        $modele->contact = $request->input('contact');
        $modele->update(); 
        $historique=new Historique();
        $historique->actions = "Modifier";
        $historique->cible = "Banques";
        $historique->user_id =Auth::user()->id;
        $historique->save();
        return [];

    }

  // update compte
  public function update_compte(Request $request)
  {
      $modele = CompteBancaire::findOrFail($request->input('idnom'));
      $modele->banque_id = $request->input('banque_id');
      $modele->type = $request->input('type');
      
      $modele->numero = $request->input('numero');
      $modele->update(); 
      $historique=new Historique();
      $historique->actions = "Modifier";
      $historique->cible = "comptes";
      $historique->user_id =Auth::user()->id;
      $historique->save();
      return [];

  }
  
    
    // desactivate banques
    public function  desactivate_banque(){

    }
   /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $modele= Banque::findOrFail($id);
        //dd($modele);
        $modele ->delete();
        $historique=new Historique();
        $historique->actions = "Supprimer";
        $historique->cible = "Banques";
        $historique->user_id =Auth::user()->id;
        $historique->save();

        error_log($modele);
        return Redirect()->back();
    }

    // liste banques.
    public function list_banques(){
    $banks = Banque::all() ;
    error_log($banks);
    return view('banque.list_bank',
        compact('banks')) ;
    }
    
    public function listagences(){
        $agences = DB::table('agence_banques')
        ->join('banques','banques.id','agence_banques.banque_id')
        ->select('agence_banques.*','banques.nom')
        ->get();
        $boutique = Banque::all() ;
        error_log($agences);
        return view('banque.list_agences',
            compact('agences','boutique')) ;
        }

    public function list_comptes()
    {
            $comptes_bancaires = DB::table('compte_bancaires')
            ->join('banques','banques.id','compte_bancaires.banque_id')
            ->select('banques.nom as banque','compte_bancaires.*')
            ->get();
           // dd($comptes_bancaires);
        $agences = Banque::all();
            error_log($comptes_bancaires);
            
            return view('banque.list_comptes',
                compact('comptes_bancaires','agences')) ;
    }

    // update agence
    public function update_agence(){}

    // list agence for a banques

    public function  list_agence_of_bank(Request $request,$bank_id)
    {
    $data = Banque::where('id',$bank_id)->agences() ;
    return $data ;
    }

    // return the list of account of a given bank.
    public function get_account_for_bank(Request  $request){
        $boutique_id = $request->input('boutique');
        $bank_id = $request->input('banque');

        //banques
        //compte_bancaires
        //agence
        $result = DB::table('banques')
            ->join('agence_banques',function ($join){
                $join->on('banques.id','=','agence_banques.banque_id');
            })
            ->join('compte_bancaires',function ($join){
                $join->on('compte_bancaires.agence_id',
                    '=','agence_banques.id');
            })
            ->where('compte_bancaires.boutique_id','=',$boutique_id)
            ->where('banques.id','=',$bank_id)
            ->get();
        error_log($request);

        return response()->json($result);

    }

    // get sold
    public function get_solde(Request $request){
        $account_id = $request->input('compte') ;
        $result = DB::table('compte_bancaires')
            ->where('id','=',$account_id)
            ->get();
         error_log($result);
        return response()->json($result) ;
    }

    public function create_compte(Request $request)
    {
        
        $id=DB::table('compte_bancaires')->max('id');
        $ed=1+$id;
        $compte = new CompteBancaire();
        $compte ->numero= $request->input('numero');

        $compte ->boutique_id= Auth::user()->boutique->id;
        $compte->banque_id = $request->input('banque_id');

        $compte->type =  $request->input('type');
        $compte->solder = 0;
        $compte->save();
        error_log($compte);
        return $request->input();
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
        $historique->cible = "banque";
        $historique->user_id =Auth::user()->id;
        $historique->save();
        $categorie= Banque::findOrFail($id);
        //dd($categorie);
        return $categorie;
    }
    
    public function showdetailcompt($id)
    {
        $historique=new Historique();
        $historique->actions = "detail";
        $historique->cible = "compte";
        $historique->user_id =Auth::user()->id;
        $historique->save();
        $categorie= CompteBancaire::findOrFail($id);
        //dd($categorie);
        return $categorie;
    }
    public function showdetail($id)
    {
        $historique=new Historique();
        $historique->actions = "detail";
        $historique->cible = "banque";
        $historique->user_id =Auth::user()->id;
        $historique->save();
        $categorie= 
        //dd($categorie);
        
        $categorie= DB::table('compte_bancaires')
        ->LEFTjoin('banques','compte_bancaires.banque_id','banques.id')
        ->where('banque_id',$id)
        ->select('banques.*','compte_bancaires.*')->get();
        return view('banque.detailbanque',compact('categorie'));
    }
    
    public function showdetailcompte($id)
    {
        $historique=new Historique();
        $historique->actions = "detail";
        $historique->cible = "compte";
        $historique->user_id =Auth::user()->id;
        $historique->save();
        $categorie= 
        //dd($categorie);
        
        $categorie= DB::table('compte_bancaires')
        ->join('banques','compte_bancaires.banque_id','banques.id')
        //->join('reglements','')
        ->where('compte_bancaires.id',$id)
        ->select('banques.*','compte_bancaires.*')->get();

        $reglements= DB::table('compte_bancaires')
        ->join('reglement_achats','compte_bancaires.id','reglement_achats.compte_id')
        ->join('fournisseurs','fournisseurs.id','reglement_achats.fournisseur_id')
        ->where('reglement_achats.compte_id',$id)
        ->select('reglement_achats.*','compte_bancaires.*','fournisseurs.nom as nom')->get();

        $versements= DB::table('compte_bancaires')
        ->join('versements','compte_bancaires.id','versements.compte_id')
        ->where('versements.compte_id',$id)
        ->where('versements.statut',1)
        ->select('versements.*','compte_bancaires.*')->get();


        return view('banque.detailcompte',compact('categorie','reglements','versements'));
    }

    public function fermer()
    {
        $id=DB::table('journals')->max('id');
        $journ= journal::findOrFail($id);
        if ($journ->date_fermeture==null){
            $journ->date_fermeture =now();
            $journ->update();
            return 1;
        }
        else{
            return 2;
        }

    }
    
    public function destroycompte($id)
    {
        $modele= CompteBancaire::findOrFail($id);
        //dd($modele);
        $modele ->delete();
        $historique=new Historique();
        $historique->actions = "Supprimer";
        $historique->cible = "Comptes";
        $historique->user_id =Auth::user()->id;
        $historique->save();

        error_log($modele);
        return Redirect()->back();
    }

}
