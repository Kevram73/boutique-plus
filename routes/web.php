<?php

use App\Caisse;
use RealRashid\SweetAlert\Facades\Alert;
use App\CompteBancaire;
use Carbon\Carbon;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // Alert::success('Success Title', 'Success Message');
    return redirect('/connexion');
});


Route::get('/listemontantcollecte','CaisseController@listemontantcollecte')->name('listemontantcollecte');

// Route::get('/b', 'BordsController@b');
Route::group(['middleware' => 'flagetat'], function () {
    // Routes nécessitant la vérification du flagetat
    Route::get('/bord', 'BordsController@index')->name('bord');

});

Route::get('/bordcaisse', 'BordsController@caisse')->name('caissebord');
Route::get('/bordmagasin', 'BordsController@magasin')->name('magasinbord');
Route::get('/bordadmin', 'BordsController@admin')->name('adminbord');
Route::get('/allseuil', 'BordsController@allseuil')->name('allseuil');
//Route::resource('Users','UserController');

Route::post('/ajoutDEBITEURINVENT', 'ClientsController@storeDEBITEUR');

Route::get('/clientAjout', 'ClientsController@clientAjout')->name('clientAjout');

Route::get('/clients', 'ClientsController@liste')->name('clients');
Route::get('/allclient', 'ClientsController@index');
Route::get('/deleteclient-{id}', 'ClientsController@destroy');
Route::post('/ajoutclient', 'ClientsController@store');
Route::post('/updateclient', 'ClientsController@update');
Route::get('/showclient-{id}', 'ClientsController@show');
Route::get('/livraison', 'LivraisonsController@liste')->name('livraisons');

Route::get('/showlivraisonNew-{id}', 'LivraisonsController@showNewOne');
Route::get('/livraison-{id}', 'LivraisonsController@showNew');
//Route::get('/newlivraisonCentrale', 'LivraisonsController@livraisonNew');

Route::get('/newlivraisonCentrale', 'LivraisonsController@createNew');
Route::get('/alllivraisonNew', 'LivraisonsController@indexNew');
Route::get('/deletelivraisonNew-{id}', 'LivraisonsController@destroyNew');
Route::post('/storelivraisonNew', 'LivraisonsController@storeNew');
Route::post('/updatelivraisonNew', 'LivraisonsController@updateNew');
Route::get('/showlivraison-{id}', 'LivraisonsController@showNew');
Route::get('/verificationNew-{id}', 'LivraisonsController@verificationNew');
Route::get('/detaillivraisonNew-{id}', 'LivraisonsController@showNew1');

Route::get('/modeleboutique-{id}', 'BoutiquesController@modeleboutique');
Route::get('/recuperercommandeNewmodele-{id}', 'LivraisonsController@commande1');
Route::get('/showlivNew-{id}', 'LivraisonsController@showN');
//Route::get('/alllivraisonNew', 'LivraisonsController@indexNew1');
Route::get('/recupercommande-{id}', 'LivraisonsController@commande1');

Route::get('/boutiques', 'BoutiquesController@liste')->name('boutiques');
Route::get('/allboutique', 'BoutiquesController@index');
Route::get('/deleteboutique-{id}', 'BoutiquesController@destroy');
Route::post('/ajoutboutique', 'BoutiquesController@store');
Route::post('/updateboutique', 'BoutiquesController@update');
Route::get('/showboutique-{id}', 'BoutiquesController@show');
Route::get('/showboutiquevaleur-{id}', 'BoutiquesController@showValeur');
Route::post('/settings', 'BoutiquesController@settingStore');
Route::get('/settings-{id}', 'BoutiquesController@settingIndex');
Route::get('/restantinventairedebitor-{id}', 'InventairesController@totalachat');
Route::get('/calculate/{id}', 'InventairesController@calculate');
Route::post('/updatesuperinventaire', 'InventairesController@updateModeleSuperInventaire');
Route::get('/ajouterinventaire', 'InventairesController@ajouterInventaire')->name('ajouterinventaire');

Route::get('/fournisseurs', 'FournisseursController@liste')->name('fournisseurs');
Route::get('/allfournisseur', 'FournisseursController@index');
Route::get('/deletefournisseur-{id}', 'FournisseursController@destroy');
Route::post('/ajoutfournisseur', 'FournisseursController@store');
Route::post('/updatefournisseur', 'FournisseursController@update');
Route::get('/showfournisseur-{id}', 'FournisseursController@show');

Route::get('/allfourni', 'FournisseursController@index2');
Route::get('/recupererproduit-{id}', 'FournisseursController@produit');
Route::get('/recupererproduit2-{id}', 'FournisseursController@produit2');
Route::get('/recuperermodele-{id}', 'FournisseursController@modele');
Route::get('/recuperermodele2-{id}', 'FournisseursController@modele2');
Route::get('/recupefournisseurmodele-{modele}-{fournisseur}', 'FournisseursController@fournisseurmodele');
Route::get('/recupefournisseur-{id}', 'FournisseursController@fournisseur');
Route::get('/recupefournisseurgros-{id}', 'FournisseursController@fournisseurgros');
Route::get('/deletefourni-{id}', 'FournisseursController@destroy2');
Route::post('/ajoutfourni', 'FournisseursController@store2');
Route::post('/updatefourni', 'FournisseursController@update2');
Route::get('/showfourni-{id}', 'FournisseursController@show2');


Route::get('/recuperermodeleboutiq-{id}', 'LivraisonsController@produit');


Route::get('/recupevaleurbilling-{id}', 'CaisseController@valeurBilling');


Route::get('/employes', 'EmployesController@liste')->name('employes');
Route::get('/allemploye', 'EmployesController@index');
Route::get('/deleteemploye-{id}', 'EmployesController@destroy');
Route::post('/ajoutemploye', 'EmployesController@store');
Route::post('/updateemploye', 'EmployesController@update');
Route::get('/showemploye-{id}', 'EmployesController@show');


Route::get('/utilisateurs', 'UserController@liste')->name('utilisateurs');
Route::get('/allUser', 'UserController@index');
Route::get('/deleteUser-{id}', 'UserController@destroy');
Route::post('/ajoutUser', 'UserController@store');
Route::post('/updateUser', 'UserController@update');
Route::post('/updateUser2', 'UserController@update2');
Route::get('/showUser-{id}', 'UserController@show');
Route::get('/show', 'UserController@create');
Route::get('/compte', 'UserController@compte')->name('compte');
Route::get('/changeUserState-{id}', 'UserController@changeState');
Route::get('/changeBoutiqState-{id}', 'BoutiquesController@changeState');


Route::get('/changeUserPwd-{id}', 'UserController@changePwd');



Route::get('/categories', 'CategoriesController@liste')->name('categories');
Route::get('/recuperercategorie', 'CategoriesController@categorie');
Route::get('/allcategorie', 'CategoriesController@index');
Route::get('/deletecategorie-{id}', 'CategoriesController@destroy');
Route::post('/ajoutcategorie', 'CategoriesController@store');
Route::post('/updatecategorie', 'CategoriesController@update');
Route::get('/showcategorie-{id}', 'CategoriesController@show');


//Route::get('/allinventaire', 'ModelesController@inventaire');
Route::get('/inventaire', 'InventairesController@liste')->name('inventaire');
Route::get('/inventairesuper', 'InventairesController@listesuper')->name('inventairesuper');
Route::get('/inventaireglobal', 'InventairesController@listeglobal')->name('inventaireglobal');


Route::get('/allinventairesuper-{id}', 'InventairesController@indexsuper');
Route::get('/allinventaire', 'InventairesController@index');
Route::get('/inventaire_non_regulated','InventairesController@list_non_regulated');
Route::get('/inventaire_non_reg-{id}','InventairesController@regulate_inventaire');

//Route::get('/remboursement-{id}','InventairesController@remboursement_inventaire');
Route::get('/debtor_inv_amount',
    'InventairesController@get_client_amount_by_inventory');

Route::get('/allinventairepending', 'InventairesController@indexPending');
Route::get('/newinventaire', 'InventairesController@create');
Route::post('/inventaire_pay', 'InventairesController@change_inventaire_regulate');
Route::post('/inventaire-debtors','InventairesController@create_inventory_debtor');
Route::get('/new2inventaire-{id}', 'InventairesController@create2invt');
Route::get('/fermerinventaire', 'InventairesController@fermer');
Route::get('/fermerinventaire-{id}', 'InventairesController@fermerbydata');
Route::get('/toutinventaire', 'InventairesController@toutinventaire');
Route::get('/recuperercategorie', 'InventairesController@categorie');
Route::get('/inventairecategorie-{id}', 'InventairesController@inventairecategorie');
Route::get('/showinventaire-{id}', 'InventairesController@show');
Route::get('/showinvent-{id}', 'InventairesController@show2');
Route::get('/detailinventaire-{id}', 'InventairesController@show2');
Route::get('/detailinventaireprint-{id}', 'InventairesController@show3');
Route::post('/updateinventaire', 'InventairesController@update');
Route::post('/updateinventaire-{id}', 'InventairesController@update2');
Route::post('/createinventaire', 'InventairesController@create2');
Route::get('/deleteinventaire/{id}', 'InventairesController@destroy');
Route::get('/inventaires', 'ModelesController@invent')->name('inventaires');


Route::get('/modeles', 'ModelesController@liste')->name('modeles');
Route::get('/allmodele', 'ModelesController@index');
Route::get('/allmodelevente-{id}', 'ModelesController@allmodelvent');
Route::get('/deletemodele-{id}', 'ModelesController@destroy');
Route::post('/ajoutmodele', 'ModelesController@store');
Route::post('/updatemodele', 'ModelesController@update');
Route::get('/showmodele-{id}', 'ModelesController@show');
Route::get('modeles-reporting', 'ModelesController@liste_reporting')->name('liste_reporting');
Route::get('/allreportvent', 'ModelesController@allreportvent');
Route::get('/allreportventsuper', 'InventairesController@allreportventsuper');


Route::get('/allreportventsum', 'ModelesController@allreportventsum');

Route::get('/allproduit', 'ProduitsController@index');
Route::get('/deleteproduit-{id}', 'ProduitsController@destroy');
Route::post('/ajoutproduit', 'ProduitsController@store');
Route::post('/updateproduit', 'ProduitsController@update');
Route::get('/showproduit-{id}', 'ProduitsController@show');

Route::get('/services', 'ServiceController@index')->name('services');
Route::post('/ajoutservice', 'ServiceController@store');
Route::post('/updateservice', 'ServiceController@update');
Route::get('/allservice', 'ServiceController@allservice');
Route::get('/showservice-{id}', 'ServiceController@show');
Route::get('/deleteservice-{id}', 'ServiceController@destroy');
Route::get('/prestations', 'ServiceController@index_prestation')->name('prestations');

Route::get('/livraisons', 'LivraisonsController@liste')->name('livraisons');
Route::get('/newlivraison', 'LivraisonsController@create')->name('newlivraison');
Route::get('/alllivraison', 'LivraisonsController@index');
Route::get('/deletelivraison-{id}', 'LivraisonsController@destroy');
Route::post('/storelivraison', 'LivraisonsController@store');
Route::post('/updatelivraison', 'LivraisonsController@update');
Route::get('/showlivraison-{id}', 'LivraisonsController@show');
Route::get('/verification-{id}', 'LivraisonsController@verification');
Route::get('/detaillivraison-{id}', 'LivraisonsController@show')->name('detaillivraison');

Route::get('/livraisons2', 'LivraisonsController@liste2')->name('livraisons2');
Route::get('/newlivraison2', 'LivraisonsController@create2')->name('newlivraisonvente');
Route::get('/alllivraison2', 'LivraisonsController@index2');
Route::get('/deletelivraison2-{id}', 'LivraisonsController@destroy2');
Route::post('/storelivraison2', 'LivraisonsController@store2');
Route::post('/updatelivraison2', 'LivraisonsController@update2');
Route::get('/showlivraison2-{id}', 'LivraisonsController@show2');
Route::get('/verification2-{id}', 'LivraisonsController@verification2');


Route::get('/recupererfournisseurP', 'FournisseursController@fournisseurP');
Route::get('/recupererfournisseur-{id}', 'ProvisionsController@fournisseur');
Route::get('/recupererprovision-{id}-{ed}', 'ProvisionsController@provision');
Route::get('/provisions', 'ProvisionsController@liste')->name('provisions');
Route::get('/newcommande', 'CommandesController@create')->name('newcommande');
Route::get('/newcommande2', 'CommandesController@create2')->name('newcommande2');
Route::post('/storecommande', 'CommandesController@store');
Route::post('/storecommande2', 'CommandesController@store2');
Route::post('/storecommandeindirecte', 'CommandesController@storeindirecte');
Route::get('/allcommande', 'CommandesController@index');
Route::get('/deletecommande-{id}', 'CommandesController@destroy');
Route::post('/updatecommande', 'CommandesController@update');
Route::get('/showcommande-{id}', 'CommandesController@show');
Route::get('/a-{id}', 'CommandesController@a');
Route::get('/detailcommande-{id}', 'CommandesController@show')->name('detailcommande');
Route::get('/recuperercommandemodele-{id}', 'CommandesController@commande');
Route::get('/recupererachatdate-{id}', 'CommandesController@achatdate');
Route::get('/recupererachatannee-{id}', 'CommandesController@achatannee');
Route::get('/recupererachatmois-{id}-{ed}', 'CommandesController@achatmois');
Route::get('/depensejour-{id}', 'CommandesController@depensejour');
Route::get('/depensemois-{id}-{ed}', 'CommandesController@depensemois');
Route::get('/depenseannee-{id}', 'CommandesController@depenseannee');
Route::get('/recupererdateachat', 'CommandesController@recuperdateachat');
Route::get('/recuperermoisachat', 'CommandesController@recupermoisachat');
Route::get('/annee', 'CommandesController@annee');
Route::get('/historiqueachat', 'CommandesController@historique');
Route::get('/adminrecupererachatdate-{id}-{ed}', 'CommandesController@adminachatdate');
Route::get('/adminrecupererachatannee-{id}-{ed}', 'CommandesController@adminachatannee');
Route::get('/adminrecupererachatmois-{id}-{ed}-{ad}', 'CommandesController@adminachatmois');
Route::get('/admindepensejour-{id}-{ed}', 'CommandesController@admindepensejour');
Route::get('/admindepensemois-{id}-{ed}-{ad}', 'CommandesController@admindepensemois');
Route::get('/admindepenseannee-{id}-{ed}', 'CommandesController@admindepenseannee');
Route::get('/adminrecupererdateachat-{id}', 'CommandesController@adminrecuperdateachat');
Route::get('/adminrecuperermoisachat-{id}', 'CommandesController@adminrecupermoisachat');
Route::get('/adminannee-{id}', 'CommandesController@adminannee');
Route::get('/adminhistoriqueachat', 'CommandesController@adminhistorique')->name('adminhistoriqueachat');



Route::get('/allfournihistoriquesum', 'CommandesController@allfournihistoriquesum');
Route::get('/allLivraisonBoutiqhistoriquesum', 'LivraisonsController@allLivraisonBoutiqhistoriquesum');

Route::get('/allfournihistorique', 'CommandesController@allfournihistorique');
Route::get('/alllivraisonBoutiqhistorique', 'LivraisonsController@alllivraisonBoutiqhistorique');

Route::get('/allfournihistori', 'CommandesController@tyuio');


Route::get('/recupererventemodele-{id}', 'VentesController@vente');
Route::get('/recupererlivraisonventemodele-{id}', 'VentesController@livraisonvente');
Route::get('/recuperercredit-{id}', 'VentesController@credit');
Route::get('/recupereravoir-{id}', 'VentesController@avoir');

Route::get('/devisvente', 'VentesController@createdevis')->name('devisvente');
Route::get('/devisventegros', 'VentesController@createdevisgros')->name('devisventegros');
Route::get('/ventesimple', 'VentesController@create')->name('ventesimple');
Route::get('/ventecredit', 'VentesController@create2')->name('ventecredit');
Route::get('/ventenonlivre', 'VentesController@create3')->name('ventenonlivre');
Route::get('/ventegros', 'VentesController@create4')->name('ventegros');
Route::get('/ventes', 'VentesController@liste')->name('ventes');
Route::get('/reglements-{id}', 'VentesController@reglement')->name('reglements');
Route::get('/reglementcredit-{id}', 'VentesController@reglementcredit')->name('reglementcredit');
Route::get('/reglementgros-{id}', 'VentesController@reglementgros')->name('reglementgros');
Route::get('/facturedevis-{id}', 'VentesController@facturedevis')->name('facturedevis');
Route::get('/facturedevisgros-{id}', 'VentesController@facturedevisgros')->name('facturedevisgros');
Route::get('/facturesimple-{id}', 'VentesController@facturesimple')->name('facturesimple');
Route::get('/facturecredit-{id}', 'VentesController@facturecredit')->name('facturecredit');
Route::get('/facturegros-{id}', 'VentesController@facturegros')->name('facturegros');
Route::get('/debiteurs', 'VentesController@debiteurs')->name('debiteurs');
Route::get('/alldebiteurs', 'VentesController@alldebiteurs')->name('alldebiteurs');

Route::post('/regler', 'ReglementsController@store');
Route::post('/regler-{id}', 'ReglementsController@store3');
Route::get('/reglementlist', 'ReglementsController@reglementlist')->name('reglementlist');
Route::get('/reglementlistallshoop', 'ReglementsController@reglementlistallshoop')->name('reglementlistallshoop');


Route::get('/reglementachatlist', 'ReglementsController@reglementachatlist')->name('reglementachatlist');
Route::get('/reglementlist-{id}', 'ReglementsController@reglementlistshow')->name('reglementlistshow');
//Route::get('/reglementlist-{id}', 'ReglementsController@reglementlistshow')->name('reglementlistshow');

Route::get('/reglementachatlist-{id}', 'ReglementsController@reglementachatlistshow')->name('reglementachatlistshow');
Route::get('/reglementsbyclient-{id}', 'ReglementsController@reglementsbyclient');
Route::get('/reglementsbyfournisseur-{id}', 'ReglementsController@reglementsbyfournisseur');
Route::get('/ventesbyclient-{id}', 'ReglementsController@ventesbyclient');
Route::get('/commandesbyfournisseur-{id}', 'ReglementsController@commandesbyfournisseur');
Route::get('/reglement', 'ReglementsController@liste')->name('reglement');
Route::get('/allreglement', 'ReglementsController@index');
Route::post('/storereglement', 'ReglementsController@store2');
Route::get('/showreglement-{id}', 'ReglementsController@show');
Route::post('/updatereglement', 'ReglementsController@update');
Route::get('/deletereglement-{id}', 'ReglementsController@destroy');
Route::post('/storereglementachat', 'ReglementsController@storeachat');
Route::get('/showreglementachat-{id}', 'ReglementsController@showachat');
Route::post('/updatereglementachat', 'ReglementsController@updateachat');
Route::get('/deletereglementachat-{id}', 'ReglementsController@destroyachat');
Route::get('/restant-{id}', 'ReglementsController@total');
Route::get('/restantachat-{id}', 'ReglementsController@totalachat');

Route::get('/get-solde-{id}', function($id) {
    $user = CompteBancaire::findOrFail($id);
    //dd($user);
    return response()->json(['solder' => $user->solder]);
    })->name('get-solde');
    Route::get('/get-date', function() {
        $user = DB::table('caisses')
        ->where('date', '=',Carbon::now()->format('Y-m-d'))
        ->where ('boutique_id', '=',Auth::user()->boutique->id )
        ->exists();
        //dd($user);
        return response()->json($user);
        })->name('get-date');
Route::get('/expl', 'ReglementsController@create');
Route::get('/recettes', 'ReglementsController@recetteIndex')->name('recettes');
Route::get('/recetteslist', 'ReglementsController@recetteListe');
Route::post('/recettestore', 'ReglementsController@recetteStore');
Route::get('/recettesshow-{id}', 'ReglementsController@recetteShow');
Route::post('/recetteupdate-{id}', 'ReglementsController@recetteUpdate');
Route::get('/recettedelete-{id}', 'ReglementsController@recetteDelete');
Route::get('/fictive-{id}', 'VentesController@fictiveCreate');
Route::post('/factures/fictive', 'VentesController@fictive');
Route::get('/retourvente-{id}', 'VentesController@retourvente')->name('retourvente');
Route::get('/retourventedetail-{id}', 'VentesController@retourventedetail')->name('retourventedetail');
Route::post('/storeretourevente-{id}', 'VentesController@storeretourevente')->name('storeretourevente');
Route::get('/retoureventeverification-{id}', 'VentesController@retoureventeverification')->name('retoureventeverification');

Route::post('/storevente', 'VentesController@store')->name('storevente');
Route::post('/storevente2', 'VentesController@store2')->name('storevente2');
Route::post('/storevente3', 'VentesController@store3')->name('storevente3');
Route::post('/storevente4', 'VentesController@store4')->name('storevente4');
Route::post('/storedevis', 'VentesController@storedevis');
Route::get('/allvente', 'VentesController@index');
Route::get('/verification', 'VentesController@verification');
Route::get('/journal', 'VentesController@journal');
Route::get('/fermer', 'VentesController@fermer');
Route::get('/deletevente-{id}', 'VentesController@destroy');
Route::post('/updatevente', 'VentesController@update');
Route::get('/edit', 'VentesController@edit');
Route::get('/showvente-{id}', 'VentesController@show')->name("show.vente");
Route::get('/detailvente-{id}', 'VentesController@show')->name('detailvente');
Route::get('/detailvente2-{id}', 'VentesController@show')->name('detailvente2');
Route::get('/recupererventedate-{id}', 'VentesController@ventedate');
Route::get('/recupererventeannee-{id}', 'VentesController@venteannee');
Route::get('/recupererventemois-{id}-{ed}', 'VentesController@ventemois');
Route::get('/totaljour-{id}', 'VentesController@totaljour');
Route::get('/totalmois-{id}-{ed}', 'VentesController@totalmois');
Route::get('/totalannee-{id}', 'VentesController@totalannee');
Route::get('/anneevente', 'VentesController@annee');
Route::get('/recupererdatvente', 'VentesController@recuperdatevente');
Route::get('/historiquevente', 'VentesController@historique');
Route::get('/adminrecupererventedate-{id}-{ed}', 'VentesController@adminventedate');
Route::get('/adminrecupererventeannee-{id}-{ed}', 'VentesController@adminventeannee');
Route::get('/adminrecupererventemois-{id}-{ed}-{ad}', 'VentesController@adminventemois');
Route::get('/admintotaljour-{id}-{ed}', 'VentesController@admintotaljour');
Route::get('/admintotalmois-{id}-{ed}-{ad}', 'VentesController@admintotalmois');
Route::get('/admintotalannee-{id}-{ed}', 'VentesController@admintotalannee');
Route::get('/adminanneevente-{id}', 'VentesController@adminannee');
Route::get('/adminrecupererdatvente-{id}', 'VentesController@adminrecuperdatevente');
Route::get('/adminhistoriquevente', 'VentesController@adminhistorique')->name('adminhistoriquevente');

Route::get('/historiques', 'HistoriquesController@liste')->name('historiques');
Route::get('/allhistorique', 'HistoriquesController@index');

Route::get('/connexion', function () {return view('connexion');})->name('connexion');
Route::get('/bloquer', function () {return view('bloquer');})->name('bloquer');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/verificationachat', 'CommandesController@verification');
Route::get('/journalachat', 'CommandesController@journal');
Route::get('/fermer_achat', 'CommandesController@fermer');

Route::get('/amortissements', 'AmortissementsController@liste')->name('amortissements');
Route::get('/allamor', 'AmortissementsController@index');
Route::get('/deleteamor-{id}', 'AmortissementsController@destroy');
Route::post('/ajoutamor', 'AmortissementsController@store');
Route::post('/updateamor', 'AmortissementsController@update');
Route::get('/showamor-{id}', 'AmortissementsController@show');


Route::get('/immobilisations', 'ImmobilisationsController@liste')->name('immobilisations');
Route::get('/allimmo', 'ImmobilisationsController@index');
Route::get('/deleteimmo-{id}', 'ImmobilisationsController@destroy');
Route::post('/ajoutimmo', 'ImmobilisationsController@store');
Route::post('/updateimmo', 'ImmobilisationsController@update');
Route::get('/showimmo-{id}', 'ImmobilisationsController@show');
Route::get('/detailimmo-{id}', 'ImmobilisationsController@detail');


Route::get('/verificationcharge', 'ChargesController@verification');
Route::get('/journalcharge', 'ChargesController@journal');
Route::get('/fermercharge', 'ChargesController@fermer');
Route::get('/charges', 'ChargesController@liste')->name('charges');
Route::get('/allcharge', 'ChargesController@index');
Route::get('/deletecharge-{id}', 'ChargesController@destroy');
Route::post('/ajoutcharge', 'ChargesController@store');
Route::post('/updatecharge', 'ChargesController@update');
Route::get('/showcharge-{id}', 'ChargesController@show');

Route::get('/recupererdatediversdepenses', 'DepenseController@recuperdatedivers');
Route::get('/diversjourdepenses-{id}', 'DepenseController@totaljour');
Route::get('/recupererdiversdatedepenses-{id}', 'DepenseController@diversdate');
Route::get('/anneediversdepenses', 'DepenseController@annee');
Route::get('/diversmoisdepenses-{id}-{ed}', 'DepenseController@totalmois');
Route::get('/recupererdiversmoisdepenses-{id}-{ed}', 'DepenseController@diversmois');
Route::get('/diversanneedepenses-{id}', 'DepenseController@totalannee');
Route::get('/recupererdiversanneedepenses-{id}', 'DepenseController@diversannee');
Route::get('/historiquedepenses', 'DepenseController@historique');
Route::get('/viewDepense-files-{id}', 'DepenseController@justificatifdepense');


Route::get('/viewCharge-files-{id}', 'ChargesController@justificatifCharge');

Route::get('/recupererdiversdate-{id}', 'ChargesController@diversdate');
Route::get('/recupererdatedivers', 'ChargesController@recuperdatedivers');
Route::get('/recupererdiversannee-{id}', 'ChargesController@diversannee');
Route::get('/recupererdiversmois-{id}-{ed}', 'ChargesController@diversmois');
Route::get('/diversjour-{id}', 'ChargesController@totaljour');
Route::get('/diversmois-{id}-{ed}', 'ChargesController@totalmois');
Route::get('/diversannee-{id}', 'ChargesController@totalannee');
Route::get('/anneedivers', 'ChargesController@annee');
Route::get('/historiquecharges', 'ChargesController@historique');

Route::get('/adminrecupererdiversdate-{id}-{ed}', 'ChargesController@admindiversdate');
Route::get('/adminrecupererdatedivers-{id}', 'ChargesController@adminrecuperdatedivers');
Route::get('/adminrecupererdiversannee-{id}-{ed}', 'ChargesController@admindiversannee');
Route::get('/adminrecupererdiversmois-{id}-{ed}-{ad}', 'ChargesController@admindiversmois');
Route::get('/admindiversjour-{id}-{ed}', 'ChargesController@admintotaljour');
Route::get('/admindiversmois-{id}-{ed}-{ad}', 'ChargesController@admintotalmois');
Route::get('/admindiversannee-{id}-{ed}', 'ChargesController@admintotalannee');
Route::get('/adminanneedivers-{id}', 'ChargesController@adminannee');
Route::get('/adminhistoriquedivers', 'ChargesController@adminhistorique')->name('adminhistoriquedivers');


Route::get('/verificationdepense', 'DepenseController@verification');
Route::get('/journaldepense', 'DepenseController@journal');
Route::get('/fermerdepense', 'DepenseController@fermer');
Route::get('/alldepense', 'DepenseController@index')->name('alldepense');
Route::get('/depenses', 'DepenseController@liste')->name('depenses');
Route::get('/showdepense-{id}', 'DepenseController@show');
Route::get('/deletedepense-{id}-{sold_id}', 'DepenseController@destroy');
Route::post('/ajouterdepense', 'DepenseController@store')->name('store_depense');
Route::post('/updatedepense', 'DepenseController@update')->name('update_depense');
Route::get('/add-depot', 'DepenseController@create_depot')->name('add_depot');
Route::post('/add-depot', 'DepenseController@store_depot')->name('store_depot');
Route::get('/depense-files-{id}', 'DepenseController@create_depense_file')->name('create_depense_file');
Route::post('/depense-files', 'DepenseController@store_depense')->name('store_depense_file');
Route::get('/depense-file-delete-{id}', 'DepenseController@destroy_file')->name('delete_depense_file');

Route::get('/projets', 'ProjetController@liste')->name('projets');
Route::post('/ajoutprojet', 'ProjetController@store')->name('store_projet');
Route::post('/updateprojet', 'ProjetController@update')->name('update_projet');
Route::get('/allprojet', 'ProjetController@index')->name('allprojet');
Route::get('/projet-models-{id}', 'ProjetController@list_projet_models')->name('list_projet_models');
Route::get('/showdeprojet-{id}', 'ProjetController@show');
Route::get('/deleteprojet-{id}', 'ProjetController@destroy');
Route::get('/allprojet-model-{id}', 'ProjetController@index_model')->name('allprojet_model');
Route::get('/add-modele-projet-{id}', 'ProjetController@create_projet_modele')->name('create_projet_modele');
Route::post('/store_projet_modeles-{id}', 'ProjetController@store_projet_modeles')->name('store_projet_modeles');


Route::get('/resultat', 'ResultatsController@resultat')->name('resultat');
Route::get('/resultatsuper', 'ResultatsController@resultatsuper')->name('resultatsuper');
Route::get('/resultatjr', 'ResultatsController@resultatjr');
Route::get('/tableau-{id}', 'ResultatsController@tableau');
Route::get('/exemple-{id}', 'ResultatsController@exemple');
Route::get('/tableaujr-{id}', 'ResultatsController@tableaujr');
Route::get('/tableaumois-{id}-{ed}', 'ResultatsController@tableaumois');
Route::get('/resultatsuperjr', 'ResultatsController@resultatsuperjr');

Route::get('/tableausuper-{id}', 'ResultatsController@tableausuper');
Route::get('/exemplesuper-{id}', 'ResultatsController@exemplesuper');
Route::get('/tableausuperjr-{id}', 'ResultatsController@tableausuperjr');
Route::get('/tableausupermois-{id}-{ed}', 'ResultatsController@tableausupermois');


Route::get('/alllivraisonNew', 'LivraisonsController@indexNew');
Route::get('/deletelivraisonNew-{id}', 'LivraisonsController@destroyNew');
Route::post('/storelivraisonNew', 'LivraisonsController@storeNew');
Route::post('/updatelivraisonNew', 'LivraisonsController@updateNew');
Route::get('/showlivraison-{id}', 'LivraisonsController@showNew');
Route::get('/verificationNew-{id}', 'LivraisonsController@verificationNew');
Route::get('/detaillivraisonNew-{id}', 'LivraisonsController@showNew1');


Route::get('/verouillage-{id}', 'UserController@verrouillage');

Route::get('/sets', 'SettingController@index');
Route::post('/export-db', 'SettingController@export_db');

Route::get('/transferts', 'TransfertsController@liste')->name('transferts');
Route::get('/modeles/transfert/{id}', 'TransfertsController@get_modele_stock')->name('get_modele_stock');
Route::get('/recuperertransfert', 'TransfertsController@transfert');
Route::get('/recupererreception', 'TransfertsController@reception');
Route::get('/alltransfert', 'TransfertsController@indexTransfert');
Route::get('/allreception', 'TransfertsController@indexReception');
Route::get('/deletetransfert-{id}', 'TransfertsController@destroy');
Route::post('/ajouttransfert', 'TransfertsController@store');
Route::post('/updatetransfert', 'TransfertsController@update');
Route::get('/showtransfert-{id}', 'TransfertsController@show');
Route::get('/showtransfertreception-{id}', 'TransfertsController@indexUpdate');
Route::get('/recuperermodeleboutique-{famille}', 'TransfertsController@showBoutiqueProduit');



/// Banque routes.
Route::get('/banques','BanqueController@list_banques')->name('banques');
Route::get('/agences','BanqueController@listagences')->name('agences');
Route::get('/comptes','BanqueController@list_comptes')->name('comptes');

Route::post('/agences','BanqueController@create_agence');
Route::post('/banques','BanqueController@create_banque');
Route::post('/comptessaved','BanqueController@create_compte');
Route::get('/showbanquedetail-{id}', 'BanqueController@showdetail');

Route::get('/deletecompte-{id}', 'BanqueController@destroycompte');
Route::get('/showcompte-{id}', 'BanqueController@showdetailcompt');
Route::post('/updatecompte', 'BanqueController@update_compte');
Route::get('/showcomptedetail-{id}', 'BanqueController@showdetailcompte');

Route::post('/updatebanque', 'BanqueController@update_banque');

Route::get('/deletebanque-{id}', 'BanqueController@destroy');

Route::get('/showbanque-{id}', 'BanqueController@show');
Route::get('/get_account','BanqueController@get_account_for_bank');
Route::get('/get_solde','BanqueController@get_solde');
Route::get('/situationsBoutiques','SituationController@liste');

Route::get('/caisses','CaisseController@liste')->name('caisses');

Route::get('/addBulling', 'CaisseController@addBullingshow')->name('addBullingshow');
Route::post('/storecaisse', 'CaisseController@storeCaisseCollect');
Route::post('/storeBilling', 'CaisseController@store');


Route::get('/listeglobal','CaisseController@listeglobal')->name('listeglobal');

Route::get('/showdetailcaisse','CaisseController@showdetailcaisse')->name('showdetailcaisse');

Route::get('/UPDATEVERSEMENT-{id}','CaisseController@UPDATEVERSEMENT')->name('UPDATEVERSEMENT');
Route::post('/editversement/{id}','CaisseController@editversement')->name('editversement');


Route::get('/justificatifversement-{id}','CaisseController@justificatifversement')->name('justificatifversement');

Route::get('/allversements','CaisseController@versements')->name('allversements');

Route::get('/depenseversem-files-{id}', 'CaisseController@create_depense_file')->name('depenseversem_file');
Route::post('/depenseversem-files', 'CaisseController@store_depense')->name('store_depensedepenseversem_file');
Route::get('/depense-file-delete-{id}', 'CaisseController@destroy_file')->name('delete_depense_file');
Route::get('/versements', 'CaisseController@index')->name('versements');
Route::get('/validationversement', 'CaisseController@indexVALIDATION')->name('validationversement');

Route::get('/depotversement', 'CaisseController@create_depot')->name('depotversement');
Route::post('/add-depotversement', 'CaisseController@store_depot')->name('store_depotversement');



Route::get('/chargeversem-files-{id}', 'ChargesController@create_depense_file')->name('chargeversem');
Route::post('/charge-files', 'ChargesController@store_depense')->name('chargefiles');


Route::get('/inventaire/pdf', 'InventairesController@print_inventory')->name('inventory_global');

Route::get('/decharges', 'FournisseursController@decharge_liste')->name('decharges');
Route::post('/decharges/store', 'FournisseursController@save_decharge')->name('decharges_store');
Route::delete('/decharges/delete/{id}', 'FournisseursController@delete_decharge')->name('decharges_delete');
Route::put('/decharges/edit/{id}', 'FournisseursController@edit_decharge')->name('decharges_edit');
Route::get('/decharges/generate', 'FournisseursController@generate_decharge')->name('decharges.generate');
Route::get('/livraison/bon/{id}', 'VentesController@bon_de_livraison')->name('bon_de_livraison');

Route::get('/getLivraisonsByProduit', 'VentesController@getLivraisonsByProduit');

Route::get('/livraison/shop', 'LivraisonsController@livraisonsShop')->name('livraisons.shop');
Route::get('/livraison/bon/commande/{id}', 'LivraisonsController@livraison_bon_commande')->name('livraisons.bon_commande');
Route::get('/livraison/shop/{id}', 'LivraisonsController@show_livraison')->name('show_livraison');
Route::get('/livraison/detail/{id}', 'LivraisonsController@detail_livraison')->name('detail_livraison');
Route::get('/modelelivraisons-{id}', 'LivraisonsController@show_modele_livraison')->name('show_modele_livraison');

Route::get('/delivered-vente/{id}', 'VentesController@delivered_vente')->name('delivered_vente');

Route::post('/save-avoir', 'ClientsController@save_avoir')->name('save_avoir');
Route::get('/client/avoir/{id}', 'ClientsController@client_avoir_liste')->name('avoir_liste');

Route::get('/historiques/vente', 'VentesController@sales_data')->name('historique_vente');
