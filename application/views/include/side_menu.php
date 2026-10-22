<?php
$menuGroups = $this->menu->get_active_menu_groups('side');
$menu_sub_group_code = isset($this->menu_sub_group_code) ? $this->menu_sub_group_code : NULL;
?>
<div id="sidebar" class="sidebar responsive <?php echo get_cookie('sidebar_layout'); ?>" data-sidebar="true" data-sidebar-scoll="true" data-sidebar-hover="true">
	<script type="text/javascript">
		try {
			ace.settings.check('sidebar', 'fixed')
		} catch (e) {}
	</script>
	<ul class="nav nav-list">
		<?php if (!empty($menuGroups)) : ?>
			<?php foreach ($menuGroups as $menuGroup) : ?>
				<li class="<?php echo isActiveOpenMenu($this->menu_group_code, $menuGroup->code); ?>">
					<a href="#" class="dropdown-toggle">
						<i class="menu-icon fa <?php echo $menuGroup->icon; ?>"></i>
						<span class="menu-text"><?php echo $menuGroup->name; ?></span>
						<b class="arrow fa fa-angle-down"></b>
					</a>
					<?php $count_menu = $this->menu->count_menu($menuGroup->code); ?>
					<?php if ($count_menu > 0) : ?>
						<ul class="submenu">
							<?php $subGroups = $this->menu->get_menus_sub_group($menuGroup->code); ?>
							<?php if (!empty($subGroups)) : ?>
								<?php foreach ($subGroups as $subGroup) : ?>
									<?php $menus = $this->menu->get_menus_by_sub_group($subGroup->code, $menuGroup->code); ?>
									<?php if (!empty($menus)) : ?>
										<li class="<?php echo isActiveOpenMenu($menu_sub_group_code, $subGroup->code); ?>">
											<a href="#" class="dropdown-toggle">
												<i class="menu-icon fa fa-caret-right"></i> <?php echo $subGroup->name; ?> <b class="arrow fa fa-angle-down"></b>
											</a>
											<ul class="submenu">
												<?php foreach ($menus as $menu) : ?>
													<?php echo side_menu($this->menu_code, $menu->code,  $menu->url, $menu->name); ?>
												<?php endforeach; ?>
											</ul>
										</li>
									<?php endif; ?>
								<?php endforeach; ?>
							<?php endif; ?>
							<?php $menus = $this->menu->get_menus_by_group($menuGroup->code, FALSE); ?>
							<?php if (!empty($menus)) : ?>
								<?php foreach ($menus as $menu) : ?>
									<?php echo side_menu($this->menu_code, $menu->code,  $menu->url, $menu->name); ?>
								<?php endforeach; ?>
							<?php endif; ?>
						</ul> <!-- level 1 -->
					<?php endif; //--- end count menu 
					?>
				</li> <!-- / menu group -->
			<?php endforeach; ?>
		<?php endif; ?>
	</ul><!-- /.nav-list -->
	<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse" onclick="toggle_layout()">
		<i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
	</div>
</div>