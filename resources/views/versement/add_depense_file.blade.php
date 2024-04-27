@extends('layout')
@section('css')
<style>
    #previewContainer {
       width: 200px;
   height: 200px;
   border: 5px solid black;
   overflow: hidden;
   float: left;
   margin-right: 100px;
   margin-top: 50px;
   }
   #previewContainer img {
   width: 100%;
   height: 100%;
   object-fit: cover;
 }
  #removeImageButton {
      background-color: blue;
   color: white;
   padding: 5px;
   cursor: pointer;
   top: 0;
   right: 0;
  }
</style>
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

                        <h1 class="panel-title">JUSTIFICATIF - {{$depense->nature}} - {{$depense->montant}}</h1>
                    </header>
                    @if (\Session::has('success'))
                        <div class="alert alert-success">
                            <ul>
                                <li>{!! \Session::get('success') !!}</li>
                            </ul>
                        </div>
                    @endif
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <form id="form" action="/depenseversem-files" method="POST" class="form-validate form-horizontal mb-lg" enctype="multipart/form-data">
                                        {{csrf_field()}}
                                        <div class="form-group mt-lg">
                                            <label class="col-sm-3 control-label">Justificatifs</label>
                                            <div class="col-sm-9">
                                                <input type="hidden" name="sold_id" id="sold_id" value="" />
                                                <input type="hidden" name="depense_id" id="depense_id" value="{{$depense->id}}" />
                                                <input type="file" name="file"  id="fileInput" class="form-control" placeholder="" />
                                            </div>
                                        </div>
                                        <div class="form-group mt-lg">
                                            <div id="previewContainerContainer" class="card-body col-md-12 col-sm-12">
                                               
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
                            <div class="col-md-12">
                            <ul>
                              {{--   @foreach ($files as $file)
                                    <li> <a href="/justify/{{$file->url}}">{{$file->name}}</a>  <a href="/depense-file-delete-{{$file->id}}" ><i class="fa fa-trash-o" class="text-danger" aria-hidden="true"></i></li>
                                @endforeach --}}
                            </ul>
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
    <script>
        console.log('viens');
        document.querySelector('#fileInput').addEventListener('change', function(e) {
          const files = e.target.files;
          for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if (file.type.startsWith('image/')) {
              const reader = new FileReader();
              reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.width = 200;
                const previewContainer = document.createElement('div');
                previewContainer.id = 'previewContainer';
                const removeImageButton = document.createElement('div');
                removeImageButton.id = 'removeImageButton';
                removeImageButton.innerText = 'Supprimer';
                removeImageButton.addEventListener('click', function() {
                  previewContainer.remove();
                });
                previewContainer.appendChild(removeImageButton);
                previewContainer.appendChild(img);
                document.querySelector('#previewContainerContainer').appendChild(previewContainer);
              }
              reader.readAsDataURL(file);
            }
          }
        });
    </script>
@endsection
