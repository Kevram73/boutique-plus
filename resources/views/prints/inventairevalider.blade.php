<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Inventaire</title>
    <link rel="stylesheet" href="octopus/assets/vendor/bootstrap/css/bootstrap.css" />
</head>
<body>
    <div class="wrapper">
        <!-- Main content -->
        <section class="invoice">
            <!-- info row -->
            <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                    <address>
                        <strong>{{ Auth::user()->boutique->nom }}</strong><br>
                        Adresse: {{ Auth::user()->boutique->adresse }}<br>
                        Telephone: {{ Auth::user()->boutique->telephone }}
                    </address>
                </div>
            </div>
            <!-- /.row -->
            <h5 align="center"><strong>INVENTAIRE</strong></h5>

            <table class="table borderless mb-none">
                <tr>
                    <td>
                        <address>
                            <strong>Inventaire N° : <span class="text-danger">{{ $inventaire[0]->numero }}</span></strong><br>
                            Créé le <span class="text-danger">{{ date('d / m / Y', strtotime($inventaire[0]->date)) }}</span><br>
                            Fait le <span class="text-danger">{{ date('d / m / Y', strtotime($inventaire[0]->dateF)) }}</span>
                        </address>
                    </td>
                    <td class="text-right">
                        <address>
                            Par : <span class="text-danger">{{ $inventaire[0]->utilisateur . ' ' . $inventaire[0]->prenom }}</span>
                        </address>
                    </td>
                </tr>
            </table>

            <h6 align="center"><strong>LISTE DES PRODUITS</strong></h6>
            <br>
            <!-- Table row -->
            <div class="row">
                <div class="col-12 table-responsive">
                    <table class="table table-bordered table-striped mb-none">
                        <thead>
                        <tr>
                            <th class="text-center hidden-phone">Produit</th>
                            <th class="text-center hidden-phone">Quantité</th>
                            <th class="text-center hidden-phone">Quantité réelle</th>
                            <th class="text-center hidden-phone">Justification</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($inventaire as $mod)
                            <tr class="gradeA">
                                <td class="text-center hidden-phone">{{ $mod->produit . ' - ' . $mod->modele }}</td>
                                <td class="text-center hidden-phone">{{ $mod->quantite }}</td>
                                <td class="text-center hidden-phone">{{ $mod->quantiteR }}</td>
                                <td class="text-center hidden-phone">{{ $mod->justify ?? '---' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @if ($inventaire[0] && $inventaire[0]->observation)
                        <div class="list-group-item">Observation: <strong><span class="text-danger">{{ $inventaire[0]->observation }}</span></strong></div>
                    @endif
                </div>
            </div>
        </section>
    </div>
    <script src="octopus/assets/vendor/jquery/jquery.js"></script>
    <script src="octopus/assets/vendor/bootstrap/js/bootstrap.js"></script>
</body>
</html>
