<!doctype html>
<html class="fixed">

<head>
    <!-- Basic -->
    <meta charset="UTF-8">
    <!-- Vendor CSS -->
    <link rel="stylesheet" href="octopus/assets/vendor/bootstrap/css/bootstrap.css" />
    <link rel="stylesheet" href="octopus/assets/vendor/font-awesome/css/font-awesome.css" />

    <style>
        .container {
            display: flex;
            align-items: center;
        }

        .image {
            width: 200px;
            /* Ajustez la largeur selon vos besoins */
            margin-right: 20px;
            /* Ajustez la marge selon vos besoins */
        }
    </style>
</head>

<body onload="window.print(); fermer()">

    <section class="body">

        <div class="wrapper">
            <!-- Main content -->
            <section class="invoice">
                <!-- title row -->
                <div class="row">
                    <!-- /.col -->
                    <div class="row invoice-info">
                        <div class="col-sm-4 invoice-col">

                            <address>
                                <b> {{Auth::user()->boutique->nom}}</b><br>
                                Adresse: {{Auth::user()->boutique->adresse}}<br>
                                Telephone:  {{Auth::user()->boutique->telephone}}
                            </address>
                        </div>
                    </div>
                </div>
                <!-- info row -->
                <div class="row invoice-info">
                    <div class="col-sm-4 invoice-col">

                        <p style="font-size: 32px; text-decoration: underline;">
                            <strong> Facture </strong>
                        </p>
                    </div>
                </div>
                <!-- /.row -->
                <table border="1">
                    <tr>
                        <td class="text-center">

                            <address>
                                Numéro
                            </address>
                        </td>
                        <td class="text-center">

                            <address>
                                Date
                            </address>
                        </td>

                    </tr>
                    <tr>
                        <td class="text-center">

                            <address>
                                {{ $vente[0]->numero }}
                            </address>
                        </td>
                        <td class="text-center">

                            <address>
                                {{ $vente[0]->date }}
                            </address>
                        </td>

                    </tr>
                </table><br>


                <b style="text-decoration: underline;"> <strong> Client </strong></b>
                <span class="text-danger">{{ $vente[0]->nom }} </span><br>
                <b style="text-decoration: underline;">
                    <strong> Contact </strong></b>
                : <span class="text-danger">{{ $vente[0]->contact }}</span>




                <h5 align="center"><strong>LISTE DES PRODUITS DE LA VENTE</strong></h5>
                <br>
                <!-- Table row -->

                <div class="row">
                    <div class="col-12 table-responsive">
                        <table id="afficheTable" border="1"
                            data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">

                            <tr>
                                <th class="center hidden-phone">Désignation</th>
                                <th class="center hidden-phone">Prix unitaire </th>
                                <th class="center hidden-phone">Quantité </th>
                                <th class="center hidden-phone">Réduction </th>
                                <th class="center hidden-phone">Prix total </th>
                            </tr>

                            @foreach($vente as $ven)
                            <tr class="gradeA">
                                <td class="center hidden-phone">{{ $ven->produit }} - {{ $ven->modele }} </td>
                                <td class="center hidden-phone prix-n">{{ $ven->prix }}</td>
                                <td class="center hidden-phone">{{ $ven->quantite }}</td>
                                <td class="center hidden-phone">{{ $ven->reduction }}</td>
                                <td class="center hidden-phone prix">{{ $ven->prixtotal }} FCFA</td>
                            </tr>
                            @endforeach

                        </table>
                        <center>
                            <li class="list-group-item center hidden-phone">Montant réduction :<b> <span class="prix">{{
                                        $all_vente->montant_reduction }} FCFA</span></b></li>
                        </center>

                        <center>
                            <li class="list-group-item center hidden-phone">Montant total :<b> <span
                                        class="text-danger prix">{{ $total }} FCFA</span></b></li>
                        </center>

                    </div>

                </div>
                <table id="afficheTable" border="1"  style=" position:relative; "
                    data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">

                    <tr>
                        <th class="center hidden-phone">Prix Total HT</th>
                        <th class="center hidden-phone">TVA 0% </th>
                        <th class="center hidden-phone">Prix Total TTC </th>
                    </tr>


                    <tr class="gradeA">
                        <td class="center hidden-phone">{{  $all_vente->montant_ht}}</td>
                        <td class="center hidden-phone prix-n">{{ $all_vente->tva }}</td>
                        <td class="center hidden-phone"> {{ $all_vente->totaux }}</td>
                    </tr>


                </table>


                <p> Arrêter la présente Facture à la somme TTC de : {{ $all_vente->totaux }}</p>

                <p>Condition de paiement : Avance : {{ $vente[0]->donne }} FCFA ; Reste à payer : {{ $vente[0]->restant
                    }} FCFA </p>

                <table style="position: absolute; bottom: 20px; left: 0px;">
                    <tr>
                        <td class="text-left" style="position: absolute; bottom: 10px; left: 0px;">

                            <address>
                                <b style="text-decoration: underline;"> Client : </b><span class="text-danger">{{
                                    $vente[0]->nom }} </span> <br>

                            </address>
                        </td>
                        <td class="text-right" style="position: absolute; bottom: 15px;  right: 5px;">

                            <address style="position: absolute; bottom: 10px;  right: 5px;">
                                <b style="text-decoration: underline;">KANFITIE MINGOUBE </b><br>

                            </address>
                        </td>

                    </tr>
                </table>
            </section>

            <!-- <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 20px;"></div> -->

</body>

<script>
    function setNumeralHtml(element, format, surfix = "") {
        var prices = $("." + element);

        for (var i = 0; i < prices.length; i++) {
            var number = numeral(prices[i].innerText);

            var string = number.format(format);
            prices[i].innerText = string + " " + surfix;
        }

    }

    setNumeralHtml("prix", "0,0", "FCFA");
    setNumeralHtml("prix-n", "0,0");
</script>




<!-- Vendor -->
<script src="octopus/assets/vendor/jquery/jquery.js"></script>
<script src="octopus/assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
<script src="octopus/assets/vendor/bootstrap/js/bootstrap.js"></script>
<script src="octopus/assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>
<script src="/vendor/numeral/numeral.min.js"></script>
<script>
    // switch between locales
    numeral.locale('fr');
</script>



<!-- Examples -->


</html>
