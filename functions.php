

function send_course_user_to_verifyed( $data = array() ) {
	// Retrieve necessary information about the user and course
	$user = $data['user'];
	$course_id = $data['course']->ID;
	$name = $user->first_name . ' ' . $user.last_name;
	// Map WordPress course IDs to Verifyed course and template IDs
	if ($course_id == <insert course id>) {   // replace with learndash course id
		$verifyed_template_id = <insert template id>;   // replace with verifyed template id
		$verifyed_course_id = <insert course id>;   // replace with verifyed course id
	}  // repeat as needed for multiple courses
	else { 
        $slack_response = wp_remote_post(
			'https://hooks.slack.com/services/'. SLACK_APPLICATION_ERROR_ID,
			array(
				'method' => 'POST',
				'headers' => array(
					'Content-Type' => 'application/json',
				),
				'body' => json_encode(
					array(
						'text' => 'Failed to match Learndash course ID to VerifyEd course ID. You likely need to map a new course in your functions.php Wordpress file.' 
					)
				),
			)
		);
        exit(); 
    }
	// Prepare the payload for the webhook
	$payload = array(
	'templateId' => $verifyed_template_id,
	'courseId' => $verifyed_course_id,
	'leaningPathwayId'=>NULL,
	'public' => true,
	'studentData'=> [array(
		"email"=> $user->user_email,
		"name"=> $name,
		"outcome"=> "learner-outcome",
		"completionDate"=> date( 'c' ),
		"additionalInfo"=> [] 
		)]
	// Include any other relevant data you want to send
	);
	// Set VERIFYED_API_KEY in your wp-config.php file
	$webhook_url = 'https://api.verifyed.io/external/issue-credentials?apiKey='. VERIFYED_API_KEY .'&type=institution';
	// Send the webhook request
	$response = wp_remote_post( $webhook_url, array(
		'method' => 'POST',
		'headers' => array(
			'Content-Type' => 'application/json',
		),
		'body' => wp_json_encode( $payload ),
		'timeout' => 10
	) );
	// Check if the webhook request was successful
	if ( is_wp_error( $response ) ) {
		error_log( 'Webhook request failed: ' . $response->get_error_message() . '. Webhook sent to: ' . $webhook_url );
		$slack_response = wp_remote_post(
			'https://hooks.slack.com/services/'. SLACK_APPLICATION_ERROR_ID,
			array(
				'method' => 'POST',
				'headers' => array(
					'Content-Type' => 'application/json',
				),
				'body' => json_encode(
					array(
						'text' => 'Course completion webhook request failed for ' . $name .' on course ' . $data['course']->post_title . '. Endpoint: ' . $webhook_url . '. Body: ' . wp_json_encode($payload) 
					)
				),
			)
		);
	} else {
	// Message to Slack
	$slack_response = wp_remote_post(
		'https://hooks.slack.com/services/'. SLACK_APPLICATION_NOTIFICATION_ID,
		array(
			'method' => 'POST',
			'headers' => array(
				'Content-Type' => 'application/json',
			),
			'body' => json_encode(
				array(
					'text' => 'Course completion webhook sent to Verifyed for user ' . $name,
				)
			),
		)
	);
	}
}

// Hook the custom function to the learndash_course_completed hook
add_action( 'learndash_course_completed', 'send_course_user_to_verifyed', 10, 5 );