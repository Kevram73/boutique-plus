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
    // Fonction pour récupérer les données via AJAX
    function fetchExpenseData() {
        const boutique = $('#boutique').val();
        const date_deb = $('#date_deb').val();
        const date_fin = $('#date_fin').val();

        // Ajouter un indicateur de chargement
        $('#depenseTable tbody').html('<tr><td colspan="7" class="text-center">Chargement...</td></tr>');

        // Effectuer une requête AJAX
        $.ajax({
            url: '{{ route("historic_fetch") }}', // Route définie côté backend
            method: 'POST',
            data: { boutique, date_deb, date_fin, type: 'depenses' },
            success: function(data) {
                // Vider le tableau avant d'insérer les nouvelles données
                $('#depenseTable tbody').empty();

                if (data.depenses && data.depenses.length > 0) {
                    // Insérer les données dans le tableau
                    data.depenses.forEach(function(depense) {
                        $('#depenseTable tbody').append(`
                            <tr>
                                <td>${depense.name}</td>
                                <td>${depense.montant}</td>
                                <td>${depense.motif}</td>
                                <td>${depense.boutique_id}</td>
                                <td>${depense.user_id}</td>
                                <td>${depense.date_dep}</td>
                                <td>${depense.justifier ? 'Oui' : 'Non'}</td>
                            </tr>
                        `);
                    });
                } else {
                    // Afficher un message si aucune donnée n'est trouvée
                    $('#depenseTable tbody').append('<tr><td colspan="7" class="text-center">Aucune dépense trouvée</td></tr>');
                }
            },
            error: function(xhr) {
                // Afficher un message d'erreur en cas de problème
                $('#depenseTable tbody').html(`<tr><td colspan="7" class="text-center text-danger">Erreur lors de la récupération des données (${xhr.status}: ${xhr.statusText})</td></tr>`);
            }
        });
    }

    // Gestionnaire d'événements pour les champs de filtre
    $('#boutique, #date_deb, #date_fin').on('change', fetchExpenseData);

    // Charger les données par défaut au chargement de la page
    fetchExpenseData();
});
</script>
@endsection
