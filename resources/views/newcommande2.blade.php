@extends('layout')
@section('css')
    <link rel="stylesheet" href="octopus/assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
    <link rel="stylesheet" href="/vendor/select/css/select2.min.css" />
@endsection
@section('contenu')
    <div class="inner-wrapper">
        <section role="main" class="content-body">
            <header class="page-header">
                <h2>Commande non livree</h2>
            </header>

            <div class="row"> 
                <section class="panel">
                    <header class="panel-heading">
                        <div class="panel-actions">
                            <a href="#" class="fa fa-caret-down"></a>
                        </div>

                        <h1 class="panel-title">ENREGISTREMENT DE LA COMMANDE INDIRECTE (NON LIVREE)</h1>
                    </header>

                    <div class="panel-body">


                        <div class="row" " >

                                            <div class="col-md-4 form-group"  id="four"  style="display: block">
                                                <label class="col-sm-4 control-label">Fournisseur</label>
                                                <div class="col-md-9 form-group" >
                                                    <select  name="fournisseur" id="fournisseur"   class="form-control populate">
                                                        <optgroup label="Choisir le fournisseur">
                                                            <option value=""></option>
                                                            @foreach($fournisseurs as $fou)
                                                                <option value="{{$fou->id}}">{{$fou->nom}}</option>
                                                            @endforeach
                                                        </optgroup>
                                                    </select>
                                                </div>
                                                <a class="modal-with-form btn btn-default mb-xs mt-xs mr-xs btn btn-default"
                                                   id="btnfournisseur"><i class="fa fa-plus"></i></a>
                                            </div>

                                            <div class="col-md-4 form-group">
                                                <label class="col-md-4 control-label">Categorie</label>
                                                <div class="col-md-9 form-group">
                                                    <select  name="categorie" id="categorie"  class="form-control populate">
                                                        <optgroup label="Choisir la categorie">
                                                            <option value=""></option>
                                                            @foreach($categorie as $cat)
                                                                <option value="{{$cat->id}}">{{$cat->nom}}</option>
                                                            @endforeach
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4 form-group">
                                                <label class="col-sm-4 control-label">Produit</label>
                                                <div class="col-md-9 form-group">
                                                    <select  name="produit" id="produit"  class="form-control populate">
                                                        <optgroup label="Choisir un produit">
                                                            <option value="" ></option>
                                                            @foreach($produits as $cat)
                                                                <option value="{{$cat->id}}">{{$cat->nom}}</option>
                                                            @endforeach
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4 form-group">
                                                <label class="col-sm-4 control-label">Modele</label>
                                                <div class="col-md-9 form-group">
                                                    <select  name="modele" id="modele"   class="form-control populate">
                                                        <optgroup label="Choisir le modele">
                                                            <option value=""></option>
                                                            @foreach($modeles as $cat)
                                                                <option value="{{$cat->id}}">{{$cat->libelle}}</option>
                                                            @endforeach
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4 form-group">
                                                <label class="col-sm-3 control-label">Quantité</label>
                                                <div class="col-sm-9">
                                                    <input type="number" name="quantite"  id="quantite" class="form-control"  min="1" placeholder="100" required/>
                                                    <input type="hidden" name="mod" id="mod"/>
                                                </div>
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <label class="col-sm-3 control-label">Prix</label>
                                                <div class="col-sm-9">
                                                    <input type="number" name="prix"  id="prix" class="form-control" min="1" placeholder="15000" required/>

                                                </div>
                                            </div>
                                        </div>
                                                <div class="col-md-12 text-right">
                                                    <button type="button" class="btn btn-primary" id="ajout"><i class="fa fa-check"></i> Ajouter</button>
                                                    <button type="button" class="mb-xs mt-xs mr-xs btn btn-default  "  id="annuler"><i class="fa fa-times"></i> Annuler</button>
                                                </div>

                                                <div id="comform">
                                                    <form  method="POST" class="form-validate form-horizontal mb-lg" >
                                                        {{csrf_field()}}
                                                        <input type="hidden"  name="comTable" id="comTable">
                                                        <input type="hidden"  name="fournisseur" id="fournisseur_id">
                                     @if (Auth::user()->boutique->settings->where('tag', 'commande_a_credit')->first()
                            && Auth::user()->boutique->settings->where('tag', 'commande_a_credit')->first()->pivot->is_active)
                                                            <div class="row" style="width: 40%">
                                                                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                                                    <label class="form-label d-inline mr-5" for="credit">A CREDIT</label>
                                                                </div>
                                                                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                                                    <input class="form-control d-inline mr-5" checked type="checkbox" name="credit" id="credit">
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </form>
                                                </div>
         
                    <table class="table table-bordered table-striped mb-none" id="commandeTable" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
                        <thead>
                        <tr>
                            <th class="center hidden-phone">Numero</th>
                            <th class="center hidden-phone">Produits</th>
                            <th class="center hidden-phone">Modele</th>
                            <th class="center hidden-phone">Quantité </th>
                            <th class="center hidden-phone">Prix </th>
                            <th class="center hidden-phone">Total </th>
                        </tr>
                        </thead>
                        <tbody class="center hidden-phone">
                        </tbody>
                    </table>
                    <a class="btn btn-danger" id="sup" ><i class="fa fa-trash-o" ></i>Supprimer</a>
                    <div class="col-md-12 text-right">
                        <button type="button" class="btn btn-primary" id="valider"><i class="fa fa-check"></i> Valider</button>
                        <button type="button" class="mb-xs mt-xs mr-xs btn btn-default  "  id="annuler"><i class="fa fa-times"></i> Annuler</button>
                    </div>

                    </div>
            </div>

    <div class="modal fade " id="ajout_fournisseur" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header " style="background-color: #0b93d5;border-top-left-radius: inherit;border-top-right-radius: inherit">
                    <h4 class="modal-title-user" id="myModalLabel" style="color: white"></h4>
                </div>
                <div class="modal-body">
                    <form id="form" action="" method="POST" class="	form-validate form-horizontal mb-lg" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="form-group mt-lg">
                            <label class="col-sm-3 control-label">Nom</label>
                            <div class="col-sm-9">
                                <input type="text" name="nom"  id="nom" class="form-control" placeholder="LJ" required/>
                                <input type="hidden" name="idfournisseur" id="idfournisseur"/>
                            </div>
                        </div>
                        <div class="form-group mt-lg">
                            <label class="col-sm-3 control-label">Adresse</label>
                            <div class="col-sm-9">
                                <input type="text" name="adresse" id="adresse" class="form-control" placeholder="lome" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Email</label>
                            <div class="col-sm-9">
                                <input type="email" name="email" id="email" class="form-control" placeholder="aaaa@aa.com " />
                            </div>
                        </div>
                        <div class="form-group mt-lg">
                            <label class="col-sm-3 control-label">Contact</label>
                            <div class="col-sm-9">
                                <input type="integer" name="contact" id="contact" class="form-control" placeholder="92658797" />
                            </div>
                        </div>
                        <div class="form-group mt-lg">
                            <label class="col-sm-3 control-label">Description</label>
                            <div class="col-sm-9">
                                <input type="text" name="description" id="description" class="form-control" placeholder="......." />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-primary" id="btnadd"><i class="fa fa-check"></i> Valider</button>
                                <button type="button" class="mb-xs mt-xs mr-xs btn btn-default  " data-dismiss="modal"><i class="fa fa-times"></i> Annuler</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
    <script src="js/newcommande2.js"></script>
    <!-- <script src="js/fournisseur.js"></script> -->


@endsection
