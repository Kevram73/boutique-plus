@extends('layout')
@section('css')
    <link rel="stylesheet" href="octopus/assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
@endsection
@section('contenu')
    <div class="inner-wrapper">
        <section role="main" class="content-body">
            <header class="page-header">
                <h2>Detail vente</h2>
            </header>
            <div class="row">
                <section class="panel">
                    <header class="panel-heading">
                        <div class="panel-actions">
                            <a href="#" class="fa fa-caret-down"></a>
                        </div>

                        <h1 class="panel-title">LISTES DES  PRODUITS DE LA  VENTE    :     N*  {{$vente[0]->numero}}</h1>
                    </header>


                    <div class="panel-body">
                        <div class="row">
                        <ul class="list-group">
                            <li class="list-group-item">Vente N*:<b> <span class="text-danger" >{{$vente[0]->numero}}</span> </b></li>
                            <li class="list-group-item">Date de vente :<b> <span class="text-danger" >{{$vente[0]->date}}</span> </b></li>
                                <li class="list-group-item">Montant total :<b> <span class="text-danger prix" >{{ $all_vente->totaux }} FCFA</span></b></li>
                        </ul>
                        </div>
                        <table class="table table-bordered table-striped mb-none" id="afficheTable" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf" >
                            <thead>
                            <tr>
                                <th class="center hidden-phone">Produit</th>
                                <th class="center hidden-phone">Prix </th>
                                <th class="center hidden-phone">Quantité </th>
                                <th class="center hidden-phone">Prix total </th>
                                <th class="center hidden-phone">Livraison </th>
                            </tr>
                            </thead>
                            <tbody class="center hidden-phone">

                            @foreach($vente as $ven)

                                <tr class="gradeA">
                                    <td class="center hidden-phone">{{$ven->produit}} - {{$ven->modele}}  </td>
                                    <td class="center hidden-phone prix">{{$ven->prix}} fcfa</td>
                                    <td class="center hidden-phone">{{$ven->quantite}}</td>
                                    <td class="center hidden-phone prix">{{$ven->prixtotal}} fcfa</td>
                                    <td class="center hidden-phone">{{$ven->livraison}}</td>
                                </tr>

                            @endforeach

                            </tbody>
                        </table>
                        @if ($all_vente->with_tva)
                        <div>
                            <table class="table" style="width: 45%; position: relative; left: 55%;">
                                <tr>
                                    <td style="border-top-style:none;">TVA</td>
                                    <td class="text-center" style="border-top-style:none;">{{ $all_vente->tva }} %</td>
                                </tr>
                                <tr>
                                    <td>Montant TVA</td>
                                    <td class="text-center prix-2">{{ $all_vente->montant_tva }}</td>
                                </tr>
                                <tr>
                                    <td>Montant HT</td>
                                    <td class="text-center prix-2">{{ $all_vente->montant_ht }}</td>
                                </tr>
                                <tr>
                                    <td>Montant TTC</td>
                                    <td class="text-center prix">{{ $all_vente->totaux }} FCFA</td>
                                </tr>
                            </table>
                        </div>
                        @endif
                        <a class=" btn btn-default mb-xs mt-xs mr-xs btn btn-danger" href="javascript:history.go(-1)"><i class="fa  fa-times"></i>Fermé</a>
                        @if (isset($vente[0]->facture))
                        <a class=" btn btn-default mb-xs mt-xs mr-xs btn btn-info"  href="{{ '/factures/'.$vente[0]->facture }}"><i class="fa  fa-print"></i>Imprimer</a>
                        @else
                        <a class=" btn btn-default mb-xs mt-xs mr-xs btn btn-warning"  href="{{ '/reglements-'.$all_vente->id }}"><i class="fa  fa-money"></i>Valider et Imprimer</a>
                        @endif
                        @if ($vente[0]->delivered != "delivered" && isset($vente[0]->facture))
                        <a class=" btn btn-default mb-xs mt-xs mr-xs btn btn-info" data-toggle="modal" data-target="#exampleModal" ><i class="fa  fa-print"></i>Effectuer la livraison</a>
                        @endif
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="exampleModalLabel">Faire une livraison</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                  Voulez vous vraiment effectué la livraison complète de cette vente?
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                  <button type="button" onclick="valider('{{ $vente[0]->id }}')" class="btn btn-primary">Procéder</button>

                                </div>
                              </div>
                            </div>
                          </div>

                        @if (Auth::user()->boutique->settings->where('tag', 'vente_fictive')->first() && Auth::user()->boutique->settings->where('tag', 'vente_fictive')->first()->pivot->is_active)
                            <a class=" btn btn-default mb-xs mt-xs mr-xs btn btn-warning"  href="{{ '/fictive-'.$all_vente->id }}"><i class="fa fa-file"></i>Vente Fictive</a>
                            @if (isset($factures) && count($factures) > 0)
                                {{-- @if (count($factures) == 1) --}}
                                <a class=" btn btn-default mb-xs mt-xs mr-xs btn btn-info"  href="{{ '/factures/fictives/'.$factures[0]->url }}"><i class="fa  fa-print"></i>Facture Fictive</a>
                                {{-- @else
                                <div class="dropdown">
                                    <button class="btn btn-default mb-xs mt-xs mr-xs btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa  fa-print"></i>Facture Fictive {{ count($factures) > 1 ? '('. count($factures) .')' : ''}}
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        @foreach ($factures as $fac)
                                        <a class="dropdown-item" href="{{ '/factures/fictives/'.$fac->url }}">{{ $fac->url }}</a>
                                        @endforeach
                                    </div>
                                </div>
                                @endif --}}
                            @endif
                        @endif
                        <a class=" btn btn-default mb-xs mt-xs mr-xs btn btn-danger"  href="/retourvente-{{ $all_vente->id }}"><i class="fa fa-arrow-left"></i>Retour Vente</a>
                            @if (isset($retourVentes) && count($retourVentes) > 0)
                                <br><br>
                                <ul class="list-group">
                                    <li class="list-group-item"> <h4><center>Retoure produits</center></h4> </li>
                                    <li class="list-group-item">Montant total rendu :<b> <span class="text-danger prix" >{{ $total_rendu }} FCFA</span></b></li>
                                </ul>
                                <table class="table table-bordered table-striped mb-none" id="afficheTable2" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf" >
                                    <thead>
                                    <tr>
                                        <th class="center hidden-phone">Montant total</th>
                                        <th class="center hidden-phone">Total rendu au client </th>
                                        <th class="center hidden-phone">Nombre produits</th>
                                        <th class="center hidden-phone">Date Retour</th>
                                        <th class="center hidden-phone">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody class="center hidden-phone">

                                    @foreach($retourVentes as $item)

                                        <tr class="gradeA">
                                            <td class="center hidden-phone prix">{{$item->totaux}}} </td>
                                            <td class="center hidden-phone prix">{{$item->payer}}</td>
                                            <td class="center hidden-phone">{{$item->qte}}</td>
                                            <td class="center hidden-phone">{{$item->created_at}}</td>
                                            <td class="center hidden-phone"><a class="btn btn-info " href="/retourventedetail-{{ $item->id }}" ><i class="fa  fa-info"></i></a></td>
                                        </tr>

                                    @endforeach

                                    </tbody>
                                </table>
                            @endif
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


<script>
    function valider(id){


        $.ajax({
            type: "GET", // HTTP method
            url: "/delivered-vente/"+id,
            dataType: "json", // Type of data expected from the server
            success: function(response) {
                window.location.href = "/ventes";
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
</script>
@endsection
