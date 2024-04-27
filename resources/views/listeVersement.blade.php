@extends('layout')

@section('css')
    <link rel="stylesheet" href="octopus/assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
    <link rel="stylesheet" href="/vendor/select/css/select2.min.css" />
    <style>
        .select2-container {
            width: 300px;
            }
    </style>
@endsection
@section('contenu')
    <div class="inner-wrapper">
        <section role="main" class="content-body">
            <header class="page-header">
                <h2>Versements</h2>
            </header> 

            <div class="row"  id="inventaire">
              
                <section class="panel">
                    <header class="panel-heading">
                        <div class="panel-actions">
                            <a href="#" class="fa fa-caret-down"></a>
                        </div>

                        <h1 class="panel-title">Liste des Versements </h1>
                    </header>
 
                    <div class="panel-body">

                        <div class="tabs">
                        <ul class="nav nav-tabs nav-justified">
                            <li class="active">
                                <a href="#reglements" data-toggle="tab" class="text-center" style="font-size: 2rem;"><i class="fa fa-star"></i> Liste des Versements</a>
                            </li>
                            <li>
                                <a href="#debiteurs" data-toggle="tab" class="text-center text-warning" style="font-size: 2rem;">A valider</a>
                            </li>
                        </ul>
                      <div class="tab-content">

                        <div id="reglements" class="tab-pane active">
                            @if (\Session::has('success'))
                            <div class="alert alert-success">
                                <ul>
                                    <li>{!! \Session::get('success') !!}</li>
                                </ul>
                            </div>
                        @endif
    
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-8">
                                    
                                    <a class="modal-with-form btn btn-default mb-xs mt-xs mr-xs btn btn-default" id="btncharge" href="{{ url('depotversement') }}"><i class="fa fa-plus"></i> Faire un versement</a>
                                   {{-- <a class="modal-with-form btn btn-default mb-xs mt-xs mr-xs btn btn-default" id="btndepot"><i class="fa fa-arrow-left" aria-hidden="true"></i> Faire un dépôt</a>
                                    --}}
    
                                </div>
                                <div class="col-md-4 text-center">
{{--                                     <h3 >SOLD : <strong class="prix" id="sold">0</strong></h3>
 --}}                              

                                    </div>
                            </div>
    
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
                                                    <label class="col-sm-3 control-label">Bénéficière *</label>
                                                    <div class="col-sm-9">
                                                    <input type="text" name="name"  id="name" class="form-control" placeholder="Nom & Prénoms" required/>
                                                    </div>
                                                </div>
                                                <div class="form-group mt-lg">
                                                    <label class="col-sm-3 control-label">Monatant*</label>
                                                    <div class="col-sm-9">
                                                        <input type="number" name="montant"  id="montant" class="form-control" placeholder="000 000 000" required/>
                                                        <input type="hidden" name="idcharge" id="idcharge"/>
                                                        <input type="hidden" name="sold_id" id="sold_id" value="" />
                                                    </div>
                                                </div>
                                                <div class="form-group mt-lg">
                                                    <label class="col-sm-3 control-label">Date * </label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="date"  id="date" class="form-control date" placeholder="" required/>
                                                    </div>
                                                </div>
                                                <div class="form-group mt-lg">
                                                    <label class="col-sm-3 control-label">Motif *</label>
                                                    <div class="col-sm-9">
                                                        <textarea name="motif" id="motif" cols="30" rows="5" class="form-control"></textarea>
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
                            <table class="table table-bordered table-striped mb-none" id="versementTable" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
                                <thead>
                                <tr>
                                    <th class="center hidden-phone">Nom & Prénom</th>
                                    <th class="center hidden-phone">Montant</th>
                                    <th class="center hidden-phone">Date</th>
                                    <th class="center hidden-phone">Motifs</th>
                                    <th class="center hidden-phone">Jusifier</th>
                                    <th class="center hidden-phone">Action </th>
                                </tr>
                                </thead>
                                <tbody class="center hidden-phone">
    
    
                                </tbody>
                            </table>
                        </div>
                        </div>

                        <div id="debiteurs" class="tab-pane">
                            <div class="row">
                             
                                <div class="">
                                    <section class="panel">
                                        <div class="panel-body">
                                        
                                       
                                            <table class="table table-bordered table-striped mb-none" id="ValidationVersementTable" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
                                                <thead>
                                                <tr>
                                                    <th class="center hidden-phone">DATE </th>
                                                    <th class="center hidden-phone">Compte </th>
                                                    <th class="center hidden-phone">Montant</th>
                                                    <th class="center hidden-phone">Utilisateur </th>
                                                    <th class="center hidden-phone">Nature</th> 
                                                    <th class="center hidden-phone">Description </th>
                                                    <th class="center hidden-phone">Valider</th>
                                                </tr>
                                                </thead>
                                                <tbody class="center hidden-phone">
                
                
                                                </tbody>
                                            </table>
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>

                      </div>
                        </div>

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
<script src="/vendor/select/js/select2.full.min.js"></script>

    <script src="js/versement.js"></script>

@endsection
