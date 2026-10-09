<?php
date_default_timezone_set('Asia/Jakarta');
  $tranno = "";
  $pono = "";
  $tran_date = "";
  $sjno = "";
  $tableRow="";
  $tableRow2="";

  if(isset($header)){
    $tranno = $header['trans'];
    $pono = $header['pono'];
    $tgl= strtotime($header['tglinsp']);
    $tran_date = date("Y-m-d", $tgl);
    $sjno = $header['sjno'];
  }
  if(isset($inspect)){
      $tableRow.="<tr>";
      $tableRow.="<td>".floatval($inspect["seqn"])."</td>";
      $tableRow.="<td>".$inspect["matcode"]."</td>";
      $tableRow.="<td>".$inspect["matname"]."</td>";
      $tableRow.="<td>".$inspect["poqty"]."</td>";
      $tableRow.="<td>".$inspect["inqty"]."</td>";
      $tableRow.="<td>".number_format(floatval($inspect["balqty"]),2)."</td>";
      $tableRow.="</tr>";
  }
  if(isset($barcode)){
    foreach ($barcode as $lst) {
      $tableRow2.="<tr>";
      $tableRow2.="<td>".$lst['barc']."</td>";
      $tableRow2.="<td>".$lst['kem']."</td>";
      $tableRow2.="<td>".$lst['jmlkem']."</td>";
      $tableRow2.="<td>".number_format($lst['qtty'],2)."</td>";
      $tableRow2.="<td>".$lst['act']."</td>";
      $tableRow2.="</tr>";
    }
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
                  <input type="text" class="form-control" id="tranno" disabled value="<?php echo $tranno;?>">
                </div>
                <div class="col-sm-4" id="printall">
                  <label><p></p></label><br>
                  <a class="btn btn-sm btn-info" title="Cetak" onClick="printBybarcode('<?php echo $tranno;?>')"><i class="fas fa-print"></i> Print All</a>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label for="exampleInputEmail1"><b>PO No</b></label>
                      <input type="text" class="form-control" id="pono" disabled value="<?php echo $pono;?>">
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label for="exampleInputEmail1"><b>Inspect Date</b></label>
                    <input type="text" class="form-control" id="tran_date" disabled value="<?php echo $tran_date;?>">
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                      <label for="exampleInputEmail1"><b>SJ No</b></label>
                      <input type="text" class="form-control" id="sj" value="<?php echo $sjno;?>">
                  </div>
                </div>
              </div>
              
              <div class="row">
                <div class="card-body table-responsive p-0">
                  <table class="table table-hover text-nowrap text-gray-800">
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
                <div class="card-body table-responsive p-0">
                  <table class="table table-hover text-nowrap text-gray-800" id="detail">
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
                    <?php
                      echo $tableRow2;
                    ?>
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