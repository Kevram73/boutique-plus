@extends('layout')
@section('css')
    <link rel="stylesheet" href="{{ asset('octopus/assets/vendor/jquery-datatables-bs3/assets/css/datatables.css') }}" />
@endsection
@section('contenu')
    <div class="inner-wrapper">
        <section role="main" class="content-body">
            <header class="page-header">
                <h2>Livraisons</h2>
            </header>

            <div class="row">
                <section class="panel">
                    <header class="panel-heading">
                        <div class="panel-actions">
                            <a href="#" class="fa fa-caret-down"></a>
                        </div>

                        <h1 class="panel-title">LISTES DES LIVRAISONS </h1>
                    </header>

                    <div class="panel-body">

                        <table class="table table-bordered table-striped mb-none" id="employeTable" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
                            <thead>
                            <tr>
                                <th class="center hidden-phone">Numero</th>
                                <th class="center hidden-phone">Date de livraison</th>
                                <th class="center hidden-phone">Quantité livrée</th>
                                <th class="center hidden-phone">Quantité vendue</th>
                                <th class="center hidden-phone">Statut</th>
                            </tr>
                            </thead>
                            <tbody class="center hidden-phone">
                                @if(count($livraisons) == 0)
                                    <tr>
                                        <td colspan="8">Aucune livraison pour le moment</td>
                                    </tr>
                                @endif
                                @foreach($livraisons as $livraison)
                                    <tr>
                                        <td class="center hidden-phone">{{ $livraison->numero }}</td>
                                        <td class="center hidden-phone">{{ $livraison->date_livraison }}</td>
                                        <td class="center hidden-phone">{{ $livraison->qte_liv() }}</td>
                                        <td class="center hidden-phone">{{ $livraison->qte_sell() }}</td>
                                        <td class="center hidden-phone">{{ $livraison->statut() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
            </div>
        </section>
    </div>
    </section>
    </div>


@endsection
@section('js')

    <script src="{{ asset('octopus/assets/vendor/jquery/jquery.js') }}"></script>
    <script src="{{ asset('octopus/assets/vendor/bootstrap/js/bootstrap.js') }}"></script>
    <script src="{{ asset('octopus/assets/vendor/nanoscroller/nanoscroller.js') }}"></script>
    <script src="{{ asset('octopus/assets/vendor/jquery-datatables/media/js/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('octopus/assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js') }}"></script>
    <script src="{{ asset('octopus/assets/vendor/jquery-datatables-bs3/assets/js/datatables.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#employeTable').DataTable({
                "paging": true // Activer la pagination
            });
        });
    </script>
@endsection
