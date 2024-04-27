<div class="modal fade " id="ajout_caisse" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header " style="background-color: #0b93d5;border-top-left-radius: inherit;border-top-right-radius: inherit">
                <h4 class="modal-title-user" id="myModalLabel" style="color: white"></h4>
            </div>
            <div class="modal-body">
                <form id="form" action=""  method="POST" class="form-validate form-horizontal mb-lg" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="form-group mt-lg">
                        <label class="col-sm-3 control-label">Boutique</label>
                        <div class="col-sm-9">
                            <input type="text" name="boutique_id" readonly id="boutique_id" class="form-control"  required/>

                        </div>
                    </div>
                 
                  
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Ventes  </label>
                        <div class="col-sm-9">
                            <input type="number" name="totalVente" id="totalVente" readonly class="form-control"  required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Remise  </label>
                        <div class="col-sm-9">
                            <input type="number" name="remise" id="remise" readonly class="form-control"  required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Vente nette  </label>
                        <div class="col-sm-9">
                            <input type="number" name="ventenette" id="ventenette" readonly class="form-control"  required/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Créance  </label>
                        <div class="col-sm-9">
                            <input type="number" name="venteCredit" id="venteCredit" readonly class="form-control"  required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Recouvrement Intérieur  </label>
                        <div class="col-sm-9">
                            <input type="number" name="recouvrementInte" id="recouvrementInte" readonly class="form-control"  required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Avance Client </label>
                        <div class="col-sm-9">
                            <input type="number" name="ventenonlivre" id="ventenonlivre" readonly class="form-control"  required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Total Dépense </label>
                        <div class="col-sm-9">
                            <input type="number" name="totalDepense" id="totalDepense" readonly class="form-control"  required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Recette total  </label>
                        <div class="col-sm-9">
                            <input type="number" name="recetteTotal" id="recetteTotal" readonly class="form-control"  required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Montant Collecté  </label>
                        <div class="col-sm-9">
                            <input type="number" name="montantcollecte" id="montantcollecte"  class="form-control"  required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Solde veille  </label>
                        <div class="col-sm-9">
                            <input type="number" name="solde" id="solde" readonly class="form-control"  required/>
                        </div>
                    </div>
                    <div class="form-group mt-lg">
                        <label class="col-sm-3 control-label">Date * </label>
                        <div class="col-sm-9">
                            <input type="text" name="date"  id="date" readonly class="form-control date" placeholder="" required/>

                        </div>
                    </div>
                   
              
                    <div class="modal-footer">
                        <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn-primary" id="btnadd"><i class="fa fa-check"></i> Valider</button>
                            <button type="button" class="mb-xs mt-xs mr-xs btn btn-default  " data-dismiss="modal"><i class="fa fa-times"></i> Annuler</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
