<?php

namespace App\Http\Controllers;
use App\vente;
use App\Depense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SituationController extends Controller
{
    //

    public function liste()
    {   $boutiques= DB::table('boutiques')
        ->where ('id', '=',Auth::user()->boutique->id )
        ->get();
        //dd($boutiques);
       /*  $totalVente = DB::table('ventes')
        ->select('SUM(ventes.totaux) as total_amount','SUM(ventes.montant_reduction) as montant_reduction')
        ->where ('ventes.boutique_id', '=',Auth::user()->boutique->id )
        ->get();  $ventescredit = DB::table('ventes')
        ->select('SUM(ventes.totaux) as total_amount')
        ->where ('ventes.boutique_id', '=',Auth::user()->boutique->id )
        ->where ('ventes.type', '=',2 )
        ->get(); $VENTENonL = DB::table('ventes')
        ->select('SUM(ventes.totaux) as total_amount')
        ->where ('ventes.boutique_id', '=',Auth::user()->boutique->id )
        ->where ('ventes.type_vente', '=',3)
        ->get(); $reglementVC = DB::table('reglements')
        ->join('ventes','ventes.id','=','reglements.vente_id')
        ->select('SUM(reglements.montant_donne) as total_amount')
        ->where ('ventes.boutique_id', '=',Auth::user()->boutique->id )
        ->get();*/
        $totalVente = Vente::where('boutique_id', Auth::user()->boutique->id)->sum('totaux');
        $totalReduction = Vente::where('boutique_id', Auth::user()->boutique->id)->sum('montant_reduction');

        //dd($totalVente);
       
        $ventescredit = Vente::where('boutique_id', Auth::user()->boutique->id)->where ('type_vente', '=',2 )->sum('totaux');
        

        $venteNette = $totalVente - $totalReduction;
       
        $reglementVC = Vente::join('reglements', 'reglements.vente_id', '=', 'ventes.id')
        ->where ('ventes.boutique_id', '=',Auth::user()->boutique->id )
                    ->sum('reglements.montant_donne');
       //dd($reglementVC);
       
        $VENTENonL = Vente::where('boutique_id', Auth::user()->boutique->id)->where ('type_vente', '=',3 )->sum('totaux');
        $TOTALdepense = Depense::where('boutique_id', Auth::user()->boutique->id)->sum('montant');

        $recetteTotal = $venteNette - $ventescredit +$reglementVC +$VENTENonL;

        return view('situationBoutiques.allSituations',
        compact('boutiques','recetteTotal','VENTENonL','TOTALdepense','totalReduction','reglementVC','venteNette','ventescredit','totalVente'));
    }
}
