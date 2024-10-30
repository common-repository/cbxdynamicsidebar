<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://codeboxr.com
 * @since      1.0.0
 *
 * @package    CBXDynamicSidebar
 * @subpackage CBXDynamicSidebar/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    CBXDynamicSidebar
 * @subpackage CBXDynamicSidebar/admin
 * @author     Codeboxr <info@codeboxr.com>
 */
class CBXDynamicSidebar_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	public $loader;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of this plugin.
	 * @param      string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Add new col in cbxsidebar admin post listing
	 *
	 * @param $column_name
	 * @param $post_id
	 */
	function cbxsidebar_column( $column_name, $post_id ) {
		if ( $column_name == 'shortcode' ) {
			echo '<span data-flexi="0" id="cbxsidebarshortcode-' . $post_id . '" class="cbxsidebarshortcode cbxsidebarshortcode-' . $post_id . '">[cbxdynamicsidebar id="' . $post_id . '"]</span>';

		}
	}//end method cbxsidebar_column

	public function cbxsidebar_columns( $columns ) {
		//unset date col if set
		if ( isset( $columns['date'] ) ) {
			unset( $columns['date'] );
		}

		//add shortcode col title
		$columns['shortcode'] = esc_html__( 'Shortcode', 'cbxdynamicsidebar' );

		return $columns;
	}//end method cbxsidebar_columns

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles( $hook ) {
		global $post_type;

		if ( in_array( $hook, array( 'post.php', 'post-new.php', 'edit.php' ) ) && 'cbxsidebar' == $post_type ) {

			wp_register_style( 'chosen-jquery',
				plugin_dir_url( __FILE__ ) . '../assets/css/chosen.min.css',
				array(),
				$this->version,
				'all' );
			wp_register_style( 'cbxdynamicsidebar-admin',
				plugin_dir_url( __FILE__ ) . '../assets/css/cbxdynamicsidebar-admin.css',
				array( 'chosen-jquery' ),
				$this->version,
				'all' );

			wp_enqueue_style( 'chosen-jquery' );
			wp_enqueue_style( 'cbxdynamicsidebar-admin' );
		}
	}//end method enqueue_styles

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts( $hook ) {
		global $post_type;


		if ( in_array( $hook, array( 'post.php', 'post-new.php', 'edit.php' ) ) && 'cbxsidebar' == $post_type ) {

			wp_register_script( 'chosen-jquery',
				plugin_dir_url( __FILE__ ) . '../assets/js/chosen.jquery.min.js',
				array( 'jquery' ),
				$this->version,
				true );
			wp_register_script( 'cbxdynamicsidebar-admin',
				plugin_dir_url( __FILE__ ) . '../assets/js/cbxdynamicsidebar-admin.js',
				array( 'jquery', 'chosen-jquery' ),
				$this->version,
				true );

			$cbxdynamicsidebar_js_vars = apply_filters( 'cbxdynamicsidebar_js_vars',
				array(
					'list_view'      => ( $hook == 'edit.php' ) ? 1 : 0,
					'manage_widgets' => array(
						'title' => esc_html__( 'Manage Widgets', 'cbxdynamicsidebar' ),
						'url'   => esc_url( admin_url( 'widgets.php' ) ),
					),
				) );

			wp_localize_script( 'cbxdynamicsidebar-admin', 'cbxdynamicsidebar_js_vars', $cbxdynamicsidebar_js_vars );

			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'chosen-jquery' );
			wp_enqueue_script( 'cbxdynamicsidebar-admin' );
		}


		/*if($hook == 'widgets.php'){
			wp_register_script( 'cbxdynamicsidebar-widgets', plugin_dir_url( __FILE__ ) . '../assets/js/cbxdynamicsidebar-widgets.js', array( 'jquery'), $this->version, true );

			$cbxdynamicsidebar_widgets_js_vars = apply_filters('cbxdynamicsidebar_widgets_js_vars', array(

			));

			wp_localize_script( 'cbxdynamicsidebar-widgets', 'cbxdynamicsidebar_widgets_js_vars', $cbxdynamicsidebar_widgets_js_vars );

			wp_enqueue_script('jquery');
			wp_enqueue_script('cbxdynamicsidebar-widgets');
		}*/

	}//end method enqueue_scripts

	// Register Custom Post Type
	public function create_sidebar() {

		$labels = array(
			'name'               => _x( 'Sidebars', 'Post Type General Name', 'cbxdynamicsidebar' ),
			'singular_name'      => _x( 'Sidebar', 'Post Type Singular Name', 'cbxdynamicsidebar' ),
			'menu_name'          => esc_html__( 'CBX Sidebars', 'cbxdynamicsidebar' ),
			'parent_item_colon'  => esc_html__( 'Parent Sidebar:', 'cbxdynamicsidebar' ),
			'all_items'          => esc_html__( 'All Sidebars', 'cbxdynamicsidebar' ),
			'view_item'          => esc_html__( 'View Sidebar', 'cbxdynamicsidebar' ),
			'add_new_item'       => esc_html__( 'Add New Sidebar', 'cbxdynamicsidebar' ),
			'add_new'            => esc_html__( 'Add New', 'cbxdynamicsidebar' ),
			'edit_item'          => esc_html__( 'Edit Sidebar', 'cbxdynamicsidebar' ),
			'update_item'        => esc_html__( 'Update Sidebar', 'cbxdynamicsidebar' ),
			'search_items'       => esc_html__( 'Search Sidebar', 'cbxdynamicsidebar' ),
			'not_found'          => esc_html__( 'Not found', 'cbxdynamicsidebar' ),
			'not_found_in_trash' => esc_html__( 'Not found in Trash', 'cbxdynamicsidebar' ),
		);
		$args   = array(
			'label'               => esc_html__( 'cbxsidebar', 'cbxdynamicsidebar' ),
			'description'         => esc_html__( 'Dynamic Sidebars', 'cbxdynamicsidebar' ),
			'labels'              => $labels,
			'supports'            => array( 'title' ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			//'menu_position'       => 5,
			'menu_icon'           => 'dashicons-list-view',
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'capability_type'     => 'post',
		);
		register_post_type( 'cbxsidebar', $args );

	}

	/**
	 * Register all custom dynamic sidebars
	 */
	public function cbxdynamicsidebar_register_sidebars() {
		global $post;

		//get all post types(aka cbxdynamicsidebar type posts )
		$args = array(
			'post_type'      => 'cbxsidebar',
			'post_status'    => 'publish',
			'posts_per_page' => - 1,

		);

		$sidebar_posts = get_posts( $args );
		foreach ( $sidebar_posts as $post ) : CBXDynamicSidebarHelper::setup_admin_postdata( $post );
			global $post;
			$id         = $post->ID;
			$post_title = $post->post_title;

			$sidebar_custom = get_post_meta( $id, '_cbxdynamicsidebar', true );

			if ( is_array( $sidebar_custom ) && sizeof( $sidebar_custom ) == 0 ) {
				continue;
			}

			$active = intval( $sidebar_custom['active'] );

			if ( ! $active ) {
				continue;
			}

			$description   = html_entity_decode( $sidebar_custom['description'] );
			$before_widget = html_entity_decode( $sidebar_custom['before_widget'] );
			$after_widget  = html_entity_decode( $sidebar_custom['after_widget'] );
			$before_title  = html_entity_decode( $sidebar_custom['before_title'] );
			$after_title   = html_entity_decode( $sidebar_custom['after_title'] );

			register_sidebar( array(
				'name'          => $post_title,
				'id'            => 'cbxdynamicsidebar-' . $id,
				'description'   => $description,
				'before_widget' => $before_widget,
				'after_widget'  => $after_widget,
				'before_title'  => $before_title,
				'after_title'   => $after_title,
			) );

		endforeach;
		// Reset Post Data
		CBXDynamicSidebarHelper::wp_reset_admin_postdata();
	}//end method cbxdynamicsidebar_register_sidebars

	public function dynamic_sidebar_check_post2( $thing ) {
		write_log( $thing );
	}

	/**
	 * Sidebar check post if we allow the sidebar to disbale or not
	 *
	 * @param $sidebar_id
	 */
	public function dynamic_sidebar_check_post( $sidebar_id ) {
		global $post;
		global $wp_registered_sidebars;
		global $current_user;
		//$sidebars_widgets = wp_get_sidebars_widgets();

		//write_log($wp_registered_sidebars);
		//write_log($wp_registered_widgets);

		//get all post types(aka cbxdynamicsidebar type posts )
		$args = array(
			'post_type'      => 'cbxsidebar',
			'post_status'    => 'publish',
			'posts_per_page' => - 1,

		);

		$sidebar_posts = get_posts( $args );
		foreach ( $sidebar_posts as $post ) : CBXDynamicSidebarHelper::setup_admin_postdata( $post );
			global $post;


			$id         = $post->ID;
			$post_title = $post->post_title;

			$sidebar_custom = get_post_meta( $id, '_cbxdynamicsidebar', true );

			if ( is_array( $sidebar_custom ) && sizeof( $sidebar_custom ) == 0 ) {
				continue;
			}

			$active = intval( $sidebar_custom['active'] );

			if ( ! $active ) {
				continue;
			}

			/*$description        = html_entity_decode($sidebar_custom['description']);
			$before_widget      = html_entity_decode($sidebar_custom['before_widget']);
			$after_widget       = html_entity_decode($sidebar_custom['after_widget']);
			$before_title       = html_entity_decode($sidebar_custom['before_title']);
			$after_title        = html_entity_decode($sidebar_custom['after_title']);*/

			$sidebar_id_current = 'cbxdynamicsidebar-' . $id;

			if ( $sidebar_id == $sidebar_id_current ) {
				$all_user_roles     = CBXDynamicSidebarHelper::user_plain_role_keys( true );
				$sidebar_user_roles = isset( $sidebar_custom['user_roles'] ) ? maybe_unserialize( $sidebar_custom['user_roles'] ) : $all_user_roles;

				if ( is_user_logged_in() ) {
					$user_id = get_current_user_id();;
				} else {
					$user_id = 0;
				}

				if ( $user_id == 0 ) {
					$userRoles = array( 'guest' );
				} else {
					$userRoles = $current_user->roles;
				}

				if ( ! is_array( $sidebar_user_roles ) ) {
					$sidebar_user_roles = array();
				}

				//write_log($userRoles);

				$intersectedRoles = array_intersect( $sidebar_user_roles, $userRoles );
				if ( sizeof( $intersectedRoles ) == 0 ) {
					//sidebar failed to pass check post :(

					//write_log($sidebar_id_current.' failed');

					//write_log($wp_registered_widgets);

					//if(isset($wp_registered_sidebars[$sidebar_id_current])) unset($wp_registered_sidebars[$sidebar_id_current]);
					//if(isset($wp_registered_widgets[$sidebar_id_current])) unset($wp_registered_widgets[$sidebar_id_current]);

					//write_log($wp_registered_widgets[$sidebar_id_current]);

					//write_log($wp_registered_sidebars[$sidebar_id_current]);
					//write_log($sidebars_widgets[$sidebar_id_current]);


					//$wp_registered_sidebars[$sidebar_id_current] = array();
				}


			}

			/* register_sidebar( array(
				 'name'          => $post_title,
				 'id'            => 'cbxdynamicsidebar-'.$id,
				 'description'   => $description,
				 'before_widget' => $before_widget,
				 'after_widget'  => $after_widget,
				 'before_title'  => $before_title,
				 'after_title'   => $after_title
			 ) );*/

		endforeach;
		// Reset Post Data
		CBXDynamicSidebarHelper::wp_reset_admin_postdata();
	}//end method dynamic_sidebar_check_post


	public function sidebar_action_row( $actions, $post ) {

		if ( $post->post_type == "cbxsidebar" ) {
			unset( $actions['inline hide-if-no-js'] );
			unset( $actions['view'] );
		}

		return $actions;

	}


	/**
	 * Meta box init box
	 */
	public function add_meta_boxes() {

		//photo meta box
		add_meta_box(
			'cbxdynamicsidebarmetabox',
			esc_html__( 'Sidebar Definitions', 'cbxdynamicsidebar' ),
			array( $this, 'cbxdynamicsidebarmetabox_display' ),
			'cbxsidebar',
			'normal',
			'high'
		);
	}//end method add_meta_boxes

	public function cbxdynamicsidebarmetabox_display( $post ) {

		$post_id = intval( $post->ID );

		$fieldValues = get_post_meta( $post_id, '_cbxdynamicsidebar', true );
		wp_nonce_field( 'cbxdynamicsidebarmetabox', 'cbxdynamicsidebarmetabox[nonce]' );

		echo '<h2>' . esc_html__( 'Shortcode Usages:', 'cbxdynamicsidebar' ) . '</h2>';

		echo '<span data-flexi="1" id="cbxsidebarshortcode-' . $post_id . '" class="cbxsidebarshortcode cbxsidebarshortcode-flexi cbxsidebarshortcode-' . $post_id . '">[cbxdynamicsidebar id="' . $post_id . '" float="none" wid="" wclass="" /]</span>';

		echo '<div id="cbxdynamicsidebarmetabox_wrapper">';


		$active        = isset( $fieldValues['active'] ) ? intval( $fieldValues['active'] ) : 1;
		$description   = isset( $fieldValues['description'] ) ? html_entity_decode( $fieldValues['description'] ) : esc_html__( 'Yet Another Dynamic Sidebar',
			'cbxdynamicsidebar' );
		$class         = isset( $fieldValues['class'] ) ? html_entity_decode( $fieldValues['class'] ) : 'cbxdynamicsidebar';
		$before_widget = isset( $fieldValues['before_widget'] ) ? html_entity_decode( $fieldValues['before_widget'] ) : '<div id="%1$s" class="widget widget-cbxdynamicsidebar %2$s">';
		$after_widget  = isset( $fieldValues['after_widget'] ) ? html_entity_decode( $fieldValues['after_widget'] ) : '</div>';
		$before_title  = isset( $fieldValues['before_title'] ) ? html_entity_decode( $fieldValues['before_title'] ) : '<h2 class="widget-title widget-title-cbxdynamicsidebar">';
		$after_title   = isset( $fieldValues['after_title'] ) ? html_entity_decode( $fieldValues['after_title'] ) : '</h2>';

		//$all_user_roles = CBXDynamicSidebarHelper::user_roles( true, true);
		//$user_roles         = isset($fieldValues['user_roles'])? maybe_unserialize($fieldValues['user_roles']): array_keys($all_user_roles);


		?>


        <table class="form-table">
            <tbody>
            <tr valign="top">
                <th scope="row"><label
                            for="cbxdynamicsidebarmetabox_fields_class"><?php echo esc_html__( 'Sidebar Enabe/Disable',
							'cbxdynamicsidebar' ) ?></label></th>
                <td>
                    <legend class="screen-reader-text"><span>input type="radio"</span></legend>
                    <label title='g:i a'>
                        <input type="radio" name="cbxdynamicsidebarmetabox[active]" value="0" <?php checked( $active,
							'0',
							true ); ?> />
                        <span><?php esc_attr_e( 'No', 'cbxdynamicsidebar' ); ?></span>
                    </label><br>
                    <label title='g:i a'>
                        <input type="radio" name="cbxdynamicsidebarmetabox[active]" value="1" <?php checked( $active,
							'1',
							true ); ?> />
                        <span><?php esc_attr_e( 'Yes', 'cbxdynamicsidebar' ); ?></span>
                    </label>

                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label
                            for="cbxdynamicsidebarmetabox_fields_description"><?php echo esc_html__( 'Description',
							'cbxdynamicsidebar' ) ?></label></th>
                <td>
                    <textarea id="cbxdynamicsidebarmetabox_fields_description" class="regular-text"
                              name="cbxdynamicsidebarmetabox[description]"
                              placeholder="<?php echo $description; ?>"><?php echo htmlentities( $description ); ?></textarea>

                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="cbxdynamicsidebarmetabox_fields_class"><?php echo esc_html__( 'CSS Class',
							'cbxdynamicsidebar' ) ?></label></th>
                <td>
                    <input id="cbxdynamicsidebarmetabox_fields_class" class="regular-text" type="text"
                           name="cbxdynamicsidebarmetabox[class]" placeholder="cbxdynamicsidebar"
                           value="<?php echo htmlentities( $class ); ?>"/>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label
                            for="cbxdynamicsidebarmetabox_fields_before_widget"><?php echo esc_html__( 'Before Widget Html Wrapping',
							'cbxdynamicsidebar' ) ?></label></th>
                <td>
                    <input id="cbxdynamicsidebarmetabox_fields_before_widget" class="regular-text" type="text"
                           name="cbxdynamicsidebarmetabox[before_widget]"
                           placeholder="<?php echo htmlentities( $before_widget ); ?>"
                           value="<?php echo htmlentities( $before_widget ); ?>"/>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label
                            for="cbxdynamicsidebarmetabox_fields_after_widget"><?php echo esc_html__( 'After Widget Html Wrapping',
							'cbxdynamicsidebar' ) ?></label></th>
                <td>
                    <input id="cbxdynamicsidebarmetabox_fields_after_widget" class="regular-text" type="text"
                           name="cbxdynamicsidebarmetabox[after_widget]"
                           placeholder="<?php echo htmlentities( $after_widget ); ?>"
                           value="<?php echo htmlentities( $after_widget ); ?>"/>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label
                            for="cbxdynamicsidebarmetabox_fields_before_title"><?php echo esc_html__( 'Before Title Html Wrapping',
							'cbxdynamicsidebar' ) ?></label></th>
                <td>
                    <input id="cbxdynamicsidebarmetabox_fields_before_title" class="regular-text" type="text"
                           name="cbxdynamicsidebarmetabox[before_title]"
                           placeholder="<?php echo htmlentities( $before_title ); ?>"
                           value="<?php echo htmlentities( $before_title ); ?>"/>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label
                            for="cbxdynamicsidebarmetabox_fields_after_title"><?php echo esc_html__( 'After Title Html Wrapping',
							'cbxdynamicsidebar' ) ?></label></th>
                <td>
                    <input id="cbxdynamicsidebarmetabox_fields_after_title" class="regular-text" type="text"
                           name="cbxdynamicsidebarmetabox[after_title]"
                           placeholder="<?php echo htmlentities( $after_title ); ?>"
                           value="<?php echo htmlentities( $after_title ); ?>"/>
                </td>
            </tr>
            <!-- <tr valign="top">
                    <th scope="row"><label for="cbxdynamicsidebarmetabox_fields_user_roles"><?php /*echo esc_html__('Assign User Role', 'cbxdynamicsidebar') */
			?></label></th>
                    <td>
                        <a id="cbxdynamicsidebarmetabox_fields_user_roles_sa" href="#"><?php /*esc_html_e('Select All', 'cbxdynamicsidebar'); */
			?></a> | <a id="cbxdynamicsidebarmetabox_fields_user_roles_usa" href="#"><?php /*esc_html_e('Unselect All', 'cbxdynamicsidebar'); */
			?></a>
                        <select style="width: 100%;" multiple data-placeholder="<?php /*esc_html_e('Choose user roles', 'cbxdynamicsidebar'); */
			?>" id="cbxdynamicsidebarmetabox_fields_user_roles" class="select chosen-select" name="cbxdynamicsidebarmetabox[user_roles][]">
                            <?php
			/*                            foreach ($all_user_roles as $role_key => $role_name){
											echo '<option  value="'.$role_key.'" '.(in_array($role_key, $user_roles)? ' selected="selected" ':'').'>'.esc_attr($role_name).'</option>';
										}
										*/
			?>
                        </select>
                    </td>
                </tr>-->
            </tbody>
        </table>

		<?php

		echo '</div>';


	}//end method cbxdynamicsidebarmetabox_display

	/**
	 * Determines whether or not the current user has the ability to save meta data associated with this post.
	 *
	 * @param        int $post_id The ID of the post being save
	 * @param        bool                Whether or not the user has the ability to save this post.
	 */
	public function save_post( $post_id, $post ) {

		$post_type = 'cbxsidebar';

		// If this isn't a 'book' post, don't update it.
		if ( $post_type != $post->post_type ) {
			return;
		}


		if ( ! empty( $_POST['cbxdynamicsidebarmetabox'] ) ) {

			$postData = $_POST['cbxdynamicsidebarmetabox'];


			$saveableData = array();

			if ( $this->user_can_save( $post_id, 'cbxdynamicsidebarmetabox', $postData['nonce'] ) ) {

				$saveableData['active']        = intval( $postData['active'] );
				$saveableData['description']   = esc_attr( $postData['description'] );
				$saveableData['class']         = esc_attr( $postData['class'] );
				$saveableData['before_widget'] = esc_attr( $postData['before_widget'] );
				$saveableData['after_widget']  = esc_attr( $postData['after_widget'] );
				$saveableData['before_title']  = esc_attr( $postData['before_title'] );
				$saveableData['after_title']   = esc_attr( $postData['after_title'] );
				//$saveableData['user_roles'] 		    = maybe_serialize($postData['user_roles']);

				//write_log($postData['scales']);

				update_post_meta( $post_id, '_cbxdynamicsidebar', $saveableData );
			}
		}
	}

	/**
	 * Determines whether or not the current user has the ability to save meta data associated with this post.
	 *
	 * @param        int $post_id The ID of the post being save
	 * @param        bool                Whether or not the user has the ability to save this post.
	 */
	function user_can_save( $post_id, $action, $nonce ) {

		$is_autosave    = wp_is_post_autosave( $post_id );
		$is_revision    = wp_is_post_revision( $post_id );
		$is_valid_nonce = ( isset( $nonce ) && wp_verify_nonce( $nonce, $action ) );

		// Return true if the user is able to save; otherwise, false.
		return ! ( $is_autosave || $is_revision ) && $is_valid_nonce;

	}// end user_can_save

	public function remove_date_drop_sidebar() {
		$screen = get_current_screen();

		if ( 'cbxsidebar' == $screen->post_type ) {
			add_filter( 'months_dropdown_results', '__return_empty_array' );
		}
	}//end method remove_date_drop_sidebar

	/**
	 * Init all gutenberg blocks
	 */
	public function gutenberg_blocks() {

		$active_insta = CBXDynamicSidebarHelper::get_active_instaposts();


		$active_insta_posts = array();

		$active_insta_posts[] = array(
			'label' => esc_html__( 'Select CBX Sidebar Post', 'cbxdynamicsidebar' ),
			'value' => '0',
		);

		foreach ( $active_insta as $key => $label ) {
			$active_insta_posts[] = array(
				'label' => esc_html( $label ),
				'value' => intval( $key ),
			);
		}


		$floats = array(
			'none'  => esc_html__( 'None', 'cbxdynamicsidebar' ),
			'left'  => esc_html__( 'Left', 'cbxdynamicsidebar' ),
			'right' => esc_html__( 'Right', 'cbxdynamicsidebar' ),
		);

		$float_options = array();


		foreach ( $floats as $key => $value ) {
			$float_options[] = array(
				'label' => esc_html( $value ),
				'value' => esc_attr( $key ),
			);
		}


		wp_register_script( 'cbxdynamicsidebar-block',
			plugin_dir_url( __FILE__ ) . '../assets/js/cbxdynamicsidebar-block.js',
			array(
				'wp-blocks',
				'wp-element',
				'wp-components',
				'wp-editor',
				//'jquery',
				//'codeboxrflexiblecountdown-public'
			),
			filemtime( plugin_dir_path( __FILE__ ) . '../assets/js/cbxdynamicsidebar-block.js' ) );

		wp_register_style( 'cbxdynamicsidebar-block',
			plugin_dir_url( __FILE__ ) . '../assets/css/cbxdynamicsidebar-block.css',
			array(),
			filemtime( plugin_dir_path( __FILE__ ) . '../assets/css/cbxdynamicsidebar-block.css' ) );

		$js_vars = apply_filters( 'cbxdynamicsidebar_block_js_vars',
			array(
				'block_title'      => esc_html__( 'CBX Dynamic Sidebar', 'cbxdynamicsidebar' ),
				'block_category'   => 'codeboxr',
				'block_icon'       => 'welcome-widgets-menus',
				'general_settings' => array(
					'title'          => esc_html__( 'CBX Dynamic Sidebar Settings', 'cbxdynamicsidebar' ),
					'id'             => esc_html__( 'Select CBX Sidebar Post', 'cbxdynamicsidebar' ),
					'id_options'     => $active_insta_posts,
					'float_options'  => $float_options,
					'wclass'         => esc_html__( 'Html Class', 'cbxdynamicsidebar' ),
					'wclass_default' => 'cbxdynamicsidebar_wrapper',
					'wid'            => esc_html__( 'Html ID', 'cbxdynamicsidebar' ),
					'wid_default'    => 'cbxdynamicsidebar_wrapper',
					'float'          => esc_html__( 'Select Layout', 'cbxdynamicsidebar' ),
					'float_default'  => 'none',
				),
			) );

		wp_localize_script( 'cbxdynamicsidebar-block', 'cbxdynamicsidebar_block', $js_vars );

		register_block_type( 'codeboxr/cbxdynamicsidebar',
			array(
				'editor_script'   => 'cbxdynamicsidebar-block',
				'editor_style'    => 'cbxdynamicsidebar-block',
				'attributes'      => apply_filters( 'cbxdynamicsidebar_block_attributes',
					array(
						//general
						'id'     => array(
							'type'    => 'integer',
							'default' => '0',
						),
						'float'  => array(
							'type'    => 'string',
							'default' => 'none',
						),
						'wclass' => array(
							'type'    => 'string',
							'default' => 'cbxdynamicsidebar_wrapper',
						),
						'wid'    => array(
							'type'    => 'string',
							'default' => 'cbxdynamicsidebar_wrapper',
						),
					) ),
				'render_callback' => array( $this, 'cbxdynamicsidebar_block_render' ),
			) );

	}//end method gutenberg_blocks

	/**
	 * Getenberg server side render
	 *
	 * @param $settings
	 *
	 * @return string
	 */
	public function cbxdynamicsidebar_block_render( $attributes ) {
		$attr = array();

		$attr['id']     = $id = isset( $attributes['id'] ) ? intval( $attributes['id'] ) : 0;
		$attr['wclass'] = $wclass = isset( $attributes['wclass'] ) ? sanitize_text_field( $attributes['wclass'] ) : 'cbxdynamicsidebar_wrapper';
		$attr['wid']    = $wid = isset( $attributes['wid'] ) ? sanitize_text_field( $attributes['wid'] ) : 'cbxdynamicsidebar_wrapper';
		$attr['float']  = $float = isset( $attributes['float'] ) ? sanitize_text_field( $attributes['float'] ) : 'none';

		$attr = apply_filters( 'cbxdynamicsidebar_block_shortcode_builder_attr', $attr, $attributes );

		$attr_html = '';

		foreach ( $attr as $key => $value ) {
			$attr_html .= ' ' . $key . '="' . $value . '" ';
		}

		return do_shortcode( '[cbxdynamicsidebar ' . $attr_html . ']' );
		//return '[cbxdynamicsidebar ' . $attr_html . ']';
	}//end method cbxdynamicsidebar_block_render

	/**
	 * Register New Gutenberg block Category if need
	 *
	 * @param $categories
	 * @param $post
	 *
	 * @return mixed
	 */
	public function gutenberg_block_categories( $categories, $post ) {
		$found = false;

		foreach ( $categories as $category ) {
			if ( $category['slug'] == 'codeboxr' ) {
				$found = true;
				break;
			}
		}

		if ( ! $found ) {
			return array_merge(
				$categories,
				array(
					array(
						'slug'  => 'codeboxr',
						'title' => esc_html__( 'CBX Blocks', 'cbxdynamicsidebar' ),
					),
				)
			);
		}

		return $categories;
	}//end method gutenberg_block_categories


	/**
	 * Enqueue style for block editor
	 */
	public function enqueue_block_editor_assets() {

	}//end method enqueue_block_editor_assets
}//end class CBXDynamicSidebar_Admin
