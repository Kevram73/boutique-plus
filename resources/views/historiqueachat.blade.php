@extends('layout')

@section('css')
    <link rel="stylesheet" href="octopus/assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
    <link rel="stylesheet" href="/vendor/select/css/select2.min.css" />
    <style>
        .select2-container {
            width: 300px;
            }
    </style>
@endsection
@section('contenu')
    <div class="inner-wrapper">
        <section role="main" class="content-body">
            <header class="page-header">
                <h2>Historique des achats</h2>
            </header> 

            <div class="row"  id="inventaire">
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label class="col-md-4 control-label">Afficher</label>
                        <div class="col-md-9 form-group">
                            <select  name="choix" id="choix"  class="form-control populate">
                                <optgroup label="Choisir ">
                                    <option value=""></option>
                                    <option value="jour">Par jour</option>
                                    <option value="mois">Par mois</option>
                                    <option value="an">Par année</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 form-group" id="jr" style="display: none">
                        <label class="col-md-4 control-label">Jour</label>
                        <div class="col-md-9 form-group">
                            <select  name="jour" id="jour"  class="form-control populate">
                                <optgroup label="Choisir le jour">
                                    <option value=""></option>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 form-group" id="an" style="display: none">
                        <label class="col-md-4 control-label">Annee</label>
                        <div class="col-md-9 form-group">
                            <select  name="annee" id="annee"  class="form-control populate">
                                <optgroup label="Choisir le mois">
                                    <option value=""></option>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 form-group" id="moi" style="display: none">
                        <label class="col-md-4 control-label">Mois</label>
                        <div class="col-md-9 form-group">
                            <select  name="mois" id="mois"  class="form-control populate">
                                <optgroup label="Choisir le mois">
                                    <option value=""></option>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 form-group"  id="depense" style="display: none " >
                        <label class="col-md-4 control-label">Dépense</label>
                        <div class="col-sm-9">
                            <input type="number" name="depenses" id="depenses" class="form-control" readonly/>
                        </div>
                    </div>

                </div>
                <section class="panel">
                    <header class="panel-heading">
                        <div class="panel-actions">
                            <a href="#" class="fa fa-caret-down"></a>
                        </div>

                        <h1 class="panel-title">HISTORIQUE DES COMMANDES</h1>
                    </header>
 
                    <div class="panel-body">

                        <div class="tabs">
                        <ul class="nav nav-tabs nav-justified">
                            <li class="active">
                                <a href="#reglements" data-toggle="tab" class="text-center" style="font-size: 2rem;"><i class="fa fa-star"></i> Historique : Commandes</a>
                            </li>
                            <li>
                                <a href="#debiteurs" data-toggle="tab" class="text-center text-warning" style="font-size: 2rem;">Historique :  Produits / Fournisseurs</a>
                            </li>
                        </ul>
                      <div class="tab-content">

                        <div id="reglements" class="tab-pane active">
                            <table class="table table-bordered table-striped mb-none" id="achatTable">
                                <thead>
                                <tr>
                                    <th class="center hidden-phone">Commande</th>
                                    <th class="center hidden-phone">Montant</th>
                                    <th class="center hidden-phone">Date et heure</th>
                                    {{-- <th class="center hidden-phone">Utilisateur</th> --}}
                                    <th class="center hidden-phone">Action</th>
                                </tr>
                                </thead>
                                <tbody class="center hidden-phone">
    
    
                                </tbody>
                            </table>
                        </div>

                        <div id="debiteurs" class="tab-pane">
                            <div class="row">
                             
                                <div class="">
                                    <section class="panel">
                                        <div class="panel-body">
                                            <div class="row">

                                                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                    <div class="form-group">
                                                        <label for="fournisseur">Fournisseurs</label>
                                                        <div class="form-group">
                                                        <select id="fournisseur" class="form-control">
                                                            <option value="0" >  </option>
                                                           @foreach ($fournisseurs as $fournisseur)
                                                           <option value="{{ $fournisseur->id }}"> {{ $fournisseur->nom }} </option>
                                                           @endforeach
                                                        </select>   
                                                        </div>                                                 </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                    <div class="form-group">
                                                        <label for="product">Produits</label>
                                                        <div class="form-group">
                                                        <select id="product" class="form-control" style="min-width:110px;">
                                                            <option value="0">   </option>
                                                            
                                                            @foreach ($produits as $produit)
                                                            <option value="{{ $produit->id }}"> {{ $produit->libelle }}  </option>
                                                            @endforeach
                                                        </select>                         
                                                        </div>   
                                                                            </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                <div class="form-group">
                                                    <label for="debut">Date Début</label>
                                                    <input type="date" class="form-control" id="debut" placeholder="Date Début">
                                                </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                <div class="form-group">
                                                    <label for="fin">Date Fin</label>
                                                    <input type="date" class="form-control" id="fin" placeholder="Date Fin">
                                                </div></div>
                                                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6"><br>
                                                <div class="form-group text-left">
                                                    <button id="reset" class="btn btn-default">Annuler</button>
                                                    <button id="voir" class="btn btn-primary">Voir</button>
                                                </div></div>
                                            </div>
                                            <br>
                                            
                                            <div class="row">
                                                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                    <div class="form-group">
                                                        <label for="qteTotal">Quantité Totale</label>
                                                        <input type="text" class="form-control" id="qteTotal" placeholder="Quantité Total" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                    <div class="form-group">
                                                        <label for="montantTotal">Montant Total</label>
                                                        <input type="text" class="form-control prix" id="montantTotal" placeholder="Montant Total" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <table class="table table-bordered table-striped mb-none" id="achatFourniTable" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
                                                <thead>
                                                <tr>
                                                    <th class="center hidden-phone">Dates </th>
                                                    <th class="center hidden-phone">Fournisseurs</th>
                                                    <th class="center hidden-phone">Produits </th>
                                                    <th class="center hidden-phone">Quantité</th>
                                                    <th class="center hidden-phone">Prix Unit </th>
                                                    <th class="center hidden-phone">Montant</th>
                                                </tr>
                                                </thead>
                                                <tbody class="center hidden-phone">
                
                
                                                </tbody>
                                            </table>
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>

                      </div>
                        </div>

                    </div>
                </section>
            </div>
        </section>
    </div>
@endsection
@section('js')

<script src="octopus/assets/vendor/jquery/jquery.js"></script>
<script src="octopus/assets/vendor/bootstrap/js/bootstrap.js"></script>
<script src="octopus/assets/vendor/nanoscroller/nanoscroller.js"></script>
<script src="octopus/assets/vendor/jquery-datatables/media/js/jquery.dataTables.js"></script>
 <script src="octopus/assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
 
 <script src="octopus/assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
<script src="/vendor/select/js/select2.full.min.js"></script>

    <script src="js/historiqueachat.js"></script>

@endsection
