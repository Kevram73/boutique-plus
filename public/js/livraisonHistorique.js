// Cache jQuery selectors for performance improvement
var $production = $("#production"),
    $debut = $("#debut"),
    $fin = $("#fin"),
    $fournisseur = $("#fournisseur"),
    $modeles = $("#modeles"),
    $categorie = $("#categorie"),
    $voir = $("#voir"),
    $reset = $("#reset");

// Initialize select2 for multiple elements at once
$(".select2-custom").select2({
    placeholder: function() {
        return $(this).data('placeholder') || "Choisir une option";
    },
    allowClear: true
});

// Consolidate event handlers for efficiency
$reset.on('click', function() {
    $production.val(0).trigger('change'); // Ensure select2 updates visually
    $debut.val('');
    $fin.val('');
    $fournisseur.val(0).trigger('change');
    getSum();
});

$debut.add($fin).on('change', function() {
    if (this.id === 'debut') {
        $fin.attr('min', $debut.val());
    } else {
        $debut.attr('max', $fin.val());
    }
    getSum();
});

$voir.add($production).on('click change', getSum);

// Handle dynamic select options based on other selections
function handleDynamicSelections(url, $emptyTarget) {
    $.ajax({
        url: url,
        type: "GET",
        success: function(data) {
            $emptyTarget.empty().append('<option value=""></option>');
            data.forEach(function(item) {
                $emptyTarget.append(`<option value="${item.id}">${item.nom || item.libelle}</option>`);
            });
        },
        error: function() {
            console.log("Erreur lors de la récupération des données.");
        }
    });
}

$categorie.on('change', function() {
    handleDynamicSelections('/recupererproduit-' + $categorie.val(), $modeles);
});

$modeles.on('change', function() {
    handleDynamicSelections('/recuperermodele-' + $modeles.val(), $production);
});

$fournisseur.on('change', function() {
    handleDynamicSelections('/recuperermodeleboutiq-' + $fournisseur.val(), $production);
});

// Reuse DataTable initialization
function initializeTable() {
    if ($.fn.dataTable.isDataTable('#achatFourniTable')) {
        // If the table instance exists, clear its data
        $('#achatFourniTable').DataTable().clear().draw();
    }
}

function getSum() {
    $.ajax({
        url: '/allLivraisonBoutiqhistoriquesum',
        type: "GET",
        data: {
            'production': $production.val(),
            'fournisseur': $fournisseur.val(),
            'debut': $debut.val(),
            'fin': $fin.val(),
        },
        success: function(data) {
            $("#qteTotal").val(data.quantite);
            $("#montantTotal").val(data.montant);
            setNumeralHtml("prix", "0,0", "", 'value');
            // No need to destroy and reinitialize, just reload the data
            $('#achatFourniTable').DataTable().clear().draw();
        },
        error: function() {
            alert('Erreur lors de la récupération des données.');
        }
    });
}

function setNumeralHtml(element, format, suffix = "", type = "html") {
    $("." + element).each(function() {
        var number = numeral(type === "html" ? $(this).text() : $(this).val());
        var formattedNumber = number.format(format) + " " + suffix;
        if (type === "html") {
            $(this).text(formattedNumber);
        } else { // "value"
            $(this).val(formattedNumber);
        }
    });
}

// Initialize DataTable on document ready
