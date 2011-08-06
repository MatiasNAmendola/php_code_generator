<?php


  
  class Form {
  
      public $template;
      public $c = '';      
      public $data = array();
      public $elements = array();
      public $rules = array();
      public $frmid = NULL;
      public $sender = NULL;
      public $html_tags = array();
      public $js_valid = TRUE;
      public $return_output = TRUE;
      public $error_array = array();
      public $msgs = array('New words');
      
      
      function Form($id = NULL)
      {         
        if($id != NULL)
        {                     
           $this->frmid = $id;                       
        }                                         
      } 
     
      
      public function HTML($name, $tag)
      {
          
        $this->html_tags[$name] = $tag;  
          
      }
          
      
      public function ReturnOutput($bol)
      {
      
        if($bol == FALSE)
        {      
           $this->return_output = false;       
        }
      
      }
      
      public function SetMsgs($msgs)
      {
      
        $this->msgs = array();
        foreach(explode(',', $msgs) as $msg)
        {       
          array_push($this->msgs, $msg);        
        }
      
      }
      
   
      public function AddElement($element = 'input', $name, $prefix, $default_value = '', $data = array())
      {
              
         if($element == 'input') {
         
           array_push($this->data, array('input', $name, $prefix, $default_value));
                         
         }elseif($element == 'submit') {
                 
           array_push($this->data, array('submit', $name, $default_value));
           $this->sender = $name;
         
         }elseif($element == 'select') {
         
           array_push($this->data, array('select', $name, $prefix, $default_value, $data));
                  
         }elseif($element == 'password') {
         
           array_push($this->data, array('password', $name, $prefix, $default_value));
         
         }elseif($element == 'checkbox') {
         
           array_push($this->data, array('checkbox', $name, $prefix, $default_value, $data));         
         
         }elseif($element == 'radio') {

           array_push($this->data, array('radio', $name, $prefix, $default_value, $data));
         
         }elseif($element == 'captcha') {
         
           array_push($this->data, array('captcha', $name, $prefix, $default_value, $data));          
           $this->rules[$name.':CAPTCHA'] = array('safasfasfasfasf', NULL);
           
         }
     
      }
  
   
      public function AssignTemplate($template_file)
      {
      
        $this->template = $template_file;                
      
      }
      
      
      public function GenerateForm()
      {
      
      //Generator for Form ID    
       function random_gen($length)
        {
          $random= "";
          srand((double)microtime()*1000000);
          $char_list = "abcdefghijklmnopqrstuvwxyz";

           for($i = 0; $i < $length; $i++)
            {
             $random .= substr($char_list, (rand()%(strlen($char_list))), 1);
            }
           return $random;
         } 
                        
       if($this->frmid == NULL){
        
         $this->frmid = random_gen(8);    
           
       }
          
          
              
       foreach($this->data as $element)  {
           
          //set html tags
          $html = '';
          
           foreach($this->html_tags as $name=>$html_tag)
           {
               
             if($element['1'] == $name)
             {
                 
               $html .= ' '.$html_tag; 
                 
             }  
               
           }   
           
        
         if($element[0] == 'input') {
         
           $this->elements[$element['1']] = '<input type="text" id="lab_'.$element['1'].'" name="'.$element[1].'" value="'.$element[3].'" '.$html.'/>';
           $this->elements['label:'.$element['1']] = '<label for="lab_'.$element['1'].'">'.$element['2'].'</label>';         
         
         }elseif($element[0] == 'submit') {
         
           $this->elements[$element['1']] = '<p><input type="submit" name="'.$element[1].'" value="'.$element[2].'" '.$html.'/></p>';
                   
         }elseif($element [0] == 'select') {          
         
           $this->c .= '<select name="'.$element[1].'" id="lab_'.$element[1].'" '.$html.'>';
           
           
            foreach($element[4] as $pref=>$val) {
            
             if(is_array($val))
             {
              
                 $this->c .= '<optgroup label="'.$pref.'">';
              
               foreach($val as $pref=>$val)
               {
              
                  $this->c .= '<option value="'.$val.'">'.$pref.'</option>';              
              
               }
               
                $this->c .= '</optgroup>';
             
             }else
             {
            
                 if($val == $element[3]) {
 
                    $this->c .= '<option value="'.$val.'" selected="yes">'.$pref.'</option>'; 
            
                 }else {
            
                    $this->c .= '<option value="'.$val.'">'.$pref.'</option>';            
            
                 }
              
             }
            }         

           
           $this->c .= '</select>';
           
          $this->elements['label:'.$element['1']] = '<label for="lab_'.$element['1'].'">'.$element[2].'</label>';           
          $this->elements[$element['1']] = $this->c;   
          
          $this->c = '';        
           
         }elseif($element[0] == 'password') {
         
          $this->elements[$element['1']] = '<input type="password" id="lab_'.$element[1].'" name="'.$element[1].'" value="'.$element[3].'" '.$html.'/>';
          $this->elements['label:'.$element['1']] = '<label for="lab_'.$element['1'].'">'.$element['2'].'</label>';           
         
         }elseif($element[0] == 'checkbox') {
         
           foreach($element[4] as $pref=>$val) {
         
            if($element[3] == TRUE)
            {                
               $this->c .= '<input type="checkbox" name="'.$element[1].'" id="lab_'.$element[1].'" value="'.$val.'" '.$html.' CHECKED> '.$pref;                 
            }else
            {               
               $this->c .= '<input type="checkbox" name="'.$element[1].'" id="lab_'.$element[1].'" value="'.$val.'" '.$html.'/> '.$pref;                
            }  
         
           }
         
          $this->elements[$element['1']] = $this->c;          
          $this->elements['label:'.$element[1]] = '<label for="lab_'.$element[1].'">'.$element['2'].'</label>';  
          
          $this->c = '';
                 
         }elseif($element[0] == 'radio') {
           
           foreach($element[4] as $pref=>$val) {
         
            if($element[3] == $val)
            {
              $this->c .= '<input type="radio" name="'.$element[1].'" value="'.$val.'" '.$html.' checked> '.$pref;  
            }else
            {
              $this->c .= '<input type="radio" name="'.$element[1].'" value="'.$val.'" '.$html.'> '.$pref;  
            }   
            
              
           }
            
          $this->elements[$element['1']] = $this->c;          
          $this->elements['label:'.$element[1]] = '<label for="lab_'.$element[1].'">'.$element[2].'</label>';    
          
          $this->c = '';     
           
         }elseif($element[0] == 'captcha') {
         
           $this->elements[$element['1']] = '
             <script type="text/javascript">
              var RecaptchaOptions = {
               theme : \'custom\'
              };
             </script>

            <div id="recaptcha_container">
             <div id="recaptcha_image"></div>
             <input type="text" id="recaptcha_response_field" name="recaptcha_response_field" '.$html.'/>
             <input type="button" id="recaptcha_reload_btn" value="'.$this->msgs[0].'" onclick="Recaptcha.reload();" />
            </div>

            <script type="text/javascript" src="http://api.recaptcha.net/challenge?k=6LdvZMISAAAAAHZ9EqPIdDIBDfdPPCynxJo6jM7a">
            
            </script>

              <noscript>
               <iframe src="http://api.recaptcha.net/noscript?k=6LdvZMISAAAAAHZ9EqPIdDIBDfdPPCynxJo6jM7a"> height="300" width="500" frameborder="0"></iframe>
               <textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
               <input type="hidden" name="recaptcha_response_field" value="manual_challenge" />
              </noscript>
';
            $this->elements['label:'.$element['1']] = '<label for="lab_'.$element['1'].'">'.$element['2'].'</label>';                         
 
         }
                        
       }
          
           
      //RULES
     $content = '<script type="text/javascript">
                   
                 function ReportError(error) {
             
                   window.alert(error);
              
                 }
                    
             function ValidForm() {';      
         //GENERATE JS CODE             
      foreach($this->rules as $rule=>$sett) {
      
       $exp = explode(':', $rule);
       
        $prvok = $exp[0];
        $type = $exp[1];
        
         $mess = str_replace(array('{N}'), array($sett[0]), $sett[1]);
               
         if($type == 'MINSIZE') {
        
            $content.='               
              if(document.'.$this->frmid.'.lab_'.$prvok.'.value.length < '.$sett[0].') { 
               ReportError("'.$mess.'");
               return false;
              }                     
            ';                     
        
         }elseif($type == 'MAXSIZE') {
         
            $content.='               
              if(document.'.$this->frmid.'.lab_'.$prvok.'.value.length > '.$sett[0].') { 
               ReportError("'.$mess.'");
               return false;
              }                     
            ';
          
         }elseif($type == 'EMAIL') {
         
           $content .= '
             var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
              if(reg.test(document.'.$this->frmid.'.lab_'.$prvok.'.value) == false) {
               ReportError("'.$sett[0].'");
               return false;
              }
           ';
         
         }elseif($type == 'EQUAL') {
          
           $content .= '
             if((document.'.$this->frmid.'.lab_'.$prvok.'.value.length) == 0 || !(document.'.$this->frmid.'.lab_'.$prvok.'.value == document.'.$this->frmid.'.lab_'.$sett[1].'.value)) {
               ReportError("'.$sett[0].'");
               return false;              
             }
           ';         
         }elseif($type == 'MUSTBE') {
         
           $content .= '
             if(!document.'.$this->frmid.'.lab_'.$prvok.'.checked) {
               ReportError("'.$sett[0].'");
               return false;             
             }
           ';         
         }elseif($type === 'REGEXP') {
         
           $content .= '
           if(!(document.'.$this->frmid.'.lab_'.$prvok.'.value.match('.$sett[1].'))) {
               ReportError("'.$sett[0].'");
               return false;
           }';
         
         }      
      
      } 
      $content .= '}
   </script>
  ';   
         
        if($this->js_valid != TRUE)
        {
        
          $content = '';
        
        }                    
                                                            
      //GENERATE TEMPLATE AND PARSE IT     
      $content .= '<form method="post" action="" name="'.$this->frmid.'" onsubmit="return ValidForm();">';        
       if(file_exists($this->template)) {
       
         $content .= file_get_contents($this->template);
       
         if(count($this->elements)>0) {
          foreach($this->elements as $tag=>$data){
          
             $content = str_replace('{'.$tag.'}',$data,$content);
           
          }
         }
         
         $content .= '</form>';
         
       
        } else {  
          GenError('Šablóna '.$this->template.' nenájdená');  
          exit(); 
        }
            
          if($this->return_output == TRUE){
              
            return $content;   
              
          }else{
              
            echo $content;
              
          } 
              
      }
      
      
      public function Rule($element, $type, $addit = array()) 
      {
      
         $addit = explode("|", $addit);
      
         if($type == 'MINSIZE') {
        
           $this->rules[$element.':MINSIZE'] = array($addit[1], $addit[0]); 
         
         }elseif($type == 'MAXSIZE') {
         
           $this->rules[$element.':MAXSIZE'] = array($addit[1], $addit[0]);
           
         }elseif($type == 'EMAIL') {
         
           $this->rules[$element.':EMAIL'] = array($addit[0], NULL);
           
         }elseif($type == 'EQUAL') {
         
           $this->rules[$element.':EQUAL'] = array($addit[0], $addit[1]);
          
         }elseif($type == 'MUSTBE') {
         
           $this->rules[$element.':MUSTBE'] = array($addit[0], NULL);         
         
         }elseif($type == 'REGEXP') {
         
           $this->rules[$element.':REGEXP'] = array($addit[0], $addit[1]);         
         
         }
                              
      }              
      
     
     //IF submit... 
     public function IsSubmit()
     {
     
       if($this->sender != NULL && isset($_POST[$this->sender])){
         return true;
       }else
       {
         return false;
       } 
     
     } 
      
      
      //Validate
     public function Valid(){
         
       if($this->sender != NULL && isset($_POST[$this->sender])){
           
         //SERVER SIDE VALIDATION
         
         $errors = array();                   
                  
          foreach($this->rules as $rule=>$sett) {
                                     
             $exp = explode(':', $rule);       
             $prvok = $exp[0];
             $type = $exp[1];
        
             $mess = str_replace(array('{N}'), array($sett[0]), $sett[1]); 
             
               if(isset($_POST[$prvok]) && $type != 'CAPTCHA')
               {
                   $post_thru = htmlspecialchars(stripslashes($_POST[$prvok]));            
               }
          
                                             
              
              if($type == 'MINSIZE')
              {
               
                 if(strlen($post_thru) < $sett[0]) {array_push($this->error_array, $mess);}
               
              }elseif($type == 'MAXSIZE')
              {
              
                 if(strlen($post_thru) > $sett[0]) {array_push($this->error_array, $mess);}
              
              }elseif($type == 'EMAIL')
              {
              
                 if (!preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $post_thru)) {array_push($this->error_array, $sett[0]);}
              
              }elseif($type == 'EQUAL')
              {
              
                 if($post_thru != htmlspecialchars(stripslashes($_POST[$sett[1]])) || $post_thru == '') {array_push($this->error_array, $sett[0]);}
               
              }elseif($type == 'MUSTBE')
              {

                 if(!isset($_POST[$prvok])) {array_push($this->error_array, $sett[0]);}
              
              }elseif($type == 'CAPTCHA')
              {
                              
                $publickey = "6LdvZMISAAAAAHZ9EqPIdDIBDfdPPCynxJo6jM7a";
                $privatekey = "6LdvZMISAAAAAFDEa70XNO-dJO69FK2qTbC3iixq";

                 $resp = null;

                 $error = null;

                 if (isset($_POST["recaptcha_response_field"])) {
                 
                      $resp = recaptcha_check_answer ($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);

                      if ($resp->is_valid) {} else {array_push($this->error_array, $sett[0]);}
  
                 }    
              
              }elseif($type == "REGEXP")
              {
              
                 if(!preg_match($sett[1], $prvok)) {array_push($this->error_array, $sett[0]);}
              
              }      
                 
          } 
          
            if(count($this->error_array) > 0)
            {
              $c = '';
              
               $c .= '<ul>';
              
               foreach($this->error_array as $fail)
               {
                 
                 $c .= '<li>'.$fail.'</li>';
               
               }              
                    
              $c .= '</ul>';
              
              $this->error_block = $c;              
              
            }
         
         
             if(count($this->error_array)>0)
             {
             
                return false;
            
             }else
             {
             
                return true;
             
             }
            
           
       }  
         
     }
       
  } 
  
 //reCaptcha common library
define("RECAPTCHA_API_SERVER", "http://www.google.com/recaptcha/api");
define("RECAPTCHA_API_SECURE_SERVER", "https://www.google.com/recaptcha/api");
define("RECAPTCHA_VERIFY_SERVER", "www.google.com");

function _recaptcha_qsencode ($data)
{
        $req = "";
        foreach ( $data as $key => $value )
                $req .= $key . '=' . urlencode( stripslashes($value) ) . '&';

        // Cut the last '&'
        $req=substr($req,0,strlen($req)-1);
        return $req;
}

function _recaptcha_http_post($host, $path, $data, $port = 80)
{

        $req = _recaptcha_qsencode ($data);

        $http_request  = "POST $path HTTP/1.0\r\n";
        $http_request .= "Host: $host\r\n";
        $http_request .= "Content-Type: application/x-www-form-urlencoded;\r\n";
        $http_request .= "Content-Length: " . strlen($req) . "\r\n";
        $http_request .= "User-Agent: reCAPTCHA/PHP\r\n";
        $http_request .= "\r\n";
        $http_request .= $req;

        $response = '';
        if( false == ( $fs = @fsockopen($host, $port, $errno, $errstr, 10) ) ) {
                die ('Could not open socket');
        }

        fwrite($fs, $http_request);

        while ( !feof($fs) )
                $response .= fgets($fs, 1160); // One TCP-IP packet
        fclose($fs);
        $response = explode("\r\n\r\n", $response, 2);

        return $response;
}

class ReCaptchaResponse
{
        var $is_valid;
        var $error;
}

function recaptcha_check_answer ($privkey, $remoteip, $challenge, $response, $extra_params = array())
{
	if ($privkey == null || $privkey == '') {
		die ("To use reCAPTCHA you must get an API key from <a href='https://www.google.com/recaptcha/admin/create'>https://www.google.com/recaptcha/admin/create</a>");
	}

	if ($remoteip == null || $remoteip == '') {
		die ("For security reasons, you must pass the remote ip to reCAPTCHA");
	}

        if ($challenge == null || strlen($challenge) == 0 || $response == null || strlen($response) == 0) {
                $recaptcha_response = new ReCaptchaResponse();
                $recaptcha_response->is_valid = false;
                $recaptcha_response->error = 'incorrect-captcha-sol';
                return $recaptcha_response;
        }

        $response = _recaptcha_http_post (RECAPTCHA_VERIFY_SERVER, "/recaptcha/api/verify",
                                          array (
                                                 'privatekey' => $privkey,
                                                 'remoteip' => $remoteip,
                                                 'challenge' => $challenge,
                                                 'response' => $response
                                                 ) + $extra_params
                                          );

        $answers = explode ("\n", $response [1]);
        $recaptcha_response = new ReCaptchaResponse();

        if (trim ($answers [0]) == 'true') {
                $recaptcha_response->is_valid = true;
        }
        else {
                $recaptcha_response->is_valid = false;
                $recaptcha_response->error = $answers [1];
        }
        return $recaptcha_response;

}

function recaptcha_get_signup_url ($domain = null, $appname = null)
{
	return "https://www.google.com/recaptcha/admin/create?" .  _recaptcha_qsencode (array ('domains' => $domain, 'app' => $appname));
}

function _recaptcha_aes_pad($val)
{
	$block_size = 16;
	$numpad = $block_size - (strlen ($val) % $block_size);
	return str_pad($val, strlen ($val) + $numpad, chr($numpad));
}

function _recaptcha_aes_encrypt($val,$ky)
{
	if (! function_exists ("mcrypt_encrypt")) {
		die ("To use reCAPTCHA Mailhide, you need to have the mcrypt php module installed.");
	}
	$mode=MCRYPT_MODE_CBC;   
	$enc=MCRYPT_RIJNDAEL_128;
	$val=_recaptcha_aes_pad($val);
	return mcrypt_encrypt($enc, $ky, $val, $mode, "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0");
}


function _recaptcha_mailhide_urlbase64 ($x) 
{
	return strtr(base64_encode ($x), '+/', '-_');
}

function recaptcha_mailhide_url($pubkey, $privkey, $email)
{
	if ($pubkey == '' || $pubkey == null || $privkey == "" || $privkey == null) {
		die ("To use reCAPTCHA Mailhide, you have to sign up for a public and private key, " .
		     "you can do so at <a href='http://www.google.com/recaptcha/mailhide/apikey'>http://www.google.com/recaptcha/mailhide/apikey</a>");
	}
	

	$ky = pack('H*', $privkey);
	$cryptmail = _recaptcha_aes_encrypt ($email, $ky);
	
	return "http://www.google.com/recaptcha/mailhide/d?k=" . $pubkey . "&c=" . _recaptcha_mailhide_urlbase64 ($cryptmail);
}

function _recaptcha_mailhide_email_parts ($email)
{
	$arr = preg_split("/@/", $email );

	if (strlen ($arr[0]) <= 4) {
		$arr[0] = substr ($arr[0], 0, 1);
	} else if (strlen ($arr[0]) <= 6) {
		$arr[0] = substr ($arr[0], 0, 3);
	} else {
		$arr[0] = substr ($arr[0], 0, 4);
	}
	return $arr;
}

function recaptcha_mailhide_html($pubkey, $privkey, $email)
{
	$emailparts = _recaptcha_mailhide_email_parts ($email);
	$url = recaptcha_mailhide_url ($pubkey, $privkey, $email);
	
	return htmlentities($emailparts[0]) . "<a href='" . htmlentities ($url) .
		"' onclick=\"window.open('" . htmlentities ($url) . "', '', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=500,height=300'); return false;\" title=\"Reveal this e-mail address\">...</a>@" . htmlentities ($emailparts [1]);
}
