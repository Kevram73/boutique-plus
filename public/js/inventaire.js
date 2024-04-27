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
var inventaireTable;
var inventaireTablePending;
var inventaireTableRegularisation;

// Load data into invenatire valider.
$(function () {

    inventaireTable =   $('#inventaireTable').DataTable({
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
        ajax: '/allinventaire',
        "columns": [

            {data: "numero",name : 'numero'},
            {data: "date_inventaire",name : 'date'},
            {data: "nom",name : 'nom'},
            {data: "action", name : 'action' , orderable: false, searchable: false}


        ]

    });
});

// Load data into inventaire pending
$(function () {

    inventaireTablePending =   $('#inventaireTablePending').DataTable({
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
        ajax: '/allinventairepending',
        "columns": [

            {data: "numero",name : 'numero'},
            {data: "date_inventaire",name : 'date'},
            {data: "date_inventaire_prevu",name : 'date_inventaire_prevu'},
            {data: "nom",name : 'nom'},
            {data: "action", name : 'action' , orderable: false, searchable: false}
        ]

    });
});



// Load data into non regulated inventaire.
$(function () {

    inventaireTableRegularisation =   $('#inventaireTableRegularisation').DataTable({
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
        ajax: '/inventaire_non_regulated',
        "columns": [

            {data: "numero",name : 'numero'},
            {data: "date_inventaire",name : 'date'},
            {data: "nom",name : 'nom'},
            {data: "action", name : 'action' , orderable: false, searchable: false}
        ]

    });
});


$('#info').on('click', function(){

    $('.modal-title-user').text('LISTE DES PRODUITS A APPROVISIONNER');
    $('#infoproduit').modal('show');
});



//post des données

function showinventaire(id){
console.log(" valeur de id : "+id) ;
    $.ajax({
        url: '/showinvent-'+id,
        type: "get",
        success : function(data) {
         window.location='/detailinventaire-'+id

        },
        error : function(data){

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
            url : '/deleteinventaire-'+id,
                type : "get",

                contentType: false,
                processData: false,
                success : function(data) {
                Swal.fire('Effacé',
                    'Fichier bien effacé',
                    'success')
                    inventaireTable.ajax.reload();
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

$('#btninventaire').on('click',function (e) {
    window.location='/newinventaire'
});

$('#btnfermer').on('click',function (e) {
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

$('#choix').on('change',function ( ) {
    if ($('#choix').val()=="categorie"){
        $('#cate').show();
        $('#inventaireTable').DataTable().destroy()
        $.ajax({
            url: '/recuperercategorie' ,
            type: "get",
            success: function (data) {
                $('#categorie').empty()
                $('#categorie').append('<option "></option>')

                for (var i = 0; i < data.length; i++) {
                    $('#categorie').append('<option value="'+data[i].id+'">'+data[i].nom+'</option>')
                }

            },
            error: function (data) {
                console.log("erreur")
            },
        })
    }else{
        $('#cate').hide();
    }
});

$('#btnAddInventaire').on('click', function(){
    $('.modal-title-user').text('CREER UN INVENTAIRE');
    $('#choix').val(null);
    $('#cate').val(null);
    $('#date_prev').val(null);
    $('#btnadd').text('Valider');
    $('#btnadd').removeClass('btn-warning');
    $('#btnadd').addClass('btn-primary');
    $('#produit').val(null);
    $('#ajout_inventaie').modal('show');
});

//post des données
$('#ajout_inventaie form').on('submit', function (e) {
    let url, message;
    url = '/createinventaire';
    message = 'Inventaire enregistré';

    e.preventDefault();
    if (e.isDefaultPrevented()) {
        const formData = new FormData($("#ajout_inventaie form")[0]);

        // Affichez les données dans la console
        for (var pair of formData.entries()) {
            console.log(pair[0] + ', ' + pair[1]);
        }

        $.ajax({
            url: url,
            type: "post",
            data: formData,
            contentType: false,
            processData: false,
            success: function (data) {
                $('#ajout_inventaie').modal('hide');
                sweetToast('success', message);
                inventaireTablePending.ajax.reload();
            },
            error: function (data) {
                alert('Erreur de génération du PDF');
            }
        });
    }
});

function deleteinventaire(id){
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
                url : '/deleteinventaire/'+id,
                type : "get",

                contentType: false,
                processData: false,
                success : function(data) {

                    console.log(data)

                    inventaireTablePending.ajax.reload();
                    Swal.fire('Effacé',
                        'Inventaire bien effacé',
                        'success')
                },
                error : function(data){
                    console.log(data)
                }
            });

        }
    });
}
