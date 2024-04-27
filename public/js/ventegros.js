

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

$("#client").select2( {
    placeholder: "Choisir un client",
    //allowClear: true
} );

$("#produit").select2( {
    placeholder: "Choisir un produit",
    //allowClear: true
} );

$("#categorie").select2( {
    placeholder: "Choisir la catégorie",
    //allowClear: true
} );

$("#modele").select2( {
    placeholder: "Choisir le modele",
    allowClear: true
} );

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

$('#client').on('change',function ( ) {
    $.ajax({
        url: '/recuperercredit-' + $('#client').val(),
        type: "get",
        success: function (data) {
            if (data>0){
                $('#titre').text( 'CE CLIENT NOUS DOIT:');
                $('#sCredit').text(data);
                $('#verification').modal('show');
            }
        },
        error: function (data) {
            console.log("erreur")
        },
    })
})
$('#checkavoir').on('change',function () {
    var setTva = $('#checkavoir');
    var avoir = $("#avoir");
    // let  message= "Set TVA "+ setTva.attr("checked");
    if (setTva.is(":checked"))
    {
        avoir.css('display', 'inline-block');
    }else{
        avoir.css('display', 'none');
    }
    // sweetToast('success',message);
})
$('#client').on('change',function ( ) {
    $.ajax({
        url: '/recupereravoir-' + $('#client').val(),
        type: "get",
        success: function (data) {
                $('#avoir').val(data);
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
        url: '/recupefournisseurgros-' + $('#modele').val(),
        type: "get",
        success: function (data) {
            if (data == ""){
                $('#prix').val(null);
            }
            else {
                $('#prix').val(null);
                for (var i = 0; i < data.length; i++)
                {
                    $('#prix').val(data[i].prix_vente);
                    $('#mod').val(data[i].modele);
                    $('#stock').val(data[i].stock);
                    $('#qteStock').val(data[i].stock);
                }
            }


        },
        error: function (data) {
            console.log("erreur")
        },
    })
})



$(function( ) {

    'use strict';

    var datatableInit = function() {

        $table2 = $('#venteTable').DataTable({
            'paging': true,
            'lengthChange': true,
            'searching': true,
            'ordering': true,
            'info': true,
            'autoWidth': false,
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
                { data: 'modele' },
                { data: 'quantite' },
                { data: 'prix' },
                { data: 'reduction' },
                { data: 'total' },
            ]


        });

    };

    $(function() {
        datatableInit();
    });
    $("#tav").css('display', 'none');
})
$('#ajout').on('click',function () {
    let message;
    
        if( $('#modele').val() ==null   ||   $('#prix').val() <= 0
            || $('#prix').val()  ==''   || $('#quantite').val()==''  || $('#quantite').val()<=0 ){
            message='Veuillez bien remplir tous les champs svp...'
            sweetToast('warning',message);
        }
        else{

        var dEmporte , position
        let trouveEmporte = false;
        for(let i = 0; i <  $table2.data().length; i++){
            let  data = $table2.data()[i]
            if (data.id == $('#mod').val()+","+$('#client').val()) {
                trouveEmporte = true;
                position = i;
            }
        }

        if ( trouveEmporte === false) {
            var d=document.getElementById('produit')
            var b=document.getElementById('modele')
            var produit=d.options[d.selectedIndex].text;
            var modele=b.options[b.selectedIndex].text;
            $table2.row.add({
                "id":$('#mod').val()+","+$('#client').val(),
                "produit": produit,
                "modele":modele,
                "prix": $('#prix').val(),
                "quantite": $('#quantite').val(),
                "reduction": $('#reduction').val(),
                "total": ($('#prix').val() * $('#quantite').val()) - $('#reduction').val(),

            }).draw()


            $('#quantite').val(null);
            $('#reduction').val(null);
            $('#prixQte').val(null);
            $("#montant_reduction").html( calAllReduction( $table2.data() ) );
            $("#montant_total").html( calTotal( $table2.data() ) );
            setNumeralHtml("prix", "0,0");
        }else{
            $table2.data()[position].quantite = parseInt( $table2.data()[position].quantite) + parseInt( $('#quantite').val()) ;

            $table2.data()[position].total = $table2.data()[position].quantite * $table2.data()[position] .prix;

            $table2 .row().data($table2.data()[position]).draw();
            message='Produit rajouter au panier...'
            sweetToast('success',message);
            trouveEmporte = true;
        }

    } 
})
function calTotal(table)
{
    let t = 0;
    for(let i = 0; i < table.length; i++)
    {
       t += table[i].total;
    }
    return t;
}

function calAllReduction(table)
{
    let r = 0;
    for(let i = 0; i < table.length; i++)
    {
        const reduct = table[i].reduction * 1;
        r += reduct;
    }
    return r;
}

$('#annuler').on('click',function (e) {
  let  message='Annuler'
    sweetToast('warning',message);



    $('#quantite').val(null);
    $('#reduction').val(null);
    $('#prixQte').val(null);
})

$('#valider').on('click',function (e) {
    Swal.fire({
        position: 'center',
        title: 'Voulez-vous enregistrer la vente de gros?',
        text:"",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor:'#3085d6',
        cancelButtonColor:'#d33',
        confirmButtonText:'Oui '
    }).then ((result)=>{
        if (result.value){
            let url;

            url = '/storevente4'


            e.preventDefault()

            if ($table2.data().length <= 0 ){
                let message;
                message='Impossible ... Tableau vide!!!'
                sweetToast('warning',message);
            }else{
                let content =''
                for(let i = 0; i <  $table2.data().length; i++){
                    if (i!=$table2.data().length-1){
                        content +=   $table2.data()[i].id+","+ $table2.data()[i].prix+","+ $table2.data()[i].quantite+"," + ($table2.data()[i].reduction * 1)+","

                    }else{
                        content +=  $table2.data()[i].id+","+ $table2.data()[i].prix+","+ $table2.data()[i].quantite +","+ ($table2.data()[i].reduction * 1)
                    }
                }
                $('#venTable').val(content)
                 $('#avoirvenTable').val(content)
                e.preventDefault();
                if (e.isDefaultPrevented()){
                    $.ajax({
                        url :url,
                        type : "post",
                        // data : $('#modal-form-user').serialize(),
                        data: new FormData($("#comform form")[0]),
                        //data: new FormData($("#modal-form-user")[0]),
                        contentType: false,
                        processData: false,
                        success : function(data) {
                            window.location='/reglementgros-'+data.id;
                        },
                        error : function(data){
                            let message='Erreur ';
                            sweetToast('warning',data.responseJSON.msg);
                        }
                    });
                }
            }

            Swal.fire('Effectué',
                'Vente bien enregistrée')
        }
    });
})
var index;


$('#btnclient').on('click', function(){
    $('.modal-title-user').text('ENREGISTREMENT DU CLIENT (Particulier/Entreprise');
    $('#btnadd').text('Valider');
    $('#btnadd').removeClass('btn-warning');
    $('#btnadd').addClass('btn-primary');
    $('#idclient').val(null);
    $('#nom').val(null);
    $('#email').val(null);
    $('#contact').val(null);
    $('#adresse').val(null);
    $('#ajout_client').modal('show');
});
//post des données
$('#ajout_client  form').on('submit', function (e) {

    let url;
    if (!$('#idclient').val()){
        url = '/ajoutclient'
    }

    e.preventDefault();
    if (e.isDefaultPrevented()){
        $.ajax({
            url : url ,
            type : "post",
            // data : $('#modal-form-user').serialize(),
            data: new FormData($("#ajout_client form")[0]),
            //data: new FormData($("#modal-form-user")[0]),
            contentType: false,
            processData: false,
            success : function(data) {

                $('#ajout_client').modal('hide');
                window.location='/ventegros'

            },
            error : function(data){
                alert('erreur')
            }
        });
    }
});

$('#info').on('click', function(){

    $('.modal-title-user').text('LISTE DES PRODUITS A APPROVISIONNER');
    $('#infoproduit').modal('show');
});
$('#credit').on('click', function(){

    $('.modal-title-user').text('LISTE DES CREANCIERS');
    $('#infocredit').modal('show');
});
$('#venteTable tbody').on( 'click', 'tr', function () {
    if ( $(this).hasClass('selected') ) {
        $(this).removeClass('selected');
    }
    else {
        $table2.$('tr.selected').removeClass('selected');
        $(this).addClass('selected');
        index=$table2.row( '.selected').index()
    }
} );

$('#edit').on('click',function () {
    Swal.fire({
        position: 'center',
        title: 'Quantite',
        input:'number',
        icon: 'input',
        showCancelButton: true,
        confirmButtonColor:'#3085d6',
        cancelButtonColor:'#d33',
        inputPlaceholder: "100",
        confirmButtonText:'Modifier ',
    }).then (function(result) {
        if (result.value) {
            const quant=result.value
            let  message='Modifier'
            $table2.data()[index].quantite = quant;
            $table2 .row().data($table2.data()[index]).draw();
            sweetToast('success',message);
        }
    })
})
$('#sup').on('click',function () {
    let  message='Supprimer'
    $table2.row('.selected').remove().draw( false );
    $("#montant_total").html( calTotal( $table2.data() ) );
    $("#montant_reduction").html( calAllReduction( $table2.data() ) );
    setNumeralHtml("prix", "0,0");
    sweetToast('success',message);

})

$('#setTav').on('change',function () {
    var setTva = $('#setTav');
    var tva = $("#tav");
    // let  message= "Set TVA "+ setTva.attr("checked");
    if (setTva.is(":checked"))
    {
        tva.css('display', 'inline-block');
    }else{
        tva.css('display', 'none');
    }
    // sweetToast('success',message);
})

$('#quantite').on('change',function ( ) {
    var total = $('#prix').val() * $('#quantite').val();
    $('#prixQte').val(total);
});