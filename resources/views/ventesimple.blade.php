@extends('layout')
@section('css')
    <link rel="stylesheet" href="octopus/assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
    <link rel="stylesheet" href="/vendor/select/css/select2.min.css" />
@endsection
@section('contenu')
    <div class="inner-wrapper">
        <section role="main" class="content-body">
            <header class="page-header">
                <h2>Vente simple</h2>
            </header>

            <div class="row">
                <section class="panel">
                    <header class="panel-heading">
                        <div class="panel-actions">
                            <a href="#" class="fa fa-caret-down"></a>
                        </div>

                        <h1 class="panel-title">ENREGISTREMENT DE LA VENTE</h1>
                    </header>

                    <div class="panel-body">
                        <div class="row">
                        <div class="col-md-4 form-group">
                                                <label class="col-md-4 control-label">Client</label>
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
                                                <label class="col-sm-4 control-label">Famille</label>
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
                                            </div>

                                            <div class="col-md-4 form-group">
                                                <label class="col-sm-4 control-label">Livraison</label>
                                                <div class="col-md-9">
                                                    <select name="livraison" id="livraison" class="form-control" required>
                                                        <optgroup label="Choisir une livraison">
                                                            <!-- Les options seront ajoutées via JavaScript -->
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <label class="col-sm-4 control-label">Quantité</label>
                                                <div class="col-sm-9">
                                                    <input type="number" name="quantite"  id="quantite" class="form-control" placeholder="100"  min="1" required/>
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
                                            <input type="hidden" value="" id="liv_qte" />
                                        </div>
                                        <div class="row">


                                            <div class="col-md-4 form-group">
                                                <label class="col-sm-4 control-label">Total</label>
                                                <div class="col-sm-9">
                                                    <input type="number" id="prixQte" class="form-control" placeholder="0"  min="1" readonly/>
                                                </div>
                                            </div>

                                            <div class="col-md-4 form-group">
                                                <label class="col-sm-4 control-label">Réduction</label>
                                                <div class="col-sm-9">
                                                    <input type="number" id="reduction" name="reduction" class="form-control" placeholder="0"  min="1" />
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                                <div class="col-md-12 text-right">
                                                    <button type="button" class="btn btn-primary" id="ajout"><i class="fa fa-check"></i> Ajouter</button>
                                                    <button type="button" class="mb-xs mt-xs mr-xs btn btn-default  "  id="annuler"><i class="fa fa-times"></i> Annuler</button>
                                                </div>
                                                <input type="hidden" id="boutique" name="boutique" value="{{ Auth::user()->boutique_id }}" />

                    <div id="comform">
                    <form  method="POST" class="	form-validate form-horizontal mb-lg" >
                        {{csrf_field()}}
                    <input type="hidden"  name="venTable" id="venTable">
                    @if (Auth::user()->boutique->settings->where('tag', 'tva')->first() && Auth::user()->boutique->settings->where('tag', 'tva')->first()->pivot->is_active)
                        <div class="row" style="width: 40%">
                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                <label class="form-label d-inline mr-5" for="setTav">TVA (%)</label>
                            </div>
                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                <input class="form-control d-inline mr-5" type="checkbox" name="setTva" id="setTav">
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <input class="form-control d-none mr-5" type="number" name="tva" id="tav" value="18">
                            </div>
                        </div>
                    @endif

                    {{-- <div class="row align-items-center">
                        <div class="d-inline col-3">
                          <label class="col-form-label" for="setTav">TVA</label>
                        </div>
                        <div class="d-inline col-3">
                            <input class="form-control" type="checkbox" name="set_tva" id="setTav">
                        </div>
                        <div class="d-inline col-3">
                            <input class="form-control" type="number" name="tva" id="tav" value="18">
                        </div>
                        <div class="d-inline col-3">
                          <span  class="form-text">%</span>
                        </div>
                    </div> --}}

                    </form>
                    </div>

                    <div id="avoircomform">
                        <form  method="POST" class="	form-validate form-horizontal mb-lg" >
                            {{csrf_field()}}
                                <input type="hidden"  name="avoirvenTable" id="avoirvenTable">
                            <div class="row" style="width: 40%">
                                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                    <label class="form-label d-inline mr-5" for="setTav">AVOIR </label>
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                    <input class="form-control d-inline mr-5" type="checkbox" name="checkavoir" id="checkavoir">
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <input class="form-control d-none mr-5" type="number" name="avoir" id="avoir">
                                </div>
                            </div>
                        </form>
                    </div>
                    <table class="table table-bordered table-striped mb-none" id="venteTable" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
                        <thead>
                        <tr>
                            <th class="center hidden-phone">Numero</th>
                            <th class="center hidden-phone">Produit</th>
                            <th class="center hidden-phone">Modele</th>
                            <th class="center hidden-phone">Livraison</th>
                            <th class="center hidden-phone">Quantité </th>
                            <th class="center hidden-phone">Prix </th>
                            <th class="center hidden-phone">Réduction </th>
                            <th class="center hidden-phone">Total </th>
                        </tr>
                        </thead>
                        <tbody class="center hidden-phone">
                        </tbody>
                    </table>

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

                    <div class="col-md-12 text-right">
                        <button type="button" class="btn btn-primary" id="valider"><i class="fa fa-check"></i> Valider</button>
                        <button type="button" class="mb-xs mt-xs mr-xs btn btn-default  "  id="annuler"><i class="fa fa-times"></i> Annuler</button>
                    </div>

                    </div>
            </div>
        </section>
    </div>

    <div class="modal fade " id="ajout_client" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header " style="background-color: #0b93d5;border-top-left-radius: inherit;border-top-right-radius: inherit">
                    <h4 class="modal-title-user" id="myModalLabel" style="color: white"></h4>
                </div>
                <div class="modal-body">
                    <form id="form" action="" method="POST" class="	form-validate form-horizontal mb-lg" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="form-group mt-lg">
                            <label class="col-sm-3 control-label">Nom/Raison sociale</label>
                            <div class="col-sm-9">
                                <input type="text" name="nom"  id="nom" class="form-control" placeholder=" ATO Kodjo, BTD Construction" required/>
                                <input type="hidden" name="idclient" id="idclient"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Email</label>
                            <div class="col-sm-9">
                                <input type="email" name="email" id="email" class="form-control" placeholder="aaaa@aa.com " />
                            </div>
                        </div>
                        <div class="form-group mt-lg">
                            <label class="col-sm-3 control-label">Contact</label>
                            <div class="col-sm-9">
                                <input type="integer" name="contact" id="contact" class="form-control" placeholder="92658797"/>
                            </div>
                        </div>
                        <div class="form-group mt-lg">
                            <label class="col-sm-3 control-label">Adresse</label>
                            <div class="col-sm-9">
                                <input type="integer" name="adresse" id="adresse" class="form-control" placeholder="Adidogome, Lome"/>
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
    <script src="js/ventesimple.js"></script>
    <script>
    var my_tab = [];
       $('#modele').on('change', function() {
            var modele = $(this).val();
            var boutique_id = $('#boutique').val();

            if (modele) {
                // Réinitialisation du champ de sélection avant la requête
                $('#livraison').empty().append('<option value="">Chargement...</option>'); // Option par défaut pendant le chargement

                $.ajax({
                    url: '/getLivraisonsByProduit',
                    type: 'GET',
                    data: { modele_id: modele, boutique_id: boutique_id },
                    success: function(response) {
                        var options = '<option value="">Choisissez une livraison</option>'; // Valeur par défaut
                        response.livraisons.forEach(function(livraison) {
                            my_tab = response.livraisons
                            options += '<option value="' + livraison.numero + '">' + livraison.numero + ' *** ' + 'Qte rest: ' + livraison.quantite_restante + '</option>';
                        });

                        $('#livraison').html(options); // Remplir avec les nouvelles options
                    },
                    error: function(xhr, status, error) {
                        console.error("Erreur lors du chargement des livraisons :", error);
                        // Réinitialiser le champ de sélection en cas d'erreur
                        $('#livraison').empty().append('<option value="">Erreur de chargement</option>');
                    }
                });
            } else {
                // Réinitialisation si aucun modèle n'est sélectionné
                $('#livraison').empty().append('<option value="">Choisissez une livraison</option>');
            }
        });

        function getQuantiteRestante(numero){
            for(var i = 0; i < my_tab.length; i++){
                if(my_tab[i].numero === numero){
                    return my_tab[i].quantite_restante;
                }
            }
            return null;
        }

        $('#livraison').on('change', function(){
            var livraison = $(this).val();

            if(livraison){
                quantite = getQuantiteRestante(livraison);
                var quantiteInput = document.getElementById("quantite");
                quantiteInput.setAttribute("max", quantite);
            }
        })

        $('#quantite').on('change', function() {
    var enteredQuantity = parseInt($(this).val());
    var maxQuantity = parseInt($(this).attr("max"));

    if (enteredQuantity > maxQuantity) {
        alert("Quantité entrée supérieure à la quantité maximale autorisée.");
        $(this).val(0);
        $('#prixQte').val(0);
        // You can also reset the input value here if needed
    }
});

</script>

@endsection
