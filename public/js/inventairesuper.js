

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

//alert('inventaire');


$('#product').on('change',function ( ) {
   
    $('#boutiqueTable').DataTable().destroy()
    $(function () {
        boutiqueTable=$('#boutiqueTable').DataTable({
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


            ajax: '/allinventairesuper-'+  $('#product').val(),
            "columns": [
                {data: "nom",name : 'nom'},
                {data :  "famille",name : 'famille'},
                {data: "libelle",name: 'libelle'},
                {data :  "valeur",name : 'valeur'},
                {data :  "condi_modele",name : 'condi_modele'},
                {data: "boutique",name : 'boutique'},
                {data :  "quantite",name : 'quantite'},
                {data: "qte_tonne",name: 'qte_tonne'}

            ]

        });
    });
});


// Récupérez la liste déroulante et l'en-tête h1
const product = document.getElementById('product');
const resultHeader = document.getElementById('result');

// Ajoutez un gestionnaire d'événements pour le changement de sélection
product.addEventListener('change', function() {
    // Récupérez la valeur sélectionnée
    
    //alert(product.value);
    
    const id = product.value;

    // Effectuez une requête AJAX pour obtenir les éléments en fonction de l'ID sélectionné
    fetch(`/calculate/${id}`)
        .then(response => response.json())
        .then(data => {
            // Vérifiez s'il y a des erreurs dans la réponse
            if (data.error) {
                resultHeader.textContent = `Erreur : ${data.error}`;
            } else {
                // Mettez à jour le contenu de l'en-tête h1 avec les éléments de la base de données
                resultHeader.textContent = `Valeur Totale : ${JSON.stringify(data.total)} F`;
            }
        })
        .catch(error => {
            console.error('Erreur lors de la requête AJAX :', error);
        });
});
$('#btnboutique').on('click', function(){
    $('.modal-title-user').text('ENREGISTREMENT DU PRIX / TONNE');
    $('#idboutique').val(null);
    $('#nom').val(null);
    $('#adresse').val(null);
    $('#telephone').val(null);
    $('#btnadd').text('Valider');
    $('#btnadd').removeClass('btn-warning');
    $('#btnadd').addClass('btn-primary');
    $('#ajout_boutique').modal('show');
});

$("#product").select2( {
    placeholder: "Choisir un produit",
    allowClear: true
} );




/* $(function () {
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

        ajax: 
        {
            url: '/allinventairesuper',
            data: {
                'produit':$("#product").val(),
               
            }
        },
        
        "columns": [
           
            {data: "nom",name : 'nom'},
            {data :  "famille",name : 'famille'},
            {data: "libelle",name: 'libelle'},
            {data :  "valeur",name : 'valeur'},
            {data :  "condi_modele",name : 'condi_modele'},
            {data: "boutique",name : 'boutique'},
            {data :  "quantite",name : 'quantite'},
            {data: "qte_tonne",name: 'qte_tonne'},
        ]

    });


}); */

$("#product").on('change', function(){
   // setDataTable();
});
function setDataTable(){
    $('#boutiqueTable').DataTable().destroy()
    var produit = $("#product").val();
    $.ajax({
        url : '/allreportventsuper',
        type : "get",
        data: {
            'produit':produit,
        },
        success : function(data) {
            console.log(data)
            $('#boutiqueTable').DataTable({
                data: data
            });
        },
        error : function(data){
            alert('erreur')
        }
    });
}

$(document).ready(function() {
$('#boutiqueTable').DataTable();
} );
var modal = $('.Recherche');
$('.logo').click(function() {
    modal.show();
});



//post des données
$('#ajout_boutique  form').on('submit', function (e) {
    //alert($('#boutique').val());
    let url,message;
   // if (!$('#boutique').val()){
        url = '/updatesuperinventaire'
        message = 'Prix tonnage du Produit  enregistrée'
  //  }

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
                message = 'Erreur dans la sauvegarde du produit '
                sweetToast('warning',message);
            }
        });
    }



});




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




