@extends('layout')
@section('css')
    <link rel="stylesheet" href="octopus/assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
    <link rel="stylesheet" type="text/css" href="/vendor/daterangepicker/daterangepicker.css"/>
@endsection
@section('contenu')
    <div class="inner-wrapper">
        <section role="main" class="content-body">
            <header class="page-header">
                <h2>Dépôt</h2>
            </header>

            <div class="row">
                <section class="panel">
                    <header class="panel-heading">
                        <div class="panel-actions">
                            <a href="#" class="fa fa-caret-down"></a>
                        </div>

                        <h1 class="panel-title">FAIRE UN DEPOT</h1>
                    </header>

                    <div class="panel-body">
                        <div class="row text-center">
                            <div class="col-md-6">
                                <form id="form" action="/editversement/{{$versements->id}}" method="POST" class="form-validate form-horizontal mb-lg" enctype="multipart/form-data">
                                        {{csrf_field()}}
                                        <div class="form-group mt-lg">
                                            <label class="col-sm-3 control-label">Nature q</label>
                                            <div class="col-sm-9">
                                                <select name="nature"  id="nature" class="form-control populate">
                                                    <optgroup label="Choisir la nature">
                                                        <option selected value="{{ $versements->nature }}">{{ $versements->nature }}</option>
                                                    <option  value="Ventes Magasins">Ventes Magasins</option>
                                                    <option value="Autres">Autres</option>
                                                    </optgroup>
                                                </select>
{{--                                                 <input type="text" name="nature"  id="nature" class="form-control" placeholder="" />
 --}}                                            </div>
                                        </div>
                                        <div class="form-group mt-lg">
                                            <label class="col-sm-3 control-label">Montant * </label>
                                            <div class="col-sm-9">
                                                <input type="number" name="montant" value="{{$versements->montant}}"  id="montant" class="form-control" placeholder="" min="0" required/>
                                                <input type="hidden" name="idcharge" id="idcharge"/>
                                                <input type="hidden" name="sold_id" id="sold_id" value="" />
                                            </div>
                                        </div>
                                        <div class="form-group mt-lg">
                                            <label class="col-sm-3 control-label">Date * </label>
                                            <div class="col-sm-9">
                                                <input type="text" name="date"  id="date" value="{{$versements->date}}" class="form-control date" placeholder="" required/>
                                            </div>
                                        </div>
                                        <div class="form-group mt-lg">
                                            <label class="col-sm-3 control-label">Comptes * </label>
                                            <div class="col-sm-9">
                                                <select  name="compte_id" id="compte_id" class="form-control populate">
                                                    <optgroup label="Choisir un banques">
                                                        <option value=""></option>
                                                        @foreach($comptes as $compte)
                                                            @if ( $versements->compte_id == $compte->id)
                                                            <option selected value="{{$compte->id}}">
                                                                {{$compte->banques}}  -  {{$compte->numero}}
                                                            </option>                    
                                                            @else
                                                            <option value="{{$compte->id}}">
                                                                {{$compte->banques}}  -  {{$compte->numero}}
                                                            </option>                                                            @endif
                                                        @endforeach
                                                    </optgroup>
                                                </select>
                                            </div>
                                        </div>
                                     
                                        <div class="form-group mt-lg">
                                            <label class="col-sm-3 control-label">Solde Collecteur</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control"  value="{{$solde}}" readonly/>
                                            </div>
                                        </div>
                                        <div class="form-group mt-lg">
                                            <label class="col-sm-3 control-label">Description</label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" name="description" id="description"  rows="5">{{$versements->description}}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <div class="col-md-12 text-right">
                                                <button type="submit" class="btn btn-primary" id="btnadd"><i class="fa fa-check"></i> Valider</button>
                                                <a href="/allversements" class="mb-xs mt-xs mr-xs btn btn-default"><i class="fa fa-times"></i> Retour</a>
                                            </div>
                                        </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
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
    <script>
        function setNumeralHtml(element, format, surfix="")
        {
            var prices = $("."+element);

            for(var i=0; i<prices.length; i++)
            {
                var number = numeral(prices[i].innerText);

                var string = number.format(format);
                prices[i].innerText = string+" "+surfix;
            }

        }
        setNumeralHtml("prix", "0,0");

        
function sweetToast(type,text){
    return  Swal.fire({
        position: 'top-end',
        icon: type,
        title: text,
        showConfirmButton: false,
        timer: 1500,
        animation : true,
    });
}
    </script>
    <script type="text/javascript" src="/vendor/daterangepicker/moment.min.js"></script>
    <script type="text/javascript" src="/vendor/daterangepicker/daterangepicker.js"></script>

    <script>
        $(function() {
            $('input[name="date"]').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                minYear: 1901,
                maxYear: parseInt(moment().format('YYYY'),10)
            }, function(start, end, label) {
                var years = moment().diff(start, 'years');
            });
        });
    </script>
    <script src="js/depense.js"></script>
    
@endsection
