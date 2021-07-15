<?php 
require_once("Home.php"); // loading home controller

class Code_minifier extends Home
{

    public $user_id;    
    
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_in') != 1)
        redirect('home/login_page', 'location');       
 
        $this->user_id=$this->session->userdata('user_id');
        set_time_limit(0);

        $this->important_feature();
        $this->member_validity();
        $this->load->library('code_minifier_library');
        if($this->session->userdata('user_type') != 'Admin' && !in_array(17,$this->module_access))
        redirect('home/login_page', 'location'); 
    }


    public function index()
    {
    	$a = file_get_contents('application/controllers/style.css');
    	echo $this->code_minifier_library->minify_css($a);
    }

    public function html_minifier()
    {
        $data['body'] = 'code_minifier/html_minifier';
        $data['page_title'] = $this->lang->line('HTML Minifier');
        $this->_viewcontroller($data);
    }


    public function html_minifier_textarea()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }


        $code = $this->input->post('html_code');
        $html_minify = $this->code_minifier_library->minify_html($code);

        $str ="<div class='card'>
                  <div class='card-header'>
                    <h4><i class='fab fa-html5'></i> ".$this->lang->line("HTML Minified Results")."</h4>
                  </div>
                  <div class='card-body'>
                    <div class='form-group'>
                      <textarea id='html_code2' name='html_code2' class='form-control' style='width:100%;min-height: 300px;' rows='10'>".$html_minify."</textarea>
                      <div class='text-center mt-4'>
                        <button id='html_code2' type='button' data-clipboard-action='copy' data-clipboard-target='#html_code2' class='btn btn-primary'><i class='fas fa-copy'></i> ".$this->lang->line("Copy")."</button>
                      </div>
                    </div>
                  </div>
               </div>
               <script>
                  var clipboard = new Clipboard('#html_code2');

                   clipboard.on('success', function(e) {
                       alert('Copied');
                   });

                   clipboard.on('error', function(e) {
                       alert('Not Copied!');
                   });
               </script>";
         echo $str; 
       
    }

    public function read_text_file_html()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') exit();

        $ret=array();
        $output_dir = FCPATH."upload/html";
        if (isset($_FILES["myfile"])) {
            $error =$_FILES["myfile"]["error"];
            $post_fileName =$_FILES["myfile"]["name"];
            $post_fileName_array=explode(".", $post_fileName);
            $ext=array_pop($post_fileName_array);
            $filename=implode('.', $post_fileName_array);
            $filename="html_code_minify".$this->user_id."_".time().substr(uniqid(mt_rand(), true), 0, 6).".".$ext;

            $allow=".html";
            $allow=str_replace('.', '', $allow);
            $allow=explode(',', $allow);

            if(!in_array(strtolower($ext), $allow)) 
            {
                echo json_encode(array("are_u_kidding_me" => "yarki"));
                exit();
            }

            
            move_uploaded_file($_FILES["myfile"]["tmp_name"], $output_dir.'/'.$filename);

            $path = realpath(FCPATH."upload/html/".$filename);
            $read_handle=fopen($path, "r");
            $context_array =array('file_name'=>$filename);
            $context ="";
            while (!feof($read_handle)) 
            {
                $information = fgetcsv($read_handle);
                if (!empty($information)) 
                {
                    foreach ($information as $info) 
                    {
                        if (!is_numeric($info)) 
                        $context.=$info."\n";                       
                    }
                }
            }

            $context_array['content'] = trim($context, "\n");
            echo json_encode($context_array);
            
        }
    }

    public function read_after_delete_html() // deletes the uploaded video to upload another one
    {
        if(!$_POST) exit();
       
        $output_dir = FCPATH."upload/html/";
        if(isset($_POST["op"]) && $_POST["op"] == "delete" && isset($_POST['name']))
        {
             $fileName =$_POST['name'];
             $fileName=str_replace("..",".",$fileName); //required. if somebody is trying parent folder files
             $filePath = $output_dir. $fileName;
             if (file_exists($filePath))
             {
                unlink($filePath);
             }
        }
    }

    public function html_minifier_action()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }

        $output_dir = FCPATH."upload/html/";
        if (isset($_FILES["myfile"])) {

            $ret = array();
            $error =$_FILES["myfile"]["error"];
            //You need to handle  both cases
            //If Any browser does not support serializing of multiple files using FormData() 
            if(!is_array($_FILES["myfile"]["name"])) //single file
            {
                $fileName = $_FILES["myfile"]["name"];
                move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir.$fileName);

                $file_content=file_get_contents("upload/html/".$fileName);
                $file_content_new=$this->code_minifier_library->minify_html($file_content);
                file_put_contents("upload/html/".$fileName, $file_content_new, LOCK_EX);


                $ret[]= $fileName;
            }
            else  //Multiple files, file[]
            {
              $fileCount = count($_FILES["myfile"]["name"]);
              for($i=0; $i < $fileCount; $i++)
              {
                $fileName = $_FILES["myfile"]["name"][$i];
                move_uploaded_file($_FILES["myfile"]["tmp_name"][$i],$output_dir.$fileName);

                $file_content=file_get_contents("upload/html/".$fileName);
                $file_content_new=$this->code_minifier_library->minify_html($file_content);
                file_put_contents("upload/html/".$fileName, $file_content_new, LOCK_EX);

                $ret[]= $fileName;
              }
            
            }
            echo json_encode($ret);

        }
    }

    public function delete_html()
    {
        $output_dir = FCPATH."upload/html/";
        if(isset($_POST["op"]) && $_POST["op"] == "delete" && isset($_POST['name']))
        {
            $fileName =$_POST['name'];
            $fileName=str_replace("..",".",$fileName); //required. if somebody is trying parent folder files    
            $filePath = $output_dir. $fileName;
            if (file_exists($filePath)) 
            {
                unlink($filePath);
            }
            echo "Deleted File ".$fileName."<br>";
        }
    }


    public function download_html($fileName='')
    {
        $fileName = urldecode($fileName);
        if($fileName!='')
        {
            $fileName=str_replace("..",".",$fileName); //required. if somebody is trying parent folder files
            $file = FCPATH."upload/html/".$fileName;
            $file = str_replace("..","",$file);
            if (file_exists($file)) {
                $fileName =str_replace(" ","",$fileName);
                header('Content-Description: File Transfer');
                header('Content-Disposition: attachment; filename='.$fileName);
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                // ob_clean();
                // flush();
                readfile($file);
                unlink($file);
                exit;
            }
        }
    }


    public function css_minifier()
    {
        $data['body'] = 'code_minifier/css_minifier';
        $data['page_title'] = $this->lang->line('CSS code minifier');
        $this->_viewcontroller($data);
    }


    public function css_minifier_textarea()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }

        $code = $this->input->post('css_code');
        $css_minify = $this->code_minifier_library->minify_css($code);

        $str ="<div class='card'>
                  <div class='card-header'>
                    <h4><i class='fab fa-css3'></i> ".$this->lang->line("CSS Minified Results")."</h4>
                  </div>
                  <div class='card-body'>
                    <div class='form-group'>
                      <textarea id='css_code2' name='css_code2' class='form-control' style='width:100%;min-height: 300px;' rows='10'>".$css_minify."</textarea>
                      <div class='text-center mt-4'>
                        <button id='css_code2' type='button' data-clipboard-action='copy' data-clipboard-target='#css_code2' class='btn btn-primary'><i class='fas fa-copy'></i> ".$this->lang->line("Copy")."</button>
                      </div>
                    </div>
                  </div>
               </div>
               <script>
                  var clipboard = new Clipboard('.btn');

                   clipboard.on('success', function(e) {
                       alert('Copied');
                   });

                   clipboard.on('error', function(e) {
                       alert('Not Copied!');
                   });
               </script>";
         echo $str; 
    }


    public function css_minifier_action()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }

        $output_dir = FCPATH."upload/css/";
        if (isset($_FILES["myfile"])) {

            $ret = array();
            $error =$_FILES["myfile"]["error"];
            //You need to handle  both cases
            //If Any browser does not support serializing of multiple files using FormData() 
            if(!is_array($_FILES["myfile"]["name"])) //single file
            {
                $fileName = $_FILES["myfile"]["name"];
                move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir.$fileName);

                $file_content=file_get_contents("upload/css/".$fileName);
                $file_content_new=$this->code_minifier_library->minify_css($file_content);
                file_put_contents("upload/css/".$fileName, $file_content_new, LOCK_EX);


                $ret[]= $fileName;
            }
            else  //Multiple files, file[]
            {
              $fileCount = count($_FILES["myfile"]["name"]);
              for($i=0; $i < $fileCount; $i++)
              {
                $fileName = $_FILES["myfile"]["name"][$i];
                move_uploaded_file($_FILES["myfile"]["tmp_name"][$i],$output_dir.$fileName);

                $file_content=file_get_contents("upload/css/".$fileName);
                $file_content_new=$this->code_minifier_library->minify_css($file_content);
                file_put_contents("upload/css/".$fileName, $file_content_new, LOCK_EX);

                $ret[]= $fileName;
              }
            
            }
            echo json_encode($ret);

        }
    }

    public function delete_css()
    {
        $output_dir = FCPATH."upload/css/";
        if(isset($_POST["op"]) && $_POST["op"] == "delete" && isset($_POST['name']))
        {
            $fileName =$_POST['name'];
            $fileName=str_replace("..",".",$fileName); //required. if somebody is trying parent folder files    
            $filePath = $output_dir. $fileName;
            if (file_exists($filePath)) 
            {
                unlink($filePath);
            }
            echo "Deleted File ".$fileName."<br>";
        }
    }


    public function download_css($fileName='')
    {
        $fileName = urldecode($fileName);
        if($fileName!='')
        {
            $fileName=str_replace("..",".",$fileName); //required. if somebody is trying parent folder files
            $file = FCPATH."upload/css/".$fileName;
            $file = str_replace("..","",$file);
            if (file_exists($file)) {
                $fileName =str_replace(" ","",$fileName);
                header('Content-Description: File Transfer');
                header('Content-Disposition: attachment; filename='.$fileName);
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                // ob_clean();
                // flush();
                readfile($file);
                unlink($file);
                exit;
            }
        }
    }

    public function read_text_file_css()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') exit();

        $ret=array();
        $output_dir = FCPATH."upload/css";
        if (isset($_FILES["myfile"])) {
            $error =$_FILES["myfile"]["error"];
            $post_fileName =$_FILES["myfile"]["name"];
            $post_fileName_array=explode(".", $post_fileName);
            $ext=array_pop($post_fileName_array);
            $filename=implode('.', $post_fileName_array);
            $filename="css_code_minify".$this->user_id."_".time().substr(uniqid(mt_rand(), true), 0, 6).".".$ext;

            $allow=".css";
            $allow=str_replace('.', '', $allow);
            $allow=explode(',', $allow);

            if(!in_array(strtolower($ext), $allow)) 
            {
                echo json_encode(array("are_u_kidding_me" => "yarki"));
                exit();
            }

            
            move_uploaded_file($_FILES["myfile"]["tmp_name"], $output_dir.'/'.$filename);

            $path = realpath(FCPATH."upload/css/".$filename);
            $read_handle=fopen($path, "r");
            $context_array =array('file_name'=>$filename);
            $context ="";
            while (!feof($read_handle)) 
            {
                $information = fgetcsv($read_handle);
                if (!empty($information)) 
                {
                    foreach ($information as $info) 
                    {
                        if (!is_numeric($info)) 
                        $context.=$info."\n";                       
                    }
                }
            }

            $context_array['content'] = trim($context, "\n");
            echo json_encode($context_array);
            
        }
    }

    public function read_after_delete_css() // deletes the uploaded video to upload another one
    {
        if(!$_POST) exit();
       
        $output_dir = FCPATH."upload/css/";
        if(isset($_POST["op"]) && $_POST["op"] == "delete" && isset($_POST['name']))
        {
             $fileName =$_POST['name'];
             $fileName=str_replace("..",".",$fileName); //required. if somebody is trying parent folder files
             $filePath = $output_dir. $fileName;
             if (file_exists($filePath))
             {
                unlink($filePath);
             }
        }
    }


    
    public function js_minifier()
    {
        $data['body'] = 'code_minifier/js_minifier';
        $data['page_title'] = $this->lang->line('Js code minifier');
        $this->_viewcontroller($data);
    }


    public function js_minifier_textarea()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }

        $code = $this->input->post('js_code');
        $js_minify =  $this->code_minifier_library->minify_js($code);
        $str ="<div class='card'>
                  <div class='card-header'>
                    <h4><i class='fab fa-css3'></i> ".$this->lang->line("CSS Minified Results")."</h4>
                  </div>
                  <div class='card-body'>
                    <div class='form-group'>
                      <textarea id='js_code2' name='js_code2' class='form-control' style='width:100%;min-height: 300px;' rows='10'>".$js_minify."</textarea>
                      <div class='text-center mt-4'>
                        <button id='js_code2' type='button' data-clipboard-action='copy' data-clipboard-target='#js_code2' class='btn btn-primary'><i class='fas fa-copy'></i> ".$this->lang->line("Copy")."</button>
                      </div>
                    </div>
                  </div>
               </div>
               <script>
                  var clipboard = new Clipboard('.btn');

                   clipboard.on('success', function(e) {
                       alert('Copied');
                   });

                   clipboard.on('error', function(e) {
                       alert('Not Copied!');
                   });
               </script>";
         echo $str; 
    }


    public function js_minifier_action()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }

        $output_dir = FCPATH."upload/js/";
        if (isset($_FILES["myfile"])) {

            $ret = array();
            $error =$_FILES["myfile"]["error"];
            //You need to handle  both cases
            //If Any browser does not support serializing of multiple files using FormData() 
            if(!is_array($_FILES["myfile"]["name"])) //single file
            {
                $fileName = $_FILES["myfile"]["name"];
                move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir.$fileName);

                $file_content=file_get_contents("upload/js/".$fileName);
                $file_content_new=$this->code_minifier_library->minify_js($file_content);
                file_put_contents("upload/js/".$fileName, $file_content_new, LOCK_EX);


                $ret[]= $fileName;
            }
            else  //Multiple files, file[]
            {
              $fileCount = count($_FILES["myfile"]["name"]);
              for($i=0; $i < $fileCount; $i++)
              {
                $fileName = $_FILES["myfile"]["name"][$i];
                move_uploaded_file($_FILES["myfile"]["tmp_name"][$i],$output_dir.$fileName);

                $file_content=file_get_contents("upload/js/".$fileName);
                $file_content_new=$this->code_minifier_library->minify_js($file_content);
                file_put_contents("upload/js/".$fileName, $file_content_new, LOCK_EX);

                $ret[]= $fileName;
              }
            
            }
            echo json_encode($ret);

        }
    }

    public function delete_js()
    {
        $output_dir = FCPATH."upload/js/";
        if(isset($_POST["op"]) && $_POST["op"] == "delete" && isset($_POST['name']))
        {
            $fileName =$_POST['name'];
            $fileName=str_replace("..",".",$fileName); //required. if somebody is trying parent folder files    
            $filePath = $output_dir. $fileName;
            if (file_exists($filePath)) 
            {
                unlink($filePath);
            }
            echo "Deleted File ".$fileName."<br>";
        }
    }


    public function download_js($fileName='')
    {
        $fileName = urldecode($fileName);
        if($fileName!='')
        {
            $fileName=str_replace("..",".",$fileName); //required. if somebody is trying parent folder files
            $file = FCPATH."upload/js/".$fileName;
            $file = str_replace("..","",$file);
            if (file_exists($file)) {
                $fileName =str_replace(" ","",$fileName);
                header('Content-Description: File Transfer');
                header('Content-Disposition: attachment; filename='.$fileName);
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                // ob_clean();
                // flush();
                readfile($file);
                unlink($file);
                exit;
            }
        }
    }

    public function read_text_file_js()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') exit();

        $ret=array();
        $output_dir = FCPATH."upload/js";
        if (isset($_FILES["myfile"])) {
            $error =$_FILES["myfile"]["error"];
            $post_fileName =$_FILES["myfile"]["name"];
            $post_fileName_array=explode(".", $post_fileName);
            $ext=array_pop($post_fileName_array);
            $filename=implode('.', $post_fileName_array);
            $filename="css_code_minify".$this->user_id."_".time().substr(uniqid(mt_rand(), true), 0, 6).".".$ext;

            $allow=".js";
            $allow=str_replace('.', '', $allow);
            $allow=explode(',', $allow);

            if(!in_array(strtolower($ext), $allow)) 
            {
                echo json_encode(array("are_u_kidding_me" => "yarki"));
                exit();
            }

            
            move_uploaded_file($_FILES["myfile"]["tmp_name"], $output_dir.'/'.$filename);

            $path = realpath(FCPATH."upload/js/".$filename);
            $read_handle=fopen($path, "r");
            $context_array =array('file_name'=>$filename);
            $context ="";
            while (!feof($read_handle)) 
            {
                $information = fgetcsv($read_handle);
                if (!empty($information)) 
                {
                    foreach ($information as $info) 
                    {
                        if (!is_numeric($info)) 
                        $context.=$info."\n";                       
                    }
                }
            }

            $context_array['content'] = trim($context, "\n");
            echo json_encode($context_array);
            
        }
    }

    public function read_after_delete_js() // deletes the uploaded video to upload another one
    {
        if(!$_POST) exit();
       
        $output_dir = FCPATH."upload/js/";
        if(isset($_POST["op"]) && $_POST["op"] == "delete" && isset($_POST['name']))
        {
             $fileName =$_POST['name'];
             $fileName=str_replace("..",".",$fileName); //required. if somebody is trying parent folder files
             $filePath = $output_dir. $fileName;
             if (file_exists($filePath))
             {
                unlink($filePath);
             }
        }
    }



}