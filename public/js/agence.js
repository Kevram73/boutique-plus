

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
// create banque.
$('#btnSubmitAddAgence').click(function (e) {
    e.preventDefault();
    var form = $('#banqueModalFormId')[0];
    console.log(form);
    var data =new FormData(form);
    console.log(data);
    console.log(data.values());
    $('#btnSubmitAddAgence').prop("disabled",true);
    $.ajax({
        type:"POST",
       // enctype:"multipart/form-data",
        url:"/agences",
        processData:false,
        contentType:false,
        data:data,
        success:function (data) {
            // Remove the modal
            $('#idAddAgenceModal').hide();
            // show the alerte
            Swal.fire(
                'Agence',
                'Création réussi',
            );
            // reload the windows.
            window.location.reload();

        },
        error:function (data) {
            $('#btnSubmitAddAgence').prop("disabled",false);
            let message= "Erreur requette échouer";
            sweetToast('warning',message);
            console.log(data);

        }
    });

}) ;




function addAgenceBanque(){
    console.log('  Boutton clicked add banques') ;
    // show modal
    $('#idAddAgenceModal').modal('show');

}
// add agence Banque
/* function addAgenceBanque(){
    console.log('Boutton ajouter agence banque ') ;
} */

function addCompteBancaire(){
    console.log('Boutton ajouter compte bancaire') ;

}
