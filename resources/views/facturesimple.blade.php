@extends('layoutimprimer')
@section('contenu')
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impression</title>
    <style>
        /* Ajoutez ici vos styles CSS personnalisés pour la mise en page du PDF */
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body onload="window.print(); fermer()">
<div class="wrapper">
    <!-- Main content -->
    <section class="invoice">

        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                <address>
                    <b>{{ Auth::user()->boutique->nom }}</b><br>
                    Adresse: {{ Auth::user()->boutique->adresse }}<br>
                    Telephone: {{ Auth::user()->boutique->telephone }}
                </address>
            </div>
        </div>
        <!-- /.row -->

        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                <h6><strong>INFORMATIONS DU CLIENT</strong></h6>
                <address>
                    <b> Nom : <span class="text-danger" >{{$client->nom}}</span> </b><br>
                    <b>Contact : <span class="text-danger" >{{$client->contact}}</span> </b><br>
                    <b>Adresse : <span class="text-danger" >{{$client->adresse}}</span> </b><br>
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                <h6><strong>INFORMATIONS DE LA VENTE</strong></h6>
                <address>
                    <b>Vente N*: <span class="text-danger" >{{$vente[0]->numero}}</span> </b><br>
                    <b>Date de vente : <span class="text-danger" >{{$vente[0]->date}}</span> </b><br>
                    <b>Montant total : <span class="text-danger prix" >{{ $all_vente->totaux }} FCFA</span> </b><br>
                </address>
            </div>
            <!-- /.col -->
        </div>

        <h5 align="center"><strong>LISTE DES PRODUITS DE LA VENTE</strong></h5>
        <br>
        <!-- Table row -->

        <div class="row">
            <div class="col-12 table-responsive">
                <table class="table table-bordered table-striped mb-none" id="afficheTable" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf" >
                    <thead>
                    <tr>
                        <th class="center">Désignation</th>
                        <th class="center">Prix unitaire</th>
                        <th class="center">Quantité </th>
                        <th class="center">Réduction </th>
                        <th class="center">Prix total </th>
                    </tr>
                    </thead>
                    <tbody class="center">

                    @foreach($vente as $ven)

                        <tr class="gradeA">
                            <td class="center">{{$ven->produit}} - {{$ven->modele}}  </td>
                            <td class="center prix-n">{{$ven->prix}}</td>
                            <td class="center">{{$ven->quantite}}</td>
                            <td class="center">{{$ven->reduction}} FCFA</td>
                            <td class="center prix">{{$ven->prixtotal}} FCFA</td>
                        </tr>

                    @endforeach
                    </tbody>
                </table>
                <center><li class="list-group-item center" >Montant réduction :<b> <span class="prix">{{$all_vente->montant_reduction}} FCFA</span></b></li></center>
                @if (!$all_vente->with_tva)
                <center><li class="list-group-item center" >Montant total :<b> <span class="text-danger prix"  id="prixTotal">{{$total}} FCFA</span></b></li></center>
                @endif
            </div>
            @if ($all_vente->with_tva)
            <div>
                <!-- Contenu de la table de la TVA -->
            </div>
            @endif
        </div>
    </section>
</div>
</body>
</html>
@endsection
@section('js')
<script>

    function setNumeralHtml(element, format, surfix="")
    {
        var prices = $("."+element);

        for(var i=0; i<prices.length; i++)
        {
            var number = numeral(prices[i].innerText);

            var string = number.format(format);
            prices[i].innerText = string+" "+surfix;
        }

    }

    setNumeralHtml("prix", "0,0", "FCFA");
    setNumeralHtml("prix-n", "0,0");
</script>
@endsection
