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
 @isset($caisse)
                           <h1 class="panel-title">{{$caisse[0]->nom}} - {{$caisse[0]->telephone}} - {{$caisse[0]->adresse}}</h1>

                            @endisset                 </header>

                    <div class="panel-body">
                        <div class="tabs">

                            <div class="tab-content">
                                <div id="reglements" class="tab-pane active">
                                    <a class="modal-with-form btn btn-default mb-xs mt-xs mr-xs btn btn-default" id="btnreglement" ><i class="fa fa-plus"></i>Faire une caisse</a>
                                    <div id="message"></div>

{{--                                     <a  class="modal-with-form btn btn-default mb-xs mt-xs mr-xs btn btn-default"  href="/addBulling">Ajouter Billetage </a>
 --}}                                    <div class="table-responsive">
                                    <table class="table table-bordered table-striped mb-none" id="situationTable"  data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
                                        <thead>
                                            <tr>
                                                <th class="center hidden-phone">Date</th>
                                                <th class="center hidden-phone">Boutique</th>
                                                            <th class="center hidden-phone">Vente</th>
                                                <th class="center hidden-phone">Remise</th>
                                                <th class="center hidden-phone">Vente Nette</th>
                                                <th class="center hidden-phone">Créance</th>
                                                <th class="center hidden-phone">Recouvrement Intérieur</th>
                                                <th class="center hidden-phone">Avance
                                                    Client</th>   <th class="center hidden-phone">DEPENSE 
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
                                                @isset($caisse)
                                                      @foreach ($caisse as $globa)
                                                <tr>
                                                     <td>{{ $globa->date }} </td>
                                                <td>{{ $globa->nom }} </td>
                                                <td>{{ $globa->totalVente }} </td>
                                                 <td>{{ $globa->remise }} </td>
                                                  <td>{{ $globa->ventenette }} </td>
                                                  <td>{{ $globa->venteCredit }}  </td>
                                                   <td>{{ $globa->recouvrementInte }} </td>
                                                     <td>{{ $globa->ventenonlivre }}  </td>
                                                    <td>{{ $globa->totalDepense }} </td> 
                                                      <td> {{ $globa->recetteTotal }} </td>
                                                    
                                                <td>{{ $globa->solde }} </td>
                                                <td>{{ $globa->montantcollecte }} </td>
                                                <td>{{ $globa->soldeMagasin }} </td>
                                                </tr>
                                                @endforeach 
                                                @endisset
                                               
                                           
                                        </tbody>

                                    </table></div>
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
        $('#check-date').click(function() {
            $.ajax({
                url: '/dates',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var today = new Date().toISOString().slice(0, 10);
                    if (data.includes(today)) {
                        $('#message').text('La date d\'aujourd\'hui est dans la table.');
                    } else {
                        $('#message').text('La date d\'aujourd\'hui n\'est pas dans la table.');
                    }
                },
                error: function() {
                    $('#message').text('Erreur lors de la récupération des dates.');
                }
            });
        });
    </script>
   {{--  <script>
            console.log(date);
            $('#btnreglement').click(function() {
                $.ajax({
                    url: '/get-date',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data === true) {
                            let _m = " Votre compte a un solde insufisant" ;
                                            sweetToast('warning',_m) ;
                        }else{
                            window.location='/showdetailcaisse'

                        }
                    },
                    error: function() {
                        $('#message').text('Erreur lors de la récupération des dates.');
                    }
                });
            });
    </script> --}}
    
    
    
    

    <script>
        $(function() {
            console.log('ALLONS');
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

        $('#btnreglement').click(function() {
            var userId = $(this).val();
            $.get('/get-date' ,function(data) {
            var solder = data;
            //alert(solder);
            console.log(solder);
            console.log('vraiment je   plus')
            var donne = $('#donne').val();
            if (solder=== true) {
            //alert('Le montant est supérieur ou égal au solde de l'utilisateur sélectionné.');
            let _m = " Vous avez déjà enrégistrer une caisse dans la journée" ;
                        sweetToast('warning',_m) ;
            }else{
                    window.location='/showdetailcaisse'

                }
                
            });
            });
    </script>
@endsection
