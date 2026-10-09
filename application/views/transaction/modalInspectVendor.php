<div class="modal fade" id="modal_form">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Inspection Entry</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form role="form" id="form">

                <div class="card shadow mb-4">
                  <div class="card-header py-3">
                      <h6 class="m-0 font-weight-bold text-primary">Detail Inspection</h6>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-sm-12">

                        <div class="row">
                          <div class="col-sm-3">
                            <div class="form-group" id="wh2">
                              <label for="exampleInputEmail1"><b>Warehouse</b></label>
                              <input type="text" class="form-control" id="warehouse" name="warehouse" disabled>
                            </div>
                          </div>
<!--                           <div class="col-sm-3">
                            <div class="form-group" id="pono">
                              <label for="exampleInputEmail1"><b>PO No</b></label>
                              <input type="text" class="form-control" id="pono" name="pono" disabled>
                            </div>
                          </div> -->
                        </div>

                        <div class="row">
                          <div class="col-sm-3">
                            <div class="form-group" id="tranno">
                             <label for="exampleInputEmail1"><b>Trans. No</b></label>
                              <input type="text" class="form-control" id="tranno2" name="tranno2" disabled>
                            </div>
                          </div>
                          <div class="col-sm-3">
                            <div class="form-group">
                              <label class="font-weight-bold">Inspect Date</label>
                              <div class="input-group date" id="tgl3" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" data-target="#tgl3" name="tgl_inspect" id ="tgl_inspect" />
                                <div class="input-group-append" data-target="#tgl3" data-toggle="datetimepicker">
                                  <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-3">
                            <div class="form-group">
                              <label for="exampleInputEmail1"><b>Total Inspect</b></label>
                              <input type="text" class="form-control" id="total_inspect" name="total_inspect" disabled>
                            </div>
                         </div> 
                        </div>
                      </div>
                    </div>
                  </div>
                </div>


                <div class="card shadow mb-4">
                  <div class="card-header py-3">
                      <h6 class="m-0 font-weight-bold text-primary">Scan QR Code</h6>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <!-- <input type="text" class="form-control" id="barcode" name="barcode" placeholder="Barcode" autocomplete="off" onsubmit="return false"> -->
                          <input type="text" class="form-control" id="barcode" name="barcode" placeholder="Barcode">
                        </div>
                        <input type="hidden" id="user" name="user" value="<?php echo $_SESSION['user_id']; ?>">
                        
                        <div class="card-body table-responsive tableFixHead p-0">
                          <table class="table table-bordered table-hover text-nowrap text-gray-800" id="detail_material">
                            <thead>
                              <tr>
                                <th>Serial</th>
                                <th>Material Code</th>
                                <th>Material Name</th>
                                <th>Qtty</th>
                                <th>Action</th>
                              </tr>
                            </thead>
                            <tbody></tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="card shadow mb-4">
                  <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">List QR Material</h6>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="table-responsive">
                          <table class="table table-bordered table-hover text-gray-800" id="tableDetail" width="100%" cellspacing="0">
                            <thead>
                              <tr>
                                <th width="20%">Serial</th>
                                <th>Material Name</th>
                                <th>Unit</th>
                                <th>Qtty</th>
                                <th>Material Code</th>
                              </tr>
                            </thead>
                            <tbody></tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- /.card-body -->
              </form>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>