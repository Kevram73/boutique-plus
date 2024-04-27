
<div class="modal fade" id="idAddCompteModal" tabindex="-1"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">AJOUTER compte</h5>
                </div>
                <div class="modal-body" id="ModalAddBanque">
                    <form class="form" id="COMPTEModalFormId" method="" action="">
                        {{csrf_field()}}
                        <div class="form-group">
                            <label for="banque_id">Nom de la Banque </label>
                            <select class="form-control" id="banque_id" name="banque_id">
                                @foreach ($agences as $agence)
                                <option value="{{ $agence->id }}"> {{ $agence->nom }} </option>
                               

                                @endforeach
                               
                            </select>              
                                  </div>
                                 <input type="hidden" name="idnom" id="idnom"/>
                        <div class="form-group">
                            <label for="type">Type de compte </label>
                            <select class="form-control" id="type" name="type">
                                <option></option>
                                <option value="Compte Epargne">Compte Epargne</option>
                                <option value="Compte Courant">Compte Courant</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="contact">Numéro Compte  </label>
                            <input type="text" class="form-control" id="numero" name="numero" required>
                        </div>
                    </form>

                </div>
                <div class="modal-footer align-content-center">
                    <button type="reset" class="btn btn-danger"
                            onclick=" $('#idAddCompteModal').modal('hide')"
                            id="btnAnnulerBanque">ANNULLER</button>
                    <button type="submit" class="btn btn-success" id="btnSubmitAddCompte">VALIDER</button>
                </div>
            </div>
        </div>
    </div>
