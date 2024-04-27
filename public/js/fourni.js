

var fourniTable;


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

    fourniTable =   $('#fourniTable').DataTable({
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
        ajax: '/allfourni',
        "columns": [

            {data: "fournisseur",name : 'fournisseur'},
            {data: "produit",name: 'produit'},
            {data: "modele",name: 'modele'},
            {data: "prix",name: 'prix'},
            {data: "action", name : 'action' , orderable: false, searchable: false}


        ]

    });


});
$('#categorie').on('change',function ( ) {
    $.ajax({
        url: '/recupererproduit-' + $('#categorie').val(),
        type: "get",
        success: function (data) {
            $('#produit').empty();
            $('#modele').empty();
            $('#produit').append('<option value=""></option>')

            for (var i = 0; i < data.length; i++) {

                $('#produit').append('<option value="'+data[i].id+'">'+data[i].nom+'</option>')
            }

        },
        error: function (data) {
            console.log("erreur") 
        },
    })
})

$('#produit').on('change',function ( ) {
    $.ajax({
        url: '/recuperermodele-' + $('#produit').val(),
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
    $.ajax({
        url: '/recupererfournisseurP',
        type: "get",
        success: function (data) {
            $('#fournisseurP').empty();
            $('#fournisseurP').append('<option value=""></option>')

            for (var i = 0; i < data.length; i++) {
                $('#fournisseurP').append('<option value="'+data[i].id+'">'+data[i].nom+'</option>')
            }

        },
        error: function (data) {
            console.log("erreur")
        },
    })
})

$('#btnfournisseurP').on('click', function(){
    $('.modal-title-user').text('ENREGISTREMENT DU PROUDUIT / FOURNISSEUR');
    $('#idfournisseur_produit').val(null);
    $('#fournisseurP').val(null);
    $('#prix').val(null);
    $('#btnadd').text('Valider');
    $('#btnadd').removeClass('btn-warning');
    $('#btnadd').addClass('btn-primary');
    $('#produit').val(null);
    $('#ajout_fournisseurP').modal('show');
});

//post des données
$('#ajout_fournisseurP  form').on('submit', function (e) {

    let url,message;
    if (!$('#idfournisseur_produit').val()){
        url = '/ajoutfourni'
        message = 'Enregistrement reussi'

    }
    else{
        url = '/updatefourni'
        message = 'Modification reussi'

    }
    e.preventDefault();
    if (e.isDefaultPrevented()){
        $.ajax({
            url : url ,
            type : "post",
            // data : $('#modal-form-user').serialize(),
            data: new FormData($("#ajout_fournisseurP form")[0]),
            //data: new FormData($("#modal-form-user")[0]),
            contentType: false,
            processData: false,
            success : function(data) {
                sweetToast('success',message);

                $('#ajout_fournisseurP').modal('hide');

                fourniTable.ajax.reload();
            },
            error : function(data){
                alert('erreur')
            }
        });
    }



});

function showfourni(id){

    $.ajax({
        url: '/showfourni-'+id,
        type: "get",
        success : function(data) {
            $('#modal-user-title').text('FOURNISSEUR / PRODUIT ');
            $('#sFournisseur').text(data[0].fournisseur);
            $('#sProduit').text(data[0].produit);
            $('#sModele').text(data[0].modele);
            $('#sPrix').text(data[0].prix);
            $('#Create').text(data[0].created);
            $('#Update').text(data[0].updated);
            $('#detailfourni').modal('show');
        },
        error : function(data){
          alert('erreur show')
        }
    })
}


function editfourni(id){
    $.ajax({
        url : '/showfourni-'+id,
        type : "get",
        success : function(data) {
            $('#idfournisseur_produit').val(data[0].id);
            $('#categorie').val(data[0].idcategorie);
            $('#produit').val(data[0].idproduit);
            $('#modele').val(data[0].idmodele);
            $('#fournisseurP').val(data[0].idfournisseur);
            $('#prix').val(data[0].prix);
            $('#btnadd').text('Modifier');
            $('#btnadd').removeClass('btn-primary');
            $('#btnadd').addClass('btn-warning');
            $('.modal-title-user').text('Modifier les informations de : '+data[0].produit+' '+data[0].modele);
            $('#ajout_fournisseurP').modal('show');

        },
        error : function(data){
            alert('erreur edit')
        }
    });
}


function deletefourni(id){
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
                url : '/deletefourni-'+id,
                type : "get",

                contentType: false,
                processData: false,
                success : function(data) {

                    fourniTable.ajax.reload();
                    Swal.fire('Effacé',
                    'Fichier bien effacé',
                    'success')
                },
                error : function(data){
                    alert('erreur delete')
                }
            });

        }
    });
}


$('#credit').on('click', function(){

    $('.modal-title-user').text('LISTE DES CREANCIERS');
    $('#infocredit').modal('show');
});
