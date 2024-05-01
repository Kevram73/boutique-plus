@extends('layout')
@section('css')
    <link rel="stylesheet" href="octopus/assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
@endsection
@section('contenu')
    <div class="inner-wrapper">
        <section role="main" class="content-body">
            <header class="page-header">
                <h2>Produit</h2>
            </header>

            <div class="row">
                <section class="panel">
                    <header class="panel-heading">
                        <div class="panel-actions">
                            <a href="#" class="fa fa-caret-down"></a>
                        </div>

                        <h1 class="panel-title">LISTES DES  PRODUITS</h1>
                    </header>

                    <div class="panel-body">
                        <a class="modal-with-form btn btn-default mb-xs mt-xs mr-xs btn btn-default" id="btnproduit"><i class="fa fa-plus"></i>Ajouter un produit</a>
                        <a class="modal-with-form btn btn-default mb-xs mt-xs mr-xs btn btn-default" href="/modeles-reporting" ><i class="fa fa-area-chart"></i>Rapport</a>
                        <div class="modal fade " id="ajout_produit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">

                                    <div class="modal-header " style="background-color: #0b93d5;border-top-left-radius: inherit;border-top-right-radius: inherit">
                                        <h4 class="modal-title-user" id="myModalLabel" style="color: white"></h4>
                                    </div>
                                    <div class="modal-body">
                                        <form id="form" action="" method="POST" class="	form-validate form-horizontal mb-lg" enctype="multipart/form-data">
                                            {{csrf_field()}}
                                            <div class="form-group mt-lg">
                                                <label class="col-sm-3 control-label">Categorie</label>
                                                <div class="col-sm-9">
                                                    <select  name="categorie" id="categorie"   class="form-control populate">
                                                        <optgroup label="Choisir une categorie">
                                                            <option value=""></option>
                                                            @foreach($categorie as $cate)
                                                                <option value="{{$cate->id}}">{{$cate->nom}}</option>
                                                            @endforeach
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group mt-lg">
                                                <label class="col-sm-3 control-label">Famille</label>
                                                <div class="col-sm-9">
                                                    <select  name="famille" id="famille"  class="form-control populate">
                                                        <optgroup label="Choisir une famille">

                                                        </optgroup>
                                                    </select>
                                                </div>modeles
                                            </div>
                                            <div class="form-group mt-lg">
                                                Rapport           <label class="col-sm-3 control-label">Modele</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="modele"  id="modele" class="form-control" placeholder="400g" required/>
                                                    <input type="hidden" name="idmodele" id="idmodele"/>

                                                </div>
                                            </div>
                                            <div class="form-group mt-lg">
                                                <label class="col-sm-3 control-label">Quantité</label>
                                                <div class="col-sm-9">
                                                    <input type="integer" name="quantite" id="quantite" class="form-control" placeholder="100" required/>
                                                </div>
                                            </div>
                                            <div class="form-group mt-lg">
                                                <label class="col-sm-3 control-label">Prix (de détail)</label>
                                                <div class="col-sm-9">
                                                    <input type="integer" name="prix" id="prix" class="form-control" placeholder="100" required/>
                                                </div>
                                                Rapport</div>
                                            <div class="form-group mt-lg">
                                                <label class="col-sm-3 control-label">Prix (de gros)</label>
                                                <div class="col-sm-9">
                                                    <input type="integer" name="prixDeGros" id="prixDeGros" class="form-control" placeholder="100" required/>
                                                </div>
                                            </div>
                                            <div class="form-group mt-lg">
                                                <label class="col-sm-3 control-label">Prix d'achat</label>
                                                <div class="col-sm-9">
                                                    <input type="integer" name="prixAchat" id="prixAchat" class="form-control" placeholder="100" />
                                                </div>
                                            </div>
                                            <div class="form-group mt-lg">
                                                <label class="col-sm-3 control-label">Quantité seuil</label>
                                                <div class="col-sm-9">
                                                    <input type="integer" name="seuil" id="seuil" class="form-control" placeholder="100" required/>
                                                </div>
                                            </div>
                                            <div class="form-group mt-lg">
                                                <label class="col-sm-3 control-label">Conditionnement</label>
                                                <div class="col-sm-9">
                                                    <input type="integer" name="condi_modele" id="condi_modele" class="form-control" placeholder="100" required/>
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
                        <table class="table table-bordered table-striped mb-none" id="produitTable" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
                            <thead>
                            <tr>
                                <th class="center hidden-phone">Nom</th>
                                <th class="center hidden-phone">Modele</th>
                                <th class="center hidden-phone">Conditionnement</th>
                                <th class="center hidden-phone">Quantité </th>
                                <th class="center hidden-phone">Prix unitaire (détail)</th>
                                <th class="center hidden-phone">Prix unitaire (de gros)</th>
                                <th class="center hidden-phone">Prix d'achat (dernier)</th>
                                <th class="center hidden-phone">Quantité seuil</th>
                                <th class="center hidden-phone">Action</th>
                            </tr>
                            </thead>
                            <tbody class="center hidden-phone">


                            </tbody>
                        </table>
                    </div>
            </div>
        </section>
    </div>

    <div class="modal fade" id="detailproduit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#0b97c4;border-top-left-radius: inherit;border-top-right-radius: inherit">
                    <b>	<h4 class="modal-title" id="modal-user-title" align="center" style="color: white" ></h4></b>

                </div>
                <div class="modal-body">
                    <ul class="list-group">
                        <li class="list-group-item">Numero:<b> <span class="text-danger" id="sNum"></span> </b></li>
                        {{-- <li class="list-group-item">Categorie :<b> <span class="text-danger" id="sCategorie"></span> </b></li> --}}
                        <li class="list-group-item">Nom :<b> <span class="text-danger" id="sNom"></span> </b></li>
                        <li class="list-group-item">Modele :<b> <span class="text-danger" id="sModele"></span> </b></li>
                        <li class="list-group-item">Quantité :<b> <span class="text-danger" id="sQuantite"></span> </b></li>
                        <li class="list-group-item">Prix (de détail) :<b> <span class="text-danger" id="sPrix"></span> </b></li>
                        <li class="list-group-item">Prix (de gros) :<b> <span class="text-danger" id="sPrixDeGros"></span> </b></li>
                        <li class="list-group-item">Prix d'achat (dernier) :<b> <span class="text-danger" id="sPrixAchat"></span> </b></li>
                        <li class="list-group-item">Quantité seuil :<b> <span class="text-danger " id="sSeuil" ></span></b></li>
                        <li class="list-group-item">Conditionnement :<b> <span class="text-danger " id="sCondi" ></span></b></li>

                        <li class="list-group-item">
                            crée le :<b> <span class="text-danger" id="sCreate"></span></b> </li>

                        <li class="list-group-item">
                            mise a jour le :<b> <span class="text-danger" id="sUpdate"></span></b> </li>
                    </ul>

                    <table class="table table-bordered table-striped mb-none" id="venteTable" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
                        <thead>
                        <tr>
                            <th class="center hidden-phone">Utilisateur</th>
                            <th class="center hidden-phone">Date </th>
                            <th class="center hidden-phone">Quantité</th>
                            <th class="center hidden-phone">Montant</th>
                            <th class="center hidden-phone">Vente</th>
                        </tr>
                        </thead>
                        <tbody class="center hidden-phone">


                        </tbody>
                    </table>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Fermer</button>
                    </div>
                </div>

            </div>

        </div>

        <div class="modal fade" id="seuilModal" tabindex="-2" role="dialog" aria-labelledby="myModalLabellll">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color:#c4740b;border-top-left-radius: inherit;border-top-right-radius: inherit">
                        <b>	<h4 class="modal-title" id="modal-user-title" align="center" style="color: white" >QUANTITE SEUIL DEPASSEE : PASSEZ LA COMMANDE</h4></b>

                    </div>
                    <div class="modal-body">

                        <table class="table table-bordered table-striped mb-none" id="seuilTable" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
                            <thead>
                            <tr>
                                <th class="center hidden-phone">Produit</th>
                                <th class="center hidden-phone">Désignation</th>
                                <th class="center hidden-phone">Quantité</th>
                                <th class="center hidden-phone">Seuil</th>
                                <th class="center hidden-phone">Prix</th>
                            </tr>
                            </thead>
                            <tbody class="center hidden-phone">


                            </tbody>
                        </table>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                            <a href="/provisions" class="btn btn-primary" >Commander</a>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection
@section('js')

    <script src="octopus/assets/vendor/jquery/jquery.js"></script>
    <script src="octopus/assets/vendor/bootstrap/js/bootstrap.js"></script>
    <script src="octopus/assets/vendor/jquery-datatables/media/js/jquery.dataTables.js"></script>
    <script src="octopus/assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
    <script src="octopus/assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
    <script src="js/modele.js"></script>
    <script src="js/seuil.js"></script>

@endsection
