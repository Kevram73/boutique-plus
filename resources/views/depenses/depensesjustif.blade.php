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
                <h2>APERCU </h2>
            </header>

            <div class="row">
                <section class="panel">
                    <header class="panel-heading">
                        <div class="panel-actions">
                            <a href="#" class="fa fa-caret-down"></a>
                        </div>
                        @if (!$depense== null)

                        <h1 class="panel-title">JUSTIFICATIF - {{$depense->name}} - {{$depense->depense_id}}</h1>
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

                                        <div class="form-group mt-lg">

                                        </div>
                                        <div class="form-group mt-lg">
                                            <div class="card-body col-md-12 col-sm-12">
												{{-- <img style="height: 500px; width: 600px;" src="{{ 'storage/'.$depense->justificatif_versement}}" class="img-responsive" alt="" />
 --}}
                                                <iframe style="height: 1000px;" src="{{ asset( 'justify/'.$depense->url) }}" width="100%" height="500px"></iframe>

                                            </div>
                                           </div>
                                           @else
                                           <h4 style="margin: 20px; margin-top: 40px;"> PAS DE FICHIER</h4>
                                       @endif
                                        <div class="modal-footer">
                                            <div class="col-md-12 text-right">
                                                <a href="/depenses" class="mb-xs mt-xs mr-xs btn btn-default"><i class="fa fa-times"></i> Retour</a>
                                            </div>
                                        </div>
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
