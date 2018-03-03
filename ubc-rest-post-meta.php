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

class WP_REST_API_meta {

    public function init() {
        $this->add_actions();
    }

    /**
	 * Add our hooks
	 *
	 * @since 1.0.0
	 *
	 * @param null
	 * @return null
	 */
	public function add_actions() {
        add_action( 'init', array( $this, 'create_posts_meta_field' ) );
    }

    /**
     * Getting post meta for the REST API
     *
     * @param null
     * @return null
     */
    public function create_posts_meta_field() {
        register_rest_field( 'post', 'post_meta', array(
                'get_callback'    => array( $this, 'get_posts_meta_data' ),
                'update_callback' => null,
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
    public function get_posts_meta_data( $object ) {
        //Get post ID
        $post_id = $object['id'];
    
        //return post custom meta
        return get_post_custom( $post_id );
    }
}

add_action( 'plugins_loaded', 'init_WP_API_meta' );
function init_WP_API_meta() {
	$wp_api_meta = new WP_REST_API_meta();
	$wp_api_meta->init();
}/* init_WP_API_meta() */