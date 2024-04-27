@extends('layout')
@section('css')
    <link rel="stylesheet" href="octopus/assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
    <link rel="stylesheet" href="/vendor/select/css/select2.min.css" />




@endsection
@section('contenu')
    <div class="inner-wrapper">
        <section role="main" class="content-body">
            <header class="page-header">
                <h2>Livraison</h2>
            </header>

            <div class="row">
                <section class="panel">
                    <header class="panel-heading">
                        <div class="panel-actions">
                            <a href="#" class="fa fa-caret-down"></a>
                        </div>

                        <h1 class="panel-title">ENREGISTREMENT DE LA LIVRAISON</h1>
                    </header>

                    <div class="panel-body">
                        <div class="row" >

                                <div class="col-md-4 form-group">
                                    <label class="col-md-4 control-label">Commandes</label>
                                    <div class="col-md-9 form-group">
                                        <select  name="commande" id="commande"   class="form-control populate">
                                            <optgroup label="Choisir la commande">
                                                <option value=""></option>
                                                @foreach($commande as $com)
                                                    <option value="{{$com->id}}">{{$com->numero}}</option>
                                                @endforeach
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>

                                        <div class="col-md-4 form-group">
                                                <label class="col-sm-4 control-label">Produits</label>
                                            <div class="col-md-9 form-group">
                                                    <select  name="produit" id="produit"   class="form-control populate">
                                                        <optgroup label="Choisir le produit">
                                                            <option value=""></option>
                                                        </optgroup>
                                                    </select>
                                            </div>
                                        </div>

                                            <div class="col-md-4 form-group">
                                                <label class="col-sm-6 control-label">Quantité à Livrer</label>
                                                <div class="col-sm-9">
                                                    <input type="number" name="quant"  id="quant" class="form-control" readonly="readonly" />
                                                </div>
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <label class="col-sm-6 control-label">Quantité Livrée</label>
                                                <div class="col-sm-9">
                                                    <input type="number" name="quantite"  id="quantite" class="form-control" placeholder="100" min="1"  required/>
                                                </div>
                                            </div>
                         </div>

                         {{--  <div class="border-top border-2"></div>--}}

                         <div class="row" > 

                            <div class="col-md-4 form-group">
                                <label class="col-md-4 control-label">Boutiques</label>
                                    <div class="col-md-9 form-group">
                                        <select  name="boutique" id="boutique"   class="form-control populate">
                                            <optgroup label="Choisir la boutique">
                                                <option value=""></option>
                                                        @foreach($boutiques as $boutique)
                                                        <option value="{{$boutique->id}}">{{$boutique->nom}}</option>
                                                        @endforeach
                                            </optgroup>
                                        </select>
                                    </div>
                            </div>

                            <div class="col-md-4 form-group">
                                <label class="col-sm-4 control-label">Produits</label>
                                <div class="col-md-9 form-group">
                                    <select  name="prod" id="prod" class="form-control populate">
                                        <optgroup label="Choisir le produit">
                                            <option value=""></option>
                                        </optgroup>
                                     
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 text-right">
                            <button type="button" class="btn btn-primary" id="ajout1"><i class="fa fa-check"></i> Ajouter</button>
                            <button type="button" class="mb-xs mt-xs mr-xs btn btn-default  "  id="annuler"><i class="fa fa-times"></i> Annuler</button>
                        </div>


                    <div id="livform">
                        <form  method="POST" class="	form-validate form-horizontal mb-lg" >
                            {{csrf_field()}}
                        <input type="hidden"  name="livTable" id="livTable">
                        </form>
                    </div>
                    <table class="table table-bordered table-striped mb-none" id="livraisonTable" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
                        <thead>
                        <tr>
                            <th class="center hidden-phone">Boutiques</th>
                            {{-- <th class="center hidden-phone">Produits à livrer</th> --}}
                            <th class="center hidden-phone">Produit</th>
                            <th class="center hidden-phone">Quantité </th>
                        </tr>
                        </thead>
                        <tbody class="center hidden-phone">

                        </tbody>
                    </table>
                    <a class="btn btn-danger" id="sup" ><i class="fa fa-trash-o" ></i>Supprimer</a>
                    <div class="col-md-12 text-right">
                        <button type="button" class="btn btn-primary" id="valider"><i class="fa fa-check"></i> Valider</button>
                        <button type="button" class="mb-xs mt-xs mr-xs btn btn-default  "  id="annuler"><i class="fa fa-times"></i> Annuler</button>
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

    <script src="js/newlivraison1.js"></script> 

@endsection
