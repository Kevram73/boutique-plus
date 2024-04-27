@extends('layout')
@section('contenu')
    <div class="inner-wrapper">
        <section role="main" class="content-body">
            <header class="page-header">
                <h2>Historique des ventes</h2>
            </header>

            <div class="row"  id="inventaire">
                <div class="row">
                    <div class="col-md-4 form-group">
                    <label class="col-md-4 control-label">Boutique</label>
                    <div class="col-md-9 form-group">
                        <select  name="boutique" id="boutique"  class="form-control populate">
                            <optgroup label="Choisir la boutique ">
                                <option value=""></option>
                                @foreach($boutiques as $boutique)
                                    <option value="{{$boutique->id}}">{{$boutique->nom}}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                </div>
                    <div class="col-md-4 form-group">
                        <label class="col-md-4 control-label">Afficher</label>
                        <div class="col-md-9 form-group">
                            <select  name="choix" id="choix"  class="form-control populate">
                                <optgroup label="Choisir ">
                                    <option value=""></option>
                                    <option value="jour">Par jour</option>
                                    <option value="mois">Par mois</option>
                                    <option value="an">Par année</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 form-group" id="jr" style="display: none">
                        <label class="col-md-4 control-label">Jour</label>
                        <div class="col-md-9 form-group">
                            <select  name="jour" id="jour"  class="form-control populate">
                                <optgroup label="Choisir le jour">
                                    <option value=""></option>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 form-group" id="an" style="display: none">
                        <label class="col-md-4 control-label">Annee</label>
                        <div class="col-md-9 form-group">
                            <select  name="annee" id="annee"  class="form-control populate">
                                <optgroup label="Choisir le mois">
                                    <option value=""></option>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 form-group" id="moi" style="display: none">
                        <label class="col-md-4 control-label">Mois</label>
                        <div class="col-md-9 form-group">
                            <select  name="mois" id="mois"  class="form-control populate">
                                <optgroup label="Choisir le mois">
                                    <option value=""></option>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 form-group"  id="depense" style="display: none " >
                        <label class="col-md-4 control-label">Total</label>
                        <div class="col-sm-9">
                            <input type="number" name="depenses" id="depenses" class="form-control" readonly/>
                        </div>
                    </div>

                </div>
                <section class="panel">
                    <header class="panel-heading">
                        <div class="panel-actions">
                            <a href="#" class="fa fa-caret-down"></a>
                        </div>

                        <h1 class="panel-title">LISTES DES  VENTES</h1>
                    </header>

                    <div class="panel-body">
                        <table class="table table-bordered table-striped mb-none" id="achatTable">
                            <thead> 
                            <tr>
                                <th class="center hidden-phone">Vente</th>
                                <th class="center hidden-phone">Montant</th>
                                <th class="center hidden-phone">Action</th>
                            </tr>
                            </thead>
                            <tbody class="center hidden-phone">


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
    <script src="js/adminhistoriquevente.js"></script>

@endsection
