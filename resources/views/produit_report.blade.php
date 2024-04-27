@extends('layout')
@section('css')
    <link rel="stylesheet" href="octopus/assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
    <link rel="stylesheet" href="/vendor/select/css/select2.min.css" />
@endsection
@section('contenu')
    <div class="inner-wrapper">
    <section role="main" class="content-body">
            <header class="page-header">
                <h2>Rapport</h2>
            </header> 

            <div class="row">
                <div class="col-sm-6 col-md-4 col-lg-4 col-xl-4">
                    <section class="panel">
                        <div class="panel-body">

                            <div class="form-group">
                                <label for="product">Produit</label>
                                <select id="product" class="form-control">
                                    <option value="0">   </option>
                                    @foreach ($produits as $item)
                                        <option value="{{ $item->id }}">{{ $item->nom }} - {{ $item->libelle }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="debut">Date Début</label> 
                                <input type="date" class="form-control" id="debut" placeholder="Date Début">
                            </div>

                            <div class="form-group">
                                <label for="fin">Date Fin</label>
                                <input type="date" class="form-control" id="fin" placeholder="Date Fin">
                            </div>

                            <div class="form-group">
                                <label for="type">Type</label>
                                <select id="type" class="form-control">
                                    <option value="0">   </option>
                                    <option value="1">Vente Simple</option>
                                    <option value="2">Vente à Crédit</option>
                                    <option value="3">Vente non Livrée</option>
                                </select>
                            </div>

                            <div class="form-group" id="clientBox">
                                <label for="client">Client</label>
                                <select id="client" class="form-control">
                                    <option value="0">   </option>
                                    @foreach ($clients as $item)
                                        <option value="{{ $item->id }}">{{ $item->nom }} {{ $item->prenom ? ' '.$item->prenom : '' }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="text-center">
                                <button id="reset" class="btn btn-default">Annuler</button>
                                <button id="voir" class="btn btn-primary">Voir</button>
                            </div>

                        </div>
                    </section>

                </div>
                <div class="col-sm-6 col-md-8 col-lg-8 col-xl-8">
                    <section class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <div class="form-group">
                                        <label for="qteTotal">Quantité Total</label>
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
                            <table class="table table-bordered table-striped mb-none" id="reportTable" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
                                <thead>
                                <tr>
                                    <th class="center hidden-phone">Utilisateur</th>
                                    <th class="center hidden-phone">Date </th>
                                    <th class="center hidden-phone">Quantité</th>
                                    <th class="center hidden-phone">Montant</th>
                                    <th class="center hidden-phone">Vente</th>
                                </tr>
                                </thead>
                                <tbody class="center hidden-phone">


                                </tbody>
                            </table>
                        </div>
                    </section>
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
    <script src="js/produit_report.js"></script>
    <script>

    </script>

@endsection
