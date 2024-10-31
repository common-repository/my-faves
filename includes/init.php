<?php 
/*
* Creating a function to create our CPT
*/
 
function myfaves_post_type() {
 
// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'My Faves', 'My Favorites', 'myfaves' ),
        'singular_name'       => _x( 'My Fave', 'My Favorite', 'myfaves' ),
        'menu_name'           => __( 'My Faves', 'myfaves' ),
        'parent_item_colon'   => __( 'Fave', 'myfaves' ),
        'all_items'           => __( 'All Faves', 'myfaves' ),
        'view_item'           => __( 'View My Faves', 'myfaves' ),
        'add_new_item'        => __( 'Add New Fave', 'myfaves' ),
        'add_new'             => __( 'Add New', 'myfaves' ),
        'edit_item'           => __( 'Edit Fave', 'myfaves' ),
        'update_item'         => __( 'Update Fave', 'myfaves' ),
        'search_items'        => __( 'Search Fave', 'myfaves' ),
        'not_found'           => __( 'Not Found', 'myfaves' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'myfaves' ),
    );
     
// Set other options for Custom Post Type
     
    $args = array(
        'label'               => __( 'myfaves', 'myfaves' ),
        'description'         => __( 'My favorite posts', 'myfaves' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'revisions', ),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        'taxonomies'          => array( 'myfaves_tags' ),
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'hierarchical'        => false,
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => false,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'menu_icon' => 'dashicons-heart',
    );
     
    // Registering your Custom Post Type
    register_post_type( 'myfaves', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action( 'init', 'myfaves_post_type', 0 );
/* Fire our meta box setup function on the post editor screen. */
add_action( 'load-post.php', 'myfaves_post_meta_boxes_setup' );
add_action( 'load-post-new.php', 'myfaves_post_meta_boxes_setup' );
/* Meta box setup function. */
function myfaves_post_meta_boxes_setup() {

  /* Add meta boxes on the 'add_meta_boxes' hook. */
  add_action( 'add_meta_boxes', 'myfaves_add_post_meta_boxes' );
  /* Save post meta on the 'save_post' hook. */
  add_action( 'save_post', 'myfaves_save_link_meta', 10, 2 );
}
/* Create one or more meta boxes to be displayed on the post editor screen. */
function myfaves_add_post_meta_boxes() {

  add_meta_box(
    'myfaves_link',      // Unique ID
    esc_html__( 'Fave Link', '' ),    // Title
    'myfaves_link_meta_box',   // Callback function
    'myfaves',         // Admin page (or post type)
    'normal',         // Context
    'high'         // Priority
  );
}
/* Display the post meta box. */
function myfaves_link_meta_box( $post ) { ?>

  <?php wp_nonce_field( basename( __FILE__ ), 'myfaves_link_nonce' ); ?>

  <p>
    <label for="myfaves_link"><?php _e( "Your saved link" ); ?></label>
    <br />
    <input class="widefat" type="url" name="myfaves-link" id="myfaves-link" value="<?php echo  get_post_meta( $post->ID, 'myfaves_link', true  ); ?>" maxlength="2000"  />
  </p>
  <p>
    <label for="myfaves_postid"><?php _e( "Your saved Post ID" ); ?></label>
    <br />
    <input type="number" name="myfaves-postid" id="myfaves-postid" value="<?php echo  get_post_meta( $post->ID, 'myfaves_postid', true  ); ?>" min="1" max="9999999" width="auto"  />
  </p>
<?php }


/* Save the meta box's post metadata. */
function myfaves_save_link_meta( $post_id, $post ) {

  /* Verify the nonce before proceeding. */
  if ( !isset( $_POST['myfaves_link_nonce'] ) || ! wp_verify_nonce( $_POST['myfaves_link_nonce'], basename( __FILE__ ) ) )
    return $post_id;

  /* Get the post type object. */
  $post_type = get_post_type_object( $post->post_type );

  /* Check if the current user has permission to edit the post. */
  if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
    return $post_id;

  /* Get the posted data and sanitize it for use as an HTML class. */
  $new_meta_value = ( isset( $_POST['myfaves-link'] ) ?  esc_url_raw( trim ($_POST['myfaves-link'])) : ’ );
  $new_meta_value2 = ( isset( $_POST['myfaves-postid'] ) ?  trim ($_POST['myfaves-postid']) : ’ );

  /* Get the meta key. */
  $meta_key = 'myfaves_link';
  $meta_key2 = 'myfaves_postid';

  /* Get the meta value of the custom field key. */
  $meta_value = get_post_meta( $post_id, $meta_key, true );
  $meta_value2 = get_post_meta( $post_id, $meta_key2, true );

  /* If a new meta value was added and there was no previous value, add it. */
  if ( $new_meta_value && ’ == $meta_value )
    add_post_meta( $post_id, $meta_key, $new_meta_value, true );

  /* If the new meta value does not match the old value, update it. */
  elseif ( $new_meta_value && $new_meta_value != $meta_value )
    update_post_meta( $post_id, $meta_key, $new_meta_value );

  /* If there is no new meta value but an old value exists, delete it. */
  elseif ( ’ == $new_meta_value && $meta_value )
    delete_post_meta( $post_id, $meta_key, $meta_value );


  if ( $new_meta_value2 && ’ == $meta_value2 )
    add_post_meta( $post_id, $meta_key2, $new_meta_value2, true );

  /* If the new meta value does not match the old value, update it. */
  elseif ( $new_meta_value2 && $new_meta_value2 != $meta_value2 )
    update_post_meta( $post_id, $meta_key2, $new_meta_value2 );

  /* If there is no new meta value but an old value exists, delete it. */
  elseif ( ’ == $new_meta_value2 && $meta_value2 )
    delete_post_meta( $post_id, $meta_key2, $meta_value2 );
}


/* Filter the post class hook with our custom post class function. */
add_filter( 'post_class', 'myfaves_link' );

function myfaves_link( $classes ) {

  /* Get the current post ID. */
  $post_id = get_the_ID();

  /* If we have a post ID, proceed. */
  if ( !empty( $post_id ) ) {

    /* Get the custom post class. */
    $post_class = get_post_meta( $post_id, 'myfaves_link', true );


    /* If a post class was input, sanitize it and add it to the post class array. */
    if ( !empty( $post_class ) )
      $classes[] =  $post_class ;
  }

  return $classes;
}

function myfaves_reg_tag() {
     register_taxonomy('myfaves_tags', 'myfaves', ['show_admin_column'=>true]);
}
add_action('init', 'myfaves_reg_tag');


