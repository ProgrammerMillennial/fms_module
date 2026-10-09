<?php
  $tableRow="";
  $nama = ";;;;;";
  $qty_akhir = 0;
  $line = "";

  if ($this->uri->segment(2) == 'barcode_transaction'){
    $no = 1;
      $nama = $trans["nama"];
      $qty_akhir = floatval("".$trans['qty_akhir']);
      $line = $trans["barcode"];

      $tableRow.="<tr align='center'>";
      $tableRow.="<td>".$no."</td>";
      $tableRow.="<td>".$trans["barcode"]."</td>";
      $tableRow.="<td>".$trans["trans_in"]."</td>";
      $tableRow.="<td>".$trans["tgl_in"]."</td>";
      $tableRow.="<td>".$trans["sj_no"]."</td>";
      $tableRow.="<td>".$trans["pono"]."</td>";
      $tableRow.="<td>".$trans["qty_in"]."</td>";
      $tableRow.="<td>".$trans["trans_out"]."</td>";
      $tableRow.="<td>".$trans["tgl_out"]."</td>";
      $tableRow.="<td>".$trans["mrno"]."</td>";
      $tableRow.="<td>".$trans["qty_out"]."</td>";
      $tableRow.="</tr>";

      $no++;
}
?>        
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <div class="card-tools">
                    <form action="<?php echo base_url(); ?>Material/barcode_transaction/barcode" method="POST">

                      <button type="submit" class="btn btn-primary btn-icon-split" data-placement="bottom" title="Cari">
                          <span class="icon text-white-50">
                            <i class="fa fa-search" aria-hidden="true"></i>
                          </span>
                      </button>

                        <div class="row">
                          <div class="col-sm-4">
                                <label>Barcode :</label>
                                <input type="text" name="barcode" class="form-control" value="<?php echo $line;?>">
                          </div>

                          <div class="col-sm-4">
                                <label>Nama :</label>
                                <input type="text" name="nama" class="form-control" disabled value="<?php echo $nama;?>">
                          </div>

                          <div class="col-sm-4">
                                <label>Qtty Akhir :</label>
                                <input type="number" name="qtty" class="form-control" disabled value="<?php echo $qty_akhir;?>">
                          </div>
                        </div>
                    </form>
                  </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                  <thead>
                    <tr align='center'>
                      <th>No</th>
                      <th>Barcode</th>
                      <th>In Transaction</th>
                      <th>In Date</th>
                      <th>SJ No</th>
                      <th>PO No</th>
                      <th>In Qtty</th>
                      <th>Out Transaction</th>
                      <th>Out Date</th>
                      <th>MR No</th>
                      <th>Out Qtty</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      echo $tableRow;
                    ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>