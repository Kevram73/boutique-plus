@extends('layout')
@section('css')
    <link rel="stylesheet" href="octopus/assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
@endsection
@section('contenu')
    <div class="inner-wrapper">
        <section role="main" class="content-body">
            <header class="page-header">
                <h2>Detail Avoir Client</h2>
            </header>
            <div class="row">
                <section class="panel">
                    <header class="panel-heading">
                        <div class="panel-actions">
                            <a href="#" class="fa fa-caret-down"></a>
                        </div>

                        <h1 class="panel-title">LISTES DES MOUVEMENTS DE L'AVOIR DU CLIENT: {{$client->nom}}</h1>
                    </header>


                    <div class="panel-body">
                        <div class="row">
                        <ul class="list-group">
                            <li class="list-group-item">Nom:<b> <span class="text-danger" >{{$client->nom}}</span> </b></li>
                            <li class="list-group-item">Email :<b> <span class="text-danger" >{{$client->email}}</span> </b></li>
                            <li class="list-group-item">Contact :<b> <span class="text-danger" >{{$client->contact}}</span> </b></li>
                            <li class="list-group-item">Avoir :<b> <span class="text-danger prix">{{ $client->avoir }} FCFA</span></b></li>
                        </ul>


                        </div>
                        <table class="table table-bordered table-striped mb-none" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
                            <thead>
                            <tr>
                                <th class="center hidden-phone">Date</th>
                                <th class="center hidden-phone">Montant</th>
                                <th class="center hidden-phone">Utilisateur</th>
                            </tr>
                            </thead>
                            <tbody class="center hidden-phone">

                            @foreach($avoirs as $avoir)

                                <tr class="gradeA">
                                    <td class="center hidden-phone">{{$avoir->date_ajout}}</td>
                                    <td class="center hidden-phone prix">{{$avoir->amount}} fcfa</td>
                                    <td class="center hidden-phone">{{$avoir->user()->nom}}</td>
                                </tr>

                            @endforeach

                            </tbody>
                        </table>

                        <table class="table table-bordered table-striped mb-none" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
                            <thead>
                            <tr>
                                <th class="center hidden-phone">Vente</th>
                                <th class="center hidden-phone">Montant</th>
                                <th class="center hidden-phone">Date</th>
                                <th class="center hidden-phone">Type</th>
                            </tr>
                            </thead>
                            <tbody class="center hidden-phone">

                            @foreach($ventes as $vente)

                                <tr class="gradeA">
                                    <td class="center hidden-phone">{{$vente->numero}}</td>
                                    <td class="center hidden-phone prix">{{$vente->totaux}} fcfa</td>
                                    <td class="center hidden-phone">{{$vente->created_at->format('Y-m-d')}}</td>
                                    <td class="center hidden-phone">
                                        @if($vente->type_vente == 1)
                                         SIMPLE
                                        @elseif ($vente->type_vente == 2)
                                         A CREDIT
                                        @elseif ($vente->type_vente == 3)
                                         NON LIVREE
                                        @elseif ($vente->type_vente == 4)
                                         EN GROS
                                        @endif
                                    </td>
                                </tr>

                            @endforeach

                            </tbody>
                        </table>




                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">

                                    <div class="modal-header " style="background-color: #0b93d5;border-top-left-radius: inherit;border-top-right-radius: inherit">
                                        <h4 class="modal-title-user" id="myModalLabel" style="color: white"></h4>
                                    </div>
                                    <div class="modal-body">
                                        <form id="form" action="{{ route('save_avoir') }}" method="POST" class="form-validate form-horizontal mb-lg" enctype="multipart/form-data">
                                            {{csrf_field()}}
                                            <div class="form-group mt-lg">
                                                <label class="col-sm-3 control-label">Nom/Raison sociale</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="nom"  id="Anom" class="form-control" value="{{ $client->nom }}" readonly/>
                                                    <input type="hidden" name="idclient" id="Aidclient" value="{{ $client->id }}"/>
                                                </div>
                                            </div>
                                            <div class="form-group mt-lg">
                                                <label class="col-sm-3 control-label">Avoir Client</label>
                                                <div class="col-sm-9">
                                                    <input type="integer" name="avoir" id="Aavoir" value="{{ $client->avoir }}" class="form-control" readonly/>
                                                </div>
                                            </div>
                                            <div class="form-group mt-lg">
                                                <label class="col-sm-3 control-label">Montant à ajouter</label>
                                                <div class="col-sm-9">
                                                    <input type="integer" name="amount" id="amount" class="form-control" placeholder="0"/>
                                                </div>
                                            </div>


                                            <div class="modal-footer">
                                                <div class="col-md-12 text-right">
                                                    <button type="submit" class="btn btn-primary" id="Abtnadd"><i class="fa fa-check"></i> Valider</button>
                                                    <button type="button" class="mb-xs mt-xs mr-xs btn btn-default  " data-dismiss="modal"><i class="fa fa-times"></i> Annuler</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-money"></i> Ajouter avoir</button>
                        {{-- <a style="display: none !important;" class=" btn btn-default mb-xs mt-xs mr-xs btn btn-danger"  href="/retourvente-{{ $all_vente->id }}"><i class="fa fa-arrow-left"></i>Retour Vente</a> --}}
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
