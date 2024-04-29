@extends('layout')
@section('css')
    <link rel="stylesheet" href="{{ asset('octopus/assets/vendor/jquery-datatables-bs3/assets/css/datatables.css') }}" />
@endsection
@section('contenu')
    <div class="inner-wrapper">
        <section role="main" class="content-body">
            <header class="page-header">
                <h2>Décharge</h2>
            </header>

            <div class="row">
                <section class="panel">
                    <header class="panel-heading">
                        <div class="panel-actions">
                            <a href="#" class="fa fa-caret-down"></a>
                        </div>

                        <h1 class="panel-title">LISTES DES LIVRAISONS </h1>
                    </header>

                    <div class="panel-body">
                        <a class="modal-with-form btn btn-default mb-xs mt-xs mr-xs btn btn-default" id="btnemploye" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-plus"></i> Ajouter une décharge</a>

                        <table class="table table-bordered table-striped mb-none" id="employeTable" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
                            <thead>
                            <tr>
                                <th class="center hidden-phone">Numero</th>
                                <th class="center hidden-phone">Date de livraison</th>
                                <th class="center hidden-phone">Quantité livrée</th>
                                <th class="center hidden-phone">Quantité vendue</th>
                                <th class="center hidden-phone">Statut</th>
                                <th class="center hidden-phone">Action</th>
                            </tr>
                            </thead>
                            <tbody class="center hidden-phone">
                                @if(count($livraisons) == 0)
                                    <tr>
                                        <td colspan="8">Aucune livraison pour le moment</td>
                                    </tr>
                                @endif
                                @foreach($livraisons as $livraison)
                                    <tr>
                                        <td class="center hidden-phone">{{ $livraison->numero }}</td>
                                        <td class="center hidden-phone">{{ $livraison->date_livraison }}</td>
                                        <td class="center hidden-phone">{{ $livraison->qte_liv() }}</td>
                                        <td class="center hidden-phone">{{ $livraison->qte_sell() }}</td>
                                        <td class="center hidden-phone">{{ $livraison->statut() }}</td>
                                        <td class="center hidden-phone">

                                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#livraisonEdit-{{ $livraison->id }}">
                                                <i class="fa fa-eye"></i>
                                            </button>

                                            <div class="modal fade" id="livraisonEdit-{{ $livraison->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header " style="background-color: #0b93d5;border-top-left-radius: inherit;border-top-right-radius: inherit">
                                                            <h4 class="modal-title-user" id="myModalLabel" style="color: white">Modification d'une décharge</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <!-- Form for editing -->
                                                            <form action="{{ route('decharges_edit', $decharge->id)}}" method="post" class="form-validate form-horizontal mb-lg" enctype="multipart/form-data">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="form-group mt-lg">
                                                                    <label class="col-sm-3 control-label">Nom</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" name="nom"  id="nom" class=" form-control" placeholder="LJ" value="{{ $decharge->nom }}"  required/>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group mt-lg">
                                                                    <label class="col-sm-3 control-label">Prenoms</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" name="prenoms" id="prenoms" class="form-control" placeholder="Kodjo" value="{{ $decharge->prenoms }}" required/>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-sm-3 control-label">CNI/PP</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" name="cni" id="cni" class="form-control" value="{{ $decharge->cni }}" required/>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-sm-3 control-label">Tel</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" name="tel" id="tel" class="form-control" value="{{ $decharge->tel }}" required/>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group mt-lg">
                                                                    <label class="col-sm-3 control-label">Motif</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" name="motif" id="motif" class="form-control" value="{{ $decharge->motif }}" required/>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group mt-lg">
                                                                    <label class="col-sm-3 control-label">Montant</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="number" name="montant" id="montant" class="form-control" value="{{ $decharge->montant }}" required/>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-md-3 control-label">Fournisseur</label>
                                                                    <div class="col-md-9">
                                                                        <select  name="fournisseur_id" id="fournisseur" class="form-control populate">
                                                                            <option value="">Choisis le fournisseur</option>
                                                                            @foreach($fournisseurs as $fournisseur)
                                                                                @if($fournisseur->id == $decharge->fournisseur_id)
                                                                                <option value="{{$fournisseur->id}}" selected>{{($fournisseur->nom)}}</option>
                                                                                @else
                                                                                <option value="{{$fournisseur->id}}">{{($fournisseur->nom)}}</option>
                                                                                @endif
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group mt-lg">
                                                                    <label class="col-sm-3 control-label">Document</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="file" name="document" id="document" class="form-control" required/>
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

                                            <!-- Delete Button -->
                                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal-{{ $decharge->id }}">
                                                <i class="fa fa-trash-o"></i>
                                            </button>

                                            <!-- Delete Modal -->
                                            <div class="modal fade" id="deleteModal-{{ $decharge->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header " style="background-color: #0b93d5;border-top-left-radius: inherit;border-top-right-radius: inherit">
                                                            <h4 class="modal-title-user" id="myModalLabel" style="color: white">Suppression d'une décharge</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            Voulez vous supprimer cette décharge?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                            <form method="POST" action="{{ route('decharges_delete', $decharge->id) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger" id="confirmDelete">Supprimer</button>
                                                            </form>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </td>



                                        <script type="text/javascript">
                                        $(document).ready(function() {
                                            $('#previewButton').on('click', function() {
                                                $('#filePreviewModal .modal-body').html(content);
                                                $('#filePreviewModal').modal('show');
                                            });
                                        });
                                    </script>


                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
            </div>
        </section>
    </div>
    </section>
    </div>


@endsection
@section('js')

    <script src="{{ asset('octopus/assets/vendor/jquery/jquery.js') }}"></script>
    <script src="{{ asset('octopus/assets/vendor/bootstrap/js/bootstrap.js') }}"></script>
    <script src="{{ asset('octopus/assets/vendor/nanoscroller/nanoscroller.js') }}"></script>
    <script src="{{ asset('octopus/assets/vendor/jquery-datatables/media/js/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('octopus/assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js') }}"></script>
    <script src="{{ asset('octopus/assets/vendor/jquery-datatables-bs3/assets/js/datatables.js') }}"></script>

@endsection
