<?php
/**
 * PART 1. Defining Custom Database Table
 * ============================================================================
 *
 * In this part you are going to define custom database table,
 * create it, update, and fill with some dummy data
 *
 * http://codex.wordpress.org/Creating_Tables_with_Plugins
 *
 * In case your are developing and want to check plugin use:
 *
 * DROP TABLE IF EXISTS wp_cte;
 * DELETE FROM wp_options WHERE option_name = 'simple_service_support_install_data';
 *
 * to drop table and option
 */

 /**
 * $simple_service_support_db_version - holds current database version
 * and used on plugin update to sync database tables
 */
global $simple_service_support_db_version;
$simple_service_support_db_version = '1.1'; // version changed from 1.0 to 1.1


function simple_service_support_install()
{
    global $wpdb;
    global $simple_service_support_db_version;

    $table_name = $wpdb->prefix . 'cte'; // do not forget about tables prefix
    $table_name2 = $wpdb->prefix . 'zlecenia_status';

    // sql to create your table
    // NOTICE that:
    // 1. each field MUST be in separate line
    // 2. There must be two spaces between PRIMARY KEY and its name
    //    Like this: PRIMARY KEY[space][space](id)
    // otherwise dbDelta will not work

    // Dodać IF NO EXISTS !!!!!!!!!!!!!!!!!!!!!!!!

    $sql = "CREATE TABLE `" . str_replace('`','', $table_name)  . "` ( 
      id int(11) NOT NULL AUTO_INCREMENT,
      opis_usterki TEXT NULL,
      info_dla_klienta TEXT NULL,
      info_dla_serwisu TEXT NULL,
      przedmiot_zlecenia VARCHAR(20) NULL,
      status_zlecenia VARCHAR(25) NOT NULL,
      brand VARCHAR(20) NULL,
      wyposazenie TEXT NULL,
      numer_seryjny VARCHAR(20),
      model VARCHAR(20) NULL,
      delivery_date DATE NULL,
      data_wydania DATE NULL,
      name tinytext NOT NULL,
      image_1 VARCHAR(150) NULL,
      image_2 VARCHAR(150) NULL,
      image_3 VARCHAR(150) NULL,
      image_4 VARCHAR(150) NULL,
      email VARCHAR(100) NOT NULL,
      plomba INT(10) NULL,
      PRIMARY KEY  (id)
    );";

    $sql .= "CREATE TABLE `" . str_replace('`','', $table_name2)  . "` (
      id int(11) NOT NULL AUTO_INCREMENT,
      status_zlecenia VARCHAR(25) NOT NULL,
      PRIMARY KEY  (id)

    );";

    // we do not execute sql directly
    // we are calling dbDelta which cant migrate database
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    // save current database version for later use (on upgrade)
    add_option('simple_service_support_db_version', $simple_service_support_db_version);

    /**
     * [OPTIONAL] Example of updating to 1.1 version
     *
     * If you develop new version of plugin
     * just increment $simple_service_support_db_version variable
     * and add following block of code
     *
     * must be repeated for each new version
     * in version 1.1 we change email field
     * to contain 200 chars rather 100 in version 1.0
     * and again we are not executing sql
     * we are using dbDelta to migrate table changes
     */
    $installed_ver = get_option('simple_service_support_db_version');
    if ($installed_ver != $simple_service_support_db_version) {
        $sql = "CREATE TABLE IF NO EXISTS `" . str_replace('`','', $table_name)  . "` (
          id int(11) NOT NULL AUTO_INCREMENT,
          opis_usterki text NULL,
          info_dla_klienta TEXT NULL,
          info_dla_serwisu TEXT NULL,
          przedmiot_zlecenia VARCHAR(20) NULL,
          status_zlecenia VARCHAR(25) NOT NULL,
          brand VARCHAR(20) NULL,
          wyposazenie TEXT NULL,
          numer_seryjny VARCHAR(20),
          model VARCHAR(20) NULL,
          delivery_date DATE NULL,
          data_wydania DATE NULL,
          name tinytext NOT NULL,
          image_1 VARCHAR(150) NULL,
          image_2 VARCHAR(150) NULL,
          image_3 VARCHAR(150) NULL,
          image_4 VARCHAR(150) NULL,          
          email VARCHAR(200) NOT NULL,
          plomba INT(10) NULL,
          PRIMARY KEY  (id)
        );";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        // notice that we are updating option, rather than adding it
        update_option('simple_service_support_db_version', $simple_service_support_db_version);
    }
}

register_activation_hook(__FILE__, 'simple_service_support_install');

/**
 * register_activation_hook implementation
 *
 * [OPTIONAL]
 * additional implementation of register_activation_hook
 * to insert some dummy data
 */

/**
 * Trick to update plugin database, see docs
 */
function simple_service_support_update_db_check()
{
    global $simple_service_support_db_version;
    if (get_site_option('simple_service_support_db_version') != $simple_service_support_db_version) {
        simple_service_support_install();
    }
}

add_action('plugins_loaded', 'simple_service_support_update_db_check');