$('#credit').on('click', function(){
    $('.modal-title-user').text('LISTE DES CREANCIERS');
    $('#infocredit').modal('show');
});
var livraisonNTableBoutique;




$(function () {

    livraisonNTableBoutique =   $('#livraisonNTableBoutique').DataTable({
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
        ajax: '/alllivraisonNew',
        "columns": [
            {data: "date_livraison",name : 'date_livraison'},
            {data: "numero",name : 'numero'},
            {data: "nom",name : 'nom'},
            {data: "action", name : 'action' , orderable: false, searchable: false}


        ]

    });
});

$('#commande').on('change',function ( ) {
    $.ajax({
        url: '/recupercommande-' + $('#commande').val(),
        type: "get",
        success: function (data) {
            $('#quantite').empty();
            $('#produit').empty();
            $('#produit').append('<option value=""></option>')

            for (var i = 0; i < data.length; i++) {

                    $('#produit').append('<option value="'+data[i].id+'">'+data[i].numero+'</option>')

            }
        },
        error: function (data) {
            console.log("erreur")
        },
    })
})

$('#boutique').on('change',function ( ) {
    $.ajax({
        url: '/recuperboutique-' + $('#boutique').val(),
        type: "get",
        success: function (data) {
            //$('#quantite').empty();
            //$('#produit').empty();
            //$('#produit').append('<option value=""></option>')

            for (var i = 0; i < data.length; i++) {

                    $('#produit').append('<option value="'+data[i].id+'">'+data[i].nom+'</option>')

            }
        },
        error: function (data) {
            console.log("erreur")
        },
    })
})

function showlivNew(id){

    $.ajax({
        url: '/showlivNew-'+id,
        type: "get",
        success : function(data) {
            $('#modal-user-title').text('LIVRAISON / AUTRES BOUTIQUES ');
            $('#sDate').text(data[0].date_livraison);
            $('#sboutique').text(data[0].nom);
            $('#scommande').text(data[0].numero);
            //$('#sPrix').text(data[0].prix);
            $('#Create').text(data[0].created);
            $('#Update').text(data[0].updated);
            $('#detaillivraisonNew').modal('show');
        },
        error : function(data){
          alert('erreur show')
        }
    })
}


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






/*
function show(id){

    $.ajax({
        url: '/showlivraison2-'+id,
        type: "get",
        success : function(data) {

            window.location='/showlivraison2-'+id

        },
        error : function(data){
            sweetToast('Une erreur c\'est produite. Veuillez recommancer')
        }
    })
} */

$("#reset").on('click', function(){
    $("#production").val(0);
    $("#debut").val('');
    $("#fin").val('');
    $("#fournisseur").val(0);
    getSum();
});

$("#debut").on('change', function(){
    $("#fin").attr('min', $(this).val());
    getSum();
});

$("#fin").on('change', function(){
    $("#debut").attr('max', $(this).val());
    getSum();
});


function setNumeralHtml(element, format, surfix="", type="html")
{
    var prices = $("."+element);

    for(var i=0; i<prices.length; i++)
    {
        if(type=="html")
        {
            var number = numeral(prices[i].innerText);

            var string = number.format(format);
            prices[i].innerText = string+" "+surfix;
        }else if(type=="value")
        {
            var number = numeral(prices[i].value);

            var string = number.format(format);
            prices[i].value = string+" "+surfix;
        }

    }
  
}

$("#voir").on('click', function(){
    getSum();
});

 
$("#fournisseur").select2( {
    placeholder: "Choisir une boutique",
    allowClear: true
} );
    $("#production").select2( {
        placeholder: "Choisir un produit",
        allowClear: true
    } ); 


$("#production").on('change', function(){
       // alert('SOMME');

    getSum();
});

$("#modeles").select2( {
    placeholder: "Choisir un produit",
    //allowClear: true
} );

$("#categorie").select2( {
    placeholder: "Choisir la catégorie",
    //allowClear: true
} );

 

$('#categorie').on('change',function ( ) {
    $.ajax({
        url: '/recupererproduit-' + $('#categorie').val(),
        type: "get",
        success: function (data) {
            $('#modeles').empty();
            $('#production').empty();
            $('#modeles').append('<option value=""></option>')

            for (var i = 0; i < data.length; i++) {

                $('#modeles').append('<option value="'+data[i].id+'">'+data[i].nom+'</option>')
            }

        },
        error: function (data) {
            console.log("erreur")
        },
    })
});

$('#modeles').on('change',function ( ) {
    $.ajax({
        url: '/recuperermodele-' + $('#modeles').val(),
        type: "get",
        success: function (data) {
            $('#production').empty();
            $('#production').append('<option value=""></option>')

            for (var i = 0; i < data.length; i++) {

                $('#production').append('<option value="'+data[i].id+'">'+data[i].libelle+'</option>')
            }

        },
        error: function (data) {
            console.log("erreur")
        },
    })
})

 $('#fournisseur').on('change',function ( ) {
     getSum();
    $.ajax({
        url: '/recuperermodeleboutiq-' + $('#fournisseur').val(),
        type: "get",
        success: function (data) {
           // alert(data);
            $('#production').empty();
            $('#production').append('<option value=""></option>')

            for (var i = 0; i < data.length; i++) {

                $('#production').append('<option value="'+data[i].id+'">'+data[i].libelle+'</option>')
            }

        },
        error: function (data) {
            console.log("erreur")
        },
    })

   
})  


var achatFourniTable;

(function(){
    getTable ();
});
$(function () {
     setDataTable()
});
function getTable () {
    achatFourniTable =   $('#achatFourniTable').DataTable({
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
        ajax:{
            url: '/alllivraisonBoutiqhistorique',
            data: {
                'production' : $("#production").val(),
                'fournisseur': $("#fournisseur").val(),
                
                'debut': $("#debut").val(),
                'fin': $("#fin").val(),
            }
        },
        "columns": [
            {data: "date",name : 'date'},
            {data: "nom",name : 'nom'},
            {data: "libelle",name : 'libelle'},
            {data: "quantite",name : 'quantite'},
            {data: "price_unit",name : 'price_unit'},
            {data: "montant",name : 'montant'},
        ]
    });


}

function getSum()
{
    var production = $("#production").val();
    
    var debut = $("#debut").val();
    var fin = $("#fin").val();
         var fournisseur = $("#fournisseur").val();
    $.ajax({
        url : '/allLivraisonBoutiqhistoriquesum',
        type : "get",
        data: {
            'production':production,
            'fournisseur': fournisseur,
            'debut': debut,
            'fin': fin,
        },
        success : function(data) {
            $("#qteTotal").val(data.quantite);
            $("#montantTotal").val(data.montant);
            setNumeralHtml("prix", "0,0", "", 'value');
            $('#achatFourniTable').DataTable().destroy()
           // alert(data.montant);
            getTable ();
        },
        error : function(data){
            alert('erreur')
        }
    });
}
//alert('produit')
 /* function setDataTable(){
    $('#achatFourniTable').DataTable().destroy()
    var production = $("#production").val();
   // alert(production);
    $.ajax({
        url : '/alllivraisonBoutiqhistorique',
        type : "get",
        data: {
            'production':production,
            'debut': 0,
            'fin': 0,
            'fournisseur': 0,
        },
        success : function(data) {
            console.log(data)
            $('#achatFourniTable').DataTable({
                data: data
            });
        },
        error : function(data){
            alert('erreur')
        }
    });
}  */


