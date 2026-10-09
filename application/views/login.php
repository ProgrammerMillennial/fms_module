<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>FMS - Login</title>

    <!-- Custom fonts for this template-->
    <link href="<?php echo base_url(); ?>assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>assets/css/css.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo base_url(); ?>assets/css/sb-admin-2.min.css" rel="stylesheet">
    <!-- Toastr -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/toastr/toastr.min.css">
    <script src="<?php echo base_url(); ?>assets/sweetalert2/sweetalert2.all.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/sweetalert2/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/sweetalert2/sweetalert2.min.css">

    <script src="<?php echo base_url(); ?>assets/plugins/jquery/jquery-3.7.0.js"></script>
    <!-- Toastr -->
    <script src="<?php echo base_url(); ?>assets/plugins/toastr/toastr.min.js"></script>
    <script type="text/javascript">
        var Toast = Swal.mixin({
          toast: true,
          position: 'bottom-end',
          showConfirmButton: false,
          timer: 3000
        });
    </script>
</head>

<body>
    <!-- <br><br><br><br> -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="text-center">
                            <img src="<?php echo base_url(); ?>assets/img/settings.png" class="img-fluid" alt="Sample image" width ="50%" height="50%">
                        </div>
                        <div class="p-5">
                            <div class="text-center">
                                <h2 class="m-0 font-weight-bold text-gray-800">
                                    <b>FMS</b>
                                </h2>
                            </div><br>
                            <div class="input-group mb-3">
                              <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-users"></i></span>
                              </div>
                              <input type="username" class="form-control form-control-user" id="usern" aria-describedby="emailHelp" placeholder="Username">
                            </div>

                            <div class="input-group mb-3">
                              <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                              </div>
                              <input type="password" class="form-control form-control-user" id="passd" placeholder="Password">
                            </div>

                            <button class="btn btn-primary btn-user btn-block" title="Login" id="login" value="login" type="submit">
                                Login
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <!-- <script src="<?php echo base_url(); ?>assets/vendor/jquery/jquery.min.js"></script> -->
    <script src="<?php echo base_url(); ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo base_url(); ?>assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo base_url(); ?>assets/js/sb-admin-2.min.js"></script>

    <script type="text/javascript">
      var input = document.getElementById("passd");
      input.addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            login();
        }
      });


        $("#login").click(function() {
            login();
        });

        function login(){

            var user = $('#usern').val();
            var pass = $('#passd').val();

                if(user == '' || pass == ''){
                    Toast.fire({
                        icon: 'info',
                        title: 'Please input username and password first!'
                    });
                    return;
                }

                const fData = [];
                var formData = {
                    user : user,
                    pass : pass
                };
                fData.push(formData);
                const dataPost = {params: fData};
                $.ajax({
                    url : "<?php echo site_url('CLogin/login_system')?>",
                    type: "POST",
                    data: dataPost,
                    success: function(data)
                    {       
                            const obj = JSON.parse(data);
                            console.log(data);
                            if(obj.status == 'success'){
                                var url ="<?php echo site_url(); ?>" + obj.page;
                                window.location.href = url;
                            }else{
                                document.getElementById('usern').value = ''
                                document.getElementById('passd').value = ''
                                Toast.fire({
                                    icon: 'error',
                                    title: obj.message
                                })
                            }
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        Toast.fire({
                            icon: 'error',
                            title: 'Error while search data, re-chek again !'
                        })
                    }
                });
        }
    </script>

</body>
</html>