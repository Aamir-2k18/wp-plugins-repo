<?php

function bootstrap_nav_menu($menu_location) {
    $menu_locations = get_nav_menu_locations();
    $menu_id = $menu_locations[$menu_location];
    $menu = wp_get_nav_menu_items($menu_id);

    $menu_list = '<ul class="navbar-nav">';
    foreach ((array) $menu as $key => $menu_item) {
        $title = $menu_item->title;
        $url = $menu_item->url;
        $menu_list .= '<li class="nav-item">';
        $menu_list .= '<a class="nav-link" href="' . $url . '">' . $title . '</a>';
        if ($menu_item->menu_item_parent == 0) {
            $parent_id = $menu_item->ID;
            $children = get_posts(array('post_type' => 'nav_menu_item', 'numberposts' => 99, 'post_status' => 'publish', 'meta_query' => array(array('key' => '_menu_item_menu_item_parent', 'value' => $parent_id))));
            if (!empty($children)) {
                $menu_list .= '<ul class="dropdown-menu">';
                foreach ((array) $children as $child) {
                    $child_url = get_post_meta($child->ID, '_menu_item_url', true);
                    $child_title = apply_filters('the_title', $child->post_title, $child->ID);
                    $menu_list .= '<li class="nav-item">';
                    $menu_list .= '<a class="nav-link" href="' . $child_url . '">' . $child_title . '</a>';
                    $grandchildren = get_posts(array('post_type' => 'nav_menu_item', 'numberposts' => 99, 'post_status' => 'publish', 'meta_query' => array(array('key' => '_menu_item_menu_item_parent', 'value' => $child->ID))));
                    if (!empty($grandchildren)) {
                        $menu_list .= '<ul class="dropdown-menu">';
                        foreach ((array) $grandchildren as $grandchild) {
                            $grandchild_url = get_post_meta($grandchild->ID, '_menu_item_url', true);
                            $grandchild_title = apply_filters('the_title', $grandchild->post_title, $grandchild->ID);
                            $menu_list .= '<li class="nav-item">';
                            $menu_list .= '<a class="nav-link" href="' . $grandchild_url . '">' . $grandchild_title . '</a>';
                            $menu_list .= '</li>';
                        }
                        $menu_list .= '</ul>';
                    }
                    
                    $menu_list .= '</li>';
                }
                $menu_list .= '</ul>';
            }
        }
        $menu_list .= '</li>';
    }
    $menu_list .= '</ul>';
    return $menu_list;
}


//use this function in your WordPress theme by calling it with the desired menu location:
//echo bootstrap_nav_menu('primary');