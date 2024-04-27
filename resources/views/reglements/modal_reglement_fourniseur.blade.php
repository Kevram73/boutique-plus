<div class="modal fade " id="ajout_reglement" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header " style="background-color: #0b93d5;border-top-left-radius: inherit;border-top-right-radius: inherit">
                <h4 class="modal-title-user" id="myModalLabel" style="color: white"></h4>
            </div>
            <div class="modal-body">
                <form id="form" action="" method="POST" class="	form-validate form-horizontal mb-lg" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="form-group mt-lg">
                        <label class="col-sm-3 control-label">Fournisseur</label>
                        <div class="col-sm-9">
                            <select  name="fournisseur" id="fournisseur"   class="form-control populate">
                                <optgroup label="Choisir un fournisseur">
                                    <option value=""></option>
                                    @foreach($fournisseur as $cli)
                                        <option value="{{$cli->id}}">{{$cli->nom}}</option>
                                    @endforeach
                                </optgroup>
                             </select>
                        </div>
                    </div>
                    <div class="form-group mt-lg">
                        <label class="col-sm-3 control-label">Total à payer</label>
                        <div class="col-sm-9">
                            <input type="number" name="total" id="total" class="form-control"  readonly="readonly" required/>
                            <input type="hidden" name="idreglement" id="idreglement"/>
                            <input type="hidden" name="reste" id="reste"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Montant  payer</label>
                        <div class="col-sm-9">
                            <input type="number" name="donne" id="donne" class="form-control"  required/>
                        </div>
                    </div>

                    <div class="form-group mt-lg">
                        <label class="col-sm-3 control-label" id="te">Restant</label>
                        <div class="col-sm-9">
                            <input type="number" name="restant" id="restant" class="form-control"  readonly="readonly" required/>
                        </div>
                    </div>
                    <div class="form-group mt-lg">
                        <label class="col-sm-3 control-label">Compte</label>
                        <div class="col-sm-9">
                            <select  name="compte" id="compte"
                                     class="form-control populate">
                                <optgroup label="Choisir un banques">
                                    <option value=""></option>
                                    @foreach($banques as $banque_item)
                                        <option id="addBanqueSelect" value="{{$banque_item->id}}">
                                            {{$banque_item->banques}}  -  {{$banque_item->numero}}
                                        </option>
                                    @endforeach
                                </optgroup>
                            </select>
                            <input type="hidden" class="form-control" name="solde" id="solde">

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
