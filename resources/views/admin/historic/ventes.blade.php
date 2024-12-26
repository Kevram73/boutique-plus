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
                <div class="col-md-3 form-group">
                    <label for="boutique" class="control-label">Boutique</label>
                    <select name="boutique" id="boutique" class="form-control populate">
                        <option value="">Choisissez votre boutique</option>
                        <option value="0">Toutes les boutiques</option>
                        @foreach($shops as $boutique)
                            <option value="{{ $boutique->id }}">{{ $boutique->nom }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtre Date de début -->
                <div class="col-md-3 form-group">
                    <label for="date_deb" class="control-label">Date de début</label>
                    <input type="date" class="form-control" name="date_deb" id="date_deb" />
                </div>

                <!-- Filtre Date de fin -->
                <div class="col-md-3 form-group">
                    <label for="date_fin" class="control-label">Date de fin</label>
                    <input type="date" class="form-control" name="date_fin" id="date_fin" />
                </div>

                <!-- Recherche -->
                <div class="col-md-3 form-group">
                    <label for="search" class="control-label">Recherche</label>
                    <input type="text" class="form-control" name="search" id="search" placeholder="Rechercher..." />
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
                    <!-- Pagination -->
                    <div class="pagination-wrapper mt-3">
                        <ul class="pagination justify-content-center" id="pagination">
                            <!-- Pagination dynamique insérée ici -->
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

    function fetchSalesData(page = 1) {
        const boutique = $('#boutique').val();
        const date_deb = $('#date_deb').val();
        const date_fin = $('#date_fin').val();
        const search = $('#search').val(); // Recherche par texte

        $('#venteTable tbody').html('<tr><td colspan="9" class="text-center">Chargement...</td></tr>');

        $.ajax({
            url: '{{ route("historic_sales_fetch") }}', // Route côté backend
            method: 'GET',
            data: {
                boutique,
                date_deb,
                date_fin,
                search, // Inclure le critère de recherche
                page // Numéro de page
            },
            success: function(data) {
                const tbody = $('#venteTable tbody');
                tbody.empty();

                if (data.data.length > 0) {
                    data.data.forEach(function(vente) {
                        tbody.append(`
                            <tr>
                                <td>${vente.numero}</td>
                                <td>${vente.client_name}</td>
                                <td>${vente.date_vente}</td>
                                <td>${vente.boutique_name}</td>
                                <td>${vente.reduction}</td>
                                <td>${vente.totaux}</td>
                                <td><a href="${vente.facture}" target="_blank">Voir</a></td>
                                <td>${vente.type_vente}</td>
                                <td>${vente.user_nom} ${vente.user_prenom}</td>
                            </tr>
                        `);
                    });
                } else {
                    tbody.append('<tr><td colspan="9" class="text-center">Aucune vente trouvée</td></tr>');
                }

                updatePagination(data);
            },
            error: function() {
                $('#venteTable tbody').html('<tr><td colspan="9" class="text-center text-danger">Erreur lors de la récupération des données</td></tr>');
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
            fetchSalesData(page);
        }
    });

    $('#boutique, #date_deb, #date_fin, #search').on('change keyup', function() {
        fetchSalesData(1);
    });

    fetchSalesData();
});
</script>
@endsection
