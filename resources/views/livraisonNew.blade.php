@extends('layout')
@section('css')
    <link rel="stylesheet" href="octopus/assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
@endsection
@section('contenu')
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
    </div>



    <div class="modal fade" id="detailfournisseur" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#0b97c4;border-top-left-radius: inherit;border-top-right-radius: inherit">
                    <b>	<h4 class="modal-title" id="modal-user-title" align="center" style="color: white" ></h4></b>

                </div>
                <div class="modal-body">
                    <ul class="list-group">
                        <li class="list-group-item">Identifiant:<b> <span class="text-danger" id="sId"></span> </b></li>
                        <li class="list-group-item">Nom :<b> <span class="text-danger" id="sNom"></span> </b></li>
                        <li class="list-group-item">Adresse :<b> <span class="text-danger" id="sAdresse"></span> </b></li>
                        <li class="list-group-item">Email :<b> <span class="text-danger " id="sEmail" ></span></b></li>
                        <li class="list-group-item">Contact :<b> <span class="text-danger " id="sContact" ></span></b></li>
                        <li class="list-group-item">Description :<b> <span class="text-danger " id="sDescription" ></span></b></li>

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
    <div class="modal fade" id="detailfourni" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#0b97c4;border-top-left-radius: inherit;border-top-right-radius: inherit">
                    <b>	<h4 class="modal-title" id="modal-user-title" align="center" style="color: white" ></h4></b>

                </div>
                <div class="modal-body">
                    <ul class="list-group">
                        <li class="list-group-item">Fournisseur :<b> <span class="text-danger" id="sFournisseur"></span> </b></li>
                        <li class="list-group-item">Produit :<b> <span class="text-danger" id="sProduit"></span> </b></li>
                        <li class="list-group-item">Modele :<b> <span class="text-danger" id="sModele"></span> </b></li>
                        <li class="list-group-item">Prix :<b> <span class="text-danger" id="sPrix"></span> </b></li>
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

    </div>

@endsection
@section('js')

    <script src="octopus/assets/vendor/jquery/jquery.js"></script>
    <script src="octopus/assets/vendor/bootstrap/js/bootstrap.js"></script>
    <script src="octopus/assets/vendor/nanoscroller/nanoscroller.js"></script>
    <script src="octopus/assets/vendor/jquery-datatables/media/js/jquery.dataTables.js"></script>
    <script src="octopus/assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
    <script src="octopus/assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
    <script src="js/fourni.js"></script>
    <script src="js/fournisseur.js"></script>
    <script >
        $('#symbole').on('change', function(e){
            var value = $('#symbole').val();
            $('#currency').val(value);
        });
    </script>



@endsection
