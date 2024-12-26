@extends('layout')
@section('contenu')
<div class="inner-wrapper">
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>Historique des dépenses</h2>
        </header>

        <div class="row" id="historique-depenses">
            <div class="row">
                <!-- Filtre Boutique -->
                <div class="col-md-4 form-group">
                    <label class="col-md-4 control-label">Boutique</label>
                    <div class="col-md-9 form-group">
                        <select name="boutique" id="boutique" class="form-control populate">
                            <option>Choisissez votre boutique</option>
                            <option value="0">Toutes les boutiques</option>
                            @foreach($shops as $bo)
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

            <!-- Table des dépenses -->
            <section class="panel">
                <header class="panel-heading">
                    <h1 class="panel-title">LISTES DES DÉPENSES</h1>
                </header>

                <div class="panel-body">
                    <table class="table table-bordered table-striped mb-none" id="depenseTable">
                        <thead>
                            <tr>
                                <th class="center hidden-phone">Nom</th>
                                <th class="center hidden-phone">Montant</th>
                                <th class="center hidden-phone">Motif</th>
                                <th class="center hidden-phone">Boutique</th>
                                <th class="center hidden-phone">Utilisateur</th>
                                <th class="center hidden-phone">Date</th>
                                <th class="center hidden-phone">Justifié?</th>
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
    function fetchExpenseData() {
        const boutique = $('#boutique').val();
        const date_deb = $('#date_deb').val();
        const date_fin = $('#date_fin').val();

        // Ajouter un indicateur de chargement
        $('#depenseTable tbody').html('<tr><td colspan="7" class="text-center">Chargement...</td></tr>');

        $.ajax({
            url: '{{ route("historique_depenses") }}', // Définir cette route côté backend
            method: 'GET',
            data: { boutique, date_deb, date_fin },
            success: function(data) {
                $('#depenseTable tbody').empty();

                if (data.depenses.length > 0) {
                    data.depenses.forEach(function(depense) {
                        $('#depenseTable tbody').append(`
                            <tr>
                                <td>${depense.nom}</td>
                                <td>${depense.montant}</td>
                                <td>${depense.motif}</td>
                                <td>${depense.boutique}</td>
                                <td>${depense.utilisateur}</td>
                                <td>${depense.date}</td>
                                <td>${depense.justifie ? 'Oui' : 'Non'}</td>
                            </tr>
                        `);
                    });
                } else {
                    $('#depenseTable tbody').append('<tr><td colspan="7" class="text-center">Aucune dépense trouvée</td></tr>');
                }
            },
            error: function() {
                $('#depenseTable tbody').html('<tr><td colspan="7" class="text-center text-danger">Erreur lors de la récupération des données</td></tr>');
            }
        });
    }

    // Déclenche la requête AJAX lorsqu'un filtre change
    $('#boutique, #date_deb, #date_fin').change(fetchExpenseData);
});
</script>
@endsection
