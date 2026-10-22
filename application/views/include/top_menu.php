<?php
$topMenuGroups = $this->menu->get_active_menu_groups('top');
$topSubMenuGroups = isset($this->menu_sub_group_code) ? $this->menu_sub_group_code : NULL;
?>

<nav role="navigation" class="navbar-menu pull-left collapse navbar-collapse">
  <!-- #section:basics/navbar.nav -->
  <?php if(!empty($topMenuGroups)) : ?>
    <?php foreach($topMenuGroups as $topMenu) : ?>
      <?php $subGroups = $this->menu->get_menus_sub_group($topMenu->code); ?>
        <?php if(!empty($subGroups)) : ?>
          <?php foreach($subGroups as $subGroup) : ?>
            <ul class="nav navbar-nav">
              <li>
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                <?php echo $subGroup->name; ?> &nbsp;
                <i class="ace-icon fa fa-angle-down bigger-110"></i>
              </a>
              <?php $menus = $this->menu->get_menus_by_sub_group($subGroup->code, $topMenu->code); ?>
    					<?php if(!empty($menus)) : ?>
                <ul class="dropdown-menu dropdown-light-blue dropdown-caret">
                  <?php foreach($menus as $menu) : ?>
                    <li>
                      <a href="<?php echo base_url().$menu->url; ?>">
                        <i class="ace-icon fa fa-bar-chart bigger-110 blue"></i>
                        <?php echo $menu->name; ?></a>
                    </li>
                  <?php endforeach; ?>
                </ul>
              <?php endif; ?>
              </li>
            </ul>
          <?php endforeach; ?>
        <?php endif; ?>
      <?php endforeach; ?>
  <?php endif; ?>
  <!-- /section:basics/navbar.nav -->
</nav>
