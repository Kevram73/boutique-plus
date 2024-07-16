@extends('layout')
@section('contenu')
<div class="inner-wrapper">
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>Historique des ventes</h2>
        </header>

        <div class="row" id="inventaire">
            <div class="row">
                <div class="col-md-4 form-group">
                    <label class="col-md-4 control-label">Boutique</label>
                    <div class="col-md-9 form-group">
                        <select name="boutique" id="boutique" class="form-control populate">
                            <option>Choisissez votre boutique</option>
                            <option value="0">Toutes les boutiques</option>
                            @foreach($boutiques as $boutique)
                                <option value="{{ $boutique->id }}">{{ $boutique->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4 form-group">
                    <label class="col-md-4 control-label">Date de debut</label>
                    <div class="col-md-9 form-group">
                        <input type="date" class="form-control" name="date_deb" id="date_deb" placeholder="Entrez la date de debut" required/>
                    </div>
                </div>

                <div class="col-md-4 form-group">
                    <label class="col-md-4 control-label">Date de fin</label>
                    <div class="col-md-9 form-group">
                        <input type="date" class="form-control" name="date_fin" id="date_fin" placeholder="Entrez la date de fin" required/>
                    </div>
                </div>

                <div class="col-md-4 form-group">
                    <label class="col-md-4 control-label">Total</label>
                    <div class="col-sm-9">
                        <input type="number" name="total" id="total" class="form-control" value="0" readonly/>
                    </div>
                </div>

            </div>
            <section class="panel">
                <header class="panel-heading">
                    <div class="panel-actions">
                        <a href="#" class="fa fa-caret-down"></a>
                    </div>

                    <h1 class="panel-title">LISTES DES VENTES</h1>
                </header>

                <div class="panel-body">
                    <table class="table table-bordered table-striped mb-none" id="achatTable">
                        <thead>
                            <tr>
                                <th class="center hidden-phone">Vente</th>
                                <th class="center hidden-phone">Montant</th>
                                <th class="center hidden-phone">Action</th>
                            </tr>
                        </thead>
                        <tbody class="center hidden-phone">
                            <!-- Data will be populated here by AJAX -->
                        </tbody>
                    </table>
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
$(document).ready(function() {
    function fetchSalesData() {
        var boutique = $('#boutique').val();
        var date_deb = $('#date_deb').val();
        var date_fin = $('#date_fin').val();

        $.ajax({
            url: '{{ route("historique_vente") }}', // Assurez-vous de définir cette route dans votre fichier de routes
            method: 'GET',
            data: {
                boutique: boutique,
                date_deb: date_deb,
                date_fin: date_fin
            },
            success: function(data) {
                $('#achatTable tbody').empty();
                data.forEach(function(sale) {
                    $('#achatTable tbody').append('<tr><td>' + sale.numero + '</td><td>' + sale.totaux + '</td><td>' + sale.action + '</td></tr>');
                });
            }
        });
    }

    $('#boutique, #date_deb, #date_fin').change(fetchSalesData);
});
</script>
@endsection
