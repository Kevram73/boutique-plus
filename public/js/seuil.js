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

var seuilTable;

$(function () {
    // Enlever la fonction alerte dans la boutique principale.
    getSeuil();
});

function getSeuil()
{

    $.ajax({
        url: '/allseuil',
        type: "get",
        success : function(data) {
            if(data.recordsTotal > 0){
                seuilTable =   $('#seuilTable').DataTable({
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

                    ajax: '/allseuil',
                    "columns": [
                        {data: "produit",name : 'produit'},
                        {data :  "libelle",name : 'libelle'},
                        {data: "quantite",name: 'quantite'},
                        {data :  "seuil",name : 'seuil'},
                        {data :  "prix",name : 'prix'}
                    ]
                });

                $('#seuilModal').modal('show');
            }
        },
        error : function(data){
            sweetToast("Une erreur c'est produite.")
        }
    });
}
