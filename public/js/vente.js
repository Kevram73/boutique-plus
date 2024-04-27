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
        "order": [[ 0, "desc" ]],
        ajax: '/allvente',
        "columns": [

            {data: "numero",name : 'numero'},
            {data: "date_vente",name : 'date_vente'},
            {data: "totaux",name : 'totaux'},
            {
                data: "type_vente",
                render : function (data, type, row) {
                    let value = ""
                    if(data == 1) value = "SIMPLE";
                    else if(data == 2) value = "CREDIT";
                    else if(data == 3) value = "NON LIVREE";
                    else if(data == 4) value = "EN GROS";
                    return "<strong >"+value+"</strong>";
                }
            },
            {
                data: "user",
                render : function (data, type, row) {
                    return "<strong >"+data.nom + ' ' + data.prenom+"</strong>";
                }
            },
            {data: "action", name : 'action' , orderable: false, searchable: false}


        ]

    });
});
$('#info').on('click', function(){

    $('.modal-title-user').text('LISTE DES PRODUITS A APPROVISIONNER');
    $('#infoproduit').modal('show');
});



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
                    venteTable.ajax.reload();
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

function pdfpro(id){
    $.ajax({
        url: '/livraison/bon/'+id,
        type: "get",
        success : function(data) {
         window.location='/ventes'
        },
        error : function(data){
            console.log(data)
        }
    })
}

$('#btnhistorique').on('click',function (e) {
    window.location='/historiquevente'
});
$('#btnrecettes').on('click',function (e) {
    window.location='/recettes'
});

$('#btnreglement').on('click',function (e) {
    window.location='/reglement'
});

$('#btnjournal').on('click',function (e) {
    $.ajax({
        url : '/fermer',
        type : "get",

        contentType: false,
        processData: false,
        success : function(data) {
            if (data==1){
                let message='Journal de caisse fermé avec succes'
                sweetToast('success',message);
            }
            else {
                if (data==2) {

                    let message='Impossible.... Ce journal de caisse est déja fermé'
                    sweetToast('warning',message);

                }
            }
        },
        error : function(data){
        }
    });

})


$('#btnventesimple').on('click',function (e) {
    $.ajax({
        url : '/verification',
        type : "get",

        contentType: false,
        processData: false,
        success : function(data) {
            if (data==1 ||data==2){
                Swal.fire({
                    position: 'center',
                    title: 'Voulez-vous creer un journal de caisse?',
                    text:"",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor:'#3085d6',
                    cancelButtonColor:'#d33',
                    confirmButtonText:'Oui '
                }).then ((result)=>{
                    if (result.value){
                        $.ajax({
                            url : '/journal',
                            type : "get",

                        });

                        Swal.fire('Effectué',
                            'Journal de caisse bien enregistré')
                        window.location='/ventesimple'
                    }
                });
            }
            else {
                if (data==3) {

                    window.location='/ventesimple'

                }
            }
        },
        error : function(data){
            Swal.fire('Impossible',
                'Cette vente a été deja éffectuée',
                'info')
        }
    });

})



$('#btnventecredit').on('click',function (e) {
    $.ajax({
        url : '/verification',
        type : "get",

        contentType: false,
        processData: false,
        success : function(data) {
            if (data==1  || data==2){
                Swal.fire({
                    position: 'center',
                    title: 'Voulez-vous creer un journal de caisse?',
                    text:"",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor:'#3085d6',
                    cancelButtonColor:'#d33',
                    confirmButtonText:'Oui '
                }).then ((result)=>{
                    if (result.value){
                        $.ajax({
                            url : '/journal',
                            type : "get",

                        });

                        Swal.fire('Effectué',
                            'Journal de caisse bien enregistré')
                        window.location='/ventecredit'
                    }
                });
            }
            else {
                if (data==3) {

                    window.location='/ventecredit'

                }
            }

        },
        error : function(data){
            Swal.fire(' Action impossible',
                'info')
        }
    });

})

$('#btnventenonlivre').on('click',function (e) {
    $.ajax({
        url : '/verification',
        type : "get",

        contentType: false,
        processData: false,
        success : function(data) {
            if (data==1  || data==2){
                Swal.fire({
                    position: 'center',
                    title: 'Voulez-vous creer un journal de caisse?',
                    text:"",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor:'#3085d6',
                    cancelButtonColor:'#d33',
                    confirmButtonText:'Oui '
                }).then ((result)=>{
                    if (result.value){
                        $.ajax({
                            url : '/journal',
                            type : "get",

                        });

                        Swal.fire('Effectué',
                            'Journal de caisse bien enregistré')
                        window.location='/ventenonlivre'
                    }
                });
            }
            else {
                if (data==3) {

                    window.location='/ventenonlivre'

                }
            }

        },
        error : function(data){
            Swal.fire(' Action impossible',
                'info')
        }
    });

})

$('#btnventegros').on('click',function (e) {
    $.ajax({
        url : '/verification',
        type : "get",

        contentType: false,
        processData: false,
        success : function(data) {
            if (data==1 ||data==2){
                Swal.fire({
                    position: 'center',
                    title: 'Voulez-vous creer un journal de caisse?',
                    text:"",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor:'#3085d6',
                    cancelButtonColor:'#d33',
                    confirmButtonText:'Oui '
                }).then ((result)=>{
                    if (result.value){
                        $.ajax({
                            url : '/journal',
                            type : "get",

                        });

                        Swal.fire('Effectué',
                            'Journal de caisse bien enregistré')
                        window.location='/ventegros'
                    }
                });
            }
            else {
                if (data==3) {

                    window.location='/ventegros'

                }
            }
        },
        error : function(data){
            Swal.fire('Impossible',
                'Cette vente a été deja éffectuée',
                'info')
        }
    });

})

$('#btndevis').on('click',function (e) {
    Swal.fire({
        text:"",
        icon: 'info',
        title: 'Veuillez choisir le type de devis a créer',
        showDenyButton: true,
        showCancelButton: true,
        cancelButtonText: 'Fermer',
        confirmButtonText: 'Devis details',
        confirmButtonColor:'#5bc0de',
        denyButtonText: 'Devis de gros ',
        denyButtonColor:'#3085d6',
    }).then((result) => {
        if (result.isConfirmed) {
            window.location='/devisvente'
        } else if (result.isDenied) {
            window.location='/devisventegros'
        }
    });
})
