<div class="modal fade" id="modal_form">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Closing Material Barcode</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="inspectEntryDetail"> 
        <form role="form" id="form">
          <div class="card-body">
            <div class="form-group">
              <div class="row">
                <div class="col-lg-12">
                  <div class="card shadow mb-3">
                    <div class="card-header">
                      <h5 class="m-0 font-weight-bold text-primary">
                          Head Inspection
                      </h5>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-sm-4">  
                            <div class="form-group">
                              <label class="font-weight-bold"><b>Periode</b></label>
                                <div class="input-group date" id="tgl3" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#tgl3" name="tgl_inspect" id="dt3" />
                                      <div class="input-group-append" data-target="#tgl3" data-toggle="datetimepicker">
                                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                      </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                          <div class="form-group">
                            <label for="exampleInputEmail1"><b>Warehouse</b></label>
                              <select class="form-control select2bs4" style="width: 100%;" id="wh2">
                                  <option selected="selected" value="ALL">ALL</option>
                                  <option value="MAT">Material Warehouse</option>
                                  <option value="ENG">Engineering Warehouse</option>
                              </select>
                          </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="font-weight-bold"><b>Factory</b></label>
                                    <select class="form-control select2bs4" style="width: 100%;" id="fact2">
                                        <!-- <option selected="selected"></option> -->
                                        <option selected="selected" value="ALL">ALL</option>
                                        <option value="IR2">Factory IR2</option>
                                        <option value="PM">Factory PM</option>
                                    </select>
                            </div>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer justify-content-between" id="button_ok">
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
   $(document).ready(function(){
   });
</script>