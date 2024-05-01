

var produitTable;
var venteTable;

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

$(function () {
    produitTable =   $('#produitTable').DataTable({
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

        ajax: '/allmodele',
        "columns": [
            {data: "produit.nom",name : 'produit.nom'},
            {data: "libelle",name : 'libelle'},
            {data: "condi_modele",name : 'condi_modele'},
            {data: "quantite",name : 'quantite'},
            {data: "prix",name : 'prix'},
            {data: "prix_de_gros",name : 'prix_de_gros'},
            {data: "prix_achat",name : 'prix_achat'},
            {data: "seuil",name: 'seuil'},
            {data: "action", name : 'action' , orderable: false, searchable: false}
        ]

    });


});
$('#btnproduit').on('click', function(){

    $('.modal-title-user').text('ENREGISTREMENT DU PRODUIT');
    $('#idmodele').val(null);
    $('#famille').val(null);
    $('#btnadd').text('Valider');
    $('#btnadd').removeClass('btn-warning');
    $('#btnadd').addClass('btn-primary');
    $('#modele').val(null);
    $('#quantite').val(null);
    $('#prix').val(null);
    $('#prixDeGros').val(null);
    $('#condi_modele').val(null);
    $('#prixAchat').val(null);
    $('#seuil').val(null);
    $('#categorie').val(null);
    $('#ajout_produit').modal('show');
});

//post des données
$('#ajout_produit  form').on('submit', function (e) {

    let url,message;
    if (!$('#idmodele').val()){
        url = '/ajoutmodele'
        message = 'modele enregistré'

    }
    else{
        url = '/updatemodele'
        message = 'modele modifié'

    }
    e.preventDefault();
    if (e.isDefaultPrevented()){
        $.ajax({
            url : url ,
            type : "post",
            // data : $('#modal-form-user').serialize(),
            data: new FormData($("#ajout_produit form")[0]),
            //data: new FormData($("#modal-form-user")[0]),
            contentType: false,
            processData: false,
            success : function(data) {

                sweetToast('success',message);

                $('#ajout_produit').modal('hide');

                produitTable.ajax.reload();
            },
            error : function(data){
                alert('erreur')
            }
        });
    }



});


function showmodele(id){

let message;
    $.ajax({
        url: '/showmodele-'+id,
        type: "get",
        success : function(data) {
            $('#modal-user-title').text('PRODUIT : '+data.produit.nom + ' - ' + data.libelle);
            $('#sNum').text(data.numero)
            $('#sNom').text(data.produit.nom)
            $('#sModele').text(data.libelle);
            $('#sQuantite').text(data.quantite);
            $('#sPrix').text(data.prix);
            $('#sPrixDeGros').text(data.prix_de_gros);
            $('#sPrixAchat').text(data.prix_achat);
            $('#sSeuil').text(data.seuil);
            $('#sCondi').text(data.condi_modele);
            $('#sCreate').text(data.created_at);
            $('#sUpdate').text(data.updated_at);
            $('#detailproduit').modal('show');

            $('#venteTable').DataTable().destroy()

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

                    ajax: '/allmodelevente-'+id,
                    "columns": [
                        {data: "user",name : 'user'},
                        {data: "date",name : 'date'},
                        {data: "quantite",name : 'quantite'},
                        {data: "montant",name : 'montant'},
                        {data: "numero",name : 'numero'},
                    ]
                });

        },
        error : function(data){
            message='Une erreur est produite. Veuillez recommancer';
            sweetToast('warning',message);
        }
    })
}
function editmodele(id){
    $.ajax({
        url : '/showmodele-'+id,
        type : "get",
        success : function(data) {

            $('#idmodele').val(data.id);
            $('#btnadd').text('Modifier');
            $('#btnadd').removeClass('btn-primary');
            $('#btnadd').addClass('btn-warning');
            $('.modal-title-user').text('Modifier les informations de : '+data.produit.nom+': '+data.libelle);
            $('#nom').val(data.nom);
            $('#modele').val(data.libelle);
            $('#quantite').val(data.quantite);
            $('#prix').val(data.prix);
            $('#prixDeGros').val(data.prix_de_gros);
            $('#condi_modele').val(data.condi_modele);
            $('#prixAchat').val(data.prix_achat);
            $('#categorie').val(data.produit.categorie_id);
			var produiId = data.produit.id;

			$.ajax({
				url: '/recupererproduit-' + $('#categorie').val(),
				type: "get",
				success: function (data) {
					$('#famille').empty();
					$('#famille').append('<option value=""></option>')

					for (var i = 0; i < data.length; i++) {

						$('#famille').append('<option value="'+data[i].id+'">'+data[i].nom+'</option>')
					}
					$('#famille').val(produiId);

				},
				error: function (data) {
					console.log("erreur")
				},
			})

            $('#seuil').val(data.seuil);
            $('#ajout_produit').modal('show');

        },
        error : function(data){
            alert('erreur')
        }
    });
}

function showmodele(id){
    $.ajax({
        url : '/modelelivraisons-'+id,
        type : "get"
    });
}

function deletemodele(id){
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
                url : '/deletemodele-'+id,
                type : "get",

                contentType: false,
                processData: false,
                success : function(data) {

                    produitTable.ajax.reload();

                },
                error : function(data){

                }
            });
            Swal.fire('Effacé',
                'Produit bien effacé',
                'success')
        }
    });
}

