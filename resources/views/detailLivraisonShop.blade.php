@extends('layout')
@section('css')
    <link rel="stylesheet" href="octopus/assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
@endsection
@section('contenu')
    <div class="inner-wrapper">
        <section role="main" class="content-body">
            <header class="page-header">
                <h2>Detail d'une livraison</h2>
            </header>
            <div class="row">
                <section class="panel">
                    <header class="panel-heading">
                        <div class="panel-actions">
                            <a href= $modeleF = modeleFournisseur::find($this->modele_fournisseur_id);
                                "#" class="fa fa-caret-down"></a>
                        </div>

                        <h1 class="panel-title">LISTES DES VENTES SUR LA LIVRAISON    :     N*  {{$livraison->numero}}</h1>
                    </header>


                    <div class="panel-body">
                        <div class="row">
                        <ul class="list-group">
                            <li class="list-group-item">Livraison N*:<b> <span class="text-danger" >{{$livraison->numero}}</span> </b></li>
                            <li class="list-group-item">Date de livraison :<b> <span class="text-danger" >{{$livraison->date_livraison}}</span> </b></li>
                            <li class="list-group-item">Boutique :<b> <span class="text-danger" >{{$livraison->boutique->nom}}</span> </b></li>
                                <li class="list-group-item">Quantité livré :<b> <span class="text-danger"  >{{ $livraison->qte_liv() }}</span></b></li>
                                <li class="list-group-item">Quantité vendue :<b> <span class="text-danger"  >{{ $livraison->qte_sell() }}</span></b></li>
                        </ul>


                        </div>
                        <table class="table table-bordered table-striped mb-none" id="afficheTable" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf" >
                            <thead>
                            <tr>
                                <th class="center hidden-phone">Produit</th>
                                <th class="center hidden-phone">Prix </th>
                                <th class="center hidden-phone">Quantité </th>
                                <th class="center hidden-phone">Réduction </th>
                                <th class="center hidden-phone">Prix total </th>
                                <th class="center hidden-phone">N° Vente </th>
                            </tr>
                            </thead>
                            <tbody class="center hidden-phone">

                            @foreach($preventes as $ven)

                                <tr class="gradeA">
                                    <td class="center hidden-phone">{{$ven->modele()->libelle}}  </td>
                                    <td class="center hidden-phone prix">{{$ven->prix}} fcfa</td>
                                    <td class="center hidden-phone">{{$ven->quantite}}</td>
                                    <td class="center hidden-phone prix">{{$ven->reduction}}</td>
                                    <td class="center hidden-phone prix">{{$ven->prixtotal}} fcfa</td>
                                    <td class="center hidden-phone">{{$ven->vente()->numero}}</td>
                                </tr>

                            @endforeach

                            </tbody>
                        </table>


                    </div>

                </section>
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
        setNumeralHtml("prix-2", "0,0");
    </script>
@endsection
