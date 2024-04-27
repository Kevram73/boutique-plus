<?php

namespace App\Http\Controllers;

use App\Boutique;
use App\Charges;
use App\Historique;
use App\Journal_divers;

use App\Charge;
use App\File;
use App\CompteBancaire;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChargesController extends Controller
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

    public function fermer()
    {
        $id=DB::table('journal_divers')->max('id');
        $journ= journal_divers::findOrFail($id);
        if ($journ->date_fermeture==null){
            $journ->date_fermeture =now();
            $journ->update();
            return 1;
        }
        else{
            return 2;
        }
    }
    public function verification()
    {
        $id=DB::table('journal_divers')->max('id');
        $journal = DB::table('journal_divers')
            ->where('journal_divers.id', '=', $id)
            ->select('journal_divers.date_fermeture as fermeture','journal_divers.date_creation as creation')
            ->get();
        if ($id==null){
            return 1;
        }
        $d1 = new DateTime($journal[0]->creation);
        if ($d1->format('Y-m-d') !== now()->format('Y-m-d') || $journal[0]->fermeture != null){
            return(2);
        }
        else
        {
            return(3);
        }
    }
    public function journal()
    {

        $journal=new Journal_divers();
        $journal->date_creation =now();
        $journal->mois = now()->format('m');
        $journal->annee = now()->format('Y');
        $journal->user_id = Auth::user()->id;
        $journal->boutique_id =Auth::user()->boutique->id;
        $journal->save();
        $historique=new Historique();
        $historique->actions = "Creer";
        $historique->cible = "Journal des achats";
        $historique->user_id =Auth::user()->id;
        $historique->save();
        return [];
    }
    public function update(Request $request)
    {
        $charge= Charges::findOrFail($request->input('idcharge'));
        $charge->type = $request->input('type');
        $charge->libelle = $request->input('libelle');
        $charge->montant = $request->input('montant');
        $charge->compte_id = $request->input('compte');
        $charge->update();

        $compte_id = $request->input('compte') ;
        $compte = CompteBancaire::find($compte_id);
        $compte->solder = $compte->solder - $charge->montant ;
        $compte->boutique_id = Auth::user()->boutique->id;

            $compte->update();
            
        $historique=new Historique();
        $historique->actions = "Modifier";
        $historique->cible = "Charge";
        $historique->user_id =Auth::user()->id;
        $historique->save();
        return [];
    }
    public function store(Request $request)
    {
        $id=DB::table('journal_divers')->max('id');
        $charge = new Charges();
        $charge->type = $request->input('type');
        $charge->libelle = $request->input('libelle');
        $charge->montant = $request->input('montant');
        $charge->compte_id = $request->input('compte');
        $charge->date = now();
        $charge->journal_divers_id =$id;
        $charge->boutique_id =Auth::user()->boutique->id;
        $charge->save();

        $compte_id = $request->input('compte') ;
        $compte = CompteBancaire::find($compte_id);
        $compte->solder = $compte->solder - $charge->montant ;
        $compte->boutique_id = Auth::user()->boutique->id;
            $compte->save();

        $historique = new Historique();
        $historique->actions = "Creer";
        $historique->cible = "Charges";
        $historique->user_id = Auth::user()->id;
        $historique->save();
        return $request->input();
    }
    public function index()
    {
        $charge = Charges::with('boutique')->where('boutique_id', '=',Auth::user()->boutique->id )->get();
        return datatables()->of($charge)
            ->addColumn('action', function ($clt) {

                return
                        '<a class="btn btn-success" onclick="editcharge(' . $clt->id . ')"> <i class="fa fa-pencil"></i></a>
                        <a class="btn btn-danger" onclick="deletecharge(' . $clt->id . ')"><i class="fa fa-trash-o"></i></a>
                        <a class="btn btn-info" href="/chargeversem-files-' . $clt->id . '"> <i class="fa fa-file"></i></a>
                        <a class="btn btn-info" href="/viewCharge-files-' . $clt->id . '"> <i class="fa fa-eye"></i>    </a>

                        ';
            })
            ->make(true);
    }
    public function justificatifCharge($id)
    {
        $depense = Charges::where('id',$id)->first();

        return view('depenses.chargejustif',compact('depense'));
    }

    public function liste()
    {
        $historique = new Historique();
        $historique->actions = "liste";
        $historique->cible = "Charge";
        $historique->user_id = Auth::user()->id;
        $historique->save(); 
        $banques = DB::table('compte_bancaires')
        ->join('banques','compte_bancaires.banque_id','=','banques.id')
        ->select('compte_bancaires.id as id','compte_bancaires.numero as numero','banques.nom as banques')
        ->get();
        return view('charge',compact('banques'));
    }

    public function destroy($id)
    {
        $charge = Charges::findOrFail($id);
        $charge->delete();
        $historique = new Historique();
        $historique->actions = "Supprimer";
        $historique->cible = "Charge";
        $historique->user_id = Auth::user()->id;
        $historique->save();
        return [];
    }
    public function show($id)
    {
        $historique = new Historique();
        $historique->actions = "Detail";
        $historique->cible = "Charge";
        $historique->user_id = Auth::user()->id;
        $historique->save();
        $charge = Charges::findOrFail($id);
        $historique = new Historique();
        $historique->actions = "Modifier";
        $historique->cible = "Clients";
        $historique->user_id = Auth::user()->id;
        $historique->save();
        return $charge;
    }

    public function diversdate($id)
    {

        $commande = DB::table('charges')
            ->where ('charges.boutique_id', '=',Auth::user()->boutique->id)
            ->where('charges.journal_divers_id', '=', $id)
            ->select('charges.libelle as charge',
                'charges.type',
                'charges.montant as montant')
            ->get();
        return datatables()->of($commande)
            ->make(true) ;
    }

    public function admindiversdate($id,$ed)
    {

        $commande = DB::table('charges')
            ->where ('charges.boutique_id', '=',$ed)
            ->where('charges.journal_divers_id', '=', $id)
            ->select('charges.libelle as charge',
                'charges.type',
                'charges.montant as montant')
            ->get();
        return datatables()->of($commande)
            ->make(true) ;
    }

    public function diversmois($id,$ed)
    {
        $commande = DB::table('charges')
            ->join('journal_divers', function ($join) {
                $join->on('charges.journal_divers_id', '=', 'journal_divers.id');
            })
            ->where ('charges.boutique_id', '=',Auth::user()->boutique->id)
            ->where('journal_divers.mois', '=', $id)
            ->where('journal_divers.annee', '=', $ed)
            ->select('charges.libelle as charge',
                'charges.type',
                'charges.montant as montant')
            ->get();
        return datatables()->of($commande)
            ->make(true) ;
    }

    public function admindiversmois($id,$ed,$ad)
    {
        $commande = DB::table('charges')
            ->join('journal_divers', function ($join) {
                $join->on('charges.journal_divers_id', '=', 'journal_divers.id');
            })
            ->where ('charges.boutique_id', '=',$ad)
            ->where('journal_divers.mois', '=', $id)
            ->where('journal_divers.annee', '=', $ed)
            ->select('charges.libelle as charge',
                'charges.type',
                'charges.montant as montant')
            ->get();
        return datatables()->of($commande)
            ->make(true) ;
    }

    public function diversannee($id)
    {

        $commande = DB::table('charges')
            ->join('journal_divers', function ($join) {
                $join->on('charges.journal_divers_id', '=', 'journal_divers.id');
            })
            ->where ('journal_divers.boutique_id', '=',Auth::user()->boutique->id)
            ->where('journal_divers.annee', '=', $id)
            ->select('charges.libelle as charge',
                'charges.type',
                'charges.montant as montant')
            ->get();
        return datatables()->of($commande)
            ->make(true) ;
    }

    public function admindiversannee($id,$ed)
    {

        $commande = DB::table('charges')
            ->join('journal_divers', function ($join) {
                $join->on('charges.journal_divers_id', '=', 'journal_divers.id');
            })
            ->where ('journal_divers.boutique_id', '=',$ed)
            ->where('journal_divers.annee', '=', $id)
            ->select('charges.libelle as charge',
                'charges.type',
                'charges.montant as montant')
            ->get();
        return datatables()->of($commande)
            ->make(true) ;
    }





    public function totaljour($id)
    {
        $depense = DB::table('charges')
            ->where ('charges.boutique_id', '=',Auth::user()->boutique->id)
            ->where('charges.journal_divers_id', '=', $id)
            ->sum('charges.montant');
        return $depense;
    }

    public function admintotaljour($id,$ed)
    {
        $depense = DB::table('charges')
            ->where ('charges.boutique_id', '=',$ed)
            ->where('charges.journal_divers_id', '=', $id)
            ->sum('charges.montant');
        return $depense;
    }
    public function totalmois($id,$ed)
    {
        $depense = DB::table('charges')
            ->join('journal_divers', function ($join) {
                $join->on('charges.journal_divers_id', '=', 'journal_divers.id');
            })
            ->where ('charges.boutique_id', '=',Auth::user()->boutique->id)
            ->where('journal_divers.mois', '=', $id)
            ->where('journal_divers.annee', '=', $ed)
            ->sum('charges.montant');
        return $depense;
    }
    public function admintotalmois($id,$ed,$ad)
    {
        $depense = DB::table('charges')
            ->join('journal_divers', function ($join) {
                $join->on('charges.journal_divers_id', '=', 'journal_divers.id');
            })
            ->where ('charges.boutique_id', '=',$ad)
            ->where('journal_divers.mois', '=', $id)
            ->where('journal_divers.annee', '=', $ed)
            ->sum('charges.montant');
        return $depense;
    }
    public function totalannee($id)
    {
        $depense = DB::table('charges')
            ->join('journal_divers', function ($join) {
                $join->on('charges.journal_divers_id', '=', 'journal_divers.id');
            })
            ->where ('charges.boutique_id', '=',Auth::user()->boutique->id)
            ->where('journal_divers.annee', '=', $id)
            ->sum('charges.montant');
        return $depense;
    }

    public function admintotalannee($id,$ed)
    {
        $depense = DB::table('charges')
            ->join('journal_divers', function ($join) {
                $join->on('charges.journal_divers_id', '=', 'journal_divers.id');
            })
            ->where ('charges.boutique_id', '=',$ed)
            ->where('journal_divers.annee', '=', $id)
            ->sum('charges.montant');
        return $depense;
    }


    public function recuperdatedivers()
    {
        $date = DB::table('charges')
            ->join('journal_divers', function ($join) {
                $join->on('charges.journal_divers_id', '=', 'journal_divers.id');
            })
            ->where ('charges.boutique_id', '=',Auth::user()->boutique->id)
            ->select('journal_divers.id as journal','journal_divers.date_creation as date')
            ->groupBy('journal_divers.id', 'journal_divers.date_creation')
            ->get() ;
        $a=array();
        $d=array();
        $c=array();
        $table=array();
        if($date==null){
            return $date;
        }
        else{
            for ($i = 0; $i <count($date); $i++) {
                setlocale(LC_TIME,'fr_FR','fra_FRA');
                $b=strftime('%A %d %B %G', strtotime($date[$i]->date));
                $a[$i]=$b;
                $d[$i]=$date[$i]->journal;
                $c=mb_convert_encoding($a,'UTF-8','UTF-8');
            }
            $table["fran"]=$c;
            $table["id"]=$d;
            return $table;
        }

        return $table;
    }

    public function adminrecuperdatedivers($id)
    {
        $date = DB::table('charges')
            ->join('journal_divers', function ($join) {
                $join->on('charges.journal_divers_id', '=', 'journal_divers.id');
            })
            ->where ('charges.boutique_id', '=',$id)
            ->select('journal_divers.id as journal','journal_divers.date_creation as date')
            ->groupBy('journal_divers.id', 'journal_divers.date_creation')
            ->get() ;
        $a=array();
        $d=array();
        $c=array();
        $table=array();
        if($date==null){
            return $date;
        }
        else{
            for ($i = 0; $i <count($date); $i++) {
                setlocale(LC_TIME,'fr_FR','fra_FRA');
                $b=strftime('%A %d %B %G', strtotime($date[$i]->date));
                $a[$i]=$b;
                $d[$i]=$date[$i]->journal;
                $c=mb_convert_encoding($a,'UTF-8','UTF-8');
            }
            $table["fran"]=$c;
            $table["id"]=$d;
            return $table;
        }

        return $table;
    }
    public function annee()
    {

        $date = DB::table('charges')
            ->join('journal_divers', function ($join) {
                $join->on('charges.journal_divers_id', '=', 'journal_divers.id');
            })
            ->where ('charges.boutique_id', '=',Auth::user()->boutique->id)
            ->select('journal_divers.annee as annee')
            ->groupBy ('journal_divers.annee')
            ->get() ;
        return $date;
    }

    public function adminannee($id)
    {

        $date = DB::table('charges')
            ->join('journal_divers', function ($join) {
                $join->on('charges.journal_divers_id', '=', 'journal_divers.id');
            })
            ->where ('charges.boutique_id', '=',$id)
            ->select('journal_divers.annee as annee')
            ->groupBy ('journal_divers.annee')
            ->get() ;
        return $date;
    }


    public function historique()
    {
        return view('historiquecharges');
    }

    public function adminhistorique()
    {
        $boutiques=Boutique::all();
        return view('adminhistoriquedivers',compact('boutiques'));
    }

    public function create_depense_file($id)
    {
        $depense = Charges::findorfail($id);
        $historique = new Historique();
        $historique->actions = "Créer";
        $historique->cible = "Dépense file";
        $historique->user_id = Auth::user()->id;
        $historique->save();
        return view('filecharge', compact('depense'));
    }

    public function store_depense(Request $request)
    {
        DB::beginTransaction();

        $record = Charges::findorfail($request->depense_id);

        $name = File::newFile($request->file, "justify");
       
        if($name)
        {
            if (request()->hasFile('file')) {
                $file = request()->file('file');
                $record->justificatif = $name;
            }
        
            $record->save();
        }
        $record->statut = 1;
        $record->update();

        $modele = CompteBancaire::find($record->compte_id);
        $modele->solder = $modele->solder - $record->montant;
        $modele->save();
        DB::commit();

        return redirect('charges')->with('success', 'Fichier Enregistrer');

    }
    public function get_solde(Request $request){
        $account_id = $request->input('compte') ;
        $result = DB::table('compte_bancaires')
            ->where('id','=',$account_id)
            ->get();
         error_log($result);
        return response()->json($result) ;
    }


}
