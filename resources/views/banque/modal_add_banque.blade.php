
<div class="modal fade" id="idAddBanqueModal" tabindex="-1"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">AJOUTER BANQUEs</h5>
                </div>
                <div class="modal-body" id="ModalAddBanque">
                    <form class="form" id="banqueModalFormId" method="" action="">
                        {{csrf_field()}}
                        <div class="form-group">
                            <label for="bName">Nom de la banques </label>
                            <input type="text" class="form-control" id="bName" name="bName" required>
                            <input type="hidden" name="idnomb" id="idnomb"/>

                        </div>
                        <div class="form-group">
                            <label for="bDesc">Description banque </label>
                            <input type="text" class="form-control" id="bDesc" name="bDesc" required>
                        </div>
                        <div class="form-group">
                            <label for="contact">Contact  </label>
                            <input type="text" class="form-control" id="contact" name="contact" required>
                        </div>
                    </form>

                </div>
                <div class="modal-footer align-content-center">
                    <button type="reset" class="btn btn-danger"
                            onclick=" $('#idAddBanqueModal').modal('hide')"
                            id="btnAnnulerBanque">ANNULLER</button>
                    {{-- <button type="submit" class="btn btn-success" id="btnSubmitAddBanque">VALIDER</button> --}}
                    <button type="submit" class="btn btn-primary" id="btnadd"><i class="fa fa-check"></i> Valider</button>

                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="detailcategorie" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#0b97c4;border-top-left-radius: inherit;border-top-right-radius: inherit">
                    <b>	<h4 class="modal-title" id="modal-user-title" align="center" style="color: white" ></h4></b>

                </div>
                <div class="modal-body">
                    <ul class="list-group">
                        <li class="list-group-item">Nom :<b> <span class="text-danger" id="sNom"></span> </b></li>
                        <li class="list-group-item">Description :<b> <span class="text-danger" id="sDescription"></span> </b></li>
                        <li class="list-group-item">
                            crée le :<b> <span class="text-danger" id="sCreate"></span></b> </li>

                        <li class="list-group-item">
                            mise a jour le :<b> <span class="text-danger" id="sUpdate"></span></b> </li>
                    </ul>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Fermer</button>
                    </div>
                </div>

            </div>

        </div>

    </div>