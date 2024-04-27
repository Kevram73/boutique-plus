@extends('layout')
@section('css')
    <link rel="stylesheet" href="octopus/assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
     <link rel="stylesheet" href="/vendor/select/css/select2.min.css" />
    <style>
      /*  .select2-container {
            width: 700px;
            }*/
    </style>
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

                        <h1 class="panel-title">LISTES DES  LIVRAISONS</h1>
                    </header>


                    <div class="panel-body">
                        <div class="col-md-18">
                            <div class="tabs">
                                <ul class="nav nav-tabs nav-justified">
                                    <li class="active">
                                        <a href="#livraison" data-toggle="tab" class="text-center"><i class="fa fa-star"></i> LIVRAISONS</a>

                                    </li>
                                    <li>
                                        <a href="#livraison_boutique" data-toggle="tab" class="text-center">LIVRAISON / AUTRES BOUTIQUES</a>
                                    </li>
                                     <li>
                                <a href="#debiteurs" data-toggle="tab" class="text-center text-warning" style="font-size: 2rem;">Historique : Livraisons</a>
                            </li>
                                </ul>
                                <div class="tab-content">
                                    <div id="livraison" class="tab-pane active">
                                        <a class="btn btn-default mb-xs mt-xs mr-xs btn btn-default" href="{{route('newlivraison')}}"><i class="fa fa-plus"></i>Ajouter une livraison</a>
                                        <table class="table table-bordered table-striped mb-none" id="livraisonTable" >
                                            <thead>
                                            <tr>
                                                <th class="center hidden-phone">Numero</th>
                                                <th class="center hidden-phone">Date de livraison</th>
                                                <th class="center hidden-phone">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody class="center hidden-phone">

                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="livraison_boutique" class="tab-pane ">
                                        <a class="btn btn-default mb-xs mt-xs mr-xs btn btn-default" href="{{ url('/newlivraisonCentrale') }}"><i class="fa fa-plus"></i>Ajouter une livraison</a>
                                        <form method="POST" action="">
                                            @csrf

                                            <div id="livraison" class="tab-pane active">

                                                <table class="table table-bordered table-striped mb-none" id="livraisonNTableBoutique" >
                                                    <thead>
                                                    <tr>

                                                        <th class="center hidden-phone">Date de livraison</th>
                                                        <th class="center hidden-phone">Livraisons</th>
                                                     <th class="center hidden-phone">Boutique</th>

                                                      {{--   <th class="center hidden-phone">Boutiques</th>--}}
                                                        <th class="center hidden-phone">Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody class="center hidden-phone">


                                                    </tbody>
                                                </table>
                                            </div>

                                        </form>

                                    </div>
                                     <div id="debiteurs" class="tab-pane">
                            <div class="row">
                             
                                <div class="">
                                    <section class="panel">
                                        <div class="panel-body">
                                            <div class="row">

                                                <div class="col-md-4 form-group">
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label" for="fournisseur">Boutique</label>
                                                        <div class="col-md-9 form-group">
                                                        <select id="fournisseur" class="form-control">
                                                            <option value="0" >Toutes les boutiques</option>
                                                           @foreach ($fournisseurs as $fournisseur)
                                                           <option value="{{ $fournisseur->id }}"> {{ $fournisseur->nom }} </option>
                                                           @endforeach
                                                           
                                                        </select>   
                                                        </div>                                                 </div>
                                                </div>
                                                 <div class="col-md-4 form-group">
                                                <label class="col-md-4 control-label">Categorie de produit</label>
                                                <div class="col-md-9 form-group">
                                                    <select  name="categorie" id="categorie"  class="form-control populate">
                                                        <optgroup label="Choisir la categorie">
                                                            <option value=""></option>
                                                            @foreach($categorie as $cat)
                                                                <option value="{{$cat->id}}">{{$cat->nom}}</option>
                                                            @endforeach
                                                        </optgroup>
                                                    </select>
                                                </div>
                                </div>

                                            <div class="col-md-4 form-group">
                                                <label class="col-sm-4 control-label">Famille de produit</label>
                                                <div class="col-md-9 form-group">
                                                    <select  name="modeles" id="modeles"   class="form-control populate">
                                                        <optgroup label="Choisir un produit">
                                                            <option value="" ></option>
                                                            @foreach($produits as $cat)
                                                                <option value="{{$cat->id}}">{{$cat->nom}}</option>
                                                            @endforeach
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            </div>

                                           {{--  <div class="col-md-4 form-group">
                                                <label class="col-sm-4 control-label">Produit</label>
                                                <div class="col-md-9 form-group">
                                                    <select  name="modele" id="modele"  class="form-control populate">
                                                        <optgroup label="Choisir le modele">
                                                            <option value=""></option>
                                                            @foreach($modeles as $cat)
                                                                <option value="{{$cat->id}}">{{$cat->libelle}}</option>
                                                            @endforeach
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            </div> --}}
                                                <div class="col-md-4 form-group">
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label" for="production">Produit</label>
                                                        <div class="col-md-9 form-group">
                                                        <select id="production" name="production" class="form-control populate" >
                                                            <option value="0">   </option>
                                                            
                                                            @foreach ($modele2 as $produits)
                                                            <option value="{{ $produits->id }}"> {{ $produits->libelle }}  </option>
                                                            @endforeach
                                                        </select>                         
                                                        </div>   
                                                                            </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                <div class="form-group">
                                                    <label for="debut">Date Début</label>
                                                    <input type="date" class="form-control" id="debut" placeholder="Date Début">
                                                </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                <div class="form-group">
                                                    <label for="fin">Date Fin</label>
                                                    <input type="date" class="form-control" id="fin" placeholder="Date Fin">
                                                </div></div>
                                                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6"><br>
                                                <div class="form-group text-left">
                                                    <button id="reset" class="btn btn-default">Annuler</button>
                                                    <button id="voir" class="btn btn-primary">Voir</button>
                                                </div></div>
                                            </div>
                                            <br>
                                            
                                            <div class="row">
                                                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                    <div class="form-group">
                                                        <label for="qteTotal">Quantité Totale</label>
                                                        <input type="text" class="form-control" id="qteTotal" placeholder="Quantité Total" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                    <div class="form-group">
                                                        <label for="montantTotal">Montant Total</label>
                                                        <input type="text" class="form-control prix" id="montantTotal" placeholder="Montant Total" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <table class="table table-bordered table-striped mb-none" id="achatFourniTable" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
                                                <thead>
                                                <tr>
                                                    <th class="center hidden-phone">Dates </th>
                                                    <th class="center hidden-phone">Boutique</th>
                                                    <th class="center hidden-phone">Produits </th>
                                                    <th class="center hidden-phone">Quantité</th>
                                                    <th class="center hidden-phone">Prix Unit </th>
                                                    <th class="center hidden-phone">Montant</th>
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




                    </div>
                </section>
            </div>

                    {{--
                    <div class="panel-body">
                        <a class="btn btn-default mb-xs mt-xs mr-xs btn btn-default" href="{{route('newlivraison')}}"><i class="fa fa-plus"></i>Ajouter une livraison</a>
                        <table class="table table-bordered table-striped mb-none" id="livraisonTable" >
                            <thead>
                            <tr>
                                <th class="center hidden-phone">Numero</th>
                                <th class="center hidden-phone">Date de livraison</th>
                                <th class="center hidden-phone">Action</th>
                            </tr>
                            </thead>
                            <tbody class="center hidden-phone">
                            </tbody>
                        </table>
                    </div>
                    <div id="fournisseur_produit" class="tab-pane ">
                        <a class="btn btn-default mb-xs mt-xs mr-xs btn btn-default" href="{{ url('/newlivraisonCentrale') }}"><i class="fa fa-plus"></i>Ajouter une livraison</a>
                        <form method="POST" action="{{ url('/livraisonNew') }}">
                            @csrf

                            <div id="fournisseur" class="tab-pane active">

                                <table class="table table-bordered table-striped mb-none" id="livraisonTable" >
                                    <thead>
                                    <tr>

                                        <th class="center hidden-phone">Date de livraison</th>
                                        <th class="center hidden-phone">Commandes</th>
                                        <th class="center hidden-phone">Boutiques</th>
                                        <th class="center hidden-phone">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody class="center hidden-phone">


                                    </tbody>
                                </table>
                            </div>

                        </form>

                    </div>
            </div>
        </section>
    </div>
    </section>
    </div>
//...
 --}}

 {{--
    <div class="inner-wrapper">
        <section role="main" class="content-body">
            <header class="page-header">
                <h2>LIVRAISON</h2>
            </header>

            <div class="row">
                <section class="panel">
                    <header class="panel-heading">
                        <div class="panel-actions">
                            <a href="#" class="fa fa-caret-down"></a>
                        </div>

                        <h1 class="panel-title">LISTES DES  LIVRAISONS</h1>
                    </header>

                    <div class="panel-body">
                        <div class="col-md-18">
                            <div class="tabs">
                                <ul class="nav nav-tabs nav-justified">
                                    <li class="active">
                                        <a href="#fournisseur" data-toggle="tab" class="text-center"><i class="fa fa-star"></i> LIVRAISONS</a>

                                    </li>
                                    <li>
                                        <a href="#fournisseur_produit" data-toggle="tab" class="text-center">LIVRAISON / AUTRES BOUTIQUES</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div id="fournisseur" class="tab-pane active">
                                        <a class="btn btn-default mb-xs mt-xs mr-xs btn btn-default" href="{{url('/newlivraison1')}}"><i class="fa fa-plus"></i>Ajouter une livraison</a>
                                        <table class="table table-bordered table-striped mb-none" id="livraisonTable" >
                                            <thead>
                                            <tr>

                                            </tr>
                                            </thead>
                                            <tbody class="center hidden-phone">
                                                <th class="center hidden-phone">Numero</th>
                                                <th class="center hidden-phone">Date de livraison</th>
                                                <th class="center hidden-phone">Action</th>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="fournisseur_produit" class="tab-pane ">
                                        <a class="btn btn-default mb-xs mt-xs mr-xs btn btn-default" href="{{ url('/newlivraisonCentrale') }}"><i class="fa fa-plus"></i>Ajouter une livraison</a>
                                        <form method="POST" action="{{ url('/livraisonNew') }}">
                                            @csrf

                                            <div id="fournisseur" class="tab-pane active">

                                                <table class="table table-bordered table-striped mb-none" id="livraisonTable" >
                                                    <thead>
                                                    <tr>

                                                        <th class="center hidden-phone">Date de livraison</th>
                                                        <th class="center hidden-phone">Commandes</th>
                                                        <th class="center hidden-phone">Boutiques</th>
                                                        <th class="center hidden-phone">Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody class="center hidden-phone">


                                                    </tbody>
                                                </table>
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
    </div>--}}



    <div class="modal fade" id="detaillivraison" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#0b97c4;border-top-left-radius: inherit;border-top-right-radius: inherit">
                    <b>	<h4 class="modal-title" id="modal-user-title" align="center" style="color: white" ></h4></b>

                </div>
                <div class="modal-body">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Fermer</button>
                    </div>
                </div>

            </div>

        </div>

    </div>
    {{-- <div class="modal fade" id="detaillivraisonNew" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#0b97c4;border-top-left-radius: inherit;border-top-right-radius: inherit">
                    <b>	<h4 class="modal-title" id="modal-user-title" align="center" style="color: white" ></h4></b>

                </div>
                <div class="modal-body">

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Fermer</button>
                    </div>
                </div>

            </div>

        </div>

    </div> --}}

{{--
    <div class="modal fade" id="detaillivraisonNew" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#0b97c4;border-top-left-radius: inherit;border-top-right-radius: inherit">
                    <b>	<h4 class="modal-title" id="modal-user-title" align="center" style="color: white" ></h4></b>

                </div>
                <div class="modal-body">
                    <ul class="list-group">
                        <li class="list-group-item">Date de livraison :<b> <span class="text-danger" id="sDate"></span> </b></li>
                        <li class="list-group-item">Commades :<b> <span class="text-danger" id="scommande"></span> </b></li>
                        <li class="list-group-item">Boutiques :<b> <span class="text-danger" id="sboutique"></span> </b></li>

                        <li class="list-group-item">
                            crée le :<b> <span class="text-danger" id="Create"></span></b> </li>

                        <li class="list-group-item">
                            mise a jour le :<b> <span class="text-danger" id="Update"></span></b> </li>
                    </ul>



                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Fermer</button>
                    </div>
                </div>

            </div>

        </div>

    </div> --}}

@endsection
@section('js')
 
    <script src="octopus/assets/vendor/jquery/jquery.js"></script>
    <script src="octopus/assets/vendor/bootstrap/js/bootstrap.js"></script>
    <script src="octopus/assets/vendor/nanoscroller/nanoscroller.js"></script>
    <script src="octopus/assets/vendor/jquery-datatables/media/js/jquery.dataTables.js"></script>
    <script src="octopus/assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
    <script src="octopus/assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
    <script src="/vendor/select/js/select2.full.min.js"></script>

    {{-- <script src="js/livraison.js"></script> --}}
    <script src="js/livraison.js"></script>
        <script src="js/livraisonHistorique.js"></script>

    <script src="js/livraisonNew1.js"></script>
    <script src="js/test/livraisonOne.js"></script>

@endsection
