console.log('banQue');

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
    /* $('#btnSubmitAddBanque').click(function (e) {
        e.preventDefault();
        var form = $('#banqueModalFormId')[0];
        console.log(form);
        var data =new FormData(form);
        console.log(data);
        console.log(data.values());
        $('#btnSubmitAddBanque').prop("disabled",true);
        $.ajax({
            type:"POST",
        // enctype:"multipart/form-data",
        
            url:"/banques",
            processData:false,
            contentType:false,
            data:data,
            success:function (data) {
                // Remove the modal
                $('#idAddBanqueModal').hide();
                // show the alerte
                Swal.fire(
                    'Banque',
                    'Création réussi',
                );
                // reload the windows.
                window.location.reload();

            },

            
            error:function (data) {
                $('#btnSubmitAddBanque').prop("disabled",false);
                let message= "Erreur requette échouer";
                sweetToast('warning',message);
                console.log(data);

            }
        });

    }) ; */

console.log('idnomb');

    /* $('#idAddBanqueModal  form').on('submit', function (e) {

        let url,message;
        if (!$('#idnomb').val()){
        
            url = '/banques'
            message = 'Banque enregistrée'
        }
        else{
            url = '/updatebanque'
            message = 'Banque modifiée'
    console.log(idnomb);

        }
        e.preventDefault();
        if (e.isDefaultPrevented()){
            $.ajax({
                url : url ,
                type : "post",
                // data : $('#modal-form-user').serialize(),
                data: new FormData($("#idAddBanqueModal form")[0]),

                //data: new FormData($("#modal-form-user")[0]),
                contentType: false,
                processData: false,
                success : function(data) {

                    console.log(data);
                    sweetToast('success',message);
                    $('#idAddBanqueModal').modal('hide');

                    provisionTable.ajax.reload();
                },
                error : function(data){
                    message = 'Erreur dans la sauvegarde de la banque '
                    sweetToast('warning',message);
                }
            });
        }



    });
 */
$('#btnaddBanq').on('click', function(){

    $('.banqueModalFormId').text('ENREGISTREMENT DE BANQUE');
    $('#idnomb').val(null);
    $('#bName').val(null);
    $('#bDesc').val(null);
    $('#contact').val(null);
    $('#btnadd').text('Valider');
    $('#btnadd').removeClass('btn-warning');
    $('#btnadd').addClass('btn-primary'); 
    $('#idAddBanqueModal').modal('show');
});


function addBanque(){
    console.log('  Boutton clicked add banques') ;
    // show modal
    $('#idAddBanqueModal').modal('show');

}
// add agence Banque
function addAgenceBanque(){
    console.log('Boutton ajouter agence banque ') ;
}
 
function addCompteBancaire(){
    console.log('Boutton ajouter compte bancaire') ;

}

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
                
                url : '/deletebanque-'+id,
                type : "get",
                contentType: false,
                processData: false,
                success : function(data) {
                    console.log('idbanque');

                    console.log(data)
                    //provisionTable.ajax.reload();
                    window.location.replace(window.location.href);

                    Swal.fire('Effacé',
                        'Banque bien effacé',
                        'success')
                },
                error : function(data){
                }
            });
    
        }
    });
    }
    
    function editbanque(id){
        $.ajax({
            url : '/showbanque-'+id,
            type : "get",
            success : function(data) {
                console.log(id);
                $('#idnomb').val(data.id);
                $('#bDesc').val(data.description);
                
                $('#bName').val(data.nom);
                $('#contact').val(data.contact);
                $('#btnadd').text('Modifier');
                $('#btnadd').removeClass('btn-primary');
                $('#btnadd').addClass('btn-warning');
                $('.banqueModalFormId').text('Modifier les informations de : '+data.nom);
                $('#idAddBanqueModal').modal('show');
    
            },
            error : function(data){
    alert('erreur')
            }
        });
    }

    function showbanquedetail(id){

        $.ajax({
            url: '/showbanquedetail-'+id,
            type: "get",
            success : function(data) {
                $('#modal-user-title').text('CATEGORIE : '+data.nom);
                $('#sNom').text(data.nom);
                $('#sDescription').text(data.description);
                $('#sCreate').text(data.created_at);
                $('#sUpdate').text(data.updated_at);
                $('#detailcategorie').modal('show');
    
    
    
            },
            error : function(data){
                sweetToast('Une erreur c\'est produite. Veuillez recommancer')
            }
        })
    }


    $('#btnadd').click(function (e) {
        console.log('idnom');
        let url,message;
        if (!$('#idnomb').val()){
            url = '/banques'
            message = 'Banque enregistrée'
        console.log(idnomb);
    
        }
        else{
            url = '/updatebanque'
            message = 'Banque modifiée'
    
        }
        e.preventDefault();
        if (e.isDefaultPrevented()){
            $.ajax({
                url : url ,
                type : "post",
                // data : $('#modal-banqueModalFormId-user').serialize(),
                data: new FormData($("#banqueModalFormId")[0]),
               
                //data: new FormData($("#modal-form-user")[0]),
                contentType: false,
                processData: false,
                success : function(data) {
    
                    $('#idAddBanqueModal').modal('hide');
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
    

    

var categorieTable;

$(function () {

    categorieTable =   $('#categorieTable').DataTable({
        processing: true,
        serverSide: true,
        'paging': true,
        'lengthChange': true,
        'searching': true,
        'ordering': true,
        'info': true,
        'autoWidth': true,
        language: {
            "sProcessing": "Traitement en cours...",
            "sSearch": "Rechercher&nbsp;:",
            "sLengthMenu": "Afficher _MENU_ &eacute;l&eacute;ments",
            "sInfo": "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
            "sInfoEmpty": "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
            "sInfoFiltered": "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
            "sInfoPostFix": "",
            "sLoadingRecords": "Chargement en cours...",
            "sZeroRecords": "Aucun &eacute;l&eacute;ment &agrave; afficher",
            "sEmptyTable": "Aucune donn&eacute;e disponible dans le tableau",
            "oPaginate": {
                "sFirst": "Premier",
                "sPrevious": "Pr&eacute;c&eacute;dent",
                "sNext": "Suivant",
                "sLast": "Dernier"
            },
            "oAria": {
                "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
            },
            "select": {
                "rows": {
                    _: "%d lignes séléctionnées",
                    0: "Aucune ligne séléctionnée",
                    1: "1 ligne séléctionnée"
                }
            }
        },
        ajax: '/showbanquedetail',
        "columns": [

            {data: "nom",name : 'nom'},
            {data: "description",name : 'description'},
            {data: "contact",name : 'contact'},
            {data: "numero",name : 'numero'},
            {data: "type",name : 'type'},]

    });


});