@extends('layout')
@section('css')
    <link rel="stylesheet" href="octopus/assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
@endsection
@section('contenu')
    <div class="inner-wrapper">
        <section role="main" class="content-body">
            <header class="page-header">
                <h2>Inventaire Globale</h2>
            </header>

            <div class="row">
                <section class="panel">
                    <header class="panel-heading">
                        <div class="panel-actions">
                            <a href="#" class="fa fa-caret-down"></a>
                        </div>

                        <h1 class="panel-title">LISTES  DE TOUS  PRODUITS</h1>
                    </header>

                    <div class="panel-body">
                        {{-- <a class="modal-with-form btn btn-default mb-xs mt-xs mr-xs btn btn-default" id="btnboutique"><i class="fa fa-plus"></i>Ajouter tonnes</a>
                          --}}
                         <div class="form-group">
                                <label for="product">Boutique</label>
                                <select id="product" class="form-control">
                                    <option value="0">   </option>
                                    @foreach ($boutiques as $item)
                                        <option value="{{ $item->id }}">{{ $item->nom }} </option>
                                    @endforeach
                                </select>
                            </div>
                        <div class="modal fade " id="ajout_boutique" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">

                                    <div class="modal-header " style="background-color: #0b93d5;border-top-left-radius: inherit;border-top-right-radius: inherit">
                                        <h4 class="modal-title-user" id="myModalLabel" style="color: white"></h4>
                                    </div>
                                    <div class="modal-body">
                                        <form id="form" action="" method="POST" class="	form-validate form-horizontal mb-lg" enctype="multipart/form-data">
                                            {{csrf_field()}}

                                            <div class="form-group mt-lg">
                                                <label class="col-sm-3 control-label">Produit</label>
                                                <div class="col-sm-9">
                                                    <select  name="boutique" id="boutique"  class="form-control populate">
                                                        <optgroup label="Choisir le produit ">
                                                            <option value=""></option>
                                                            @foreach($inventaires as $boutique)
                                                                <option value="{{$boutique->id}}">{{$boutique->libelle}}</option>
                                                            @endforeach
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group mt-lg">
                                                <label class="col-sm-3 control-label">Tonnage</label>
                                                <div class="col-sm-9">
                                                    <input type="integer" name="telephone" id="telephone" class="form-control" placeholder="92658797" required/>
                                                </div>
                                            </div>
                                            <div class="form-group mt-lg">
                                                <label class="col-sm-3 control-label">Prix par tonne</label>
                                                <div class="col-sm-9">
                                                    <input type="number" name="nom"  id="nom" class="form-control" placeholder="LJ" required/>
                                                    <input type="hidden" name="idboutique" id="idboutique"/>
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
                        <table class="table table-bordered table-striped mb-none" id="boutiqueTable" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
                            <thead>
                            <tr>
                                <th class="center hidden-phone">Categorie</th>
                                <th class="center hidden-phone">Produit</th>
                                <th class="center hidden-phone">Modele</th>
                                <th class="center hidden-phone">COÛT D’ACHAT/Condtnmt</th>
                                <th class="center hidden-phone">Condtnmt</th>
                                <th class="center hidden-phone">MAGASIN</th>
                                <th class="center hidden-phone">TOTAL EN
                                    UNITE</th>
                                <th class="center hidden-phone">NOMBRE DE
                                    TONNAGE</th>


                            </tr>
                            </thead>
                            <tbody class="center hidden-phone">

                              {{--   @foreach ($produitsInventaire as $produit)
                                <tr>
                                    <td>{{ $produit->nom }}</td>
                                    <td>{{ $produit->famille }}</td>
                                    <td>{{ $produit->libelle }}</td>
                                    <td>{{ $produit->valeur }}</td>
                                    <td>{{ $produit->qte_tonne }}</td>
                                    <td>{{ $produit->boutique }}</td>
                                    <td>{{ $produit->quantite }}</td>
                                    <td>{{ $produit->nom }}</td>

                                </tr>

                                @endforeach --}}

                            </tbody>
                        </table>
                    </div>
                    <h1 id="result">Valeur total :  F</h1>
            </div>
        </section>
    </div>

    <div class="modal fade" id="detailboutique" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#0b97c4;border-top-left-radius: inherit;border-top-right-radius: inherit">
                    <b>	<h4 class="modal-title" id="modal-user-title" align="center" style="color: white" ></h4></b>

                </div>
                <div class="modal-body">
                    <ul class="list-group">
                        <li class="list-group-item">Nom :<b> <span class="text-danger" id="sNom"></span> </b></li>
                        <li class="list-group-item">Adresse :<b> <span class="text-danger" id="sAdresse"></span> </b></li>
                        <li class="list-group-item">Telephone :<b> <span class="text-danger " id="sTelephone" ></span></b></li>

                        <li class="list-group-item">
                            crée le :<b> <span class="text-danger" id="sCreate"></span></b> </li>

                        <li class="list-group-item">
                            mise a jour le :<b> <span class="text-danger" id="sUpdate"></span></b> </li>
                    </ul>



                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Fermer</button>
                    </div>
                </div>

            </div>

        </div>

    </div>
@endsection
@section('js')

    <script src="octopus/assets/vendor/jquery/jquery.js"></script>
    <script src="octopus/assets/vendor/bootstrap/js/bootstrap.js"></script>
    <script src="octopus/assets/vendor/nanoscroller/nanoscroller.js"></script>
    <script src="octopus/assets/vendor/jquery-datatables/media/js/jquery.dataTables.js"></script>
    <script src="octopus/assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
    <script src="octopus/assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
    <script src="js/inventairesuper.js"></script>
    
<script>



</script>
@endsection
