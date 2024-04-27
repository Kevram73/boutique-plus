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
            url: '/anneevente',
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
            $('#c').text('');
            $('#depensetotal').text('');
            $('#ca').text('');
            $('#cpv').text('');
            $('#i').text('');
            $('#mb').text('');
            $('#rai').text('');
            $('#rn').text('');
            $.ajax({
                url: '/tableaumois-'+ $('#mois').val()+"-"+ $('#annee').val(),
                type: "get",
                success: function (data) {
                    $('#depensetotal').text(data[5]+'   cfa');
                        $('#c').text(data[0]+'   cfa');
                        $('#ca').text(data[1]+'   cfa');
                        $('#cpv').text(data[2]+'   cfa');
                        $('#i').text(data[4]+'   cfa');
                        $('#mb').text(data[1]-data[2]+'   cfa');
                        $('#rai').text(data[1]-data[2]-data[0]-data[5]+'   cfa');
                        $('#rn').text(data[1]-data[2]-data[0]-data[4]-data[5]+'   CFA');
                },
                error: function (data) {
                    console.log("erreur")
                },
            })

        });
    }
    else {
        if ($('#choix').val()==""){
            $('#jr').hide();
            $('#moi').hide();
            $('#depense').hide();
            $('#an').hide();
            $('#achatTable').DataTable().destroy()
        }
        if ($('#choix').val()=="jour"){
            $('#jr').val(null);
            $('#jr').show();
            $('#depense').show();
            $('#depenses').val(null);
            $('#moi').hide();
            $('#an').hide();
            $.ajax({
                url: '/resultatjr',
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
                $('#c').text('');
                $('#ca').text('');
                $('#cpv').text('');
                $('#i').text('');
                $('#mb').text('');
                $('#rai').text('');
                $('#rn').text('');
                $.ajax({
                    url: '/tableaujr-'+ $('#jour').val(),
                    type: "get",
                    success: function (data) {
                        $('#depensetotal').text(data[5]+'   cfa');
                        $('#c').text(data[0]+'   cfa');
                        $('#ca').text(data[1]+'   cfa');
                        $('#cpv').text(data[2]+'   cfa');
                        $('#i').text(data[3]+'   cfa');
                        $('#mb').text(data[1]-data[2]+'   cfa');
                        $('#rai').text(data[1]-data[2]-data[0]-data[5]+'   cfa');
                        $('#rn').text(data[1]-data[2]-data[0]-data[3]-data[5]+'   CFA');
                    },
                    error: function (data) {
                        console.log("erreur")
                    },
                })


            })
        }
        if ($('#choix').val()=="an"){
            $('#jr').hide();
            $('#depense').show();
            $('#depenses').val(null);
            $('#moi').hide();
            $('#an').show();
            $.ajax({
                url: '/anneevente',
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
                $('#c').text('');
                $('#ca').text('');
                $('#cpv').text('');
                $('#i').text('');
                $('#mb').text('');
                $('#rai').text('');
                $('#rn').text('');/*layout*/
                $.ajax({
                    url: '/tableau-'+ $('#annee').val(),
                    type: "get",
                    success: function (data) {
                        $('#depensetotal').text(data[5]+'   cfa');
                        $('#c').text(data[0]+'   cfa');
                        $('#ca').text(data[1]+'   cfa');
                        $('#cpv').text(data[2]+'   cfa');
                        $('#i').text(data[3]+'   cfa');
                        $('#mb').text(data[1]-data[2]+'   cfa');
                        $('#rai').text(data[1]-data[2]-data[0]-data[5]+'   cfa');
                        $('#rn').text(data[1]-data[2]-data[0]-data[3]-data[5]+'   CFA');
                    },
                    error: function (data) {
                        console.log("erreur")
                    },
                })

            })
        }
    }
})

