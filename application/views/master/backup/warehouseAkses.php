
<?= 
    $tableRow="";
    if ($this->uri->segment(2) == 'warehouse'){
        foreach ($list as $lst) {
            $tableRow.="<tr align='center'>";
            $tableRow.="<td>".$lst->ROTE_OPCD."</td>";
            $tableRow.="<td>".$lst->ROTE_NAME."</td>";
            $tableRow.="<td></td>";
            // $tableRow.='<td><a class="btn btn-info btn-circle" href="javascript:void()" title="Update" onclick="edit_wh('."'".$lst->ROTE_OPCD."'".')"><i class="fas fa-pencil-alt"></i></a>
            //                 <a class="btn btn-danger btn-circle" href="javascript:void()" title="Hapus" onclick="delete_wh('."'".$lst->ROTE_OPCD."'".')"><i class="fas fa-trash"></i></a>';

            $tableRow.="</tr>";
        }
    }
?>

<!-- Page Heading -->
<!-- <h1 class="h3 mb-2 text-gray-800">Warehouse Area</h1> -->


        <div class="card shadow mb-4 border-bottom-primary">
            <div class="card-header py-3">
                <h4 class="m-0 font-weight-bold text-primary">Warehouse Area</h4>
            </div>
            <div class="card-body">
                <a href="<?php echo base_url(); ?>Material/warehouse/wh" class="btn btn-primary btn-icon-split" data-placement="bottom" title="Cari">
                    <span class="icon text-white-50">
                        <i class="fa fa-search" aria-hidden="true"></i>
                    </span>
                    <!-- <span class="text">Cari</span> -->
                </a>

<!--                 <a href="#" class="btn btn-primary btn-icon-split" data-toggle="modal" data-target="#modal_form" data-placement="bottom" title="Tambah" onclick="add_wh()">
                    <span class="icon text-white-50">
                        <i class="fa fa-plus-circle" aria-hidden="true"></i>
                    </span>
                    <span class="text">Tambah</span>
                </a> -->
                
                <div class="my-3"></div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr align="center">
                                <th>Warehouse ID</th>
                                <th>Warehouse Name</th>
                                <th>Area</th>
                                <!-- <th width="14%">Action</th> -->
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

      <div class="modal fade" id="modal_form">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Warehouse Form</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form role="form" id="form">
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Code Departement</label>
                    <!-- <input type="hidden" value="" name="id"/>  -->
                    <input type="text" class="form-control" id="kode" name="kode" maxlength="20" style="text-transform:uppercase" /> 
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Name Departement</label>
                    <input type="text" class="form-control" name="nama" maxlength="50" style="text-transform:uppercase" />
                  </div>
                </div>
                <!-- /.card-body -->
              </form>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="btnSave" onclick="save()">Save changes</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>

<script type="text/javascript">

    var save_method; //for save method string
    var table;

    function reload_table()
    {
      table.ajax.reload(null,false); //reload datatable ajax 
    }

    function add_wh()
    {
      save_method = 'add';
      $('#form')[0].reset(); // reset form on modals
      $('#modal_form').modal('show'); // show bootstrap modal
      $('.modal-title').text('Add New Warehouse'); // Set Title to Bootstrap modal title
      $( "#kode" ).prop( "disabled", false );
    }

    function edit_wh(id)
    {
      save_method = 'update';
      $('#form')[0].reset(); // reset form on modals

      //Ajax Load data from ajax
      $.ajax({
        url : "<?php echo site_url('master/edit_warehouse')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
          $( "#kode" ).prop( "disabled", true );
          $('[name="kode"]').val(data.ROTE_OPCD);
          $('[name="nama"]').val(data.ROTE_NAME);

            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Warehouse'); // Set title to Bootstrap modal title
            
          },
          error: function (jqXHR, textStatus, errorThrown)
          {
            alert('Error get data from ajax');
          }
        });
    }

    function save()
    {
      var url;
      if(save_method == 'add') 
      {
        url = "<?php echo site_url('departement/ajax_add')?>";
      }
      else
      {
        url = "<?php echo site_url('departement/ajax_update')?>";
      }

       // ajax adding data to database
       $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data)
        {
               //if success close modal and reload ajax table
               $('#modal_form').modal('hide');
               reload_table();
               Swal.fire(
                  'Success!',
                  'New data has been saved!',
                  'success'
                )
        },

        error: function (jqXHR, textStatus, errorThrown)
             {
                Swal.fire(
                  'Error!',
                  'Error while edit or add data!',
                  'error'
                )
            }
          });
     }

     function delete_wh(id)
     {
        Swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url : "<?php echo site_url('departement/ajax_delete')?>/" + id,
              type: "POST",
              dataType: "JSON",
              success: function(data){
                             //if success reload ajax table
                             $('#modal_form').modal('hide');
                             reload_table();
                              Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                              );
                           },
              error: function (jqXHR, textStatus, errorThrown)
                    {
                             alert('Error adding / update data');
                    }
                  });

            Swal.fire(
              'Deleted!',
              'Your file has been deleted.',
              'success'
            )
          }
        })
     }
</script>