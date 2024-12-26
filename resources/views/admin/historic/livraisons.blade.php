@extends('layout')
@section('contenu')
<div class="inner-wrapper">
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>Historique des livraisons</h2>
        </header>

        <div class="row" id="historique-livraisons">
            <div class="row">
                <!-- Filtre Boutique -->
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

            <!-- Table des livraisons -->
            <section class="panel">
                <header class="panel-heading">
                    <h1 class="panel-title">LISTES DES LIVRAISONS</h1>
                </header>

                <div class="panel-body">
                    <table class="table table-bordered table-striped mb-none" id="livraisonTable">
                        <thead>
                            <tr>
                                <th class="center hidden-phone">Numéro</th>
                                <th class="center hidden-phone">Date de Livraison</th>
                                <th class="center hidden-phone">Boutique</th>
                                <th class="center hidden-phone">Statut</th>
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
    function fetchDeliveryData() {
        const boutique = $('#boutique').val();
        const date_deb = $('#date_deb').val();
        const date_fin = $('#date_fin').val();

        // Ajouter un indicateur de chargement
        $('#livraisonTable tbody').html('<tr><td colspan="4" class="text-center">Chargement...</td></tr>');

        $.ajax({
            url: '{{ route("historique_livraisons") }}', // Définir cette route côté backend
            method: 'GET',
            data: { boutique, date_deb, date_fin },
            success: function(data) {
                $('#livraisonTable tbody').empty();

                if (data.livraisons.length > 0) {
                    data.livraisons.forEach(function(livraison) {
                        $('#livraisonTable tbody').append(`
                            <tr>
                                <td>${livraison.numero}</td>
                                <td>${livraison.date_livraison}</td>
                                <td>${livraison.boutique}</td>
                                <td>${livraison.status}</td>
                            </tr>
                        `);
                    });
                } else {
                    $('#livraisonTable tbody').append('<tr><td colspan="4" class="text-center">Aucune livraison trouvée</td></tr>');
                }
            },
            error: function() {
                $('#livraisonTable tbody').html('<tr><td colspan="4" class="text-center text-danger">Erreur lors de la récupération des données</td></tr>');
            }
        });
    }

    // Déclenche la requête AJAX lorsqu'un filtre change
    $('#boutique, #date_deb, #date_fin').change(fetchDeliveryData);
});
</script>
@endsection
