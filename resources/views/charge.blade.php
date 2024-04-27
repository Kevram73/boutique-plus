@extends('layout')
@section('css')
    <link rel="stylesheet" href="octopus/assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
@endsection
@section('contenu')
    <div class="inner-wrapper">
        <section role="main" class="content-body">
            <header class="page-header">
                <h2>Charge</h2>
            </header>

            <div class="row">
                <section class="panel">
                    <header class="panel-heading">
                        <div class="panel-actions">
                            <a href="#" class="fa fa-caret-down"></a>
                        </div>

                        <h1 class="panel-title">LISTES DES CHARGES</h1>
                    </header>

                    <div class="panel-body">
                        <a class="modal-with-form btn btn-default mb-xs mt-xs mr-xs btn btn-default" id="btncharge"><i class="fa fa-plus"></i>Ajouter une charge</a>
                        <a class="btn btn-default mb-xs mt-xs mr-xs btn btn-success" id="btnhistorique"><i class="fa fa-file-text"></i>Historiques</a>
                        <a class="btn btn-default mb-xs mt-xs mr-xs btn btn-danger" id ="btnjournal"><i class="fa fa-file-text"></i>Fermer le journal</a>
                        <div class="modal fade " id="ajout_charge" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">

                                    <div class="modal-header " style="background-color: #0b93d5;border-top-left-radius: inherit;border-top-right-radius: inherit">
                                        <h4 class="modal-title-user" id="myModalLabel" style="color: white"></h4>
                                    </div>
                                    <div class="modal-body">
                                        <form id="form" action="" method="POST" class="	form-validate form-horizontal mb-lg" enctype="multipart/form-data">
                                            {{csrf_field()}}
                                            <div  class="form-group mt-lg">
                                                <label class="col-sm-3 control-label">Type de charge</label>
                                                <div class="col-sm-9 ">
                                                    <select  name="type" id="type"  class="form-control populate">
                                                        <optgroup label="Choisir ">
                                                            <option value=""></option>
                                                            <option value="loyer">Loyer</option>
                                                            <option value="salaire" >Salaire</option>
                                                            <option value="impots">Impots et taxes</option>
                                                            <option value="facture">Factures</option>
                                                            <option value="autres">Autres charges</option>
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group mt-lg">
                                                <label class="col-sm-3 control-label">Libellé</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="libelle"  id="libelle" class="form-control" placeholder="Achat de......" required/>
                                                    <input type="hidden" name="idcharge" id="idcharge"/>
                                                </div>
                                            </div>
                                            <div class="form-group mt-lg">
                                                <label class="col-sm-3 control-label">Montant</label>
                                                <div class="col-sm-9">
                                                    <input type="number" name="montant" id="montant" class="form-control" placeholder="" min="0" required/>
                                                </div>
                                            </div>
                                            <div  class="form-group mt-lg">
                                                <label class="col-sm-3 control-label">COMPTES</label>
                                                <div class="col-sm-9 ">
                                                    <select  name="compte" id="compte"  class="form-control populate">
                                                        <optgroup label="Choisir ">
                                                            @foreach ($banques as $banque)
                                                                
                                                            <option value="{{ $banque->id }}">{{ $banque->banques }} - {{ $banque->numero }}</option>
                                                            @endforeach
                                                        </optgroup>
                                                    </select>
                                                    <input type="hidden" class="form-control" name="solde" id="solde">

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
                        <table class="table table-bordered table-striped mb-none" id="chargeTable" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
                            <thead>
                            <tr>
                                <th class="center hidden-phone">Type</th>
                                <th class="center hidden-phone">Libellé</th>
                                <th class="center hidden-phone">Montant</th>
                                <th class="center hidden-phone">Date </th>
                                <th class="center hidden-phone">Statut </th>
                                <th class="center hidden-phone">Action </th>
                            </tr>
                            </thead>
                            <tbody class="center hidden-phone">


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
    <script src="js/charge.js"></script>

@endsection
