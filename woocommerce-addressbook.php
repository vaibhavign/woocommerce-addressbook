<?php
/*
Plugin Name: WooCommerce add shipping address to addressbook
Description: Add shipping address to addressbook
Version: 0.1
Author: Vaibhav Sharma
Author Email: vaibhavign@gmail.com
*/

/**
 * Copyright (c) `date "+%Y"` Vaibhav Sharma. All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * **********************************************************************
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class WC_woocommerce_addressbook{
    public function __construct(){
        register_activation_hook( __FILE__, array( $this, 'createTables' ));
        add_action('woocommerce_checkout_update_order_meta', array($this,'add_to_addressbook'));
    }

    function add_to_addressbook(){
        global $wpdb;
        $current_user = wp_get_current_user();
        if($current_user>0){ // no guest checkout
        $wpdb->insert(   
           $wpdb->prefix . "woo_address", 
           array( 
               'id'=>'',
               'first_name' => $_POST['shipping_first_name'], 
               'last_name' => $_POST['shipping_last_name'],
               'company' => $_POST['shipping_company'],
               'address1' => $_POST['shipping_address_1'],
               'address2' => $_POST['shipping_first_name'],
               'city' => $_POST['shipping_city'],
               'state' => $_POST['shipping_state'],
               'country' => $_POST['shipping_country'],
               'pincode' => $_POST['shipping_postcode'],
               'userid' => $current_user->ID   
                )
            );
        }
    }               
       
    /**
     * Get the plugin url.
     *
     * @access public
     * @return string
     */
    public function plugin_url() {
        if ( $this->plugin_url ) return $this->plugin_url;
        return $this->plugin_url = untrailingslashit( plugins_url( '/', __FILE__ ) );
    }


    /**
     * Get the plugin path.
     *
     * @access public
     * @return string
     */
    public function plugin_path() {
        if ( $this->plugin_path ) return $this->plugin_path;
        return $this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
    }
    
    /**
     * Create table.
     *
     * 
     * 
     */
    
    public function createTables(){
        global $wpdb;
        $table_name = $wpdb->prefix . "woo_address"; 
        $sql = "CREATE TABLE IF NOT EXISTS  $table_name (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `first_name` varchar(255) NOT NULL,
            `last_name` varchar(255) DEFAULT NULL,
            `company` varchar(200) DEFAULT NULL,
            `address1` varchar(500) DEFAULT NULL,
            `address2` varchar(500) DEFAULT NULL,
            `city` varchar(200) DEFAULT NULL,
            `state` varchar(200) DEFAULT NULL,
            `country` varchar(20) DEFAULT NULL,
            `pincode` int(11) DEFAULT NULL,
            `userid` int(11) DEFAULT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=latin1;" ;
        require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
        dbDelta($sql);  
    }
    
}
new WC_woocommerce_addressbook();
