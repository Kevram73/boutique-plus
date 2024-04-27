@extends('layout')
@section('css')
    <link rel="stylesheet" href="octopus/assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
@endsection
@section('contenu')
    <div class="inner-wrapper">
        <section role="main" class="content-body">
            <header class="page-header">
                <h2>SITUATION</h2>
            </header>

            <div class="row">
                <section class="panel">
                    <header class="panel-heading">
                        <div class="panel-actions">
                            <a href="#" class="fa fa-caret-down"></a>
                        </div>

                        <h1 class="panel-title">Situations</h1>
                    </header>

                    <div class="panel-body">
                        <div class="table-responsive">
                        
                        <table class="table table-bordered table-striped mb-none" id="situationTable" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
                            <thead>
                            <tr>
                                <th class="center hidden-phone">Nom</th>
                                <th class="center hidden-phone">Adresse</th>
                                <th class="center hidden-phone">telephone </th>
                                <th class="center hidden-phone">VENTES</th>
                                <th class="center hidden-phone">Remise</th>
                                <th class="center hidden-phone">Vente Nette</th>
                                <th class="center hidden-phone">Créance</th>
                                <th class="center hidden-phone">Recouvrement Intérieur</th>
                                <th class="center hidden-phone">Avance
                                    Client</th>
                                <th class="center hidden-phone">Recette
                                    Totale</th>
                                    <th class="center hidden-phone">DEPENSE 
                                        Totale</th>
                                        <th class="center hidden-phone">SOLDE MAGASIN
                                            </th>

                            </tr>
                            </thead>
                            <tbody class="center hidden-phone">
                                <td>{{ $boutiques[0]->nom }} </td>
                                <td>{{ $boutiques[0]->adresse }} </td>
                                 <td>{{ $boutiques[0]->telephone }} </td>
                                  <td>{{ $totalVente }} </td>
                                   <td>{{ $totalReduction }} </td>
                                    <td>{{ $venteNette }} </td> 
                                    <td>{{ $ventescredit }} </td>
                                     <td>{{ $reglementVC }} </td>
                                      <td>{{ $VENTENonL }} </td>
                                <td>{{ $recetteTotal }} </td>
                                <td>{{$TOTALdepense}}</td>
                                <td></td>
                            </tbody>
                        </table>
                        </div>
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
<script>
  
    $(document).ready(function(){
        $('#situationTable').DataTable({
            "order": [[ 0, "desc" ]],
            "pageLength":10,
            "oLanguage": {
                
                "sProcessing":     "Traitement en cours...",
                "sSearch":         "Rechercher&nbsp;:",
                "sLengthMenu":     "Afficher _MENU_ &eacute;l&eacute;ments",
                "sInfo":           "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
                "sInfoEmpty":      "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
                "sInfoFiltered":   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
                "sInfoPostFix":    "",
                "sLoadingRecords": "Chargement en cours...",
                "sZeroRecords":    "Aucun &eacute;l&eacute;ment &agrave; afficher",
                "sEmptyTable":     "Aucune donn&eacute;e disponible dans le tableau",
                "oPaginate": {
                    "sFirst":      "Premier",
                    "sPrevious":   "Pr&eacute;c&eacute;dent",
                    "sNext":       "Suivant",
                    "sLast":       "Dernier"
                },
                
                "oAria": {
                    "sSortAscending":  ": activer pour trier la colonne par ordre croissant",
                    "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
                }
            }
        });
    });


        $(document).ready(function() {
        $('#situationTable').DataTable();
        } );
            var modal = $('.Recherche');
            $('.logo').click(function() {
                modal.show();
            });
</script>
    <script src="octopus/assets/vendor/jquery/jquery.js"></script>
    <script src="octopus/assets/vendor/bootstrap/js/bootstrap.js"></script>
    <script src="octopus/assets/vendor/nanoscroller/nanoscroller.js"></script>
    <script src="octopus/assets/vendor/jquery-datatables/media/js/jquery.dataTables.js"></script>
    <script src="octopus/assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
    <script src="octopus/assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>

@endsection
