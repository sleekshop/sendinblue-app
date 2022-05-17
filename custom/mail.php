<?php
if($_POST) {

    $subscriber_email = $_POST['email'];


	$array = array();

    if( $subscriber_email == "" ) {

        $array["valid"] = 0;
        $array["message"] = $varErrorEmpty;

    } else {

        if( !filter_var($subscriber_email, FILTER_VALIDATE_EMAIL) || $subscriber_fhp_input != "") {

            $array["valid"] = 0;
            $array["message"] = $varErrorValidation;

        } else {

            if ($mode === "mailchimp") {

                // where are we posting to?
                $url =  $myarray['SENDINBLUE_TOKEN'];
                // what post fields?
                $fields = array(
                    'EMAIL' => $subscriber_email,
                    'OPT_IN' => 1,
                );

                // build the urlencoded data
                $postvars = http_build_query($fields);

                // open connection
                $ch = curl_init();

                // set the url, number of POST vars, POST data
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                // execute post
                $result = curl_exec($ch);


                // close connection
                curl_close($ch);

                $array["valid"] = 1;
                $array["message"] = $varSuccess;

            }

        }

    }
	echo json_encode($array);
}
?>
