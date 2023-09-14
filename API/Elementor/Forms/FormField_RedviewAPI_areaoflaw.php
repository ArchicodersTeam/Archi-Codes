<?php
function add_new_area_of_laws_select_field($form_fields_registrar)
{

    class Elementor_Area_Of_Laws_Field extends \ElementorPro\Modules\Forms\Fields\Field_Base
    {

        /**
         * Get field type.
         *
         * Retrieve area of laws field unique ID.
         *
         * @since 1.0.0
         * @access public
         * @return string Field type.
         */
        public function get_type()
        {
            return 'area_of_laws';
        }

        /**
         * Get field name.
         *
         * Retrieve area of laws field label.
         *
         * @since 1.0.0
         * @access public
         * @return string Field name.
         */
        public function get_name()
        {
            return esc_html__('Area Of Laws', 'elementor-form-area_of_laws-field');
        }

        /**
         * Render field output on the frontend.
         *
         * Written in PHP and used to generate the final HTML.
         *
         * @since 1.0.0
         * @access public
         * @param mixed $item
         * @param mixed $item_index
         * @param mixed $form
         * @return void
         */
        private function get_area_of_laws()
        {
            $clientId = "3F6C5EC4-F5DF-44DC-B541-3878C737BE67"; // Replace with your actual ClientId

            // Get the access token
            $accessToken = $this->get_bearerToken();

            if (empty($accessToken)) {
                // Handle the error from the token request
                return new WP_Error('token_error', 'Error getting access token', array('status' => 500));
            }

            // API endpoint URL
            $apiUrl = "https://api.redraincorp.com/external/api/lookup/areaoflaw";

            // Create the cURL session
            $ch = curl_init();

            // Set the cURL options
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "ClientId: $clientId",
                "Authorization: Bearer $accessToken",
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Execute the cURL session and get the response
            $response = curl_exec($ch);

            // Check for cURL errors
            if (curl_errno($ch)) {
                return new WP_Error('curl_error', 'Curl error: ' . curl_error($ch), array('status' => 500));
            }

            // Close the cURL session
            curl_close($ch);

            // Return the API response as JSON
            return json_decode($response);
        }

        private function get_bearerToken()
        {
            $url = 'https://api.redraincorp.com/external/api/authenticate/login';

            $headers = array(
                'accept' => 'text/plain',
                'ClientId' => '3F6C5EC4-F5DF-44DC-B541-3878C737BE67',
                'Content-Type' => 'application/json-patch+json',
            );

            $body = json_encode(array(
                'clientSecret' => '2C2B9C04-EB4A-4C13-8D8A-EE35B557C668',
            ));

            $args = array(
                'body' => $body,
                'headers' => $headers,
                'method' => 'POST',
            );

            $response = wp_remote_request($url, $args);

            if (is_wp_error($response)) {
                // Handle error
                return ''; // Return an empty string if there's an error
            } else {
                // Request was successful
                $response_body = wp_remote_retrieve_body($response);
                $response_data = json_decode($response_body);

                if ($response_data !== null) {
                    // Access the access token field
                    $accessToken = $response_data->data->accessToken;

                    return $accessToken;
                } else {
                    // JSON decoding error
                    return ''; // Return an empty string if there's a decoding error
                }
            }
        }

        public function render($item, $item_index, $form)
        {
            $form_id = $form->get_id();

            $form->add_render_attribute(
                'input' . $item_index,
                [
                    'class' => 'elementor-field-textual',
                    'for' => $form_id . $item_index,
                ]
            );
            $atts = $form->get_render_attribute_string('input' . $item_index);
            $api_response = $this->get_area_of_laws();
            $options = array_map(function ($data) {
                $desc = $data->description;
                return "<option value='$desc'>$desc</option>";
            }, $api_response->data);

            $options_html = join("", $options);
            echo
            "<div class='elementor-field elementor-select-wrapper remove-before '>
                <div class='select-caret-down-wrapper'>
                    <i aria-hidden='true' class='eicon-caret-down'></i>
                </div>
                <select $atts >
                    $options_html
                </select>
            </div>";
        }

        /**
         * Field validation.
         *
         * Validate area of laws field value to ensure it complies to certain rules.
         *
         * @since 1.0.0
         * @access public
         * @param \ElementorPro\Modules\Forms\Classes\Field_Base   $field
         * @param \ElementorPro\Modules\Forms\Classes\Form_Record  $record
         * @param \ElementorPro\Modules\Forms\Classes\Ajax_Handler $ajax_handler
         * @return void
         */
        public function validation($field, $record, $ajax_handler)
        {
            if (empty($field['value'])) {
                return;
            }
        }

        /**
         * Update form widget controls.
         *
         * Add input fields to allow the user to customize the area of laws field.
         *
         * @since 1.0.0
         * @access public
         * @param \Elementor\Widget_Base $widget The form widget instance.
         * @return void
         */
        public function update_controls($widget)
        {
            $elementor = \ElementorPro\Plugin::elementor();

            $control_data = $elementor->controls_manager->get_control_from_stack($widget->get_unique_name(), 'form_fields');

            if (is_wp_error($control_data)) {
                return;
            }

            $widget->update_control('form_fields', $control_data);
        }

        /**
         * Field constructor.
         *
         * Used to add a script to the Elementor editor preview.
         *
         * @since 1.0.0
         * @access public
         * @return void
         */
        public function __construct()
        {
            parent::__construct();
            add_action('elementor/preview/init', [$this, 'editor_preview_footer']);
        }

        /**
         * Elementor editor preview.
         *
         * Add a script to the footer of the editor preview screen.
         *
         * @since 1.0.0
         * @access public
         * @return void
         */
        public function editor_preview_footer()
        {
            add_action('wp_footer', [$this, 'content_template_script']);
        }

        /**
         * Content template script.
         *
         * Add content template alternative, to display the field in Elemntor editor.
         *
         * @since 1.0.0
         * @access public
         * @return void
         */
        public function content_template_script()
        {
?>
            <!-- 			<script>
            jQuery( document ).ready( () => {
    
                elementor.hooks.addFilter(
                    'elementor_pro/forms/content_template/field/<?php echo $this->get_type(); ?>',
                    function ( inputField, item, i ) {
                        const fieldId      = `form_field_${i}`;
                        const fieldClass   = `elementor-field-textual elementor-field ${item.css_classes}`;
    
                        return
                        `<div class='elementor-field elementor-select-wrapper remove-before '>
                            <div class='select-caret-down-wrapper'>
                                <i aria-hidden='true' class='eicon-caret-down'></i>
                            </div>
                            <select class="${fieldClass}" id="${fieldId}">
                                <option value='' selected>Placeholder Text</option>
                            </select>
                        </div>`
                    }, 10, 3
                );
    
            });
            </script> -->
<?php
        }
    }

    $form_fields_registrar->register(new \Elementor_Area_Of_Laws_Field());
}
add_action('elementor_pro/forms/fields/register', 'add_new_area_of_laws_select_field');
