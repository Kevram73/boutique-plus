@extends('layout')
@section('css')
    <link rel="stylesheet" href="octopus/assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
@endsection
@section('contenu')
    <div class="inner-wrapper">
        <section role="main" class="content-body">
            <header class="page-header">
                <h2>Caisse</h2>
            </header>

            <div class="row">
                <section class="panel">
                    <header class="panel-heading">
                        <div class="panel-actions">
                            <a href="#" class="fa fa-caret-down"></a>
                        </div>

                        <h1 class="panel-title">Caisse Global</h1>
                     </header>

                    <div class="panel-body">
                        <div class="tabs">
                            <ul class="nav nav-tabs nav-justified">

                                <li class="active">
                                    <a href="#sommedebiteurs" data-toggle="tab" class="text-center text-warning" style="font-size: 2rem;">Caisse Global Détaillée</a>
                                </li>
                                 <li >
                                    <a href="#reglements" data-toggle="tab" class="text-center" style="font-size: 2rem;"><i class="fa fa-star"></i> Caisse Global</a>
                                </li>
                                <li >
                                    <a href="#versements" data-toggle="tab" class="text-center" style="font-size: 2rem;"><i class="fa fa-star"></i> Montants Collectés – Versements</a>
                                </li>
                                </ul>
                            <div class="tab-content">
                                <div id="sommedebiteurs" class="tab-pane active">
                                    {{--                                   <a class="modal-with-form btn btn-default mb-xs mt-xs mr-xs btn btn-default" id="btnreglement" href="/showdetailcaisse"><i class="fa fa-plus"></i>Faire une caisse</a>

                                 <a  class="modal-with-form btn btn-default mb-xs mt-xs mr-xs btn btn-default"  href="/addBulling">Ajouter Billetage </a>
                                    --}}
                                   <div class="table-responsive">
                                           <table class="table table-bordered table-striped mb-none" id="detaillecaissenTable"  data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
                                               <thead>
                                                   <tr>
                                                       <th class="center hidden-phone">Date</th>
                                                       <th class="center hidden-phone">Boutique</th>
                                                        <th class="center hidden-phone">Vente</th>
                                                       <th class="center hidden-phone">Remise</th>
                                                       <th class="center hidden-phone">Vente Nette</th>
                                                       <th class="center hidden-phone">Créance</th>
                                                       <th class="center hidden-phone">Recouvrement Intérieur</th>
                                                       <th class="center hidden-phone">Avoir</th>
                                                       <th class="center hidden-phone">Avance
                                                           Client</th>

                                                           <th class="center hidden-phone">DEPENSE
                                                               Totale</th>
                                                        <th class="center hidden-phone">Recette
                                                           Totale</th>
                                                                   <th class="center hidden-phone">Solde Veille </th>

                                                                   <th class="center hidden-phone">Montant collecté </th>
                                                                   <th class="center hidden-phone">SOLDE MAGASIN
                                                                   </th>
                                                   </tr>
                                                   </thead>
                                                   <tbody class="center hidden-phone">
                                                       @foreach ($caissere as $globa)
                                                       <tr>
                                                           <td>{{ $globa->date }} </td>
                                                           <td>{{ $globa->boutique }} </td>
                                                       <td>{{ $globa->totalVente }} </td>
                                                       <td>{{ $globa->remise }} </td>
                                                       <td>{{ $globa->ventenette }} </td>
                                                       <td>{{ $globa->venteCredit }}  </td>
                                                       <td>{{ $globa->recouvrementInte }} </td>
                                                       <td>{{ $globa->avoir }} </td>
                                                           <td>{{ $globa->ventenonlivre }}  </td>
                                                           <td>{{ $globa->totalDepense }} </td>
                                                           <td> {{ $globa->recetteTotal }} </td>

                                                         <td>{{ $globa->solde }} </td>
                                                       <td>{{ $globa->montantcollecte }} </td>
                                                       <td>{{ $globa->soldeMagasin }} </td>
                                                       </tr>
                                                       @endforeach


                                               </tbody>

                                           </table>

                                   </div>
                                </div>
                                <div id="reglements" class="tab-pane">
                                     {{--                                   <a class="modal-with-form btn btn-default mb-xs mt-xs mr-xs btn btn-default" id="btnreglement" href="/showdetailcaisse"><i class="fa fa-plus"></i>Faire une caisse</a>

                                  <a  class="modal-with-form btn btn-default mb-xs mt-xs mr-xs btn btn-default"  href="/addBulling">Ajouter Billetage </a>
                                     --}}
                                    <div class="table-responsive">
                                            <table class="table table-bordered table-striped mb-none" id="situationTable"  data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
                                                <thead>
                                                    <tr>
                                                        <th class="center hidden-phone">Date</th>
                                                                    <th class="center hidden-phone">Vente</th>
                                                        <th class="center hidden-phone">Remise</th>
                                                        <th class="center hidden-phone">Vente Nette</th>
                                                        <th class="center hidden-phone">Créance</th>
                                                        <th class="center hidden-phone">Recouvrement Intérieur</th>
                                                        <th class="center hidden-phone">Avoir</th>
                                                        <th class="center hidden-phone">Avance
                                                            Client</th>  <th class="center hidden-phone">DEPENSE
                                                                Totale</th>
                                                        <th class="center hidden-phone">Recette
                                                            Totale</th>


                                                                    <th class="center hidden-phone">Solde Veille </th>

                                                                    <th class="center hidden-phone">Montant collecté </th>
                                                                    <th class="center hidden-phone">SOLDE MAGASIN
                                                                    </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody class="center hidden-phone">
                                                        @foreach ($caisse as $globa)
                                                        <tr>
                                                            <td>{{ $globa->date }} </td>
                                                        <td>{{ $globa->totalVente }} </td>
                                                        <td>{{ $globa->remise }} </td>
                                                        <td>{{ $globa->ventenette }} </td>
                                                        <td>{{ $globa->venteCredit }}  </td>
                                                        <td>{{ $globa->recouvrementInte }} </td>
                                                        <td>{{ $globa->avoir }} </td>
                                                            <td>{{ $globa->ventenonlivre }}  </td>
                                                            <td>{{ $globa->totalDepense }} </td>
                                                            <td> {{ $globa->recetteTotal }} </td>

                                                        <td>{{ $globa->solde }} </td>
                                                        <td>{{ $globa->total_montantcollecte }} </td>
                                                        <td>{{ $globa->soldeMagasin }} </td>
                                                        </tr>
                                                        @endforeach


                                                </tbody>

                                            </table>

                                    </div>
                                 </div>
                                  <div id="versements" class="tab-pane">
                                    <div class="row">
                                                <div class="col-md-4">

                                                    <h4 >Montant Collecté : <strong class="prix" id="sold">{{ $collecteTotal }}</strong></h4>

                                                </div>
                                                <div class="col-md-4 text-center">
                                                    <h4 >Montant Versé : <strong class="prix" id="sold">{{ $versementTotal }}</strong></h4>


                                                 </div>
                                                <div class="col-md-4 text-center">
                                                   <h4 >Montant à Verser : <strong class="prix" id="sold">{{ $lastReste }}</strong></h4>


                                                </div>
                                            </div>
                                     {{--                                   <a class="modal-with-form btn btn-default mb-xs mt-xs mr-xs btn btn-default" id="btnreglement" href="/showdetailcaisse"><i class="fa fa-plus"></i>Faire une caisse</a>

                                  <a  class="modal-with-form btn btn-default mb-xs mt-xs mr-xs btn btn-default"  href="/addBulling">Ajouter Billetage </a>
                                     --}}
                                    <div class="table-responsive">
                                    <table class="table table-bordered table-striped mb-none" id="versementTable"  data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
                                        <thead>
                                            <tr>
                                                <th class="center hidden-phone">Date</th>
                                                <th class="center hidden-phone">Solde Veille</th>
                                                <th class="center hidden-phone">Montant Collecté</th>
                                                <th class="center hidden-phone">Montant Versé</th>
                                                <th class="center hidden-phone">Reste</th>
                                            </tr>
                                        </thead>
                                        <tbody class="center hidden-phone">
                                            @foreach($collecteVers as $clv)
                                            <tr>
                                                    <td>{{ $clv->created_at->format('Y-m-d') }}</td>
                                                    <td>{{ number_format($clv->veille, 2, ",", ".") }}</td>
                                                    <td>{{ number_format($clv->montantCollecte, 2, ",", ".") }}</td>
                                                    <td>{{ number_format($clv->montantVerse, 2, ",", ".") }}</td>
                                                    <td>{{ number_format($clv->reste, 2, ",", ".") }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>




                                 </div>
                            </div>
                        </div>


                    </div>
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
    <script type="text/javascript" src="/vendor/daterangepicker/moment.min.js"></script>
    <script type="text/javascript" src="/vendor/daterangepicker/daterangepicker.js"></script>
    <script>
        $(function() {
            console.log('bien');
            $('input[name="date"]').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                minYear: 1901,
                maxYear: parseInt(moment().format('YYYY'),10)
            }, function(start, end, label) {
                var years = moment().diff(start, 'years');
            });
        });
    </script>
    <script src="js/caisse.js"></script>
    <script>


        $(document).ready(function(){
            $('#detaillecaissenTable').DataTable({
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
            $('#detaillecaissenTable').DataTable();
            } );
                var modal = $('.Recherche');
                $('.logo').click(function() {
                    modal.show();
                });
    </script>
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

     <script>

        $(document).ready(function(){
            $('#versementTable').DataTable({
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
            $('#versementTable').DataTable();
            } );
                var modal = $('.Recherche');
                $('.logo').click(function() {
                    modal.show();
                });
    </script>


      <script>

        $(document).ready(function(){
            $('#montantVersementTable').DataTable({
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
            $('#montantVersementTable').DataTable();
            } );
                var modal = $('.Recherche');
                $('.logo').click(function() {
                    modal.show();
                });
    </script>
@endsection
