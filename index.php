<?php
/*
* index file
* version: 1.0.0
* (c) Boilerplate for sleekshop applications
* (c) Demo app - Manisha Sharma(ms@sleekshop.io)
*/

require __DIR__ . '/vendor/bootstrap.php';
//define application folder name
define("APP_PATH",basename(__DIR__));

$request = strtok($_SERVER["REQUEST_URI"], '?');
$request=explode("/",$request);
$request=array_pop($request);
$request="/".$request;
$app_path = APP_PATH;
$remote_session = $_GET["ses"];
$custom_template = "";
$custom_mail_file = $_SERVER['SERVER_NAME']."/".basename(__DIR__)."/custom/mail.php";
if(isset($remote_session)){
  $custom_template = '<form action="'.$custom_mail_file.'">
                          <div class="container">
                            <div class="row">
                              <div class="col-sm-12">
                              	 <div class="single">
                                		<h2>Subscribe to our Newsletter</h2>
                                  	<div class="input-group">
                                       <input type="email" class="form-control" name="email" placeholder="Enter your email">
                                       <span class="input-group-btn">
                                            <button class="btn btn-theme" type="submit" name="subscribe">Subscribe</button>
                                       </span>
                                    </div>
                                	</div>
                              </div>
                            </div>
                          </div>
                      </form>
                      ';
}
switch ($request) {
    case '/' :
            echo $twig->render('error.html', ['data' =>  '404 Not Found!!!','path' => $app_path]);
            break;
    case '/home' :
            echo $twig->render('index.html', ['data' =>  'Welcome to Sendinblue','path' => $app_path,'token'=>$_GET["token"],'remote_session'=>$remote_session,'custom_html' => $custom_template] );
            break;
    case '/settings' :
             $data_array = $myarray;
             $success = 0;
             $server            = $data_array['SERVER'];
             $licence_username  = $data_array['LICENCE_USERNAME'];
             $application_token = $data_array['APPLICATION_TOKEN'];
             //data in our POST
             if(isset($_POST['add'])){
                $data_array['SERVER']             = $_POST['SERVER'];
                $data_array['LICENCE_USERNAME']   = $_POST['LICENCE_USERNAME'];
                $data_array['LICENCE_PASSWORD']   = $_POST['LICENCE_PASSWORD'];
                $data_array['APPLICATION_TOKEN']  = $_POST['APPLICATION_TOKEN'];
                $data_array['SENDINBLUE_TOKEN']   = $_POST['SENDINBLUE_TOKEN'];

                //$data_array = json_encode($data_array, JSON_PRETTY_PRINT);
                file_put_contents(__DIR__.'/vendor/config.php',
                    "<?php\n\$myarray = "
                      .var_export($data_array, true)
                    .";\n?>"
                  );
                  $data_message = "Configuration Updated!!";
                  $sr=new SleekshopRequest($data_array);
                  $res=$sr->instant_login($_GET["token"]);
                  $jsondecoded = json_decode($res,true);
                  if($jsondecoded['status'] == "SUCCESS"){
                    echo $twig->render('settings.html', ['data' => $data_message,'mail_action' => $custom_mail_file,'config_data' =>  $data_array,'path' => $app_path,'token'=>$_GET["token"],'remote_session'=>$jsondecoded['remote_session']]);
                  }else{
                    echo $twig->render('error.html', ['data' =>  'PERMISSION_DENIED','path' => $app_path] );
                  }
             }
             else{
                $data_message = "Configuration Settings!!";
                echo $twig->render('settings.html', ['data' => $data_message,'path' => $app_path,'config_data'=>$data_array,'token'=>$_GET["token"],'remote_session'=>$remote_session,'custom_html' => $custom_template] );
              }
              break;
    default:
              if(empty($_GET["token"])){
                  echo $twig->render('error.html', ['data' =>  'PERMISSION_DENIED','path' => $app_path] );
              }else{
                  //checking for empty values and try instant login and if all good show settings form else permission denied
                  if($myarray['SERVER'] != "" && $myarray['LICENCE_USERNAME'] != "" && $myarray['LICENCE_PASSWORD'] != "" && $myarray['APPLICATION_TOKEN'] != ""){
                        $sr=new SleekshopRequest($myarray);
                        $res=$sr->instant_login($_GET["token"]);
                        $jsondecoded = json_decode($res,true);
                        if($jsondecoded['status'] == "SUCCESS"){
                          echo $twig->render('index.html', ['path' => $app_path,'token'=>$_GET["token"],'custom_template' => $custom_mail_file,'remote_session'=>$jsondecoded['remote_session'],'custom_html' => $custom_template]);
                        }else{
                          echo $twig->render('error.html', ['data' =>  'PERMISSION_DENIED','path' => $app_path] );
                        }
                  }else{
                    echo $twig->render('index.html', ['token'=>$_GET["token"],'path' => $app_path,'custom_html' => $custom_template]);
                  }
                }
                break;
}
?>
