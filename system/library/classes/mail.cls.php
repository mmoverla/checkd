<?php

class NicMail {

    var $to = null;
    var $from = null;
    var $subject = null;
    var $headers = null;
    var $body = null;
    var $apiname = 'azure_b6af34b86e5d7b201cfe0d0c67994c4d@azure.com';
    var $apipass = 'OIlll18rNhIolo2';
    var $apihost = 'https://api.sendgrid.com/';
    var $category = 'test_cateogry';

    // Define initial mail variables
    function __construct() {
        
    }

    // Send template base/simple mail.
    function send($tmppath = '', $set_tmp_replace_var = '') {
        global $ADMINEMAIL;

        if ($tmppath != '' && $set_tmp_replace_var != '') {
            $this->getTemplate($tmppath, $set_tmp_replace_var);
        }
        $json_string = false;
        if (!is_array($this->to)) {
            $emails = explode(',', $this->to);
            $json_string = json_encode(array(
                'to' => $emails,
                'category' => $this->category
            ));
            $to = $emails[0];
        } else {
            $emails = $this->to;
            $to = $this->to;
        }

        if ($json_string) {
            $params['x-smtpapi'] = $json_string;
        }
        $this->body = utf8_encode($this->body);
        $params = array(
            'api_user' => $this->apiname,
            'api_key' => $this->apipass,
            'to' => $to,
            'subject' => $this->subject,
            'html' => "<p>{$this->body}</p>",
            'text' => strip_tags($this->body),
            'from' => !empty($this->from) ? $this->from : ADMINEMAIL,
        );
        //echo "<pre>";print_r($params);
        $request = $this->apihost . 'api/mail.send.json';

        $session = curl_init($request);
        curl_setopt($session, CURLOPT_POST, true);
        curl_setopt($session, CURLOPT_POSTFIELDS, $params);
        curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);
        $response = json_decode(curl_exec($session));
        curl_close($session);

        if ($response->message == "success") {
            return 1;
        } else {
           //die("Curl failed with error: " . curl_error($ch));
            return 0;
        }
    }

    function sendMultiMail($tmppath = '', $set_tmp_replace_var = '') {
        if (!is_array($this->to)) {
            $emails = explode(',', $this->to);
        } else {
            $emails = $this->to;
        }

        $to = $emails[0];

        $json_string = array(
            'to' => $emails,
            'category' => $this->category
        );
        $this->body = utf8_encode($this->body);
        $params = array(
            'api_user' => $this->apiname,
            'api_key' => $this->apipass,
            'x-smtpapi' => json_encode($json_string),
            'to' => $emails[0],
            'subject' => $this->subject,
            'html' => "<p>{$this->body}</p>",
            'text' => strip_tags($this->body),
            'from' => !empty($this->from) ? $this->from : ADMINEMAIL,
        );
       
        $request = $this->apihost . 'api/mail.send.json';

        $session = curl_init($request);
        curl_setopt($session, CURLOPT_POST, true);
        curl_setopt($session, CURLOPT_POSTFIELDS, $params);
        curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        $response = json_decode(curl_exec($session));
        curl_close($session);
         echo '<pre>';
        print_r($params);
        print_r($response);
        echo '</pre>';
        if ($response->message == "success") {
            return 1;
        } else {
            return 0;
        }
    }

    // Send template base/simple mail with attachment.
    function sendWithAttachment($atchfilename, $atchfilepath, $tmppath = '', $set_tmp_replace_var = '') {
        if ($tmppath != '') {
            $this->getTemplate($tmppath, $set_tmp_replace_var);
        }
        if (!is_array($this->to)) {
            $emails = explode(',', $this->to);
        } else {
            $emails = $this->to;
        }

        $to = $emails[0];

        $json_string = array(
            'to' => $emails,
            'category' => $this->category
        );
        $this->body = utf8_encode($this->body);
        $params = array(
            'api_user' => $this->apiname,
            'api_key' => $this->apipass,
            'x-smtpapi' => json_encode($json_string),
            'to' => $emails[0],
            'subject' => $this->subject,
            'html' => "<p>{$this->body}</p>",
            'text' => strip_tags($this->body),
            'from' => !empty($this->from) ? $this->from : ADMINEMAIL,
        );
        $file = $atchfilepath . $atchfilename;
        if (file_exists($file)) {
            $params['files[' . $atchfilename . ']'] = '@' . $file;
        }

        $request = $this->apihost . 'api/mail.send.json';

        $session = curl_init($request);
        curl_setopt($session, CURLOPT_POST, true);
        curl_setopt($session, CURLOPT_POSTFIELDS, $params);
        curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        $response = json_decode(curl_exec($session));
        curl_close($session);
        if ($response->message == "success") {
            return 1;
        } else {
            return 0;
        }
    }
    
    // Send template base/simple mail with attachment.
    function sendWithAttachmentWithFileName($atchfilename, $atchfilepath, $tmppath = '', $set_tmp_replace_var = '',$newfilename = '') {
        if ($tmppath != '') {
            $this->getTemplate($tmppath, $set_tmp_replace_var);
        }
        if (!is_array($this->to)) {
            $emails = explode(',', $this->to);
        } else {
            $emails = $this->to;
        }

        $to = $emails[0];

        $json_string = array(
            'to' => $emails,
            'category' => $this->category
        );
        $this->body = utf8_encode($this->body);
        $params = array(
            'api_user' => $this->apiname,
            'api_key' => $this->apipass,
            'x-smtpapi' => json_encode($json_string),
            'to' => $emails[0],
            'subject' => $this->subject,
            'html' => "<p>{$this->body}</p>",
            'text' => strip_tags($this->body),
            'from' => !empty($this->from) ? $this->from : ADMINEMAIL,
        );
        $file = $atchfilepath . $atchfilename;
        if (file_exists($file)) {
            $params['files[' . $newfilename . ']'] = '@' . $file;
        }

        $request = $this->apihost . 'api/mail.send.json';

        $session = curl_init($request);
        curl_setopt($session, CURLOPT_POST, true);
        curl_setopt($session, CURLOPT_POSTFIELDS, $params);
        curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        $response = json_decode(curl_exec($session));
        curl_close($session);
        if ($response->message == "success") {
            return 1;
        } else {
            return 0;
        }
    }

    // Replace template variable with set variables
    function getTemplate($tmppath, $set_tmp_replace_var) {

        $this->body = file_get_contents($tmppath);

        if (count($set_tmp_replace_var) > 0) {
            foreach ($set_tmp_replace_var as $key => $val) {
                $this->body = str_replace("#$key#", $val, $this->body);
            }
        }
    }

    // Adding header
    function addHeader($header) {
        $this->headers .= $header;
    }

}

?>
