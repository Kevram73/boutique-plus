var globalStore ;
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


$('#fournisseur').on('change',function ( ) {
    $('#total').empty();
    $.ajax({
        url: '/restantachat-' + $('#fournisseur').val(),
        type: "get",
        success: function (data) {
            $('#total').empty();

            $('#total').val(data.total - data.montant);
        },
        error: function (data) {
            console.log("erreur")
        },
    })
})

$('#banque').on('change',function () {
    console.log(" banque selectionner") ;

    let _banKId =$('#banque').val();
    let _btqId = $('#idBoutique').val();

    console.log(_banKId);
    console.log(_btqId);
    let _getAcountUrl = "/get_account?boutique="+_btqId+"&&"+"banque="+_banKId+"";
    console.log(_getAcountUrl);
    $.ajax({
        url : _getAcountUrl,
        type : "get",
        // data : $('#modal-form-user').serialize(),
        //data: new FormData($("#ajout_reglement form")[0]),
        //data: new FormData($("#modal-form-user")[0]),
        contentType: false,
        processData: false,
        success : function(data) {
            console.log(data);
            if(data.length==0){
                let _m = " Vous n'avez pas de compte dans cette banques" ;
                sweetToast('warning',_m) ;
                $('#banque').val(null);
            }

            if(data.length != 0){
                let _accountList = data;
                globalStore = data ;
                $(_accountList).each(function () {
                    var option = $("<option/>");
                    option.html(this.numero);
                    option.val(this.id);
                    $('#compte').append(option) ;
                })
                $('#group_compte').show();

            }
            //$('#ajout_reglement').modal('hide');
            //sweetToast('success',message);

            //reglementTable.ajax.reload();
           // window.location.reload();
        },
        error : function(data){
            console.log(data.error()) ;
        }
    });

});

$('#comptes').on('change',function () {
    let _x = $('#compte').val() ;
    console.log(_x);

    let _getAcountUrl = "/get_solde?account_id="+_x+"";
    console.log(_getAcountUrl);
    $.ajax({
        url : _getAcountUrl,
        type : "get",
         data : $('#modal-form-user').serialize(),
        data: new FormData($("#ajout_reglement form")[0]),
        data: new FormData($("#modal-form-user")[0]),
        contentType: false,
        processData: false,
        success : function(data) {
            console.log(data[0]);
            $('#solde').html(data[0].solde);
            if(data.solde==0){
                let _m = " Votre compte a un solde insufisant" ;
                sweetToast('warning',_m) ;
                $('#compte').val(null);
            }

        },
        error : function(data){
            console.log(data) ;
        }
    });
})

$('#donne').on('value change',function ( ) {
    $('#restant').val($('#total').val()-$('#donne').val());
    if ( $('#restant').val()>0) {
        $('#te').text('Restant');
        $('#reste').val(1);

    }
    if ( $('#restant').val()<0) {
        $('#te').text('Monnaie');
        $('#restant').val(   $('#restant').val()*-1);
        $('#reste').val(0);
    }
})

var reglementTable;
var debiteurTable;


$(function () {

    reglementTable =   $('#reglementTable').DataTable({
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
        ajax: '/allreglement',
        "columns": [

            {data: "nom",name : 'nom'},
            {data :  "total",name : 'total'},
            {data :  "montant_donne",name : 'montant_donne'},
            {data :  "montant_restant",name : 'montant_restant'},
            {data :  "created_at",name : 'created_at'},
            {data: "action", name : 'action' , orderable: false, searchable: false}


        ]

    });

 

});
$('#btnreglement').on('click', function(){

    $('.modal-title-user').text('ENREGISTREMENT  REGLEMENT FOURNISSEUR');
    $('#idreglement').val(null);
    $('#fournisseur').val(null);
    $('#btnadd').text('Valider');
    $('#btnadd').removeClass('btn-warning').addClass('btn-primary');
    //$('#btnadd').addClass('btn-primary');
    $('#total').val(null);
    $('#donne').val(null);
    $('#restant').val(null);
    $('#group_compte').hide();
    $('#ajout_reglement').modal('show');
});

//post des données
$('#ajout_reglement  form').on('submit', function (e) {

    let url,message;
    if (!$('#idreglement').val()){
        url = '/storereglementachat'
        message = 'reglement enregistré' 


    }
    else{
        url = '/updatereglementachat'
        message = 'reglement modifié'

    }
    e.preventDefault();
    if (e.isDefaultPrevented()){
        $.ajax({
            url : url ,
            type : "post",
            // data : $('#modal-form-user').serialize(),
            data: new FormData($("#ajout_reglement form")[0]),
            //data: new FormData($("#modal-form-user")[0]),
            contentType: false,
            processData: false,
            success : function(data) {

                $('#ajout_reglement').modal('hide');
                sweetToast('success',message);

               reglementTable.ajax.reload();
               window.location.reload();
            },
            error : function(data){
              alert('erreur')
            }
        });
    }
});


function editreglement(id){
    $.ajax({
        url : '/showreglementachat-'+id,
        type : "get",
        success : function(data) {

            $('#idreglement').val(data.id);
            $('#btnadd').text('Modifier');
            $('#btnadd').removeClass('btn-primary');
            $('#btnadd').addClass('btn-warning');
            $('#email').val(data.email);
            $('#contact').val(data.contact);
            $('#sexe').val(data.sexe);
            $('#ajout_reglement').modal('show');

        },
        error : function(data){
alert('erreur')
        }
    });
}


function deletereglement(id){
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
                url : '/deletereglementachat-'+id,
                type : "get",
                contentType: false,
                processData: false,
                success : function(data) {
                    Swal.fire('Effacé',
                        'Fichier bien effacé',
                        'success')
                    reglementTable.ajax.reload();
                },
                error : function(data){
                    Swal.fire('Erreur',
                        '...',
                        'warning')
                }
            });

        }
    });
}

$('#compte').on('change', function() {
    var userId = $(this).val();
    $.get('/get-solde-' + userId, function(data) {
    var solder = data.solder;
    console.log(solder);
    console.log('vraiment je   plus')
    var donne = $('#donne').val();
    if (parseFloat(donne) >= parseFloat(solder)) {
    //alert('Le montant est supérieur ou égal au solde de l'utilisateur sélectionné.');
    let _m = " Votre compte a un solde insufisant" ;
                sweetToast('warning',_m) ;
    }
    });
    });

