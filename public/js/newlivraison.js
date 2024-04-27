$('#info').on('click', function(){

    $('.modal-title-user').text('LISTE DES PRODUITS A APPROVISIONNER');
    $('#infoproduit').modal('show');
});
$('#credit').on('click', function(){

    $('.modal-title-user').text('LISTE DES CREANCIERS');
    $('#infocredit').modal('show');
});

var $table2


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

$("#produit").select2( {
    placeholder: "Choisir un produit",
    allowClear: true
} );

$("#commande").select2( {
    placeholder: "Choisir la commande",
    //allowClear: true
} );

$("#modele").select2( {
    placeholder: "Choisir le modele",
    allowClear: true
} );



$('#commande').on('change',function ( ) {
    $.ajax({
        url: '/recuperercommandemodele-' + $('#commande').val(),
        type: "get",
        success: function (data) {
            $('#quantite').empty();
            $('#produit').empty();
            $('#produit').append('<option value=""></option>')

            for (var i = 0; i < data.length; i++) {
                if (data[i].etat==false){
                    $('#produit').append('<option value="'+data[i].id+'">'+data[i].produit+"-"+data[i].modele+'</option>')
                }
            }
        },
        error: function (data) {
            console.log("erreur")
        },
    })
})
$('#produit').on('change',function ( ) {
    $.ajax({
        url: '/verification-' + $('#produit').val(),
        type: "get",
        success: function (data) {
            $('#quant').val(data);
        },
        error: function (data) {
            console.log("erreur")
        },
    })
})



$(function( ) {

    'use strict';

    var datatableInit = function() {

        $table2 = $('#livraisonTable').DataTable({
            'paging': true,
            'lengthChange': true,
            'searching': true,
            'ordering': true,
            'info': true,
            'autoWidth': false,
            select: true,
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

    };

    $(function() {
        datatableInit();
    });

})
$('#ajout').on('click',function () {
    let message,quantite;

        $.ajax({
            url: '/verification-' + $('#produit').val(),
            type: "get",
            success: function (data) {
                quantite=$('#quantite').val()-data
                if ( quantite>0){
                    message='Quantité saisie supérieure a la commande'
                    sweetToast('warning',message);
                }
                else {
                    var dEmporte , position
                    let trouveEmporte = false;
                    for(let i = 0; i <  $table2.data().length; i++){
                        let  data = $table2.data()[i]
                        if (data.id == $('#produit').val()) {
                            trouveEmporte = true;
                            position = i;
                        }
                    }

                    if ( trouveEmporte === false) {
                        var d=document.getElementById('produit')
                        var produit=d.options[d.selectedIndex].text;
                        $table2.row.add({
                            "id":$('#produit').val(),
                            "produit": produit,
                            "quantite": $('#quantite').val(),
                            // "action": '<a  class="btn btn-primary"><i class="fa fa-pencil"  id="edit"></i></a>' +
                            //     '  <a class="btn btn-danger" ><i class="fa fa-trash-o" id="sup"></i></a>'
                        }).draw()
                        let message;
                        message='Ajouter au tableau'
                        // sweetToast('success',message);
                        $('#quantite').val(null);

                    }else{
                        $table2.data()[position].quantite = parseInt( $table2.data()[position].quantite) + parseInt( $('#quantite').val()) ;

                        $table2 .row().data($table2.data()[position]).draw();

                        trouveEmporte = true;
                        let message;
                        message='Quantite mise a jour'
                        sweetToast('info',message);
                    }
                }
            },
            error: function (data) {
                console.log("erreur")
            },
        })


        {

        }

})
$('#annuler').on('click',function () {
  let  message='Annuler'
    sweetToast('warning',message);


    $('#quantite').val(null);
})


$('#valider').on('click',function (e) {

    e.preventDefault()

    if ($table2.data().length <= 0 ){
        let message;
        message='Impossible ... Tableau vide!!!'
        sweetToast('warning',message);
    }else{

        Swal.fire({
            position: 'center',
            title: 'Voulez-vous enregistrer la livraison?',
            text:"",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor:'#3085d6',
            cancelButtonColor:'#d33',
            confirmButtonText:'Oui '
        }).then ((result)=>{
            if (result.value){
                let content =''
                for(let i = 0; i <  $table2.data().length; i++){
                    if (i!=$table2.data().length-1){
                        content +=   $table2.data()[i].id+","+  $table2.data()[i].quantite+","

                    }else{
                        content +=  $table2.data()[i].id+","+  $table2.data()[i].quantite
                    }
                }
                $('#livTable').val(content)
                $.ajax({
                    url :'storelivraison',
                    type : "post",
                    // data : $('#modal-form-user').serialize(),
                    data: new FormData($("#livform form")[0]),
                    //data: new FormData($("#modal-form-user")[0]),
                    contentType: false,
                    processData: false,
                    success : function(data) {
                    let message='Livraison enregistrée';
                        sweetToast('success',message);
                        window.location='/livraison'

                    },
                    error : function(data){

                    }
                });
            }
        });
    }

})

var index;

$('#livraisonTable tbody').on( 'click', 'tr', function () {
    if ( $(this).hasClass('selected') ) {
        $(this).removeClass('selected');
    }
    else {
        $table2.$('tr.selected').removeClass('selected');
        $(this).addClass('selected');
        index=$table2.row('.selected').index()
    }
} );

$('#sup').on('click',function () {
    let  message='Supprimer'
    $table2.row('.selected').remove().draw( false );
    sweetToast('success',message);
})

