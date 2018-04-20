<?php 
/**
 *
 * @wordpress-plugin
 * Plugin Name:       REST API - Custom Endpoint for Post Meta
 * Description:       Adding Custom Endpoint to retrieve custom field values from post meta data
 * Version:           1.0.0
 * Author:            Cynthia Deng 
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ubc-rest
*/

class UBC_WP_REST_API_meta extends WP_REST_Controller {

	/**
	 * Add our hooks
	 *
	 * @since 1.0.0
	 *
	 * @param null
	 * @return null
	 */
	public function register_routes() {
		$version = '1';
		$namespace = 'postmeta/v' . $version;
        $base = 'fields/(?P<id>\d+)(?:/(?P<fieldkey>[a-zA-Z0-9-]+))?';
        
		register_rest_route( $namespace, '/' . $base, array(
				'methods'         => WP_REST_Server::READABLE,
                'callback'        => array( $this, 'get_post_cf' ),
                'args'            => array( 'id', 'fieldkey' ),
		) );
	}

	/**
	 * Get post custom fields
	 *
	 * @param WP_REST_Request $object the post object
	 * @return JSON object with all custom fields if no field key specified, or single custom field value 
     *              for field key specified
	 */
	public function get_post_cf( $object ) {
        //Check if post ID exists
        if( empty( $object['id'] ) ) {
            return false;
        }

        $post_id = absint( $object['id'] );

        if( ! $post_id ) {
            return false;
        }

        //If field key specified, get one custom field value
        if( isset( $field_key ) && (! empty( $field_key ) ) ) {

            $field_key = sanitize_title( $object['fieldkey'] );

            if ( ! $fieldkey ) {
                return false;
            }

            $value = get_post_meta( $post_id, $field_key );

        } else {
            
            //No field key specified, get all public data
            $custom_field = get_post_meta( $post_id );

            $hidden = '_';

            foreach ( $custom_field as $key => $value ){

                if ( empty( $value ) ) {
                    continue;
                }

                $pos = strpos( $key, $hidden );

                if ( false === $pos ) {
                    continue;
                }

                if ( 0 !== $pos ) {
                    continue;
                }

                unset( $custom_field[ $key ] );
                
            }

            $value = $custom_field;
        }

        return apply_filters( 'ubc_cm_rest_postmeta_value', $value, $object );
	}

}

add_action( 'plugins_loaded', 'init_WP_API_meta' );
function init_WP_API_meta() {
    $wp_api_meta = new UBC_WP_REST_API_meta();
    $wp_api_meta->register_routes();
}/* init_WP_API_meta() */