<section class="section">
	<div class="section-header">
		<h1><i class="fas fa-toolbox"></i> <?php echo $page_title; ?></h1>
		<div class="section-header-breadcrumb">
			<div class="breadcrumb-item"><?php echo $this->lang->line("System"); ?></div>
			<div class="breadcrumb-item active"><a href="<?php echo base_url('admin/settings'); ?>"><?php echo $this->lang->line("Settings"); ?></a></div>
			<div class="breadcrumb-item"><?php echo $page_title; ?></div>
		</div>
	</div>

	<?php $this->load->view('admin/theme/message'); ?>

	<?php $save_button = '<div class="card-footer bg-whitesmoke">
	                      <button class="btn btn-primary btn-lg" id="save-btn" type="submit"><i class="fas fa-save"></i> '.$this->lang->line("Save").'</button>
	                      <button class="btn btn-secondary btn-lg float-right" onclick=\'goBack("admin/settings")\' type="button"><i class="fa fa-remove"></i> '. $this->lang->line("Cancel").'</button>
	                    </div>'; ?>
	
	<form class="form-horizontal text-c" enctype="multipart/form-data" action="<?php echo site_url().'admin/general_settings_action';?>" method="POST">	
		<input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo $this->session->userdata('csrf_token_session'); ?>">
		<div class="section-body">
			<div id="output-status"></div>
			<div class="row">
				<div class="col-md-8">					
					<div class="card" id="brand">

						<div class="card-header">
							<h4><i class="fas fa-flag"></i> <?php echo $this->lang->line("Brand"); ?></h4>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for=""><i class="fa fa-globe"></i> <?php echo $this->lang->line("Application Name");?> </label>
										<input name="product_name" value="<?php echo $this->config->item('product_name');?>"  class="form-control" type="text">		          
										<span class="red"><?php echo form_error('product_name'); ?></span>
									</div>
								</div>
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for=""><i class="fa fa-compress"></i> <?php echo $this->lang->line("Application Short Name");?> </label>
										<input name="product_short_name" value="<?php echo $this->config->item('product_short_name');?>"  class="form-control" type="text">
										<span class="red"><?php echo form_error('product_short_name'); ?></span>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label for=""><i class="fas fa-tag"></i> <?php echo $this->lang->line("Slogan");?> </label>
								<input name="slogan" value="<?php echo $this->config->item('slogan');?>"  class="form-control" type="text">
								<span class="red"><?php echo form_error('slogan'); ?></span>
							</div>

							<div class="row">
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for=""><i class="fa fa-briefcase"></i> <?php echo $this->lang->line("Company Name");?></label>
										<input name="institute_name" value="<?php echo $this->config->item('institute_address1');?>"  class="form-control" type="text">	
										<span class="red"><?php echo form_error('institute_name'); ?></span>
									</div>
								</div>

								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for=""><i class="fa fa-map-marker"></i> <?php echo $this->lang->line("Company Address");?></label>
										<input name="institute_address" value="<?php echo $this->config->item('institute_address2');?>"  class="form-control" type="text">
										<span class="red"><?php echo form_error('institute_address'); ?></span>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for=""><i class="fa fa-envelope"></i> <?php echo $this->lang->line("Company Email");?> *</label>
										<input name="institute_email" value="<?php echo $this->config->item('institute_email');?>"  class="form-control" type="email">
										<span class="red"><?php echo form_error('institute_email'); ?></span>
									</div>  
								</div>

								<div class="col-12 col-md-6">	
									<div class="form-group">
										<label for=""><i class="fa fa-mobile"></i> <?php echo $this->lang->line("Company Phone");?></label>
										<input name="institute_mobile" value="<?php echo $this->config->item('institute_mobile');?>"  class="form-control" type="text">
										<span class="red"><?php echo form_error('institute_mobile'); ?></span>
									</div>
								</div>
							</div>
						</div>
						<?php echo $save_button; ?>
					</div>

					<div class="card" id="preference">
						<div class="card-header">
							<h4><i class="fas fa-tasks"></i> <?php echo $this->lang->line("Preference"); ?></h4>
						</div>
						<div class="card-body">

				            <div class="row">
								<div class="col-12 col-md-6">
									<div class="form-group">
						             	<label for=""><i class="fa fa-language"></i> <?php echo $this->lang->line("Language");?></label>            			
				               			<?php
										$select_lan="english";
										if($this->config->item('language')!="") $select_lan=$this->config->item('language');
										echo form_dropdown('language',$language_info,$select_lan,'class="form-control select2" id="language"');  ?>		          
				             			<span class="red"><?php echo form_error('language'); ?></span>
						            </div>
						        </div>

						        <div class="col-12 col-md-6">
						            <div class="form-group">
						             	<label for=""><i class="fa fa-clock-o"></i> <?php echo $this->lang->line("Time Zone");?></label>          			
				               			<?php	$time_zone['']=$this->lang->line('Time Zone');
										echo form_dropdown('time_zone',$time_zone,$this->config->item('time_zone'),'class="form-control select2" id="time_zone"');  ?>		          
				             			<span class="red"><?php echo form_error('time_zone'); ?></span>
						            </div>
						        </div>
					        </div>						
						   

				            <div class="form-group">
				             	<label for="email_sending_option"><i class="fa fa-at"></i> <?php echo $this->lang->line('Email Sending Option');?></label> 
		               			<?php
		               			$email_sending_option= $this->config->item('email_sending_option');
		               			if($email_sending_option == '') $email_sending_option = 'php_mail';
		               			?>
								<div class="row">
									<div class="col-12 col-md-6">
										<label class="custom-switch">
										  <input type="radio" name="email_sending_option" value="php_mail" class="custom-switch-input" <?php if($email_sending_option=='php_mail') echo 'checked'; ?>>
										  <span class="custom-switch-indicator"></span>
										  <span class="custom-switch-description"><?php echo $this->lang->line('Use PHP Email Function'); ?></span>
										</label>
									</div>
									<div class="col-12 col-md-6">
										<label class="custom-switch">
										  <input type="radio" name="email_sending_option" value="smtp" class="custom-switch-input" <?php if($email_sending_option=='smtp') echo 'checked'; ?>>
										  <span class="custom-switch-indicator"></span>
										  <span class="custom-switch-description"><?php echo $this->lang->line('Use SMTP Email'); ?>
										  	&nbsp;:&nbsp;<a href="<?php echo base_url('admin/smtp_settings');?>" class="float-right"> <?php echo $this->lang->line("SMTP Setting"); ?> </a></span>
										</label>
									</div>
								</div>
		             			<span class="red"><?php echo form_error('email_sending_option'); ?></span>
				            </div>

   						    <div class="row">
   						        <div class="col-12 col-md-6">
   						        	<div class="form-group">
   						        	  <?php	
   						        	  $force_https = $this->config->item('force_https');
   						        	  if($force_https == '') $force_https='0';
   						        	  ?>
   						        	  <label class="custom-switch mt-2">
   						        	    <input type="checkbox" name="force_https" value="1" class="custom-switch-input"  <?php if($force_https=='1') echo 'checked'; ?>>
   						        	    <span class="custom-switch-indicator"></span>
   						        	    <span class="custom-switch-description"><?php echo $this->lang->line('Force HTTPS');?>?</span>
   						        	    <span class="red"><?php echo form_error('force_https'); ?></span>
   						        	  </label>
   						        	</div>
   						        </div>

   					           	<div class="col-12 col-md-6">
   					           		<div class="form-group">
   					           		  <?php	
   					           		  $enable_signup_form = $this->config->item('enable_signup_form');
           		               			if($enable_signup_form == '') $enable_signup_form='1';
   					           		  ?>
   					           		  <label class="custom-switch mt-2">
   					           		    <input type="checkbox" name="enable_signup_form" value="1" class="custom-switch-input"  <?php if($enable_signup_form=='1') echo 'checked'; ?>>
   					           		    <span class="custom-switch-indicator"></span>
   					           		    <span class="custom-switch-description"><?php echo $this->lang->line('Display Signup Page');?></span>
   					           		    <span class="red"><?php echo form_error('enable_signup_form'); ?></span>
   					           		  </label>
   					           		</div>        				           	
   					            </div>
   					        </div>

   					        <div class="row">
   						        <div class="col-12 col-md-6">
   						        	<div class="form-group">
   						        	  <?php	
   						        	  $use_admin_app = $this->config->item('use_admin_app');
   						        	  if($use_admin_app == '') $use_admin_app='no';
   						        	  ?>
   						        	  <label class="custom-switch mt-2">
   						        	    <input type="checkbox" name="use_admin_app" value="yes" class="custom-switch-input"  <?php if($use_admin_app=='yes') echo 'checked'; ?>>
   						        	    <span class="custom-switch-indicator"></span>
   						        	    <span class="custom-switch-description"><?php echo $this->lang->line("Give admin's API access to users.");?></span>
   						        	    <span class="red"><?php echo form_error('use_admin_app'); ?></span>
   						        	  </label>
   						        	</div>
   						        </div>
   					        </div>

						</div>
						<?php echo $save_button; ?>
					</div>

					<div class="card" id="logo-favicon">
						<div class="card-header">
							<h4><i class="fas fa-images"></i> <?php echo $this->lang->line("Logo & Favicon"); ?></h4>
						</div>
						<div class="card-body">			             	

			             	<div class="row">
			             		<div class="col-6">
 					             	<label for=""><i class="fas fa-image"></i> <?php echo $this->lang->line("logo");?> (png)</label>
 					             	<div class="custom-file">
 			                            <input type="file" name="logo" class="custom-file-input">
 			                            <label class="custom-file-label"><?php echo $this->lang->line("Choose File"); ?></label>
 			                            <small><?php echo $this->lang->line("Max Dimension");?> : 700 x 200, <?php echo $this->lang->line("Max Size");?> : 500KB </small>	          
 			                            <span class="red"> <?php echo $this->session->userdata('logo_error'); $this->session->unset_userdata('logo_error'); ?></span>
 			                         </div>
			             		</div>
			             		<div class="col-6 my-auto text-center">
			             			<img class="img-fluid" src="<?php echo base_url().'assets/img/logo.png';?>" alt="Logo"/>
			             		</div>
			             	</div>

			             	<div class="row">
			             		<div class="col-6">
 					             	<label for=""><i class="fas fa-portrait"></i> <?php echo $this->lang->line("Favicon");?> (png)</label>
 					             	<div class="custom-file">
 			                            <input type="file" name="favicon" class="custom-file-input">
 			                            <label class="custom-file-label"><?php echo $this->lang->line("Choose File"); ?></label>
 			                            <small><?php echo $this->lang->line("Dimension");?> : 100 x 100, <?php echo $this->lang->line("Max Size");?> : 50KB </small>	          
 			                            <span class="red"> <?php echo $this->session->userdata('favicon_error'); $this->session->unset_userdata('favicon_error'); ?></span>
 			                         </div>
			             		</div>
			             		<div class="col-6 my-auto text-center">
			             			<img class="img-fluid" src="<?php echo base_url().'assets/img/favicon.png';?>" alt="Favicon" style="max-width:50px;"/>
			             		</div>
			             	</div>
						</div>
						<?php echo $save_button; ?>
					</div>

					<div class="card" id="master-password">
						<div class="card-header">
							<h4><i class="fab fa-keycdn"></i> <?php echo $this->lang->line("Master Password"); ?></h4>
						</div>
						<div class="card-body">
				           <div class="form-group">
				             	<label for=""><i class="fa fa-key"></i> <?php echo $this->lang->line("Master Password (will be used for login as user)");?></label>
		               			<input name="master_password" value="******"  class="form-control" type="text">
		             			<span class="red"><?php echo form_error('master_password'); ?></span>
				           </div>
						   <div class="row d-none">
						        <div class="col-12 col-md-6">
						        	<div class="form-group">
						        	  <?php	
						        	  $backup_mode = $this->config->item('backup_mode');
						        	  if($backup_mode == '') $backup_mode='0';
						        	  ?>
						        	  <label class="custom-switch mt-2">
						        	    <input type="checkbox" name="backup_mode" value="1" class="custom-switch-input"  <?php if($backup_mode=='1') echo 'checked'; ?>>
						        	    <span class="custom-switch-indicator"></span>
						        	    <span class="custom-switch-description"><?php echo $this->lang->line('Give access to user to set their own Facebook APP');?>?</span>
						        	    <span class="red"><?php echo form_error('backup_mode'); ?></span>
						        	  </label>
						        	</div>
						        </div>

					           	<!-- <div class="col-12 col-md-6">
					           		<div class="form-group">
					           		  <?php	
					           		  $developer_access = $this->config->item('developer_access');
	   		               			if($developer_access == '') $developer_access='0';
					           		  ?>
					           		  <label class="custom-switch mt-2">
					           		    <input type="checkbox" name="developer_access" value="1" class="custom-switch-input"  <?php if($developer_access=='1') echo 'checked'; ?>>
					           		    <span class="custom-switch-indicator"></span>
					           		    <span class="custom-switch-description"><?php echo $this->lang->line('Use Approved Facebook App of Author?');?> </span>
					           		    <a href="#" data-placement="top"  data-html="true" data-toggle="popover" data-trigger="focus" title="<?php echo $this->lang->line("Use Approved Facebook App of Author?") ?>" data-content="<?php echo $this->lang->line("If you select Yes, you may skip to add your own app. You can use Author's app. But this option only for the admin only. This can't be used for other system users. User management feature will be disapeared."); ?><br><br><?php echo $this->lang->line("If select No , you will need to add your own app & get approval and system users can use it.");?>"><i class='fa fa-info-circle'></i> </a>

					           		    <span class="red"><?php echo form_error('developer_access'); ?></span>
					           		    
					           		  </label>
					           		</div>        				           	
					            </div> -->
					        </div>
						</div>
						<?php echo $save_button; ?>
					</div>

				

					<?php if($this->session->userdata('license_type') == 'double') { ?>
					<div class="card" id="support-desk">
						<div class="card-header">
							<h4><i class="fas fa-headset"></i> <?php echo $this->lang->line("Support Desk"); ?></h4>
						</div>
						<div class="card-body">
			           		<div class="form-group">
			           		  <?php	
		               			$enable_support = $this->config->item('enable_support');
		               			if($enable_support == '') $enable_support='1';
		               		  ?>
			           		  <label class="custom-switch mt-2">
			           		    <input type="checkbox" name="enable_support" value="1" class="custom-switch-input"  <?php if($enable_support=='1') echo 'checked'; ?>>
			           		    <span class="custom-switch-indicator"></span>
			           		    <span class="custom-switch-description"><?php echo $this->lang->line('Enable Support Desk for Users');?></span>
			           		    <span class="red"><?php echo form_error('enable_support'); ?></span>
			           		  </label>
			           		</div>
						</div>
						<?php echo $save_button; ?>
					</div>
					<?php } ?>

					<div class="card" id="file-upload">
						<div class="card-header">
							<h4><i class="fas fa-cloud-upload-alt"></i> <?php echo $this->lang->line("File Upload"); ?></h4>
						</div>
						<div class="card-body">
			           		<div class="form-group">
			           			<label for=""><i class="fas fa-file"></i> <?php echo $this->lang->line("File Upload Limit (MB)");?></label>
		             			<?php 
			             			$xeroseo_file_upload_limit=$this->config->item('xeroseo_file_upload_limit');
			             			if($xeroseo_file_upload_limit=="") $xeroseo_file_upload_limit=4; 
		             			?>
		               			<input name="xeroseo_file_upload_limit" value="<?php echo $xeroseo_file_upload_limit;?>"  class="form-control" type="number" min="1">
		               			<span class="red"><?php echo form_error('xeroseo_file_upload_limit'); ?></span>	
			           		</div>
						</div>
						<?php echo $save_button; ?>
					</div>	

					<div class="card" id="junk_data">
						<div class="card-header">
							<h4><i class="fas fa-trash-alt"></i> <?php echo $this->lang->line("Junk Data Deletion"); ?></h4>
						</div>
						<div class="card-body">				       
			              <div class="row">
			              		<div class="col-12">
	 				              	<div class="form-group">
	 					             	<label for=""><i class="fa fa-calendar"></i> <?php echo $this->lang->line("Visitor analytics older data, log/cache data after how many days?");?></label>
				             			<?php 
					             			$delete_junk_data_after_how_many_days=$this->config->item('delete_junk_data_after_how_many_days');
					             			if($delete_junk_data_after_how_many_days=="") $delete_junk_data_after_how_many_days=30; 
				             			?>
	 			               			<input name="delete_junk_data_after_how_many_days" value="<?php echo $delete_junk_data_after_how_many_days;?>"  class="form-control" type="number" min="1">          
	 			             			<span class="red"><?php echo form_error('delete_junk_data_after_how_many_days'); ?></span>
	 					            </div>
			              		</div>
			              	</div>
						</div>
						<?php echo $save_button; ?>
					</div>	

					<div class="card" id="server-status">
						<div class="card-header">
							<h4><i class="fas fa-server"></i> <?php echo $this->lang->line("Server Status"); ?></h4>
						</div>
						<div class="card-body">
							<?php

							$sql="SHOW VARIABLES;";
				            $mysql_variables=$this->basic->execute_query($sql);
				            $variables_array_format=array();
				            foreach($mysql_variables as $my_var){
				                $variables_array_format[$my_var['Variable_name']]=$my_var['Value'];
				            }
				            $disply_index = array("version","innodb_version","innodb_log_file_size","wait_timeout","max_connections","connect_timeout","max_allowed_packet");

							$list1=$list2="";						  
						    $make_dir = (!function_exists('mkdir')) ? $this->lang->line("Disabled"):$this->lang->line("Enabled");
						    $zip_archive = (!class_exists('ZipArchive')) ? $this->lang->line("Disabled"):$this->lang->line("Enabled");
						    $list1 .= "<li class='list-group-item'><b>mkdir</b> : ".$make_dir."</li>"; 
						    $list2 .= "<li class='list-group-item'><b>ZipArchive</b> : ".$zip_archive."</li>"; 

						    if(function_exists('curl_version'))	$curl="Enabled";								    
							else $curl="Disabled";

							if(function_exists('mb_detect_encoding')) $mbstring="Enabled";								    
							else $mbstring="Disabled";

							if(function_exists('set_time_limit')) $set_time_limit="Enabled";								    
							else $set_time_limit="Disabled";

							if(function_exists('exec')) $exec="Enabled";								    
							else $exec="Disabled";

							$list2 .= "<li class='list-group-item'><b>curl</b> : ".$curl."</li>";
						    $list1 .= "<li class='list-group-item'><b>exec</b> : ".$exec."</li>"; 
							$list2 .= "<li class='list-group-item'><b>mb_detect_encoding</b> : ".$mbstring."</li>"; 
							$list2 .= "<li class='list-group-item'><b>set_time_limit</b> : ".$set_time_limit."</li>"; 


						    if(function_exists('ini_get'))
							{								 
								if( ini_get('safe_mode') )
							    $safe_mode="ON, please set safe_mode=off";								    
							    else $safe_mode="OFF";

							    if( ini_get('open_basedir')=="")
							    $open_basedir="No Value";								    
							    else $open_basedir="Has value";

							    if( ini_get('allow_url_fopen'))
							    $allow_url_fopen="TRUE";								    
							    else $allow_url_fopen="FALSE";

							    $list1 .= "<li class='list-group-item'><b>safe_mode</b> : ".$safe_mode."</li>"; 
							    $list2 .= "<li class='list-group-item'><b>open_basedir</b> : ".$open_basedir."</li>"; 
							    $list1 .= "<li class='list-group-item'><b>allow_url_fopen</b> : ".$allow_url_fopen."</li>";	
								$list1 .= "<li class='list-group-item'><b>upload_max_filesize</b> : ".ini_get('upload_max_filesize')."</li>";   
						    	$list1 .= "<li class='list-group-item'><b>max_input_time</b> : ".ini_get('max_input_time')."</li>";
					       		$list2 .= "<li class='list-group-item'><b>post_max_size</b> : ".ini_get('post_max_size')."</li>"; 
						    	$list2 .= "<li class='list-group-item'><b>max_execution_time</b> : ".ini_get('max_execution_time')."</li>";
													    
							}						       

					        $php_version = (function_exists('ini_get') && phpversion()!=FALSE) ? phpversion() : ""; ?>							

						    <div class="row">
							  	<div class="col-12 col-lg-6">								  		
									<ul class="list-group">
										<li class='list-group-item active'>PHP</li>  
							  			<li class='list-group-item'><b>PHP version : </b> <?php echo $php_version; ?></li>   
										<?php echo $list1; ?>
									</ul>
							  	</div>
							  	<div class="col-12 col-lg-6">
							  		<ul class="list-group">
							  			<li class='list-group-item active'>PHP</li>
							  			<?php echo $list2; ?>
									</ul>
							  	</div>
							  	<div class="col-12">
							  		<br>
							  		<ul class="list-group">
							  			<li class='list-group-item active'>MySQL</li>  
							  			
							  			<?php 
							  			foreach ($disply_index as $value) 
							  			{
							  				if(isset($variables_array_format[$value]))
							  				echo "<li class='list-group-item'><b>".$value."</b> : ".$variables_array_format[$value]."</li>";  
							  			} 
							  			?>
									</ul>
							  	</div>

						    </div>
							  	
						</div>
					</div>	
				</div>

				<div class="col-md-4 d-none d-sm-block">
					<div class="sidebar-item">
						<div class="make-me-sticky">
							<div class="card">
								<div class="card-header">
									<h4><i class="fas fa-columns"></i> <?php echo $this->lang->line("Sections"); ?></h4>
								</div>
								<div class="card-body">
									<ul class="nav nav-pills flex-column settings_menu">
										<li class="nav-item"><a href="#brand" class="nav-link"><i class="fas fa-flag"></i> <?php echo $this->lang->line("Brand"); ?></a></li>
										<li class="nav-item"><a href="#preference" class="nav-link"><i class="fas fa-tasks"></i> <?php echo $this->lang->line("Preference"); ?></a></li>
										<li class="nav-item"><a href="#logo-favicon" class="nav-link"><i class="fas fa-images"></i> <?php echo $this->lang->line("Logo & Favicon"); ?></a></li>
										<li class="nav-item"><a href="#master-password" class="nav-link"><i class="fab fa-keycdn"></i> <?php echo $this->lang->line("Master Password"); ?></a></li>
									

										<?php if($this->session->userdata('license_type') == 'double') { ?>
										<li class="nav-item"><a href="#support-desk" class="nav-link"><i class="fas fa-headset"></i> <?php echo $this->lang->line("Support Desk"); ?></a></li>
										<?php } ?>
										<li class="nav-item"><a href="#file-upload" class="nav-link"><i class="fas fa-cloud-upload-alt"></i> <?php echo $this->lang->line("File Upload"); ?></a></li>
										<li class="nav-item"><a href="#junk_data" class="nav-link"><i class="fas fa-trash-alt"></i> <?php echo $this->lang->line("Delete Junk Data"); ?></a></li>
										<li class="nav-item"><a href="#server-status" class="nav-link"><i class="fas fa-server"></i> <?php echo $this->lang->line("Server Status"); ?></a></li>								
									</ul>
								</div>						
							</div>
							
						</div>
					</div>
				</div>				
			</div>
		</div>
	</form>
</section>


<script type="text/javascript">
  $('document').ready(function(){
    $(".settings_menu a").click(function(){
    	$(".settings_menu a").removeClass("active");
    	$(this).addClass("active");
    });
  });
</script>
<script>
	$('[data-toggle="popover"]').popover();
	$('[data-toggle="popover"]').on('click', function(e) {e.preventDefault(); return true;});
</script>