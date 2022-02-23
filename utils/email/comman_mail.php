<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require __DIR__.'/mail/vendor/phpmailer/src/Exception.php';
require __DIR__.'/mail/vendor/phpmailer/src/PHPMailer.php';
require __DIR__.'/mail/vendor/phpmailer/src/SMTP.php';

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

    function send_mail($param){
        $myemail    = strtolower($param['to_email']);
        $myfullname = $param['to_name'];
        //$from_email = strtolower(otp_mail_sender);
        $from_name  = sender_name;
        
        $mail = new PHPMailer(true);
        try {
            $host   =  host;
            $host_u =  host_u;
            $host_p =  host_p;

            if(isset($param['from_email'])){
                $from_email = strtolower($param['from_email']);
                $from_name  = $param['from_name'];
            }

            if(isset($param['change_auth'])){
                $host       = host;
                $from_email = $param['host_u'];
                
            }
           
            $mail->isSMTP();
            $mail->SMTPDebug  = 0;
            $mail->Host       = $host;
            $mail->SMTPAuth   = true;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
        
            $mail->Username   = $host_u;
            $mail->Password   = $host_p;
            // $mail->SMTPDebug  = 2; 
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            // Sender and recipient settings
            $mail->setFrom($from_email, $from_name);
            $mail->addAddress($myemail , $myfullname);
            if(isset($param['add_cc']) && count($param['add_cc']) > 0){
                foreach($param['add_cc'] as $key_cc => $val_cc){
                    if(isset($val_cc['add_cc']) && $val_cc['add_cc']!=''){
                        $mail->AddCC(strtolower($val_cc['add_cc']), $val_cc['cc_name']);
                    }
                    
                }
            }
            // Setting the email content
            $mail->IsHTML(true);
            $mcontent      = $param['content'];

            //$mcontent      .= '<div style="display:none"><img src="'.website_path.'email_tracking.php?XsfhhytnghntgSD='.$object->aes_encrypt_string($send_mail).'" width="1" height="1" /></div>';
            $mail->Subject = $param['subject'];
            $mail->Body    = $mcontent;
            $mail->AltBody = 'This is a plain text message body';

            if(!$mail->send()) {
                $result =  false;
                
            } else {
               
                $result =  true;
            }
        } catch (Exception $e) {
            
            $result = false;
        }
        return $result;
    }
    
	function direct_registration($data = array()){
		$result = false;
        if(count($data) > 0){                 
            $from_email = host_u;
            $from_name  = sender_name;
            
            $content	= '';
            $myFile         = __DIR__.'/registration.html';
            $file_funs      = file($myFile);
            $mail_content   = '';
            if(count($file_funs) > 0){
                foreach($file_funs as $file_num => $file_fun) {
                    #$file_fun = str_replace('$date$', date('d, M Y'), $file_fun);
                    $file_fun = str_replace('$sname$', $data['to_name'], $file_fun);
                    $file_fun = str_replace('$email$', $data['to_email'], $file_fun); 
					//$file_fun = str_replace('$degree$', $data['degree'], $file_fun);
                    //$file_fun = str_replace('$course$', $data['course'], $file_fun);
                    $file_fun = str_replace('$site_url$', $data['site_url'], $file_fun);
                    $file_fun = str_replace('$posted_by$', $data['posted_by'], $file_fun);
                    $file_fun = str_replace('$disclaimer_content$', disclaimer_content, $file_fun);
                    $file_fun = str_replace('$support_and_contact$', support_and_contact, $file_fun);
                    $mail_content.= $file_fun;
                }
            }
            $content	 .= $mail_content;
            $data['template_id']  = 1;
            $data['content']      = $content;
            $data['from_email']   = $from_email;
            $data['from_name']   = $from_name;
            $data['subject']      = 'New Job Posted';//Email Subject
            /*$data['change_auth']  = '1';
            $data['host_u']       = admission_mail_sender;*/
            $mail                 = send_mail($data);
            if($mail){
                $result = true;
            } 
        }
        return $result;
	}

	function new_registration($data = array()){
		$result = false;
        if(count($data) > 0){
            $from_email = host_u;
            $from_name  = sender_name;

            $content	= '';
            $myFile         = __DIR__.'/user_registration.html';
            $file_funs      = file($myFile);
            $mail_content   = '';
            if(count($file_funs) > 0){
                foreach($file_funs as $file_num => $file_fun) {
                    $file_fun = str_replace('$sname$', $data['to_name'], $file_fun);
                    $file_fun = str_replace('$email$', $data['to_email'], $file_fun);
                    $file_fun = str_replace('$site_url$', $data['site_url'], $file_fun);
                    $file_fun = str_replace('$posted_by$', $data['posted_by'], $file_fun);
                    $file_fun = str_replace('$posted_name$', $data['posted_name'], $file_fun);
                    $file_fun = str_replace('$roll_number$', $data['roll_number'], $file_fun);
                    $file_fun = str_replace('$disclaimer_content$', disclaimer_content, $file_fun);
                    $file_fun = str_replace('$support_and_contact$', support_and_contact, $file_fun);
                    $mail_content.= $file_fun;
                }
            }
            $content	 .= $mail_content;
            $data['template_id']  = 1;
            $data['content']      = $content;
            $data['from_email']   = $from_email;
            $data['from_name']   = $from_name;
            $data['subject']      = 'New User Request';//Email Subject
            $mail                 = send_mail($data);
            if($mail){
                $result = true;
            }
        }
        return $result;
	}
	function approve_user($data = array()){
		$result = false;
        if(count($data) > 0){
            $from_email = host_u;
            $from_name  = sender_name;

            $content	= '';
            $myFile         = __DIR__.'/user_staus_email.html';
            $file_funs      = file($myFile);
            $mail_content   = '';
            if(count($file_funs) > 0){
                foreach($file_funs as $file_num => $file_fun) {
                    #$file_fun = str_replace('$date$', date('d, M Y'), $file_fun);
                    $file_fun = str_replace('$sname$', $data['to_name'], $file_fun);
                    $file_fun = str_replace('$email$', $data['to_email'], $file_fun);
                    $file_fun = str_replace('$site_url$', $data['site_url'], $file_fun);
                    $file_fun = str_replace('$roll_number$', $data['roll_number'], $file_fun);
                    $file_fun = str_replace('$disclaimer_content$', disclaimer_content, $file_fun);
                    $file_fun = str_replace('$support_and_contact$', support_and_contact, $file_fun);
                    $mail_content.= $file_fun;
                }
            }
            $content	 .= $mail_content;
            $data['template_id']  = 1;
            $data['content']      = $content;
            $data['from_email']   = $from_email;
            $data['from_name']   = $from_name;
            $data['subject']      = 'User Account Activated';//Email Subject
            /*$data['change_auth']  = '1';
            $data['host_u']       = admission_mail_sender;*/
            $mail                 = send_mail($data);
            if($mail){
                $result = true;
            }
        }
        return $result;
	}


    

?>