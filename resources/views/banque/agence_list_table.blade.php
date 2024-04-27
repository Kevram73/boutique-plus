<table class="table table-bordered table-striped mb-none"
       id="provisionTable" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
    <thead>
    <tr>
        <th class="center hidden-phone">Nom </th>
        <th class="center hidden-phone">Ville </th>
        <th class="center hidden-phone">Quartier</th>
        <th class="center hidden-phone">Contact</th>
        <th class="center hidden-phone">Action</th>
    </tr>
    </thead>
    <tbody class="center hidden-phone">
    @foreach($agences as $bank)
        <tr>
            <th class="center hidden-phone">
                {{$bank->nom}}
            </th>
            <th class="center hidden-phone">
                {{$bank->ville}}
            </th>
            <th class="center hidden-phone">
                {{$bank->quartier}}
            </th>
            <th class="center hidden-phone">
                {{$bank->contact}}
            </th>
            <th class="center hidden-phone">   <a class="btn btn-success" onclick="editboutique('.$bank->id.')"> <i class="fa fa-pencil"></i></a>
                <a class="btn btn-danger" onclick="deleteboutique('.$bank->id.')"><i class="fa fa-trash-o"></i></a></th>
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
