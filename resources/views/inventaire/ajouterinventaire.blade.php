<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Inventaire</title>
    <link rel="stylesheet" href="{{ asset('octopus/assets/vendor/bootstrap/css/bootstrap.css') }}" />
</head>
<body>
    <div class="wrapper">
        <!-- Main content -->
        <section class="invoice">
           <form method="POST" action="{{ route('nom_de_la_route') }}">
            @csrf
        
            <label for="choix">Choisir une option :</label>
            <select id="choix" name="choix">
                <option value="toutes_categories">Toutes les catégories</option>
                <option value="par_categorie">Par catégorie</option>
            </select>
        
            <div id="categorie_selection" style="display: none;">
                <label for="categorie">Sélectionner une catégorie :</label>
                <select id="categorie" name="categorie">
                    <option value="quincaillerie">Quincaillerie</option>
                    <option value="boutique">Boutique</option>
                </select>
            </div>
        
            <label for="date">Date du jour :</label>
            <input type="date" id="date" name="date" value="{{ date('Y-m-d') }}" readonly>
        
            <button type="submit">Enregistrer l'inventaire</button>
        </form>
        
        <script>
            document.getElementById('choix').addEventListener('change', function() {
                const categorieSelection = document.getElementById('categorie_selection');
                const dateInput = document.getElementById('date');
        
                if (this.value === 'par_categorie') {
                    categorieSelection.style.display = 'block';
                    dateInput.readOnly = false;
                } else {
                    categorieSelection.style.display = 'none';
                    dateInput.readOnly = true;
                }
            });
        </script>

        </section>
    </div>
    <script src="octopus/assets/vendor/jquery/jquery.js"></script>
    <script src="octopus/assets/vendor/bootstrap/js/bootstrap.js"></script>
</body>
</html>