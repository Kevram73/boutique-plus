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

                        <h1 class="panel-title">LISTES DES DECHARGES</h1>
                    </header>

                    <div class="panel-body">
                        <a class="modal-with-form btn btn-default mb-xs mt-xs mr-xs btn btn-default" id="btnemploye" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-plus"></i> Ajouter une décharge</a>
                        <a class="modal-with-form btn btn-default mb-xs mt-xs mr-xs btn btn-default" href="{{ route('decharges.generate') }}"><i class="fa fa-file"></i> Document de décharge</a>
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header " style="background-color: #0b93d5;border-top-left-radius: inherit;border-top-right-radius: inherit">
                                        <h4 class="modal-title-user" id="myModalLabel" style="color: white">Ajout d'une décharge</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('decharges_store')}}" method="post" class="form-validate form-horizontal mb-lg" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group mt-lg">
                                                <label class="col-sm-3 control-label">Nom</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="nom"  id="nom" class="form-control" placeholder="LJ" required/>
                                                </div>
                                            </div>
                                            <div class="form-group mt-lg">
                                                <label class="col-sm-3 control-label">Prenoms</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="prenoms" id="prenoms" class="form-control" placeholder="Kodjo" required/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">CNI/PP</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="cni" id="cni" class="form-control" placeholder="0**2-520*-***" required/>
                                                </div>
                                            </div>
                                            <div class="form-group mt-lg">
                                                <label class="col-sm-3 control-label">Motif</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="motif" id="motif" class="form-control" placeholder="Règlement" required/>
                                                </div>
                                            </div>
                                            <div class="form-group mt-lg">
                                                <label class="col-sm-3 control-label">Montant</label>
                                                <div class="col-sm-9">
                                                    <input type="number" name="montant" id="montant" class="form-control" placeholder="Montant" required/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Fournisseur</label>
                                                <div class="col-md-9">
                                                    <select  name="fournisseur_id" id="fournisseur" class="form-control populate">
                                                        <option value="">Choisis le fournisseur</option>
                                                        @foreach($fournisseurs as $fournisseur)
                                                            <option value="{{$fournisseur->id}}">{{($fournisseur->nom)}}</option>
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
                        <table class="table table-bordered table-striped mb-none" id="employeTable" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
                            <thead>
                            <tr>
                                <th class="center hidden-phone">Nom</th>
                                <th class="center hidden-phone">Prénom(s)</th>
                                <th class="center hidden-phone">CNI/PP</th>
                                <th class="center hidden-phone">Motif</th>
                                <th class="center hidden-phone">Montant</th>
                                <th class="center hidden-phone">Fournisseur</th>
                                <th class="center hidden-phone">Enregistré le</th>
                                <th class="center hidden-phone">Action</th>
                            </tr>
                            </thead>
                            <tbody class="center hidden-phone">
                                @if(count($decharges) == 0)
                                    <tr>
                                        <td colspan="8">Aucune décharge pour le moment</td>
                                    </tr>
                                @endif
                                @foreach($decharges as $decharge)
                                    <tr>
                                        <td class="center hidden-phone">{{ $decharge->nom }}</td>
                                        <td class="center hidden-phone">{{ $decharge->prenoms }}</td>
                                        <td class="center hidden-phone">{{ $decharge->cni }}</td>
                                        <td class="center hidden-phone">{{ $decharge->motif }}</td>
                                        <td class="center hidden-phone">{{ $decharge->montant }}</td>
                                        <td class="center hidden-phone">
                                            @if($decharge->fournisseur_id)
                                                {{ $decharge->fournisseur->nom }}
                                            @endif
                                            </td>
                                        <td class="center hidden-phone">{{ $decharge->created_at }}</td>
                                        <td class="center hidden-phone">
                                            <button class="btn btn-primary" id="previewButton" data-toggle="modal" data-target="#filePreviewModal-{{ $decharge->id }}"><i class="fa fa-eye"></i></button>
                                            <div class="modal fade" id="filePreviewModal-{{ $decharge->id }}" tabindex="-1" role="dialog" aria-labelledby="filePreviewModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="filePreviewModalLabel">Prévisualisation</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <embed src="{{ asset('storage/decharge/' . $decharge->filename) }}" type="application/pdf" width="100%" height="800" id="previewImage">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editModal-{{ $decharge->id }}">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            
                                            <div class="modal fade" id="editModal-{{ $decharge->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
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
                                                                        <input type="text" name="nom"  id="nom" class="form-control" placeholder="LJ" value="{{ $decharge->nom }}"  required/>
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
