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
          <div class="row">
            <div class="col-sm-3">
              <div class="form-group">
                    <select class="form-control select2bs4" style="width: 100%;" id="">
                      <option value="-">Release</option>
                      <option value="MAT">Material Warehouse</option>
                      <option value="ENG">Engineering Warehouse</option>
                      <option value="IDC">IDC Warehouse</option>
                    </select>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <input type="text" id="pono" class="form-control" value="" placeholder="Style"> 
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <input type="text" id="pono" class="form-control" value="" placeholder="Model"> 
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                    <select class="form-control select2bs4" style="width: 100%;" id="">
                      <option value="">Factory</option>
                      <option value="PM">PM</option>
                      <option value="IR2">IR2</option>
                    </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-3">
              <div class="form-group">
                <input type="text" id="pono" class="form-control" value="" placeholder="Comp. Code"> 
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <input type="text" id="pono" class="form-control" value="" placeholder="Comp. Name"> 
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <input type="text" id="pono" class="form-control" value="" placeholder="Material Name"> 
              </div>
            </div>
            <div class="col-sm-3">
                <a type="button" onclick="rePrintQR()" class="btn btn-primary btn-icon-split">
                    <span class="icon text-white-50">
                        <i class="fas fa-search"></i>
                    </span>
                    <span class="text">Search</span>
                </a>
            </div>
          </div>
          <hr class="sidebar-divider d-none d-md-block">
          <div class="row">
            <div class="col-sm-12">      
              <div class="table-responsive">
                  <table class="table table-bordered table-hover text-gray-800 text-center" id="tableDetail1" width="100%" cellspacing="0">
                  <thead>
                      <tr align="center">
                        <th>No</th>
                        <th>Release</th>
                        <th>Style</th>
                        <th>Model</th>
                        <th>Comp.Code</th>
                        <th>Comp.Name</th>
                        <th>Material</th>
                        <th>Balance SPK</th>
                        <th>Action</th>
                      </tr>
                  </thead>
                  <tbody>
                  </tbody>
                  </table>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>