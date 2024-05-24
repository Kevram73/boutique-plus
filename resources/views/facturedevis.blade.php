<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devis en détail</title>

    <style>
        @page {
            size: A4;
            margin: 20mm 25mm 20mm 25mm;
        }
        body{
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            width: 100%;
            max-width: 210mm;
            min-height: 297mm;
            margin: auto;
            position: relative;
            z-index: 1;

        }

        body::before{
            content: '';
          position: absolute;
          top: 50;
          right: 0;
          bottom: 0;
          left: 0;
          background-image: url('https://boutique.mingoube.com/image/logo-MINGOUBE.png');
          background-size: 100% 100%;
          background-repeat: no-repeat;
          background-attachment: fixed;
          background-position: center;
          opacity: 0.4; /* Adjust the opacity as needed */
          z-index: -1;
        }

        header img {
            width: 100%;
            height: auto;
        }

        .header-text{
            font-weight: bold;
            margin: 20px 0;
        }

        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table th, table td { border: 1px solid #4a4a4a; padding: 5px; text-align: left; }
        table th { background-color: #c6c6c6; }
        table * { font-size: 14px; }

        .inline-elt{
            display: flex;
            margin-bottom: 10px;
        }

        .inline-elt h5, .inline-elt span{
            margin: 0;
            font-size: 14px;
        }

        .condition, .date {
            margin-top: 20px;
            font-size: 14px;
            text-align: left;
        }

        .date{
            font-size: 14px;
            text-align: right;
        }

        .bottom-text{
            font-size: 12px;
            margin-top: 10px;
        }

        .bottom-text span{
            font-weight: bold;
        }

        .separator {
            margin-top: 50px;
            border-top: 3px solid #4a4a4a;
            border-bottom: 2px solid #323d7c;
        }

        footer {
            text-align: center;
            font-size: 12px;
        }

        .footer-info {
            margin-bottom: 5px;
        }

        .footer-info span {
            font-weight: bold;
        }

        .bottom-space {
            display: flex;
            justify-content: space-between;
            margin-top: 120px;
            font-size: 14px;
            text-decoration: underline;
            font-weight: bold;
        }

        .bottom-space .second{
            text-align: right;
            margin-top: -15px;
        }
        .header-container {
            background-color: #004a99; /* Dark blue background */
            color: white;
            padding: 10px 20px; /* Adjust the padding to fit your content */
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            width: 100px; /* Adjust based on actual logo size */
            height: auto;
        }

        .header-text-logo {
            text-align: left;
            font-size: 12px; /* Adjust the size as needed */
        }

        .header-text-logo h3 {
            margin: 0;
            padding: 0;
            font-size: 20px; /* Adjust the size as needed */
            font-weight: bold;
        }

        .header-text-logo p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <header>
        <img src="https://boutique.mingoube.com/image/top.png" />
    </header>

    <h3 class="header-text">Devis Détail</h3>

    <!-- Head Table -->
    <table id="head-table" style="width: 60%;">
        <thead>
            <tr>
                <th>Numéro</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{$devis->numero}}</td>
                <td>{{$devis->date_devis}}</td>
            </tr>
        </tbody>
    </table>

    <!-- Inline Elements Section -->
    <div class="inline-elt">
        <div>Client: <span>{{$client->nom}}</span></div>
        <div>Contact: <span>{{$client->contact}}</span></div>
    </div>

    <!-- Main Table -->
    <table>
        <thead>
            <tr>
                <th>N°</th>
                <th>Désignations</th>
                <th>Prix Unit</th>
                <th>Quantités</th>
                <th>Réduction</th>
                <th>Montants</th>
            </tr>
        </thead>
        <tbody>
            @foreach($devislignes as $devisligne)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$devisligne->produit}} - {{$devisligne->modele}} </td>
                <td>{{number_format($devisligne->prix, 2, ',', '.')}}</td>
                <td>{{$devisligne->quantite}}</td>
                <td>{{number_format($devisligne->reduction, 2, ',', '.')}} FCFA</td>
                <td>{{number_format($devisligne->prixtotal, 2, ',', '.')}} FCFA</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5">TOTAL</th>
                @if($devis->with_tva == 1)
                <th>{{number_format($devis->montant_ht, 2, ',', '.')}} FCFA</th>
                @else
                <th>{{ number_format($devis->totaux, 2, ',', '.') }} FCFA</th>
                @endif
            </tr>
        </tfoot>
    </table>
    @if($devis->with_tva == 1)
    <table id="footer-table" style="width: 80%;">
        <thead>
            <tr>
                <th>Prix Total HT</th>
                <th>TVA {{ $devis->tva }} %</th>
                <th>Prix Total TTC</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ number_format($devis->montant_ht, 2, ',', '.') }} FCFA</td>
                <td>{{ number_format($devis->montant_tva, 2, ',', '.') }} FCFA</td>
                <td>{{ number_format($devis->totaux, 2, ',', '.')}} FCFA</td>
            </tr>
        </tbody>
    </table>
    @endif

    <!-- Footer Text -->
    <p class="bottom-text">Arrêter la présente Facture à la somme TTC de: <span>@php $fmt = new NumberFormatter('fr', NumberFormatter::SPELLOUT);
echo $fmt->format($devis->totaux); @endphp Francs CFA.</span></p>

    <h3 class="condition">Condition de paiement :</h3>
    <p class="date">Fait à {{ Auth::user()->boutique->nom }}, ce @php
    setlocale(LC_TIME, 'fr_FR.UTF-8');
    $date = new DateTimeImmutable(now());
    echo strftime('%A %d %B %Y', $date->getTimestamp());
@endphp</p>

    <div class="bottom-space">
        <p>Client:</p>
        <p class="second">Kanfitine MINGOUBE</p>
    </div>

    <div class="separator"></div>

    <footer>
        <div class="footer-info">SIS à DJAPENI, Rue Numero 1 Carréfour Non Loin de L'EPP  DJAPENI, Cinkasse-Togo</div>
        <div class="footer-info">Tél: +228 90 91 35 00 | NIF : 1001178767 | N° R.C.C.M : TG-LOM 2019 B 0001</div>
        <div class="footer-info">E-mail: <span>kmingoube@mingoube.com</span> | Site web: <span>www.mingoube.com</span> | BP: <span>347 - Dapaong</span></div>
    </footer>

</body>
</html>
