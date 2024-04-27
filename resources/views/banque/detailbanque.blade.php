@extends('layout')
@section('css')
    <link rel="stylesheet" href="octopus/assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
@endsection
@section('contenu')
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

                        <h1 class="panel-title">LISTES DES  COMPTES</h1>
                    </header>
                    <div class="panel-body">
                        <div class="col-md-18">
                            <div class="tabs">
                              
                                <div class="tab-content">
                                    <div id="categorie" class="tab-pane active">
                                     
                                        <table class="table table-bordered table-striped mb-none" id="categorieTable" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
                                            <thead>
                                            <tr>
                                                <th class="center hidden-phone">Nom Banque</th>
                                                <th class="center hidden-phone">Description</th>
                                                <th class="center hidden-phone">Contact</th>
                                                <th class="center hidden-phone">Numéro</th>
                                                <th class="center hidden-phone">Type compte</th>
                                            </tr>
                                            </thead>
                                            <tbody class="center hidden-phone">
                                                    @foreach ($categorie as $categori)
                                                    <tr>
                                                        <th class="center hidden-phone">
                                                            {{$categori->nom}}
                                                        </th> 
                                                        <th class="center hidden-phone">
                                                            {{$categori->description}}
                                                        </th> 
                                                        <th class="center hidden-phone">
                                                            {{$categori->numero}}
                                                        </th> 
                                                        <th class="center hidden-phone">
                                                            {{$categori->contact}}
                                                        </th> 
                                                        <th class="center hidden-phone">
                                                            {{$categori->type}}
                                                        </th> 
                                                    </tr>
                                                    @endforeach

                                            </tbody>
                                        </table>
                                     
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
   {{--  <script src="js/banques.js"></script> --}}
 

    <script>
        console.log('recherche');
   $(document).ready(function(){
       $('#categorieTable').DataTable({
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
       $('#categorieTable').DataTable();
       } );
           var modal = $('.Recherche');
           $('.logo').click(function() {
               modal.show();
           });
</script>
@endsection
