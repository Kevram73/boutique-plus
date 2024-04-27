@extends('layout')
@section('contenu')
    <div class="inner-wrapper">
        <section role="main" class="content-body">
            <header class="page-header">
                <h2>Compte de résultat</h2>
            </header>

                <div class="row">
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

                </div>
                <section class="panel">
                    <header class="panel-heading">
                        <div class="panel-actions">
                            <a href="#" class="fa fa-caret-down"></a>
                        </div>

                        <h1 class="panel-title">COMPTE DE RESULTAT</h1>
                    </header>

                    <div class="panel-body">
                        <div class="col-sm-16 invoice-col">
                            <h4 align="center"><strong>TABLEAU DE COMPTE DE RESULTAT</strong></h4>
                            <br>

                            <table class="table table-striped table-bordered" style="font-size: 2rem">
                                <thead>
                                    <tr>
                                    <th scope="col">Libelle</th>
                                    <th scope="col">Montant</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr  style="background: #9fff9f; color: #090909;"><th scope="row">Chiffre d'affaire         </th><td>                  <span class="text-dark text-bold"  id="ca"></span></td></tr>
                                    <tr style="background: #f5837d; color: #090909;" ><th scope="row">Cout des produits vendus  </th><td> <span class="text-dark text-bold"  id="cpv"></span></td></tr>
                                    <tr style="background: #a4f6ee; color: #090909;" ><th scope="row">Marge brute               </th><td>                    <span class="text-dark text-bold" id="mb" ></span></td></tr>
                                    <tr style="background: #f5837d; color: #090909;" ><th scope="row">Charges                   </th><td>                          <span class="text-dark text-bold"  id="c"></span></td></tr>
                                    <tr style="background: #f5837d; color: #090909;" ><th scope="row">Depenses                  </th><td>                           <span class="text-dark text-bold"  id="depensetotal"></span></td></tr>
                                    <tr style="background: #a4f6ee; color: #090909;" ><th scope="row">Resultat avant impot      </th><td>        <span class="text-dark text-bold"  id="rai"></span></td></tr>
                                    <tr style="background: #f5837d; color: #090909;" ><th scope="row">Impots                    </th><td>                           <span class="text-dark text-bold"  id="i"></span></td></tr>
                                    <tr style="background: #03a9f4; color: #090909;" ><th scope="row">Resultat net              </th><td>                     <span class="text-dark text-bold" style="color: white !important; font-size: 2.5rem"  id="rn"></span></td></tr>
                                </tbody>
                            </table>
                            <!-- <address>
                                <b class="list-group-item">Chiffre d'affaire                          : <span class="text-danger"  id="ca"></span> </b>
                                <b class="list-group-item">Cout des produits vendus  : <span class="text-danger"  id="cpv"></span> </b>
                                <b class="list-group-item">Marge brute                                  : <span class="text-danger" id="mb" ></span> </b>
                                <b class="list-group-item">Charges                                            : <span class="text-danger"  id="c"></span> </b>
                                <b class="list-group-item">Depenses                                            : <span class="text-danger"  id="depensetotal"></span> </b>
                                <b class="list-group-item">Resultat avant impot             : <span class="text-danger"  id="rai"></span> </b>
                                <b class="list-group-item">Impots                                              : <span class="text-danger"  id="i"></span> </b>
                                <b class="list-group-item">Resultat net                                  : <span class="text-danger"  id="rn"></span> </b>
                            </address> -->
                        </div>

                    </div>
                </section>
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
    <script src="js/resultatsuper.js"></script>

@endsection
