<?php

//--- check menu level 1 that open or not
function isOpenMenu($menu, $code)
{
  return $menu === $code ? 'open' : '';
}


function isActiveOpenMenu($menu, $code)
{
  return $menu === $code ? 'active open' : '';
}


function active_menu($menu, $code)
{
  return $menu === $code ? 'active' : '';
}


function side_menu($menu_code, $code, $url, $name)
{
  $menu = '';
  $menu .= '<li class="'.active_menu($menu_code, $code).'">';
  $menu .= '<a href="'.base_url().$url.'">';
  $menu .= '<span class="menu-text">'.$name.'</span>';
  $menu .= '</a>';
  $menu .= '</li>';

  return $menu;
}

 ?>
