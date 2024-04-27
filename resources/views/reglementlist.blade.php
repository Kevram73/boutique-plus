@extends('layout')
@section('css')
    <link rel="stylesheet" href="octopus/assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
@endsection
@section('contenu')
    <div class="inner-wrapper">
        <section role="main" class="content-body">
            <header class="page-header">
                <h2>Reglements</h2>
            </header>

            <div class="row">
                <section class="panel">
                    <header class="panel-heading">
                        <div class="panel-actions">
                            <a href="#" class="fa fa-caret-down"></a>
                        </div>

                        <h1 class="panel-title">LISTES DES  REGLEMENTS</h1>
                    </header>

                    <div class="panel-body">
                        <div class="tabs">
                            <ul class="nav nav-tabs nav-justified">
                                <li class="active">
                                    <a href="#reglements" data-toggle="tab" class="text-center" style="font-size: 2rem;"><i class="fa fa-star"></i> Liste des Règlements</a>
                                </li>
                                <li>
                                    <a href="#debiteurs" data-toggle="tab" class="text-center text-warning" style="font-size: 2rem;">Débiteurs</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div id="reglements" class="tab-pane active">
                                    <a class="modal-with-form btn btn-default mb-xs mt-xs mr-xs btn btn-default" id="btnreglement"><i class="fa fa-plus"></i>Ajouter un reglement</a>
                                    <table class="table table-bordered table-striped mb-none" id="reglementTable" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
                                        <thead>
                                        <tr>
                                            <th class="center hidden-phone">Date</th>
                                            <th class="center hidden-phone">Client</th>
                                            <th class="center hidden-phone">Contact</th>{{--
                                            <th class="center hidden-phone">Numero Vente</th>
                                            <th class="center hidden-phone">Total Vente</th> --}}
                                            <th class="center hidden-phone">Total Payé</th>
                                            <th class="center hidden-phone">Restant</th>
                                            <th class="center hidden-phone">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody class="center hidden-phone">
                                            @if (isset($reglements) && count($reglements) > 0)
                                                @foreach ($reglements as $index => $item)
                                                    <tr style="color: {{$item->solde > 0 ? 'red' : 'black'}}">
                                                        <td>{{ $item->date }}</td>
                                                        <td>{{ $item->nom }}</td>
                                                        <td>{{ $item->contact }}</td>{{--
                                                        <td>{{ $item->numero }}</td>
                                                        <td class="prix">{{ $item->totaux }}</td> --}}
                                                        <td class="prix">{{ $item->donner }}</td>
                                                        <td class="prix" >{{ $item->solde}}</td>
                                                        <td>
                                                            <a class="btn btn-info" href="/reglementlist-{{ $item->venteId }}"> <i class="fa fa-arrow-right"></i>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>

                                </div>
                                <div id="debiteurs" class="tab-pane ">
                                    <div class="row">
                                    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <div class="form-group">
                                            <h3 class="">Total: <strong id="" class="">{{ $total[0]->solde }}</strong></h3>
                                            <h3> Clients Débiteurs  <a class="modal-with-form btn btn-default mb-xs mt-xs mr-xs btn btn-primary" id="btnclient"><i class="fa fa-plus"></i></a>
                                            </h3>
                                        </div>
                                    </div>
                                    </div>
                                <br>
                                    <table class="table table-bordered table-striped mb-none" id="debiteurTable" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
                                        <thead>
                                        <tr>
                                            <th class="center hidden-phone">Nom</th>
                                            <th class="center hidden-phone">Contact</th>
                                            <th class="center hidden-phone">Adresse</th>
                                            <th class="center hidden-phone">Montant</th>
                                        </tr>
                                        </thead>
                                        <tbody class="center hidden-phone">


                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>


                    </div>
            </div>
        </section>
    </div>

    <div class="modal fade " id="ajout_reglement" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">

                                    <div class="modal-header " style="background-color: #0b93d5;border-top-left-radius: inherit;border-top-right-radius: inherit">
                                        <h4 class="modal-title-user" id="myModalLabel" style="color: white"></h4>
                                    </div>
                                    <div class="modal-body">
                                        <form id="form" action="" method="POST" class="	form-validate form-horizontal mb-lg" enctype="multipart/form-data">
                                            {{csrf_field()}}
                                            <div class="form-group mt-lg">
                                                <label class="col-sm-3 control-label">Client</label>
                                                <div class="col-sm-9">
                                                    <select  name="client" id="client"   class="form-control populate">
                                                        <optgroup label="Choisir une categorie">
                                                            <option value=""></option>
                                                            @foreach($clients as $cli)
                                                                <option value="{{$cli->id}}">{{$cli->nom}}</option>
                                                            @endforeach
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group mt-lg">
                                                <label class="col-sm-3 control-label">Total a payer</label>
                                                <div class="col-sm-9">
                                                    <input type="number" name="total" id="total" class="form-control"  readonly="readonly" required/>
                                                    <input type="hidden" name="idreglement" id="idreglement"/>
                                                    <input type="hidden" name="idvente" id="idvente"/>
                                                    <input type="hidden" name="reste" id="reste"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Montant  payer</label>
                                                <div class="col-sm-9">
                                                    <input type="number" name="donne" id="donne" class="form-control"  required/>
                                                </div>
                                            </div>
                                            <div class="form-group mt-lg">
                                                <label class="col-sm-3 control-label" id="te"></label>
                                                <div class="col-sm-9">
                                                    <input type="number" name="restant" id="restant" class="form-control"  readonly="readonly" required/>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <div class="col-md-12 text-right">
                                                    <button type="submit" class="btn btn-primary" id="btnadd"><i class="fa fa-check"></i> Valider</button>
                                                    <button type="button" class="mb-xs mt-xs mr-xs btn btn-default  " data-dismiss="modal"><i class="fa fa-times"></i> Annuler</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
    <div class="modal fade " id="ajout_client" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header " style="background-color: #0b93d5;border-top-left-radius: inherit;border-top-right-radius: inherit">
                    <h4 class="modal-title-user" id="myModalLabel" style="color: white"></h4>
                </div>
                <div class="modal-body">
                    <form id="form" action="" method="POST" class="	form-validate form-horizontal mb-lg" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="form-group mt-lg">
                            <label class="col-sm-3 control-label">Nom/Raison sociale</label>
                            <div class="col-sm-9">
                                <input type="text" name="nom"  id="nom" class="form-control" placeholder=" ATO Kodjo, BTD Construction" required/>
                                <input type="hidden" name="idclient" id="idclient"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Email</label>
                            <div class="col-sm-9">
                                <input type="email" name="email" id="email" class="form-control" placeholder="aaaa@aa.com " />
                            </div>
                        </div>
                        <div class="form-group mt-lg">
                            <label class="col-sm-3 control-label">Contact</label>
                            <div class="col-sm-9">
                                <input type="integer" name="contact" id="contact" class="form-control" placeholder="92658797"/>
                            </div>
                        </div>
                        <div class="form-group mt-lg">
                            <label class="col-sm-3 control-label">Adresse</label>
                            <div class="col-sm-9">
                                <input type="integer" name="adresse" id="adresse" class="form-control" placeholder="Adidogome, Lome"/>
                            </div>
                        </div>
                        <div class="form-group mt-lg">
                            <label class="col-sm-3 control-label">Dette</label>
                            <div class="col-sm-9">
                                <input type="integer" name="solde" id="solde" class="form-control" placeholder="Adidogome, Lome"/>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-primary" id="btnadd"><i class="fa fa-check"></i> Valider</button>
                                <button type="button" class="mb-xs mt-xs mr-xs btn btn-default  " data-dismiss="modal"><i class="fa fa-times"></i> Annuler</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="js/addReglementClient.js"></script>
    <script src="octopus/assets/vendor/jquery/jquery.js"></script>
    <script src="octopus/assets/vendor/bootstrap/js/bootstrap.js"></script>
    <script src="octopus/assets/vendor/nanoscroller/nanoscroller.js"></script>
    <script src="octopus/assets/vendor/jquery-datatables/media/js/jquery.dataTables.js"></script>
    <script src="octopus/assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
    <script src="octopus/assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>

    <script >
                    $('#btnclient').on('click', function(){
                    $('.modal-title-user').text('ENREGISTREMENT DU CLIENT (Particulier/Entreprise');
                    $('#btnadd').text('Valider');
                    $('#btnadd').removeClass('btn-warning');
                    $('#btnadd').addClass('btn-primary');
                    $('#idclient').val(null);
                    $('#nom').val(null);
                    $('#email').val(null);
                    $('#contact').val(null);
                    $('#adresse').val(null);
                    $('#solde').val(null);

                    $('#ajout_client').modal('show');
                });

                    //post des données
                    $('#ajout_client  form').on('submit', function (e) {

                        let url,message;
                        if (!$('#idclient').val()){
                            url = '/ajoutclient'
                            message = 'Client enregistré'


                        }
                        else{
                            url = '/updateclient'
                            message = 'Client modifié'

                        }
                        e.preventDefault();
                        if (e.isDefaultPrevented()){
                            $.ajax({
                                url : url ,
                                type : "post",
                                // data : $('#modal-form-user').serialize(),
                                data: new FormData($("#ajout_client form")[0]),
                                //data: new FormData($("#modal-form-user")[0]),
                                contentType: false,
                                processData: false,
                                success : function(data) {

                                    $('#ajout_client').modal('hide');
                                    sweetToast('success',message);
                                    window.location='/reglementlist'

                                },
                                error : function(data){
                                alert('erreur')
                                }
                            });
                        }
                    });

                                $('#btnreglement').on('click', function(){

                                    $('.modal-title-user').text('ENREGISTREMENT DU REGLEMENT');
                                    $('#idreglement').val(null);
                                    $('#client').val(null);
                                    $('#btnadd').text('Valider');
                                    $('#btnadd').removeClass('btn-warning');
                                    $('#btnadd').addClass('btn-primary');
                                    $('#total').val(null);
                                    $('#donne').val(null);
                                    $('#restant').val(null);
                                    $('#ajout_reglement').modal('show');
                                    });

                                //post des données
                                $('#ajout_reglement  form').on('submit', function (e) {

                                    let url,message;
                                    if (!$('#idreglement').val()){
                                        url = '/storereglement'
                                        message = 'reglement enregistré'


                                    }
                                    else{
                                        url = '/updatereglement'
                                        message = 'reglement modifié'

                                    }
                                    e.preventDefault();
                                    if (e.isDefaultPrevented()){
                                        $.ajax({
                                            url : url ,
                                            type : "post",
                                            // data : $('#modal-form-user').serialize(),
                                            data: new FormData($("#ajout_reglement form")[0]),
                                            //data: new FormData($("#modal-form-user")[0]),
                                            contentType: false,
                                            processData: false,
                                            success : function(data) {

                                                $('#ajout_reglement').modal('hide');
                                                sweetToast('success',message);

                                            //    reglementTable.ajax.reload();
                                            window.location.reload();
                                            },
                                            error : function(data){
                                            alert('erreur')
                                            }
                                        });
                                    }
                                });
                                //post des données


    </script>

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

        setNumeralHtml("prix", "0,0", "");
    </script>
@endsection
