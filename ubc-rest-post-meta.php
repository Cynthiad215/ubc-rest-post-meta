<?php 
/**
 *
 * @wordpress-plugin
 * Plugin Name:       UBC Post Meta Data (Custom Fields)
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
        $base = 'fields/(?P<id>\d+)(?:/(?P<fieldkey>[\w]+[a-zA-Z0-9\-\_]*))?';
        
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
	 * @return JSON object with all custom fields if no field key specified, or single custom field value for field key specified
	 */
	public function get_post_cf( $object ) {
        //Check if post ID exists
        if ( empty( $object['id'] ) ) {
            return false;
        }

        $post_id = absint( $object['id'] );

        if ( ! $post_id ) {
            return false;
        }

        //If field key specified, get one custom field value
        if ( isset( $object['fieldkey'] ) &&  ! empty( $object['fieldkey'] ) ) {

            $field_key = sanitize_title( $object['fieldkey'] );

            if ( ! $field_key ) {
                return false;
            }

            $value = get_post_meta( $post_id, $field_key );

        } else {
            
            //No field key specified, get all public data
            $custom_field = get_post_meta( $post_id, '', false );
            
            $hidden = '_';

            foreach ( $custom_field as $key => $value ) {

                $pos = strpos( $key, $hidden );

                //check if private metadata
                if ( $pos === 0 ) {
                    unset( $custom_field[ $key ] );
                }
                
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