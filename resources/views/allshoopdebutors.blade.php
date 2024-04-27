
@extends('layout')
@section('css')
    <link rel="stylesheet" href="octopus/assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
@endsection
@section('contenu')
    <div class="inner-wrapper">
        <section role="main" class="content-body">
            <header class="page-header">
                <h2>Créances</h2>
            </header>

            <div class="row">
                <section class="panel">
                    <header class="panel-heading">
                        <div class="panel-actions">
                            <a href="#" class="fa fa-caret-down"></a>
                        </div>

                        <h1 class="panel-title">LISTES DES CRÉANCES</h1>
                    </header>

                    <div class="panel-body">
                        <div class="tabs">

                            <div class="tab-content">

                                    <div class="row">
                                    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-group">
                                            <h3 class="">Total: <strong id="" class="">{{ $total[0]->solde }}</strong></h3>
                                            <h3> Clients Débiteurs  <!--<a class="modal-with-form btn btn-default mb-xs mt-xs mr-xs btn btn-primary" id="btnclient"><i class="fa fa-plus"></i></a>-->
                                            </h3>
                                        </div>
                                    </div>
                                    </div>
                                <br>
                                    <table class="table table-bordered table-striped mb-none" id="alldebiteurTable" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
                                        <thead>
                                        <tr>
                                            <th class="center hidden-phone">Magasin</th>
                                            <th class="center hidden-phone">Nom</th>

                                            <th class="center hidden-phone">Contact / Adresse</th>
                                            <th class="center hidden-phone">Montant</th>
                                        </tr>
                                        </thead>
                                        <tbody class="center hidden-phone">
{{--                                            // @if (isset($reglements) && count($reglements) > 0)
 --}}                                          
 
                                            @foreach ($clients as $index => $item)
                                                <tr style="color: {{$item->solde > 0 ? 'red' : 'black'}}">
                                                    <td>{{ $item->boutique }}</td>
                                                    <td>{{ $item->nom }}</td>
                                                    <td>{{ $item->contact }} / {{ $item->adresse }}</td>
                                                    {{--
                                                    <td>{{ $item->numero }}</td>
                                                    <td class="prix">{{ $item->totaux }}</td> --}}
                                                    <td class="prix" >{{ $item->solde}}</td>
                                                    <td>
                                                        <a class="btn btn-info" href="/reglementlist-{{ $item->id }}"> <i class="fa fa-arrow-right"></i>
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


@endsection
@section('js')
    <script src="octopus/assets/vendor/jquery/jquery.js"></script>
    <script src="octopus/assets/vendor/bootstrap/js/bootstrap.js"></script>
    <script src="octopus/assets/vendor/nanoscroller/nanoscroller.js"></script>
    <script src="octopus/assets/vendor/jquery-datatables/media/js/jquery.dataTables.js"></script>
    <script src="octopus/assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
    <script src="octopus/assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>

        <script>
                var alldebiteurTable;



    /* $(function () {
        alldebiteurTable =   $('#alldebiteurTable').DataTable({
            processing: true,
            serverSide: true,
            'paging': true,
            'lengthChange': true,
            'searching': true,
            'ordering': true,
            'info': true,
            'autoWidth': true,
            language: {
                "sProcessing": "Traitement en cours...",
                "sSearch": "Rechercher&nbsp;:",
                "sLengthMenu": "Afficher _MENU_ &eacute;l&eacute;ments",
                "sInfo": "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
                "sInfoEmpty": "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
                "sInfoFiltered": "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
                "sInfoPostFix": "",
                "sLoadingRecords": "Chargement en cours...",
                "sPrint": "Imprimer",
                "sZeroRecords": "Aucun &eacute;l&eacute;ment &agrave; afficher",
                "sEmptyTable": "Aucune donn&eacute;e disponible dans le tableau",
                "oPaginate": {
                    "sFirst": "Premier",
                    "sPrevious": "Pr&eacute;c&eacute;dent",
                    "sNext": "Suivant",
                    "sLast": "Dernier"
                },
                "oAria": {
                    "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                    "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
                },
                "select": {
                    "rows": {
                        _: "%d lignes séléctionnées",
                        0: "Aucune ligne séléctionnée",
                        1: "1 ligne séléctionnée"
                    }
                }
            },
            ajax: '/alldebiteurs',
            "columns": [
                {data: "boutique",name : 'boutique'},

                {data: "nom",name : 'nom'},
                {data: "contact",name: 'contact'},
                {data: "adresse",name: 'adresse'},
                {data :  "solde",name : 'solde'},
                {data :  "action",name : 'action'},

            ]
        });
    }); */
        </script>

    <script>

        function setNumeralHtml(element, format, surfix="")
        {
            var prices = $("."+element);

            for(var i=0; i<prices.length; i++)
            {
                var number = numeral(prices[i].innerText);

                var string = number.format(format);
                prices[i].innerText = string+" "+surfix;
            }

        }

        setNumeralHtml("prix", "0,0", "");
    </script>
@endsection



