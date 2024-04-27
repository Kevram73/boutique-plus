var globalStore ;
function sweetToast(type,text){
    return  Swal.fire({
        position: 'top-end',
        icon: type,
        title: text,
        showConfirmButton: false,
        timer: 2000,
        animation : true,
    });
}
console.log('HIIIIIIII');



var reglementTable;


$(function () {

    reglementTable =   $('#reglementTabl').DataTable({
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
            "sPrint": "Imprimer",
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
        ajax: '/caisses',
        "columns": [

            {data: "date",name : 'date'},
            {data :  "montant",name : 'montant'},
            {data :  "boutique_id",name : 'boutique_id'},
            {data :  "user_id",name : 'user_id'},
            {data: "action", name : 'action' , orderable: false, searchable: false}


        ] 

    });



});
$('#btnreglement').on('click', function(){

    $('.modal-title-user').text('ENREGISTREMENT DE LA CAISSE');
    $('#btnadd').text('Valider');
    $('#btnadd').removeClass('btn-warning').addClass('btn-primary');
    //$('#btnadd').addClass('btn-primary'); 
       $('#fournisseur').val(null);

    $('#date').val(null);
    $('#solde').val(null);
    $('#ajout_caisse').modal('show');
});

//post des données
/*         $('#ajout_caisse  form').on('submit', function (e) {

            let url,message;
                url = '/storecaisse'
                message = 'reglement enregistré'
            
        /*   else{
                url = '/updatereglementachat'
                message = 'reglement modifié'

            } 
            e.preventDefault();
            if (e.isDefaultPrevented()){
                $.ajax({
                    url : url ,
                    type : "post",
                    // data : $('#modal-form-user').serialize(),
                    data: new FormData($("#ajout_caisse form")[0]),
                    //data: new FormData($("#modal-form-user")[0]),
                    contentType: false,
                    processData: false,
                    success : function(data) {

                        $('#ajout_caisse').modal('hide');
                        sweetToast('success',message);

                    reglementTable.ajax.reload();
                    window.location.reload();
                    },
                    error : function(data){
                    alert('erreur')
                    }
                });
            }
        }); */

// create banque.
console.log('perty');

$('#btnadd').click(function (e) {
    e.preventDefault();
    var form = $('#form')[0];
    console.log(form);
    console.log('allo');
    var data =new FormData(form);
    console.log(data);
    console.log(data.values());
    $('#btnadd').prop("disabled",true);
    $.ajax({
       
        type:"POST",
       // enctype:"multipart/form-data",
        url:"/storecaisse",
        processData:false,
        contentType:false,
        data:data,
        success:function (data) {
            // Remove the modal
            $('#ajout_caisse').hide();
            // show the alerte
            Swal.fire(
                'Compte',
                'Création réussi',
            );
            // reload the windows.
            window.location.reload();

        },
        error:function (data) {
            $('#btnadd').prop("disabled",false);
            let message= "Erreur requette échouer";
            sweetToast('warning',message);
            console.log(data);

        }
    });

}) ;

function editreglement(id){
    $.ajax({
        url : '/showreglementachat-'+id,
        type : "get",
        success : function(data) {

            $('#idreglement').val(data.id);
            $('#btnadd').text('Modifier');
            $('#btnadd').removeClass('btn-primary');
            $('#btnadd').addClass('btn-warning');
            $('#email').val(data.email);
            $('#contact').val(data.contact);
            $('#sexe').val(data.sexe);
            $('#ajout_caisse').modal('show');

        },
        error : function(data){
alert('erreur')
        }
    });
}


function deletereglement(id){
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
            $.ajax({
                url : '/deletereglementachat-'+id,
                type : "get",
                contentType: false,
                processData: false,
                success : function(data) {
                    Swal.fire('Effacé',
                        'Fichier bien effacé',
                        'success')
                    reglementTable.ajax.reload();
                },
                error : function(data){
                    Swal.fire('Erreur',
                        '...',
                        'warning')
                }
            });

        }
    });
}



