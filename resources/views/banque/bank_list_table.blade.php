<table class="table table-bordered table-striped mb-none"
       id="provisionTable" data-swf-path="octopus/assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
    <thead>
    <tr>
        <th class="center hidden-phone">Nom banque</th>
        <th class="center hidden-phone">Nom agence</th>
        <th class="center hidden-phone">Compte</th>
        <th class="center hidden-phone">Type de compte</th>

    </tr>
    </thead>
    <tbody class="center hidden-phone">
    @foreach($banks as $bank)
        <tr>
            <th class="center hidden-phone">
                {{$bank->nom}}
            </th>
            <th class="center hidden-phone">
                {{$bank->nom}}
            </th>
            <th class="center hidden-phone">
                {{$bank->nom}}
            </th>
            <th class="center hidden-phone">
                {{$bank->nom}}
            </th>
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
