@extends('layout')
@section('css')
    <link rel="stylesheet" href="octopus/assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
@endsection
@section('contenu')
    <div class="inner-wrapper">
        <section role="main" class="content-body">
            <header class="page-header">
                <h2>Détail livraison</h2>
            </header>

            <div class="row">
                <section class="panel">
                    <header class="panel-heading">
                        <div class="panel-actions">
                            <a href="#" class="fa fa-caret-down"></a>
                        </div>

                        <h1 class="panel-title">LISTES DES  PRODUITS DE LA  LIVRAISON     :     N*  {{isset($livraison[0]) ?  $livraison[0]->num : 0}}</h1>
                    </header>

                    <div class="panel-body">
                        <div class="row"  >
                        <ul class="list-group">
                            <li class="list-group-item">Livraison N*:<b> <span class="text-danger" id="sId">{{ isset($livraison[0]) ? $livraison[0]->numero : 0}}</span> </b></li>
                            <li class="list-group-item">Date de livraison :<b> <span class="text-danger" id="sNom">{{isset($livraison[0]) ? $livraison[0]->dateC : ''}}</span> </b></li>
                            <li class="list-group-item">Boutique :<b> <span class="text-danger" id="sAdresse">{{isset($livraison[0]) ? $livraison[0]->boutique : ''}}</span> </b></li>
                            {{--<li class="list-group-item">Date de livraison :<b> <span class="text-danger " id="sEmail" >{{isset($livraison[0]) ? $livraison[0]->dateL : ''}}</span></b></li>--}}
                         </ul>


                    </div>
                    <table class="table table-bordered table-striped mb-none" id="afficheTable" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf" >
                        <thead>
                        <tr>
                            <th class="center hidden-phone">Produit</th>
                            {{--<th class="center hidden-phone">Quantite vendue</th>--}}
                            <th class="center hidden-phone">Quantite livrée</th>
                            <th class="center hidden-phone">Quantité restante</th>
                            <th class="center hidden-phone">Etat</th>
                        </tr>
                        </thead>
                        <tbody class="center hidden-phone">

                        @if (isset($livraison))
                            @foreach($livraison as $liv)
                                <tr class="gradeA">
                                    <td class="center hidden-phone">{{$liv->produit}} - {{$liv->modele}}  </td>
                                    <td class="center hidden-phone">{{$liv->quantiteC}}</td>
                                    <td class="center hidden-phone">{{$liv->quantiteL}}</td>
                                    <td class="center hidden-phone">{{$liv->quantiteR}}</td>
                                    @if($liv->etat==false)
                                        <td class="center hidden-phone">  <a class="btn btn-success" >Livré </a></td>
                                    @endif
                                    @if($liv->etat==true)
                                        <td class="center hidden-phone">  <a class="btn btn-danger" >Non Livré </a></td>
                                    @endif
                                </tr>
                            @endforeach
                        @endif

                        </tbody>
                    </table>
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
@endsection
