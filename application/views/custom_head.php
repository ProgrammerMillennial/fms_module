<script type="text/javascript">
		var Toast = Swal.mixin({
      toast: true,
      position: 'bottom-end',
      showConfirmButton: false,
      timer: 3000
    });

    var url = '<?php echo site_url('Cglobal/getDateServer')?>';
    $.get( url , function( data ) {
            $('[name="tgl_inspect"]').val(data.tgl);
    }, "json" );

    $("#tableHeader").DataTable().off('select');
    $("#tableHeader").DataTable().clear().destroy();
    $("#tableDetail").DataTable().off('select');
    $("#tableDetail").DataTable().clear().destroy();
    $("#tableDetail1").DataTable().off('select');
    $("#tableDetail1").DataTable().clear().destroy();

    function clearTable(){
        $("#tableHeader").DataTable().off('select');
        $("#tableHeader").DataTable().clear().destroy();
        $("#tableDetail").DataTable().off('select');
        $("#tableDetail").DataTable().clear().destroy();
        $("#tableDetail1").DataTable().off('select');
        $("#tableDetail1").DataTable().clear().destroy();
    }

    var URL= window.location.href;
    var arr= URL.split('/');
    var judul = arr[5];
    if(judul == undefined){
        $('#title').html('FMS Module-Dashboard');
    }else{
        $('#title').html('FMS Module-'+ judul);
    }
</script>