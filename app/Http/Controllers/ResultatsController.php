<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;
use App;
use Illuminate\Support\Facades\DB;

class ResultatsController extends Controller
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
        //
    }
    public function resultat()
    {
        $date = DB::table('commandes')
            ->join('journal_achats', function ($join) {
                $join->on('commandes.journal_achat_id', '=', 'journal_achats.id');
            })
            ->where ('commandes.boutique_id', '=',Auth::user()->boutique->id)
            ->select ('journal_achats.annee as annee')
            ->groupBy ('journal_achats.annee')
            ->get() ;
        return view ('resultat',compact('date'));
    }

    public function resultatsuper()
    {
        $date = DB::table('commandes')
            ->join('journal_achats', function ($join) {
                $join->on('commandes.journal_achat_id', '=', 'journal_achats.id');
            })
            ->where ('commandes.boutique_id', '=',Auth::user()->boutique->id)
            ->select ('journal_achats.annee as annee')
            ->groupBy ('journal_achats.annee')
            ->get() ;
        return view ('resultatsuper',compact('date'));
    }
    public function resultatjr()
    {
        $jour = DB::select('SELECT DISTINCT DATE_FORMAT(created_at, "%y-%m-%d") as jour FROM `ventes` WHERE boutique_id = ? ORDER BY jour DESC;', [Auth::user()->boutique->id]);

        // $jour = DB::table('ventes')
        //     ->where ('ventes.boutique_id', '=',Auth::user()->boutique->id)
        //     ->select ('created_at as jour')
        //     ->groupBy ('created_at')
        //     ->get();

        $a=array();
        $d=array();
        $table=array();
        for ($i = 0; $i <count($jour); $i++) {
            setlocale(LC_TIME,'fr_FR','fra_FRA');
            $b=strftime('%A %d %B %G', strtotime($jour[$i]->jour));
            $a[$i]=$b;
            $d[$i]=$jour[$i]->jour;
            $c=mb_convert_encoding($a,'UTF-8','UTF-8');
        }
        $table["fran"]=$c;
        $table["id"]=$d;

        return $table;
    }

    public function resultatsuperjr()
    {
        $jour = DB::select('SELECT DISTINCT DATE_FORMAT(created_at, "%y-%m-%d") as jour FROM `ventes`  ORDER BY jour DESC;');

        // $jour = DB::table('ventes')
        //     ->where ('ventes.boutique_id', '=',Auth::user()->boutique->id)
        //     ->select ('created_at as jour')
        //     ->groupBy ('created_at')
        //     ->get();

        $a=array();
        $d=array();
        $table=array();
        for ($i = 0; $i <count($jour); $i++) {
            setlocale(LC_TIME,'fr_FR','fra_FRA');
            $b=strftime('%A %d %B %G', strtotime($jour[$i]->jour));
            $a[$i]=$b;
            $d[$i]=$jour[$i]->jour;
            $c=mb_convert_encoding($a,'UTF-8','UTF-8');
        }
        $table["fran"]=$c;
        $table["id"]=$d;

        return $table;
    }

    public function tableau($id)
    {
        $table=array();
        $c = DB::table('charges')
            ->join('journal_divers', function ($join) {
                $join->on('charges.journal_divers_id', '=', 'journal_divers.id');
            })
            ->where ('journal_divers.annee', '=',$id)
            ->where('charges.boutique_id', '=',Auth::user()->boutique->id)
            ->select ('charges.montant as charge')
            ->sum ('charges.montant');
        $ca = DB::table('ventes')
            ->join('journals', function ($join) {
                $join->on('ventes.journal_id', '=', 'journals.id');
            })
            ->where ('journals.annee', '=',$id)
            ->where('ventes.boutique_id', '=',Auth::user()->boutique->id)
            ->select ('ventes.totaux as vente')
            ->sum ('ventes.totaux');


        $mnr = DB::table('ventes')
        ->join('reglements', function ($join) {
            $join->on('reglements.vente_id', '=', 'ventes.id');
        })
            ->join('journals', function ($join) {
                $join->on('ventes.journal_id', '=', 'journals.id');
            })
            ->where ('journals.annee', '=',$id)
            ->where ('ventes.type_vente', '=',2)
            ->where('ventes.boutique_id', '=',Auth::user()->boutique->id)
            ->select ('ventes.totaux as vente,reglements.montant_restant')
            ->sum ('reglements.montant_restant');


        $table=array();
        $cpv =0;
        $det = DB::table('preventes')
            ->join('modele_fournisseurs', function ($join) {
                $join->on('preventes.modele_fournisseur_id', '=', 'modele_fournisseurs.id');
            })
            ->join('ventes', function ($join) {
                $join->on('preventes.vente_id', '=', 'ventes.id');
            })
            ->join('journals', function ($join) {
                $join->on('ventes.journal_id', '=', 'journals.id');
            })
            ->where ('journals.annee', '=',$id)
            ->where('ventes.boutique_id', '=',Auth::user()->boutique->id)
            ->select ('preventes.quantite as quantite','modele_fournisseurs.prix as prix')
            ->get();
        for ($i = 0; $i <count($det); $i++)
        {
            $table[$i] =( $det[$i]->quantite * $det[$i]->prix);
            $cpv+=  $table[$i];
        }
        $i = DB::table('charges')
            ->join('journal_divers', function ($join) {
                $join->on('charges.journal_divers_id', '=', 'journal_divers.id');
            })
            ->where ('journal_divers.annee', '=',$id)
            ->where('charges.boutique_id', '=',Auth::user()->boutique->id)
            ->select ('charges.montant as impot')
            ->where ('charges.type','=','impots')
            ->sum ('charges.montant');

        $depensetotal = DB::table('depenses')
        ->where('depenses.boutique_id', '=',Auth::user()->boutique->id)
        ->sum('montant');

        $table=[$c,$ca,$mnr,$cpv,$i, $depensetotal];
        return $table;
    }

    public function tableausuper($id)
    {
        $table=array();
        $c = DB::table('charges')
            ->join('journal_divers', function ($join) {
                $join->on('charges.journal_divers_id', '=', 'journal_divers.id');
            })
            ->where ('journal_divers.annee', '=',$id)
            //->where('charges.boutique_id', '=',Auth::user()->boutique->id)
            ->select ('charges.montant as charge')
            ->sum ('charges.montant');
        $ca = DB::table('ventes')
            ->join('journals', function ($join) {
                $join->on('ventes.journal_id', '=', 'journals.id');
            })
            ->where ('journals.annee', '=',$id)
           // ->where('ventes.boutique_id', '=',Auth::user()->boutique->id)
            ->select ('ventes.totaux as vente')
            ->sum ('ventes.totaux');


        $mnr = DB::table('ventes')
        ->join('reglements', function ($join) {
            $join->on('reglements.vente_id', '=', 'ventes.id');
        })
            ->join('journals', function ($join) {
                $join->on('ventes.journal_id', '=', 'journals.id');
            })
            ->where ('journals.annee', '=',$id)
            ->where ('ventes.type_vente', '=',2)
            //->where('ventes.boutique_id', '=',Auth::user()->boutique->id)
            ->select ('ventes.totaux as vente,reglements.montant_restant')
            ->sum ('reglements.montant_restant');


        $table=array();
        $cpv =0;
        $det = DB::table('preventes')
            ->join('modele_fournisseurs', function ($join) {
                $join->on('preventes.modele_fournisseur_id', '=', 'modele_fournisseurs.id');
            })
            ->join('ventes', function ($join) {
                $join->on('preventes.vente_id', '=', 'ventes.id');
            })
            ->join('journals', function ($join) {
                $join->on('ventes.journal_id', '=', 'journals.id');
            })
            ->where ('journals.annee', '=',$id)
            //->where('ventes.boutique_id', '=',Auth::user()->boutique->id)
            ->select ('preventes.quantite as quantite','modele_fournisseurs.prix as prix')
            ->get();
        for ($i = 0; $i <count($det); $i++)
        {
            $table[$i] =( $det[$i]->quantite * $det[$i]->prix);
            $cpv+=  $table[$i];
        }
        $i = DB::table('charges')
            ->join('journal_divers', function ($join) {
                $join->on('charges.journal_divers_id', '=', 'journal_divers.id');
            })
            ->where ('journal_divers.annee', '=',$id)
            //->where('charges.boutique_id', '=',Auth::user()->boutique->id)
            ->select ('charges.montant as impot')
            ->where ('charges.type','=','impots')
            ->sum ('charges.montant');

        $depensetotal = DB::table('depenses')
        //->where('depenses.boutique_id', '=',Auth::user()->boutique->id)
        ->sum('montant');

        $table=[$c,$ca,$mnr,$cpv,$i, $depensetotal];
        return $table;
    }
    public function exemple($id)
    {
        $table=array();
        $cpv =0;
        $det = DB::table('preventes')
            ->join('modele_fournisseurs', function ($join) {
                $join->on('preventes.modele_fournisseur_id', '=', 'modele_fournisseurs.id');
            })
            ->join('ventes', function ($join) {
                $join->on('preventes.vente_id', '=', 'ventes.id');
            })
            ->join('journals', function ($join) {
                $join->on('ventes.journal_id', '=', 'journals.id');
            })
            ->where ('journals.annee', '=',$id)
            ->where('ventes.boutique_id', '=',Auth::user()->boutique->id)
            ->select ('preventes.quantite as quantite','modele_fournisseurs.prix as prix')
            ->get();
        for ($i = 0; $i <count($det); $i++) {
            $table[$i] =( $det[$i]->quantite * $det[$i]->prix);
            $cpv+=  $table[$i];
        }
        return $det;
    }

    public function exemplesuper($id)
    {
        $table=array();
        $cpv =0;
        $det = DB::table('preventes')
            ->join('modele_fournisseurs', function ($join) {
                $join->on('preventes.modele_fournisseur_id', '=', 'modele_fournisseurs.id');
            })
            ->join('ventes', function ($join) {
                $join->on('preventes.vente_id', '=', 'ventes.id');
            })
            ->join('journals', function ($join) {
                $join->on('ventes.journal_id', '=', 'journals.id');
            })
            ->where ('journals.annee', '=',$id)
            //->where('ventes.boutique_id', '=',Auth::user()->boutique->id)
            ->select ('preventes.quantite as quantite','modele_fournisseurs.prix as prix')
            ->get();
        for ($i = 0; $i <count($det); $i++) {
            $table[$i] =( $det[$i]->quantite * $det[$i]->prix);
            $cpv+=  $table[$i];
        }
        return $det;
    }
    public function tableaujr($id)
    {
        $table=array();
        $c = DB::table('charges')
            ->whereDate('created_at', '=',$id)
            ->where('boutique_id', '=',Auth::user()->boutique->id)
            ->select ('charges.montant as charge')
            ->sum ('charges.montant');
        $ca = DB::table('ventes')
            ->whereDate('created_at', '=',$id)
            ->where('boutique_id', '=',Auth::user()->boutique->id)
            ->select ('ventes.totaux as vente')
            ->sum ('ventes.totaux');
        $table=array();
        $cpv =0;
        $det = DB::table('preventes')
            ->join('modele_fournisseurs', function ($join) {
                $join->on('preventes.modele_fournisseur_id', '=', 'modele_fournisseurs.id');
            })
            ->join('ventes', function ($join) {
                $join->on('preventes.vente_id', '=', 'ventes.id');
            })
            ->join('journals', function ($join) {
                $join->on('ventes.journal_id', '=', 'journals.id');
            })
            ->whereDate ('ventes.created_at', '=',$id)
            ->where('ventes.boutique_id', '=',Auth::user()->boutique->id)
            ->select ('preventes.quantite as quantite','modele_fournisseurs.prix as prix')
            ->get();
        for ($i = 0; $i <count($det); $i++) {
            $table[$i] =( $det[$i]->quantite * $det[$i]->prix);
            $cpv+=  $table[$i];
        }
        $i = DB::table('charges')
            ->whereDate('created_at', '=',$id)
            ->where('boutique_id', '=',Auth::user()->boutique->id)
            ->select ('charges.montant as impot')
            ->where ('charges.type','=','impots')
            ->sum ('charges.montant');

            $mnr = DB::table('ventes')
        ->join('reglements', function ($join) {
            $join->on('reglements.vente_id', '=', 'ventes.id');
        })
            ->join('journals', function ($join) {
                $join->on('ventes.journal_id', '=', 'journals.id');
            })
            ->whereDate('ventes.created_at', '=',$id)
            ->where ('ventes.type_vente', '=',2)
            ->where('ventes.boutique_id', '=',Auth::user()->boutique->id)
            ->select ('ventes.totaux as vente,reglements.montant_restant')
            ->sum ('reglements.montant_restant');

            $depensetotal = DB::table('depenses')
        ->where('depenses.boutique_id', '=',Auth::user()->boutique->id)
        ->whereDate('created_at', '=',$id)
        ->sum('montant');


        $table=[$c,$ca,$mnr, $cpv,$i, $depensetotal];
        return $table;
    }

    public function tableausuperjr($id)
    {
        $table=array();
        $c = DB::table('charges')
            ->whereDate('created_at', '=',$id)
            //->where('boutique_id', '=',Auth::user()->boutique->id)
            ->select ('charges.montant as charge')
            ->sum ('charges.montant');
        $ca = DB::table('ventes')
            ->whereDate('created_at', '=',$id)
            //->where('boutique_id', '=',Auth::user()->boutique->id)
            ->select ('ventes.totaux as vente')
            ->sum ('ventes.totaux');
        $table=array();
        $cpv =0;
        $det = DB::table('preventes')
            ->join('modele_fournisseurs', function ($join) {
                $join->on('preventes.modele_fournisseur_id', '=', 'modele_fournisseurs.id');
            })
            ->join('ventes', function ($join) {
                $join->on('preventes.vente_id', '=', 'ventes.id');
            })
            ->join('journals', function ($join) {
                $join->on('ventes.journal_id', '=', 'journals.id');
            })
            ->whereDate ('ventes.created_at', '=',$id)
           // ->where('ventes.boutique_id', '=',Auth::user()->boutique->id)
            ->select ('preventes.quantite as quantite','modele_fournisseurs.prix as prix')
            ->get();
        for ($i = 0; $i <count($det); $i++) {
            $table[$i] =( $det[$i]->quantite * $det[$i]->prix);
            $cpv+=  $table[$i];
        }
        $i = DB::table('charges')
            ->whereDate('created_at', '=',$id)
            //->where('boutique_id', '=',Auth::user()->boutique->id)
            ->select ('charges.montant as impot')
            ->where ('charges.type','=','impots')
            ->sum ('charges.montant');

            $mnr = DB::table('ventes')
        ->join('reglements', function ($join) {
            $join->on('reglements.vente_id', '=', 'ventes.id');
        })
            ->join('journals', function ($join) {
                $join->on('ventes.journal_id', '=', 'journals.id');
            })
            ->whereDate('ventes.created_at', '=',$id)
            ->where ('ventes.type_vente', '=',2)
            //->where('ventes.boutique_id', '=',Auth::user()->boutique->id)
            ->select ('ventes.totaux as vente,reglements.montant_restant')
            ->sum ('reglements.montant_restant');

            $depensetotal = DB::table('depenses')
       // ->where('depenses.boutique_id', '=',Auth::user()->boutique->id)
        ->whereDate('created_at', '=',$id)
        ->sum('montant');


        $table=[$c,$ca,$mnr, $cpv,$i, $depensetotal];
        return $table;
    }


    public function tableaumois($id,$ed)
    {
        $table=array();
        $c = DB::table('charges')
            ->join('journal_divers', function ($join) {
                $join->on('charges.journal_divers_id', '=', 'journal_divers.id');
            })
            ->where ('journal_divers.annee', '=',$ed)
            ->where('journal_divers.mois', '=', $id)
            ->where('charges.boutique_id', '=',Auth::user()->boutique->id)
            ->select ('charges.montant as charge')
            ->sum ('charges.montant');
        $ca = DB::table('ventes')
            ->join('journals', function ($join) {
                $join->on('ventes.journal_id', '=', 'journals.id');
            })
            ->where ('journals.annee', '=',$ed)
            ->where('journals.mois', '=', $id)
            ->where('ventes.boutique_id', '=',Auth::user()->boutique->id)
            ->select ('ventes.totaux as vente')
            ->sum ('ventes.totaux');
        $table=array();
        $cpv =0;
        $det = DB::table('preventes')
            ->join('modeles', function ($join) {
                $join->on('preventes.modele_fournisseur_id', '=', 'modeles.id');
            })
            ->join('ventes', function ($join) {
                $join->on('preventes.vente_id', '=', 'ventes.id');
            })
            ->join('journals', function ($join) {
                $join->on('ventes.journal_id', '=', 'journals.id');
            })
            ->where ('journals.annee', '=',$ed)
            ->where('journals.mois', '=', $id)
            ->where('ventes.boutique_id', '=',Auth::user()->boutique->id)
            ->select ('preventes.quantite as quantite','modeles.prix_achat as prix')
            ->get();
        for ($i = 0; $i <count($det); $i++) {
            $table[$i] =( $det[$i]->quantite * $det[$i]->prix);
            $cpv+=  $table[$i];
        }
        $i = DB::table('charges')
            ->join('journal_divers', function ($join) {
                $join->on('charges.journal_divers_id', '=', 'journal_divers.id');
            })
            ->where ('journal_divers.annee', '=',$ed)
            ->where('journal_divers.mois', '=', $id)
            ->select ('charges.montant as impot')
            ->where('charges.boutique_id', '=',Auth::user()->boutique->id)
            ->where ('charges.type','=','impots')
            ->sum ('charges.montant');
            //dd($i);
            $datecalc = $ed.'-'.(strlen($id) == 1 ? '0'.$id : $id).'-%';

            $depensetotal = DB::table('depenses')
        ->where('depenses.boutique_id', '=',Auth::user()->boutique->id)
        ->where('date_dep', 'like',$datecalc)
        ->sum('montant');

        $mnr = DB::table('ventes')
        ->join('reglements', function ($join) {
            $join->on('reglements.vente_id', '=', 'ventes.id');
        })
            ->join('journals', function ($join) {
                $join->on('ventes.journal_id', '=', 'journals.id');
            })
            ->where ('journals.annee', '=',$ed)
            ->where('journals.mois', '=', $id)
            ->where ('ventes.type_vente', '=',2)
            ->where('ventes.boutique_id', '=',Auth::user()->boutique->id)
            ->select ('ventes.totaux as vente,reglements.montant_restant')
            ->sum ('reglements.montant_restant');

        $table=[$c,$ca, $mnr, $cpv,$i, $depensetotal];
        return $table;
    }

    public function tableausupermois($id,$ed)
    {
        $table=array();
        $c = DB::table('charges')
            ->join('journal_divers', function ($join) {
                $join->on('charges.journal_divers_id', '=', 'journal_divers.id');
            })
            ->where ('journal_divers.annee', '=',$ed)
            ->where('journal_divers.mois', '=', $id)
           // ->where('charges.boutique_id', '=',Auth::user()->boutique->id)
            ->select ('charges.montant as charge')
            ->sum ('charges.montant');
        $ca = DB::table('ventes')
            ->join('journals', function ($join) {
                $join->on('ventes.journal_id', '=', 'journals.id');
            })
            ->where ('journals.annee', '=',$ed)
            ->where('journals.mois', '=', $id)
            //->where('ventes.boutique_id', '=',Auth::user()->boutique->id)
            ->select ('ventes.totaux as vente')
            ->sum ('ventes.totaux');
        $table=array();
        $cpv =0;
        $det = DB::table('preventes')
            ->join('modeles', function ($join) {
                $join->on('preventes.modele_fournisseur_id', '=', 'modeles.id');
            })
            ->join('ventes', function ($join) {
                $join->on('preventes.vente_id', '=', 'ventes.id');
            })
            ->join('journals', function ($join) {
                $join->on('ventes.journal_id', '=', 'journals.id');
            })
            ->where ('journals.annee', '=',$ed)
            ->where('journals.mois', '=', $id)
           // ->where('ventes.boutique_id', '=',Auth::user()->boutique->id)
            ->select ('preventes.quantite as quantite','modeles.prix_achat as prix')
            ->get();
        for ($i = 0; $i <count($det); $i++) {
            $table[$i] =( $det[$i]->quantite * $det[$i]->prix);
            $cpv+=  $table[$i];
        }
        $i = DB::table('charges')
            ->join('journal_divers', function ($join) {
                $join->on('charges.journal_divers_id', '=', 'journal_divers.id');
            })
            ->where ('journal_divers.annee', '=',$ed)
            ->where('journal_divers.mois', '=', $id)
            ->select ('charges.montant as impot')
            //->where('charges.boutique_id', '=',Auth::user()->boutique->id)
            ->where ('charges.type','=','impots')
            ->sum ('charges.montant');

            $datecalc = $ed.'-'.(strlen($id) == 1 ? '0'.$id : $id).'-%';

            $depensetotal = DB::table('depenses')
        //->where('depenses.boutique_id', '=',Auth::user()->boutique->id)
        ->where('date_dep', 'like',$datecalc)
        ->sum('montant');

        $mnr = DB::table('ventes')
        ->join('reglements', function ($join) {
            $join->on('reglements.vente_id', '=', 'ventes.id');
        })
            ->join('journals', function ($join) {
                $join->on('ventes.journal_id', '=', 'journals.id');
            })
            ->where ('journals.annee', '=',$ed)
            ->where('journals.mois', '=', $id)
            ->where ('ventes.type_vente', '=',2)
            //->where('ventes.boutique_id', '=',Auth::user()->boutique->id)
            ->select ('ventes.totaux as vente,reglements.montant_restant')
            ->sum ('reglements.montant_restant');

        $table=[$c,$ca, $mnr, $cpv,$i, $depensetotal];
        return $table;
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
