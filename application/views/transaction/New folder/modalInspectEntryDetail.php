<?php
  $tableRow="";

  if(isset($header)){
  }

  if(isset($detail)){
      $tableRow.="<tr>";
      $tableRow.="<td>".floatval($inspect["seqn"])."</td>";
      $tableRow.="<td>".$inspect["matcode"]."</td>";
      $tableRow.="<td>".$inspect["matname"]."</td>";
      $tableRow.="<td>".$inspect["poqty"]."</td>";
      $tableRow.="<td>".$inspect["inqty"]."</td>";
      $tableRow.="<td>".number_format(floatval($inspect["balqty"]),2)."</td>";
      $tableRow.="</tr>";
  }
?>
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
                  <label for="exampleInputEmail1"><b>Trans. No</b></label>
                  <input type="text" class="form-control" id="tranno" disabled>
                </div>
              </div>
              
              <div class="row">
                <div class="col-sm-4">
                    <div class="form-group" id="pono">
                      <label for="exampleInputEmail1"><b>PO No</b></label>
                      <input type="text" class="form-control" name="pono" disabled>
                    </div>
                </div>

                <div class="col-sm-4">
                  <div class="form-group">
                      <label class="font-weight-bold">Inspect Date</label>
                      <div class="input-group date" id="tgl1" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#tgl1" name="tgl_inspect" />
                        <div class="input-group-append" data-target="#tgl1" data-toggle="datetimepicker">
                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                      </div>
                  </div>
                </div>

                <div class="col-sm-4">
                  <div class="form-group">
                    <label for="exampleInputEmail1"><b>SJ No</b></label>
                    <input type="text" class="form-control" id="sj">
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
                        <th>Qtty</th>
                        <th>Qtty Inspect</th>
                        <th>Balance Qtty</th>
                      </tr>
                      </thead>
                      <tbody>
                        <?php
                          echo $tableRow;
                        ?>
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
                    <input type="hidden" name="seqh">
                    <input type="hidden" name="seqn">
                    <input type="hidden" name="matcode">
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
                    <tbody>
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

        var baris_baru = '<tr><td><input type="text" class="form-control" id="serial[]"" value=""></td><td><input type="text" class="form-control" id="kem[]" value='+kem+'></td><td><input type="text" class="form-control" id="jmlkem[]" value='+jmlKem+'></td><td><input type="number" class="form-control" id="qtty[]" value='+qtty+'></td><td><a class="btn btn-sm btn-info" href="javascript:void()" title="Cetak""><i class="fas fa-print"></i></a>&nbsp<button class="btn btn-danger btn btn-sm" value="delete" onclick="deleteRow(this)" title="Hapus"><i class="fas fa-trash"></i></a></td></tr>';

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