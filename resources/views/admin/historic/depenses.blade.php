@extends('layout')
@section('contenu')
<div class="inner-wrapper">
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>Historique des dépenses</h2>
        </header>

        <div class="row" id="historique-depenses">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Filtres</h6>
                </div>
                <div class="card-body">
                    <form class="row g-3">
                        <!-- Filtre Boutique -->
                        <div class="col-md-4">
                            <label for="boutique" class="form-label">Boutique</label>
                            <select id="boutique" class="form-select">
                                <option value="">Choisissez votre boutique</option>
                                <option value="0">Toutes les boutiques</option>
                                @foreach($shops as $boutique)
                                    <option value="{{ $boutique->id }}">{{ $boutique->nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filtre Date de début -->
                        <div class="col-md-4">
                            <label for="date_deb" class="form-label">Date de début</label>
                            <input type="date" id="date_deb" class="form-control" />
                        </div>

                        <!-- Filtre Date de fin -->
                        <div class="col-md-4">
                            <label for="date_fin" class="form-label">Date de fin</label>
                            <input type="date" id="date_fin" class="form-control" />
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table des dépenses -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Liste des Dépenses</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="depenseTable" width="100%">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Montant</th>
                                    <th>Motif</th>
                                    <th>Boutique</th>
                                    <th>Utilisateur</th>
                                    <th>Date</th>
                                    <th>Justifié ?</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Les données seront insérées ici via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
@endsection

@section('js')
<script>
$(document).ready(function() {
    // Initialisation de DataTable
    const table = $('#depenseTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("historic_fetch") }}',
            method: 'GET',
            data: function(d) {
                d.boutique = $('#boutique').val();
                d.date_deb = $('#date_deb').val();
                d.date_fin = $('#date_fin').val();
                d.type = 'depenses';
            }
        },
        columns: [
            { data: 'name', title: 'Nom' },
            { data: 'montant', title: 'Montant' },
            { data: 'motif', title: 'Motif' },
            { data: 'boutique_name', title: 'Boutique' },
            { data: 'user_nom_prenom', title: 'Utilisateur' },
            { data: 'date_dep', title: 'Date' },
            { data: 'justifier', title: 'Justifié ?', render: function(data) {
                return data ? 'Oui' : 'Non';
            }},
        ],
        order: [[5, 'desc']],
        
    });

    // Rafraîchir la table lorsque les filtres changent
    $('#boutique, #date_deb, #date_fin').on('change', function() {
        table.ajax.reload();
    });
});
</script>
@endsection
