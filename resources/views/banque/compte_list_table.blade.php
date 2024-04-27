<table class="table table-bordered table-striped mb-none"
       id="provisionTable" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
    <thead>
    <tr>
        <th class="center hidden-phone">Numéro Compte</th>
        <th class="center hidden-phone">Nom Banque</th>
        <th class="center hidden-phone">Type de Compte</th>
        <th class="center hidden-phone">Action</th>
    </tr>
    </thead>
    <tbody class="center hidden-phone">
    @foreach($comptes_bancaires as $bank)
        <tr>
            <th class="center hidden-phone">
                {{$bank->numero}}
            </th>
            
            <th class="center hidden-phone">
              {{$bank->banque}}  
            </th>
            <th class="center hidden-phone">
                {{$bank->type}}  
              </th>
           
            <th class="center hidden-phone">   <a class="btn btn-success" onclick="editcompte('{{ $bank->id }}')"> <i class="fa fa-pencil"></i></a>
                <a class="btn btn-danger" onclick="deletebanque('{{ $bank->id }}')"><i class="fa fa-trash-o"></i></a>
                <a class="btn btn-info " href="/showcomptedetail-{{ $bank->id }}" ><i class="fa  fa-info"></i></a></th>

            </tr>
    @endforeach

    </tbody>
    <tfoot>
    <tr>
        <th>
            Montant total
        </th>
    </tr>
    </tfoot>
</table>
