@extends('layout')
@section('css')
    <link rel="stylesheet" href="octopus/assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
    <style>
        .hideIds tr td:first-of-type {
            visibility: hidden;
            width: 0px !important;
            padding: 0 !important;
            position: absolute;
        }
    </style>
@endsection
@section('contenu')
    <div class="inner-wrapper">
        <section role="main" class="content-body">
            <header class="page-header">
                <h2>Transfert</h2>
            </header>

            <div class="row">
                <section class="panel">
                    <header class="panel-heading">
                        <div class="panel-actions">
                            <a href="#" class="fa fa-caret-down"></a>
                        </div>

                        <h1 class="panel-title">LISTES DES  TRANSFERTS</h1>
                    </header>
                    <div class="panel-body">
                        <div class="col-md-18">
                            <div class="tabs">
                                <ul class="nav nav-tabs nav-justified">
                                    <li class="active">
                                        <a href="#transfert" data-toggle="tab" class="text-center"><i class="fa fa-send"></i> TRANSFERT DE PRODUIT</a>
                                    </li>
                                    <li>
                                        <a href="#reception" data-toggle="tab" class="text-center"><i class="fa fa-envelope"></i> RECEPTION DE PRODUIT TRANSFÉRÉS</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div id="transfert" class="tab-pane active">
                                        <a class="modal-with-form btn btn-default mb-xs mt-xs mr-xs btn btn-default" id="btntransfert"><i class="fa fa-plus"></i>Faire un transfert</a>
                                        <div class="modal fade " id="ajout_transfert" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">

                                                    <div class="modal-header " style="background-color: #0b93d5;border-top-left-radius: inherit;border-top-right-radius: inherit">
                                                        <h4 class="modal-title-user" id="myModalLabel" style="color: white"></h4>
                                                    </div>
                                                    <div class="modal-body" id="produitTransfertForm">
                                                        <form method="POST" class="	 form-validate form-horizontal" enctype="multipart/form-data">
                                                        <input type="hidden" name="produitTransfertData" id="produitTransfertData"/>
                                                        <input type="hidden" name="idmagasin" id="idmagasin"/>
                                                            {{csrf_field()}}


                                                            <div class="form-group mt-lg">
                                                                <label class="col-sm-3 control-label">Magasin de réception</label>
                                                                <div class="col-sm-9">
                                                                    <select  name="magasin" id="magasin"   class="form-control populate">
                                                                        <optgroup label="Choisir une categorie">
                                                                            <option value=""></option>
                                                                            @foreach($magasins as $magasin)
                                                                                <option value="{{$magasin->id}}">{{$magasin->nom}}</option>
                                                                            @endforeach
                                                                        </optgroup>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <br>
                                                            <div style="border-bottom: 1px solid gray"></div>
                                                            <div class="form-group mt-lg">
                                                                <label class="col-sm-3 control-label">Categorie produit</label>
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
                                                                <label class="col-sm-3 control-label">Famille produit</label>
                                                                <div class="col-sm-9">
                                                                    <select  name="famille" id="famille"  class="form-control populate">
                                                                        <optgroup label="Choisir une famille">
                                                                        </optgroup>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group mt-lg">
                                                                <label class="col-sm-3 control-label">Produit</label>
                                                                <div class="col-sm-9">
                                                                    <select  name="modele" id="modele"  class="form-control populate">
                                                                        <optgroup label="Choisir un produit">
                                                                        </optgroup>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group mt-lg">
                                                                <label class="col-sm-3 control-label">Livraison</label>
                                                                <div class="col-sm-9">
                                                                    <select name="livraison" id="livraison" class="form-control">

                                                                    </select>
                                                                    <input type="hidden" value="{{ Auth::user()->boutique_id }}" id="boutique" name="boutique"/>
                                                                </div>
                                                            </div>
                                                            <div class="form-group mt-lg">
                                                                <label class="col-sm-3 control-label">Quantité à transférer</label>
                                                                <div class="col-sm-9">
                                                                    <input type="integer" name="quantite" id="quantite" class="form-control" placeholder="100" required/>
                                                                </div>
                                                            </div>
                                                            <div style="display: flex; justify-content: flex-end;">
                                                            <button type="button" class="btn btn-info" id="btnselect"><i class="fa fa-plus"></i> Ajouter</button>
                                                            </div>
                                                            <br>

                                                            <table class="table table-bordered table-striped mb-none" id="produitTransfertTable" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
                                                                <thead>
                                                                <tr>
                                                                    <th class="center hidden-phone" style="display: table-column;"></th>
                                                                    <th class="center hidden-phone">Libellé produit</th>
                                                                    <th class="center hidden-phone">Qte transfert</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody class="center hidden-phone hideIds">
                                                                </tbody>
                                                            </table>

                                                            <br>
                                                            <div class="modal-footer">
                                                                <div class="col-md-12 text-right">
                                                                    <button type="button" class="btn btn-primary" id="btnadd"><i class="fa fa-check"></i> Valider</button>
                                                                    <button type="button" class="mb-xs mt-xs mr-xs btn btn-default" id="btnclosereception" data-dismiss="modal"><i class="fa fa-times"></i> Annuler</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <table class="table table-bordered table-striped mb-none" id="transfertTable" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
                                            <thead>
                                            <tr>
                                                <th class="center hidden-phone">Code</th>
                                                <th class="center hidden-phone">Magasin de réception</th>
                                                <th class="center hidden-phone">Date</th>
                                                <th class="center hidden-phone">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody class="center hidden-phone">


                                            </tbody>
                                        </table>
                                        <div class="modal fade" id="detailtransfert" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header" style="background-color:#0b97c4;border-top-left-radius: inherit;border-top-right-radius: inherit">
                                                        <b>	<h4 class="modal-title" id="modal-user-title" align="center" style="color: white" ></h4></b>

                                                    </div>
                                                    <div class="modal-body">
                                                        <ul class="list-group">
                                                            <li class="list-group-item">Magasin de réception :<b> <span class="text-primary" id="tmagasin"></span> </b></li>
                                                            <li class="list-group-item">crée le :<b> <span class="text-primary" id="tdate"></span></b> </li>
                                                            <li class="list-group-item">Statut transfert :<b> <span class="" id="tstatut"></span></b> </li>
                                                        </ul>

                                                            <br>
                                                            <div style="border-bottom: 1px solid gray"></div>
                                                            <br>
                                                            <h5 style="margin-bottom: 25px;">Liste des produits</h5>
                                                            <table class="table table-bordered table-striped mb-none" id="tproduitTransfertTable" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
                                                                <thead>
                                                                <tr>
                                                                    <th class="center hidden-phone" style="display: table-column;"></th>
                                                                    <th class="center hidden-phone">Libellé produit</th>
                                                                    <th class="center hidden-phone">Quantité transferé</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody class="center hidden-phone hideIds">
                                                                </tbody>
                                                            </table>


                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-primary" data-dismiss="modal">Fermer</button>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>
                                    <div id="reception" class="tab-pane">
                                    <div class="modal fade " id="ajout_reception" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">

                                                    <div class="modal-header " style="background-color: #0b93d5;border-top-left-radius: inherit;border-top-right-radius: inherit">
                                                        <b><h4 class="modal-title" id="modal-title-reception" align="center" style="color: white"></h4></b>
                                                    </div>
                                                    <div class="modal-body" id="produitReceptiontForm">
                                                        <form method="POST" class="	 form-validate form-horizontal" enctype="multipart/form-data">
                                                        <input type="hidden" name="produitReceptiontData" id="produitReceptiontData"/>
                                                        <input type="hidden" name="idtransfert" id="idtransfert" content="{{ csrf_token() }}"/>


                                                            <ul class="list-group">
                                                                <li class="list-group-item">Magasin de transfert :<b> <span class="text-primary" id="r2magasin"></span> </b></li>
                                                                <li class="list-group-item">crée le :<b> <span class="text-primary" id="r2date"></span></b> </li>
                                                                <li class="list-group-item">Statut transfert :<b> <span class="" id="r2statut"></span></b> </li>
                                                            </ul>

                                                            <br>

                                                            <table class="table table-bordered table-striped mb-none" id="produitReceptionTable" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
                                                                <thead>
                                                                <tr>
                                                                    <th class="center hidden-phone" style="display: table-column;"></th>
                                                                    <th class="center hidden-phone">Libellé produit</th>
                                                                    <th class="center hidden-phone">Quantité à recevoir</th>
                                                                    <th class="center hidden-phone">Correspondance</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody class="center hidden-phone hideIds">
                                                                </tbody>
                                                            </table>

                                                            <br>
                                                            <div class="modal-footer">
                                                                <div class="col-md-12 text-right">
                                                                    <button type="button" class="btn btn-primary" id="btnupdate"><i class="fa fa-check"></i> Valider</button>
                                                                    <button type="button" class="mb-xs mt-xs mr-xs btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Annuler</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <table class="table table-bordered table-striped mb-none" id="receptionTable" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
                                            <thead>
                                            <tr>
                                                <th class="center hidden-phone">Code</th>
                                                <th class="center hidden-phone">Magasin de transfert</th>
                                                <th class="center hidden-phone">Date</th>
                                                <th class="center hidden-phone">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody class="center hidden-phone">


                                            </tbody>
                                        </table>
                                        <div class="modal fade" id="detailreception" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header" style="background-color:#0b97c4;border-top-left-radius: inherit;border-top-right-radius: inherit">
                                                        <b>	<h4 class="modal-title" id="modal-user-title-reception" align="center" style="color: white" ></h4></b>

                                                    </div>
                                                    <div class="modal-body">
                                                        <ul class="list-group">
                                                            <li class="list-group-item">Magasin de transfert :<b> <span class="text-primary" id="rmagasin"></span> </b></li>
                                                            <li class="list-group-item">crée le :<b> <span class="text-primary" id="rdate"></span></b> </li>
                                                            <li class="list-group-item">Statut transfert :<b> <span class="" id="rstatut"></span></b> </li>
                                                        </ul>

                                                            <br>
                                                            <div style="border-bottom: 1px solid gray"></div>
                                                            <br>
                                                            <h5 style="margin-bottom: 25px;">Liste des produits</h5>
                                                            <table class="table table-bordered table-striped mb-none" id="tproduitReceptionTable" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
                                                                <thead>
                                                                <tr>
                                                                    <th class="center hidden-phone" style="display: table-column;"></th>
                                                                    <th class="center hidden-phone">Libellé produit</th>
                                                                    <th class="center hidden-phone" id="titlerecevoir">Quantité à recevoir</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody class="center hidden-phone hideIds">
                                                                </tbody>
                                                            </table>


                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-primary" data-dismiss="modal">Fermer</button>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>

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
    <script src="js/transfert.js"></script>
    <script>
        var my_tab = [];
           $('#modele').on('change', function() {
                var modele = $(this).val();
                var boutique_id = $('#boutique').val();

                if (modele) {
                    // Réinitialisation du champ de sélection avant la requête
                    $('#livraison').empty().append('<option value="">Chargement...</option>'); // Option par défaut pendant le chargement

                    $.ajax({
                        url: '/getLivraisonsByProduit',
                        type: 'GET',
                        data: { modele_id: modele, boutique_id: boutique_id },
                        success: function(response) {
                            var options = '<option value="">Choisissez une livraison</option>'; // Valeur par défaut
                            response.livraisons.forEach(function(livraison) {
                                my_tab = response.livraisons
                                options += '<option value="' + livraison.numero + '">' + livraison.numero + ' *** ' + 'Qte rest: ' + livraison.quantite_restante + '</option>';
                            });

                            $('#livraison').html(options); // Remplir avec les nouvelles options
                        },
                        error: function(xhr, status, error) {
                            console.error("Erreur lors du chargement des livraisons :", error);
                            // Réinitialiser le champ de sélection en cas d'erreur
                            $('#livraison').empty().append('<option value="">Erreur de chargement</option>');
                        }
                    });
                } else {
                    // Réinitialisation si aucun modèle n'est sélectionné
                    $('#livraison').empty().append('<option value="">Choisissez une livraison</option>');
                }
            });

            function getQuantiteRestante(numero){
                for(var i = 0; i < my_tab.length; i++){
                    if(my_tab[i].numero === numero){
                        return my_tab[i].quantite_restante;
                    }
                }
                return null;
            }

            $('#livraison').on('change', function(){
                var livraison = $(this).val();

                if(livraison){
                    quantite = getQuantiteRestante(livraison);
                    var quantiteInput = document.getElementById("quantite");
                    quantiteInput.setAttribute("max", quantite);
                }
            })

            $('#quantite').on('change', function() {
        var enteredQuantity = parseInt($(this).val());
        var maxQuantity = parseInt($(this).attr("max"));

        if (enteredQuantity > maxQuantity) {
            alert("Quantité entrée supérieure à la quantité maximale autorisée.");
            $(this).val(0);
            $('#prixQte').val(0);
            // You can also reset the input value here if needed
        }
    });

    </script>

@endsection
