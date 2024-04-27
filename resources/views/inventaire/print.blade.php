<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>INVENTAIRE GLOBAL</title>
<style>
        table {
            width: 100%;
            border-collapse: collapse; /* Collapse borders so that they don't double up */
        }

        th, td {
            border: 1px solid black; /* Add border to table cells */
            padding: 8px; /* Add some padding for content spacing */
            text-align: center; /* Center the text */
        }

        thead {
            background-color: #f2f2f2; /* Optional: add a background color to the table header */
        }

        th {
            height: 50px; /* Optional: define a specific height for header cells */
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9; /* Optional: add zebra striping to the table body rows */
        }
    </style>
</head>
<body>
    <div class="panel-body" id="printableArea">
        <h3>Inventaire Global</h3>  

        <table >
            <thead>
            <tr>
                <th class="center hidden-phone">Noms Boutiques</th>
                <th class="center hidden-phone">Noms Articles</th>
                <th class="center hidden-phone">Quantité en Stock</th>
                <th class="center hidden-phone">Prix d'Achats</th>
                <th class="center hidden-phone">Prix de Ventes</th>
                <th class="center hidden-phone">Valeur En Magasin</th>
            </tr>
            </thead>
            <tbody class="center hidden-phone">
                @foreach ($boutiques as $nomBoutique => $data)
                    @foreach ($data['produits'] as $produit)
                        <tr>
                            <td>{{ $nomBoutique }}</td>
                            <td>{{ $produit->NomProduit }}</td>
                            <td>{{ $produit->QuantiteEnStock }}</td>
                            <td>{{ number_format($produit->PrixAchat, 2, ',', '.') }}</td>
                            <td>{{ number_format($produit->PrixVente, 2, ',', '.') }}</td>
                            <td>{{ number_format($produit->ValeurEnMagasin, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="5" style="text-align: right; font-weight: bold;">Valeur Totale pour {{ $nomBoutique }}</td>
                        <td style="font-weight: bold;">{{ number_format($data['totalValeur'], 2, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="5" style="text-align: right; font-weight: bold;">VALEUR TOTALE DES MAGASINS</td>
                    <td style="font-weight: bold;">{{ number_format($total, 2, ',', '.') }}</td>
                </tr>
            </tbody>

        </table>
    </div>
    <script src="octopus/assets/vendor/jquery/jquery.js"></script>
    <script src="octopus/assets/vendor/bootstrap/js/bootstrap.js"></script>
    <script src="octopus/assets/vendor/nanoscroller/nanoscroller.js"></script>
    <script src="octopus/assets/vendor/jquery-datatables/media/js/jquery.dataTables.js"></script>
    <script src="octopus/assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
    <script src="octopus/assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
    <script src="js/inventairesuper.js"></script>
</body>
</html>
