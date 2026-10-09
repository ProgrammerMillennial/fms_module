<div class="card shadow mb-4">
  <div class="card-body">
    <div class="row">
      <div class="col-sm-12">
        <div class="form-group">
          <input type="text" class="form-control" id="barcode" name="barcode" placeholder="Barcode">
        </div>
        <input type="hidden" id="user" name="user" value="<?php echo $_SESSION['user_id']; ?>">
                        
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-gray-800 text-center" id="tableHeader" width="100%" cellspacing="0">
            <thead>
                <tr align="center">
                  <th width="5%">No</th>
                  <th width="6%">Style</th>
                  <th width="5%">Comp. Code</th>
                  <th width="10%">Comp. Name</th>
                  <th width="50%">Material</th>
                  <th>Unit</th>
                  <th>Qtty</th>
                  <th>Serial</th>
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