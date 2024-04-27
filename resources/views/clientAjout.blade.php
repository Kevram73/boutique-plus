<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap.min.css"> <!-- Incluez la feuille de style Bootstrap -->
    <title>Formulaire Client</title>
</head>
<body>
    <form id="clientForm" action="{{ url('ajoutclient') }}" method="POST" class="form-validate form-horizontal mb-lg" enctype="multipart/form-data">
        @csrf
        <style>
            #clientForm {
                background-color: #e0f2f1; /* Couleur de fond bleu ciel */
                padding: 20px;
                border-radius: 10px; /* Bordures arrondies */
                margin: 0 auto; /* Pour centrer horizontalement */
                max-width: 400px; /* Largeur maximale du formulaire */
            }

            .form-group {
                margin-bottom: 20px; /* Espacement entre les champs de formulaire */
            }

            .form-control {
                border-radius: 5px; /* Bordures arrondies pour les champs de formulaire */
            }

            .modal-footer {
                text-align: right; /* Alignement du texte à droite dans le pied de formulaire */
            }

            /* CSS pour les boutons */
            .btn-success {
                background-color: #4CAF50; /* Vert */
                color: #fff; /* Texte en blanc */
            }

            .btn-danger {
                background-color: #FF5733; /* Rouge */
                color: #fff; /* Texte en blanc */
            }
        </style>
        <div class="form-group mt-lg">
            <label class="col-sm-3 control-label">Nom/Raison sociale</label>
            <div class="col-sm-9">
                <input type="text" name="nom" id="nom" class="form-control" placeholder="ATO Kodjo, BTD Construction" required />
                <input type="hidden" name="idclient" id="idclient" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Email</label>
            <div class="col-sm-9">
                <input type="email" name="email" id="email" class="form-control" placeholder="aaaa@aa.com" />
            </div>
        </div>
        <div class="form-group mt-lg">
            <label class="col-sm-3 control-label">Contact</label>
            <div class="col-sm-9">
                <input type="integer" name="contact" id="contact" class="form-control" placeholder="92658797" />
            </div>
        </div>
        <div class="form-group mt-lg">
            <label class="col-sm-3 control-label">Adresse</label>
            <div class="col-sm-9">
                <input type="integer" name="adresse" id="adresse" class="form-control" placeholder="Adidogome, Lome" />
            </div>
        </div>
        <div class="form-group mt-lg">
            <label class="col-sm-3 control-label">Avoir Client</label>
            <div class="col-sm-9">
                <input type="integer" name="avoir" id="avoir" class="form-control" placeholder="0" />
            </div>
        </div>
        <div class="modal-footer">
            <div class="col-md-12 text-right">
                <button type="submit" class="btn btn-success" id="btnadd"><i class="fa fa-check"></i> Valider</button>
                <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger" data-dismiss="modal" onclick="window.location.href = '{{ route('clients') }}'">
                    <i class="fa fa-times"></i> Annuler
                </button>
            </div>
        </div>
    </form>

    <script>
        document.getElementById('clientForm').addEventListener('submit', function(event) {
            var avoirInput = document.getElementById('avoir');
            if (avoirInput.value === '') {
                avoirInput.value = '0';
            }
        });
    </script>

    <script src="jquery.min.js"></script> <!-- Incluez jQuery -->
    <script src="bootstrap.min.js"></script> <!-- Incluez Bootstrap JavaScript -->
</body>
</html>
