@extends('layout')
@section('css')
<link rel="stylesheet" type="text/css"
      href="DataTables/datatables.min.css"/>
<script src="https://unpkg.com/htmx.org@1.8.5"></script>
@endsection

@section('contenu')

    <div class="inner-wrapper">
        <section role="main" class="content-body">
            <header class="page-header">
                <h2>Régularisation de l'inventaire</h2>
            </header>
            <div class="modal fade " id="inventaireRegulateModal"
                 tabindex="-1"
                 role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">

                        <div class="modal-header " style="background-color: #0b93d5;border-top-left-radius: inherit;border-top-right-radius: inherit">
                            <h4 class="modal-title-user" id="myModalLabel" style="color: white">
                                    FORMULAIRE DE REGULARISATION.
                            </h4>
                        </div>
                        <div class="modal-body">
                            <form
                                id="form"
                                  action="/inventaire_pay"
                                  method="POST"
                                  class="form-validate form-horizontal mb-lg"
                                enctype="multipart/form-data">
                                {{csrf_field()}}
                                <div class="form-group mt-lg">
                                <label class="col-sm-3 control-label">DEBITEUR</label>
                                <div class="col-sm-9">
                                <select  name="fournisseur" id="fournisseur"   class="form-control populate">
                                    <optgroup label="Choisir un DEBITEUR">
                                        <option value=""></option>
                                        @foreach($result as $cli)
                                            <option value="{{$cli->id}}">{{$cli->nom}}</option>
                                        @endforeach
                                    </optgroup>
                                 </select>
                                 <input type="hidden" value="{{$result[0]->id}}" name="debtor_id"/>
                                 <input type="hidden" value="{{$id}}" name="inv_id"/>

                                </div></div>
                                <div class="form-group mt-lg">
                                    <label class="col-sm-3 control-label">Total à payer</label>
                                    <div class="col-sm-9">
                                        <input type="number" name="total" id="total"  class="form-control"  readonly="readonly" required/>
                                        <input type="hidden" name="idreglement" id="idreglement"/>
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
                                    <label class="col-sm-3 control-label" id="te">Restant</label>
                                    <div class="col-sm-9">
                                        <input type="number" name="restant" id="restant" class="form-control"  readonly="readonly" required/>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="col-md-12 text-right">
                                        <button type="submit" class="btn btn-primary" id="btnadd"
                                          hx-confirm="Confirmer ?"
                                        ><i class="fa fa-check"></i> Valider</button>
                                        <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger  " data-dismiss="modal"><i class="fa fa-times"></i> Annuler</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row"  id="inventaire">
                <input type="hidden" name="cat_id" id="cat_id" value="{{ $data->categorie_id }}">
                <input type="hidden" name="_id" id="_id" value="{{ $data->id }}">

                <section class="panel">
                    <header class="panel-heading">
                        <div class="panel-actions">
                            <a href="#" class="fa fa-caret-down"></a>
                        </div>

                        <h1 class="panel-title">Régularisations</h1>
                    </header>

                    <div class="panel-body">
                        <a type="button"  class="btn btn-info" data-toggle="modal" data-target="#inventaireRegulateModal"> Faire une Régularisation </a>

                        <table class="table table-bordered table-striped mb-none" id="regulatedTable" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
                            <thead>
                            <tr>
                                <th class="center hidden-phone">N°</th>
                                <th class="center hidden-phone">Code Inventaire</th>
                                <th class="center hidden-phone">Nom et prénom</th>
                                <th class="center hidden-phone">Montant à rembourser</th>
                                <th class="center hidden-phone">Montant déja rembourser</th>
                                <th class="center hidden-phone">Restant à rembourser</th>
                            </tr>
                            </thead>
                            <tbody class="center hidden-phone">
                            @php
                            $i = 1 ;
                            @endphp
                            @isset($inventaire)
                                
                            @foreach($inventaire as $re)
                                <tr>

                                    <td>{{$i++}}</td>
                                    <td>
                                        {{$re->numero}}
                                    </td>
                                    <td>
                                        {{$re->nom}} {{$re->prenom}}
                                    </td>
                                    <td>
                                        {{$re->dette}}
                                    </td>
                                    <td>
                                        {{$re->montant_rembourser}}
                                    </td>
                                    <td>
                                        {{$re->solde}}
                                    </td>

                                </tr>
                            @endforeach

                            @endisset


                            </tbody>
                        </table>
                    </div>

                </section>

            </div>

            <a class=" btn btn-default mb-xs mt-xs mr-xs btn btn-danger"
               onclick="history.back()"
               ><i class="fa fa-step-backward"></i> &nbsp; Retour</a>
        </section>
    </div>
@endsection
@section('js')
    <script src="/octopus/assets/vendor/jquery/jquery.js"></script>
    <script src="/octopus/assets/vendor/bootstrap/js/bootstrap.js"></script>
    <script src="/octopus/assets/vendor/nanoscroller/nanoscroller.js"></script>
    <script src="/octopus/assets/vendor/jquery-datatables/media/js/jquery.dataTables.js"></script>
    <script src="/octopus/assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
    <script src="/octopus/assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
<script type="application/javascript">



</script>
<script>
    
    $('#fournisseur').on('change',function ( ) {
       console.log('lsuds');
   $('#total').empty();
   $.ajax({
       url: '/restantinventairedebitor-'+$('#fournisseur').val(),
       type: "get",
       success: function (data) {
           $('#total').empty();
           //console.log(data.total)
           $('#total').val(data);
       },
       error: function (data) {
           console.log("erreur")
       },
   })
})
$('#donne').on('value change',function ( ) {
   $('#restant').val($('#total').val()-$('#donne').val());
   if ( $('#restant').val()>0) {
       $('#te').text('Restant');
       $('#reste').val(1);

   }
   if ( $('#restant').val()<0) {
       $('#te').text('Monnaie');
       $('#restant').val(   $('#restant').val()*-1);
       $('#reste').val(0);
   }
})
</script>

<script>
        

    $(document).ready(function(){
        $('#regulatedTable').DataTable({
            "order": [[ 0, "desc" ]],
            "pageLength":10,
            "oLanguage": {
                
                "sProcessing":     "Traitement en cours...",
                "sSearch":         "Rechercher&nbsp;:",
                "sLengthMenu":     "Afficher _MENU_ &eacute;l&eacute;ments",
                "sInfo":           "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
                "sInfoEmpty":      "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
                "sInfoFiltered":   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
                "sInfoPostFix":    "",
                "sLoadingRecords": "Chargement en cours...",
                "sZeroRecords":    "Aucun &eacute;l&eacute;ment &agrave; afficher",
                "sEmptyTable":     "Aucune donn&eacute;e disponible dans le tableau",
                "oPaginate": {
                    "sFirst":      "Premier",
                    "sPrevious":   "Pr&eacute;c&eacute;dent",
                    "sNext":       "Suivant",
                    "sLast":       "Dernier"
                },
                
                "oAria": {
                    "sSortAscending":  ": activer pour trier la colonne par ordre croissant",
                    "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
                }
            }
        });
    });
    $(document).ready(function() {
        $('#regulatedTable').DataTable();
        } );
            var modal = $('.Recherche');
            $('.logo').click(function() {
                modal.show();
            });
</script>
@endsection
