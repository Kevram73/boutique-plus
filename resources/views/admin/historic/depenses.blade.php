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
                <div class="col-md-3 form-group">
                    <label for="boutique" class="control-label">Boutique</label>
                    <select name="boutique" id="boutique" class="form-control populate">
                        <option value="0">Choisissez votre boutique</option>
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

                <!-- Recherche par caractères -->
                <div class="col-md-3 form-group">
                    <label for="search" class="control-label">Recherche</label>
                    <input type="text" class="form-control" name="search" id="search" placeholder="Rechercher..." />
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
                    <div class="pagination-wrapper mt-3">
                        <ul class="pagination justify-content-center" id="pagination">
                            <!-- Les boutons de pagination seront insérés ici dynamiquement -->
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

    function fetchExpenseData(page = 1) {
        const boutique = $('#boutique').val();
        const date_deb = $('#date_deb').val();
        const date_fin = $('#date_fin').val();
        const search = $('#search').val(); // Nouvelle variable de recherche

        $('#depenseTable tbody').html('<tr><td colspan="7" class="text-center">Chargement...</td></tr>');

        $.ajax({
            url: '{{ route("historic_fetch") }}',
            method: 'GET',
            data: {
                boutique,
                date_deb,
                date_fin,
                search, // Inclure la recherche dans les données
                type: 'depenses',
                page
            },
            success: function(data) {
                const tbody = $('#depenseTable tbody');
                tbody.empty();

                if (data.data.length > 0) {
                    const formatter = new Intl.NumberFormat('fr-FR', { style: 'decimal', minimumFractionDigits: 2 });
                    
                    data.data.forEach(function(depense) {

                        tbody.append(`
                            <tr>
                                <td>${depense.name}</td>
                                <td>${depense.montant}</td>
                                <td>${depense.motif}</td>
                                <td>${depense.boutique_name}</td>
                                <td>${depense.user_nom} ${depense.user_prenom}</td>
                                <td>${depense.date_dep}</td>
                                <td>${depense.justifier ? 'Oui' : 'Non'}</td>
                            </tr>
                        `);
                    });
                } else {
                    tbody.append('<tr><td colspan="7" class="text-center">Aucune dépense trouvée</td></tr>');
                }

                updatePagination(data);
            },
            error: function(xhr) {
                $('#depenseTable tbody').html(`<tr><td colspan="7" class="text-center text-danger">Erreur lors de la récupération des données (${xhr.status}: ${xhr.statusText})</td></tr>`);
            }
        });
    }

    function updatePagination(data) {
    const pagination = $('#pagination');
    pagination.empty();

    if (data.total > data.per_page) {
        // Bouton "Précédent"
        if (data.prev_page_url) {
            pagination.append(`
                <li class="page-item">
                    <a class="page-link" href="#" data-page="${data.current_page - 1}">Précédent</a>
                </li>
            `);
        } else {
            pagination.append(`
                <li class="page-item disabled">
                    <span class="page-link">Précédent</span>
                </li>
            `);
        }

        // Page actuelle
        pagination.append(`
            <li class="page-item active">
                <span class="page-link">${data.current_page}</span>
            </li>
        `);

        // Bouton "Suivant"
        if (data.next_page_url) {
            pagination.append(`
                <li class="page-item">
                    <a class="page-link" href="#" data-page="${data.current_page + 1}">Suivant</a>
                </li>
            `);
        } else {
            pagination.append(`
                <li class="page-item disabled">
                    <span class="page-link">Suivant</span>
                </li>
            `);
        }
    }
}


    $('#pagination').on('click', '.page-link', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        if (page) {
            currentPage = page;
            fetchExpenseData(page);
        }
    });

    // Rafraîchir les données lorsque les filtres changent ou lors de la saisie dans la recherche
    $('#boutique, #date_deb, #date_fin, #search').on('change keyup', function() {
        fetchExpenseData(1);
    });

    fetchExpenseData();
});

    </script>

@endsection
