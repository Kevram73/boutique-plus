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

$("#reset").on('click', function(){
    $("#type").val(0);
    $("#debut").val('');
    $("#fin").val('');
    $("#client").val(0);
    getSum();
});

$("#product").select2( {
    placeholder: "Choisir un produit",
    allowClear: true
} );

$("#client").select2( {
    placeholder: "Choisir un client",
    allowClear: true
} );

$("#product").on('change', function(){
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

$("#type").on('change', function(){
    getSum();
});

$("#client").on('change', function(){
    getSum();
});


$("#type").on('change', function(){
    var client = $('#client');
    var clientBox = $('#clientBox');

    if($(this).val() == 1)
    {
       clientBox.hide(200);
    }else{
        clientBox.show(200);
    }

    client.val(0);
});

var reportTable;

(function(){
    getTable ();
});

function getTable () {
    reportTable =   $('#reportTable').DataTable({
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
            url: '/allreportvent',
            data: {
                'produit':$("#product").val(),
                'type': $("#type").val(),
                'debut': $("#debut").val(),
                'fin': $("#fin").val(),
                'client': $("#client").val(),
            }
        },
        "columns": [
            {data: "user",name : 'user'},
            {data: "date",name : 'date'},
            {data: "quantite",name : 'quantite'},
            {data: "montant",name : 'montant'},
            {data: "numero",name : 'numero'},
        ]
    });


}

function getSum()
{
    var produit = $("#product").val();
    var type = $("#type").val();
    var debut = $("#debut").val();
    var fin = $("#fin").val();
    var client = $("#client").val();
    $.ajax({
        url : '/allreportventsum',
        type : "get",
        data: {
            'produit':produit,
            'type': type,
            'debut': debut,
            'fin': fin,
            'client': client,
        },
        success : function(data) {
            $("#qteTotal").val(data.quantite);
            $("#montantTotal").val(data.montant);
            setNumeralHtml("prix", "0,0", "", 'value');
            $('#reportTable').DataTable().destroy()
            getTable ();
        },
        error : function(data){
            alert('erreur')
        }
    });
}

function setDataTable(){
    $('#reportTable').DataTable().destroy()
    var produit = $("#product").val();
    $.ajax({
        url : '/allreportvent',
        type : "get",
        data: {
            'produit':produit,
            'type': 0,
            'debut': 0,
            'fin': 0,
            'client': 0,
        },
        success : function(data) {
            console.log(data)
            $('#reportTable').DataTable({
                data: data
            });
        },
        error : function(data){
            alert('erreur')
        }
    });
}

$(function () {
    // setDataTable()
});
