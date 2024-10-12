<?php

class CertificateGeneratorController extends WP_REST_Controller
{
    /**
     * Register the routes for the objects of the controller.
     */
    public function register_routes()
    {
        $version = '1';
        $namespace = 'cert/v' . $version;
        $base = 'certificates';
        register_rest_route($namespace, '/' . $base, array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_certficates'),
                'permission_callback' => array($this, 'get_certficates_permissions_check'),
                'args' => array(

                ),
            ),
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'create_certficate'),
                'permission_callback' => array($this, 'create_certficate_permissions_check'),
                 'args' => $this->get_endpoint_args_for_certficate_schema(),
            ),
        ));

        register_rest_route($namespace, '/' . $base . '/(?P<id>[\d]+)', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_certficate'),
                'permission_callback' => array($this, 'get_certficate_permissions_check'),
                'args' => array(
                    'context' => array(
                        'default' => 'view',
                    ),
                ),
            ),
            array(
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => array($this, 'update_certficate'),
                'permission_callback' => array($this, 'update_certficate_permissions_check'),
                'args' => $this->get_endpoint_args_for_certficate_schema(),
            ),
            array(
                'methods' => WP_REST_Server::DELETABLE,
                'callback' => array($this, 'delete_certficate'),
                'permission_callback' => array($this, 'delete_certficate_permissions_check'),
                'args' => array(
                    'force' => array(
                        'default' => false,
                    ),
                ),
            ),
        ));
        register_rest_route($namespace, '/' . $base . '/schema', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'get_public_certficate_schema'),
        ));
    }

    /**
     * Get a collection of certficates
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function get_certficates($request)
    {
        $certficates = array(); //do a query, call another class, etc
        $data = array();
        foreach ($certficates as $certficate) {
            $certficatedata = $this->prepare_certficate_for_response($certficate, $request);
            $data[] = $this->prepare_response_for_collection($certficatedata);
        }

        return new WP_REST_Response($data, 200);
    }

    /**
     * Get the argument schema for this example endpoint.
     */
    public function get_endpoint_args_for_certficate_schema()
    {
        $args = array();

        // Here we add our PHP representation of JSON Schema.
        $args['owner_name'] = array(
            'description' => esc_html__('This is the argument our endpoint returns.', 'my-textdomain'),
            'type' => 'string',
            'validate_callback' => 'prefix_validate_my_arg',
           'sanitize_callback' => 'prefix_sanitize_my_arg',
            'required' => true,
        );

        $args['owner_email'] = array(
            'description' => esc_html__('This is the argument our endpoint returns.', 'my-textdomain'),
            'type' => 'string',
            'validate_callback' => 'prefix_validate_my_arg',
           'sanitize_callback' => 'prefix_sanitize_my_arg',
            'required' => true,
        );

        $args['certificate_type'] = array(
            'description' => esc_html__('This is the argument our endpoint returns.', 'my-textdomain'),
            'type' => 'string',
            'validate_callback' => 'prefix_validate_my_arg',
            'sanitize_callback' => 'prefix_sanitize_my_arg',
            'required' => true,
        );

        $args['batch_id'] = array(
            'description' => esc_html__('This is the argument our endpoint returns.', 'my-textdomain'),
            'type' => 'string',
            'validate_callback' => 'prefix_validate_my_arg',
            'sanitize_callback' => 'prefix_sanitize_my_arg',
            'required' => true,
        );

        return $args;
    }

    public function prefix_validate_my_arg($value, $request, $param)
    {
        $attributes = $request->get_attributes();

        if (isset($attributes['args'][$param])) {
            $argument = $attributes['args'][$param];
            // Check to make sure our argument is a string.
            if ('string' === $argument['type'] && !is_string($value)) {
                return new WP_Error('rest_invalid_param', sprintf(esc_html__('%1$s is not of type %2$s', 'my-textdomain'), $param, 'string'), array('status' => 400));
            }
        } else {
            // This code won't execute because we have specified this argument as required.
            // If we reused this validation callback and did not have required args then this would fire.
            return new WP_Error('rest_invalid_param', sprintf(esc_html__('%s was not registered as a request argument.', 'my-textdomain'), $param), array('status' => 400));
        }

        // If we got this far then the data is valid.
        return true;
    }

    public function prefix_sanitize_my_arg($value, $request, $param)
    {
        $attributes = $request->get_attributes();

        if (isset($attributes['args'][$param])) {
            $argument = $attributes['args'][$param];
            // Check to make sure our argument is a string.
            if ('string' === $argument['type']) {
                return sanitize_text_field($value);
            }else{
                return new WP_Error('rest_invalid_param', sprintf(esc_html__('%s was not registered as a request argument.', 'my-textdomain'), $param), array('status' => 400));
            }
        } else {
            // This code won't execute because we have specified this argument as required.
            // If we reused this validation callback and did not have required args then this would fire.
          
        }

        // If we got this far then something went wrong don't use user input.
        return new WP_Error('rest_api_sad', esc_html__('Something went terribly wrong.', 'my-textdomain'), array('status' => 500));
    }

    /**
     * Get one certficate from the collection
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function get_certficate($request)
    {
        //get parameters from request
        $params = $request->get_params();
        $certficate = array(); //do a query, call another class, etc
        $data = $this->prepare_certficate_for_response($certficate, $request);

        //return a response or error based on some conditional
        if (1 == 1) {
            return new WP_REST_Response($data, 200);
        } else {
            return new WP_Error('code', __('message', 'text-domain'));
        }
    }

    /**
     * Create one certficate from the collection
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function create_certficate($request)
    {
        $certficate = $this->prepare_certficate_for_database($request);

        if (function_exists('slug_some_function_to_create_certficate')) {
            $data = slug_some_function_to_create_certficate($certficate);
            if (is_array($data)) {
                return new WP_REST_Response($data, 200);
            }
        }

        return new WP_Error('cant-create', __('message', 'text-domain'), array('status' => 500));
    }

    /**
     * Update one certficate from the collection
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function update_certficate($request)
    {
           $certficate = $this->prepare_certficate_for_database($request);

       
            $data = $this->slug_some_function_to_update_certficate($certficate);
            if (1 == 1) {
                $res = array('message' => "certificates updated successfully", 'data' => $certficate , 'success' => true);
                // Create the response object
                $response = new WP_REST_Response($res);
                return $response;
            }
     
        return  new WP_Error('cant-update', __('message', 'text-domain'), array('status' => 500));
    }

    public function slug_some_function_to_update_certficate($request)
    {
        global $wpdb;
        
        return $wpdb->update( $wpdb->prefix.'ma_certificates', $request , array( 'batch_id' => $request["batch_id"] ), array( '%s'), array( '%d' ) );
    }

    /**
     * Delete one certficate from the collection
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function delete_certficate($request)
    {
        //$certficate = $this->prepare_certficate_for_database($request);

        if (function_exists('slug_some_function_to_delete_certficate')) {
            $deleted = slug_some_function_to_delete_certficate($certficate);
            if ($deleted) {
                return new WP_REST_Response(true, 200);
            }
        }

        return new WP_Error('cant-delete', __('message', 'text-domain'), array('status' => 500));
    }
    /**
     * Check if a given request has access to get certficates
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function get_certficates_permissions_check($request)
    {
        //return true; <--use to make readable by all
        return true; //current_user_can( 'edit_something' );
    }

    /**
     * Check if a given request has access to get a specific certficate
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function get_certficate_permissions_check($request)
    {
        return $this->get_certficates_permissions_check($request);
    }

    /**
     * Check if a given request has access to create certficates
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function create_certficate_permissions_check($request)
    {
        return true; //current_user_can('edit_something');
    }

    /**
     * Check if a given request has access to update a specific certficate
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function update_certficate_permissions_check($request)
    {
        return $this->create_certficate_permissions_check($request);
    }

    /**
     * Check if a given request has access to delete a specific certficate
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function delete_certficate_permissions_check($request)
    {
        return $this->create_certficate_permissions_check($request);
    }

    /**
     * Prepare the certficate for create or update operation
     *
     * @param WP_REST_Request $request Request object
     * @return WP_Error|object $prepared_certficate
     */
    protected function prepare_certficate_for_database($request)
    {
        
        return array(
            'owner_name' => $_REQUEST['owner_name'] ,
            'owner_email' =>  $_REQUEST['owner_email'],
            'certificate_type' => $_REQUEST['certificate_type'],
            'issue_date' => $_REQUEST['issue_date'],
           // 'certificate_code' => $code,
            'batch_id' => $_REQUEST['batch_id'], 
        );
    }

    /**
     * Prepare the certficate for the REST response
     *
     * @param mixed $certficate WordPress representation of the certficate.
     * @param WP_REST_Request $request Request object.
     * @return mixed
     */
    public function prepare_certficate_for_response($certficate, $request)
    {
        global $wpdb;
        $table_name = $wpdb->prefix.'ma_certificates';
        return $certficates = $wpdb->get_results($wpdb->prepare(
            "
                SELECT *
                FROM $table_name
                WHERE batch_id = %d
            ",
            $request["id"]
        ),
            ARRAY_A
        );
    }

    /**
     * Get the query params for collections
     *
     * @return array
     */
    public function get_collection_params()
    {
        return array(
            'page' => array(
                'description' => 'Current page of the collection.',
                'type' => 'integer',
                'default' => 1,
                'sanitize_callback' => 'absint',
            ),
            'per_page' => array(
                'description' => 'Maximum number of certficates to be returned in result set.',
                'type' => 'integer',
                'default' => 10,
                'sanitize_callback' => 'absint',
            ),
            'search' => array(
                'description' => 'Limit results to those matching a string.',
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
            ),
        );
    }
}
