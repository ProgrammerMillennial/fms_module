<li class="dropdown no-arrow">
    <a class="nav-link dropdown-toggle" href="#" id="modulDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-th-list" aria-hidden="true"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="modulDropdown">
<?php
if(isset($menu_list)){
    $mn_md = '';
    $title = '';
    $icon = '';
    $accordion = '';
    $mn_list = $menu_list;
    foreach ($menu_list as $lst) {
    if($mn_md != $lst['menu_md']){
        $title = $lst['menu_md'];

        if($lst['menu_md'] == 'MBS'){
            $icon = '<i class="fas fa-warehouse fa-sm fa-fw mr-2 text-gray-400"></i>';
        }elseif($lst['menu_md'] == 'MQC'){
            $icon = '<i class="far fa-calendar-check"></i>';
        }

        if($lst['menu_gp'] == 1 and $lst['menu_gm'] == 1){
?>
        <a class="dropdown-item" href="<?php echo site_url($lst['menu_pg']); ?>">
            <?php echo $icon; ?>
            <?php echo $title; ?>
        </a>

<?php
        }
    }
        $mn_md = $lst['menu_md'];
    }
}
?>
    </div>
</li>