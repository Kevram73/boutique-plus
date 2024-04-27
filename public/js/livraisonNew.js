$('#credit').on('click', function(){

    $('.modal-title-user').text('LISTE DES CREANCIERS');
    $('#infocredit').modal('show');
});
var livraisonTable;




$(function () {

    livraisonTable =   $('#livraisonTable').DataTable({
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
        ajax: '/alllivraison',
        "columns": [

            {data: "numero",name : 'numero'},
            {data: "date_livraison",name : 'date_livraison'},
            {data: "action", name : 'action' , orderable: false, searchable: false}


        ]

    });
});


$('#produit').on('change',function ( ) {
    $.ajax({
        url: '/recupererfournisseur-' + $('#produit').val(),
        type: "get",
        success: function (data) {
            $('#provision').empty();
            $('#fournisseur').empty();
            $('#fournisseur').append('<option value=""></option>')

            for (var i = 0; i < data.length; i++) {

                $('#fournisseur').append('<option value="'+data[i].fournisseur.id+'">'+data[i].fournisseur.nom+'</option>')
            }

        },
        error: function (data) {
            console.log("erreur")
        },
    })
})





//post des données




function show(id){

    $.ajax({
        url: '/showlivraison-'+id,
        type: "get",
        success : function(data) {

            window.location='/detaillivraison-'+id

        },
        error : function(data){
            sweetToast('Une erreur c\'est produite. Veuillez recommancer')
        }
    })
}







