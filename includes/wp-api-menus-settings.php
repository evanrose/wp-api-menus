<?php

add_action( 'admin_menu', 'wp_api_menus_create_plugin_settings_page' );
add_action( 'admin_init', 'wp_api_menus_setup_sections' );
add_action( 'admin_init', 'wp_api_menus_setup_fields' );

function wp_api_menus_create_plugin_settings_page() {
	
	$page_title = 'WP API Menus Settings';
	$menu_title = 'WP API Menus Settings';
	$capability = 'manage_options';
	$slug 		= 'wp_api_menus_fields';
	$callback 	= 'wp_api_menus_settings_page_content';
	$icon 		= 'dashicons-admin-plugins';
	$position 	= 100;
	
	add_menu_page( $page_title, $menu_title, $capability, $slug, $callback, $icon, $position );
}

function wp_api_menus_settings_page_content() { ?>
	
	<div class="wrap">
		
		<h2>WP API Settings</h2>

		<?php
        
        	if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] ){
				
				wp_api_menus_admin_notice();
        	} 
        ?>
		<form method="POST" action="options.php">
            <?php
                
                settings_fields( 'wp_api_menus_fields' );
                do_settings_sections( 'wp_api_menus_fields' );
                submit_button();
            ?>
		</form>
	</div>

	<?php
}

function wp_api_menus_admin_notice() { ?>

    <div class="notice notice-success is-dismissible">
        
        <p>Your settings have been updated!</p>
    </div><?php
}

function wp_api_menus_setup_sections() {
    
    add_settings_section( 'wp_api_menus_first_section', '', 'wp_api_menus_section_callback', 'wp_api_menus_fields' );
   
}

function wp_api_menus_section_callback( $arguments ) {
	
	switch( $arguments['id'] ){
		
		case 'wp_api_menus_first_section':
			echo 'These values are used to populate the links value in the WP API Menu JSON feed.';
			break;
	}
}
function wp_api_menus_setup_fields() {
    
    $fields = array(
    	
    	array(
    		'uid' 			=> 'wpapimenu_links_key',
    		'label' 		=> 'Links Key',
    		'section' 		=> 'wp_api_menus_first_section',
    		'type' 			=> 'text',
    		'placeholder' 	=> 'Links Key',
    	),
    	array(
    		'uid' 			=> 'wpapimenu_links_value',
    		'label' 		=> 'Links Value',
    		'section' 		=> 'wp_api_menus_first_section',
    		'type' 			=> 'text',
    		'placeholder' 	=> 'Links Value',
    	),
    );
	
	foreach( $fields as $field ) {
    	
    	add_settings_field( $field['uid'], $field['label'], 'wp_api_menus_field_callback', 'wp_api_menus_fields', $field['section'], $field );
        register_setting( 'wp_api_menus_fields', $field['uid'] );
	}
}

function wp_api_menus_field_callback( $arguments ) {
    
    $value = get_option( $arguments['uid'] );
    
    if( ! $value ) {
        $value = $arguments['default'];
    }
    
    switch( $arguments['type'] ){
        
        case 'text':
            printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value );
            break;
    }
}