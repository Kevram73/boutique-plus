$(document).ready(function () {
    var achatTable;
    
    function show(id){
        $.ajax({
            url: '/showvente-'+id,
            type: "get",
            success : function(data) {
                window.location='/detailvente-'+id
            },
            error : function(data){
                window.location='/detailvente2-'+id
            }
        })
    }

    function initializeDataTable(dataUrl) {
        if (achatTable) {
            achatTable.destroy();
        }
        achatTable = $('#achatTable').DataTable({
            processing: true,
            serverSide: true,
            'paging': true,
            'lengthChange': true,
            'searching': true,
            'ordering': true,
            'info': true,
            'autoWidth': true,
            language: {
                // Language options
            },
            ajax: dataUrl,
            columns: [
                { data: "vente", name: 'vente' },
                { data: "totaux", name: 'totaux' },
                { data: "user", name: 'user' },
                { data: "action", name: 'action', orderable: false, searchable: false }
            ]
        });
    }

    function fetchAndPopulateData(url, depenseSelector) {
        $.ajax({
            url: url,
            type: "get",
            success: function (data) {
                $(depenseSelector).val(data);
            },
            error: function (data) {
                console.log("Erreur");
            }
        });
    }

    $('#choix').on('change', function () {
        var choixVal = $('#choix').val();
        $('#jr, #moi, #an, #depense').hide();
        $('#depenses').val(null);

        if (choixVal == "mois") {
            $('#depense, #moi, #an').show();
            $('#mois').empty().append('<option value=""></option>',
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
            $.ajax({
                url: '/anneevente',
                type: "get",
                success: function (data) {
                    $('#annee').empty().append('<option value=""></option>');
                    $.each(data, function (index, item) {
                        $('#annee').append('<option value="' + item.annee + '">' + item.annee + '</option>');
                    });
                },
                error: function (data) {
                    console.log("Erreur");
                }
            });
            $('#mois').on('change', function () {
                fetchAndPopulateData('/totalmois-' + $('#mois').val() + "-" + $('#annee').val(), '#depenses');
                initializeDataTable('/recupererventemois-' + $('#mois').val() + "-" + $('#annee').val());
            });
        } else if (choixVal == "jour") {
            $('#depense, #jr').show();
            $.ajax({
                url: '/recupererdatvente',
                type: "get",
                success: function (data) {
                    $('#jour').empty().append('<option value=""></option>');
                    $.each(data.fran, function (index, item) {
                        $('#jour').append('<option value="' + data.id[index] + '">' + item + '</option>');
                    });
                },
                error: function (data) {
                    console.log("Erreur");
                }
            });
            $('#jour').on('change', function () {
                fetchAndPopulateData('/totaljour-' + $('#jour').val(), '#depenses');
                initializeDataTable('/recupererventedate-' + $('#jour').val());
            });
        } else if (choixVal == "an") {
            $('#depense, #an').show();
            $.ajax({
                url: '/anneevente',
                type: "get",
                success: function (data) {
                    $('#annee').empty().append('<option value=""></option>');
                    $.each(data, function (index, item) {
                        $('#annee').append('<option value="' + item.annee + '">' + item.annee + '</option>');
                    });
                },
                error: function (data) {
                    console.log("Erreur");
                }
            });
            $('#annee').on('change', function () {
                fetchAndPopulateData('/totalannee-' + $('#annee').val(), '#depenses');
                initializeDataTable('/recupererventeannee-' + $('#annee').val());
            });
        }
    });
});
