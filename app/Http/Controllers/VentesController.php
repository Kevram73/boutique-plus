<?php

namespace App\Http\Controllers;

use App\Boutique;
use App\Client;
use App\Facture;
use App\Historique;
use App\Journal;
use App\Modele;
use App\Prevente;
use App\Produit;
use App\Reglement;
use App\vente;
use App\Livraison;
use App\livraisonCommande;
use App\DevisVente;
use App\DevisLignesVente;
use Illuminate\Support\Facades\DB;
use App\Categorie;
use App\Commande;
use App\FactureFictive;
use App\produitProvision;
use App\Retour;
use App\RetourLigne;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use RealRashid\SweetAlert\Facades\Alert;
use PDF;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\View;

class VentesController extends Controller
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
        $vente = vente::with('user')->
        with('boutique')->where ('ventes.boutique_id', '=',Auth::user()->boutique->id)->orderBy('ventes.created_at', 'DESC')->get();

        return datatables()->of($vente)
            ->addColumn('action', function ($clt) {
                if($clt->delivered != "delivered")
                {
                    return  '<a class="btn btn-info " onclick="show(' . $clt->id . ')" ><i class="fa  fa-info"></i></a>
                                     <a class="btn btn-danger" onclick="deletepro(' . $clt->id . ')"><i class="fa fa-trash-o"></i></a>'
                                      ;
                } else {
                    return  '<a class="btn btn-info " onclick="show(' . $clt->id . ')" ><i class="fa  fa-info"></i></a>
                                     <a class="btn btn-danger" onclick="deletepro(' . $clt->id . ')"><i class="fa fa-trash-o"></i></a> <a class="btn btn-warning" href="/livraison/bon/'. $clt->id .'"><i class="fa fa-file"></i></a>'
                                      ;
                }

            })
            ->make(true);
    }

    // public function liste()
    // {

    //     //get ventes joining on users
    //     $vente=vente::join('users', function ($join) {
    //         $join->on('ventes.user_id', '=', 'users.id');
    //     })->
    //     orderBy('ventes.created_at', 'DESC')->get();

    //     $modele2=DB::table('modeles')
    //         ->join('produits', function ($join) {
    //             $join->on('modeles.produit_id', '=', 'produits.id');
    //         })
    //         ->where ('modeles.boutique_id', '=',Auth::user()->boutique->id )
    //         ->whereColumn('modeles.seuil','>=','modeles.quantite')
    //         ->get();
    //     $mod=count($modele2);
    //     $clients=DB::table('clients')
    //         ->join('ventes', function ($join) {
    //             $join->on('ventes.client_id', '=', 'clients.id');
    //         })
    //         ->join('reglements', function ($join) {
    //             $join->on('reglements.vente_id', '=', 'ventes.id');
    //         })
    //         ->where ('ventes.boutique_id', '=',Auth::user()->boutique->id )
    //         ->where('reglements.montant_restant', '>', 0)
    //         ->select('clients.nom as nom','clients.id as id')
    //         ->groupBy('id', 'clients.nom')
    //         ->get();
    //     $credit=array();
    //     for ($i =0 ;$i<count($clients);$i++) {
    //         $total = DB::table('reglements')
    //             ->join('ventes', function ($join) {
    //                 $join->on('reglements.vente_id', '=', 'ventes.id');
    //             })
    //             ->where ('ventes.boutique_id', '=',Auth::user()->boutique->id )
    //             ->where('ventes.client_id', '=', $clients[$i]->id)
    //             ->SUM('reglements.montant_restant');
    //         $credit[$i] = $total;
    //     }
    //     $cre=count($clients);
    //     $historique=new Historique();
    //     $historique->actions = "Liste";
    //     $historique->cible = "Ventes";
    //     $historique->user_id =Auth::user()->id;
    //     $historique->save();
    //     return view('vente',compact('vente','modele2','mod','clients','credit','cre'));
    // }

    public function liste()
    {
        // Récupération des ventes avec les utilisateurs associés
        $ventes = Vente::join('users', 'ventes.user_id', '=', 'users.id')
                       ->where('ventes.boutique_id', '=', Auth::user()->boutique->id)
                       ->orderBy('ventes.created_at', 'DESC')
                       ->get();

        // Récupération des modèles de produits disponibles dans la boutique
        $modeles = Modele::join('produits', 'modeles.produit_id', '=', 'produits.id')
                         ->where('modeles.boutique_id', '=', Auth::user()->boutique->id)
                         ->whereColumn('modeles.seuil', '>=', 'modeles.quantite')
                         ->get();

        // Comptage des modèles de produits
        $nombreModeles = $modeles->count();

        // Récupération des clients avec un crédit restant à payer
        $clients = Client::join('ventes', 'ventes.client_id', '=', 'clients.id')
                         ->join('reglements', 'reglements.vente_id', '=', 'ventes.id')
                         ->where('ventes.boutique_id', '=', Auth::user()->boutique->id)
                         ->where('reglements.montant_restant', '>', 0)
                         ->select('clients.nom as nom', 'clients.id as id')
                         ->groupBy('id', 'clients.nom')
                         ->get();

        // Calcul du crédit pour chaque client
        $creditClients = [];
        foreach ($clients as $client) {
            $totalCredit = Reglement::join('ventes', 'reglements.vente_id', '=', 'ventes.id')
                                    ->where('ventes.boutique_id', '=', Auth::user()->boutique->id)
                                    ->where('ventes.client_id', '=', $client->id)
                                    ->sum('reglements.montant_restant');
            $creditClients[$client->id] = $totalCredit;
        }

        // Enregistrement de l'action dans l'historique
        $historique = new Historique();
        $historique->actions = "Liste";
        $historique->cible = "Ventes";
        $historique->user_id = Auth::user()->id;
        $historique->save();

        // Renvoi de la vue avec les données
        return view('vente', compact('ventes', 'modeles', 'nombreModeles', 'clients', 'creditClients'));
    }


    public function reglement($id)
    {
        // $id=DB::table('ventes')->max('id');
        $vente = DB::table('ventes')
        ->join('preventes', function ($join) {
            $join->on('preventes.vente_id', '=', 'ventes.id');
        })
        ->join('modeles', function ($join) {
            $join->on('modeles.id', '=', 'preventes.modele_fournisseur_id');
        })
        ->join('produits', function ($join) {
            $join->on('produits.id', '=', 'modeles.produit_id');
        })
        ->where('ventes.id','=',$id)
        ->select('ventes.numero as numero',
            'ventes.date_vente as date',
            'modeles.libelle as modele',
            'produits.nom as produit',
            'preventes.quantite as quantite',
            'preventes.prix as prix',
            'preventes.reduction as reduction',
            'preventes.prixtotal as prixtotal',
            'ventes.created_at as create',
            'ventes.updated_at as update')
        ->get();
        $modele2=DB::table('modeles')
            ->join('produits', function ($join) {
                $join->on('modeles.produit_id', '=', 'produits.id');
            })
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
                ->where('ventes.client_id', '=', $clients[$i]->id)
                ->SUM('reglements.montant_restant');
            $credit[$i] = $total;
        }
        $cre=count($clients);
        $total = DB::table('ventes')
            ->join('preventes', function ($join) {
                $join->on('preventes.vente_id', '=', 'ventes.id');
            })
            ->where('ventes.id','=',$id)
            ->SUM('preventes.prixtotal');
        $total_reduction = DB::table('ventes')
            ->join('preventes', function ($join) {
                $join->on('preventes.vente_id', '=', 'ventes.id');
            })
            ->where('ventes.id','=',$id)
            ->SUM('preventes.reduction');
        $all_vente = Vente::find($id);
        return view('reglement',compact('total_reduction','all_vente', 'vente','modele2','mod','total','clients','cre','credit'));
    }

    public function reglementcredit($id)
    {
        // $id=DB::table('ventes')->max('id');
        $vente = DB::table('ventes')
        ->join('preventes', function ($join) {
            $join->on('preventes.vente_id', '=', 'ventes.id');
        })
        ->join('modeles', function ($join) {
            $join->on('modeles.id', '=', 'preventes.modele_fournisseur_id');
        })
        ->join('produits', function ($join) {
            $join->on('produits.id', '=', 'modeles.produit_id');
        })
        ->where('ventes.id','=',$id)
        ->select('ventes.numero as numero',
            'ventes.date_vente as date',
            'modeles.libelle as modele',
            'produits.nom as produit',
            'preventes.quantite as quantite',
            'preventes.prix as prix',
            'preventes.reduction as reduction',
            'preventes.prixtotal as prixtotal',
            'ventes.created_at as create',
            'ventes.updated_at as update')
        ->get();
        $modele2=DB::table('modeles')
            ->join('produits', function ($join) {
                $join->on('modeles.produit_id', '=', 'produits.id');
            })
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
                ->where('ventes.client_id', '=', $clients[$i]->id)
                ->SUM('reglements.montant_restant');
            $credit[$i] = $total;
        }
        $cre=count($clients);
        $total = DB::table('ventes')
            ->join('preventes', function ($join) {
                $join->on('preventes.vente_id', '=', 'ventes.id');
            })
            ->where('ventes.id','=',$id)
            ->SUM('preventes.prixtotal');
        $total_reduction = DB::table('ventes')
        ->join('preventes', function ($join) {
            $join->on('preventes.vente_id', '=', 'ventes.id');
        })
        ->where('ventes.id','=',$id)
        ->SUM('preventes.reduction');
        $all_vente = Vente::find($id);
        return view('reglementcredit',compact('total_reduction','all_vente', 'vente','modele2','mod','total','clients','credit','cre'));
    }

    public function reglementgros($id)
    {
        // $id=DB::table('ventes')->max('id');
        $vente = DB::table('ventes')
        ->join('preventes', function ($join) {
            $join->on('preventes.vente_id', '=', 'ventes.id');
        })
        ->join('modeles', function ($join) {
            $join->on('modeles.id', '=', 'preventes.modele_fournisseur_id');
        })
        ->join('produits', function ($join) {
            $join->on('produits.id', '=', 'modeles.produit_id');
        })
        ->where('ventes.id','=',$id)
        ->select('ventes.numero as numero',
            'ventes.date_vente as date',
            'modeles.libelle as modele',
            'produits.nom as produit',
            'preventes.quantite as quantite',
            'preventes.prix as prix',
            'preventes.reduction as reduction',
            'preventes.prixtotal as prixtotal',
            'ventes.created_at as create',
            'ventes.updated_at as update')
        ->get();
        $modele2=DB::table('modeles')
            ->join('produits', function ($join) {
                $join->on('modeles.produit_id', '=', 'produits.id');
            })
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
                ->where('ventes.client_id', '=', $clients[$i]->id)
                ->SUM('reglements.montant_restant');
            $credit[$i] = $total;
        }
        $cre=count($clients);
        $total = DB::table('ventes')
            ->join('preventes', function ($join) {
                $join->on('preventes.vente_id', '=', 'ventes.id');
            })
            ->where('ventes.id','=',$id)
            ->SUM('preventes.prixtotal');
        $total_reduction = DB::table('ventes')
        ->join('preventes', function ($join) {
            $join->on('preventes.vente_id', '=', 'ventes.id');
        })
        ->where('ventes.id','=',$id)
        ->SUM('preventes.reduction');
        $all_vente = Vente::find($id);
        return view('reglementgros',compact('total_reduction','all_vente', 'vente','modele2','mod','total','clients','credit','cre'));
    }

    public function facturedevis(Request $request, $id)
    {
        $devis=DevisVente::findOrFail($id);
        $client=DB::table('clients')->where('id', $devis->client_id)->first();

        $devislignes = DB::table('devis_ventes')
            ->join('devis_lignes_ventes', function ($join) {
                $join->on('devis_lignes_ventes.devis_id', '=', 'devis_ventes.id');
            })
            ->join('modeles', function ($join) {
                $join->on('modeles.id', '=', 'devis_lignes_ventes.modele_fournisseur_id');
            })
            ->join('produits', function ($join) {
                $join->on('produits.id', '=', 'modeles.produit_id');
            })
            ->where('devis_ventes.id','=',$id)
            ->select('devis_ventes.numero as numero',
                'devis_ventes.date_devis as date',
                'modeles.libelle as modele',
                'produits.nom as produit',
                'devis_lignes_ventes.quantite as quantite',
                'devis_lignes_ventes.prix as prix',
                'devis_lignes_ventes.reduction as reduction',
                'devis_lignes_ventes.prixtotal as prixtotal',
                'devis_ventes.created_at as create',
                'devis_ventes.updated_at as update')
            ->get();

        $name = "devis_".date('Y-m-d_H-i-s', strtotime(now())).".pdf";

            try{
                $options = new Options();
                $options->set('isHtml5ParserEnabled', true);
                $options->set('isRemoteEnabled', TRUE);
                $options->set('isPhpEnabled', true);

                $dompdf = new Dompdf($options);

                // Chargez la vue dans Dompdf
                $view = view('facturedevis',compact('devis', 'devislignes', 'client'))->render();
                $dompdf->loadHtml($view);

                // Définissez la taille du papier
                $dompdf->setPaper('a4');

                // Rendez le PDF
                $dompdf->render();

                // Enregistrez le PDF dans un répertoire
                file_put_contents(public_path("devis/" . $name), $dompdf->output());

            }catch(Exception $e)
            {}

            //return $pdf->download($name);
            return response()->download(public_path("devis/" . $name));
    }

    public function facturedevisgros(Request $request, $id)
    {
        $devis=DevisVente::findOrFail($id);
        $client=DB::table('clients')->where('id', $devis->client_id)->first();

        $devislignes = DB::table('devis_ventes')
            ->join('devis_lignes_ventes', function ($join) {
                $join->on('devis_lignes_ventes.devis_id', '=', 'devis_ventes.id');
            })
            ->join('modeles', function ($join) {
                $join->on('modeles.id', '=', 'devis_lignes_ventes.modele_fournisseur_id');
            })
            ->join('produits', function ($join) {
                $join->on('produits.id', '=', 'modeles.produit_id');
            })
            ->where('devis_ventes.id','=',$id)
            ->select('devis_ventes.numero as numero',
                'devis_ventes.date_devis as date',
                'modeles.libelle as modele',
                'produits.nom as produit',
                'devis_lignes_ventes.quantite as quantite',
                'devis_lignes_ventes.prix as prix',
                'devis_lignes_ventes.reduction as reduction',
                'devis_lignes_ventes.prixtotal as prixtotal',
                'devis_ventes.created_at as create',
                'devis_ventes.updated_at as update')
            ->get();

        $name = "devis_de_gros_".date('Y-m-d_H-i-s', strtotime(now())).".pdf";
        $pdf = null;
            try{
                $options = new Options();
                $options->set('isHtml5ParserEnabled', true);
                $options->set('isRemoteEnabled', TRUE);
                $options->set('isPhpEnabled', true);

                $dompdf = new Dompdf($options);

                // Chargez la vue dans Dompdf
                $view = view('facturedevisgros',compact('devis', 'devislignes', 'client'))->render();
                $dompdf->loadHtml($view);

                // Définissez la taille du papier
                $dompdf->setPaper('a4');

                // Rendez le PDF
                $dompdf->render();

                // Enregistrez le PDF dans un répertoire
                file_put_contents(public_path("devis/" . $name), $dompdf->output());

            }catch(Exception $e)
            {}

            //return $pdf->download($name);
            return response()->download(public_path("devis/" . $name));
    }

    public function facturecredit(Request $request, $id)
    {
        // $id=DB::table('ventes')->max('id');
        $vente = DB::table('ventes')
        ->join('preventes', function ($join) {
            $join->on('preventes.vente_id', '=', 'ventes.id');
        })
        ->join('modeles', function ($join) {
            $join->on('modeles.id', '=', 'preventes.modele_fournisseur_id');
        })
        ->join('produits', function ($join) {
            $join->on('produits.id', '=', 'modeles.produit_id');
        })
        ->join('clients', function ($join) {
            $join->on('clients.id', '=', 'ventes.client_id');
        })
        ->join('reglements', function ($join) {
            $join->on('ventes.id', '=', 'reglements.vente_id');
        })
        ->where('ventes.id','=',$id)
        ->select('ventes.numero as numero',
            'ventes.date_vente as date',
            'modeles.libelle as modele',
            'produits.nom as produit',
            'preventes.quantite as quantite',
            'preventes.prix as prix',
            'preventes.reduction as reduction',
            'preventes.prixtotal as prixtotal',
            'clients.nom as nom',
            'clients.contact as contact',
            'reglements.montant_donne as donne',
            'reglements.montant_restant as restant',
            'ventes.created_at as create',
            'ventes.updated_at as update')
        ->get();
        $modele2=DB::table('modeles')
            ->join('produits', function ($join) {
                $join->on('modeles.produit_id', '=', 'produits.id');
            })
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
                ->where('ventes.client_id', '=', $clients[$i]->id)
                ->SUM('reglements.montant_restant');
            $credit[$i] = $total;
        }
        $cre=count($clients);
        $total = DB::table('ventes')
            ->join('preventes', function ($join) {
                $join->on('preventes.vente_id', '=', 'ventes.id');
            })
            ->where('ventes.id','=',$id)
            ->SUM('preventes.prixtotal');

            $name = "facture_".date('Y-m-d_H-i-s', strtotime(now())).".pdf";
            $pdf = null;
            $all_vente = Vente::find($id);
            /* try{
                $pdf = PDF::loadView('facturecredit',compact('all_vente', 'vente','modele2','mod','total','clients','credit','cre'))
                        ->setPaper('a4')
                        ->save(public_path("factures/".$name));
                DB::table('ventes')->where('id',$id)->update(['facture' => $name]);
            }catch(Exception $e)
            {}

            // return $pdf->stream();
            return $pdf->download($name); */

            $client = DB::table('clients')->where('id', $all_vente->client_id)->first();

            try {
                // Créez une instance de Dompdf avec des options
                $options = new Options();
                $options->set('isHtml5ParserEnabled', true);
                $options->set('isPhpEnabled', true);

                $dompdf = new Dompdf($options);

                // Chargez la vue dans Dompdf
                $view = view('facturecredit', compact('all_vente', 'vente', 'modele2', 'mod', 'total', 'clients', 'credit', 'cre', 'client'))->render();
                $dompdf->loadHtml($view);

                // Définissez la taille du papier
                $dompdf->setPaper('a4');

                // Rendez le PDF
                $dompdf->render();

                // Enregistrez le PDF dans un répertoire
                file_put_contents(public_path("factures/" . $name), $dompdf->output());

                // Mettez à jour la base de données avec le nom du fichier
                DB::table('ventes')->where('id', $id)->update(['facture' => $name]);

                return response()->download(public_path("factures/" . $name));

            } catch (Exception $e) {
                return response()->json(['message' => $e->getMessage()]);
            }
    }

    public function facturegros(Request $request, $id)
    {
        // $id=DB::table('ventes')->max('id');
        $vente = DB::table('ventes')
        ->join('preventes', function ($join) {
            $join->on('preventes.vente_id', '=', 'ventes.id');
        })
        ->join('modeles', function ($join) {
            $join->on('modeles.id', '=', 'preventes.modele_fournisseur_id');
        })
        ->join('produits', function ($join) {
            $join->on('produits.id', '=', 'modeles.produit_id');
        })
            ->join('clients', function ($join) {
            $join->on('clients.id', '=', 'ventes.client_id');
        })
        ->where('ventes.id','=',$id)
        ->select('ventes.numero as numero',
            'ventes.date_vente as date',
            'modeles.libelle as modele',
            'produits.nom as produit',
            'preventes.quantite as quantite',
            'preventes.prix as prix',
            'preventes.reduction as reduction',
            'preventes.prixtotal as prixtotal',
            'clients.nom as nom',
            'clients.contact as contact',
            'ventes.created_at as create',
            'ventes.updated_at as update')
        ->get();
        $modele2=DB::table('modeles')
            ->join('produits', function ($join) {
                $join->on('modeles.produit_id', '=', 'produits.id');
            })
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
                ->where('ventes.client_id', '=', $clients[$i]->id)
                ->SUM('reglements.montant_restant');
            $credit[$i] = $total;
        }
        $cre=count($clients);
        $total = DB::table('ventes')
            ->join('preventes', function ($join) {
                $join->on('preventes.vente_id', '=', 'ventes.id');
            })
            ->where('ventes.id','=',$id)
            ->SUM('preventes.prixtotal');

            $name = "facture_".date('Y-m-d_H-i-s', strtotime(now())).".pdf";
            $pdf = null;
            $all_vente = Vente::find($id);
            try{
                $pdf = PDF::loadView('facturegros',compact('all_vente', 'vente','modele2','mod','total','clients','cre'))
                        ->setPaper('a4')
                        ->save(public_path("factures/".$name));
                DB::table('ventes')->where('id',$id)->update(['facture' => $name]);
            }catch(Exception $e)
            {}

            // return $pdf->stream();
            return $pdf->download($name);

        // return view('facturecredit',compact('vente','modele2','mod','total','clients','credit','cre'));
    }

    public function facturesimple($id)
    {
        // $id=DB::table('ventes')->max('id');
        $vente = DB::table('ventes')
        ->join('preventes', function ($join) {
            $join->on('preventes.vente_id', '=', 'ventes.id');
        })
        ->join('modeles', function ($join) {
            $join->on('modeles.id', '=', 'preventes.modele_fournisseur_id');
        })
        ->join('produits', function ($join) {
            $join->on('produits.id', '=', 'modeles.produit_id');
        })
        ->where('ventes.id','=',$id)
        ->select('ventes.numero as numero',
            'ventes.date_vente as date',
            'modeles.libelle as modele',
            'produits.nom as produit',
            'preventes.quantite as quantite',
            'preventes.prix as prix',
            'preventes.reduction as reduction',
            'preventes.prixtotal as prixtotal',
            'ventes.created_at as create',
            'ventes.updated_at as update')
        ->get();
        $modele2=DB::table('modeles')
            ->join('produits', function ($join) {
                $join->on('modeles.produit_id', '=', 'produits.id');
            })
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
                ->where('ventes.client_id', '=', $clients[$i]->id)
                ->SUM('reglements.montant_restant');
            $credit[$i] = $total;
        }
        $cre=count($clients);
        $total = DB::table('ventes')
            ->join('preventes', function ($join) {
                $join->on('preventes.vente_id', '=', 'ventes.id');
            })
            ->where('ventes.id','=',$id)
            ->SUM('preventes.prixtotal');

        $name = "facture_".date('Y-m-d_H-i-s', strtotime(now())).".pdf";
        $all_vente = vente::find($id);
        $client=DB::table('clients')->where('id', $all_vente->client_id)->first();
        /* try{
            $pdf = PDF::loadView('facturesimple',compact('all_vente', 'vente','modele2','mod','total','clients','credit','cre', 'client'))
                    ->setPaper('a4')
                    ->save(public_path("factures/".$name));

                    //dd($pdf);
            DB::table('ventes')->where('id',$id)->update(['facture' => $name]);
        }catch(Exception $e)
        {}

        // return $pdf->stream();
        return $pdf->download($name); */

        // Créer une instance Dompdf
        $dompdf = new Dompdf();

        // Rendre la vue de facture à l'aide de View
        $html = View::make('facturesimple', compact('all_vente', 'vente', 'modele2', 'mod', 'total', 'clients', 'credit', 'cre', 'client'))->render();

        // Charger le HTML dans Dompdf
        $dompdf->loadHtml($html);

        // Définir le format du papier et de l'orientation
        $dompdf->setPaper('A4', 'landscape');

        // Générer le PDF
        $dompdf->render();

        // Nom du fichier PDF
        $name = "facture_" . date('Y-m-d_H-i-s', strtotime(now())) . ".pdf";

        // Enregistrez le PDF dans un dossier public
        $output = $dompdf->output();
        file_put_contents(public_path("factures/" . $name), $output);

        // Mettez à jour votre modèle de vente avec le nom du fichier PDF si nécessaire
        DB::table('ventes')->where('id', $id)->update(['facture' => $name]);

        $pdfPath = public_path("factures/" . $name);

        return response()->download($pdfPath);

    }

    public function fictiveCreate($id)
    {
        $categorie=Categorie::all();
        $client=Client::with('boutique')->where ('boutique_id', '=',Auth::user()->boutique->id)->get();
        $modele2=DB::table('modeles')
            ->join('produits', function ($join) {
                $join->on('modeles.produit_id', '=', 'produits.id');
            })
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
        $vente = Vente::find($id);
        return view('fictive', compact('vente', 'categorie', 'client', 'clients'));
    }

    public function fictive(Request $request)
    {
        $allcommande= explode( ',', $request->input('venTable') );
        $vente = vente::find($request->input('vente_id'));
        $client = $request->input('client_id') > 0 ?  Client::find($request->input('client_id')) : null;
        $total = 0;
        $modeles = [];
        $lignes = [];
        $tva = null;
        for ($i =0 ;$i<count($allcommande);$i+=3) {
            $id = $allcommande[$i];
            $prix = $allcommande[$i+1];
            $quantite = $allcommande[$i+2];
            $prixtotal = $prix * $quantite;

            $modele = DB::table('modeles')
            ->join('modele_fournisseurs', function ($join) {
                $join->on('modeles.id', '=', 'modele_fournisseurs.modele_id');
            })
            ->join('produits', function ($join) {
                $join->on('produits.id', '=', 'modeles.produit_id');
            })
            ->where(['modele_fournisseurs.id' => $id])
            ->select('modeles.*','produits.*')
            ->first();

            $lignes[] = [
                'modele_id' => $id,
                'prix' => $prix,
                'quantite' => $quantite,
                'prixtotal' => $prixtotal,
            ];

            $modeles[] = $modele;

            $total = $total + $prixtotal;
        }

        if($request->input('setTva') == "on")
        {

            $montant_ht = $total;
            $montant_tva = ($total * $request->input('tva'))/100;
            $tva = [
                'with_tva' => true,
                'montant_ht' => $montant_ht,
                'montant_tva' => $montant_tva,
                'tva' => $request->input('tva'),
                'montant_ttc' => $montant_ht + $montant_tva
            ];

        }else{
            $tva = null;
        }

        // dd($models, $lignes, $tva, $vente);

        $historique=new Historique();
        $historique->actions = "Creer";
        $historique->cible = "Ventes Fictive";
        $historique->user_id =Auth::user()->id;
        $historique->save();

        $facture= Facture::where(['vente_id' => $vente->id])->first();



            $name = "facture_".date('Y-m-d_H-i-s', strtotime(now())).".pdf";
            $pdf = null;
            try{
                $pdf = PDF::loadView('facturecfictive',compact('vente', 'modeles', 'lignes', 'tva', 'total','client'))
                        ->setPaper('a4')
                        ->save(public_path("factures/fictives/".$name));
                // DB::table('ventes')->where('id',$id)->update(['facture' => $name]);
                $facture = new FactureFictive();
                $facture->url = $name;
                if($tva != null)
                {
                    $facture->with_tva = true;
                    $facture->tva = $tva['tva'];
                    $facture->montant_tva = $tva['montant_tva'];
                    $facture->montant_ht = $tva['montant_ht'];
                }
                $facture->montant_ttc = $tva != null ? $tva['montant_ttc'] : $total;
                $facture->vente_id = $vente->id;
                $facture->save();
            }catch(Exception $e)
            {
                return "0";
            }

            return "1";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categorie=Categorie::all();
        $produits=Produit::all();
        $modeles=Modele::all();
        $client=Client::with('boutique')->where ('boutique_id', '=',Auth::user()->boutique->id)->get();

        $modele2=DB::table('modeles')
            ->where('modeles.seuil','>=','modeles.quantite')
            ->get();
        $mod=count($modele2);

        return view('ventesimple',compact('categorie', 'produits', 'modeles','modele2','mod', 'client'));
    }
    public function create2()
    {
        $categorie=Categorie::all();
        $produits=Produit::all();
        $modeles=Modele::all();
        $client=Client::with('boutique')->where ('boutique_id', '=',Auth::user()->boutique->id)->get();
        $modele2=DB::table('modeles')
            ->join('produits', function ($join) {
                $join->on('modeles.produit_id', '=', 'produits.id');
            })
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
                ->where('ventes.client_id', '=', $clients[$i]->id)
                ->SUM('reglements.montant_restant');
            $credit[$i] = $total;
        }
        $cre=count($clients);

        return view('ventecredit',compact('categorie','client', 'produits', 'modeles','modele2','mod','clients','credit','cre'));
    }
    public function create3()
    {
        $categorie=Categorie::all();
        $produits=Produit::all();
        $modeles=Modele::all();
        $client=Client::with('boutique')->where ('boutique_id', '=',Auth::user()->boutique->id)->get();
        $modele2=DB::table('modeles')
            ->join('produits', function ($join) {
                $join->on('modeles.produit_id', '=', 'produits.id');
            })
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
                ->where('ventes.client_id', '=', $clients[$i]->id)
                ->SUM('reglements.montant_restant');
            $credit[$i] = $total;
        }
        $cre=count($clients);

        return view('ventenonlivre',compact('categorie','client', 'produits', 'modeles','modele2','mod','clients','credit','cre'));
    }
    public function create4()
    {
        $categorie=Categorie::all();
        $produits=Produit::all();
        $modeles=Modele::all();
        $client=Client::with('boutique')->where ('boutique_id', '=',Auth::user()->boutique->id)->get();
        $modele2=DB::table('modeles')
            ->join('produits', function ($join) {
                $join->on('modeles.produit_id', '=', 'produits.id');
            })
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
                ->where('ventes.client_id', '=', $clients[$i]->id)
                ->SUM('reglements.montant_restant');
            $credit[$i] = $total;
        }
        $cre=count($clients);

        return view('ventegros',compact('categorie','client', 'produits', 'modeles','modele2','mod','clients','credit','cre'));
    }
    public function createdevis()
    {
        $categorie=Categorie::all();
        $produits=Produit::all();
        $modeles=Modele::all();
        $client=Client::with('boutique')->where ('boutique_id', '=',Auth::user()->boutique->id)->get();

        $modele2=DB::table('modeles')
            ->where('modeles.seuil','>=','modeles.quantite')
            ->get();
        $mod=count($modele2);

        return view('devisvente',compact('categorie', 'produits', 'modeles','modele2','mod', 'client'));
    }
    public function createdevisgros()
    {
        $categorie=Categorie::all();
        $produits=Produit::all();
        $modeles=Modele::all();
        $client=Client::with('boutique')->where ('boutique_id', '=',Auth::user()->boutique->id)->get();

        $modele2=DB::table('modeles')
            ->where('modeles.seuil','>=','modeles.quantite')
            ->get();
        $mod=count($modele2);

        return view('devisventegros',compact('categorie', 'produits', 'modeles','modele2','mod', 'client'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storedevis(Request $request)
    {
        $id=DB::table('devis_ventes')->max('id');
        $ed=1+$id;
        $allcommande= explode( ',', $request->input('venTable') );
        DB::beginTransaction();
        $devis = new DevisVente();
        $devis ->numero="DEV".now()->format('Y')."-".$ed;
        $devis ->date_devis= now();
        $devis ->client_id= $allcommande[1];
        $devis ->user_id= Auth::user()->id;
        $devis ->boutique_id= Auth::user()->boutique->id;
        $devis->save();
        $total = 0;
        $allReduction = 0;

        for ($i =0 ;$i<count($allcommande);$i+=5) {
            $devisligne = new DevisLignesVente();
            $devisligne ->modele_fournisseur_id=$allcommande[$i];
            $devisligne ->prix=$allcommande[$i+2];
            $devisligne ->quantite= $allcommande[$i+3];
            $devisligne ->reduction= $allcommande[$i+4];
            $devisligne ->prixtotal = $allcommande[$i+3]*$allcommande[$i+2] - $allcommande[$i+4];
            $devisligne ->devis_id=$devis->id;
            $devisligne->save();

            $total = $total + $devisligne->prixtotal;
            $allReduction = $allReduction + $devisligne->reduction;
        }
        DB::commit();

        $devis=DevisVente::findOrFail($devis->id);
        $devis->montant_reduction = $allReduction;

        if($request->input('setTva') == "on")
        {
            $montant_ht = $total;
            $montant_tva = ($total * $request->input('tva'))/100;
            $devis->with_tva = true;
            $devis->tva = $request->input('tva');
            $devis->montant_ht = $montant_ht;
            $devis->montant_tva = $montant_tva;
            $devis->totaux= $montant_ht + $montant_tva;
        }else{
            $devis->with_tva = false;
            $devis->totaux = $total;
        }

        $devis->update();

        $historique=new Historique();
        $historique->actions = "Creer";
        $historique->cible = "Devis";
        $historique->user_id =Auth::user()->id;
        $historique->save();

        return $devis;
    }

    public function store(Request $request)
    {

        //error_log($allcommande);
        $i=DB::table('journals')->max('id');
        $id=DB::table('ventes')->max('id');
        $ed=1+$id;
       //DB::beginTransaction();
        $vente = new vente();
        $vente ->numero="VENT".now()->format('Y')."-".$ed;
        $vente ->date_vente= now();
        $vente ->user_id= Auth::user()->id;
        $vente ->client_id= $request->client;
        $vente ->journal_id= $i;
        $vente ->type_vente= 1;
        $vente ->boutique_id= Auth::user()->boutique->id;
        $vente->delivered = "En attente";
        $vente->save();
        $total = 0;
        $allReduction = 0;

        foreach($request->lines as $line){
            $prevente = new Prevente();
            $prevente->modele_fournisseur_id=$line["id"];
            $prevente->prix=$line["prix"];
            $prevente->quantite= $line["quantite"];
            $prevente->reduction= $line["reduction"];
            $prevente->livraison= $line["livraison"];
            $prevente->prixtotal = $line['prix']*$line['quantite'] - $line['reduction'];
            $prevente->vente_id=$vente->id;
            $prevente->save();

            $livraison = Livraison::where('numero', $line["livraison"])->get()->first();
            $livraison_commande = livraisonCommande::where('livraison_id', $livraison->id)->get()->first();
            $livraison_commande->quantite_vendue += $line['quantite'];
            $livraison_commande->save();

            $total+= $prevente->prixtotal;
        }


        //DB::commit();

       $vente=vente::findOrFail($vente->id);
        $vente->montant_reduction = $allReduction;

        if($request->input('setTva') == "on")
        {
            $montant_ht = $total;
            $montant_tva = ($total * $request->input('tva'))/100;
            $vente->with_tva = true;
            $vente->tva = $request->input('tva');
            $vente->montant_ht = $montant_ht;
            $vente->montant_tva = $montant_tva;
            $vente->totaux= $montant_ht + $montant_tva;
             if($request->input('checkavoir') == "on")
            {
                 $client = Client::find($vente->client_id);
                  $vente->with_avoir = true;
                    if($client->avoir > $vente->totaux)
                    {

                    $client->avoir = $client->avoir - $vente->totaux;
                    // Mise à jour des informations de l'utilisateur
                    $client->solde = $client->avoir - $vente->totaux + $client->solde;

                    }
                    elseif($client->avoir < $vente->totaux)
                    {

                    $client->avoir = 0;
                    // Mise à jour des informations de l'utilisateur
                    $client->solde =$vente->totaux - $client->avoir  + $client->solde;

                    }
                    // Sauvegarde des modifications
                    $client->save();
            }
        }else{
            $vente->with_tva = false;
            $vente->totaux = $total;
             if($request->input('checkavoir') == "on")
            {
                 $client = Client::find($vente->client_id);
                  $vente->with_avoir = true;
                    if($client->avoir > $vente->totaux)
                    {

                    $client->avoir = $client->avoir - $vente->totaux;
                    // Mise à jour des informations de l'utilisateur
                    $client->solde = $client->avoir - $vente->totaux + $client->solde;

                    }
                    elseif($client->avoir < $vente->totaux)
                    {

                    $client->avoir = 0;
                    // Mise à jour des informations de l'utilisateur
                    $client->solde =$vente->totaux - $client->avoir  + $client->solde;

                    }
                    // Sauvegarde des modifications
                    $client->save();
            }
        }

        $vente->update();

        $modele2=DB::table('modeles')
            ->join('produits', function ($join) {
                $join->on('modeles.produit_id', '=', 'produits.id');
            })
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
                ->where('ventes.client_id', '=', $clients[$i]->id)
                ->SUM('reglements.montant_restant');
            $credit[$i] = $total;
        }
     $cre=count($clients);
        $historique=new Historique();
        $historique->actions = "Creer";
        $historique->cible = "Ventes";
        $historique->user_id =Auth::user()->id;
        $historique->save();
          $total = DB::table('ventes')
            ->join('preventes', function ($join) {
                $join->on('preventes.vente_id', '=', 'ventes.id');
            })
            ->where('ventes.id','=',$vente->id)
            ->SUM('preventes.prixtotal');
        $id=DB::table('factures')->max('id');
        $ed=1+$id;
         $facture=new Facture();
        $facture->prixapayer =$total;
        $facture->montant_reduction =$allReduction;
        $facture->vente_id =$vente->id;
        $facture ->numero="FACT".now()->format('Y')."-".$ed;
        $facture->save();
       /* if ($mod>0){
         Alert::warning('Attention quantité inferieure au seuil','Veuillez vous approvisionner');
        }*/
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
                ->where('ventes.client_id', '=', $clients[$i]->id)
                ->SUM('reglements.montant_restant');
            $credit[$i] = $total;
        }
        $cre=count($clients);
        // return view('vente',compact('modele2','mod','clients','credit','cre'));
        return $vente;
    }

    public function store2(Request $request)
    {
        $i=DB::table('journals')->max('id');
        $id=DB::table('ventes')->max('id');
        $ed=1+$id;
       DB::beginTransaction();
        $vente = new vente();
        $vente ->numero="VENT".now()->format('Y')."-".$ed;
        $vente ->date_vente= now();
        $vente ->client_id= $request->client;
        $vente ->user_id= Auth::user()->id;
        $vente ->journal_id= $i;
        $vente ->type_vente= 2;
        $vente ->boutique_id= Auth::user()->boutique->id;
        $vente->delivered = "En attente";
        $vente->save();
        $total = 0;
        $allReduction = 0;

        foreach($request->lines as $line){
            $prevente = new Prevente();
            $prevente->modele_fournisseur_id=$line["id"];
            $prevente->prix=$line["prix"];
            $prevente->quantite= $line["quantite"];
            $prevente->reduction= $line["reduction"];
            $prevente->livraison= $line["livraison"];
            $prevente->prixtotal = $line['prix']*$line['quantite'] - $line['reduction'];
            $prevente->vente_id=$vente->id;
            $prevente->save();

            $livraison = Livraison::where('numero', $line["livraison"])->get()->first();
            $livraison_commande = livraisonCommande::where('livraison_id', $livraison->id)->get()->first();
            $livraison_commande->quantite_vendue += $line['quantite'];
            $livraison_commande->save();

            $total+= $prevente->prixtotal;
        }

        $vente=vente::findOrFail($vente->id);
        $vente->montant_reduction = $allReduction;

        if($request->input('setTva') == "on")
        {
            $montant_ht = $total;
            $montant_tva = ($total * $request->input('tva'))/100;
            $vente->with_tva = true;
            $vente->tva = $request->input('tva');
            $vente->montant_ht = $montant_ht;
            $vente->montant_tva = $montant_tva;
            $vente->totaux= $montant_ht + $montant_tva;

             // Récupération de l'utilisateur à mettre à jour
             $client = Client::find($vente->client_id);
              $vente->with_avoir = true;
            if($client->avoir > $vente->totaux)
            {

             $client->avoir = $client->avoir - $vente->totaux;
             // Mise à jour des informations de l'utilisateur
             $client->solde = $client->avoir - $vente->totaux + $client->solde;

            }
            elseif($client->avoir < $vente->totaux)
            {

             $client->avoir = 0;
             // Mise à jour des informations de l'utilisateur
             $client->solde =$vente->totaux - $client->avoir  + $client->solde;

            }
             // Sauvegarde des modifications
             $client->save();


        }else{
            $vente->with_tva = false;
            $vente->totaux = $total;
             // Récupération de l'utilisateur à mettre à jour
             $client = Client::find($vente->client_id);

             // Mise à jour des informations de l'utilisateur
             $client->solde = $vente->totaux + $client->solde;

             // Sauvegarde des modifications
             $client->save();
        }

        $vente->update();
             if($request->input('checkavoir') == "on")
            {
                 $client = Client::find($vente->client_id);
                    if($client->avoir > $vente->totaux)
                    {

                    $client->avoir = $client->avoir - $vente->totaux;
                    // Mise à jour des informations de l'utilisateur
                    $client->solde = $client->avoir - $vente->totaux + $client->solde;

                    }
                    elseif($client->avoir < $vente->totaux)
                    {

                    $client->avoir = 0;
                    // Mise à jour des informations de l'utilisateur
                    $client->solde =$vente->totaux - $client->avoir  + $client->solde;

                    }
                    // Sauvegarde des modifications
                    $client->save();
            }
        $modele2=DB::table('modeles')
            ->join('produits', function ($join) {
                $join->on('modeles.produit_id', '=', 'produits.id');
            })
            ->whereColumn('modeles.seuil','>=','modeles.quantite')
            ->get();
        $mod=count($modele2);
        $historique=new Historique();
        $historique->actions = "Creer";
        $historique->cible = "Ventes";
        $historique->user_id =Auth::user()->id;
        $historique->save();

        $total = DB::table('ventes')
            ->join('preventes', function ($join) {
                $join->on('preventes.vente_id', '=', 'ventes.id');
            })
            ->where('ventes.id','=',$vente->id)
            ->SUM('preventes.prixtotal');
        $id=DB::table('factures')->max('id');
        $ed=1+$id;
        $facture=new Facture();
        $facture->prixapayer =$total;
        $facture->montant_reduction =$allReduction;
        $facture->vente_id =$vente->id;
        $facture ->numero="FACT".now()->format('Y')."-".$ed;
        $facture->save();
        DB::commit();
        // $clients=DB::table('clients')
        //     ->join('ventes', function ($join) {
        //         $join->on('ventes.client_id', '=', 'clients.id');
        //     })
        //     ->join('reglements', function ($join) {
        //         $join->on('reglements.vente_id', '=', 'ventes.id');
        //     })
        //     ->where ('ventes.boutique_id', '=',Auth::user()->boutique->id )
        //     ->where('reglements.montant_restant', '>', 0)
        //     ->select('clients.nom as nom','clients.id as id')
        //     ->groupBy('id', 'clients.nom')
        //     ->get();
        // $credit=array();
        // for ($i =0 ;$i<count($clients);$i++) {
        //     $total = DB::table('reglements')
        //         ->join('ventes', function ($join) {
        //             $join->on('reglements.vente_id', '=', 'ventes.id');
        //         })
        //         ->where('ventes.client_id', '=', $clients[$i]->id)
        //         ->SUM('reglements.montant_restant');
        //     $credit[$i] = $total;
        // }
        // $cre=count($clients);
        // // return view('vente',compact('modele2','mod','clients','credit','cre'));
        return $vente;
    }
    public function store3(Request $request)
    {
        $i=DB::table('journals')->max('id');
        $id=DB::table('ventes')->max('id');
        $ed=1+$id;
        $vente = new vente();
        $vente ->numero="VENT".now()->format('Y')."-".$ed;
        $vente ->date_vente= now();
        $vente ->client_id= $request->client;
        $vente ->user_id= Auth::user()->id;
        $vente ->journal_id= $i;
        $vente ->type_vente= 3;
        $vente ->boutique_id= Auth::user()->boutique->id;
        $vente->delivered = "En attente";
        $vente->save();
        $total = 0;
        $allReduction = 0;

        foreach($request->lines as $line){
            $prevente = new Prevente();
            $prevente->modele_fournisseur_id=$line["id"];
            $prevente->prix=$line["prix"];
            $prevente->quantite= $line["quantite"];
            $prevente->reduction= $line["reduction"];
            $prevente->livraison= $line["livraison"];
            $prevente->prixtotal = $line['prix']*$line['quantite'] - $line['reduction'];
            $prevente->vente_id=$vente->id;
            $prevente->save();

            $livraison = Livraison::where('numero', $line["livraison"])->get()->first();
            $livraison_commande = livraisonCommande::where('livraison_id', $livraison->id)->get()->first();
            $livraison_commande->quantite_vendue += $line['quantite'];
            $livraison_commande->save();

            $total+= $prevente->prixtotal;
        }

        $vente=vente::findOrFail($vente->id);
        $vente->montant_reduction = $allReduction;

        if($request->input('setTva') == "on")
        {
            $montant_ht = $total;
            $montant_tva = ($total * $request->input('tva'))/100;
            $vente->with_tva = true;
            $vente->tva = $request->input('tva');
            $vente->montant_ht = $montant_ht;
            $vente->montant_tva = $montant_tva;
            $vente->totaux= $montant_ht + $montant_tva;
        }else{
            $vente->with_tva = false;
            $vente->totaux = $total;
        }

        $vente->update();

            if($request->input('checkavoir') == "on")
            {
                 $client = Client::find($vente->client_id);
                  $vente->with_avoir = true;
                    if($client->avoir > $vente->totaux)
                    {

                    $client->avoir = $client->avoir - $vente->totaux;
                    // Mise à jour des informations de l'utilisateur
                    $client->solde = $client->avoir - $vente->totaux + $client->solde;

                    }
                    elseif($client->avoir < $vente->totaux)
                    {

                    $client->avoir = 0;
                    // Mise à jour des informations de l'utilisateur
                    $client->solde =$vente->totaux - $client->avoir  + $client->solde;

                    }
                    // Sauvegarde des modifications
                    $client->save();
            }
        $modele2=DB::table('modeles')
            ->join('produits', function ($join) {
                $join->on('modeles.produit_id', '=', 'produits.id');
            })
            ->whereColumn('modeles.seuil','>=','modeles.quantite')
            ->get();
        $mod=count($modele2);
        $historique=new Historique();
        $historique->actions = "Creer";
        $historique->cible = "Ventes";
        $historique->user_id =Auth::user()->id;
        $historique->save();
        $total = DB::table('ventes')
            ->join('preventes', function ($join) {
                $join->on('preventes.vente_id', '=', 'ventes.id');
            })
            ->where('ventes.id','=',$vente->id)
            ->SUM('preventes.prixtotal');
        $id=DB::table('factures')->max('id');
        $ed=1+$id;
        $facture=new Facture();
        $facture->prixapayer =$total;
        $facture->montant_reduction =$allReduction;
        $facture->vente_id =$vente->id;
        $facture ->numero="FACT".now()->format('Y')."-".$ed;
        $facture->save();
        // $clients=DB::table('clients')
        //     ->join('ventes', function ($join) {
        //         $join->on('ventes.client_id', '=', 'clients.id');
        //     })
        //     ->join('reglements', function ($join) {
        //         $join->on('reglements.vente_id', '=', 'ventes.id');
        //     })
        //     ->where ('ventes.boutique_id', '=',Auth::user()->boutique->id )
        //     ->where('reglements.montant_restant', '>', 0)
        //     ->select('clients.nom as nom','clients.id as id')
        //     ->groupBy('id', 'clients.nom')
        //     ->get();
        // $credit=array();
        // for ($i =0 ;$i<count($clients);$i++) {
        //     $total = DB::table('reglements')
        //         ->join('ventes', function ($join) {
        //             $join->on('reglements.vente_id', '=', 'ventes.id');
        //         })
        //         ->where('ventes.client_id', '=', $clients[$i]->id)
        //         ->SUM('reglements.montant_restant');
        //     $credit[$i] = $total;
        // }
        // $cre=count($clients);
        // return view('vente',compact('modele2','mod','clients','credit','cre'));
        return $vente;
    }

    public function store4(Request $request)
    {
        $i=DB::table('journals')->max('id');
        $id=DB::table('ventes')->max('id');
        $ed=1+$id;
        DB::beginTransaction();
        $vente = new vente();
        $vente ->numero="VENT".now()->format('Y')."-".$ed;
        $vente ->date_vente= now();
        $vente ->client_id= $request->client;
        $vente ->user_id= Auth::user()->id;
        $vente ->journal_id= $i;
        $vente ->type_vente= 4;
        $vente ->boutique_id= Auth::user()->boutique->id;
        $vente->delivered = "En attente";
        $vente->save();
        $total = 0;
        $allReduction = 0;

        foreach($request->lines as $line){
            $prevente = new Prevente();
            $prevente->modele_fournisseur_id=$line["id"];
            $prevente->prix=$line["prix"];
            $prevente->quantite= $line["quantite"];
            $prevente->reduction= $line["reduction"];
            $prevente->livraison= $line["livraison"];
            $prevente->prixtotal = $line['prix']*$line['quantite'] - $line['reduction'];
            $prevente->vente_id=$vente->id;
            $prevente->save();

            $livraison = Livraison::where('numero', $line["livraison"])->get()->first();
            $livraison_commande = livraisonCommande::where('livraison_id', $livraison->id)->get()->first();
            $livraison_commande->quantite_vendue += $line['quantite'];
            $livraison_commande->save();

            $total+= $prevente->prixtotal;
        }
        $vente=vente::findOrFail($vente->id);
        $vente->montant_reduction = $allReduction;

        if($request->input('setTva') == "on")
        {
            $montant_ht = $total;
            $montant_tva = ($total * $request->input('tva'))/100;
            $vente->with_tva = true;
            $vente->tva = $request->input('tva');
            $vente->montant_ht = $montant_ht;
            $vente->montant_tva = $montant_tva;
            $vente->totaux= $montant_ht + $montant_tva;
        }else{
            $vente->with_tva = false;
            $vente->totaux = $total;
        }

        $vente->update();
         if($request->input('checkavoir') == "on")
            {
                 $client = Client::find($vente->client_id);
                  $vente->with_avoir = true;
                    if($client->avoir > $vente->totaux)
                    {

                    $client->avoir = $client->avoir - $vente->totaux;
                    // Mise à jour des informations de l'utilisateur
                    $client->solde = $client->avoir - $vente->totaux + $client->solde;

                    }
                    elseif($client->avoir < $vente->totaux)
                    {

                    $client->avoir = 0;
                    // Mise à jour des informations de l'utilisateur
                    $client->solde =$vente->totaux - $client->avoir  + $client->solde;

                    }
                    // Sauvegarde des modifications
                    $client->save();
            }
        $modele2=DB::table('modeles')
            ->join('produits', function ($join) {
                $join->on('modeles.produit_id', '=', 'produits.id');
            })
            ->whereColumn('modeles.seuil','>=','modeles.quantite')
            ->get();
        $mod=count($modele2);
        $historique=new Historique();
        $historique->actions = "Creer";
        $historique->cible = "Ventes";
        $historique->user_id =Auth::user()->id;
        $historique->save();
        $total = DB::table('ventes')
            ->join('preventes', function ($join) {
                $join->on('preventes.vente_id', '=', 'ventes.id');
            })
            ->where('ventes.id','=',$vente->id)
            ->SUM('preventes.prixtotal');
        $id=DB::table('factures')->max('id');
        $ed=1+$id;
        $facture=new Facture();
        $facture->prixapayer =$total;
        $facture->montant_reduction =$allReduction;
        $facture->vente_id =$vente->id;
        $facture ->numero="FACT".now()->format('Y')."-".$ed;
        $facture->save();
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
                ->where('ventes.client_id', '=', $clients[$i]->id)
                ->SUM('reglements.montant_restant');
            $credit[$i] = $total;
        }
        $cre=count($clients);
        DB::commit();
        // return view('vente',compact('modele2','mod','clients','credit','cre'));
        return $vente;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $type= vente::find($id);
        $factures = FactureFictive::where(['vente_id' => $id])->orderBy('created_at', 'DESC')->get();

        $retourVentes = Retour::join('retour_lignes', function ($join) {
            $join->on('retour_lignes.retour_id', '=', 'retours.id');
        })
        ->where(['retours.vente_id' => $id])
        ->selectRaw('retours.id, retours.totaux, retours.payer, retours.created_at, sum(retour_lignes.quantite_retourner) as qte')
        ->groupBy('retours.id', 'retours.totaux', 'retours.payer', 'retours.created_at')
        ->get();

        $total_rendu = Retour::where(['retours.vente_id' => $id])->sum('payer');


        if ($type->client_id==null)
        {

         // Return all the producst sales given an id.
        $vente = DB::table('ventes')
            ->join('preventes', function ($join) {
                $join->on('preventes.vente_id', '=', 'ventes.id');
            })
            ->join('modeles', function ($join) {
                $join->on('modeles.id', '=', 'preventes.modele_fournisseur_id');
            })
            ->join('produits', function ($join) {
                $join->on('produits.id', '=', 'modeles.produit_id');
            })
            ->where('ventes.id','=',$id)
            ->select('ventes.numero as numero',
                'ventes.date_vente as date',
                'ventes.id as id',
                'ventes.facture as facture',
                'ventes.delivered as delivered',
                'modeles.libelle as modele',
                'produits.nom as produit',
                'preventes.quantite as quantite',
                'preventes.prix as prix',
                'preventes.prixtotal as prixtotal',
                'ventes.created_at as create',
                'preventes.livraison as livraison',
                'ventes.updated_at as update')
            ->get();
        $total = DB::table('ventes')
            ->join('preventes', function ($join) {
                $join->on('preventes.vente_id', '=', 'ventes.id');
            })
            ->where('ventes.id','=',$id)
            ->SUM('preventes.prixtotal');
            // dd($vente);
            $all_vente = Vente::find($id);
            return view('detailvente2',compact('all_vente', 'vente','total', 'factures','retourVentes', 'total_rendu'));
        }
        else{
            $vente = DB::table('ventes')
                ->join('preventes', function ($join) {
                    $join->on('preventes.vente_id', '=', 'ventes.id');
                })
                ->join('modeles', function ($join) {
                    $join->on('modeles.id', '=', 'preventes.modele_fournisseur_id');
                })
                ->join('produits', function ($join) {
                    $join->on('produits.id', '=', 'modeles.produit_id');
                })
                ->join('clients', function ($join) {
                    $join->on('clients.id', '=', 'ventes.client_id');
                })
                ->where('ventes.id','=',$id)
                ->select('ventes.numero as numero',
                    'ventes.date_vente as date',
                    'ventes.id as id',
                    'ventes.facture as facture',
                    'ventes.delivered as delivered',
                    'modeles.libelle as modele',
                    'produits.nom as produit',
                    'preventes.quantite as quantite',
                    'preventes.prix as prix',
                    'preventes.prixtotal as prixtotal',
                    'preventes.livraison as livraison',
                    'clients.nom as Nclient',
                    'ventes.created_at as create',
                    'ventes.updated_at as update')
                ->get();
            $total = DB::table('ventes')
                ->join('preventes', function ($join) {
                    $join->on('preventes.vente_id', '=', 'ventes.id');
                })
                ->where('ventes.id','=',$id)
                ->SUM('preventes.prixtotal');
            $historique=new Historique();
            $historique->actions = "Detail";
            $historique->cible = "Ventes";
            $historique->user_id =Auth::user()->id;
            $historique->save();
            // dd($vente);
            $all_vente = Vente::find($id);
            return view('detailvente',compact('all_vente', 'vente','total', 'factures','retourVentes', 'total_rendu'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $modele=DB::table('modeles')
            ->join('produits', function ($join) {
                $join->on('modeles.produit_id', '=', 'produits.id');
            })
            ->whereColumn('modeles.seuil','>=','modeles.quantite')
            ->get();
        $mod=count($modele);
        return $modele;
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
        $provision= produitProvision::findOrFail($request->input('idprovision'));
        $provision->quantite = $request->input('quantite');
        $provision->prix_achat = $request->input('prix');
        $provision->date_provision = $request->input('dateprovision');
        $provision->provision_id = $request->input('provision');
        $provision->update();
        $historique=new Historique();
        $historique->actions = "Modifier";
        $historique->cible = "Commandes";
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
        $vente=Vente::findOrFail($id);
        Prevente::where('vente_id', '=', $vente->id)->delete();
        $vente ->delete();
        $historique=new Historique();
        $historique->actions = "Supprimer";
        $historique->cible = "Ventes";
        $historique->user_id =Auth::user()->id;
        $historique->save();
        return [];
    }

    public function credit($id)
    {
        $credit =DB::table('clients')
            ->join('reglements', function ($join) {
                $join->on('reglements.client_id', '=', 'clients.id');
            })
            -> where ('reglements.client_id', '=',$id)
            -> select ('reglements.montant_restant','reglements.created_at')
            ->latest()
            ->first();
        return $credit->montant_restant;
    }
    public function avoir($id)
    {
        $credit =DB::table('clients')
            -> where ('id', '=',$id)
            -> select ('clients.avoir','clients.created_at')
            ->latest()
            ->first();
        return $credit->avoir;
    }
    public function debiteurs()
    {
        $clients = Client::where ('clients.boutique_id', '=',Auth::user()->boutique->id )
        ->where('clients.solde','>',0)
        ->select ('clients.nom', 'clients.contact', 'clients.adresse','clients.solde', 'clients.created_at')
        ->get();

       /*  $array = [];
        foreach($clients as $client)
        {
            $data = Reglement::where('clients.id', $client->id)
            ->join('clients', 'reglements.client_id', '=', 'clients.id')
            ->join('ventes', 'reglements.vente_id', '=', 'ventes.id')
            ->select ('clients.nom', 'clients.contact','clients.solde', 'clients.adresse', 'reglements.montant_restant','reglements.created_at')


            if($data != null && $data->montant_restant > 0 && $data->solde >0 )
            {
                $array[] = $data;
            }
        } */

        return datatables()->of($clients)
        ->make(true);
       /*  $client =Client::with('boutique')->where ('boutique_id', '=',Auth::user()->boutique->id )->orderBy('created_at', 'DESC')->get();
        return datatables()->of($client)

            ->make(true); */
    }

    public function alldebiteurs()
    {
        $clients = Client::where('clients.solde','>',0)
        ->join('boutiques', 'clients.boutique_id', '=', 'boutiques.id')

        ->select ('boutiques.nom as boutique', 'clients.id', 'clients.nom', 'clients.contact', 'clients.adresse','clients.solde', 'clients.created_at')
        ->get();

        return datatables()->of($clients)
        ->addColumn('action', function ($clt) {
            return '<a class="btn btn-info " onclick="show(' . $clt->id . ')" ><i class="fa  fa-info"></i></a>';
        })
        ->make(true);
    }
    public function journal()
    {

        $id=DB::table('journals')->max('id');
        if($id != null)
        {
            $journ= Journal::findOrFail($id);
            $journ->date_fermeture =now();
            $journ->update();
        }
        $journal= new Journal();
        $journal->date_creation =now();
        $journal->user_id = Auth::user()->id;
        $journal->mois = now()->format('m');
        $journal->annee = now()->format('Y');
        $journal->boutique_id =Auth::user()->boutique->id;
        $journal->save();
        $historique=new Historique();
        $historique->actions = "Creer";
        $historique->cible = "Journal";
        $historique->user_id =Auth::user()->id;
        $historique->save();
        return [];
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

    public function verification()
    {
        $id=DB::table('journals')->max('id');
        $journal = DB::table('journals')
            ->where('journals.id', '=', $id)
            ->select('journals.date_fermeture as fermeture','journals.date_creation as creation')
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


    public function ventedate($id)
    {
        $vente = DB::table('ventes')

            ->join('boutiques', function ($join) {
                $join->on('ventes.boutique_id', '=', 'boutiques.id');
            })
            ->join('users', function ($join) {
                $join->on('ventes.user_id', '=', 'users.id');
            })
            ->where ('boutiques.id', '=',Auth::user()->boutique->id)
            ->where('ventes.journal_id', '=', $id)
            // ->select('ventes.id as id','ventes.numero as vente','ventes.totaux as totaux', 'users.nom as user')
            ->selectRaw('ventes.id as id, ventes.numero as vente, ventes.totaux as totaux, CONCAT(users.nom, " ", users.prenom) as user')
            ->get();
        return datatables()->of($vente)
            ->addColumn('action', function ($clt) {
                return  '<a class="btn btn-info " onclick="show(' . $clt->id . ')" ><i class="fa  fa-info"></i></a>';
            })
            ->make(true) ;
    }

    public function ventedate2($id)
    {

        $vente = DB::table('ventes')
            ->join('boutiques', function ($join) {
                $join->on('ventes.boutique_id', '=', 'boutiques.id');
            })
            ->where ('boutiques.id', '=',Auth::user()->boutique->id)
            ->where('ventes.journal_id', '=', $id)
            ->select('ventes.id as id','ventes.numero as vente','ventes.totaux as totaux')
            ->get();
        return datatables()->of($vente)
            ->addColumn('action', function ($clt) {
                return  '<a class="btn btn-info " onclick="show(' . $clt->id . ')" ><i class="fa  fa-info"></i></a>';
            })
            ->make(true) ;
    }
    public function adminventedate($id,$ed)
    {
        $vente = DB::table('ventes')

            ->join('boutiques', function ($join) {
                $join->on('ventes.boutique_id', '=', 'boutiques.id');
            })
            ->where ('boutiques.id', '=',$ed)
            ->where('ventes.journal_id', '=', $id)
            ->select('ventes.id as id','ventes.numero as vente','ventes.totaux as totaux')
            ->get();
        return datatables()->of($vente)
            ->addColumn('action', function ($clt) {
                return  '<a class="btn btn-info " onclick="show(' . $clt->id . ')" ><i class="fa  fa-info"></i></a>';
            })
            ->make(true) ;
    }
    public function totaljour($id)
    {
        $vente = DB::table('ventes')
            ->join('preventes', function ($join) {
                $join->on('preventes.vente_id', '=', 'ventes.id');
            })
            ->where('ventes.journal_id', '=', $id)
            ->where ('ventes.boutique_id', '=',Auth::user()->boutique->id)
            ->sum('preventes.prixtotal');
        return $vente;
    }
    public function admintotaljour($id,$ed)
    {
        $vente = DB::table('ventes')
            ->join('preventes', function ($join) {
                $join->on('preventes.vente_id', '=', 'ventes.id');
            })
            ->where('ventes.journal_id', '=', $id)
            ->where ('ventes.boutique_id', '=',$ed)
            ->sum('preventes.prixtotal');
        return $vente;
    }
    public function ventemois($id,$ed)
    {
        /*$vente = DB::table('ventes')
            ->join('journals', function ($join) {
                $join->on('ventes.journal_id', '=', 'journals.id');
            })
            ->join('users', function ($join) {
                $join->on('ventes.user_id', '=', 'users.id');
            })
            ->where ('journals.boutique_id', '=',Auth::user()->boutique->id)
            ->where('journals.mois', '=', $id)
            ->where('journals.annee', '=', $ed)
            // ->select('ventes.id as id','ventes.numero as vente','ventes.totaux as totaux', 'users.nom as user')
            ->selectRaw('ventes.id as id, ventes.numero as vente, ventes.totaux as totaux, CONCAT(users.nom, " ", users.prenom) as user')
            ->get();*/
        $vente = DB::table('ventes')
            ->join('journals', function ($join) use ($id, $ed) {
                $join->on('ventes.journal_id', '=', 'journals.id')
                     ->where('journals.mois', '=', $id)
                     ->where('journals.annee', '=', $ed);
            })
            ->join('users', function ($join) {
                $join->on('ventes.user_id', '=', 'users.id');
            })
            ->where('ventes.boutique_id', '=', Auth::user()->boutique->id)
            ->selectRaw('ventes.id as id, ventes.numero as vente, ventes.totaux as totaux, CONCAT(users.nom, " ", users.prenom) as user')
            ->get();

        return datatables()->of($vente)
            ->addColumn('action', function ($clt) {
                return  '<a class="btn btn-info " onclick="show(' . $clt->id . ')" ><i class="fa  fa-info"></i></a>';
            })
            ->make(true) ;
    }
    public function adminventemois($id,$ed,$ad)
    {
        $vente = DB::table('ventes')
        ->join('users', function ($join) {
            $join->on('ventes.user_id', '=', 'users.id');
        })
            ->join('journals', function ($join) {
                $join->on('ventes.journal_id', '=', 'journals.id');
            })
            ->where ('journals.boutique_id', '=',$ad)
            ->where('journals.mois', '=', $id)
            ->where('journals.annee', '=', $ed)
            ->select('ventes.id as id','ventes.numero as vente','ventes.totaux as totaux', 'users.nom as user')
            ->get();
          //  dd($vente);
        return datatables()->of($vente)
            ->addColumn('action', function ($clt) {
                return  '<a class="btn btn-info " onclick="show(' . $clt->id . ')" ><i class="fa  fa-info"></i></a>';
            })
            ->make(true) ;
    }

    public function venteannee($id)
    {
        /*$vente = DB::table('ventes')
            ->join('journals', function ($join) {
                $join->on('ventes.journal_id', '=', 'journals.id');
            })
            ->join('users', function ($join) {
                $join->on('ventes.user_id', '=', 'users.id');
            })
            ->where ('journals.boutique_id', '=',Auth::user()->boutique->id)
            ->where('journals.annee', '=', $id)
            // ->select('ventes.id as id','ventes.numero as vente','ventes.totaux as totaux', 'users.nom as user')
            ->selectRaw('ventes.id as id, ventes.numero as vente, ventes.totaux as totaux, CONCAT(users.nom, " ", users.prenom) as user')
            ->get();*/
        $vente = DB::table('ventes')
            ->join('journals', 'ventes.journal_id', '=', 'journals.id')
            ->join('users', 'ventes.user_id', '=', 'users.id')
            ->where('ventes.boutique_id', '=', Auth::user()->boutique->id) // Check for ventes.boutique_id
            ->where('journals.annee', '=', $id) // Check for journals.annee
            ->selectRaw('ventes.id as id, ventes.numero as vente, ventes.totaux as totaux, CONCAT(users.nom, " ", users.prenom) as user')
            ->get();
        return datatables()->of($vente)
            ->addColumn('action', function ($clt) {
                return  '<a class="btn btn-info " onclick="show(' . $clt->id . ')" ><i class="fa  fa-info"></i></a>';
            })
            ->make(true) ;
    }
    public function adminventeannee($id,$ed)
    {
        $vente = DB::table('ventes')
            ->join('journals', function ($join) {
                $join->on('ventes.journal_id', '=', 'journals.id');
            })
            ->where ('journals.boutique_id', '=',$ed)
            ->select('ventes.id as id','ventes.numero as vente','ventes.totaux as totaux')
            ->get();
        return datatables()->of($vente)
            ->addColumn('action', function ($clt) {
                return  '<a class="btn btn-info " onclick="show(' . $clt->id . ')" ><i class="fa  fa-info"></i></a>';
            })
            ->make(true) ;
    }
    public function totalmois($id,$ed)
    {
        $vente = DB::table('ventes')
            ->join('preventes', function ($join) {
                $join->on('preventes.vente_id', '=', 'ventes.id');
            })
            ->join('journals', function ($join) {
                $join->on('ventes.journal_id', '=', 'journals.id');
            })
            ->where ('journals.boutique_id', '=',Auth::user()->boutique->id)
            ->where('journals.mois', '=', $id)
            ->where('journals.annee', '=', $ed)
            ->sum('preventes.prixtotal');
        return $vente;
    }
    public function admintotalmois($id,$ed,$ad)
    {
        $vente = DB::table('ventes')
            ->join('preventes', function ($join) {
                $join->on('preventes.vente_id', '=', 'ventes.id');
            })
            ->join('journals', function ($join) {
                $join->on('ventes.journal_id', '=', 'journals.id');
            })
            ->where ('journals.boutique_id', '=',$ad)
            ->where('journals.mois', '=', $id)
            ->where('journals.annee', '=', $ed)
            ->sum('preventes.prixtotal');
        return $vente;
    }
    public function totalannee($id)
    {
        $vente = DB::table('ventes')
            ->join('preventes', function ($join) {
                $join->on('preventes.vente_id', '=', 'ventes.id');
            })
            ->join('journals', function ($join) {
                $join->on('ventes.journal_id', '=', 'journals.id');
            })
            ->where ('journals.boutique_id', '=',Auth::user()->boutique->id)
            ->where('journals.annee', '=', $id)
            ->sum('preventes.prixtotal');
        return $vente;
    }
    public function admintotalannee($id,$ed)
    {
        $vente = DB::table('ventes')
            ->join('preventes', function ($join) {
                $join->on('preventes.vente_id', '=', 'ventes.id');
            })
            ->join('journals', function ($join) {
                $join->on('ventes.journal_id', '=', 'journals.id');
            })
            ->where ('journals.boutique_id', '=',$ed)
            ->where('journals.annee', '=', $id)
            ->sum('preventes.prixtotal');
        return $vente;
    }


    public function recuperdatevente()
    {
        $date = DB::table('ventes')
            ->join('journals', 'ventes.journal_id', '=', 'journals.id')
            ->where('journals.boutique_id', '=', Auth::user()->boutique->id)
            ->select('ventes.journal_id as journal', 'journals.id as id', 'journals.date_creation as date')
            ->distinct()
            ->get();

        $a=array();
        $d=array();
        $table=array();
        for ($i = 0; $i <count($date); $i++) {
            setlocale(LC_TIME,'fr_FR','fra_FRA');
            $b=strftime('%A %d %B %G', strtotime($date[$i]->date));
            $a[$i]=$b;
            $d[$i]=$date[$i]->id;
            $c=mb_convert_encoding($a,'UTF-8','UTF-8');
        }
        $table["fran"]=$c;
        $table["id"]=$d;

        return $table;
    }
    public function adminrecuperdatevente($id)
    {
        $date = DB::table('ventes')
            ->join('journals', function ($join) {
                $join->on('ventes.journal_id', '=', 'journals.id');
            })
            ->where ('journals.boutique_id', '=',$id)
            ->select('ventes.journal_id as journal','journals.id as id','journals.date_creation as date')
            ->groupBy('journal', 'id', 'date')
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
                $d[$i]=$date[$i]->id;
                $c=mb_convert_encoding($a,'UTF-8','UTF-8');
            }
            $table["fran"]=$c;
            $table["id"]=$d;
            return $table;
        }


    }

    public function annee()
    {

        $date = DB::table('ventes')
            ->join('journals', function ($join) {
                $join->on('ventes.journal_id', '=', 'journals.id');
            })
            ->where ('journals.boutique_id', '=',Auth::user()->boutique->id)
            ->select('journals.annee as annee')
            ->groupBy ('journals.annee')
            ->get() ;
        return $date;
    }
    public function adminannee($id)
    {

        $date = DB::table('ventes')
            ->join('journals', function ($join) {
                $join->on('ventes.journal_id', '=', 'journals.id');
            })
            ->where ('journals.boutique_id', '=',$id)
            ->select('journals.annee as annee')
            ->groupBy ('journals.annee')
            ->get() ;
        return $date;
    }

    public function historique()
    {
        return view('historiquevente');
    }
    public function adminhistorique()
    {
        $boutiques=Boutique::all();
        return view('adminhistoriquevente',compact('boutiques'));
    }

    public function vente($id)
    {
        $commande = DB::table('ventes')
            ->join('preventes', function ($join) {
                $join->on('preventes.vente_id', '=', 'ventes.id');
            })
            ->join('modeles', function ($join) {
                $join->on('modeles.id', '=', 'preventes.modele_fournisseur_id');
            })
            ->join('produits', function ($join) {
                $join->on('produits.id', '=', 'modeles.produit_id');
            })
            ->where('ventes.id','=',$id)
            ->select(
                'modeles.libelle as modele',
                // 'preventes.etat as etat',
                'produits.nom as produit',
                'preventes.id as id')
            ->get();
        return $commande;
    }

    public function livraisonvente($id)
    {
        $commande = DB::table('ventes')
            ->join('preventes', function ($join) {
                $join->on('preventes.vente_id', '=', 'ventes.id');
            })
            ->join('modeles', function ($join) {
                $join->on('modeles.id', '=', 'preventes.modele_fournisseur_id');
            })
            ->join('produits', function ($join) {
                $join->on('produits.id', '=', 'modeles.produit_id');
            })
            ->where('ventes.id','=',$id)
            ->where('preventes.etat','=',1)
            ->select(
                'modeles.libelle as modele',
                // 'preventes.etat as etat',
                'produits.nom as produit',
                'preventes.id as id')
            ->get();
        return $commande;
    }

    public function retourvente($id)
    {
        $vente = vente::findOrFail($id);

        $commande = DB::table('ventes')
            ->join('preventes', function ($join) {
                $join->on('preventes.vente_id', '=', 'ventes.id');
            })
            ->join('modeles', function ($join) {
                $join->on('modeles.id', '=', 'preventes.modele_fournisseur_id');
            })
            ->join('produits', function ($join) {
                $join->on('produits.id', '=', 'modeles.produit_id');
            })
            ->where('ventes.id','=',$id)
            ->select(
                'modeles.libelle as modele',
                // 'preventes.etat as etat',
                'produits.nom as produit',
                'preventes.id as id')
            ->get();

        $historique=new Historique();
        $historique->actions = "Creer";
        $historique->cible = "Retour Vente";
        $historique->user_id =Auth::user()->id;
        $historique->save();
       return  view('retourvente',compact('vente', 'commande'));
    }

    public function storeretourevente(Request $request, $id)
    {
        DB::beginTransaction();

        // $vente = Vente::findOrFail($id);
        $retour = new Retour();
        $retour->vente_id = $id;
        $retour->boutique_id = Auth::user()->boutique->id;
        $retour->save();

        $allretour= explode( ',', $request->input('retTable') );
        // dd($allretour);
        for ($i =0 ;$i<count($allretour);$i+=4) {
            $commande = DB::table('ventes')
                ->join('preventes', function ($join) {
                    $join->on('preventes.vente_id', '=', 'ventes.id');
                })
                ->join('modeles', function ($join) {
                    $join->on('modeles.id', '=', 'preventes.modele_fournisseur_id');
                })
                ->join('produits', function ($join) {
                    $join->on('produits.id', '=', 'modeles.produit_id');
                })
                ->join('fournisseurs', function ($join) {
                    $join->on('fournisseurs.id', '=', 'modele_fournisseurs.fournisseur_id');
                })
                ->where('preventes.id','=',$allretour[$i])
                ->select('preventes.quantite as quantite', 'preventes.prix as prix', 'preventes.id as prevente_id','modeles.id as id','modeles.quantite as modele_qte', 'ventes.id as vente_id')
                ->first();

                $quantite= RetourLigne::where('prevente_id',$allretour[$i] )
                    ->sum('quantite_retourner');

                $retourcommande = new RetourLigne();
                $retourcommande ->retour_id=$retour->id;
                $retourcommande ->prevente_id=$allretour[$i];
                $retourcommande ->vente_id=$id;
                $retourcommande ->quantite_retourner =$allretour[$i+1];
                $retourcommande ->payer =$allretour[$i+2] == "OUI" ? true : false;
                $retourcommande ->rayon =$allretour[$i+3] == "OUI" ? true : false;
                $retourcommande ->montant = $allretour[$i+1] * $commande->prix;
                $retourcommande->quantite_restante = $commande->quantite - $quantite - $allretour[$i+1];
                $retourcommande->save();

                $modele = Modele::
                    join('modele_fournisseurs', function ($join) {
                        $join->on('modele_fournisseurs.modele_id', '=', 'modeles.id');
                    })
                    ->join('preventes', function ($join) {
                        $join->on('preventes.modele_fournisseur_id', '=', 'modele_fournisseurs.id');
                    })
                    ->join('retour_lignes', function ($join) {
                        $join->on('retour_lignes.prevente_id', '=', 'preventes.id');
                    })
                    ->where('retour_lignes.id','=',$retourcommande->id)
                    ->select('modeles.id as id')
                    ->first();

                $modele= Modele::findOrFail($modele->id);
                $modele->quantite=$modele->quantite + $retourcommande->quantite_retourner;
                $modele->update();

                $ret= RetourLigne::
                    where('prevente_id',$commande->prevente_id )
                    ->latest('created_at')
                    ->first();

                $montan_total = RetourLigne::where(['retour_id' => $retour->id])->sum("montant");
                $montan_payer = RetourLigne::where(['retour_id' => $retour->id, 'payer' => 1])->sum("montant");

                $retour->totaux = $montan_total;
                $retour->payer = $montan_payer;
                $retour->update();

                        // if ($ret->quantite_restante==0){
                        //     DB::table('preventes')
                        //         ->where('id',$commande->prevente_id)
                        //         ->update(['etat' => false]);
                        // }
            }
                $historique=new Historique();
                $historique->actions = "Store";
                $historique->cible = "Retour Vente";
                $historique->user_id =Auth::user()->id;
                $historique->save();

            DB::commit();

            return $retour;
    }

    public function retoureventeverification($id)
    {
        $commande = DB::table('ventes')
            ->join('preventes', function ($join) {
                $join->on('preventes.vente_id', '=', 'ventes.id');
            })
            ->join('modele_fournisseurs', function ($join) {
                $join->on('modele_fournisseurs.id', '=', 'preventes.modele_fournisseur_id');
            })
            ->join('modeles', function ($join) {
                $join->on('modeles.id', '=', 'modele_fournisseurs.modele_id');
            })
            ->join('produits', function ($join) {
                $join->on('produits.id', '=', 'modeles.produit_id');
            })
            ->join('fournisseurs', function ($join) {
                $join->on('fournisseurs.id', '=', 'modele_fournisseurs.fournisseur_id');
            })
            ->where('preventes.id','=',$id)
            ->select('preventes.quantite as quantite', 'preventes.prix as prix', 'preventes.id as prevente_id','modeles.id as id','modeles.quantite as modele_qte', 'ventes.id as vente_id')
            ->first();
        $quantite= RetourLigne::
            where('prevente_id',$id)
            ->sum('quantite_retourner');
        return $commande->quantite - $quantite;
    }

    public function retourventedetail($id)
    {
        $retourLignes = RetourLigne::
            join('preventes', function ($join) {
                $join->on('retour_lignes.prevente_id', '=', 'preventes.id');
            })
            ->join('modele_fournisseurs', function ($join) {
                $join->on('modele_fournisseurs.id', '=', 'preventes.modele_fournisseur_id');
            })
            ->join('modeles', function ($join) {
                $join->on('modeles.id', '=', 'modele_fournisseurs.modele_id');
            })
            ->join('produits', function ($join) {
                $join->on('produits.id', '=', 'modeles.produit_id');
            })
            ->where('retour_lignes.retour_id','=',$id)
            ->select('retour_lignes.*',
                'modeles.libelle as modele',
                'produits.nom as produit',
                'preventes.quantite as quantite',
                'preventes.prix as prix',
                'preventes.prixtotal as prixtotal'
                )
            ->get();

        return view('retourventedetail', compact('retourLignes'));
    }

    public function bon_de_livraison(Request $request, $id)
    {

        $name = "bon_livraison_".date('Y-m-d_H-i-s', strtotime(now())).".pdf";
        $vente = DB::table('ventes')
            ->join('preventes', function ($join) {
                $join->on('preventes.vente_id', '=', 'ventes.id');
            })
            ->join('modeles', function ($join) {
                $join->on('modeles.id', '=', 'preventes.modele_fournisseur_id');
            })
            ->join('produits', function ($join) {
                $join->on('produits.id', '=', 'modeles.produit_id');
            })
            ->join('clients', function ($join) {
                $join->on('clients.id', '=', 'ventes.client_id');
            })
            ->where('ventes.id','=',$id)
            ->select('ventes.numero as numero',
                'ventes.date_vente as date',
                'ventes.facture as facture',
                'modeles.libelle as modele',
                'modeles.ref_modele as ref',
                'produits.nom as produit',
                'preventes.quantite as quantite',
                'preventes.prix as prix',
                'preventes.prixtotal as prixtotal',
                'clients.nom as Nclient',
                'ventes.created_at as create',
                'ventes.updated_at as update')
            ->get();
        $total = DB::table('ventes')
            ->join('preventes', function ($join) {
                $join->on('preventes.vente_id', '=', 'ventes.id');
            })
            ->where('ventes.id','=',$id)
            ->SUM('preventes.prixtotal');
        $all_vente = Vente::find($id);

        try{
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', TRUE);
            $options->set('isPhpEnabled', true);

            $dompdf = new Dompdf($options);

            // Chargez la vue dans Dompdf
            $view = view('bon_livraison', compact('vente', 'total', 'all_vente'))->render();
            $dompdf->loadHtml($view);

            // Définissez la taille du papier
            $dompdf->setPaper('a4');

            // Rendez le PDF
            $dompdf->render();

            // Enregistrez le PDF dans un répertoire
            file_put_contents(public_path("bons/" . $name), $dompdf->output());

        }catch(Exception $e)
        {}

        //return $pdf->download($name);
        return response()->download(public_path("bons/" . $name));
    }

    public function getLivraisonsByProduit(Request $request)
    {
        try {
            // Obtenir les paramètres de la requête
            $modele_id = $request->query('modele_id');
            $boutique_id = $request->query('boutique_id');

            // Valider que les paramètres sont des nombres
            if (!is_numeric($modele_id) || !is_numeric($boutique_id)) {
                return response()->json([
                    'error' => 'Les paramètres modele_id et boutique_id doivent être des nombres.',
                ], 400);
            }

            // Récupérer les livraisons associées au modèle et à la boutique
            $livraisons = livraisonCommande::join('livraisons', 'livraison_commandes.livraison_id', '=', 'livraisons.id') // Jointure explicite
                            ->where('livraisons.boutique_id', $boutique_id) // Filtrage par boutique_id
                            ->where('livraison_commandes.modele_id', $modele_id) // Filtrage par modele_id
                            ->with('livraison') // Charger les relations nécessaires
                            ->get(); // Obtenir les résultats


            if ($livraisons->isEmpty()) {
                return response()->json([
                    'message' => 'Aucune livraison trouvée pour ce modèle et cette boutique.',
                    'data' => $livraisons
                ], 404);
            }

            // Mapper les résultats pour obtenir les données requises
            $livraisons_data = $livraisons->map(function ($livraison_commande) {
                $livraison = $livraison_commande->livraison;

                return [
                    'id' => $livraison->id,
                    'numero' => $livraison->numero,
                    'date_livraison' => $livraison->date_livraison,
                    'modele_libelle' => $livraison_commande->modele_produit()->libelle, // Libelle du modèle
                    'quantite_restante' => $livraison_commande->quantite_livre -  $livraison_commande->quantite_vendue, // Quantité restante
                ];
            });

            return response()->json([
                'livraisons' => $livraisons_data,
            ]);

        } catch (Exception $e) {
            // Gestion des exceptions inattendues
            return response()->json([
                'error' => 'Erreur inattendue : ' . $e->getMessage(),
            ], 500);
        }
    }


    public function delivered_vente($id){
        $vente = vente::find($id);
        $vente->delivered = "delivered";
        $vente->save();

        $livraison_nums = [];
        $preventes = Prevente::where('vente_id', $vente->id)->get();
        foreach($preventes as $prevente){
            $livraison = Livraison::where('numero', $prevente->livraison)->get()->first();
            $livraisonCommande = livraisonCommande::where('livraison_id', $livraison->id)->where('modele_id', $prevente->modele_fournisseur_id)->get()->first();
            $livraisonCommande->quantite_vendue += $prevente->quantite;
            $livraisonCommande->save();
        }


        return response()->json([
            'message' => 'Vente marquée comme livrée avec succès.',
            'data' => $vente
        ]);
    }


}
