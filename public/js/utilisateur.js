

let userTable;

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
    userTable =   $('#userTable').DataTable({
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
        ajax: 'allUser',
        "columns": [
            {data: "nom",name : 'nom'},
            {data: "prenom",name: 'prenom'},
            {data :  "sexe",name : 'sexe'},
            {data :  "email",name : 'email'},
            {data :  "contact",name : 'contact'},
            {data: "action", name : 'action' , orderable: false, searchable: false}
        ]

    });




});



$('#addUser').on('click', function(){

        $('.modal-title-user').text('Enregistrer un nouvel utilisateur');
    $('#id').val(null);
    $('#nom').val(null);
    $('#btnadd').text('Valider');
    $('#btnadd').removeClass('btn-warning');
    $('#btnadd').addClass('btn-primary');
    $('#prenom').val(null);
    $('#email').val(null);
    $('#contact').val(null);
    $('#sexe').val(null);
    $('#boutique').val(null);
    $('#store-user').text('Enregistrer');
    $('#modal-form-user').modal('show');
});
//post des données
$('#modal-form-user form').on('submit', function (e) {
    let url,message;
    if (!$('#id').val()){
        url = '/ajoutUser'
        message = 'Employé enregistré'
    }
    else{
        url ='/updateUser'
        message = 'Employé modifié'

    }
    e.preventDefault();
    if (e.isDefaultPrevented()){
        $.ajax({
            url : url ,
            type : "post",
           // data : $('#modal-form-user').serialize(),
            data: new FormData($("#modal-form-user form")[0]),
            //data: new FormData($("#modal-form-user")[0]),
            contentType: false,
            processData: false,
            success : function(data) {
                $('#modal-form-user').modal('hide');
                sweetToast('success',message);

                userTable.ajax.reload();
            },
            error : function(data){
            }
        });
    }



});

function showUser(id) {

    $.ajax({
        url: '/showUser-'+id,
        type: "get",
        success : function(data) {
            $('#modal-user-title').text('Utilisateur : '+data.user.nom+' '+data.user.prenom);
            $('#sProfil').text(data.profil.name);
             $('#sNom_Prenom').text(data.user.nom+' '+data.user.prenom);
             if(data.user.sexe ===  'M'){
                 $('#sSexe').text('Masculin');
             }else if(data.user.sexe === 'F'){
                 $('#sSexe').text('Féminin');
             }
            $('#sEmail').text(data.user.email);
            $('#boutique').text(data.user.boutique);
            $('#sContact').text(data.user.contact);
            $('#sCreate').text(data.user.created_at);
            $('#sUpdate').text(data.user.updated_at);
            $('#detailUser').modal('show');



        },
        error : function(data){
        }
    })

}
function editUser(id) {


    $.ajax({
        url : '/showUser-'+id,
        type : "get",
        success : function(data) {
            $('#id').val(data.user.id);
            $('#nom').val(data.user.nom);
            $('#store-user').text('Modifier');
            $('#store-user').removeClass('btn-primary');
            $('#store-user').addClass('btn-warning');
            $('.modal-title-user').text('Modifier les informations de : '+data.user.nom+' '+data.user.prenom);
            $('#prenom').val(data.user.prenom);
            $('#email').val(data.user.email);
            $('#contact').val(data.user.contact);
            $('#profil').val(data.profil.role_id);
            $('#sexe').val(data.user.sexe);
            $('#boutique').val(data.user.boutique_id);
            $('#modal-form-user').modal('show');

        },
        error : function(data){
        }
    });
}


function changeState(id){
    Swal.fire({
        position: 'center',
        title: 'Voulez-vous modifier  l\'etat de cet employé?',
        text:"Il pourra plus se connecté",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor:'#3085d6',
        cancelButtonColor:'#d33',
        confirmButtonText:'Oui '
    }).then ((result)=>{
        if (result.value){
            $.ajax({
                url : '/changeUserState-'+id,
                type : "get",
                success : function(data) {
                    userTable.ajax.reload();

                },
                error : function(data){
                }
            });

            Swal.fire('Effectué',
                'Etat de l\'employé modifié')
        }
    });
} 

function resetPwd(id){
    Swal.fire({
        position: 'center',
        title: 'Voulez-vous renitialiser le mot de passe de cet employé?',
        text:"le mot de passe par défaut sera 'password'",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor:'#3085d6',
        cancelButtonColor:'#d33',
        confirmButtonText:'Oui '
    }).then ((result)=>{
        if (result.value){
            $.ajax({
                url : '/changeUserPwd-'+id,
                type : "get",
                success : function(data) {
                    userTable.ajax.reload();

                },
                error : function(data){
                }
            });

            Swal.fire('Effectué',
                'Mot de passe de l\'employé modifié')
        }
    });
}
