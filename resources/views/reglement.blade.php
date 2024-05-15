@extends('layout')
@section('css')
    <link rel="stylesheet" href="octopus/assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
@endsection
@section('contenu')
    <div class="inner-wrapper" onload="window.print();">
        <section role="main" class="content-body">
            <header class="page-header">
                <h2>Facture</h2>
            </header>
            <div class="row">
                <div class="row">
                    @if($all_vente->with_avoir)
                    <div class="col-md-4 form-group">
                        <label class="col-sm-3 control-label">Montant total</label>
                        <div class="col-sm-9">
                            @if($avoir>$all_vente->totaux)
                            <input type="text" name="total"  id="total" class="form-control"  value="0"  readonly="readonly" required/>
                            @else
                            <input type="text" name="total"  id="total" class="form-control"  value="{{$all_vente->totaux-$avoir}}"  readonly="readonly" required/>
                            @endif
                        </div>
                    </div>
                    @else
                    <div class="col-md-4 form-group">
                        <label class="col-sm-3 control-label">Montant total</label>
                        <div class="col-sm-9">
                            <input type="text" name="total"  id="total" class="form-control"  value="{{$all_vente->totaux}}"  readonly="readonly" required/>
                        </div>
                    </div>
                    @endif
                    <div class="col-md-4 form-group">
                        <label class="col-sm-3 control-label">Montant donné</label>
                        <div class="col-sm-9">
                            <input type="number" name="donne"  id="donne" class="form-control" required/>
                        </div>
                    </div>
                    <div class="col-md-4 form-group">
                        <label class="col-sm-3 control-label" id="te"></label>
                        <div class="col-sm-9">
                            <input type="number" name="restant"  id="restant" class="form-control"  readonly="readonly"  required/>
                            <input type="hidden" name="reste" id="reste"/>

                        </div>
                    </div>
                </div>
                <a class=" btn btn-default mb-xs mt-xs mr-xs btn btn-danger"  href="{{route('ventes')}}"><i class="fa fa-close"></i> Fermer </i></a>
                <input type="hidden" name="typeVente" id="typeVente" value="{{$all_vente->type_vente}}"/>
                <input type="hidden" name="urlFacture" id="urlFacture" value="{{'/facturesimple-'.$all_vente->id}}"/>
                <spann class=" btn btn-default mb-xs mt-xs mr-xs btn btn-primary" id="valider"><i class="fa fa-check"></i> Valider et imprimer la facture <i class="fa  fa-file-pdf-o"></i></spann>

                <section class="panel">
                    <header class="panel-heading">
                        <div class="panel-actions">
                            <a href="#" class="fa fa-caret-down"></a>
                        </div>

                        <h1 class="panel-title">LISTES DES  PRODUITS DE LA  VENTE </h1>
                    </header>


                    <div class="panel-body">
                        <div class="row">
                        <ul class="list-group">
                                <li class="list-group-item">Montant total :<b> <span class="text-danger prix"  >{{$all_vente->totaux}} fcfa</span></b></li>
                                <li class="list-group-item">Montant réduit :<b> <span class="text-danger prix" >{{$total_reduction}} fcfa</span></b></li>
                                <li class="list-group-item">Montant donné :<b> <span class="text-danger prix-n" id="Sdonne" > </span></b></li>
                                <li class="list-group-item">Montant Restant :<b> <span class="text-danger prix-n" id="Srestant" > </span></b></li>
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
                            </tr>
                            </thead>
                            <tbody class="center hidden-phone">

                            @foreach($vente as $ven)

                                <tr class="gradeA">
                                    <td class="center hidden-phone">{{$ven->produit}} - {{$ven->modele}}  </td>
                                    <td class="center hidden-phone prix-n">{{$ven->prix}} fcfa</td>
                                    <td class="center hidden-phone">{{$ven->quantite}}</td>
                                    <td class="center hidden-phone">{{$ven->reduction}}</td>
                                    <td class="center hidden-phone prix">{{$ven->prixtotal}} fcfa</td>
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
                                    <td class="text-center">{{ $all_vente->montant_tva }}</td>
                                </tr>
                                <tr>
                                    <td>Montant HT</td>
                                    <td class="text-center">{{ $all_vente->montant_ht }}</td>
                                </tr>
                                <tr>
                                    <td>Montant TTC</td>
                                    <td class="text-center">{{ $all_vente->totaux }} FCFA</td>
                                </tr>
                            </table>
                        </div>
                        @endif
                        {{-- @if ($all_vente->with_tva)
                        <br>
                        <div class="d-flex justify-content-end">
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div class="row">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 align-center">
                                                TVA
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 align-center">
                                                <strong class="badge-pillt">{{ $all_vente->tva }} %</strong>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div class="row">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 align-center">
                                                Montant TVA
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 align-center">
                                                <strong class="">{{ $all_vente->montant_tva }}</strong>
                                            </div>
                                        </div>


                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div class="row">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 align-center">
                                                Montant HT
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 align-center">
                                                <strong class="">{{ $all_vente->montant_ht }}</strong>
                                            </div>
                                        </div>


                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div class="row">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 align-center">
                                                Montant TTC
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 align-center">
                                                <strong class="">{{ $all_vente->totaux }} FCFA</strong>
                                            </div>
                                        </div>


                                    </li>
                                  </ul>
                            </div>
                        </div>

                        @endif --}}

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
    <script src="js/facture.js"></script>
    <script src="js/facture-paye-complet.js"></script>
    <script>

        function setNumeralHtml(element, format, surfix="", type="html")
        {
            var prices = $("."+element);

            for(var i=0; i<prices.length; i++)
            {
                if(type=="html")
                {
                    var number = numeral(prices[i].innerText);

                    var string = number.format(format);
                    prices[i].innerText = string+" "+surfix;
                }else if(type=="value")
                {
                    var number = numeral(prices[i].value);

                    var string = number.format(format);
                    prices[i].value = string+" "+surfix;
                }

            }

        }

        setNumeralHtml("prix", "0,0", "FCFA");
        setNumeralHtml("prix-n", "0,0");
        setNumeralHtml("prix-value", "0,0", "", "value");
    </script>

@endsection
