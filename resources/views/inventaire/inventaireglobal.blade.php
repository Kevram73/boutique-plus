@extends('layout')
@section('css')
    <link rel="stylesheet" href="octopus/assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
@endsection
@section('contenu')
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
        
            /* Only show the printable area */
            #printableArea, #printableArea * {
                visibility: visible;
            }
        
            /* Position the printable area at the top of the page */
            #printableArea {
                position: absolute;
                left: 0;
                top: 0;
            }
        }
    </style>
    <div class="inner-wrapper">
        <section role="main" class="content-body">
            <header class="page-header">
                <h2>Inventaire Globale</h2>
            </header>

            <div class="row" >
                <section class="panel">
                    <header class="panel-heading">
                        <div class="panel-actions">
                            <a href="{{ route('inventory_global') }}" class="fa fa-file"></a>
                        </div>

                        <h1 class="panel-title">INVENTAIRE GLOBAL</h1>
                    </header>

                    <div class="panel-body" id="printableArea">
                        
                        
                        <table class="table table-bordered table-striped mb-none" id="boutiqueTable" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
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
            </div>
        </section>
    </div>

    
@endsection
@section('js')

    <script src="octopus/assets/vendor/jquery/jquery.js"></script>
    <script src="octopus/assets/vendor/bootstrap/js/bootstrap.js"></script>
    <script src="octopus/assets/vendor/nanoscroller/nanoscroller.js"></script>
    <script src="octopus/assets/vendor/jquery-datatables/media/js/jquery.dataTables.js"></script>
    <script src="octopus/assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
    <script src="octopus/assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
    <script src="js/inventairesuper.js"></script>
    
<script>



</script>
@endsection