@extends('layout')
@section('css')
    <link rel="stylesheet" href="octopus/assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
@endsection
@section('contenu')
    <!-- Modal to add banques -->
    @include('banque.modals.modal_add_banque')
    <!-- Modal to add agences banques -->
    <div class="inner-wrapper">
        <section role="main" class="content-body">
            <header class="page-header">
                <h2>BANQUES</h2>
            </header>

            <div class="row">
                <section class="panel"> 
                    <header class="panel-heading">
                        <div class="panel-actions">
                            <a href="#" class="fa fa-caret-down"></a>
                        </div>

                        <h1 class="panel-title">LISTES DES  BANQUES</h1>
                    </header>
 
                    <div class="panel-body">

                        <a class="btn btn-default mb-xs mt-xs mr-xs btn btn-outline-light"
                           id="btnaddBanq" onclick="addBanque()"
                           data-bs-toggle="modal"
                           data-bs-target="#idAddBanqueModal">Ajouter Banque</a>
                       
                        @include('banque.bank_list_table')
                    </div>
                </section>
            </div>
        </section>
    </div>

    <div class="modal fade" id="detailprovision" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#0b97c4;border-top-left-radius: inherit;border-top-right-radius: inherit">
                    <b>	<h4 class="modal-title" id="modal-user-title" align="center" style="color: white" ></h4></b>

                </div>
                <div class="modal-body">




                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Fermer</button>
                    </div>
                </div>

            </div>

        </div>

    </div>
@endsection
@section('js')

<script>
    import Index from "../../../public/octopus/assets/vendor/flot/examples/zooming/index.html";
    export default {
        components: {Index}
    }
</script>
    <script src="octopus/assets/vendor/bootstrap/js/bootstrap.js"></script>
    <script src="octopus/assets/vendor/nanoscroller/nanoscroller.js"></script>
    <script src="octopus/assets/vendor/jquery-datatables/media/js/jquery.dataTables.js"></script>
    <script src="octopus/assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
    <script src="octopus/assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
    <script src="octopus/assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
    <script src="js/banques.js"></script>

    <script>
             console.log('recherche');
        $(document).ready(function(){
            $('#provisionTable').DataTable({
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
            $('#provisionTable').DataTable();
            } );
                var modal = $('.Recherche');
                $('.logo').click(function() {
                    modal.show();
                });
    </script>
@endsection
