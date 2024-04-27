@extends('layout')
@section('css')
<link rel="stylesheet" href="octopus/assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
@endsection
@section('contenu')
    <div class="inner-wrapper">
        <section role="main" class="content-body">
            <header class="page-header">
                <h2>Inventaire</h2>
            </header>

            <div class="row">
                <section class="panel">
                    <header class="panel-heading">
                        <div class="panel-actions">
                            <a href="#" class="fa fa-caret-down"></a>
                        </div>

                        <h1 class="panel-title">LISTES DES  INVENTAIRES</h1>
                    </header>

                    <div class="panel-body">
                        <!--  Inventaire category-->
                        <div class="col-md-18">
                            <div class="tabs">
                                <!-- Tables types -->
                                <ul class="nav nav-tabs nav-justified">
                                    <li class="active">
                                        <a href="#inventaire_pending" data-toggle="tab" class="text-center"><i class="fa fa-star"></i> EN COURS</a>

                                    </li>
                                    <li>
                                        <a href="#inventaire_valider" data-toggle="tab" class="text-center">VALIDER</a>
                                    </li>
                                    <li>
                                        <a href="#inventaire_regularistion" data-toggle="tab" class="text-center">RÉGULARISATION INVENTAIRE</a>
                                    </li>
                                </ul>


                                <!-- Body of tables -->
                                <div class="tab-content">
                                    <!-- Inventaire pending table -->
                                    <div id="inventaire_pending" class="tab-pane active">
                                        <a class="modal-with-form btn btn-default mb-xs mt-xs mr-xs btn btn-default" id="btnAddInventaire"><i class="fa fa-plus"></i>Ajouter un inventaire</a>
                                      <!-- a href="{{ route('ajouterinventaire') }}">Ajouter un inventaire</a-->
                                        <table class="table table-bordered table-striped mb-none" id="inventaireTablePending" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
                                            <thead>
                                            <tr>
                                                <th class="center hidden-phone">Numero</th>
                                                <th class="center hidden-phone">Date et Heure</th>
                                                <th class="center hidden-phone">Date Prevu</th>
                                                <th class="center hidden-phone">Utilisateur</th>
                                                <th class="center hidden-phone">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody class="center hidden-phone">


                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Inventaire valider -->
                                    <div id="inventaire_valider" class="tab-pane ">
                                        {{-- <a class=" btn btn-default mb-xs mt-xs mr-xs btn btn-default" id="btninventaire"><i class="fa fa-plus"></i>Faire l'inventaire</a> --}} <br>
                                        <table class="table table-bordered table-striped mb-none" id="inventaireTable" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
                                            <thead>
                                            <tr>
                                                <th class="center hidden-phone">Numero</th>
                                                <th class="center hidden-phone">Date et Heure</th>
                                                <th class="center hidden-phone">Utilisateur</th>
                                                <th class="center hidden-phone">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody class="center hidden-phone">


                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Inventaire regularisation -->
                                    <div id="inventaire_regularistion" class="tab-pane ">
                                        {{-- <a class=" btn btn-default mb-xs mt-xs mr-xs btn btn-default" id="btninventaire"><i class="fa fa-plus"></i>Faire l'inventaire</a> --}} <br>
                                        <table class="table table-bordered table-striped mb-none" id="inventaireTableRegularisation" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
                                            <thead>
                                            <tr>
                                                <th class="center hidden-phone">Code Inventaire</th>
                                                <th class="center hidden-phone">Date</th>
                                                <th class="center hidden-phone">Utilisateur</th>
                                                <th class="center hidden-phone">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody class="center hidden-phone">


                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade "
                             id="ajout_inventaie"
                             tabindex="-1"
                             role="dialog"
                             aria-labelledby="myModalLabel"
                        >
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">

                                    <div class="modal-header " style="background-color: #0b93d5;border-top-left-radius: inherit;border-top-right-radius: inherit">
                                        <h4 class="modal-title-user" id="myModalLabel" style="color: white"></h4>
                                    </div>
                                    <div class="modal-body">
                                        <form id="form" action="" method="POST" class="	form-validate form-horizontal mb-lg" enctype="multipart/form-data">
                                            {{csrf_field()}}
                                            <div class="form-group mt-lg">
                                                <label class="col-sm-3 control-label">Models</label>
                                                <div class="col-sm-9">
                                                    <select  name="choix" id="choix"  class="form-control populate">
                                                        <optgroup label="Choisir ">
                                                            <option value=""></option>
                                                            <option value="tous">Toutes catégories</option>
                                                            <option value="categorie">Par catégorie</option>
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group mt-lg" id="cate" style="display: none">
                                                <label class="col-sm-3 control-label">Catégorie</label>
                                                <div class="col-sm-9">
                                                    <select  name="categorie" id="categorie"  class="form-control populate">
                                                        <optgroup label="Choisir la categorie">
                                                            <option></option>
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Date prévu</label>
                                                <div class="col-sm-9">
                                                    <input type="date" name="date_prev" id="date_prev" class="form-control" required />
                                                </div>
                                            </div>
                                            {{-- <div class="form-group mt-lg">
                                                <label class="col-sm-3 control-label">Contact</label>
                                                <div class="col-sm-9">
                                                    <input type="integer" name="contact" id="contact" class="form-control" placeholder="92658797" />
                                                </div>
                                            </div>
                                            <div class="form-group mt-lg">
                                                <label class="col-sm-3 control-label">Description</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="description" id="description" class="form-control" placeholder="......." />
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
    <script src="js/inventaire.js"></script>

@endsection
