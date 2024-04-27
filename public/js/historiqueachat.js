

var achatTable;
$('#choix').on('change',function ( ) {
    if ($('#choix').val()=="mois"){
        $('#jr').hide();
        $('#depense').show();
        $('#depenses').val(null);
        $('#moi').show(); 
                $('#mois').empty()
                    $('#mois').append('<option value=""></option>',
                        '<option value="1">Janvier</option>',
                    '<option value="2">Fevrier</option>',
                    '<option value="3">Mars</option>',
                    '<option value="4">Avril</option>',
                    '<option value="5">Mai</option>',
                    '<option value="6">Juin</option>',
                    '<option value="7">Juillet</option>',
                    '<option value="8">Aout</option>',
                    '<option value="9">Septembre</option>',
                    '<option value="10">Octobre</option>',
                    '<option value="11">Novembre</option>',
                    '<option value="12">Decembre</option>');
        $('#an').show()
        $.ajax({
            url: '/annee',
            type: "get",
            success: function (data) {

                $('#annee').empty()
                $('#annee').append('<option value=""></option>')
                for (var i = 0; i < data.length; i++) {
                    $('#annee').append('<option value="' + data[i].annee + '">' + data[i].annee + '</option>')
                }

            },
            error: function (data) {
                console.log("erreur")
            },
        })
        $('#mois').on('change',function ( ) {
            $.ajax({
                url: '/depensemois-'+ $('#mois').val()+"-"+ $('#annee').val(),
                type: "get",
                success: function (data) {
                    $('#depenses').val(data);
                },
                error: function (data) {
                    console.log("erreur")
                },
            })
            $('#achatTable').DataTable().destroy()
            $(function () {
                achatTable=$('#achatTable').DataTable({
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


                    ajax: '/recupererachatmois-'+ $('#mois').val()+"-"+ $('#annee').val(),
                    "columns": [
                        {data:"commande",name : 'commande'},
                        {data:  "totaux",name : 'totaux'},
                        {data: "date",name : 'date'},
                        // {data:"user",name : 'user'},
                        {data: "action", name : 'action' , orderable: false, searchable: false}


                    ]

                });
            });
        });
    }
    else {
        if ($('#choix').val()==""){
            $('#jr').hide();
            $('#depense').hide();
            $('#moi').hide();
            $('#an').hide();
            $('#achatTable').DataTable().destroy()
        }
        if ($('#choix').val()=="jour"){
            $('#jr').show();
            $('#depense').show();
            $('#depenses').val(null);
            $('#moi').hide();
            $('#an').hide();
            $.ajax({
                url: '/recupererdateachat',
                type: "get",
                success: function (data) {

                    $('#jour').empty()
                    $('#jour').append('<option value=""></option>')
                    var tab=data;
                    for (var i = 0; i < tab["fran"].length; i++) {
                        $('#jour').append('<option value="'+tab["id"][i]+'">'+tab["fran"][i]+'</option>')
                    }

                },
                error: function (data) {
                    console.log("erreur")
                },
            })
            $('#jour').on('change',function ( ) {
                $.ajax({
                    url: '/depensejour-'+ $('#jour').val(),
                    type: "get",
                    success: function (data) {
                        $('#depenses').val(data);
                    },
                    error: function (data) {
                        console.log("erreur")
                    },
                })
                $('#achatTable').DataTable().destroy()
                $(function () {
                    achatTable=$('#achatTable').DataTable({
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


                        ajax: '/recupererachatdate-'+ $('#jour').val(),
                        "columns": [
                            {data:"commande",name : 'commande'},
                            {data:  "totaux",name : 'totaux'},
                            {data: "date",name : 'date'},
                            // {data:"user",name : 'user'},
                            {data: "action", name : 'action' , orderable: false, searchable: false}


                        ]

                    });
                });
            })
        }
        if ($('#choix').val()=="an"){
            $('#jr').hide();
            $('#depense').show();
            $('#depenses').val(null);
            $('#moi').hide();
            $('#an').show();
            $.ajax({
                url: '/annee',
                type: "get",
                success: function (data) {

                    $('#annee').empty()
                    $('#annee').append('<option value=""></option>')
                    for (var i = 0; i < data.length; i++) {
                        $('#annee').append('<option value="' + data[i].annee + '">' + data[i].annee + '</option>')
                    }

                },
                error: function (data) {
                    console.log("erreur")
                },
            })

            $('#annee').on('change',function ( ) {
                $('#depenses').val(null);
                $.ajax({
                    url: '/depenseannee-'+ $('#annee').val(),
                    type: "get",
                    success: function (data) {
                        $('#depenses').val(data);
                    },
                    error: function (data) {
                        console.log("erreur")
                    },
                })
                $('#achatTable').DataTable().destroy()
                $(function () {
                    achatTable=$('#achatTable').DataTable({
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


                        ajax: '/recupererachatannee-'+ $('#annee').val(),
                        "columns": [
                            {data:"commande",name : 'commande'},
                            {data:  "totaux",name : 'totaux'},
                            {data: "date",name : 'date'},
                            // {data:"user",name : 'user'},
                            {data: "action", name : 'action' , orderable: false, searchable: false}

                        ]

                    });
                });
            })
        }
    }
})



function show(id){

    $.ajax({
        url: '/showcommande-'+id,
        type: "get",
        success : function(data) {

            window.location='/detailcommande-'+id

        },
        error : function(data){
            sweetToast('Une erreur c\'est produite. Veuillez recommancer')
        }
    })
}

$("#reset").on('click', function(){
    $("#product").val(0);
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
    placeholder: "Choisir un fournisseur",
    allowClear: true
} );
    $("#product").select2( {
        placeholder: "Choisir un produit",
        allowClear: true
    } ); 


$("#product").on('change', function(){
    getSum();
});



$("#fournisseur").on('change', function(){
    getSum();
});





var achatFourniTable;

(function(){
    getTable ();
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
            url: '/allfournihistorique',
            data: {
                'produit':$("#product").val(),
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
    var produit = $("#product").val();
    
    var debut = $("#debut").val();
    var fin = $("#fin").val();
    console.log(produit);
        /*     console.log(produit);return;
        */  
         var fournisseur = $("#fournisseur").val();
    $.ajax({
        url : '/allfournihistoriquesum',
        type : "get",
        data: {
            'produit':produit,
            'fournisseur': fournisseur,
            'debut': debut,
            'fin': fin,
        },
        success : function(data) {
            $("#qteTotal").val(data.quantite);
            $("#montantTotal").val(data.montant);
            setNumeralHtml("prix", "0,0", "", 'value');
            $('#achatFourniTable').DataTable().destroy()
            getTable ();
        },
        error : function(data){
            alert('erreur')
        }
    });
}

$(function () {
    setDataTable()
});

function setDataTable(){
    $('#achatFourniTable').DataTable().destroy()
    var produit = $("#product").val();
    
    
    $.ajax({
        url : '/allfournihistorique',
        type : "get",
        data: {
            'produit':produit,
            'fournisseur': 0,
            'debut': 0,
            'fin': 0,
            
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
    });console.log(produit);
}

