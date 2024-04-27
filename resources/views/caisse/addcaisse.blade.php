@extends('layout')
@section('css')
    <link rel="stylesheet" href="octopus/assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
@endsection
@section('contenu')
    <div class="inner-wrapper">
        <section role="main" class="content-body">
            <header class="page-header">
                <h2>Caisse</h2>
            </header>

            <div class="row">
                <section class="panel">
                    <header class="panel-heading"> 
                        <div class="panel-actions">
                            <a href="#" class="fa fa-caret-down"></a>
                        </div>

                        <h1 class="panel-title">{{ $boutiques[0]->nom }} - {{ $boutiques[0]->adresse }} - {{ $boutiques[0]->telephone }}</h1>
                     </header>

                    <div class="panel-body">
                        <div class="tabs">

                            <div class="tab-content">
                                <div id="reglements" class="tab-pane active">
                                  
                                
                                                <div class="modal-header " style="background-color: #0b93d5;border-top-left-radius: inherit;border-top-right-radius: inherit">
                                                    <h4 class="modal-title-user" id="myModalLabel" style="color: white"></h4>
                                                </div>    @if (\Session::has('success'))
                                                <div class="alert alert-success">
                                                    <ul>
                                                        <li>{!! \Session::get('success') !!}</li>
                                                    </ul>
                                                </div>
                                            @endif
                                                <div class="modal-body">
                                                    <form id="form" action="/storecaisse"  method="POST" class="form-validate form-horizontal mb-lg" enctype="multipart/form-data">
                                                        {{csrf_field()}}
                                                        <div class="form-group mt-lg">
                                                            <label class="col-sm-3 control-label">Boutique</label>
                                                            <div class="col-sm-9">
{{--                                                                 <input type="text" name="boutique_id" readonly id="boutique_id" value="{{ $boutiques[0]->nom }}" class="form-control"  required/>
 --}}                                    
                                                                    <select class="form-group">
                                                                        <optgroup>
                                                                            <option></option>
                                                                            <option value="{{ $boutiques[0]->id }}" name="boutique_id" id="boutique_id" selected >{{ $boutiques[0]->nom }}</option>
                                                                        </optgroup>
                                                                    </select>
                                                            </div>
                                                        </div>
                                                     
                                                      
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label">Ventes  </label>
                                                            <div class="col-sm-9">
                                                                <input type="number" name="totalVente" id="totalVente" value="{{ $globalbybouventeglobal }}" readonly class="form-control"  required/>
                                                            </div>
                                                        </div>
                                                          <div class="form-group">
                                                            <label class="col-sm-3 control-label">Avoirs  </label>
                                                            <div class="col-sm-9">
                                                                <input type="number" name="totalAvoir" id="totalAvoir" value="{{ $globalbyboutiqAvoir }}" readonly class="form-control"  required/>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label">Remise  </label>
                                                            <div class="col-sm-9">
                                                                <input type="number" name="remise" id="remise" readonly class="form-control"  value="{{ $globalbybouremiseglobal }}"  required/>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label">Vente nette  </label>
                                                            <div class="col-sm-9">
                                                                <input type="number" name="ventenette" id="ventenette" readonly class="form-control"  value="{{ $venteNette }}"  required/>
                                                            </div>
                                                        </div>
                                    
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label">Créance  </label>
                                                            <div class="col-sm-9">
                                                                <input type="number" name="venteCredit" id="venteCredit" readonly class="form-control"  value="{{ $globalbyboucredit }}"  required/>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label">Recouvrement Intérieur  </label>
                                                            <div class="col-sm-9">
                                                                <input type="number" name="recouvrementInte" id="recouvrementInte" readonly class="form-control"  value="{{ $recouvrementInterieur }}"  required/>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label">Avance Client </label>
                                                            <div class="col-sm-9">
                                                                <input type="number" name="ventenonlivre" id="ventenonlivre" readonly class="form-control"  value="{{ $globalbybounonlivret }}"  required/>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label">Total Dépense </label>
                                                            <div class="col-sm-9">
                                                                <input type="number" name="totalDepense" id="totalDepense" readonly class="form-control"  value="{{ $TOTALdepense }}"  required/>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label">Recette total  </label>
                                                            <div class="col-sm-9">
                                                                <input type="number" name="recetteTotal" id="recetteTotal" readonly class="form-control"  value="{{ $recetteTotale }}"   required/>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label">Montant Collecté  </label>
                                                            <div class="col-sm-9">
                                                                <input type="number" name="montantcollecte" id="montantcollecte" readonly  class="form-control"  value="{{ $totalmontant }}"  required/>
                                                                <a  class="modal-with-form btn btn-default mb-xs mt-xs mr-xs btn btn-default"  href="/addBulling">Ajouter Billetage </a>

                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label">Solde veille  </label>
                                                            <div class="col-sm-9">
                                                                <input type="number" name="solde" id="solde" readonly class="form-control"  value="{{ $dernieresolde }}" />
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label">Solde Magasin  </label>
                                                            <div class="col-sm-9">
                                                                <input type="number" name="soldeMagasin" id="soldeMagasin" readonly class="form-control"  value="{{ $soldemagasin }}" />
                                                            </div>
                                                        </div>
                                                        <div class="form-group mt-lg">
                                                            <label class="col-sm-3 control-label">Date * </label>
                                                            <div class="col-sm-9">
                                                                <input type="text" name="date"  id="date" readonly class="form-control date" placeholder=""  value="{{ $date }}"  required/>
                                    
                                                            </div>
                                                        </div>
                                                       
                                                     {{--    <div class="form-group mt-lg">
                                                            <label class="col-sm-3 control-label">solde Magasin * </label>
                                                            <div class="col-sm-9">
                                                                <input type="text" name="soldeMagasin"  id="soldeMagasin" readonly class="form-control" placeholder="" required/>
                                    
                                                            </div>
                                                        </div> --}}
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
                        </div>

                       

                    </div>
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
    <script type="text/javascript" src="/vendor/daterangepicker/moment.min.js"></script>
    <script type="text/javascript" src="/vendor/daterangepicker/daterangepicker.js"></script>
    <script>
        function sweetToast(type,text){
    return  Swal.fire({
        position: 'top-end',
        icon: type,
        title: text,
        showConfirmButton: false,
        timer: 1500,
        animation : true,
    });
}
      
    </script>
    <script>
  
        $(document).ready(function(){
            $('#situationTable').DataTable({
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
            $('#situationTable').DataTable();
            } );
                var modal = $('.Recherche');
                $('.logo').click(function() {
                    modal.show();
                });
    </script>
@endsection
