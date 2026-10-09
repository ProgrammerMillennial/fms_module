<div class="modal fade" id="modal_form">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Inspection Entry</h4>
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
                        <div class="col-sm-4" id="tranno">
                          <label for="exampleInputEmail1"><b>Trans. No</b></label>
                          <input type="text" class="form-control" id="tranno2" name="tranno2" disabled>
                        </div>
                        <div class="col-sm-4" id="printall">
                          <label><p></p></label><br>
                          <a class="btn btn-sm btn-info" title="Cetak"><i class="fas fa-print"></i> Print All</a>
                        </div>
                      </div>
                      
                      <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                              <label for="exampleInputEmail1"><b>PO No</b></label>
                              <input type="text" class="form-control" id="ponu" name="ponu" disabled>
                            </div>
                        </div>
                        <div class="col-sm-4">
                          <div class="form-group">
                              <label class="font-weight-bold">Inspect Date</label>
                              <div class="input-group date" id="tgl1" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" data-target="#tgl1" name="tgl_inspect" id ="tgl_inspect" />
                                <div class="input-group-append" data-target="#tgl1" data-toggle="datetimepicker">
                                  <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                              </div>
                          </div>
                        </div>
                        <div class="col-sm-4">
                          <div class="form-group">
                            <label for="exampleInputEmail1"><b>SJ No</b></label>
                            <input type="text" class="form-control" id="sjno" name="sjno">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="card-body table-responsive p-0">
                          <table class="table table-hover text-nowrap text-gray-800" id = "header_inspect">
                              <thead>
                              <tr>
                                <th>#</th>
                                <th>Material Code</th>
                                <th>Material Name</th>
                                <th>Unit</th>
                                <th>Qtty</th>
                                <th>Qtty Inspect</th>
                                <th>Balance Qtty</th>
                              </tr>
                              </thead>
                              <tbody>
                              </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="col-lg-12">
                  <div class="card shadow mb-4">
                    <div class="card-header">
                      <h5 class="m-0 font-weight-bold text-primary">
                        Detail Inspection
                      </h5>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-sm-4">
                          <div class="form-group">
                            <input type="hidden" id="seqn" name="seqn">
                            <input type="hidden" id="matcode" name="matcode">
                            <input type="hidden" id="price" name="price">
                            <input type="hidden" id="umcd" name="umcd">
                            <input type="hidden" id="matunit" name="matunit">
                            <input type="hidden" id="rls1" name="rls1">
                            <input type="hidden" id="part1" name="part1">
                            <input type="hidden" id="wh" name="wh">
                            <input type="hidden" id="vend" name="vend">
                            <input type="text" class="form-control" id="kemasan" placeholder="Kemasan">
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <div class="form-group">
                            <input type="text" class="form-control" id="jmlkem" placeholder="Jumlah Kemasan">
                          </div>
                        </div>
                        <div class="col-sm-4">
                          <div class="form-group">
                            <input type="number" class="form-control" id="qtty2" placeholder="Qtty">
                          </div>
                        </div>
                        <div class="col-sm-1">
                          <div class="form-group">
                            <a class="btn btn-info" id="add"><i class="fas fa-plus"></i></a>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="card-body table-responsive p-0">
                          <table class="table table-hover text-nowrap text-gray-800" id="detail_inspect">
                            <thead>
                              <tr>
                                <th>Serial</th>
                                <th>Kemasan</th>
                                <th>Jumlah Kemasan</th>
                                <th>Qtty</th>
                                <th>Action</th>
                              </tr>
                            </thead>
                            <tbody id ="detail_body">
                            </tbody>
                          </table>
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
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="btnSave" onclick="saveTrans()">Update changes</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
   $(document).ready(function() {

      $("#add").click(function() {
        var kem = $("#kemasan").val();
        var jmlKem = $("#jmlkem").val();
        var qtty = $("#qtty2").val();

        if(qtty == "" || qtty < 0 ){
          alert("There is no qtty to input!");
          return;
        }else{

        var baris_baru = '<tr><td><input type="text" class="form-control" name="serial[]"></td><td><input type="text" class="form-control" name="kem[]" value='+kem+'></td><td><input type="text" class="form-control" name="jmlkem[]" value='+jmlKem+'></td><td><input type="number" class="form-control" name="qtty_inspect[]" value='+qtty+'></td><td><a class="btn btn-sm btn-info" href="javascript:void()" title="Cetak""><i class="fas fa-print"></i></a>&nbsp<button class="btn btn-danger btn btn-sm" value="delete" onclick="deleteRow(this)" title="Hapus"><i class="fas fa-trash"></i></a></td></tr>';

        $("#detail_inspect").append(baris_baru);
        }
      })
   });

  function deleteRow(ele){
    var table = document.getElementById('detail_inspect');
    var rowCount = table.rows.length;
      if(rowCount <= 1){
          alert("There is no row available to delete!");
          return;
      }
        if(ele){
            ele.parentNode.parentNode.remove();
        }else{
            table.deleteRow(rowCount-1);
        }
  }
</script>
<!-- <div class="modal fade" id="inspectHeader">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Inspection Entry</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="inspectEntryDetail"> 
        <form role="form" id="form2">
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
                        <div class="col-sm-4" id="trannodd">
                          <label for="exampleInputEmail1"><b>Trans. No</b></label>
                          <input type="text" class="form-control" id="trannod" name="trannod" disabled>
                        </div>
                        <div class="col-sm-4" id="printall2">
                          <label><p></p></label><br>
                          <a class="btn btn-sm btn-info" title="Cetak"><i class="fas fa-print"></i> Print All</a>
                        </div>
                      </div>
                      
                      <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                              <label for="exampleInputEmail1"><b>PO No</b></label>
                              <input type="text" class="form-control" id="ponud" name="ponud" disabled>
                            </div>
                        </div>
                        <div class="col-sm-4">
                          <div class="form-group">
                              <label class="font-weight-bold">Inspect Date</label>
                              <div class="input-group date" id="tgl1" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" data-target="#tgl1" name="tgl_inspectd" id ="tgl_inspectd" />
                                <div class="input-group-append" data-target="#tgl1" data-toggle="datetimepicker">
                                  <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                              </div>
                          </div>
                        </div>
                        <div class="col-sm-4">
                          <div class="form-group">
                            <label for="exampleInputEmail1"><b>SJ No</b></label>
                            <input type="text" class="form-control" id="sjnod" name="sjnod">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="card-body table-responsive p-0">
                          <table class="table table-hover text-nowrap text-gray-800" id = "header_inspectd">
                              <thead>
                              <tr>
                                <th>#</th>
                                <th>Material Code</th>
                                <th>Material Name</th>
                                <th>Unit</th>
                                <th>Qtty</th>
                                <th>Qtty Inspect</th>
                                <th>Balance Qtty</th>
                              </tr>
                              </thead>
                              <tbody>
                              </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="col-lg-12">
                  <div class="card shadow mb-4">
                    <div class="card-header">
                      <h5 class="m-0 font-weight-bold text-primary">
                        Detail Inspection
                      </h5>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-sm-4">
                          <div class="form-group">
                            <input type="hidden" id="seqnd" name="seqnd">
                            <input type="hidden" id="matcoded" name="matcoded">
                            <input type="hidden" id="priced" name="priced">
                            <input type="hidden" id="umcdd" name="umcdd">
                            <input type="hidden" id="matunitd" name="matunitd">
                            <input type="hidden" id="rls1d" name="rls1d">
                            <input type="hidden" id="part1d" name="part1d">
                            <input type="hidden" id="whd" name="whd">
                            <input type="hidden" id="vendd" name="vendd">
                            <input type="text" class="form-control" id="kemasand" placeholder="Kemasan">
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <div class="form-group">
                            <input type="text" class="form-control" id="jmlkemd" placeholder="Jumlah Kemasan">
                          </div>
                        </div>
                        <div class="col-sm-4">
                          <div class="form-group">
                            <input type="number" class="form-control" id="qttyd" placeholder="Qtty">
                          </div>
                        </div>
                        <div class="col-sm-1">
                          <div class="form-group">
                            <a class="btn btn-info" id="add_detail"><i class="fas fa-plus"></i></a>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="card-body table-responsive p-0">
                          <table class="table table-hover text-nowrap text-gray-800" id="detail_inspectd">
                            <thead>
                              <tr>
                                <th>Serial</th>
                                <th>Kemasan</th>
                                <th>Jumlah Kemasan</th>
                                <th>Qtty</th>
                                <th>Action</th>
                              </tr>
                            </thead>
                            <tbody id ="detail_body">
                            </tbody>
                          </table>
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
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="btnSave" onclick="saveTrans()">Update changes</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
   $(document).ready(function() {

      $("#add_detail").click(function() {
        var kem = $("#kemasand").val();
        var jmlKem = $("#jmlkemd").val();
        var qtty = $("#qttyd").val();

        if(qtty == "" || qtty < 0 ){
          alert("There is no qtty to input!");
          return;
        }else{

        var baris_baru = '<tr><td><input type="text" class="form-control" name="serial[]"></td><td><input type="text" class="form-control" name="kem[]" value='+kem+'></td><td><input type="text" class="form-control" name="jmlkem[]" value='+jmlKem+'></td><td><input type="number" class="form-control" name="qtty_inspect[]" value='+qtty+'></td><td><a class="btn btn-sm btn-info" href="javascript:void()" title="Cetak""><i class="fas fa-print"></i></a>&nbsp<button class="btn btn-danger btn btn-sm" value="delete" onclick="deleteRow2(this)" title="Hapus"><i class="fas fa-trash"></i></a></td></tr>';

        $("#detail_inspectd").append(baris_baru); }
      })
   });

  function deleteRow2(ele){
    var table = document.getElementById('detail_inspectd');
    var rowCount = table.rows.length;
      if(rowCount <= 1){
          alert("There is no row available to delete!");
          return;
      }
        if(ele){
            ele.parentNode.parentNode.remove();
        }else{
            table.deleteRow(rowCount-1);
        }
  }
</script> -->