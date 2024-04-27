$('#credit').on('click', function(){

    $('.modal-title-user').text('LISTE DES CREANCIERS');
    $('#infocredit').modal('show');
});
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

$('#btnhistorique').on('click',function (e) {
    window.location='/historiquecharges'
});

var chargeTable;




$(function () {

    chargeTable =   $('#chargeTable').DataTable({
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

        ajax: '/allcharge',
        "columns": [

            {data: "type",name : 'type'},
            {data: "libelle",name : 'libelle'},
            {data: "montant",name : 'montant'},
            {data: "date",name : 'date'},
            {
                data: "statut",
                render : function (data, type, row) {
                    if(data == 1)
                    {
                        return "<strong ><span class='badge badge-success'>O U I</span></strong>";
                    }else{
                        return "<strong ><span class='badge badge-danger'>N O N</span></strong>";
                    }
                }
            },
            {data: "action", name : 'action' , orderable: false, searchable: false}


        ]

    });
});

$('#btncharge').on('click', function(){
    $.ajax({
        url : '/verificationcharge',
        type : "get",

        contentType: false,
        processData: false,
        success : function(data) {
            if (data==2  || data==1){
                Swal.fire({
                    position: 'center',
                    title: 'Voulez-vous creer un journal des charge?',
                    text:"",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor:'#3085d6',
                    cancelButtonColor:'#d33',
                    confirmButtonText:'Oui '
                }).then ((result)=>{
                    if (result.value){
                        $.ajax({
                            url : '/journalcharge',
                            type : "get",

                        });

                        Swal.fire('Effectué',
                            'Journal des charges bien enregistré')
                        $('.modal-title-user').text('ENREGISTREMENT DE LE CHARGE');
                        $('#idcharge').val(null);
                        $('#libelle').val(null);
                        $('#btnadd').text('Valider');
                        $('#btnadd').removeClass('btn-warning');
                        $('#btnadd').addClass('btn-primary');
                        $('#montant').val(null);

                        $('#ajout_charge').modal('show');
                    }
                });
            }
            else {
                if (data==3) {
                    $('.modal-title-user').text('ENREGISTREMENT DE LE CHARGE');
                    $('#idcharge').val(null);
                    $('#libelle').val(null);
                    $('#btnadd').text('Valider');
                    $('#btnadd').removeClass('btn-warning');
                    $('#btnadd').addClass('btn-primary');
                    $('#montant').val(null);

                    $('#ajout_charge').modal('show');

                }
            }
        },
        error : function(data){
            Swal.fire('Impossible',

                'info')
        }
    });


});


//post des données
$('#ajout_charge  form').on('submit', function (e) {

    let url,message;
    if (!$('#idcharge').val()){
        url = '/ajoutcharge'
        message = 'Charge enregisetré'


    }
    else{
        url = '/updatecharge'
        message = 'Charge modifiée'

    }
    e.preventDefault();
    if (e.isDefaultPrevented()){
        $.ajax({
            url : url ,
            type : "post",
            // data : $('#modal-form-user').serialize(),
            data: new FormData($("#ajout_charge form")[0]),
            //data: new FormData($("#modal-form-user")[0]),
            contentType: false,
            processData: false,
            success : function(data) {

                $('#ajout_charge').modal('hide');
                sweetToast('success',message);

                chargeTable.ajax.reload();
            },
            error : function(data){
                alert('erreur')
            }
        });
    }

});

function editcharge(id){
    $.ajax({
        url : '/showcharge-'+id,
        type : "get",
        success : function(data) {

            $('#idcharge').val(data.id);
            $('#type').val(data.type);
            $('#libelle').val(data.libelle);
            $('#compte').val(data.compte);
            $('#btnadd').text('Modifier');
            $('#btnadd').removeClass('btn-primary');
            $('#btnadd').addClass('btn-warning');
            $('.modal-title-user').text('Modifier les informations');
            $('#montant').val(data.montant);
            $('#ajout_charge').modal('show');

        },
        error : function(data){
            alert('erreur')
        }
    });
}

function deletecharge(id){
    Swal.fire({
        position: 'center',
        title: 'Vous etes sûr',
        text:"Les produits enregistrés sur la commande seront supprimé aussi",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor:'#3085d6',
        cancelButtonColor:'#d33',
        confirmButtonText:'Oui effacer'
    }).then ((result)=>{
        if (result.value){
            $.ajax({
                url : '/deletecharge-'+id,
                type : "get",

                contentType: false,
                processData: false,
                success : function(data) {
                    Swal.fire('Effacé',
                        'Fichier bien effacé',
                        'success')
                    chargeTable.ajax.reload();
                },
                error : function(data){
                    Swal.fire('Impossible',
                        'Cette commande a été deja livrée',
                        'info')
                }
            });

        }
    });
}

console.log('vraiment plus')
$('#btnjournal').on('click',function (e) {
    $.ajax({
        url : '/fermercharge',
        type : "get",

        contentType: false,
        processData: false,
        success : function(data) {
            if (data==1){
                let message='Journal des charges fermé avec succes'
                sweetToast('success',message);
            }
            else {
                if (data==2) {

                    let message='Impossible.... Ce journal des charges est déja fermé'
                    sweetToast('warning',message);

                }
            }
        },
        error : function(data){
        }
    });

})



   
   
$('#compte').on('change', function() {
    var userId = $(this).val();
    $.get('/get-solde-' + userId, function(data) {
    var solder = data.solder;
    console.log(solder);
    console.log('vraiment je   plus')
    var donne = $('#montant').val();
    if (parseFloat(donne) >= parseFloat(solder)) {
    //alert('Le montant est supérieur ou égal au solde de l'utilisateur sélectionné.');
    let _m = " Votre compte a un solde insufisant" ;
                sweetToast('warning',_m) ;
    }
    });
    });


    