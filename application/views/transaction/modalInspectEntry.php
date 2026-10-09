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
                      <div class="col-sm-2" id="clearall">
                        <label><p></p></label><br>
                        <a class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i> Delete All</a>
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
                                <input type="text" class="form-control datetimepicker-input" data-target="#tgl1" name="tgl_inspect" id ="tgl_inspect" disabled/>
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
                                <th>Balance Qtty</th>
                                <th>Qtty</th>
                                <th>Qtty Inspect</th>
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
                    <div class="card-header d-flex justify-content-between align-items-center">
                      <h5 class="m-0 font-weight-bold text-primary">
                        Detail Inspection
                      </h5>
                      <span class="badge badge-info px-3 py-2" id="detailSummary">0 row</span>
                    </div>
                    <div class="card-body">
                      <div class="alert alert-light border rounded mb-3 py-2 px-3 mb-3">
                        <small class="text-muted">
                          <i class="fas fa-info-circle mr-1"></i>
                          Masukkan data per row. LOT dan Expired Date akan diterapkan pada setiap baris detail yang dibuat.
                        </small>
                      </div>

                      <div class="row align-items-end mb-3">
                        <div class="col-md-3">
                          <div class="form-group mb-0">
                            <label class="font-weight-bold mb-1">Kemasan</label>
                            <input type="hidden" id="updt" name="updt">
                            <input type="hidden" id="seqn" name="seqn">
                            <input type="hidden" id="matcode" name="matcode">
                            <input type="hidden" id="matname" name="matname">
                            <input type="hidden" id="price" name="price">
                            <input type="hidden" id="umcd" name="umcd">
                            <input type="hidden" id="matunit" name="matunit">
                            <input type="hidden" id="rls1" name="rls1">
                            <input type="hidden" id="part1" name="part1">
                            <input type="hidden" id="wh" name="wh">
                            <input type="hidden" id="vend" name="vend">
                            <input type="hidden" id="mino2" name="mino2">
                            <input type="hidden" id="bal_qty" name="bal_qty">
                            <input type="hidden" id="user" name="user" value="<?php echo $_SESSION['user_id']; ?>">
                            <input type="text" class="form-control form-control-sm" id="kemasan" placeholder="Contoh: Box, Karton">
                          </div>
                        </div>
                        <div class="col-md-2">
                          <div class="form-group mb-0">
                            <label class="font-weight-bold mb-1">Jumlah Kemasan</label>
                            <input type="number" class="form-control form-control-sm" id="jmlkem" placeholder="Jumlah">
                          </div>
                        </div>
                        <div class="col-md-2">
                          <div class="form-group mb-0">
                            <label class="font-weight-bold mb-1">Qtty</label>
                            <input type="number" class="form-control form-control-sm" id="qtty2" placeholder="Qty">
                          </div>
                        </div>
                        <div class="col-md-2">
                          <div class="form-group mb-0">
                            <label class="font-weight-bold mb-1">LOT per Kemasan</label>
                            <input type="text" class="form-control form-control-sm" id="row_lotnumber" name="row_lotnumber" placeholder="LOT row ini">
                          </div>
                        </div>
                        <div class="col-md-2">
                          <div class="form-group mb-0">
                            <label class="font-weight-bold mb-1">Expired per Kemasan</label>
                            <input type="date" class="form-control form-control-sm" id="row_tgl_expired" name="row_tgl_expired">
                          </div>
                        </div>
                        <div class="col-md-1">
                          <div class="form-group mb-0">
                            <label class="font-weight-bold mb-1">&nbsp;</label>
                            <button type="button" class="btn btn-primary btn-sm btn-block rounded-pill" id="go_add">
                              <i class="fas fa-plus mr-1"></i>Go
                            </button>
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="card-body table-responsive tableFixHead p-0">
                          <table class="table table-bordered table-striped table-hover text-nowrap text-gray-800 mb-0" id="detail_inspect">
                            <thead class="thead-light">
                              <tr>
                                <th width="8%">Serial</th>
                                <th width="17%">Kemasan</th>
                                <th width="15%">Jumlah Kemasan</th>
                                <th width="12%">Qtty</th>
                                <th width="20%">Lot Number</th>
                                <th width="18%">Expired Date</th>
                                <th width="10%">Action</th>
                              </tr>
                            </thead>
                            <tbody id="detail_body">
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
      <div class="modal-footer justify-content-between" id="button_ok">
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
   $(document).ready(function() {

      $("#go_add").click(function() {
        var updt = $("#updt").val();
        if(updt == 0){
          $("#detail_inspect").find("tr:gt(0)").remove();  
        }
        var jmlKem = $("#jmlkem").val();
        var kem = $("#kemasan").val();
        var qtty = $("#qtty2").val();
        var lotnumber = $("#row_lotnumber").val();
        var tgl_expired = $("#row_tgl_expired").val();

        if (kem === '' || jmlKem === '' || qtty === '' || lotnumber === '' || tgl_expired === '') {
          Toast.fire({
            icon: 'error',
            title: 'Kemasan, Qty, LOT Number, dan Expired Date per row harus diisi.'
          });
          return;
        }

        var bal_qty2 = "";
        var bal_qty2 = $("#bal_qty").val();
        let bal_qty = removeFormatting(bal_qty2);

        var ttl = parseFloat(0);
        var gap = parseFloat(0);
        var ttl_sisa = parseFloat(0);
        var loop = 0;
        bal_qty = parseFloat(bal_qty);
        // var ttl_bagi = qtty / jmlKem;
        // let x = Math.floor(ttl_bagi);
        // var ttl_sisa = qtty - (x * jmlKem);
        // bal_qty = bal_qty.replace(",", "");
        // bal_qty = parseFloat(bal_qty);

        // if(qtty > bal_qty){
        //   Toast.fire({
        //     icon: 'error',
        //     title: 'Qtty melebihi Balance'
        //   })
        //   return;
        // }
        console.log(bal_qty);
        for(key=0; key < jmlKem; key++)  {
          loop = loop + 1;
          gap = gap + parseFloat(qtty);
          if(gap >= bal_qty && loop == jmlKem){
            ttl_sisa = parseFloat(bal_qty) - parseFloat(ttl);
            var baris_baru = '<tr><td><input type="text" class="form-control" name="serial[]" readonly tabindex="-1" placeholder="Auto"></td><td><input type="text" class="form-control" name="kem[]" value='+kem+'></td><td><input type="number" class="form-control" name="jmlkem[]" value='+jmlKem+'></td><td><input type="number" class="form-control" name="qtty_inspect[]" value='+ttl_sisa+'></td><td><input type="text" class="form-control" name="lotnumber[]" value="'+lotnumber+'"></td><td><input type="date" class="form-control" name="tgl_expired[]" value="'+tgl_expired+'"></td><td><a class="btn btn-sm btn-info" href="javascript:void()" title="Cetak""><i class="fas fa-print"></i></a>&nbsp<button class="btn btn-danger btn btn-sm" value="delete" onclick="deleteRow(this)" title="Hapus"><i class="fas fa-trash"></i></a></td></tr>';

            $("#detail_inspect").append(baris_baru);
          }else if(gap < bal_qty && loop != jmlKem){
            ttl = ttl + parseFloat(qtty);
            var baris_baru = '<tr><td><input type="text" class="form-control" name="serial[]" readonly tabindex="-1" placeholder="Auto"></td><td><input type="text" class="form-control" name="kem[]" value='+kem+'></td><td><input type="number" class="form-control" name="jmlkem[]" value='+jmlKem+'></td><td><input type="number" class="form-control" name="qtty_inspect[]" value='+qtty+'></td><td><input type="text" class="form-control" name="lotnumber[]" value="'+lotnumber+'"></td><td><input type="date" class="form-control" name="tgl_expired[]" value="'+tgl_expired+'"></td><td><a class="btn btn-sm btn-info" href="javascript:void()" title="Cetak""><i class="fas fa-print"></i></a>&nbsp<button class="btn btn-danger btn btn-sm" value="delete" onclick="deleteRow(this)" title="Hapus"><i class="fas fa-trash"></i></a></td></tr>';

            $("#detail_inspect").append(baris_baru);
          }else if(gap < bal_qty && loop == jmlKem){
            ttl_sisa = parseFloat(bal_qty) - parseFloat(ttl);
            var baris_baru = '<tr><td><input type="text" class="form-control" name="serial[]" readonly tabindex="-1" placeholder="Auto"></td><td><input type="text" class="form-control" name="kem[]" value='+kem+'></td><td><input type="number" class="form-control" name="jmlkem[]" value='+jmlKem+'></td><td><input type="number" class="form-control" name="qtty_inspect[]" value='+ttl_sisa+'></td><td><input type="text" class="form-control" name="lotnumber[]" value="'+lotnumber+'"></td><td><input type="date" class="form-control" name="tgl_expired[]" value="'+tgl_expired+'"></td><td><a class="btn btn-sm btn-info" href="javascript:void()" title="Cetak""><i class="fas fa-print"></i></a>&nbsp<button class="btn btn-danger btn btn-sm" value="delete" onclick="deleteRow(this)" title="Hapus"><i class="fas fa-trash"></i></a></td></tr>';

            $("#detail_inspect").append(baris_baru);
          }
        }

        // if(ttl_sisa > 0){
        //   var baris_baru = '<tr><td><input type="text" class="form-control" name="serial[]"></td><td><input type="text" class="form-control" name="kem[]" value='+kem+'></td><td><input type="number" class="form-control" name="jmlkem[]" value='+jmlKem+'></td><td><input type="number" class="form-control" name="qtty_inspect[]" value='+ttl_sisa+'></td><td><a class="btn btn-sm btn-info" href="javascript:void()" title="Cetak""><i class="fas fa-print"></i></a>&nbsp<button class="btn btn-danger btn btn-sm" value="delete" onclick="deleteRow(this)" title="Hapus"><i class="fas fa-trash"></i></a></td></tr>';

        //   $("#detail_inspect").append(baris_baru);
        // }

        // console.log(x);
        // console.log(ttl_sisa);

        $("#kemasan").val('');
        $("#jmlkem").val('');
        $("#qtty2").val('');
        $("#row_lotnumber").val('');
        $("#row_tgl_expired").val('');
        $("#kemasan").focus();
      })

      $("#add").click(function() {
        var kem = $("#kemasan").val();
        var jmlKem = $("#jmlkem").val();
        var qtty = $("#qtty2").val();

        if(qtty == "" || qtty < 0 ){
          alert("There is no qtty to input!");
          return;
        }else{

        var baris_baru = '<tr><td><input type="text" class="form-control" name="serial[]"></td><td><input type="text" class="form-control" name="kem[]" value='+kem+'></td><td><input type="number" class="form-control" name="jmlkem[]" value='+jmlKem+'></td><td><input type="number" class="form-control" name="qtty_inspect[]" value='+qtty+'></td><td><a class="btn btn-sm btn-info" href="javascript:void()" title="Cetak""><i class="fas fa-print"></i></a>&nbsp<button class="btn btn-danger btn btn-sm" value="delete" onclick="deleteRow(this)" title="Hapus"><i class="fas fa-trash"></i></a></td></tr>';

        $("#detail_inspect").append(baris_baru);
        }
      })
   });
</script>