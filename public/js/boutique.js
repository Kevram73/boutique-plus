

var boutiqueTable;
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



$(function () {

    boutiqueTable =   $('#boutiqueTable').DataTable({
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
        ajax: '/allboutique', 
        "columns": [

            {data: "nom",name : 'nom'},
            {data: "adresse",name : 'adresse'},
            {data: "telephone",name : 'telephone'},
            {data: "valeur",name : 'valeur'},
            {data: "action", name : 'action' , orderable: false, searchable: false}
        ]

    });


});
$('#btnboutique').on('click', function(){

    $('.modal-title-user').text('ENREGISTREMENT DE LA BOUTIQUE');
    $('#idboutique').val(null);
    $('#nom').val(null);
    $('#adresse').val(null);
    $('#telephone').val(null);
    $('#btnadd').text('Valider');
    $('#btnadd').removeClass('btn-warning');
    $('#btnadd').addClass('btn-primary');
    $('#ajout_boutique').modal('show');
});

//post des données
$('#ajout_boutique  form').on('submit', function (e) {

    let url,message;
    if (!$('#idboutique').val()){
        url = '/ajoutboutique'
        message = 'boutique enregistrée'


    }
    else{
        url = '/updateboutique'
        message = 'boutique modifiée'


    }
    e.preventDefault();
    if (e.isDefaultPrevented()){
        $.ajax({
            url : url ,
            type : "post",
            // data : $('#modal-form-user').serialize(),
            data: new FormData($("#ajout_boutique form")[0]),
            //data: new FormData($("#modal-form-user")[0]),
            contentType: false,
            processData: false,
            success : function(data) {

                sweetToast('success',message);


                $('#ajout_boutique').modal('hide');

                boutiqueTable.ajax.reload();
            },
            error : function(data){
                message = 'Erreur dans la sauvegarde de la boutique '
                sweetToast('warning',message);
            }
        });
    }



});


function showboutique(id){

    $.ajax({
        url: '/showboutique-'+id,
        type: "get",
        success : function(data) {
            $('#modal-user-title').text('CATEGORIE : '+data.nom);
            $('#sNom').text(data.nom);
            $('#sAdresse').text(data.adresse);
            $('#sTelephone').text(data.telephone);
            $('#sCreate').text(data.created_at);
            $('#sUpdate').text(data.updated_at);
            $('#detailboutique').modal('show');



        },
        error : function(data){
            sweetToast('Une erreur c\'est produite. Veuillez recommancer')
        }
    })
}

function showvaleur(id){
    $.ajax({
        url: '/showboutiquevaleur-'+id,
        type: "get",
        success : function(data) {
            Swal.fire(`La valeur nette de la boutique ${data?.nom} est ${data?.prix}`,
            '',
            'info');
        },
        error : function(data){
            sweetToast('Une erreur c\'est produite. Veuillez recommancer')
        }
    })
}

function editboutique(id){
    $.ajax({
        url : '/showboutique-'+id,
        type : "get",
        success : function(data) {

            $('#idboutique').val(data.id);
            $('#nom').val(data.nom);
            $('#adresse').val(data.adresse);
            $('#telephone').val(data.telephone);
            $('#btnadd').text('Modifier');
            $('#btnadd').removeClass('btn-primary');
            $('#btnadd').addClass('btn-warning');
            $('.modal-title-user').text('Modifier les informations de : '+data.nom);
            $('#ajout_boutique').modal('show');

        },
        error : function(data){
alert('erreur')
        }
    });
}
function deleteboutique(id){
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
            url : '/deleteboutique-'+id,
            type : "get",
            contentType: false,
            processData: false,
            success : function(data) {
                boutiqueTable.ajax.reload();
                Swal.fire('Effacé',
                    'Fichier bien effacé',
                    'success')
            },
            error : function(data){
            }
        });

    }
});
}



function changeState(id){
    Swal.fire({
        position: 'center',
        title: 'Voulez-vous bloquer  le stock de cette Boutique ?',
        text:"Elle n'aura plus de stock",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor:'#3085d6',
        cancelButtonColor:'#d33',
        confirmButtonText:'Oui '
    }).then ((result)=>{
        if (result.value){
            $.ajax({
                url : '/changeBoutiqState-'+id,
                type : "get",
                success : function(data) {
                    boutiqueTable.ajax.reload();
                },
                error : function(data){
                }
            });

            Swal.fire('Effectué',
                'Etat du stock modifié')
        }
    });
}
