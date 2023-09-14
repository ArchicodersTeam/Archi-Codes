<?php

// Documentation: https://api.redraincorp.com/external/swagger/index.html

function add_new_redview_action($form_actions_registrar)
{

    class Redview_Action_After_Submit extends \ElementorPro\Modules\Forms\Classes\Action_Base
    {

        /**
         * Get action name.
         *
         * Retrieve redview action name.
         *
         * @since 1.0.0
         * @access public
         * @return string
         */
        public function get_name()
        {
            return 'redview';
        }

        /**
         * Get action label.
         *
         * Retrieve redview action label.
         *
         * @since 1.0.0
         * @access public
         * @return string
         */
        public function get_label()
        {
            return esc_html__('Redview', 'elementor-forms-redview-action');
        }

        /**
         * Run action.
         *
         * Redview an external server after form submission.
         *
         * @since 1.0.0
         * @access public
         * @param \ElementorPro\Modules\Forms\Classes\Form_Record  $record
         * @param \ElementorPro\Modules\Forms\Classes\Ajax_Handler $ajax_handler
         */
        public function run($record, $ajax_handler)
        {
            // Get the form fields from the $record
            $settings = $record->get('form_settings');
            $accessToken = get_bearerToken();
            $form_fields = $record->get('fields');
            $dateTime = new DateTime();
            $formattedDate = $dateTime->format('Y-m-d\TH:i:s.u\Z');


            if (empty($settings['redview_clientId'])) {
                return;
            }
            if (empty($settings['redview_apiEndpoint'])) {
                return;
            }

            if (!$accessToken) {
                return;
            }

            // Prepare data to send to the API
            $data_to_send = [
                'firstName' => $form_fields['first_name']['value'],
                'lastName' => $form_fields['last_name']['value'],
                'initialEnquiryDate' => $formattedDate,
                'gender' => 'U',
                'email' => $form_fields['email']['value'],
                'phone' => $form_fields['telephone']['value'],
                'source' => 'Website',
                'sourceNotes' => $form_fields['hear_about_us']['value'],
                'areaOfLaw' => $form_fields['areaOfLaw']['value'],
                'natureOfEnquiry' => $form_fields['message']['value'],
            ];

            // Set the API endpoint URL
            $api_url = $settings['redview_apiEndpoint'];

            // Prepare the request arguments

            $args = [
                'method' => 'POST',
                'body' => json_encode($data_to_send),
                'headers' => [
                    'Content-Type' => 'application/json',
                    'ClientId' => $settings['redview_clientId'],
                    'Authorization' => "Bearer $accessToken"
                ],
                'timeout' => 60,
            ];

            // Send the POST request using wp_remote_post
            $response = wp_remote_post($api_url, $args);

            // Check for errors and handle the API response
            if (is_wp_error($response)) {
                // Handle the error
                $error_message = $response->get_error_message();

                // Set an error message in the form record
                $ajax_handler->add_error_message($error_message);
            } else {
                // The API request was successful
                $response_code = wp_remote_retrieve_response_code($response);
                $response_body = wp_remote_retrieve_body($response);


                if ($response_code >= 400) {
                    $ajax_handler->add_error_message("Redview API ERROR $response_code");
                }
            }
        }


        /**
         * Register action controls.
         *
         * Redview action has no input fields to the form widget.
         *
         * @since 1.0.0
         * @access public
         * @param \Elementor\Widget_Base $widget
         */
        public function register_settings_section($widget)
        {

            $widget->start_controls_section(
                'custom_action_section',
                [
                    'label' => esc_html__('Redview API', 'elementor-forms-redview-action'),
                    'condition' => [
                        'submit_actions' => $this->get_name(),
                    ],
                ]
            );

            $widget->add_control(
                'redview_apiEndpoint',
                [
                    'label' => esc_html__('API Endpoint', 'elementor-forms-redview-action'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => 'https://api.redraincorp.com/external/api',
                ]
            );

            $widget->add_control(
                'redview_clientId',
                [
                    'label' => esc_html__('Client ID', 'elementor-forms-redview-action'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'description' => esc_html__('Client Id provided by RedView.', 'elementor-forms-redview-action'),
                ]
            );

            $widget->end_controls_section();
        }

        /**
         * On export.
         *
         * Redview action has no fields to clear when exporting.
         *
         * @since 1.0.0
         * @access public
         * @param array $element
         */
        public function on_export($element)
        {
            unset(
                $element['redview_clientId'],
                $element['redview_apiEndpoint'],
            );

            return $element;
        }
    }

    $form_actions_registrar->register(new \Redview_Action_After_Submit());
}
add_action('elementor_pro/forms/actions/register', 'add_new_redview_action');
