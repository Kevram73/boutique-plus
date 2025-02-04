<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bon de Commande</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            width: 80%;
            margin: auto;
            border: 1px solid #000;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #004a99;
            padding-bottom: 10px;
        }
        .header img {
            height: 50px;
        }
        .title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }
        .info-table, .order-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .info-table td {
            padding: 5px;
        }
        .order-table th, .order-table td {
            border: 1px solid black;
            padding: 10px;
            text-align: center;
        }
        .order-table th {
            background-color: #c6c6c6;
        }
        .signature {
            margin-top: 50px;
            text-align: right;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <header>
        <img src="https://boutique.mingoube.com/image/top.png" />
    </header>

    <div class="title">BON DE COMMANDE N°{{$commande->id}}</div>

    <table class="info-table">
        <tr>
            <td><strong>Date de la commande:</strong> {{$commande->date_commande}}</td>
            <td><strong>Date de livraison:</strong> </td>
        </tr>
        <tr>
            <td colspan="2"><strong>Adresse de livraison:</strong> </td>
        </tr>
    </table>

    <table class="order-table">
        <tr>
            <th>Réf.</th>
            <th>Désignation</th>
            <th>Prix Unitaire</th>
            <th>Quantité</th>
            <th>Montant Total</th>
        </tr>
        @foreach($commande->commandeModele() as $cmdModele)
            <tr>
                <td>$cmdModele->modele->ref_modele</td>
                <td>$cmdModele->modele->libelle</td>
                <td>$cmdModele->prix</td>
                <td>$cmdModele->quantite</td>
                <td>$cmdModele->total</td>
            </tr>
        @endforeach
    </table>

    <p class="date">Fait à {{ Auth::user()->boutique->nom }}, ce @php
        setlocale(LC_TIME, 'fr_FR.UTF-8');
        $date = new DateTimeImmutable(now());
        echo strftime('%A %d %B %Y', $date->getTimestamp());
    @endphp</p>


        <div class="bottom-space">
            <p class="second">Le gérant {{ Auth::user()->nom }}</p>
        </div>

        <div class="separator"></div>

    <footer>
        <div class="footer-info">SIS à DJAPENI, Rue Numero 1 Carréfour Non Loin de L'EPP  DJAPENI, Cinkasse-Togo</div>
        <div class="footer-info">Tél: +228 90 91 35 00 | NIF : 1001178767 | N° R.C.C.M : TG-LOM 2019 B 0001</div>
        <div class="footer-info">E-mail: <span>kmingoube@mingoube.com</span> | Site web: <span>www.mingoube.com</span> | BP: <span>347 - Dapaong</span></div>
    </footer>
</body>
</html>
