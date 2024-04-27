

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
    /* $('#btnSubmitAddCompte').click(function (e) {
        e.preventDefault();
        var form = $('#COMPTEModalFormId')[0];
        console.log(form);
        var data =new FormData(form);
        console.log(data);
        console.log(data.values());
        $('#btnSubmitAddCompte').prop("disabled",true);
        $.ajax({
            type:"POST",
        // enctype:"multipart/form-data",
            url:"/comptessaved",
            processData:false,
            contentType:false,
            data:data,
            success:function (data) {
                // Remove the modal
                $('#idAddCompteModal').hide();
                // show the alerte
                Swal.fire(
                    'Compte',
                    'Création réussi',
                );
                // reload the windows.
                window.location.reload();

            },
            error:function (data) {
                $('#btnSubmitAddCompte').prop("disabled",false);
                let message= "Erreur requette échouer";
                sweetToast('warning',message);
                console.log(data);

            }
        });

    }) ; */




function addCompte(){
  
    // show modal
    $('#idAddCompteModal').modal('show');

} 
// add agence Banque
/* function addAgenceBanque(){
    console.log('Boutton ajouter agence banque ') ;
} */

function addCompteBancaire(){
    console.log('Boutton ajouter  bancaire') ;

}
  console.log(' VRAIMENT') ;

function deletebanque(id){
    Swal.fire({
        position: 'center',
        title: 'Vous etes sûr',
        text:"Pas de retour en arriere",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor:'#3085d6',
        cancelButtonColor:'#d33',
        confirmButtonText:'Oui effacer'
    }).then ((result)=>{
        if (result.value){
            console.log(id);
            $.ajax({
                
                url : '/deletecompte-'+id,
                type : "get",
                contentType: false,
                processData: false,
                success : function(data) {
                    console.log('idbanque');

                    console.log(data)
                    //provisionTable.ajax.reload();
                    window.location.replace(window.location.href);

                    Swal.fire('Effacé',
                        'Compte bien effacé',
                        'success')
                },
                error : function(data){
                }
            });
    
        }
    });
    }

    function editcompte(id){
        $.ajax({
            url : '/showcompte-'+id,
            type : "get",
            success : function(data) {
                console.log(id);
                $('#idnom').val(data.id);
                $('#type').val(data.type);
                $('#numero').val(data.numero);
                $('#banque_id').val(data.banque_id);
                $('#btnSubmitAddCompte').text('Modifier');
                $('#btnSubmitAddCompte').removeClass('btn-primary');
                $('#btnSubmitAddCompte').addClass('btn-warning');
                $('.COMPTEModalFormId').text('Modifier les informations de : '+data.numero);
                $('#idAddCompteModal').modal('show');
    
            },
            error : function(data){
    alert('erreur')
            }
        });
    }


    $('#btnSubmitAddCompte').click(function (e) {
        console.log('idnom');
        let url,message;
        if (!$('#idnom').val()){
            url = '/comptessaved'
            message = 'compte enregistré'
        console.log(idnom);
    
        }
        else{
            url = '/updatecompte'
            message = 'compte modifié'
    
        }
        e.preventDefault();
        if (e.isDefaultPrevented()){
            $.ajax({
                url : url ,
                type : "post",
                // data : $('#modal-COMPTEModalFormId-user').serialize(),
                data: new FormData($("#COMPTEModalFormId")[0]),
               
                //data: new FormData($("#modal-form-user")[0]),
                contentType: false,
                processData: false,
                success : function(data) {
    
                    $('#idAddCompteModal').modal('hide');
                    sweetToast('success',message);
    
                   //clientTable.ajax.reload();
                   
                   window.location.replace(window.location.href);
                },
                error : function(data){
                  alert('erreur')
                }
            });
        }
    });
    

    $('#btnAddCompte').on('click', function(){
        $('.COMPTEModalFormId').text('ENREGISTREMENT DU CLIENT (Particulier/Entreprise');
        $('#btnSubmitAddCompte').text('Valider');
        $('#btnSubmitAddCompte').removeClass('btn-warning');
        $('#btnSubmitAddCompte').addClass('btn-primary');
        $('#idnom').val(null);
      
        $('#banque_id').val(null);
        $('#type').val(null);
        $('#numero').val(null);
        $('#idAddCompteModal').modal('show');
    });