@extends('layout')
@section('css')
    <link rel="stylesheet" href="octopus/assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
@endsection
@section('contenu')
    <div class="inner-wrapper">
        <section role="main" class="content-body">
            <header class="page-header">
                <h2>Employés</h2>
            </header>

            <div class="row">
                <section class="panel">
                    <header class="panel-heading">
                        <div class="panel-actions">
                            <a href="#" class="fa fa-caret-down"></a>
                        </div>

                        <h1 class="panel-title">LISTES DES  EMPLOYES</h1>
                    </header>

                    <div class="panel-body">
                        <a class="modal-with-form btn btn-default mb-xs mt-xs mr-xs btn btn-default" id="addUser"><i class="fa fa-plus"></i>Ajouter un employé</a>
                        <table class="table table-bordered table-striped mb-none" id="userTable" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
                            <thead>
                            <tr>
                                <th class="center hidden-phone">Nom</th>
                                <th class="center hidden-phone">Prenoms</th>
                                <th class="center hidden-phone">Sexe</th>
                                <th class="center hidden-phone">Email</th>
                                <th class="center hidden-phone">Contact</th>
                                <th class="center hidden-phone">Action</th>
                            </tr>
                            </thead>
                            <tbody class="center hidden-phone">


                            </tbody>
                        </table>
                    </div>
            </div>
        </section>
    </div>


    <div class="modal fade " id="modal-form-user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header " style="background-color: #0b93d5;border-top-left-radius: inherit;border-top-right-radius: inherit">
                    <h4 class="modal-title-user" id="myModalLabel" style="color: white"></h4>
                </div>
                <div class="modal-body">
                    <form id="form" action="" method="POST" class="	form-validate form-horizontal mb-lg" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="form-group">
                            <label class="col-md-3 control-label">Profil</label>
                            <div class="col-md-9">
                                <select  name="profil" id="profil" class="form-control populate">
                                    <option value=""></option>
                                    @foreach($Roles as $Role)
                                        <option value="{{$Role->id}}">{{($Role->name)}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Boutique</label>
                            <div class="col-md-9">
                                <select  name="boutique" id="boutique" class="form-control populate">
                                    <option value=""></option>
                                    @foreach($boutiques as $boutique)
                                        <option value="{{$boutique->id}}">{{($boutique->nom)}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group mt-lg">
                            <label class="col-sm-3 control-label">Nom</label>
                            <div class="col-sm-9">
                                <input type="text" name="nom"  id="nom" class="form-control" placeholder="LJ" required/>
                                <input type="hidden" name="id" id="id"/>
                            </div>
                        </div>
                        <div class="form-group mt-lg">
                            <label class="col-sm-3 control-label">Prenoms</label>
                            <div class="col-sm-9">
                                <input type="text" name="prenom" id="prenom" class="form-control" placeholder="Kodjo" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Email</label>
                            <div class="col-sm-9">
                                <input type="email" name="email" id="email" class="form-control" placeholder="aaaa@aa.com " required/>
                            </div>
                        </div>
                        <div class="form-group mt-lg">
                            <label class="col-sm-3 control-label">Contact</label>
                            <div class="col-sm-9">
                                <input type="integer" name="contact" id="contact" class="form-control" placeholder="92658797" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Sexe</label>
                            <div class="col-md-9">
                                <select  name="sexe" id="sexe" class="form-control populate">
                                    <optgroup label="Choisir un sexe">
                                        <option value="M">Masculin</option>
                                        <option value="F">Feminin</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-primary" id="store_user"><i class="fa fa-check"></i> Valider</button>
                                <button type="button" class="mb-xs mt-xs mr-xs btn btn-default  " data-dismiss="modal"><i class="fa fa-times"></i> Annuler</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

            <div class="modal fade" id="detailUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color:#0b97c4;border-top-left-radius: inherit;border-top-right-radius: inherit">
                            <b>	<h4 class="modal-title" id="modal-user-title" align="center" style="color: white" ></h4></b>

                        </div>
                        <div class="modal-body">
                            <ul class="list-group">
                                <li class="list-group-item">Profil:<b> <span class="text-danger" id="sProfil"></span> </b></li>
                                <li class="list-group-item">Nom et prenoms :<b> <span class="text-danger" id="sNom_Prenom"></span> </b></li>
                                <li class="list-group-item">Sexe :<b> <span class="text-danger " id="sSexe" ></span></b></li>
                                <li class="list-group-item">Email :<b> <span class="text-danger " id="sEmail" ></span></b></li>
                                <li class="list-group-item">Contact :<b> <span class="text-danger " id="sContact" ></span></b></li>

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
    <script src="js/utilisateur.js"></script>
 
@endsection
