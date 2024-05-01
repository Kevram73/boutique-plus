@extends('layout')
@section('css')
    <link rel="stylesheet" href="octopus/assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
@endsection
@section('contenu')
    <div class="inner-wrapper">
        <section role="main" class="content-body">
            <header class="page-header">
                <h2>Liste des livraisons</h2>
            </header>
            <div class="row">
                <section class="panel">
                    <header class="panel-heading">
                        <div class="panel-actions">

                        </div>

                        <h1 class="panel-title">LISTES DES LIVRAISONS SUR LE MODELE : N°  {{$modele->libelle}}</h1>
                    </header>


                    <div class="panel-body">
                        <div class="row">
                        <ul class="list-group">
                            <li class="list-group-item">Modele N°:<b> <span class="text-danger" >{{$modele->numero}}</span> </b></li>
                            <li class="list-group-item">Ref Modele :<b> <span class="text-danger" >{{$modele->ref_modele}}</span> </b></li>
                            <li class="list-group-item">Conditionnement:<b> <span class="text-danger" >{{$modele->condi_modele}}</span> </b></li>
                            <li class="list-group-item">Quantité:<b> <span class="text-danger" >{{$modele->quantite}}</span> </b></li>
                                <li class="list-group-item">Prix :<b> <span class="text-danger prix"  >{{ $modele->prix }}</span></b></li>
                                <li class="list-group-item">Prix de gros :<b> <span class="text-danger prix"  >{{ $modele->prix_de_gros }}</span></b></li>
                        </ul>


                        </div>
                        <table class="table table-bordered table-striped mb-none" id="afficheTable" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf" >
                            <thead>
                            <tr>
                                <th class="center hidden-phone">N° Livraison</th>
                                <th class="center hidden-phone">Quantité livré</th>
                                <th class="center hidden-phone">Quantité vendue</th>
                            </tr>
                            </thead>
                            <tbody class="center hidden-phone">
                                @if (count($livraisons) == 0)
                                <tr>
                                    <td colspan="3" class="text-center">Aucune livraison</td>
                                </tr>
                                @endif
                            @foreach($livraisons as $ven)

                                <tr class="gradeA">
                                    <td class="center hidden-phone">{{$ven->livraison->numero}}  </td>
                                    <td class="center hidden-phone">{{$ven->quantite_livre}}</td>
                                    <td class="center hidden-phone">{{$ven->quantite_vendue}}</td>
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
