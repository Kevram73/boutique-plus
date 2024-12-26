@extends('layout')
@section('contenu')
<div class="inner-wrapper">
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>Historique des ventes</h2>
        </header>

        <div class="row" id="historique-ventes">
            <div class="row">
                <!-- Filtre Boutique -->
                <div class="col-md-4 form-group">
                    <label class="col-md-4 control-label">Boutique</label>
                    <div class="col-md-9 form-group">
                        <select name="boutique" id="boutique" class="form-control populate">
                            <option>Choisissez votre boutique</option>
                            <option value="0">Toutes les boutiques</option>
                            @foreach($shops as $boutique)
                                <option value="{{ $boutique->id }}">{{ $boutique->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Filtre Date de début -->
                <div class="col-md-4 form-group">
                    <label class="col-md-4 control-label">Date de début</label>
                    <div class="col-md-9 form-group">
                        <input type="date" class="form-control" name="date_deb" id="date_deb" required/>
                    </div>
                </div>

                <!-- Filtre Date de fin -->
                <div class="col-md-4 form-group">
                    <label class="col-md-4 control-label">Date de fin</label>
                    <div class="col-md-9 form-group">
                        <input type="date" class="form-control" name="date_fin" id="date_fin" required/>
                    </div>
                </div>
            </div>

            <!-- Table des ventes -->
            <section class="panel">
                <header class="panel-heading">
                    <h1 class="panel-title">LISTES DES VENTES</h1>
                </header>

                <div class="panel-body">
                    <table class="table table-bordered table-striped mb-none" id="venteTable">
                        <thead>
                            <tr>
                                <th class="center hidden-phone">Numéro</th>
                                <th class="center hidden-phone">Client</th>
                                <th class="center hidden-phone">Date de la Vente</th>
                                <th class="center hidden-phone">Boutique</th>
                                <th class="center hidden-phone">Réduction</th>
                                <th class="center hidden-phone">Total</th>
                                <th class="center hidden-phone">Facture</th>
                                <th class="center hidden-phone">Type de Vente</th>
                                <th class="center hidden-phone">Réceptionniste</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Les données seront insérées ici via AJAX -->
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
@endsection

@section('js')
<script>
$(document).ready(function() {
    function fetchSalesData() {
        const boutique = $('#boutique').val();
        const date_deb = $('#date_deb').val();
        const date_fin = $('#date_fin').val();

        // Ajouter un indicateur de chargement
        $('#venteTable tbody').html('<tr><td colspan="9" class="text-center">Chargement...</td></tr>');

        $.ajax({
            url: '', // Définir cette route côté backend
            method: 'GET',
            data: { boutique, date_deb, date_fin },
            success: function(data) {
                $('#venteTable tbody').empty();

                if (data.ventes.length > 0) {
                    data.ventes.forEach(function(vente) {
                        $('#venteTable tbody').append(`
                            <tr>
                                <td>${vente.numero}</td>
                                <td>${vente.client}</td>
                                <td>${vente.date_vente}</td>
                                <td>${vente.boutique}</td>
                                <td>${vente.reduction}</td>
                                <td>${vente.total}</td>
                                <td><a href="${vente.facture}" target="_blank">Voir</a></td>
                                <td>${vente.type_vente}</td>
                                <td>${vente.receptionniste}</td>
                            </tr>
                        `);
                    });
                } else {
                    $('#venteTable tbody').append('<tr><td colspan="9" class="text-center">Aucune vente trouvée</td></tr>');
                }
            },
            error: function() {
                $('#venteTable tbody').html('<tr><td colspan="9" class="text-center text-danger">Erreur lors de la récupération des données</td></tr>');
            }
        });
    }

    // Déclenche la requête AJAX lorsqu'un filtre change
    $('#boutique, #date_deb, #date_fin').change(fetchSalesData);
});
</script>
@endsection
