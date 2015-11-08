<?php
/*
Plugin Name: Simple Service Support
Description: Plugin do obsługi serwisu
Plugin URI: http://www.tech-sas.pl
Author URI: http://www.tech-sas.pl
Author: Tadeusz Sasnal
License: Public Domain
Version: 1.1
Text Domain: simple_service_support
Domain Path: /languages
*/

load_plugin_textdomain('simple_service_support', false, basename( dirname( __FILE__ ) ) . '/languages' );  // Translations :)

/**
 * PART 1. Defining Custom Database Table
 * ============================================================================
 */
require_once(ABSPATH. 'wp-content/plugins/oko/install_functions.php');

simple_service_support_install();    // FUNKCJA INSTALUJĄCA TABELE W BAZIE + SAMPLE DATA


/**
 * PART 2. Defining Custom Table List
 * ============================================================================
 *
 * This part define custom table list class, that will display database records in nice looking table
 *
 * http://codex.wordpress.org/Class_Reference/WP_List_Table
 * http://wordpress.org/extend/plugins/custom-list-table-example/
 */

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

/**
 * simple_service_support_List_Table class that will display our custom table records in nice table
 */


class simple_service_support_List_Table extends WP_List_Table
{
    /**
     * [REQUIRED] must declare constructor and give some basic params
     */
    function __construct()
    {
        global $status, $page;

        parent::__construct(array(
            'singular' => 'person',
            'plural' => 'zlecenia',
            'ajax' => false
        ));
       
    }


	function extra_tablenav( $which ) {
	if ( $which == "top" ){
		options_status();
		?><input type="submit" name="show" id="namelist" value="Submit" />
		<?php
	}
	if ( $which == "bottom" ){
		//The code that goes after the table is there
		
	}
}

    /**
     * [REQUIRED] this is a default column renderer
     *
     * @param $item - row (key, value array)
     * @param $column_name - string (key)
     * @return HTML
     */
    function column_default($item, $column_name)
    {
        switch( $column_name ) {
     case 'id':
     case 'opis_usterki':
     case 'przedmiot_zlecenia':
     case 'status_zlecenia':
     case 'name':
     case 'numer_seryjny':
     case 'brand':
     case 'model':
     case 'email':
     case 'delivery_date':
     case 'data_wydania':
     case 'options':
        return $item[ $column_name ];
    default:
        return print_r( $item, true ) ;
    }
}

    /**
     * [OPTIONAL] this is example, how to render specific column
     *
     * method name must be like this: "column_[column_name]"
     *
     * @param $item - row (key, value array)
     * @return HTML
     */

    function column_options($item){
                $actions = array(
            'edit' => sprintf('<a href="?page=zlecenia_form&id=%s">%s</a>', $item['id'], __('Edit', 'simple_service_support')),
            'delete' => sprintf('<a href="?page=%s&action=delete&id=%s">%s</a>', $_REQUEST['page'], $item['id'], __('Delete', 'simple_service_support')),
        );
                        return sprintf('%s %s',
            '<a href="?page=zlecenia_form&id='.$item['id'].'"></a>',  // pokazuje name po kliknięciu możliwość edycji
            $this->row_actions($actions)  // po najechaniu dodaje 2 opcje ze zmienej $actions : edytuj albo usuń.
        );
    }
    
    function column_status_zlecenia($item)
    {
        $status_zlecenia = $item['status_zlecenia'] ;
        switch( $status_zlecenia ) {
            case 'Przyjęty do serwisu':
        return '<div style="background-color:#5DCFC3; padding:5px; border-radius:2px;">'.$status_zlecenia.'</div>';
            case 'Oczekiwanie na części':
        return '<span style="background-color:#E567B1; padding:5px; border-radius:2px;">'.$status_zlecenia.'</span>';
            case 'Wydany':
        return '<div style="background-color:#CBF76F; padding:5px; border-radius:2px;">'.$status_zlecenia.'</div>';
            case 'W trakcie naprawy':
        return '<span style="background-color:#FFAE73; padding:5px; border-radius:2px;">'.$status_zlecenia.'</span>';
            default:
        return $status_zlecenia ;
    }



        //return '<em>' . $item['status_zlecenia'] . '</em>';
    }
	

    /**
     * [OPTIONAL] this is example, how to render column with actions,
     * when you hover row "Edit | Delete" links showed
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_name($item)
    {
        // links going to /admin.php?page=[your_plugin_page][&other_params]
        // notice how we used $_REQUEST['page'], so action will be done on curren page
        // also notice how we use $this->_args['singular'] so in this example it will
        // be something like &person=2

       /* $actions = array(
            'edit' => sprintf('<a href="?page=zlecenia_form&id=%s">%s</a>', $item['id'], __('Edit', 'simple_service_support')),
            'delete' => sprintf('<a href="?page=%s&action=delete&id=%s">%s</a>', $_REQUEST['page'], $item['id'], __('Delete', 'simple_service_support')),
        );
       */
        return sprintf(//'%s %s',
            '<a href="?page=zlecenia_form&id='.$item['id'].'">'.$item['name'].'</a>'  // pokazuje name po kliknięciu możliwość edycji
            //$this->row_actions($actions)  // po najechaniu dodaje 2 opcje ze zmienej $actions : edytuj albo usuń.
        );
    }

    /**
     * [REQUIRED] this is how checkbox column renders
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />',
            $item['id']
        );
    }

    /**
     * [REQUIRED] This method return columns to display in table
     * you can skip columns that you do not want to show
     * like content, or description
     *
     * @return array
     */
    function get_columns()    // TA FUNKCJA POKAZUJE MI KOLUMNY
    {
        $columns = array(
            'cb' => '<input type="checkbox" />', //Render a checkbox instead of text
			'id' => __('ID', 'simple_service_support'),
            'przedmiot_zlecenia' => __('Przedmiot zlecenia', 'simple_service_support'),
            'status_zlecenia' => __('Status Order', 'simple_service_support'),
            'numer_seryjny' => __('Serial Number', 'simple_service_support'),
            'name' => __('Name', 'simple_service_support'),
            'brand' => __('Brand', 'simple_service_support'),
            'model' => __('Model', 'simple_service_support'),
            'opis_usterki' => __('Fault Description', 'simple_service_support'),
            'delivery_date' => __('Delivery Date', 'simple_service_support'),
            'data_wydania' => __('Data Wydania', 'simple_service_support'),
            'email' => __('E-Mail', 'simple_service_support'),
            'options' => __('Options', 'simple_service_support')
            
        );
        return $columns;
    }

    /**
     * [OPTIONAL] This method return columns that may be used to sort table
     * all strings in array - is column names
     * notice that true on name column means that its default sort
     *
     * @return array
     */
    function get_sortable_columns()
    {
        $sortable_columns = array(
			'id' => array('id', false),
            'name' => array('name', false),
            'email' => array('email', false),
            'delivery_date' => array('delivery_date', false),
            'data_wydania' => array('data_wydania', false),
            'status_zlecenia' => array('status_zlecenia', false),
        ); 
        return $sortable_columns;
    }

    /**
     * [OPTIONAL] Return array of bult actions if has any
     *
     * @return array
     */
    function get_bulk_actions()
    {
        $actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }

    /**
     * [OPTIONAL] This method processes bulk actions
     * it can be outside of class
     * it can not use wp_redirect coz there is output already
     * in this example we are processing delete action
     * message about successful deletion will be shown on page in next part
     */
    function process_bulk_action()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'cte'; // tables prefix

        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);

            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $table_name WHERE id IN($ids)");
            }
        }
    }

    /**
     * [REQUIRED] This is the most important method
     *
     * It will get rows from database and prepare them to be showed in table
     */
    function prepare_items()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'cte'; // tables prefix

        $per_page = $this->get_items_per_page('books_per_page'/*,5*/) ; // constant, how much records will be shown per page

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $current_page = $this->get_pagenum();

        // here we configure table headers, defined in our methods
        $this->_column_headers = $this->get_column_info(); /*array($columns, $hidden, $sortable);*/

        // [OPTIONAL] process bulk action if any
        $this->process_bulk_action();

        // will be used in pagination settings
        

        // prepare query params, as usual current page, order by and order direction
        $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'id';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'desc';

        // [REQUIRED] define $items array
        // notice that last argument is ARRAY_A, so we will retrieve array
        if(isset($_POST['s']) && ($_POST['s']) != NULL ){  // JEŻELI JEST USTAWIONA ZMIENNA POST Z WYSZUKIWARKI
       
        	// Trim Search Term
        	$search = $_POST['s'];   // PRZYPISZ WARTOŚC POST DO ZMIENNEJ
$val = $_GET['namelist'];
        	$search = trim($search);  // WYWAL SPACJE
       

        	// WYPISZ ZNALEZIONE WYNIIKI
        	$this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE name LIKE '%%$search%%' OR numer_seryjny LIKE '%%$search%%' AND status_zlecenia LIKE '%%$val%%' ", $per_page, $paged), ARRAY_A);
        	$total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name WHERE name LIKE '%%$search%%'");
 
		}else if (isset($_GET['namelist']) && !(isset($_POST['s']) && ($_POST['s']) != NULL)) {
			$val = $_GET['namelist'];

        	$this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE status_zlecenia LIKE '%%$val%%'", $per_page, $paged), ARRAY_A);
        	$total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name WHERE status_zlecenia LIKE '%%$val%%'");

			
		}else{
			 // WYPISZ WSZYSTKIE DOSTĘPNE WPISZY Z BAZY
        	$this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $paged), ARRAY_A);
        	$total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name");
		}

        // [REQUIRED] configure pagination
        $this->set_pagination_args(array(
            'total_items' => $total_items, // total items defined above
            'per_page' => $per_page, // per page constant defined at top of method
            'total_pages' => ceil($total_items / $per_page) // calculate pages count
        ));
    }
}


/**
 * PART 3. Admin page
 * ============================================================================
 *
 * In this part you are going to add admin page for custom table
 *
 * http://codex.wordpress.org/Administration_Menus
 */

/**
 * admin_menu hook implementation, will add pages to list zlecenia and to add new one
 */
 
function simple_service_support_admin_menu()
{
    $hook = add_menu_page(__('Order status', 'simple_service_support'), __('Order status', 'simple_service_support'), 'activate_plugins', 'zlecenia', 'simple_service_support_zlecenia_page_handler');
    add_submenu_page('zlecenia', __('Order status', 'simple_service_support'), __('Order status', 'simple_service_support'), 'activate_plugins', 'zlecenia', 'simple_service_support_zlecenia_page_handler');
    // add new will be described in next part
    add_submenu_page('zlecenia', __('Add / Edit', 'simple_service_support'), __('Add / Edit', 'simple_service_support'), 'activate_plugins', 'zlecenia_form', 'simple_service_support_zlecenia_form_page_handler');
    add_submenu_page('zlecenia', __('Statystyki', 'simple_service_support'), __('Statystyki', 'simple_service_support'), 'activate_plugins', 'statystyki', 'simple_service_support_zlecenia_stats');
    add_action( "load-$hook", 'simple_service_support_add_options' );
}

function simple_service_support_add_options() 
{
    global $table;
    $option = 'per_page';

    $args = array(
        'label' => 'Wierszy',
        'default' => 10,
        'option' => 'books_per_page'
    );

add_screen_option( $option, $args );
$table = new simple_service_support_List_Table();
}

add_action('admin_menu', 'simple_service_support_admin_menu');

add_filter('set-screen-option', 'test_table_set_option', 10, 3);

function test_table_set_option($status, $option, $value) {
  return $value;
}

/**
 * List page handler
 *
 * This function renders our custom table
 * Notice how we display message about successfull deletion
 * Actualy this is very easy, and you can add as many features
 * as you want.
 *
 * Look into /wp-admin/includes/class-wp-*-list-table.php for examples
 */
function simple_service_support_zlecenia_page_handler()
{
    global $wpdb;

    $table = new simple_service_support_List_Table();
    if(isset($_POST['s'])){
    	$table->prepare_items($_POST['s']);
    } else {
        $table->prepare_items();
    }

    $message = '';
    if ('delete' === $table->current_action()) {
        $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Items deleted: %d', 'simple_service_support'), count($_REQUEST['id'])) . '</p></div>';
    }
    ?>
<div class="wrap">

    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php _e('Order status', 'simple_service_support')?> <a class="add-new-h2"
                                 href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=zlecenia_form');?>"><?php _e('Add New', 'simple_service_support')?></a>
    </h2>
        <?php echo $message; ?>

    <form method="post">
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
        <?php $table->search_box('Search Table', 'search_id'); ?>
    </form>

    <form id="zlecenia-table" method="GET">
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
        <?php $table->display() ?>
        
    </form>


</div>


<?php
}


/**
 * PART 4. Form for adding andor editing row
 * ============================================================================
 *
 * In this part you are going to add admin page for adding andor editing items
 * You cant put all form into this function, but in this example form will
 * be placed into meta box, and if you want you can split your form into
 * as many meta boxes as you want
 *
 * http://codex.wordpress.org/Data_Validation
 * http://codex.wordpress.org/Function_Reference/selected
 */

/**
 * Form page handler checks is there some data posted and tries to save it
 * Also it renders basic wrapper in which we are callin meta box render
 */

function simple_service_support_zlecenia_stats ()
{

    global $wpdb;
    $table_name = $wpdb->prefix . 'cte'; // tables prefix
}





function simple_service_support_zlecenia_form_page_handler()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'cte'; // tables prefix

    $message = '';
    $notice = '';

    // this is default $item which will be used for new records
    $default = array(
        'id' => 0,
        'opis_usterki' => '',
        'przedmiot_zlecenia' => '',
        'status_zlecenia' => '',
        'name' => '',
        'brand' => '',
        'wyposazenie' => '',
        'numer_seryjny' => '',
        'model' => '',
        'delivery_date' => '',
        'data_wydania' => '',
        'info_dla_klienta' => '',
        'info_dla_serwisu' => '',
        'email' => '',
        'plomba' => '',
        'image_1' => '',
        'image_2' => '',
        'image_3' => '',
        'image_4' => '',
    );

    // here we are verifying does this request is post back and have correct nonce
    if (wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {
        // combine our default item with request params
        $item = shortcode_atts($default, $_REQUEST);
        // validate data, and if all ok save item to database
        // if id is zero insert otherwise update
        $item_valid = simple_service_support_validate_person($item);
        if ($item_valid === true) {
            if ($item['id'] == 0) {
                $result = $wpdb->insert($table_name, $item);
                $item['id'] = $wpdb->insert_id;
                if ($result) {
                    $message = __('Item was successfully saved', 'simple_service_support');
                } else {
                    $notice = __('There was an error while saving item', 'simple_service_support');
                }
            } else {
                $result = $wpdb->update($table_name, $item, array('id' => $item['id']));
                if ($result) {
                    $message = __('Item was successfully updated', 'simple_service_support');
                } else {
                    $notice = __('There was an error while updating item', 'simple_service_support');
                }
            }
        } else {
            // if $item_valid not true it contains error message(s)
            $notice = $item_valid;
        }
    }
    else {
        // if this is not post back we load item to edit or give new one to create
        $item = $default;
        if (isset($_REQUEST['id'])) {
            $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $_REQUEST['id']), ARRAY_A);
            if (!$item) {
                $item = $default;
                $notice = __('Item not found', 'simple_service_support');
            }
        }
    }

    // here we adding our custom meta box
    add_meta_box('zlecenia_form_meta_box', 'Order details', 'simple_service_support_zlecenia_form_meta_box_handler', 'person', 'normal', 'default');

    ?>
<div class="wrap">
    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>

    <h2><?php 
    if (!empty($item['id'])) {
    	    _e('Order Details', 'simple_service_support'); _e(' no. ', 'simple_service_support'); echo $item['id']; ?> <a class="add-new-h2"
                                href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=zlecenia');?>"><?php _e('back to list', 'simple_service_support')?></a>
    <?php }else{
    		_e('Add New Order', 'simple_service_support')?> <a class="add-new-h2"
                                href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=zlecenia');?>"><?php _e('back to list', 'simple_service_support')?></a>
    <?php } ?>
    

    </h2>

    <?php if (!empty($notice)): ?>
        <div id="notice" class="error"><p><?php echo $notice ?></p></div>
    <?php endif;?>
    <?php if (!empty($message)): ?>
        <div id="message" class="updated"><p><?php echo $message ?></p></div>
    <?php endif;?>

    <form id="form" method="POST">
        <input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>"/>
        <?php /* NOTICE: here we storing id to determine will be item added or updated */ ?>
        <input type="hidden" name="id" value="<?php echo $item['id'] ?>"/>

        <div class="metabox-holder" id="poststuff">
            <div id="post-body">
                <div id="post-body-content">
                    <?php /* And here we call our custom meta box */ ?>
                    <?php do_meta_boxes('person', 'normal', $item); ?>
                    <input type="submit" value="<?php _e('Save', 'simple_service_support')?>" id="submit" class="button-primary" name="submit">
                </div>
            </div>
        </div>
    </form>
</div>
<?php
}

/**
 * This function renders our custom meta box
 * $item is row
 *
 * @param $item
 */



/*wp_enqueue_style('jquery-ui-css', 'http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css');*/

function wp_gear_manager_admin_scripts() {
    wp_enqueue_script('media-upload');
    //wp_enqueue_script('thickbox');
    wp_enqueue_script('jquery');
    wp_enqueue_media();
    wp_enqueue_script('my-uploader', WP_PLUGIN_URL. '/oko/scripts/m-uploader.js', array('jquery'/*,'media-upload','thickbox'*/));
    wp_enqueue_script('my-uploader');
    wp_enqueue_script('datepicker', WP_PLUGIN_URL. '/oko/scripts/datepicker.js');
    wp_enqueue_script('jquery-ui-datepicker');

    wp_enqueue_script( 'fancybox', WP_PLUGIN_URL. '/oko/js/jquery.fancybox.pack.js', array( 'jquery' ), false, true );
    wp_enqueue_script( 'lightbox', WP_PLUGIN_URL. '/oko/js/lightbox.js', array( 'fancybox' ), false, true );
   
   
}

function wp_gear_manager_admin_styles() {
    //wp_enqueue_style('thickbox');
    wp_enqueue_style('my_css', WP_PLUGIN_URL. '/oko/css/style.css');
    wp_enqueue_style('jquery-ui-css', 'http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css');

    wp_enqueue_style( 'lightbox-style', WP_PLUGIN_URL. '/oko/css/jquery.fancybox.css' );

   
}

add_action('admin_print_scripts', 'wp_gear_manager_admin_scripts');
add_action('admin_print_styles', 'wp_gear_manager_admin_styles');

//add_action( 'wp_enqueue_scripts', 'add_thickbox' );   // dodaje Thickbox do pokazywania zdjęć w ładnych wyskakujących ramkach.

//wp_enqueue_script('m-uplader', WP_PLUGIN_URL. '/oko/scripts/m-uploader.js');



function options_status(){
            global $wpdb;
    		$table_name = $wpdb->prefix . 'zlecenia_status'; // tables prefix
            $options_status = $wpdb->get_row($wpdb->prepare("SELECT 'status_zlecenia' FROM $table_name" ));
            ?>

 			<select name="status_zlecenia">  <!-- TU COŚ NIE ŚMIGA DO POPRAWY -->
            	<?php foreach ($options_status as $option_status): ?>
                	<option value="<?php echo $option_status; ?>"<?php if (esc_attr($item['status_zlecenia']) == $option_status): ?> selected="selected"<?php endif; ?>>
                    	<?php echo ($option_status); ?>
                	</option>
                <?php endforeach; ?>
            </select>
         
<?php
}


function simple_service_support_zlecenia_form_meta_box_handler($item)
{

    ?>


<table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
    <tbody>
    <tr class="form-field">
        <th valign="top" scope="row" style="width: 15%;">
            <label for="name"><?php _e('Name', 'simple_service_support')?></label>
        </th>
        <td style="width: 35%;">
            <input id="name" name="name" type="text"  value="<?php echo esc_attr($item['name'])?>"
                   size="50" class="code" placeholder="<?php _e('Your name', 'simple_service_support')?>" required>
        </td>
        <th valign="top" scope="row" style="width: 15%;">
            <label for="numer_seryjny"><?php _e('Serial Number', 'simple_service_support')?></label>
        </th>
        <td style="width: 35%;">
            <input id="numer_seryjny" name="numer_seryjny" type="text"  value="<?php echo esc_attr($item['numer_seryjny'])?>"
                   size="50" class="code" placeholder="<?php _e('Serial Number', 'simple_service_support')?>" >
        </td>
    </tr>

    <tr class="form-field">
            <th valign="top" scope="row">
            <label for="przedmiot_zlecenia"><?php _e('Przedmiot zlecenia', 'simple_service_support')?></label>
        </th>
        <td>
            <?php
            	$options_przedmiot_zlecenia = array("", "Laptop", "Komputer PC", "Tablet", "Monitor");
            ?>
 
            <select name="przedmiot_zlecenia" >
            <?php foreach ($options_przedmiot_zlecenia as $option_przedmiot_zlecenia): ?>
                <option value="<?php echo $option_przedmiot_zlecenia; ?>"<?php if (esc_attr($item['przedmiot_zlecenia']) == $option_przedmiot_zlecenia): ?> selected="selected"<?php endif; ?>>
                    <?php echo $option_przedmiot_zlecenia; ?>
                </option>
            <?php endforeach; ?>
            </select>

        </td>
        <th valign="top" scope="row">
            <label for="opis_usterki"><?php _e('Fault Description', 'simple_service_support')?></label>
        </th>
        <td>
            <textarea id="opis_usterki" name="opis_usterki" type="text"
                   size="50" class="code" placeholder="<?php _e('Fault Description', 'simple_service_support')?>" ><?php echo esc_attr($item['opis_usterki'])?></textarea>
        </td>
    </tr>
    <tr class="form-field">
    	<th valign="top" scope="row">
    		<label for="info_dla_klienta"><?php _e('Info Dla Klienta', 'simple_service_support')?></label>
    	</th>
    	<td>
    		<textarea id="info_dla_klienta" name="info_dla_klienta" type="text"
    				size="50" class="code" placeholder="<?php _e('Info Dla Klienta', 'simple_service_support')?>" ><?php echo esc_attr($item['info_dla_klienta'])?></textarea>
    	</td>
        <th valign="top" scope="row">
            <label for="info_dla_serwisu"><?php _e('Info Dla Serwisu', 'simple_service_support')?></label>
        </th>
        <td>
            <textarea id="info_dla_serwisu" name="info_dla_serwisu" type="text"
                    size="50" class="code" placeholder="<?php _e('Info Dla Serwisu', 'simple_service_support')?>" ><?php echo esc_attr($item['info_dla_serwisu'])?></textarea>
        </td>
    </tr>

    <tr class="form-field">
        <th valign="top" scope="row">
            <label for="brand"><?php _e('Brand / Model', 'simple_service_support')?></label>
        </th>
        <td>
            <?php
            	$options_brand = array("", "Acer", "Asus", "Dell", "Fujitsu Siemens", "Gateway", "HP", "Lenovo", "MSI", "Sony", "Samsung", "Toshiba");
            ?>
 
            <select name="brand" style="width: 47%">
            <?php foreach ($options_brand as $option_brand): ?>
                <option value="<?php echo $option_brand; ?>"<?php if (esc_attr($item['brand']) == $option_brand): ?> selected="selected"<?php endif; ?>>
                    <?php echo $option_brand; ?>
                </option>
             <?php endforeach; ?>
            </select>
            <input id="model" name="model" type="text" style="width: 48%" value="<?php echo esc_attr($item['model'])?>" size="50" class="code" placeholder="<?php _e('Model', 'simple_service_support')?>" >
        </td>
         <th valign="top" scope="row">
            <label for="email"><?php _e('Plomba', 'simple_service_support')?></label>
        </th>
        <td>
            <input id="plomba" name="plomba" type="text" value="<?php echo esc_attr($item['plomba'])?>"
                   size="50" class="code" placeholder="<?php _e('Plomba', 'simple_service_support')?>" >        

        </td>
    </tr>

    <tr class="form-field">
        <th valign="top" scope="row">
            <label for="email"><?php _e('E-Mail', 'simple_service_support')?></label>
        </th>
        <td>
            <input id="email" name="email" type="email" value="<?php echo esc_attr($item['email'])?>"
                   size="50" class="code" placeholder="<?php _e('Your E-Mail', 'simple_service_support')?>" >
        </td>

        <th valign="top" scope="row">
            <label for="delivery_date"><?php _e('Delivery Date / Wydnia', 'simple_service_support')?></label>
        </th>
        <td>
            <input type="text" id="delivery_date" name="delivery_date" style="width: 47%" value="<?php echo esc_attr($item['delivery_date'])?>"
                    size="50" class="code" placeholder="<?php _e('Delivery Date', 'simple_service_support')?>">
            <input type="text" id="data_wydania" name="data_wydania" style="width: 47%" value="<?php echo esc_attr($item['data_wydania'])?>"
                    size="50" class="code" placeholder="<?php _e('Data Wydania', 'simple_service_support')?>">
        </td>

    </tr>
    <tr class="form-field">
        <th valign="top" scope="row">
            <label for="status_zlecenia"><?php _e('Order status', 'simple_service_support')?></label>
        </th>
        <td>
        <?php

            global $wpdb;
    		$table_name = $wpdb->prefix . 'zlecenia_status'; // tables prefix
            $options_status = $wpdb->get_col($wpdb->prepare("SELECT status_zlecenia FROM $table_name"));

            ?>
            <select name="status_zlecenia">
            <?php foreach ($options_status as $option_status): ?>
                <option value="<?php echo $option_status; ?>"<?php if (esc_attr($item['status_zlecenia']) == $option_status): ?> selected="selected"<?php endif; ?>>
                    <?php echo ($option_status); ?>
                    
                </option>
             <?php endforeach; ?>
            </select>


        </td>
        <th valign="top" scope="row">
            <label for="wyposazenie"><?php _e('Equipment', 'simple_service_support')?></label>
        </th>
        <td>
            <input id="wyposazenie" name="wyposazenie" type="text"  value="<?php echo esc_attr($item['wyposazenie'])?>"
                   size="50" class="code" placeholder="<?php _e('Equipment', 'simple_service_support')?>" >
        </td>
    </tr>
    <tr class="form-field">
        <!-- <th valign="top" scope="row">
            <label for="foto"><?php _e('Foto', 'simple_service_support')?></label>
        </th> -->

<td colspan="2">
	<input type="text" id="image_1" name="image_1" value="<?php echo esc_attr($item['image_1'])?>" style="width: 60%; float:left; margin:0 5px;"/>
	<input id="_btn" class="upload_image_button" type="button" value="<?php _e('Upload Photo', 'simple_service_support')?>" style="float:left; width: 35%;" />
	<?php
		if (!empty($item['image_1'])) {
	        $image_1 = esc_attr($item['image_1']);
			echo '<a href="'.$image_1.'" rel="gallery" class="fancybox"><img src="'. $image_1 .'"></a>';
		}
	?>

	<input type="text" id="image_2" name="image_2" value="<?php echo esc_attr($item['image_2'])?>" style="width: 60%; float:left; margin:0 5px;"/>
	<input id="_btn" class="upload_image_button" type="button" value="<?php _e('Upload Photo', 'simple_service_support')?>" style="float:left; width: 35%;" />
	<?php
		if (!empty($item['image_2'])) {
			$image_2 = esc_attr($item['image_2']);
			echo '<a href="'.$image_2.'" rel="gallery" class="fancybox"><img src="'. $image_2 .'"></a>';

		}
	?>

</td>
        <!-- <th valign="top" scope="row">
            <label for="foto"><?php _e('Foto (300x200)', 'simple_service_support')?></label>
        </th> -->
<td colspan="2">

<input type="text" id="image_3" name="image_3" value="<?php echo esc_attr($item['image_3'])?>" style="width: 60%; float:left; margin:0 5px;"/>
<input id="_btn" class="upload_image_button" type="button" value="<?php _e('Upload Photo', 'simple_service_support')?>" style="float:left; width: 35%;" />
<?php
	if (!empty($item['image_3'])) {
		$image_3 = esc_attr($item['image_3']);
		echo '<a href="'.$image_3.'" rel="gallery" class="fancybox"><img src="'. $image_3 .'"></a>';

	}
?>


<input type="text" id="image_4" name="image_4" value="<?php echo esc_attr($item['image_4'])?>" style="width: 60%; float:left; margin:0 5px;"/>
<input id="_btn" class="upload_image_button" type="button" value="<?php _e('Upload Photo', 'simple_service_support')?>" style="float:left; width: 35%;" />
<?php
	if (!empty($item['image_4'])) {
		$image_4 = esc_attr($item['image_4']);
		echo '<a href="'.$image_4.'" rel="gallery" class="fancybox"><img src="'. $image_4 .'"></a>';

	}
?>

</td>

    </tr>

    </tbody>
</table>
<?php
}

/**
 * Simple function that validates data and retrieve bool on success
 * and error message(s) on error
 *
 * @param $item
 * @return bool|string
 */
function simple_service_support_validate_person($item)
{
    $messages = array();

    if (empty($item['name'])) $messages[] = __('Name is required', 'simple_service_support');
    if (!empty($item['email']) && !is_email($item['email'])) $messages[] = __('E-Mail is in wrong format', 'simple_service_support');
    // if (!ctype_digit($item['age'])) $messages[] = __('Age in wrong format', 'simple_service_support');
    //if(!empty($item['age']) && !absint(intval($item['age'])))  $messages[] = __('Age can not be less than zero');
    if(!empty($item['plomba']) && !preg_match('/[0-9]+/', $item['plomba'])) $messages[] = __('Plomba must be number');
    //...

    if (empty($messages)) return true;
    return implode('<br />', $messages);
}

/**
 * Do not forget about translating your plugin, use __('english string', 'your_uniq_plugin_name') to retrieve translated string
 * and _e('english string', 'your_uniq_plugin_name') to echo it
 * in this example plugin your_uniq_plugin_name == simple_service_support
 *
 * Name your file like this: [my_plugin]-[ru_RU].po
 *
 * http://codex.wordpress.org/Writing_a_Plugin#Internationalizing_Your_Plugin
 * http://codex.wordpress.org/I18n_for_WordPress_Developers
 */
function simple_service_support_languages()
{
    load_plugin_textdomain('simple_service_support', false, dirname(plugin_basename(__FILE__)));
}

add_action('init', 'simple_service_support_languages');




 require_once(ABSPATH. 'wp-content/plugins/oko/admin_widget.php');

/**
 * Add a widget to the dashboard.
 *
 * This function is hooked into the 'wp_dashboard_setup' action below.
 */
function example_add_dashboard_widgets() {

    wp_add_dashboard_widget(
                 'simple_service_support_dashboard_widget',         // Widget slug.
                 'Status Zleceń',                                 // Title.
                 'simple_service_support_dashboard_widget_function' // Display function.
        );  
}
add_action( 'wp_dashboard_setup', 'example_add_dashboard_widgets' );