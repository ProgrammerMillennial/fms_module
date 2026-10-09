
<?php
if(isset($menu_list)){
    $mn_id = '';
    $menu = '';
    $icon = '';
    $accordion = '';
    $mn_list = $menu_list;
    foreach ($menu_list as $lst) {
    if($mn_id != $lst['menu_id']){
        if($lst['menu_id'] == 'D'){
?>
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo site_url($lst['menu_pg']); ?>">
            <img src="<?php echo base_url(); ?>assets/img/logo.png" width = "100%">
        </a>
        <hr class="sidebar-divider my-0">
<?php
        }
        if($lst['menu_id'] == 'D'){
            $menu = 'Dashboard';
            $icon = '<i class="fas fa-columns"></i>';
        }elseif($lst['menu_id'] == 'M'){
            $menu = 'Master';
            $icon = '<i class="fas fa-clipboard-list"></i>';
            $accordion = 'collapseOne';
        }elseif($lst['menu_id'] == 'T'){
            $menu = 'Transaction';
            $icon = '<i class="fas fa-dolly-flatbed"></i>';
            $accordion = 'collapseTwo';
        }elseif($lst['menu_id'] == 'R'){
            $menu = 'Report';
            $icon = '<i class="fas fa-chart-line"></i>';
            $accordion = 'collapseThree';
        }

        if($lst['menu_cnt'] == 1){
?>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo site_url($lst['menu_pg']); ?>">
            <?php echo $icon; ?>
            <span><?php echo $menu; ?></span>
        </a>
    </li>
<?php
        }else{
?>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#<?php echo $accordion; ?>" aria-expanded="true" aria-controls="<?php echo $accordion; ?>">
            <?php echo $icon; ?>
            <span><?php echo $menu; ?></span>
        </a>
        <div id="<?php echo $accordion; ?>" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <?php 
                    foreach($mn_list as $lst2){
                        if($lst['menu_id']==$lst2['menu_id']){
                            $url = site_url($lst2['menu_pg']);
                            $nama = $lst2['menu_nm'];
                            echo '<a class="collapse-item" href="'. $url .'">'. $nama .'</a>';
                        }
                    }
                ?>
            </div>
        </div>
    </li>
<?php
        }

    }
        $mn_id = $lst['menu_id'];
    }
}
?>