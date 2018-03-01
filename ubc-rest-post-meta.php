<?php
/**
 *
 * @wordpress-plugin
 * Plugin Name:       WP REST API - Post Meta   
 * Description:       Modifying REST API fields to get post meta (ie. custom fields)
 * Version:           1.0.0
 * Author:            Cynthia Deng 
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ubc-rest
*/

/** Add action to initialize */
add_action( 'rest_api_init', 'create_posts_meta_field' );
 
/**
  * Getting post meta for the REST API
  *
  * 
  * @param null
  * @return null
 */
function create_posts_meta_field() {
    register_rest_field('post', 'post-meta', array(
          'get_callback'    => 'get_posts_meta_data',
          'schema'          => null,
      )
    );
}
 
/**
* Get value of post
*
* @param array $object Details of current post
* @return array Post meta for given post
*/
function get_posts_meta_data( $object ) {
    //Get post ID
    $post_id = $object['id'];
  
   //return post custom meta
    return get_post_custom( $post_id );
}
  
?>