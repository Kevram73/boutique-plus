@extends('layout')
@section('css')
    <link rel="stylesheet" href="octopus/assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
    <link rel="stylesheet" href="/vendor/select/css/select2.min.css" />
@endsection
@section('contenu')
    <div class="inner-wrapper">
        <section role="main" class="content-body">
            <header class="page-header">
                <h2>Devis de vente (en détails)</h2>
            </header>

            <div class="row">
                <section class="panel">
                    <header class="panel-heading">
                        <div class="panel-actions">
                            <a href="#" class="fa fa-caret-down"></a>
                        </div>

                        <h1 class="panel-title">GENERATION D'UN DEVIS DE VENTE EN DETAIL</h1>
                    </header>

                    <div class="panel-body">
                        <div class="row">
                                            <div class="col-md-4 form-group">
                                                <label class="col-md-4 control-label">Nom</label>
                                                <div class="col-md-9 form-group">
                                                    <select  name="client" id="client"  class=" form-control populate">
                                                        <optgroup label="Choisir le client">
                                                            <option value=""></option>
                                                            @foreach($client as $clt)
                                                                <option value="{{$clt->id}}">{{$clt->nom}}</option>
                                                            @endforeach
                                                        </optgroup>
                                                    </select>
                                                </div>
                                                <a class="modal-with-form btn btn-default mb-xs mt-xs mr-xs btn btn-primary" id="btnclient"><i class="fa fa-plus"></i></a>
                                            </div>

                                            <div class="col-md-4 form-group">
                                                <label class="col-md-4 control-label">Categorie</label>
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
                                                <label class="col-sm-4 control-label">Produit</label>
                                                <div class="col-md-9 form-group">
                                                    <select  name="produit" id="produit"   class="form-control populate">
                                                        <optgroup label="Choisir un produit">
                                                            <option value="" ></option>
                                                            @foreach($produits as $cat)
                                                                <option value="{{$cat->id}}">{{$cat->nom}}</option>
                                                            @endforeach
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4 form-group">
                                                <label class="col-sm-4 control-label">Modele</label>
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
                                            </div>
                                            <div class="col-md-4 form-group">
                                                   <label class="col-sm-4 control-label">Prix</label>
                                                     <div class="col-sm-9">
                                                         <input type="number" name="prix"  id="prix" class="form-control" placeholder="15000" required readonly/>
                                                         <input type="hidden" name="mod" id="mod"/>
                                                         <input type="hidden" name="stock" id="stock"/>
                                                       </div>
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <label class="col-sm-4 control-label">Quantité</label>
                                                <div class="col-sm-9">
                                                    <input type="number" name="quantite"  id="quantite" class="form-control" placeholder="100"  min="1" required/>
                                                </div>
                                            </div>

                                            <div class="col-md-4 form-group"></div>
                                            <div class="col-md-4 form-group">
                                                <label class="col-sm-4 control-label">Total</label>
                                                <div class="col-sm-9">
                                                    <input type="number" id="prixQte" class="form-control" placeholder="0"  min="1" readonly/>
                                                </div>
                                            </div>

                                            <div class="col-md-4 form-group">
                                                <label class="col-sm-4 control-label">Stock</label>
                                                <div class="col-sm-9">
                                                    <input type="number" id="qteStock" class="form-control" placeholder="100"  min="1" readonly/>
                                                </div>
                                            </div>

                                            <div class="col-md-4 form-group"></div>
                                            <div class="col-md-4 form-group">
                                                <label class="col-sm-4 control-label">Réduction</label>
                                                <div class="col-sm-9">
                                                    <input type="number" id="reduction" name="reduction" class="form-control" placeholder="0"  min="1" />
                                                </div>
                                            </div>
                                        </div>
                                                <div class="col-md-12 text-right">
                                                    <button type="button" class="btn btn-primary" id="ajout"><i class="fa fa-check"></i> Ajouter</button>
                                                    <button type="button" class="mb-xs mt-xs mr-xs btn btn-default  "  id="annuler"><i class="fa fa-times"></i> Annuler</button>
                                                </div>



                    <div class="col-md-12">
                        <div class="row">
                        <div class="col-md-6"></div>
                        <div style="display: none;" class="col-md-6 text-right">
                                <h4 class="m-0">Montant réduction: <strong id="montant_reduction" class="prix">0</strong></h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <a class="btn btn-danger" id="sup" ><i class="fa fa-trash-o" ></i>Supprimer</a>
                            </div>

                            <div class="col-md-6 text-right">
                                <h3 class="m-0">Total: <strong id="montant_total" class="prix">0</strong></h3>
                            </div>
                        </div>
                    </div>


                    </div>
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
    <script>

        function setNumeralHtml(element, format, surfix="", type="html")
        {
            var prices = $("."+element);

            for(var i=0; i<prices.length; i++)
            {
                if(type=="html")
                {
                    var number = numeral(prices[i].innerText);

                    var string = number.format(format);
                    prices[i].innerText = string+" "+surfix;
                }else if(type=="value")
                {
                    var number = numeral(prices[i].value);

                    var string = number.format(format);
                    prices[i].value = string+" "+surfix;
                }

            }

        }

        setNumeralHtml("prix", "0,0");
    </script>
    <script src="js/devisvente.js"></script>

@endsection
