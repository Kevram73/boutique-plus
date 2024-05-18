$('#credit').on('click', function(){

    $('.modal-title-user').text('LISTE DES CREANCIERS');
    $('#infocredit').modal('show');
});

var venteTable;

$(function () {

    venteTable =   $('#venteTable').DataTable({
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

        ajax: '/allvente',
        "columns": [

            {data: "numero",name : 'numero'},
            {data: "date_vente",name : 'date_vente'},
            {data: "action", name : 'action' , orderable: false, searchable: false}


        ]

    });
});
$('#donne').on('value change',function ( ) {
    $('#restant').val($('#total').val()-$('#donne').val());
    if ( $('#restant').val()>0) {
        $('#te').text('Restant');
        $('#Sdonne').text( $('#donne').val());
        $('#Srestant').text( $('#restant').val(),'fcfa');
        $('#reste').val($('#restant').val());
    }
    if ( $('#restant').val()<0) {
        $('#reste').val($('#restant').val());
        $('#te').text('Monnaie');
        $('#restant').val(   $('#restant').val()*-1);
        $('#Sdonne').text( $('#donne').val());
        $('#Srestant').text( '0 fcfa');
    }
})

//post des données

function show(id){

    $.ajax({
        url: '/showvente-'+id,
        type: "get",
        success : function(data) {
         window.location='/detailvente-'+id
        },
        error : function(data){
            window.location='/detailvente2-'+id
        }
    })
}


function deletepro(id){
    Swal.fire({
        position: 'center',
        title: 'Vous etes sûr',
        text:"Les produits enregistrés sur la vente seront supprimé aussi",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor:'#3085d6',
        cancelButtonColor:'#d33',
        confirmButtonText:'Oui effacer'
    }).then ((result)=>{
        if (result.value){
            $.ajax({
                url : '/deletevente-'+id,
                type : "get",

                contentType: false,
                processData: false,
                success : function(data) {
                    Swal.fire('Effacé',
                        'Fichier bien effacé',
                        'success')
                    provisionTable.ajax.reload();
                },
                error : function(data){
                    Swal.fire('Impossible',
                        'Cette vente a été deja éffectuée',
                        'info')
                }
            });

        }
    });
}

$('#ajout_reglement  form').on('submit', function (e) {
    let url = '/regler-'+$('#id').val();
    e.preventDefault();
    if (e.isDefaultPrevented()){
        $.ajax({
            url : url ,
            type : "post",
            // data : $('#modal-form-user').serialize(),
            data: new FormData($("#ajout_reglement form")[0]),
            //data: new FormData($("#modal-form-user")[0]),
            contentType: false,
            processData: false,
            success : function(data) {
                Swal.fire('Patientez',
                        'Enregistrement en cours...',
                        'info')
                        if(document.getElementById('type_vente').value == 4){
                            window.location='/facturegros-'+$('#id').val();
                        } else {
                            window.location='/facturecredit-'+$('#id').val();
                        }

                setTimeout(() => {
                    Swal.fire('Confirmé',
                            'Vente encaissée avec succes',
                            'success')
                        setTimeout(() => {
                            window.location = '/ventes';
                        }, 3000);
                }, 10000);
            },
            error : function(data){
                alert('attention')
            }
        });
    }  });

// function fermer(){
//   setTimeout(1)
//                     window.location='/ventes'
// }

// window.print()

