$('#credit').on('click', function(){

    $('.modal-title-user').text('LISTE DES CREANCIERS');
    $('#infocredit').modal('show');
});




function showOne(id){

    $.ajax({
        url: '/showlivraisonNew-'+id,
        type: "get",
        success : function(data) {

            window.location='/showlivraisonNew-'+id

        },
        error : function(data){
            sweetToast('Une erreur c\'est produite. Veuillez recommancer')
        }
    })
}
