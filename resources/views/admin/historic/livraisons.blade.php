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
                            <option value="">Choisissez votre boutique</option>
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
                        <input type="date" class="form-control" name="date_deb" id="date_deb" />
                    </div>
                </div>

                <!-- Filtre Date de fin -->
                <div class="col-md-4 form-group">
                    <label class="col-md-4 control-label">Date de fin</label>
                    <div class="col-md-9 form-group">
                        <input type="date" class="form-control" name="date_fin" id="date_fin" />
                    </div>
                </div>

                <!-- Recherche -->
                <div class="col-md-4 form-group">
                    <label class="col-md-4 control-label">Recherche</label>
                    <div class="col-md-9 form-group">
                        <input type="text" class="form-control" id="search" placeholder="Rechercher..." />
                    </div>
                </div>
            </div>

            <!-- Table des livraisons -->
            <section class="panel mt-3">
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
                    <div class="pagination-wrapper mt-3">
                        <ul class="pagination justify-content-center" id="pagination">
                            <!-- Pagination dynamique sera ajoutée ici -->
                        </ul>
                    </div>
                </div>
            </section>
        </div>
@endsection

@section('js')
<script>
$(document).ready(function() {
    let currentPage = 1;

    function fetchDeliveryData(page = 1) {
        const boutique = $('#boutique').val();
        const date_deb = $('#date_deb').val();
        const date_fin = $('#date_fin').val();
        const search = $('#search').val();

        // Ajouter un indicateur de chargement
        $('#livraisonTable tbody').html('<tr><td colspan="4" class="text-center">Chargement...</td></tr>');

        $.ajax({
            url: '{{ route("historic_fetch") }}', // Route côté backend
            method: 'GET',
            data: {
                boutique,
                date_deb,
                date_fin,
                search,
                type: 'livraisons',
                page
            },
            success: function(data) {
                const tbody = $('#livraisonTable tbody');
                tbody.empty();

                if (data.data.length > 0) {
                    data.data.forEach(function(livraison) {
                        tbody.append(`
                            <tr>
                                <td>${livraison.numero}</td>
                                <td>${livraison.date_livraison}</td>
                                <td>${livraison.boutique_name}</td>
                                <td>${livraison.status}</td>
                                <td>${depense.justifier ? 'Terminée' : 'En attente'}</td>
                            </tr>
                        `);
                    });
                } else {
                    tbody.append('<tr><td colspan="4" class="text-center">Aucune livraison trouvée</td></tr>');
                }

                // Mettre à jour la pagination
                updatePagination(data);
            },
            error: function() {
                $('#livraisonTable tbody').html('<tr><td colspan="4" class="text-center text-danger">Erreur lors de la récupération des données</td></tr>');
            }
        });
    }

    function updatePagination(data) {
        const pagination = $('#pagination');
        pagination.empty();

        if (data.total > data.per_page) {
            if (data.prev_page_url) {
                pagination.append(`<li class="page-item"><a class="page-link" href="#" data-page="${data.current_page - 1}">Précédent</a></li>`);
            }

            for (let i = 1; i <= data.last_page; i++) {
                pagination.append(`
                    <li class="page-item ${i === data.current_page ? 'active' : ''}">
                        <a class="page-link" href="#" data-page="${i}">${i}</a>
                    </li>
                `);
            }

            if (data.next_page_url) {
                pagination.append(`<li class="page-item"><a class="page-link" href="#" data-page="${data.current_page + 1}">Suivant</a></li>`);
            }
        }
    }

    $('#pagination').on('click', '.page-link', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        if (page) {
            currentPage = page;
            fetchDeliveryData(page);
        }
    });

    // Rafraîchir les données lorsque les filtres changent
    $('#boutique, #date_deb, #date_fin, #search').on('change keyup', function() {
        fetchDeliveryData(1); // Revenir à la première page lors de modifications
    });

    // Charger les données par défaut
    fetchDeliveryData();
});
</script>
@endsection
