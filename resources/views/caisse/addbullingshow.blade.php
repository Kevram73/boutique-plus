@extends('layout')
@section('css')
    <link rel="stylesheet" href="octopus/assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
    <link rel="stylesheet" href="/vendor/select/css/select2.min.css" />
@endsection
@section('contenu')
    <div class="inner-wrapper">
        <section role="main" class="content-body">
            <header class="page-header">
                <h2>BILLETAGE</h2>
            </header>

            <div class="row">
                <section class="panel">
                    <header class="panel-heading">
                        <div class="panel-actions">
                            <a href="#" class="fa fa-caret-down"></a>
                        </div>

                        <h1 class="panel-title">ENREGISTREMENT DE BILLETAGE </h1>
                    </header>

                    <div class="panel-body">
                        <div class="row">
                        <div class="col-md-4 form-group">
                                                <label class="col-md-4 control-label">Billets</label>
                                                <div class="col-md-9 form-group">
                                                    <select  name="modele" id="modele"  class=" form-control populate">
                                                        <optgroup label="Choisir le billet">
                                                            <option value=""></option>
                                                            @foreach($billings as $clt)
                                                                <option value="{{$clt->value}}">{{$clt->billing_item}} FCFA</option>
                                                            @endforeach
                                                        </optgroup>
                                                    </select>
                                                </div>
                                                <input type="hidden" value="{{$clt->value}}" name="value" id="value">
                                </div>
                                                           
                                            <div class="col-md-4 form-group">
                                                <label class="col-sm-4 control-label">Nombre</label>
                                                <div class="col-sm-9">
                                                    <input type="number" name="quantite"  id="quantite" class="form-control" placeholder="100"  min="1" required/>
                                                    <input type="hidden" name="mod" id="mod"/>

                                                </div>
                                            </div>
                                            <div class="col-md-4 form-group">
                                                   <label class="col-sm-4 control-label">Total</label>
                                                     <div class="col-sm-9">
                                                         <input type="number" name="prixQte"  id="prixQte" class="form-control" placeholder="15000" required readonly/>
                                                        
                                                       </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            
                                        <div class="col-md-4 form-group"></div>
                                            <div class="col-md-4 form-group">
                                                
                                            </div>
                                            <div class="col-md-4 form-group">
                                                
                                            </div>

                                            <div class="col-md-4 form-group"></div>
                                            <div class="col-md-4 form-group"></div>
                                         
                                        </div>
                                        <br>
                                                <div class="col-md-12 text-right">
                                                    <button type="button" class="btn btn-primary" id="ajout"><i class="fa fa-check"></i> Ajouter</button>
                                                    <button type="button" class="mb-xs mt-xs mr-xs btn btn-default  "  id="annuler"><i class="fa fa-times"></i> Annuler</button>
                                                </div>

                    <div id="comform">
                    <form  method="POST" class="form-validate form-horizontal mb-lg" >
                        {{csrf_field()}}
                    <input type="hidden"  name="venTable" id="venTable">
                   
                    </form>
                    </div>
                    <table class="table table-bordered table-striped mb-none" id="venteTable" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
                        <thead>
                        <tr>
                            <th class="center hidden-phone">Numero</th>
                            <th class="center hidden-phone">Billetage</th>
                            <th class="center hidden-phone">Valeur</th>
                            <th class="center hidden-phone">Nombre </th>
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
    <script src="js/addbillingshow.js"></script>

@endsection
