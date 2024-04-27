<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Inventaire</title>
    <link rel="stylesheet" href="{{ asset('octopus/assets/vendor/bootstrap/css/bootstrap.css') }}" />
    <style>
        /* Ajoutez des styles personnalisés ici */
        body {
            font-size: 14px;
        }
        .wrapper {
            padding: 20px;
        }
        .invoice-info {
            margin-bottom: 20px;
        }
        .invoice-info address {
            margin-bottom: 10px;
        }
        .invoice-title {
            text-align: center;
            font-size: 20px;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Main content -->
        <section class="invoice">
            <!-- info row -->
            <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                    <address>
                        <b>{{ Auth::user()->boutique->nom }}</b><br>
                        Adresse: {{ Auth::user()->boutique->adresse }}<br>
                        Téléphone: {{ Auth::user()->boutique->telephone }}
                    </address>
                </div>
            </div>
            <!-- /.row -->
            <h5 class="invoice-title"><strong>INVENTAIRE</strong></h5>

            <table>
                <tr>
                    <td>
                        <address>
                            <b>Inventaire N* : <span class="text-danger">{{ $inventaire->numero }}</span></b><br>
                            <b>Créé le <span class="text-danger">{{ date('d / m / Y', strtotime($inventaire->date_inventaire)) }}</span></b><br>
                            <br>Fait le <span class="text-danger">........ / ........ / {{ now()->format('Y') }}</span>
                        </address>
                    </td>
                    <td>
                        <address>
                            <b><span class="text-danger"></span></b><br>
                            <b>Par : <span class="text-danger">{{ '    '.$user->nom . ' ' . $user->prenom }}</span></b><br>
                            <br>Par : <span class="text-danger"> .....................................................</span>
                        </address>
                    </td>
                </tr>
            </table>

            <h6 class="invoice-title"><strong>LISTE DES PRODUITS</strong></h6>
            <br>
            <!-- Table row -->

            <div class="row">
                <div class="col-12 table-responsive">
                    <form>
                        @csrf
                        <table>
                            <thead>
                            <tr>
                                <th class="text-center">Produit</th>
                                <th class="text-center">Quantité</th>
                                <th class="text-center">Quantité réelle</th>
                                <th class="text-center">Justification</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($modeles as $mod)
                                <tr>
                                    <td>{{ $mod->produit .' - '. $mod->modele }}</td>
                                    <td>{{ $mod->quantite }}</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{-- <li class="list-group-item center hidden-phone" >Montant total :<b> <span class="text-danger "  >{{$total}} fcfa</span></b></li> --}}
                    </form>
                </div>
            </div>
        </section>
    </div>
    <script src="octopus/assets/vendor/jquery/jquery.js"></script>
    <script src="octopus/assets/vendor/bootstrap/js/bootstrap.js"></script>
</body>
</html>
