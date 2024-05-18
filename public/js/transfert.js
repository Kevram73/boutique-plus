var newReception;
var BreakException = {};

var transfertTable;
var receptionTable;

var produitTransfertTable;
var tproduitTransfertTable;

var produitReceptionTable;
var tproduitReceptionTable;

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

$('#categorie').on('change',function ( ) {
    $.ajax({
        url: '/recupererproduit-' + $('#categorie').val(),
        type: "get",
        success: function (data) {
            $('#famille').empty();
            $('#famille').append('<option value=""></option>')

            for (var i = 0; i < data.length; i++) {

                $('#famille').append('<option value="'+data[i].id+'">'+data[i].nom+'</option>')
            }

        },
        error: function (data) {
            console.log("erreur")
        },
    })
})

$('#famille').on('change',function ( ) {
    $.ajax({
        url: '/recuperermodele-' + $('#famille').val(),
        type: "get",
        success: function (data) {
            $('#modele').empty();
            $('#modele').append('<option value=""></option>')

            for (var i = 0; i < data.length; i++) {

                $('#modele').append('<option value="'+data[i].id+'">'+data[i].libelle+'</option>')
            }

        },
        error: function (data) {
            console.log("erreur")
        },
    })
})

$('#modele').on('change',function ( ) {
    const data = $('#modele').val().split('|');
    $('#stock').val(data[1]);
})

$('#magasin').on('change',function ( ) {
    $('#idmagasin').val($('#magasin').val());
})

//
$(function () {

    transfertTable =   $('#transfertTable').DataTable({
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
        ajax: '/alltransfert',
        "columns": [

            {data: "code",name : 'code'},
            {data: "magasin_reception.nom",name : 'magasin'},
            {data: "created_at",name : 'date'},
            {data: "action", name : 'action' , orderable: false, searchable: false}
        ]

    });


});

$(function () {

    receptionTable =   $('#receptionTable').DataTable({
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
        ajax: '/allreception',
        "columns": [

            {data: "code",name : 'code'},
            {data: "magasin_transfert.nom",name : 'magasin'},
            {data: "created_at",name : 'date'},
            {data: "action", name : 'action' , orderable: false, searchable: false}


        ]

    });


});

//
$(function( ) {

    'use strict';
    produitTransfertTable = $('#produitTransfertTable').DataTable({
        'paging': true,
        'lengthChange': true,
        'searching': false,
        'ordering': false,
        'info': true,
        'autoWidth': false,
        select: true,
        "order": [[ 1, 'asc' ]],
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
        data : [],
        columns: [
            { data: 'id' },
            { data: 'produit' },
            { data: 'stock' },
            { data: 'quantite' },
        ]
    });
})
$(function( ) {

    'use strict';
})

//
$(function( ) {

    'use strict';
    tproduitTransfertTable = $('#tproduitTransfertTable').DataTable({
        'paging': true,
        'lengthChange': true,
        'searching': false,
        'ordering': false,
        'info': true,
        'autoWidth': false,
        select: true,
        "order": [[ 1, 'asc' ]],
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
        data : [],
        columns: [
            { data: 'id' },
            { data: 'produit' },
            { data: 'quantite' },
        ]
    });
})
$(function( ) {

    'use strict';
    tproduitReceptionTable = $('#tproduitReceptionTable').DataTable({
        'paging': true,
        'lengthChange': true,
        'searching': false,
        'ordering': false,
        'info': true,
        'autoWidth': false,
        select: true,
        "order": [[ 1, 'asc' ]],
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
        data : [],
        columns: [
            { data: 'id' },
            { data: 'produit' },
            { data: 'quantite' },
        ]
    });
})

//
$('#btnselect').on('click',function () {
    let message;
        if( ($('#quantite').val() - $('#stock').val())>0 ){
            message='Quantite superieure au stock!'
            sweetToast('warning',message);
        } else{
        if($('#modele').val() ==null   || $('#quantite').val()==''  || $('#quantite').val()<=0 ){
            message='Veuillez bien remplir tous les champs svp!'
            sweetToast('warning',message);
        }
        else{


                    var d=document.getElementById('famille')
                    var b=document.getElementById('modele')
                    var famille=d.options[d.selectedIndex].text;
                    var modele=b.options[b.selectedIndex].text;
                    const data = $('#modele').val().split('|');
                    produitTransfertTable.row.add({
                        "id":data[0],
                        "produit": famille + " -> " + modele,
                        "stock": 5000,
                        "quantite": $('#quantite').val(),
                    }).draw()


                    $('#categorie').val(null);
                    $('#famille').empty();
                    $('#modele').empty();
                    $('#livraison').empty();
                    $('#quantite').val(null);
                    $('#stock').val(null);

            }
        }

})

//
$('#btntransfert').on('click', function(){
    $('.modal-title-user').text('ENREGISTREMENT DU TRANSFERT');

    $('#categorie').val(null);
    $('#famille').empty();
    $('#modele').empty();
    $('#quantite').val(null);
    $('#stock').val(null);

    $('#btnadd').text('Valider transfert');
    $('#btnadd').removeClass('btn-warning');
    $('#btnadd').addClass('btn-primary');
    $('#ajout_transfert').modal('show');
});

function showreception(id){
    $.ajax({
        url: '/showtransfert-'+id,
        type: "get",
        success : function(data) {
            $('#modal-title-reception').text('VALIDATION DU TRANSFERT : '+data.transfert.code);

            $('#r2magasin').text(data.transfert.magasin_transfert.nom);
            $('#r2date').text(data.transfert.created_at);
            $('#r2statut').removeClass('text-success');
            $('#r2statut').removeClass('text-warning');

            if(data.transfert.status === 0){
                $('#r2statut').addClass('text-warning');
                $('#r2statut').text('Traitement en cours..');
            } else {
                $('#r2statut').addClass('text-success');
                $('#r2statut').text('Marqué comme recu');
            }

            newReception = data.transfert_lignes;
            produitReceptionTable = $('#produitReceptionTable').DataTable({
                'paging': true,
                'lengthChange': true,
                'searching': false,
                'ordering': false,
                'info': true,
                'autoWidth': false,
                select: true,
                "order": [[ 1, 'asc' ]],
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
                ajax : '/showtransfertreception-'+id,
                columns: [
                    { data: 'id' },
                    { data: 'modele_libelle' },
                    { data: 'modele_qte' },
                    { data: 'action' },
                ]
            });

            $('#btnupdate').text('Valider réception');
            $('#btnupdate').removeClass('btn-warning');
            $('#btnupdate').addClass('btn-success');
            $('#ajout_reception').modal('show');
        },
        error : function(data){
            sweetToast('Une erreur c\'est produite. Veuillez recommencer')
        }
    })
}


//
$('#btnadd').on('click',function (e) {
    Swal.fire({
        position: 'center',
        title: 'Voulez-vous enregistrer le transfert?',
        text:"",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor:'#3085d6',
        cancelButtonColor:'#d33',
        confirmButtonText:'Oui '
    }).then ((result)=>{

        if (result.value){
            let url;

            url = '/ajouttransfert'


            e.preventDefault()

            if (produitTransfertTable.data().length <= 0 ){
                let message;
                message='Aucun produit sélectionné!'
                sweetToast('warning',message);

            } else if(!$('#magasin').val()){
                message='Veuillez sélectionner le magasin de réception!'
                sweetToast('warning',message);

            } else {
                let content =''
                for(let i = 0; i <  produitTransfertTable.data().length; i++){
                    if (i!=produitTransfertTable.data().length-1){
                        content +=   produitTransfertTable.data()[i].id+"|"+ produitTransfertTable.data()[i].produit+"|"+ produitTransfertTable.data()[i].stock+"|" + produitTransfertTable.data()[i].quantite+"|"

                    }else{
                        content +=  produitTransfertTable.data()[i].id+"|"+ produitTransfertTable.data()[i].produit+"|"+ produitTransfertTable.data()[i].stock +"|"+ produitTransfertTable.data()[i].quantite
                    }
                }
                $('#produitTransfertData').val(content)

                e.preventDefault();
                if (e.isDefaultPrevented()){
                    $.ajax({
                        url :url,
                        type : "post",
                        data: new FormData($("#produitTransfertForm form")[0]),
                        contentType: false,
                        processData: false,
                        success : function(data) {
                            window.location='/transferts';
                        },
                        error : function(data){
                            let message='Erreur ';
                            sweetToast('warning',data.responseJSON.msg);
                        }
                    });
                }
            }
        }
    });
})
$('#btnupdate').on('click',function (e) {
    let updateCount = 0;
        for (var i=0; i<newReception.length; i++) {
            if(newReception[i]?.id && $('#modele'+newReception[i].id).val()){
                newReception[i].modele_reception_id = $('#modele'+newReception[i].id).val() * 1;
                updateCount++;
            } else {
                sweetToast('warning', 'Veuillez renseigner tous les produits');
                break;
            }
        };
    if(newReception.length === updateCount){
        Swal.fire({
            position: 'center',
            title: 'Voulez-vous valider la réception du transfert?',
            text:"",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor:'#3085d6',
            cancelButtonColor:'#d33',
            confirmButtonText:'Oui '
        }).then ((result)=>{

            if (result.value){
                let url = '/updatetransfert';

                e.preventDefault();

                if (e.isDefaultPrevented()){
                    $.ajax({
                        url :url,
                        type : "post",
                        headers: {'X-CSRF-TOKEN': $('#idtransfert').attr('content')},
                        data: {data: JSON.stringify(newReception)},
                        success : function(data) {
                            window.location='/transferts';
                        },
                        error : function(data){
                            sweetToast('warning',data.responseJSON.msg);
                        }
                    });
                }
            }
        });
    }
})


//
function showtransfert(id){
    tproduitTransfertTable.clear();
    $.ajax({
        url: '/showtransfert-'+id,
        type: "get",
        success : function(data) {
            $('#modal-user-title').text('TRANSFERT : '+data.transfert.code);

            $('#tmagasin').text(data.transfert.magasin_reception.nom);
            $('#tdate').text(data.transfert.created_at);
            $('#tstatut').removeClass('text-success');
            $('#tstatut').removeClass('text-warning');

            if(data.transfert.status === 0){
                $('#tstatut').addClass('text-warning');
                $('#tstatut').text('Traitement en cours..');
            } else {
                $('#tstatut').addClass('text-success');
                $('#tstatut').text('Reception confirmée par le magasin');
            }

            data.transfert_lignes.forEach(tl => {
                tproduitTransfertTable.row.add({
                    "id": tl.id,
                    "produit": tl.modele_libelle,
                    "quantite": tl.modele_qte,
                }).draw()
            });


            $('#detailtransfert').modal('show');
        },
        error : function(data){
            sweetToast('Une erreur c\'est produite. Veuillez recommencer')
        }
    })
}

function showtransfert2(id){
    tproduitReceptionTable.clear();
    $.ajax({
        url: '/showtransfert-'+id,
        type: "get",
        success : function(data) {
            $('#modal-user-title-reception').text('TRANSFERT : '+data.transfert.code);

            $('#rmagasin').text(data.transfert.magasin_transfert.nom);
            $('#rdate').text(data.transfert.created_at);
            $('#rstatut').removeClass('text-success');
            $('#rstatut').removeClass('text-warning');

            if(data.transfert.status === 0){
                $('#rstatut').addClass('text-warning');
                $('#rstatut').text('Traitement en cours..');
                $('#titlerecevoir').text('Quantité à recevoir');
            } else {
                $('#rstatut').addClass('text-success');
                $('#rstatut').text('Marqué comme recu');
                $('#titlerecevoir').text('Quantité recu');
            }

            data.transfert_lignes.forEach(tl => {
                tproduitReceptionTable.row.add({
                    "id": tl.id,
                    "produit": tl.modele_libelle,
                    "quantite": tl.modele_qte,
                }).draw()
            });


            $('#detailreception').modal('show');
        },
        error : function(data){
            sweetToast('Une erreur c\'est produite. Veuillez recommencer')
        }
    })
}

function deletetransfert(id){
Swal.fire({
    position: 'center',
    title: 'Vous etes sûr',
    text:"Le transfert sera annulé",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor:'#3085d6',
    cancelButtonColor:'#d33',
    confirmButtonText:'Oui annuler'
}).then ((result)=>{
    if (result.value){
        $.ajax({
            url : '/deletetransfert-'+id,
            type : "get",
            contentType: false,
            processData: false,
            success : function(data) {
                transfertTable.ajax.reload();
                Swal.fire('Effacé',
                    'Transfert annulé',
                    'success')
            },
            error : function(data){
            }
        });

    }
});
}



