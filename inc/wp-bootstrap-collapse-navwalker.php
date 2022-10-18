<?php

/**
 * Class Name: Bootstrap_Collapse_NavWalker
 * GitHub URI: https://gist.github.com/mtx-z/db34d68364108c0285e6e3e721630846
 * Description: A custom WordPress 5.3 nav walker class for Bootstrap 4.4 nav menus in a custom theme using the WordPress built in menu manager
 * Version: 0.1
 * Author: Mtxz
 * Source: https://github.com/filipszczepanski/wp-bootstrap4-collapse-navwalker
 * Tested only with 1 sublevel, but should work with as many level as you want
 */

/**
 * Usage
 * <?php if (has_nav_menu('my_menu')): ?>
 * <nav>
 *    <?php
 *    wp_nav_menu([
 *    'theme_location'  => 'my_menu',
 *    'echo'            => true,
 *    'container'       => false,
 *    'container_class' => '',
 *    'container_id'    => '',
 *    'menu_class'      => 'w-auto', //example
 *    'menu_id'         => 'shop_menu_nav', //example
 *    'fallback_cb'     => 'wp_page_menu',
 *    'before'          => '',
 *    'after'           => '',
 *    'link_before'     => '',
 *    'link_after'      => '',
 *    'items_wrap'      => '<ul id="%1$s" class="%2$s" aria-labelledby="shop_menu_nav">%3$s</ul>',
 *    'depth'           => 0,
 *    'walker'          => new Bootstrap_Collapse_NavWalker()
 *  ]); ?>
 * </nav>
 * <?php endif; ?>
 */

/**
 * Class Bootstrap_Collapse_NavWalker
 */
class Bootstrap_Collapse_NavWalker extends Walker_Nav_Menu
{
    var $parent_item_id = 0;
    var $parent_item_depth = false;
    var $parent_has_current_child = false;
    var $currentItem = null;

    /**
     * Starts the list before the elements are added.
     *
     * @since 3.0.0
     *
     * @see Walker::start_lvl()
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param int $depth Depth of menu item. Used for padding.
     * @param stdClass $args An object of wp_nav_menu() arguments.
     */
    public function start_lvl(&$output, $depth = 0, $args = [])
    {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = str_repeat($t, $depth);
        $collapse_in_class = $this->parent_has_current_child ? 'in' : '';
        $collapse_id = '';
        if (!empty($this->parent_item_id)) {
            $collapse_id = $this->collapse_id($this->parent_item_id);
        }

        //if is current parent and have children: open collapse
        if (null !== $this->currentItem
            && isset($this->currentItem->classes)
            && in_array('current-menu-parent', $this->currentItem->classes, true)
            && in_array('menu-item-has-children', $this->currentItem->classes, true)) {
            $collapse_in_class .= ' show';
        }

        $collapse_block = sprintf('<ul id="%s" class="nav collapse %s" aria-labelledby="link_%s" role="tabpanel">' . "\n", $collapse_id, $collapse_in_class, $collapse_id);
        $output .= $n . $indent . $collapse_block . $n;
    }

    /**
     * Ends the list of after the elements are added.
     *
     * @since 3.0.0
     *
     * @see Walker::end_lvl()
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param int $depth Depth of menu item. Used for padding.
     * @param stdClass $args An object of wp_nav_menu() arguments.
     */
    public function end_lvl(&$output, $depth = 0, $args = [])
    {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = str_repeat($t, $depth);
        $output .= "$indent</ul>{$n}";
    }

    /**
     * Starts the element output.
     *
     * @since 3.0.0
     * @since 4.4.0 The {@see 'nav_menu_item_args'} filter was added.
     *
     * @see Walker::start_el()
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param WP_Post $item Menu item data object.
     * @param int $depth Depth of menu item. Used for padding.
     * @param stdClass $args An object of wp_nav_menu() arguments.
     * @param int $id Current item ID.
     */
    public function start_el(&$output, $item, $depth = 0, $args = [], $id = 0)
    {
        $this->currentItem = $item;
        $classes = empty($item->classes) ? [] : (array)$item->classes;
        if ($this->parent_item_depth !== $depth || $this->parent_item_id !== $item->ID) {
            $this->parent_item_depth = $depth;
            $this->parent_item_id = $item->ID;
            $this->parent_has_current_child = (in_array('current-menu-ancestor', $classes, true));
            $this->start_el($output, $item, $depth, $args, $item->ID);
        } else {
            if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
                $t = '';
                $n = '';
            } else {
                $t = "\t";
                $n = "\n";
            }
            $indent = ($depth) ? str_repeat($t, $depth) : '';
            $this->parent_item_depth = 0;
            $classes[] = 'menu-item-' . $item->ID;
            $classes[] = 'nav-item';

            if (in_array('current-menu-item', $classes, true)) {
                $classes[] = ' active';
            }

            /**
             * Filters the arguments for a single nav menu item.
             *
             * @since 4.4.0
             *
             * @param stdClass $args An object of wp_nav_menu() arguments.
             * @param WP_Post $item Menu item data object.
             * @param int $depth Depth of menu item. Used for padding.
             */
//    		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

            /**
             * Filters the CSS class(es) applied to a menu item's list item element.
             *
             * @since 3.0.0
             * @since 4.1.0 The `$depth` parameter was added.
             *
             * @param array $classes The CSS classes that are applied to the menu item's `<li>` element.
             * @param WP_Post $item The current menu item.
             * @param stdClass $args An object of wp_nav_menu() arguments.
             * @param int $depth Depth of menu item. Used for padding.
             */
            $class_names = implode(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
            $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
            /**
             * Filters the ID applied to a menu item's list item element.
             *
             * @since 3.0.1
             * @since 4.1.0 The `$depth` parameter was added.
             *
             * @param string $menu_id The ID that is applied to the menu item's `<li>` element.
             * @param WP_Post $item The current menu item.
             * @param stdClass $args An object of wp_nav_menu() arguments.
             * @param int $depth Depth of menu item. Used for padding.
             */
            $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth);
            $id = $id ? ' id="' . esc_attr($id) . '"' : '';
            $item_output = $indent . '<li' . $id . $class_names . '>';
            $atts = [];
            $atts[ 'title' ] = !empty($item->attr_title) ? $item->attr_title : '';
            $atts[ 'target' ] = !empty($item->target) ? $item->target : '';
            $atts[ 'rel' ] = !empty($item->xfn) ? $item->xfn : '';
            $atts[ 'href' ] = !empty($item->url) ? $item->url : '';
            if ($depth === 0) {
                $atts[ 'class' ] = 'nav-link';
            } elseif ($depth > 0) {
                // $atts[ 'class' ] = 'link-item';
                $atts[ 'class' ] = 'nav-link';
            }

            $collapse_icon_atts = array();

            if ($args && isset($args->walker) && $args->walker->has_children) {
                $collapse_icon_atts[ 'data-toggle' ] = 'collapse';
                $collapse_icon_atts[ 'aria-expanded' ] = 'false';
                $collapse_icon_atts[ 'aria-controls' ] = $this->collapse_id($item->ID);
                // $atts['data-parent']  = '#nav-panel-left';
                $collapse_icon_atts[ 'role' ] = 'tab';
                $collapse_icon_atts[ 'href' ] = '#' . $this->collapse_id($item->ID);
                // $collapse_icon_atts[ 'data-target' ] = '#' . $this->collapse_id($item->ID);
                $collapse_icon_atts[ 'id' ] = 'link_' . $this->collapse_id($item->ID);
            }
            if (is_array($item->classes)
                && in_array('current-menu-item', $item->classes, true)
                && in_array('nav-item', $item->classes, true)) {
                $atts[ 'class' ] .= ' active';
            }

            /**
             * Filters the HTML attributes applied to a menu item's anchor element.
             *
             * @since 3.6.0
             * @since 4.1.0 The `$depth` parameter was added.
             *
             * @param array $atts {
             *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
             *
             * @type string $title Title attribute.
             * @type string $target Target attribute.
             * @type string $rel The rel attribute.
             * @type string $href The href attribute.
             * }
             * @param WP_Post $item The current menu item.
             * @param stdClass $args An object of wp_nav_menu() arguments.
             * @param int $depth Depth of menu item. Used for padding.
             */
            $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);

            /**
             * Is parent item and not active: collapse.
             * If is a parent and active, do not add "collapsed" class so it'll be in "open state"
             */
            if (in_array('menu-item-has-children', $item->classes, true)
                && !in_array('current-menu-parent', $item->classes, true)) {
                $atts[ 'class' ] .= ' collapsed';
            }


            // if ( current_user_can( 'manage_options' ) ) {
            //     echo '<pre>'; 
            //         print_r( $args ); 
            //     echo '</pre>';
            // }
            
            

            if (
                $item->url == '#' && 
                $args && 
                isset($args->walker) &&
                $args->walker->has_children
            ) {
                $atts[ 'data-toggle' ] = 'collapse';
                $atts[ 'aria-expanded' ] = 'false';
                $atts[ 'aria-controls' ] = $this->collapse_id($item->ID);
                $atts[ 'role' ] = 'tab';
                $atts[ 'href' ] = '#' . $this->collapse_id($item->ID);
                $atts[ 'id' ] = 'link_' . $this->collapse_id($item->ID);
            }

            $attributes = '';
            foreach ($atts as $attr => $value) {
                if (!empty($value)) {
                    $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                    $attributes .= ' ' . $attr . '="' . $value . '"';
                }
            }

            $collapse_icon_attributes = '';
            if ( $collapse_icon_atts ) {
                foreach ($collapse_icon_atts as $attr => $value) {
                    if (!empty($value)) {
                        $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                        $collapse_icon_attributes .= ' ' . $attr . '="' . $value . '"';
                    }
                }
            }
            /** This filter is documented in wp-includes/post-template.php */
            $title = apply_filters('the_title', $item->title, $item->ID);
            /**
             * Filters a menu item's title.
             *
             * @since 4.4.0
             *
             * @param string $title The menu item's title.
             * @param WP_Post $item The current menu item.
             * @param stdClass $args An object of wp_nav_menu() arguments.
             * @param int $depth Depth of menu item. Used for padding.
             */
            $title = apply_filters('nav_menu_item_title', $title, $item, $args, $depth);
            $item_output .= $args->before;
            $item_output .= '<a' . $attributes . '>';
            $item_output .= $args->link_before . $title . $args->link_after;
            $item_output .= '</a>';

            if ( $collapse_icon_atts ) {
                $item_output .= '<a' . $collapse_icon_attributes . '>';
                $item_output .= '</a>';
            }

            $item_output .= $args->after;

            /**
             * Filters a menu item's starting output.
             *
             * The menu item's starting output only includes `$args->before`, the opening `<a>`,
             * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
             * no filter for modifying the opening and closing `<li>` for a menu item.
             *
             * @since 3.0.0
             *
             * @param string $item_output The menu item's starting HTML output.
             * @param WP_Post $item Menu item data object.
             * @param int $depth Depth of menu item. Used for padding.
             * @param stdClass $args An object of wp_nav_menu() arguments.
             */
            $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
        }
    }

    /**
     * Ends the element output, if needed.
     *
     * @since 3.0.0
     *
     * @see Walker::end_el()
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param WP_Post $item Page data object. Not used.
     * @param int $depth Depth of page. Not Used.
     * @param stdClass $args An object of wp_nav_menu() arguments.
     */
    public function end_el(&$output, $item, $depth = 0, $args = [])
    {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $output .= "</li>{$n}";
    }

    private function collapse_id($nav_id)
    {
        return 'collapse_' . $nav_id;
    }
} // Bootstrap_Collapse_NavWalker
