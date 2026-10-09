
        let table = new DataTable('#tableHeader', {  
                    // "bDestroy": true,
                    select: {
                                style: 'single'
                            }
                    });

        let table2 = new DataTable('#tableDetail', {      
                    // "bDestroy": true,   
                    select: {
                                style: 'single'
                            }
                    });
        let table3 = new DataTable('#tableDetail1', {      
                    // "bDestroy": true,   
                    select: {
                                style: 'single'
                            }
                    });

        function printBybarcode(id){
            let tipe = id;
            var tipe2;
                if(tipe.substring(0, 3) == 'INH'){
                    tipe2 = 'head';
                }else{
                    tipe2 = 'line';
                }
            var url = "<?php echo site_url('transaction/printNewBarcode')?>/"+tipe2+"/"+id;
            window.open(url);
        }
        function loaderSpinner(){
            Swal.fire({
                title: "Please Wait",
                allowEscapeKey: false,
                allowOutsideClick: false,
                onOpen: function() {
                    Swal.showLoading()
                }
            })
        }

        function loadVendor() {
            var line;
                $.ajax({
                url : "<?php echo site_url('master/loadVendorList')?>",
                type: "POST",
                success: function(data){
                  for (let i = 0; i < data.vendor.length; i++) {
                        var baris_baru = '<option value='+ data.vendor[i]['vend_id'] +'>['+data.vendor[i]['vend_id']+'] '+ data.vendor[i]['vend_nama'] +'</option>';
                        $("#vendor").append(baris_baru);
                  }

                  // const obj = JSON.parse(data);
                  //       for (let i = 0; i < obj.vendor.length; i++) {
                  //           for (var key in obj.vendor[i]) {
                  //                 if (obj.vendor[i].hasOwnProperty(key)) {
                  //                   if(obj.vendor[i]['vend_id'] != line){
                  //                       var baris_baru = '<option value='+ obj.vendor[i]['vend_id'] +'>['+obj.vendor[i]['vend_id']+'] '+ obj.vendor[i]['vend_nama'] +'</option>';
                  //                       $("#vendor").append(baris_baru);
                  //                   }
                  //                   line = obj.vendor[i]['vend_id'];
                  //                 }
                  //           }
                  //        }
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                  alert('Error get data from ajax');
                }
            });
        }

        function loadWarehouse() {
            var line;
                $.ajax({
                url : "<?php echo site_url('master/getWarehouse')?>",
                type: "POST",
                success: function(data){
                  // console.log(data);
                  for (let i = 0; i < data.wh.length; i++) {
                        var baris_baru = '<option value='+ data.wh[i]['opcd'] +'>['+data.wh[i]['opcd']+'] '+ data.wh[i]['name'] +'</option>';
                        $("#wh").append(baris_baru);
                  }
                  // var obj = JSON.parse(data);
                  // console.log(obj);
                        // for (let i = 0; i < obj.wh.length; i++) {
                        //     for (var key in obj.wh[i]) {
                        //           if (obj.wh[i].hasOwnProperty(key)) {
                        //             if(obj.wh[i]['opcd'] != line){
                        //                 var baris_baru = '<option value='+ obj.wh[i]['opcd'] +'>['+obj.wh[i]['opcd']+'] '+ obj.wh[i]['name'] +'</option>';
                        //                 $("#wh").append(baris_baru);
                        //             }
                        //             line = obj.wh[i]['opcd'];
                        //           }
                        //     }
                        //  }
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                  alert('Error get data from ajax');
                }
            });
        }
        
        $('#logout').click(function(){ 
            logout();
        });
          
          function logout(){
            var url = "<?php echo site_url('clogin/logout')?>";
            Swal.fire({
              title: "Do you want to logout ?",
              // text: "You won't be able to revert this!",
              icon: "info",
              showCancelButton: true,
              confirmButtonColor: "#3085d6",
              cancelButtonColor: "#d33",
              confirmButtonText: "Logout"
            }).then((result) => {
              if (result.value == true) {
                window.location.href = url;
              }
            });
          }

        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
              theme: 'bootstrap4'
            })
        });
      
        $(function () {
            //Date range picker
            $('#tgl1').datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $('#tgl2').datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $('#tgl3').datetimepicker({
                format: 'YYYY-MM-DD'
            });
        })

    $('body').bind('copy',function(e) {
    e.preventDefault(); return false; 
    });