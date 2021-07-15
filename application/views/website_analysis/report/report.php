<?php 
echo "<!DOCTYPE html><html><head>";
echo "<title>".$domain_info[0]['domain_name']." ".$this->lang->line('Anlaysis Report')." | ".$this->config->item("product_name")."</title>";
echo "<link rel='shortcut icon' href='".base_url()."'assets/img/favicon.png'>";
include("application/views/website_analysis/report/report_css_js.php");
echo "</head><body><div class='row'>";

$path = 'assets/img/logo.png';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
echo "<div class='text-center'><img style='width:200px;' src='".$base64."' alt='".$this->config->item("institute_address1")."'></div>";
echo "<h3 class='text-center'>".$this->config->item("institute_address1")."</h3>";
if($this->config->item("institute_address2")!="")
echo "<h6 class='text-center'>Address: ".$this->config->item("institute_address2")."</h6>";
echo "<h6 class='text-center'>Contact: ".$this->config->item("institute_email");
echo " | ".$this->config->item("institute_mobile");
echo "</h6><h6 class='text-center'>Website: <a href=".site_url()." target='_BLANK'>".site_url()."</a></h6>";
echo "<h6 class='text-center'>Generated At: ".$domain_info[0]['search_at']."</h6></div>";

?>

<!-- ******************************** general sectio *********************************** -->
<style type="text/css">
	*,body{margin:0;padding:0;box-sizing:border-box;background-color:#f4f6f9}
	.card{box-shadow:0 4px 8px rgba(0,0,0,.03);background-color:#fff;border-radius:3px;border:none;position:relative;margin-bottom:30px}
	.card .card-body,.card .card-header{background-color:transparent;padding:20px 25px}
	.card .card-body{padding-top:20px;padding-bottom:20px}
	.card .card-header{border-bottom-color:#f9f9f9;line-height:30px;align-self:center;width:100%;min-height:70px;padding:15px 25px;display:flex;align-items:center}
	.card .card-header h4{font-size:16px;line-height:28px;color:#6777ef;padding-right:10px;margin-bottom:0}
	.col-6,.col-md-6{width:49%!important;margin:0;padding:0}
	.col-12{width:100%!important;text-align:center!important}
	.pdf-list-group .pdf-list-group-item-active{background-color:#6777ef!important;border-color:#6777ef!important;color:#fff;padding:10px;font-weight:700;font-size: 14px !important;padding-left:14px !important;}
	.list-group-item{list-style:none;color:#6c757d}
	.list-group-item b{font-size:13px}
	.table tbody tr td,.table tbody tr th,.table thead tr td,.table thead tr th{padding:5px 10px;text-align:center}
	.table thead tr th{color:#6777ef}
	.table tbody tr td{color:#6c757d}
	.table-styles{font-weight:700;color:#191919;border:solid 1px #e1e1e1;padding:10px;background-color:#e1e1e1;list-style:none!important;margin-top:10px;font-style:italic;letter-spacing:1px}
	.well{margin-bottom:10px;background-color:#eee;border-color:#eee;color:#6c757d}
</style>
	
<div id="hide_after_ajax">
	<div class="row">
		<div class="col-12">
			<h3><div class="well text-center"><?php echo "Domain Name - ".$domain_info[0]['domain_name']; ?></div></h3>
		</div>
	</div>

	<div class="row">
		<div class="col-md-6" style="float: left;">
			<ul class="list-group pdf-list-group">
				<li class="list-group-item pdf-list-group-item-active"><?php echo $this->lang->line('WhoIs Information'); ?></li>  

				<li class="list-group-item"><b><?php echo $this->lang->line('Registered'); ?> : </b>
					<?php 
						if($domain_info[0]['whois_is_registered'] == 'yes') $is_registered = $this->lang->line('Yes'); 
						else $is_registered = $this->lang->line('No');

						echo $is_registered; ?>
				</li>

				<li class="list-group-item"><b><?php echo $this->lang->line('Domain Age'); ?> : </b>
					<?php
						if($domain_info[0]['whois_created_at'] != '0000-00-00'){									
							$end = date("Y-m-d");
							$start = date("Y-m-d",strtotime($domain_info[0]['whois_created_at']));
						} else {
							$end = $domain_info[0]['whois_created_at'] ;
							$start = $domain_info[0]['whois_created_at'] ;
						}

					 	echo calculate_date_differece($end,$start); ?>
				</li>

				<li class="list-group-item"><b><?php echo $this->lang->line('Tech Email'); ?> : </b>
					<?php echo $domain_info[0]['whois_tech_email']; ?>
				</li>

				<li class="list-group-item"><b><?php echo $this->lang->line('Name Servers'); ?> : </b>
					<?php echo $domain_info[0]['whois_name_servers']; ?>
				</li>

				<li class="list-group-item"><b><?php echo $this->lang->line('Created At'); ?> : </b>
					<?php if($domain_info[0]['whois_created_at'] != '0000-00-00') echo date("d-M-Y",strtotime($domain_info[0]['whois_created_at'])); ?>
				</li>
				<li class="list-group-item"><b><?php echo $this->lang->line('Changed At'); ?> : </b>
					<?php if($domain_info[0]['whois_changed_at'] != '0000-00-00') echo date("d-M-Y",strtotime($domain_info[0]['whois_changed_at'])); ?>
				</li>
				<li class="list-group-item"><b><?php echo $this->lang->line('Expire At'); ?> : </b>
					<?php if($domain_info[0]['whois_expire_at'] != '0000-00-00') echo date("d-M-Y",strtotime($domain_info[0]['whois_expire_at'])); ?>
				</li>

				<li class="list-group-item"><b><?php echo $this->lang->line('Registrant Name'); ?> : </b>
					<?php echo $domain_info[0]['whois_registrant_name']; ?>
				</li>
				<li class="list-group-item"><b><?php echo $this->lang->line('Admin Name'); ?> : </b>
					<?php echo $domain_info[0]['whois_admin_name']; ?>
				</li>

				<li class="list-group-item"><b><?php echo $this->lang->line('Registrant Country'); ?> : </b>
					<img style="height: 15px; width: 20px; margin-top: -3px;" alt=" " src="<?php echo base_url().'assets/images/flags/'.$domain_info[0]['whois_registrant_country'].'.png' ?>" >&nbsp;<?php echo $domain_info[0]['whois_registrant_country']; ?>
				</li>
				
				<li class="list-group-item"><b><?php echo $this->lang->line('Admin Country'); ?> : </b>
					<img style="height: 15px; width: 20px; margin-top: -3px;" alt=" " src="<?php echo base_url().'assets/images/flags/'.$domain_info[0]['whois_admin_country'].'.png' ?>" >&nbsp;<?php echo $domain_info[0]['whois_admin_country']; ?>
				</li>
				
				<li class="list-group-item"><b><?php echo $this->lang->line('Registrant Phone'); ?> : </b>
					<?php echo $domain_info[0]['whois_registrant_phone']; ?>
				</li>
				
				<li class="list-group-item"><b><?php echo $this->lang->line('Admin Phone'); ?> : </b>
					<?php echo $domain_info[0]['whois_admin_phone']; ?>
				</li>
			</ul>	
		</div>
		
		<!-- Moz & Link info -->
		<div class="col-md-6" style="float: right;">
			<ul class="list-group pdf-list-group">
				<li class="list-group-item pdf-list-group-item-active"><?php echo $this->lang->line('MOZ Information'); ?></li>
				<li class="list-group-item">
					<b><?php echo $this->lang->line('Subdomain Normalized'); ?> : </b>
					<?php echo $domain_info[0]['moz_subdomain_normalized']; ?>
				</li>
				<li class="list-group-item">
					<b><?php echo $this->lang->line('Subdomain Raw'); ?> : </b>
					<?php echo $domain_info[0]['moz_subdomain_raw']; ?>
				</li>
				<li class="list-group-item">
					<b><?php echo $this->lang->line('URL Normalized'); ?> : </b>
					<?php echo $domain_info[0]['moz_url_normalized']; ?>
				</li>
				<li class="list-group-item">
					<b><?php echo $this->lang->line('URL Raw'); ?> : </b>
					<?php echo $domain_info[0]['moz_url_raw']; ?>
				</li>
				<li class="list-group-item">
					<b><?php echo $this->lang->line('HTTP Status Code'); ?> : </b>
					<?php echo $domain_info[0]['moz_http_status_code']; ?>
				</li>
				<li class="list-group-item">
					<b><?php echo $this->lang->line('Domain Authority'); ?> : </b>
					<?php echo $domain_info[0]['moz_domain_authority']; ?>
				</li>
				<li class="list-group-item">
					<b><?php echo $this->lang->line('Page Authority'); ?> : </b>
					<?php echo $domain_info[0]['moz_page_authority']; ?>
				</li>
				<li class="list-group-item">
					<b><?php echo $this->lang->line('External Quality Link'); ?> : </b>
					<?php echo $domain_info[0]['moz_external_equity_links']; ?>
				</li>
				<li class="list-group-item">
					<b><?php echo $this->lang->line('Links'); ?> : </b>
					<?php echo $domain_info[0]['moz_links']; ?>
				</li>
			</ul>

			<ul class="list-group pdf-list-group">
				<li class="list-group-item pdf-list-group-item-active"><?php echo $this->lang->line('Link Information'); ?></li>
				<li class="list-group-item">
					<b><?php echo $this->lang->line('BackLink Count'); ?> : </b>
					<?php echo $domain_info[0]['google_back_link_count']; ?>
				</li>
				<li class="list-group-item">
					<b><?php echo $this->lang->line('Total Link Count'); ?> : </b>
					<?php 
						$total_link_count=$domain_info[0]['moz_links'];
						if($total_link_count=="") $total_link_count=0;
					 	echo number_format($total_link_count); 
					?>
				</li>
				<li class="list-group-item">
					<b><?php echo $this->lang->line('MozRank'); ?> : </b>
					<?php echo $domain_info[0]['moz_url_normalized']; ?>
				</li>
			</ul>	
		</div>

		<div class="col-12" style="text-align:left;">
			<?php if ($domain_info[0]['screenshot'] != ""): ?>
				<img src='<?php echo $domain_info[0]['screenshot']; ?>' class='img-responsive' style='border-radius: 0px;width:100%' />
			<?php endif; ?>
		</div>
	</div>

	<div class="row">
		<div class="card" style="box-shadow: none;margin-top:10px;">
			<div class="card-header" style="border-bottom: 1px solid #f1f1f1; text-align: left;background-color: #007bff;">
				<h4 style="color: #fff;"><?php echo $this->lang->line('Mobile Friendly Check'); ?></h4>
			</div>

			<div class="card-body">
				<?php 							

				$lighthouseresult_categories = json_decode($domain_info[0]['lighthouseresult_categories'],true);
				// $final_score = isset($lighthouseresult_categories['performance']['score']) ? $lighthouseresult_categories['performance']['score']*100 : 0;

				$lighthouseresult_configsettings = json_decode($domain_info[0]['lighthouseresult_configsettings'],true);

				$lighthouseresult_audits = json_decode($domain_info[0]['lighthouseresult_audits'],true);

				$first_meaningful_paing = isset($lighthouseresult_audits['first-meaningful-paint']['score']) ? $lighthouseresult_audits['first-meaningful-paint']['score'] : 0;
				$speed_index = isset($lighthouseresult_audits['speed-index']['score']) ? $lighthouseresult_audits['speed-index']['score'] : 0;
				$first_cpu_idle = isset($lighthouseresult_audits['first-cpu-idle']['score']) ? $lighthouseresult_audits['first-cpu-idle']['score'] : 0;
				$first_contentful_paint = isset($lighthouseresult_audits['first-contentful-paint']['score']) ? $lighthouseresult_audits['first-contentful-paint']['score'] : 0;
				$interactive = isset($lighthouseresult_audits['interactive']['score']) ? $lighthouseresult_audits['interactive']['score'] : 0;

				$final_score = ($first_meaningful_paing*7)+($speed_index*27)+($first_cpu_idle*13)+($first_contentful_paint*20)+($interactive*33);
				                    
				$loadingexperience_metrics = json_decode($domain_info[0]['loadingexperience_metrics'],true);					   	
				$originloadingexperience_metrics = json_decode($domain_info[0]['originloadingexperience_metrics'],true);	

				?>
					
			<?php if (empty($lighthouseresult_categories)): ?>
				<div class="alert alert-warning alert-has-icon">
				  <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
				  <div class="alert-body" style="word-break: break-word">
				    <div class="alert-title"><?php echo $this->lang->line("Warning"); ?></div>
				    <?php echo isset($domain_info[0]['google_api_error']) ? $domain_info[0]['google_api_error'] : ""; ?><br>
				    <a target='_BLANK' href="https://console.developers.google.com/apis/library"><?php echo $this->lang->line("Enable Google PageInsights API from here"); ?></a>
				  </div>
				</div>
			<?php else: ?>
				<ul class="list-group">
					<div class="row">
						<div class="col-12">						
								<h4><?php echo $this->lang->line('Performance'); ?> : <?php echo $final_score; ?></h4>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<li class="list-group-item d-flex justify-content-between align-items-center">
								<?php echo $this->lang->line("Emulated Form Factor"); ?>
								<span class="badge badge-primary badge-pill">
									<?php  

										if(isset($lighthouseresult_configsettings['emulatedFormFactor']))
											echo ucwords($lighthouseresult_configsettings['emulatedFormFactor']);
									?>
										
									</span>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-center">
								<?php echo $this->lang->line("Locale") ?>
								<span class="badge badge-primary badge-pill">
									<?php 
										if(isset($lighthouseresult_configsettings['locale']))
											echo ucwords($lighthouseresult_configsettings['locale']);
									 ?>
								</span>
							</li>									
							<li class="list-group-item d-flex justify-content-between align-items-center">
								<?php echo $this->lang->line("Category") ?>
								<span class="badge badge-primary badge-pill">
									<?php 
										if(isset($lighthouseresult_configsettings['onlyCategories'][0]))
											echo ucwords($lighthouseresult_configsettings['onlyCategories'][0]);
									 ?>
								</span>
							</li>
						</div>
					</div>
				
				</ul>
				
				<div class="row mt-5">
					<div class="col-12 col-md-8">
						<div class="card card-hero custom_card-hero">
						    <div class="card-header" style="background-color: #007bff;">
						        <div class="card-icon m-0">
						            <i class="fab fa-google"></i>
						        </div>
						        <h4 style="color: #fff;"><?php echo $this->lang->line('Field Data'); ?></h4>
						        <div class="card-description">
									<p style="font-size: 12px; color: #fff;"><?php echo $this->lang->line("Over the last 30 days, the field data shows that this page has an <b>Moderate</b> speed compared to other pages in the") ?> <b><a target="_BLANK" style="color: #fff;" href="https://developers.google.com/web/tools/chrome-user-experience-report/"></b> <?php echo $this->lang->line("Chrome User Experience Report") ?></a>. <?php echo $this->lang->line("We are showing") ?> <b> <a style="color: #fff;" target="_BLANK"  href="https://developers.google.com/speed/docs/insights/v5/about#faq"><?php echo $this->lang->line("the 75th percentile of FCP") ?></b> <b></a> and <a target="_BLANK" style="color: #fff;" href="https://developers.google.com/speed/docs/insights/v5/about#faq"><?php echo $this->lang->line("the 95th percentile of FID") ?></a></b></p>
						        </div>

						    </div>

						    <div class="card-body p-0 moz-body">
						        <div class="tickets-list">
						            <div class="row">
						                <div class="col-12  pr-0">
						                    <a class="ticket-item">
						                        <div class="ticket-title" style="color:#6777ef;">
						                            <h4 style="font-size:14px;"><?php echo $this->lang->line("First Contentful Paint (FCP)"); ?></h4>
						                        </div>
						                        <div class="ticket-info" style="word-break:break-all;font-size:11px; color: #6777ef">
						                            <div>
						                            	<?php 
						                            	if(isset($loadingexperience_metrics['metrics']['FIRST_CONTENTFUL_PAINT_MS']['percentile']))
						                            		echo $loadingexperience_metrics['metrics']['FIRST_CONTENTFUL_PAINT_MS']['percentile'].' ms';
						                            	 ?>
						                            </div>
						                        </div>
						                    </a>
						                    <a class="ticket-item">
						                        <div class="ticket-title" style="color:#6777ef;">
						                            <h4 style="font-size:14px;"><?php echo $this->lang->line("Metric Category"); ?></h4>
						                        </div>
						                        <div class="ticket-info" style="word-break:break-all;font-size:11px;color: #6777ef">
						                            <div>
						                            	<?php 
						                            	if(isset($loadingexperience_metrics['metrics']['FIRST_CONTENTFUL_PAINT_MS']['category']))
						                            		echo $loadingexperience_metrics['metrics']['FIRST_CONTENTFUL_PAINT_MS']['category'];
						                            	 ?>
						                            </div>
						                        </div>
						                    </a>
						                </div>
						            </div> 
						            <div class="row">								                
						                <div class="col-12  pr-0">
						                    <a class="ticket-item">
						                        <div class="ticket-title" style="color:#6777ef;">
						                            <h4 style="font-size:14px;"><?php echo $this->lang->line("First Input Delay (FID)"); ?></h4>
						                        </div>
						                        <div class="ticket-info" style="word-break:break-all;font-size:11px;color: #6777ef">
						                            <div>								                <?php 
						                            	if(isset($loadingexperience_metrics['metrics']['FIRST_INPUT_DELAY_MS']['percentile']))
						                            		echo $loadingexperience_metrics['metrics']['FIRST_INPUT_DELAY_MS']['percentile'].' ms';
						                            	 ?></div>
						                        </div>
						                    </a>								                    
						                    <a class="ticket-item">
						                        <div class="ticket-title" style="color:#6777ef;">
						                            <h4 style="font-size:14px;"><?php echo $this->lang->line("Metric Category"); ?></h4>
						                        </div>
						                        <div class="ticket-info" style="word-break:break-all;font-size:11px;color: #6777ef">
						                            <div>								                <?php 
						                            	if(isset($loadingexperience_metrics['metrics']['FIRST_INPUT_DELAY_MS']['category']))
						                            		echo $loadingexperience_metrics['metrics']['FIRST_INPUT_DELAY_MS']['category'];
						                            	 ?></div>
						                        </div>
						                    </a>
						                </div>
						            </div>    
						            </div>
						            <div class="row">
						            	<div class="col-12 pr-0">
						            		<a class="ticket-item ticket-item ticket-more">
						            		    <div class="ticket-title" style="color:#6777ef;">
						            		        <h4 style="font-size:14px;"><?php echo $this->lang->line("Overall Category "); ?></h4>
						            		    </div>
						            		    <div style="word-break:break-all;font-size:11px;color: #6777ef">
						            		        <div>
						            		        	<?php 
						            		        	if(isset($loadingexperience_metrics['overall_category']))
						            		        		echo $loadingexperience_metrics['overall_category'];
						            		        	 ?>
						            		        </div>
						            		    </div>
						            		</a>
						            	</div>
						            </div>
						        </div>
						    </div>
						</div>
					</div>
                    <div class="col-12 col-md-4">
                        <div style="height:530px;background: url('<?php echo base_url("assets/images/mobile.png");?>') no-repeat !important;text-align: center;background-position: top center;">
                            <?php

                            if(isset($lighthouseresult_audits['final-screenshot']['details']['data']))
                            {

                                echo '<img src="'.$lighthouseresult_audits['final-screenshot']['details']['data'].'" width="225px" style="margin-top:52px;display: inline-block;">';
                            }

                            ?>
                        </div>
                    </div>
				</div>

				<div class="row">
					<div class="col-12">
						<div class="card card-hero custom_card-hero">
						    <div class="card-header" style="background-color: #007bff;">
						        <div class="card-icon m-0">
						            <i class="fab fa-google"></i>
						        </div>
						        <h4 style="color: #fff;"><?php echo $this->lang->line('Origin Summary'); ?></h4>
						        <div class="card-description">
									<p style="font-size: 12px;color: #fff;"> <?php echo $this->lang->line("All pages served from this origin have a <b>Slow</b> speed compared to other pages in the"); ?> <a target="_BLANK" style="color: #fff" href="https://developers.google.com/web/tools/chrome-user-experience-report/"><?php echo $this->lang->line("Chrome User Experience Report") ?></a> <?php echo $this->lang->line("over the last 30 days.To view suggestions tailored to each page, analyze individual page URLs.") ?></p>
						        </div>

						    </div>

						    <div class="card-body p-0 moz-body">
						        <div class="tickets-list">
						            <div class="row">
						                <div class="col-12  pr-0">
						                    <a class="ticket-item">
						                        <div class="ticket-title" style="color:#6777ef;">
						                            <h4 style="font-size:14px;"><?php echo $this->lang->line("First Contentful Paint (FCP)"); ?></h4>
						                        </div>
						                        <div class="ticket-info" style="word-break:break-all;font-size:11px;color:#6777ef;">
						                            <div>
						                            	<?php 
						                            	if(isset($originloadingexperience_metrics['metrics']['FIRST_CONTENTFUL_PAINT_MS']['percentile']))
						                            		echo $originloadingexperience_metrics['metrics']['FIRST_CONTENTFUL_PAINT_MS']['percentile'].' ms';
						                            	 ?>
						                            </div>
						                        </div>
						                    </a>								                    
						                    <a class="ticket-item">
						                        <div class="ticket-title" style="color:#6777ef;">
						                            <h4 style="font-size:14px;"><?php echo $this->lang->line("Metric Category"); ?></h4>
						                        </div>
						                        <div class="ticket-info" style="word-break:break-all;font-size:11px;color:#6777ef;">
						                            <div>
						                            	<?php 
						                            	if(isset($originloadingexperience_metrics['metrics']['FIRST_CONTENTFUL_PAINT_MS']['category']))
						                            		echo $originloadingexperience_metrics['metrics']['FIRST_CONTENTFUL_PAINT_MS']['category'];
						                            	 ?>
						                            </div>
						                        </div>
						                    </a>
						                </div>
						            </div>
						            <div class="row">							                
						                <div class="col-12  pr-0">
						                    <a class="ticket-item">
						                        <div class="ticket-title" style="color:#6777ef;">
						                            <h4 style="font-size:14px;"><?php echo $this->lang->line("First Input Delay (FID)"); ?></h4>
						                        </div>
						                        <div class="ticket-info" style="word-break:break-all;font-size:11px;color:#6777ef;">
						                            <div>								                <?php 
						                            	if(isset($originloadingexperience_metrics['metrics']['FIRST_INPUT_DELAY_MS']['percentile']))
						                            		echo $originloadingexperience_metrics['metrics']['FIRST_INPUT_DELAY_MS']['percentile'].' ms';
						                            	 ?></div>
						                        </div>
						                    </a>								                    
						                    <a class="ticket-item">
						                        <div class="ticket-title" style="color:#6777ef;">
						                            <h4 style="font-size:14px;"><?php echo $this->lang->line("Metric Category"); ?></h4>
						                        </div>
						                        <div class="ticket-info" style="word-break:break-all;font-size:11px;color:#6777ef;">
						                            <div>								                <?php 
						                            	if(isset($originloadingexperience_metrics['metrics']['FIRST_INPUT_DELAY_MS']['category']))
						                            		echo $originloadingexperience_metrics['metrics']['FIRST_INPUT_DELAY_MS']['category'];
						                            	 ?></div>
						                        </div>
						                    </a>
						                </div>    
						            </div>

						            <div class="row">
						            	<div class="col-12 pr-0">
						            		<a class="ticket-item ticket-item ticket-more">
						            		    <div class="ticket-title" style="color:#6777ef;">
						            		        <h4 style="font-size:14px;"><?php echo $this->lang->line("Overall Category "); ?></h4>
						            		    </div>
						            		    <div style="word-break:break-all;font-size:11px;color:#6777ef;">
						            		        <div>
						            		        	<?php 
						            		        	if(isset($originloadingexperience_metrics['overall_category']))
						            		        		echo $originloadingexperience_metrics['overall_category'];
						            		        	 ?>
						            		        </div>
						            		    </div>
						            		</a>
						            	</div>
						            </div>

						        </div>
						    </div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<div class="card card-hero custom_card-hero">
						    <div class="card-header" style="background-color: #007bff">
						        <div class="card-icon m-0">
						            <i class="fab fa-google"></i>
						        </div>
						        <h4 style="color: #fff;"><?php echo $this->lang->line('Lab Data'); ?></h4>

						    </div>

						    <div class="card-body p-0 moz-body">
						        <div class="tickets-list">
						            <div class="row">
						                <div class="col-12  pr-0">
						                    <div class="ticket-item">
						                        <div class="ticket-title" style="color:#6777ef;">
						                            <h4 style="font-size:14px;"><?php echo $this->lang->line("First Contentful Paint"); ?></h4>
						                            <p style="font-size: 12px;line-height: initial;"><?php echo $this->lang->line("First Contentful Paint marks the time at which the first text or image is painted."); ?> <b><a target="_BLANK" class="text-danger" href="https://web.dev/first-contentful-paint/?utm_source=lighthouse&utm_medium=unknown"><?php echo $this->lang->line("Learn more"); ?></a></b> </p>
						                        </div>
						                        <div class="ticket-info" style="word-break:break-all;font-size:11px;">
						                            <div>
						                            	<?php 
						                            	if(isset($lighthouseresult_audits['first-contentful-paint']['displayValue']))
						                            		echo $lighthouseresult_audits['first-contentful-paint']['displayValue'];
						                            	 ?>
						                            </div>
						                        </div>
						                    </div>								                    

						                </div>
						            </div> 
						            <div class="row">						                
						                <div class="col-12 pr-0">
						                    <div class="ticket-item">
						                        <div class="ticket-title" style="color:#6777ef;">
						                            <h4 style="font-size:14px;"><?php echo $this->lang->line("First Meaningful Paint"); ?></h4>
						                            <p style="font-size: 12px;line-height: initial;"><?php echo $this->lang->line("First Meaningful Paint measures when the primary content of a page is visible."); ?> <b><a target="_BLANK" class="text-danger" href="https://web.dev/first-meaningful-paint?utm_source=lighthouse&utm_medium=unknown"><?php echo $this->lang->line("Learn more"); ?></a></b> </p>
						                        </div>
						                        <div class="ticket-info" style="word-break:break-all;font-size:11px;">
						                            <div>								                <?php 
						                            	if(isset($lighthouseresult_audits['first-meaningful-paint']['displayValue']))
						                            		echo $lighthouseresult_audits['first-meaningful-paint']['displayValue'];
						                            	 ?></div>
						                        </div>
						                    </div>								                    

						                </div> 
						            </div> 
						            <div class="row">                                            
                                        <div class="col-12  pr-0">
                                            <div class="ticket-item">
                                                <div class="ticket-title" style="color:#6777ef;">
                                                    <h4 style="font-size:14px;"><?php echo $this->lang->line("Speed Index"); ?></h4>
                                                    <p style="font-size: 12px;line-height: initial;"><?php echo $this->lang->line("Speed Index shows how quickly the contents of a page are visibly populated."); ?> <b><a target="_BLANK" class="text-danger" href="https://web.dev/speed-index?utm_source=lighthouse&utm_medium=unknown"><?php echo $this->lang->line("Learn more"); ?></a></b> </p>
                                                </div>
                                                <div class="ticket-info" style="word-break:break-all;font-size:11px;">
                                                    <div>                                               <?php 
                                                        if(isset($lighthouseresult_audits['speed-index']['displayValue']))
                                                            echo $lighthouseresult_audits['speed-index']['displayValue'];
                                                         ?></div>
                                                </div>
                                            </div>                                                  

                                        </div>  
                                    </div> 
                                    <div class="row">                                              
                                        <div class="col-12  pr-0">
                                            <div class="ticket-item">
                                                <div class="ticket-title" style="color:#6777ef;">
                                                    <h4 style="font-size:14px;"><?php echo $this->lang->line("First CPU Idle"); ?></h4>
                                                    <p style="font-size: 12px;line-height: initial;"><?php echo $this->lang->line("First CPU Idle marks the first time at which the page's main thread is quiet enough to handle input."); ?> <b><a target="_BLANK" class="text-danger" href="https://web.dev/first-cpu-idle?utm_source=lighthouse&utm_medium=unknown"><?php echo $this->lang->line("Learn more"); ?></a></b> </p>
                                                </div>
                                                <div class="ticket-info" style="word-break:break-all;font-size:11px;">
                                                    <div>                                               <?php 
                                                        if(isset($lighthouseresult_audits['first-cpu-idle']['displayValue']))
                                                            echo $lighthouseresult_audits['first-cpu-idle']['displayValue'];
                                                         ?></div>
                                                </div>
                                            </div>                                                  

                                        </div>
                                    </div>
                                    <div class="row">                                                   
                                        <div class="col-12  pr-0">
                                            <div class="ticket-item">
                                                <div class="ticket-title" style="color:#6777ef;">
                                                    <h4 style="font-size:14px;"><?php echo $this->lang->line("Time to Interactive"); ?></h4>
                                                    <p style="font-size: 12px;line-height: initial;"><?php echo $this->lang->line("Time to interactive is the amount of time it takes for the page to become fully interactive."); ?> <b><a target="_BLANK" class="text-danger" href="https://web.dev/interactive/?utm_source=lighthouse&utm_medium=unknown"><?php echo $this->lang->line("Learn more"); ?></a></b> </p>
                                                </div>
                                                <div class="ticket-info" style="word-break:break-all;font-size:11px;">
                                                    <div>                                               <?php 
                                                        if(isset($lighthouseresult_audits['interactive']['displayValue']))
                                                            echo $lighthouseresult_audits['interactive']['displayValue'];
                                                         ?></div>
                                                </div>
                                            </div>                                                  

                                        </div>
                                    </div> 
                                    <div class="row">                                                  
                                        <div class="col-12  pr-0">
                                            <div class="ticket-item">
                                                <div class="ticket-title" style="color:#6777ef;">
                                                    <h4 style="font-size:14px;"><?php echo $this->lang->line("Max Potential First Input Delay"); ?></h4>
                                                    <p style="font-size: 12px;line-height: initial;"><?php echo $this->lang->line("The maximum potential First Input Delay that your users could experience is the duration, in milliseconds, of the longest task."); ?> <b><a target="_BLANK" class="text-danger" href="https://web.dev/fid/"><?php echo $this->lang->line("Learn more"); ?></a></b> </p>
                                                </div>
                                                <div class="ticket-info" style="word-break:break-all;font-size:11px;">
                                                    <div>                                               <?php 
                                                        if(isset($lighthouseresult_audits['max-potential-fid']['displayValue']))
                                                            echo $lighthouseresult_audits['max-potential-fid']['displayValue'];
                                                         ?></div>
                                                </div>
                                            </div>                                                  

                                        </div>    
						            </div>
						        </div>
						    </div>
						</div>
					</div>
				</div>
                <br>
                <div class="row mt-5">
                    <div class="col-12">
                        <div class="card card-hero custom_card-hero">
                            <div class="card-header" style="background-color: #007bff">
                                <div class="card-icon m-0">
                                    <i class="fab fa-google"></i>
                                </div>
                                <h4 style="color: #fff;"><?php echo $this->lang->line('Audit Data'); ?></h4>

                            </div>

                            <div class="card-body p-0 moz-body">
                                <div class="tickets-list">
                                    <div class="row">
                                        <div class="col-12 col-md-3 pr-0">
                                            <div class="ticket-item">
                                                <div class="ticket-title" style="color:#6777ef;">
                                                    <h3 style="font-size: 16px;">
                                                        <?php 
                                                         if(isset($lighthouseresult_audits['resource-summary']['title']))
                                                            echo $lighthouseresult_audits['resource-summary']['title']; 
                                                         ?>
                                                    </h3>
                                                    <p style="font-size: 12px; line-height: initial;">
                                                         <?php 

                                                          if(isset($lighthouseresult_audits['resource-summary']['description'])){

                                                            $resource_sum = explode('[',$lighthouseresult_audits['resource-summary']['description']);
                                                             echo $resource_sum[0] .'<b><a class="text-danger" target="_BLANK" href="https://developers.google.com/web/tools/lighthouse/audits/budgets">'.$this->lang->line("Learn More").'</a></b>';
                                                          }

                                                          ?>   

                                                    </p>
                                                    <h4 style="font-size:14px;" class="text-info">
                                                         <?php 
                                                         if(isset($lighthouseresult_audits['resource-summary']['displayValue']))
                                                         echo $lighthouseresult_audits['resource-summary']['displayValue'];
                                                          ?>
                                                              
                                                     </h4>
                                                </div>

                                            </div>                                                    
                                        </div>                                              
                                        <div class="col-12 col-md-3 pr-0">
                                            <div class="ticket-item">
                                                <div class="ticket-title" style="color:#6777ef;">
                                                    <h3 style="font-size: 16px;">
                                                        <?php 
                                                         if(isset($lighthouseresult_audits['time-to-first-byte']['title']))
                                                            echo $lighthouseresult_audits['time-to-first-byte']['title']; 
                                                         ?>
                                                    </h3>
                                                    <p style="font-size: 12px; line-height: initial;">
                                                         <?php 

                                                          if(isset($lighthouseresult_audits['time-to-first-byte']['description'])){

                                                            $time_to_first_byte = explode('[',$lighthouseresult_audits['time-to-first-byte']['description']);
                                                             echo $time_to_first_byte[0] .'<b><a class="text-danger" target="_BLANK" href="https://web.dev/time-to-first-byte">'.$this->lang->line("Learn More").'</a></b>';
                                                          }

                                                          ?>   

                                                    </p>
                                                    <h4 style="font-size:14px;" class="text-info">
                                                        <?php 
                                                        if(isset($lighthouseresult_audits['time-to-first-byte']['displayValue']))
                                                        echo $lighthouseresult_audits['time-to-first-byte']['displayValue'];
                                                         ?>
                                                    </h4>
                                                </div>
                                            </div>                                                    
                                        </div>                                                 
                                        <div class="col-12 col-md-3 pr-0">
                                            <div class="ticket-item">
                                                <div class="ticket-title" style="color:#6777ef;">
                                                    <h3 style="font-size: 16px;">
                                                        <?php 
                                                         if(isset($lighthouseresult_audits['render-blocking-resources']['title']))
                                                            echo $lighthouseresult_audits['render-blocking-resources']['title']; 
                                                         ?>
                                                    </h3>
                                                    <p style="font-size: 12px; line-height: initial;">
                                                         <?php 

                                                          if(isset($lighthouseresult_audits['render-blocking-resources']['description'])){

                                                            $render_blocking = explode('[',$lighthouseresult_audits['render-blocking-resources']['description']);
                                                             echo $render_blocking[0] .'<b><a class="text-danger" target="_BLANK" href="https://web.dev/render-blocking-resources">'.$this->lang->line("Learn More").'</a></b>';
                                                          }

                                                          ?>   

                                                    </p>
                                                    <h4 style="font-size:14px;" class="text-info">
                                                        <?php 
                                                        if(isset($lighthouseresult_audits['render-blocking-resources']['displayValue']))
                                                        echo $lighthouseresult_audits['render-blocking-resources']['displayValue'];
                                                         ?>
                                                    </h4>
                                                </div>
                                            </div>                                                    
                                        </div>                                                  
                                        <div class="col-12 col-md-3 pr-0">
                                            <div class="ticket-item">
                                                <div class="ticket-title" style="color:#6777ef;">
                                                    <h3 style="font-size: 16px;">
                                                        <?php 
                                                         if(isset($lighthouseresult_audits['uses-optimized-images']['title']))
                                                            echo $lighthouseresult_audits['uses-optimized-images']['title']; 
                                                         ?>
                                                    </h3>
                                                    <p style="font-size: 12px; line-height: initial;">
                                                         <?php 

                                                          if(isset($lighthouseresult_audits['uses-optimized-images']['description'])){

                                                            $optimizaed = explode('[',$lighthouseresult_audits['uses-optimized-images']['description']);
                                                             echo $optimizaed[0] .'<b><a class="text-danger" target="_BLANK" href="https://web.dev/uses-optimized-images">'.$this->lang->line("Learn More").'</a></b>';
                                                          }

                                                          ?>   

                                                    </p>
                                                </div>
                                            </div>                                                    
                                        </div>                                                   
                                    </div>                                            
                                    <div class="row">
                                        <div class="col-12 col-md-3 pr-0">
                                            <div class="ticket-item">
                                                <div class="ticket-title" style="color:#6777ef;">
                                                    <h3 style="font-size: 16px;">
                                                        <?php 
                                                         if(isset($lighthouseresult_audits['uses-text-compression']['title']))
                                                            echo $lighthouseresult_audits['uses-text-compression']['title']; 
                                                         ?>
                                                    </h3>
                                                    <p style="font-size: 12px; line-height: initial;">
                                                         <?php 

                                                          if(isset($lighthouseresult_audits['uses-text-compression']['description'])){

                                                            $text_compresseion = explode('[',$lighthouseresult_audits['uses-text-compression']['description']);
                                                             echo $text_compresseion[0] .'<b><a class="text-danger" target="_BLANK" href="https://web.dev/uses-text-compression">'.$this->lang->line("Learn More").'</a></b>';
                                                          }

                                                          ?>   

                                                    </p>
                                                    <h4 style="font-size:14px;" class="text-info">
                                                         <?php 
                                                         if(isset($lighthouseresult_audits['uses-text-compression']['displayValue']))
                                                         echo $lighthouseresult_audits['uses-text-compression']['displayValue'];
                                                          ?>
                                                              
                                                     </h4>
                                                </div>

                                            </div>                                                    
                                        </div>                                                     
                                        <div class="col-12 col-md-3 pr-0">
                                            <div class="ticket-item">
                                                <div class="ticket-title" style="color:#6777ef;">
                                                    <h3 style="font-size: 16px;">
                                                        <?php 
                                                         if(isset($lighthouseresult_audits['uses-long-cache-ttl']['title']))
                                                            echo $lighthouseresult_audits['uses-long-cache-ttl']['title']; 
                                                         ?>
                                                    </h3>
                                                    <p style="font-size: 12px; line-height: initial;">
                                                         <?php 

                                                          if(isset($lighthouseresult_audits['uses-long-cache-ttl']['description'])){

                                                            $uses_long_cache = explode('[',$lighthouseresult_audits['uses-long-cache-ttl']['description']);
                                                             echo $uses_long_cache[0] .'<b><a class="text-danger" target="_BLANK" href="https://web.dev/uses-long-cache-ttl">'.$this->lang->line("Learn More").'</a></b>';
                                                          }

                                                          ?>   

                                                    </p>
                                                    <h4 style="font-size:14px;" class="text-info">
                                                         <?php 
                                                         if(isset($lighthouseresult_audits['uses-long-cache-ttl']['displayValue']))
                                                         echo $lighthouseresult_audits['uses-long-cache-ttl']['displayValue'];
                                                          ?>
                                                              
                                                     </h4>
                                                </div>

                                            </div>                                                    
                                        </div>                                                    
                                        <div class="col-12 col-md-3 pr-0">
                                            <div class="ticket-item">
                                                <div class="ticket-title" style="color:#6777ef;">
                                                    <h3 style="font-size: 16px;">
                                                        <?php 
                                                         if(isset($lighthouseresult_audits['third-party-summary']['title']))
                                                            echo $lighthouseresult_audits['third-party-summary']['title']; 
                                                         ?>
                                                    </h3>
                                                    <p style="font-size: 12px; line-height: initial;">
                                                         <?php 

                                                          if(isset($lighthouseresult_audits['third-party-summary']['description'])){

                                                            $third_party_summary = explode('[',$lighthouseresult_audits['third-party-summary']['description']);
                                                             echo $third_party_summary[0] .'<b><a class="text-danger" target="_BLANK" href="https://developers.google.com/web/fundamentals/performance/optimizing-content-efficiency/loading-third-party-javascript">'.$this->lang->line("Learn More").'</a></b>';
                                                          }

                                                          ?>   

                                                    </p>
                                                    <h4 style="font-size:14px;" class="text-info">
                                                         <?php 
                                                         if(isset($lighthouseresult_audits['third-party-summary']['displayValue']))
                                                         echo $lighthouseresult_audits['third-party-summary']['displayValue'];
                                                          ?>
                                                              
                                                     </h4>
                                                </div>

                                            </div>                                                    
                                        </div>                                                  
                                        <div class="col-12 col-md-3 pr-0">
                                            <div class="ticket-item">
                                                <div class="ticket-title" style="color:#6777ef;">
                                                    <h3 style="font-size: 16px;">
                                                        <?php 
                                                         if(isset($lighthouseresult_audits['network-rtt']['title']))
                                                            echo $lighthouseresult_audits['network-rtt']['title']; 
                                                         ?>
                                                    </h3>
                                                    <p style="font-size: 12px; line-height: initial;">
                                                         <?php 

                                                          if(isset($lighthouseresult_audits['network-rtt']['description'])){

                                                            $newtwork_rtt = explode('[',$lighthouseresult_audits['network-rtt']['description']);
                                                             echo $newtwork_rtt[0] .'<b><a class="text-danger" target="_BLANK" href="https://hpbn.co/primer-on-latency-and-bandwidth">'.$this->lang->line("Learn More").'</a></b>';
                                                          }

                                                          ?>   

                                                    </p>
                                                    <h4 style="font-size:14px;" class="text-info">
                                                         <?php 
                                                         if(isset($lighthouseresult_audits['network-rtt']['displayValue']))
                                                         echo $lighthouseresult_audits['network-rtt']['displayValue'];
                                                          ?>
                                                              
                                                     </h4>
                                                </div>

                                            </div>                                                    
                                        </div>                                                                                         
                                    </div>                                            
                                    <div class="row">
                                        <div class="col-12 col-md-3 pr-0">
                                            <div class="ticket-item">
                                                <div class="ticket-title" style="color:#6777ef;">
                                                    <h3 style="font-size: 16px;">
                                                        <?php 
                                                         if(isset($lighthouseresult_audits['estimated-input-latency']['title']))
                                                            echo $lighthouseresult_audits['estimated-input-latency']['title']; 
                                                         ?>
                                                    </h3>
                                                    <p style="font-size: 12px; line-height: initial;">
                                                         <?php 

                                                          if(isset($lighthouseresult_audits['estimated-input-latency']['description'])){

                                                            $estimated_latency = explode('[',$lighthouseresult_audits['estimated-input-latency']['description']);
                                                             echo $estimated_latency[0] .'<b><a class="text-danger" target="_BLANK" href="https://web.dev/estimated-input-latency">'.$this->lang->line("Learn More").'</a></b>';
                                                          }

                                                          ?>   

                                                    </p>
                                                    <h4 style="font-size:14px;" class="text-info">
                                                         <?php 
                                                         if(isset($lighthouseresult_audits['estimated-input-latency']['displayValue']))
                                                         echo $lighthouseresult_audits['estimated-input-latency']['displayValue'];
                                                          ?>
                                                              
                                                     </h4>
                                                </div>

                                            </div>                                                    
                                        </div>                                                   
                                        <div class="col-12 col-md-3 pr-0">
                                            <div class="ticket-item">
                                                <div class="ticket-title" style="color:#6777ef;">
                                                    <h3 style="font-size: 16px;">
                                                        <?php 
                                                         if(isset($lighthouseresult_audits['first-contentful-paint-3g']['title']))
                                                            echo $lighthouseresult_audits['first-contentful-paint-3g']['title']; 
                                                         ?>
                                                    </h3>
                                                    <p style="font-size: 12px; line-height: initial;">
                                                         <?php 

                                                          if(isset($lighthouseresult_audits['first-contentful-paint-3g']['description'])){

                                                            $estimated_latency = explode('[',$lighthouseresult_audits['first-contentful-paint-3g']['description']);
                                                             echo $estimated_latency[0] .'<b><a class="text-danger" target="_BLANK" href="https://developers.google.com/web/tools/lighthouse/audits/first-contentful-paint">'.$this->lang->line("Learn More").'</a></b>';
                                                          }

                                                          ?>   

                                                    </p>
                                                    <h4 style="font-size:14px;" class="text-info">
                                                         <?php 
                                                         if(isset($lighthouseresult_audits['first-contentful-paint-3g']['displayValue']))
                                                         echo $lighthouseresult_audits['first-contentful-paint-3g']['displayValue'];
                                                          ?>
                                                              
                                                     </h4>
                                                </div>

                                            </div>                                                    
                                        </div>                                                     
                                        <div class="col-12 col-md-3 pr-0">
                                            <div class="ticket-item">
                                                <div class="ticket-title" style="color:#6777ef;">
                                                    <h3 style="font-size: 16px;">
                                                        <?php 
                                                         if(isset($lighthouseresult_audits['total-blocking-time']['title']))
                                                            echo $lighthouseresult_audits['total-blocking-time']['title']; 
                                                         ?>
                                                    </h3>
                                                    <p style="font-size: 12px; line-height: initial;">
                                                         <?php 

                                                          if(isset($lighthouseresult_audits['total-blocking-time']['description'])){

                                                            $total_blocking_time = explode('[',$lighthouseresult_audits['total-blocking-time']['description']);
                                                             echo $total_blocking_time[0];
                                                          }

                                                          ?>   

                                                    </p>
                                                    <h4 style="font-size:14px;" class="text-info">
                                                         <?php 
                                                         if(isset($lighthouseresult_audits['total-blocking-time']['displayValue']))
                                                         echo $lighthouseresult_audits['total-blocking-time']['displayValue'];
                                                          ?>
                                                              
                                                     </h4>
                                                </div>

                                            </div>                                                    
                                        </div>                                                
                                        <div class="col-12 col-md-3 pr-0">
                                            <div class="ticket-item">
                                                <div class="ticket-title" style="color:#6777ef;">
                                                    <h3 style="font-size: 16px;">
                                                        <?php 
                                                         if(isset($lighthouseresult_audits['bootup-time']['title']))
                                                            echo $lighthouseresult_audits['bootup-time']['title']; 
                                                         ?>
                                                    </h3>
                                                    <p style="font-size: 12px; line-height: initial;">
                                                         <?php 

                                                          if(isset($lighthouseresult_audits['bootup-time']['description'])){

                                                            $total_blocking_time = explode('[',$lighthouseresult_audits['bootup-time']['description']);
                                                             echo $total_blocking_time[0] .'<b><a class="text-danger" target="_BLANK" href="https://web.dev/bootup-time">'.$this->lang->line("Learn More").'</a></b>';
                                                          }

                                                          ?>   

                                                    </p>
                                                    <h4 style="font-size:14px;" class="text-info">
                                                         <?php 
                                                         if(isset($lighthouseresult_audits['bootup-time']['displayValue']))
                                                         echo $lighthouseresult_audits['bootup-time']['displayValue'];
                                                          ?>
                                                              
                                                     </h4>
                                                </div>

                                            </div>                                                    
                                        </div>                                                   
                                                                                                                               
                                    </div>                                            
                                    <div class="row">
                                        <div class="col-12 col-md-3 pr-0">
                                            <div class="ticket-item">
                                                <div class="ticket-title" style="color:#6777ef;">
                                                    <h3 style="font-size: 16px;">
                                                        <?php 
                                                         if(isset($lighthouseresult_audits['offscreen-images']['title']))
                                                            echo $lighthouseresult_audits['offscreen-images']['title']; 
                                                         ?>
                                                    </h3>
                                                    <p style="font-size: 12px; line-height: initial;">
                                                         <?php 

                                                          if(isset($lighthouseresult_audits['offscreen-images']['description'])){

                                                            $offscreen = explode('[',$lighthouseresult_audits['offscreen-images']['description']);
                                                             echo $offscreen[0] .'<b><a class="text-danger" target="_BLANK" href="https://web.dev/offscreen-images">'.$this->lang->line("Learn More").'</a></b>';
                                                          }

                                                          ?>   

                                                    </p>
                                                    <h4 style="font-size:14px;" class="text-info">
                                                         <?php 
                                                         if(isset($lighthouseresult_audits['offscreen-images']['displayValue']))
                                                         echo $lighthouseresult_audits['offscreen-images']['displayValue'];
                                                          ?>
                                                              
                                                     </h4>
                                                </div>

                                            </div>                                                    
                                        </div>                                                 
                                        <div class="col-12 col-md-3 pr-0">
                                            <div class="ticket-item">
                                                <div class="ticket-title" style="color:#6777ef;">
                                                    <h3 style="font-size: 16px;">
                                                        <?php 
                                                         if(isset($lighthouseresult_audits['network-server-latency']['title']))
                                                            echo $lighthouseresult_audits['network-server-latency']['title']; 
                                                         ?>
                                                    </h3>
                                                    <p style="font-size: 12px; line-height: initial;">
                                                         <?php 

                                                          if(isset($lighthouseresult_audits['network-server-latency']['description'])){

                                                            $network_server_lantency = explode('[',$lighthouseresult_audits['network-server-latency']['description']);
                                                             echo $network_server_lantency[0] .'<b><a class="text-danger" target="_BLANK" href="(https://hpbn.co/primer-on-web-performance/#analyzing-the-resource-waterfall">'.$this->lang->line("Learn More").'</a></b>';
                                                          }

                                                          ?>   

                                                    </p>
                                                    <h4 style="font-size:14px;" class="text-info">
                                                         <?php 
                                                         if(isset($lighthouseresult_audits['network-server-latency']['displayValue']))
                                                         echo $lighthouseresult_audits['network-server-latency']['displayValue'];
                                                          ?>
                                                              
                                                     </h4>
                                                </div>

                                            </div>                                                    
                                        </div>                                                  
                                        <div class="col-12 col-md-3 pr-0">
                                            <div class="ticket-item">
                                                <div class="ticket-title" style="color:#6777ef;">
                                                    <h3 style="font-size: 16px;">
                                                        <?php 
                                                         if(isset($lighthouseresult_audits['uses-responsive-images']['title']))
                                                            echo $lighthouseresult_audits['uses-responsive-images']['title']; 
                                                         ?>
                                                    </h3>
                                                    <p style="font-size: 12px; line-height: initial;">
                                                         <?php 

                                                          if(isset($lighthouseresult_audits['uses-responsive-images']['description'])){

                                                            $uses_reponsive_images = explode('[',$lighthouseresult_audits['uses-responsive-images']['description']);
                                                             echo $uses_reponsive_images[0] .'<b><a class="text-danger" target="_BLANK" href="https://hpbn.co/primer-on-web-performance/#analyzing-the-resource-waterfall">'.$this->lang->line("Learn More").'</a></b>';
                                                          }

                                                          ?>   

                                                    </p>
                                                    <h4 style="font-size:14px;" class="text-info">
                                                         <?php 
                                                         if(isset($lighthouseresult_audits['uses-responsive-images']['displayValue']))
                                                         echo $lighthouseresult_audits['uses-responsive-images']['displayValue'];
                                                          ?>
                                                              
                                                     </h4>
                                                </div>

                                            </div>                                                    
                                        </div>                                                
                                        <div class="col-12 col-md-3 pr-0">
                                            <div class="ticket-item">
                                                <div class="ticket-title" style="color:#6777ef;">
                                                    <h3 style="font-size: 16px;">
                                                        <?php 
                                                         if(isset($lighthouseresult_audits['unused-css-rules']['title']))
                                                            echo $lighthouseresult_audits['unused-css-rules']['title']; 
                                                         ?>
                                                    </h3>
                                                    <p style="font-size: 12px; line-height: initial;">
                                                         <?php 

                                                          if(isset($lighthouseresult_audits['unused-css-rules']['description'])){

                                                            $unused_css_rules = explode('[',$lighthouseresult_audits['unused-css-rules']['description']);
                                                             echo $unused_css_rules[0] .'<b><a class="text-danger" target="_BLANK" href="https://web.dev/unused-css-rules">'.$this->lang->line("Learn More").'</a></b>';
                                                          }

                                                          ?>   

                                                    </p>
                                                    <h4 style="font-size:14px;" class="text-info">
                                                         <?php 
                                                         if(isset($lighthouseresult_audits['unused-css-rules']['displayValue']))
                                                         echo $lighthouseresult_audits['unused-css-rules']['displayValue'];
                                                          ?>
                                                              
                                                     </h4>
                                                </div>

                                            </div>                                                    
                                        </div>                                                   
                                                                                                                                                                                  
                                    </div>                                            
                                    <div class="row">
                                        <div class="col-12 col-md-3 pr-0">
                                            <div class="ticket-item">
                                                <div class="ticket-title" style="color:#6777ef;">
                                                    <h3 style="font-size: 16px;">
                                                        <?php 
                                                         if(isset($lighthouseresult_audits['total-byte-weight']['title']))
                                                            echo $lighthouseresult_audits['total-byte-weight']['title']; 
                                                         ?>
                                                    </h3>
                                                    <p style="font-size: 12px; line-height: initial;">
                                                         <?php 

                                                          if(isset($lighthouseresult_audits['total-byte-weight']['description'])){

                                                            $total_byte = explode('[',$lighthouseresult_audits['total-byte-weight']['description']);
                                                             echo $total_byte[0] .'<b><a class="text-danger" target="_BLANK" href="https://web.dev/total-byte-weight">'.$this->lang->line("Learn More").'</a></b>';
                                                          }

                                                          ?>   

                                                    </p>
                                                    <h4 style="font-size:14px;" class="text-info">
                                                         <?php 
                                                         if(isset($lighthouseresult_audits['total-byte-weight']['displayValue']))
                                                         echo $lighthouseresult_audits['total-byte-weight']['displayValue'];
                                                          ?>
                                                              
                                                     </h4>
                                                </div>

                                            </div>                                                    
                                        </div>                                                
                                        <div class="col-12 col-md-3 pr-0">
                                            <div class="ticket-item">
                                                <div class="ticket-title" style="color:#6777ef;">
                                                    <h3 style="font-size: 16px;">
                                                        <?php 
                                                         if(isset($lighthouseresult_audits['mainthread-work-breakdown']['title']))
                                                            echo $lighthouseresult_audits['mainthread-work-breakdown']['title']; 
                                                         ?>
                                                    </h3>
                                                    <p style="font-size: 12px; line-height: initial;">
                                                         <?php 

                                                          if(isset($lighthouseresult_audits['mainthread-work-breakdown']['description'])){

                                                            $total_byte = explode('[',$lighthouseresult_audits['mainthread-work-breakdown']['description']);
                                                             echo $total_byte[0] .'<b><a class="text-danger" target="_BLANK" href="https://web.dev/mainthread-work-breakdown">'.$this->lang->line("Learn More").'</a></b>';
                                                          }

                                                          ?>   

                                                    </p>
                                                    <h4 style="font-size:14px;" class="text-info">
                                                         <?php 
                                                         if(isset($lighthouseresult_audits['mainthread-work-breakdown']['displayValue']))
                                                         echo $lighthouseresult_audits['mainthread-work-breakdown']['displayValue'];
                                                          ?>
                                                              
                                                     </h4>
                                                </div>

                                            </div>                                                    
                                        </div>                                                    
                                        <div class="col-12 col-md-3 pr-0">
                                            <div class="ticket-item">
                                                <div class="ticket-title" style="color:#6777ef;">
                                                    <h3 style="font-size: 16px;">
                                                        <?php 
                                                         if(isset($lighthouseresult_audits['uses-webp-images']['title']))
                                                            echo $lighthouseresult_audits['uses-webp-images']['title']; 
                                                         ?>
                                                    </h3>
                                                    <p style="font-size: 12px; line-height: initial;">
                                                         <?php 

                                                          if(isset($lighthouseresult_audits['uses-webp-images']['description'])){

                                                            $uses_webp = explode('[',$lighthouseresult_audits['uses-webp-images']['description']);
                                                             echo $uses_webp[0] .'<b><a class="text-danger" target="_BLANK" href="https://web.dev/uses-webp-images">'.$this->lang->line("Learn More").'</a></b>';
                                                          }

                                                          ?>   

                                                    </p>
                                                    <h4 style="font-size:14px;" class="text-info">
                                                         <?php 
                                                         if(isset($lighthouseresult_audits['uses-webp-images']['displayValue']))
                                                         echo $lighthouseresult_audits['uses-webp-images']['displayValue'];
                                                          ?>
                                                              
                                                     </h4>
                                                </div>

                                            </div>                                                    
                                        </div>                                                  
                                        <div class="col-12 col-md-3 pr-0">
                                            <div class="ticket-item">
                                                <div class="ticket-title" style="color:#6777ef;">
                                                    <h3 style="font-size: 16px;">
                                                        <?php 
                                                         if(isset($lighthouseresult_audits['critical-request-chains']['title']))
                                                            echo $lighthouseresult_audits['critical-request-chains']['title']; 
                                                         ?>
                                                    </h3>
                                                    <p style="font-size: 12px; line-height: initial;">
                                                         <?php 

                                                          if(isset($lighthouseresult_audits['critical-request-chains']['description'])){

                                                            $critical = explode('[',$lighthouseresult_audits['critical-request-chains']['description']);
                                                             echo $critical[0] .'<b><a class="text-danger" target="_BLANK" href="https://web.dev/critical-request-chains">'.$this->lang->line("Learn More").'</a></b>';
                                                          }

                                                          ?>   

                                                    </p>
                                                    <h4 style="font-size:14px;" class="text-info">
                                                         <?php 
                                                         if(isset($lighthouseresult_audits['critical-request-chains']['displayValue']))
                                                         echo $lighthouseresult_audits['critical-request-chains']['displayValue'];
                                                          ?>
                                                              
                                                     </h4>
                                                </div>

                                            </div>                                                    
                                        </div>                                                                                                   
                                                                                                                                                                                  
                                    </div>                                            
                                    <div class="row">
                                        <div class="col-12 col-md-3 pr-0">
                                            <div class="ticket-item">
                                                <div class="ticket-title" style="color:#6777ef;">
                                                    <h3 style="font-size: 16px;">
                                                        <?php 
                                                         if(isset($lighthouseresult_audits['dom-size']['title']))
                                                            echo $lighthouseresult_audits['total-byte-weight']['title']; 
                                                         ?>
                                                    </h3>
                                                    <p style="font-size: 12px; line-height: initial;">
                                                         <?php 

                                                          if(isset($lighthouseresult_audits['dom-size']['description'])){

                                                            $dom_size = explode('[',$lighthouseresult_audits['dom-size']['description']);
                                                             echo $dom_size[0] .'<b><a class="text-danger" target="_BLANK" href="https://developers.google.com/web/fundamentals/performance/rendering/reduce-the-scope-and-complexity-of-style-calculations">'.$this->lang->line("Learn More").'</a></b>';
                                                          }

                                                          ?>   

                                                    </p>
                                                    <h4 style="font-size:14px;" class="text-info">
                                                         <?php 
                                                         if(isset($lighthouseresult_audits['dom-size']['displayValue']))
                                                         echo $lighthouseresult_audits['dom-size']['displayValue'];
                                                          ?>
                                                              
                                                     </h4>
                                                </div>

                                            </div>                                                    
                                        </div>                                                 
                                        <div class="col-12 col-md-3 pr-0">
                                            <div class="ticket-item">
                                                <div class="ticket-title" style="color:#6777ef;">
                                                    <h3 style="font-size: 16px;">
                                                        <?php 
                                                         if(isset($lighthouseresult_audits['redirects']['title']))
                                                            echo $lighthouseresult_audits['redirects']['title']; 
                                                         ?>
                                                    </h3>
                                                    <p style="font-size: 12px; line-height: initial;">
                                                         <?php 

                                                          if(isset($lighthouseresult_audits['redirects']['description'])){

                                                            $redirects_ss = explode('[',$lighthouseresult_audits['redirects']['description']);
                                                             echo $redirects_ss[0] .'<b><a class="text-danger" target="_BLANK" href="https://web.dev/redirects">'.$this->lang->line("Learn More").'</a></b>';
                                                          }

                                                          ?>   

                                                    </p>
                                                    <h4 style="font-size:14px;" class="text-info">
                                                         <?php 
                                                         if(isset($lighthouseresult_audits['redirects']['displayValue']))
                                                         echo $lighthouseresult_audits['redirects']['displayValue'];
                                                          ?>
                                                              
                                                     </h4>
                                                </div>

                                            </div>                                                    
                                        </div>                                                  

                                        <div class="col-12 col-md-3 pr-0">
                                            <div class="ticket-item">
                                                <div class="ticket-title" style="color:#6777ef;">
                                                    <h3 style="font-size: 16px;">
                                                        <?php 
                                                         if(isset($lighthouseresult_audits['unminified-javascript']['title']))
                                                            echo $lighthouseresult_audits['unminified-javascript']['title']; 
                                                         ?>
                                                    </h3>
                                                    <p style="font-size: 12px; line-height: initial;">
                                                         <?php 

                                                          if(isset($lighthouseresult_audits['unminified-javascript']['description'])){

                                                            $uniminified_js = explode('[',$lighthouseresult_audits['unminified-javascript']['description']);
                                                             echo $uniminified_js[0] .'<b><a class="text-danger" target="_BLANK" href="https://web.dev/unminified-javascript">'.$this->lang->line("Learn More").'</a></b>';
                                                          }

                                                          ?>   

                                                    </p>
                                                </div>

                                            </div>                                                    
                                        </div> 

                                        <div class="col-12 col-md-3 pr-0">
                                            <div class="ticket-item">
                                                <div class="ticket-title" style="color:#6777ef;">
                                                    <h3 style="font-size: 16px;">
                                                        <?php 
                                                         if(isset($lighthouseresult_audits['user-timings']['title']))
                                                            echo $lighthouseresult_audits['user-timings']['title']; 
                                                         ?>
                                                    </h3>
                                                    <p style="font-size: 12px; line-height: initial;">
                                                         <?php 

                                                          if(isset($lighthouseresult_audits['user-timings']['description'])){

                                                            $user_timings = explode('[',$lighthouseresult_audits['user-timings']['description']);
                                                             echo $user_timings[0] .'<b><a class="text-danger" target="_BLANK" href="https://web.dev/user-timings">'.$this->lang->line("Learn More").'</a></b>';
                                                          }

                                                          ?>   

                                                    </p>
                                                    <h4 style="font-size:14px;" class="text-info">
                                                         <?php 
                                                         if(isset($lighthouseresult_audits['user-timings']['displayValue']))
                                                         echo $lighthouseresult_audits['user-timings']['displayValue'];
                                                          ?>
                                                              
                                                     </h4>
                                                </div>

                                            </div>                                                    
                                        </div>                                                   
                                                                                                                                                                                                                                                                                 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
			<?php endif; ?>

		</div>
	</div>

	<div class="row">
		<div class="col-md-6" style="float: left;">
			<ul class="list-group pdf-list-group">
				<li class="list-group-item pdf-list-group-item-active"><?php echo $this->lang->line('IP Information'); ?></li>
				
				<li class="list-group-item"><b><?php echo $this->lang->line('ISP'); ?> : </b>
					<?php echo $domain_info[0]['ipinfo_isp']; ?>
				</li>
				
				<li class="list-group-item"><b><?php echo $this->lang->line('IP'); ?> : </b>
					<?php echo $domain_info[0]['ipinfo_ip']; ?>
				</li>
				
				<li class="list-group-item"><b><?php echo $this->lang->line('Country'); ?> : </b>
					<?php $x= trim(strtoupper($domain_info[0]['ipinfo_country']));?>
					<img style="height: 15px; width: 20px; margin-top: -3px;" alt=" " src="<?php $s_country = array_search($x, $country_list); echo base_url().'assets/images/flags/'.$s_country.'.png'; ?>">&nbsp;<?php echo $x; ?>
				</li>
				
				<li class="list-group-item"><b><?php echo $this->lang->line('City'); ?> : </b>
					<?php echo $domain_info[0]['ipinfo_city']; ?>
				</li>
				
				<li class="list-group-item"><b><?php echo $this->lang->line('Region'); ?> : </b>
					<?php echo $domain_info[0]['ipinfo_region']; ?>
				</li>
				
				<li class="list-group-item"><b><?php echo $this->lang->line('Timezone'); ?> : </b>
					<?php echo $domain_info[0]['ipinfo_time_zone']; ?>
				</li>
				
				<li class="list-group-item"><b><?php echo $this->lang->line('Latitude'); ?> : </b>
					<?php echo $domain_info[0]['ipinfo_latitude']; ?>
				</li>
				
				<li class="list-group-item"><b><?php echo $this->lang->line('Longitude'); ?> : </b>
					<?php echo $domain_info[0]['ipinfo_longitude']; ?>
				</li>
			</ul>	
		</div>

		<div class="col-md-6" style="float: right;">
			<ul class="list-group pdf-list-group">
				<li class="list-group-item pdf-list-group-item-active"><?php echo $this->lang->line('Malware Scan Info'); ?></li>

				<li class="list-group-item"><b><?php echo $this->lang->line('Google Safe Browser Norton'); ?> : </b>
					<?php echo $domain_info[0]['google_safety_status']; ?>
				</li>

				<li class="list-group-item"><b><?php echo $this->lang->line('Norton'); ?> : </b>
					<?php echo $domain_info[0]['norton_status']; ?>
				</li>
			</ul>	
			<br><br><br>
			<ul class="list-group pdf-list-group">
				<li class="list-group-item pdf-list-group-item-active"><?php echo $this->lang->line('Search Engine Index Info'); ?></li>

				<li class="list-group-item"><b><?php echo $this->lang->line('Google Index'); ?> : </b>
					<?php echo $domain_info[0]['google_index_count']; ?>
				</li>

				<li class="list-group-item"><b><?php echo $this->lang->line('Bing Index'); ?> : </b>
					<?php echo $domain_info[0]['bing_index_count']; ?>
				</li>

				<li class="list-group-item"><b><?php echo $this->lang->line('Yahoo Index'); ?> : </b>
					<?php echo $domain_info[0]['yahoo_index_count']; ?>
				</li>
			</ul>	
		</div>
	</div>

	<div class="row">
		<div class="col-md-6" style="float: left;">
			<ul class="list-group pdf-list-group">
				<li class="list-group-item pdf-list-group-item-active"><?php echo $this->lang->line('Sites in Same IP'); ?></li>
				<?php 
					$sites_in_same_ip = json_decode($domain_info[0]["sites_in_same_ip"],true);
					if(is_array($sites_in_same_ip))
					{
						$sites_in_same_ip = array_slice($sites_in_same_ip,1,18);
						$i=1;
						foreach($sites_in_same_ip as $key=>$value)
						{
							echo '<li class="list-group-item">'.$i.'. '.$value.'</li>';
							$i++;
						}
					}

					if(count($sites_in_same_ip)==0) echo '<li class="list-group-item">'.$this->lang->line("No data to show").'</li>';
				?>

			</ul>	
		</div>

		<div class="col-md-6" style="float: right;">
			<ul class="list-group pdf-list-group">
				<li class="list-group-item pdf-list-group-item-active"><?php echo $this->lang->line('Related Websites'); ?></li>

				<?php 
					$similar_sites = explode(',', $domain_info[0]['similar_site']);
					$i=1;
					foreach($similar_sites as $key=>$value){
						echo '<li class="list-group-item">'.$i.'. '.$value.'</li>';
						$i++;
					}

					if(count($similar_sites)==0) echo '<li class="list-group-item">'.$this->lang->line("No data to show").'</li>';
				?>
			</ul>	
		</div>
	</div>

	<div class="row">
		<div class="col-12">
			<h3><div class="well text-center"><?php echo "Social Network Information - ".$domain_info[0]['domain_name']; ?></div></h3>
		</div>
	</div>

	<div class="row">
		<div class="col-12">
			<ul class="list-group pdf-list-group">
				<li class="list-group-item pdf-list-group-item-active"><?php echo $this->lang->line('Social Network Information'); ?></li>
				
				<li class="list-group-item">
					<div class="col-6" style="float:left;text-align: left;">
						<b style="color:#3578E5 !important;"><?php echo $this->lang->line('Facebook Share'); ?> : </b>
						<?php echo $info['fb_total_share']; ?>
					</div>
					<div class="col-6" style="float:right;text-align: left;">
						<b style="color:#e60023 !important;"><?php echo $this->lang->line('Pinterest Info'); ?> : </b>
						<?php echo $info['pinterest_pin']; ?>
					</div>
				</li>
				<li class="list-group-item">
					<div class="col-6" style="float:left;text-align: left;">
						<b style="color:#3578E5 !important;"><?php echo $this->lang->line('Facebook Comment'); ?> : </b>
						<?php echo $info['fb_total_comment']; ?>
					</div>
					<div class="col-6" style="float:right;text-align: left;">
						<b style="color:#197d2f !important;"><?php echo $this->lang->line('Xing Info'); ?> : </b>
						<?php echo $info['xing_share']; ?>
					</div>

				</li>
				<li class="list-group-item">
					<div class="col-6" style="float:left;text-align: left;">
						<b style="color:#3578E5 !important;"><?php echo $this->lang->line('Facebook Like'); ?> : </b>
						<?php echo $info['fb_total_like']; ?>
					</div>
					<div class="col-6" style="float:right;text-align: left;">
						<b style="color:#231f20 !important;"><?php echo $this->lang->line('Buffer Info'); ?> : </b>
						<?php echo $info['buffer_share']; ?>
					</div>
				</li>
				<li class="list-group-item">
					<div class="col-6" style="float:left;text-align: left;">
						<b style="color:#ff4500 !important;"><?php echo $this->lang->line('Reddit Score'); ?> : </b>
						<?php echo $info['reddit_score']; ?>
					</div>
					<div class="col-6" style="float:right;text-align: left;">
						<b style="color:#ff4500 !important;"><?php echo $this->lang->line('Reddit Ups'); ?> : </b>
						<?php echo $info['reddit_ups']; ?>
					</div>
				</li>
				<li class="list-group-item">
					<div class="col-6" style="float:left;text-align: left;">
						<b style="color:#ff4500 !important;"><?php echo $this->lang->line('Reddit Downs'); ?> : </b>
						<?php echo $info['reddit_downs']; ?>
					</div>
				</li>
			</ul>	
		</div>
	</div>
</div>

<!-- ************************************************************************************************** -->


<!-- *************************************meta tag info *********************************************** -->
<?php
	$h1 = json_decode($domain_info[0]['h1'],true);
	$h2 = json_decode($domain_info[0]['h2'],true);
	$h3 = json_decode($domain_info[0]['h3'],true);
	$h4 = json_decode($domain_info[0]['h4'],true);
	$h5 = json_decode($domain_info[0]['h5'],true);
	$h6 = json_decode($domain_info[0]['h6'],true);
	$meta_tag_information = json_decode($domain_info[0]['meta_tag_information'],true);
	$blocked_by_robot_txt = $domain_info[0]['blocked_by_robot_txt'];
	$blocked_by_meta_robot = $domain_info[0]['blocked_by_meta_robot'];
	$nofollowed_by_meta_robot = $domain_info[0]['nofollowed_by_meta_robot'];
	$one_phrase = json_decode($domain_info[0]['one_phrase'],true);
	$two_phrase = json_decode($domain_info[0]['two_phrase'],true);
	$three_phrase = json_decode($domain_info[0]['three_phrase'],true);
	$four_phrase = json_decode($domain_info[0]['four_phrase'],true);
	$total_words = $domain_info[0]['total_words'];
	$domain_name = $domain_info[0]['domain_name'];

	$array_spam_keyword = array( "as seen on","buying judgments", "order status", "dig up dirt on friends",
 "additional income", "double your", "earn per week", "home based", "income from home", "money making",
 "opportunity", "while you sleep", "$$$", "beneficiary", "cash", "cents on the dollar", "claims",
 "cost", "discount", "f r e e", "hidden assets", "incredible deal", "loans", "money",
 "mortgage rates", "one hundred percent free", "price", "quote", "save big money", "subject to credit",
 "unsecured debt", "accept credit cards", "credit card offers", "investment decision",
 "no investment", "stock alert", "avoid bankruptcy", "consolidate debt and credit",
 "eliminate debt", "get paid", "lower your mortgage rate", "refinance home", "acceptance",
 "chance", "here", "leave", "maintained", "never", "remove", "satisfaction", "success", 
 "dear [email/friend/somebody]", "ad", "click", "click to remove", "email harvest", "increase sales",
 "internet market", "marketing solutions", "month trial offer", "notspam",
 "open", "removal instructions", "search engine listings", "the following form", "undisclosed recipient",
 "we hate spam", "cures baldness", "human growth hormone", "lose weight spam", "online pharmacy", 
 "stop snoring", "vicodin", "#1", "4u", "billion dollars", "million", "being a member",
 "cannot be combined with any other offer", "financial freedom", "guarantee",
 "important information regarding", "mail in order form", "nigerian", "no claim forms", "no gimmick", 
 "no obligation", "no selling", "not intended", "offer", "priority mail", "produced and sent out",
 "stuff on sale", "they’re just giving it away", "unsolicited", "warranty", "what are you waiting for?",
 "winner", "you are a winner!", "cancel at any time", "get", "print out and fax", "free", 
 "free consultation", "free grant money", "free instant", "free membership", "free preview ",
  "free sample ", "all natural", "certified", "fantastic deal", "it’s effective",  "real thing",
 "access", "apply online", "can't live without", "don't hesitate", "for you", "great offer", "instant", 
 "now", "once in lifetime", "order now", "special promotion", "time limited", "addresses on cd",
 "brand new pager", "celebrity", "legal", "phone", "buy", "clearance", "orders shipped by", 
 "meet singles", "be your own boss", "earn $", "expect to earn", "home employment", "make $",
 "online biz opportunity", "potential earnings", "work at home", "affordable",
 "best price", "cash bonus", "cheap", "collect", "credit", "earn", "fast cash",
 "hidden charges", "insurance", "lowest price", "money back", "no cost", "only '$'", "profits", 
 "refinance",  "save up to",  "they keep your money -- no refund!",  "us dollars",
 "cards accepted", "explode your business", "no credit check", "requires initial investment",
 "stock disclaimer statement ", "calling creditors", "consolidate your debt", "financially independent",
 "lower interest rate", "lowest insurance rates", "social security number", "accordingly", "dormant",
 "hidden", "lifetime", "medium", "passwords", "reverses", "solution", "teen", "friend",
 "auto email removal", "click below", "direct email", "email marketing",
 "increase traffic", "internet marketing", "mass email", "more internet traffic", "one time mailing",
 "opt in", "sale", "search engines", "this isn't junk", "unsubscribe",
 "web traffic", "diagnostics", "life insurance", "medicine", "removes wrinkles",
 "valium", "weight loss", "100% free", "50% off", "join millions",
 "one hundred percent guaranteed", "billing address", "confidentially on all orders", "gift certificate",
 "have you been turned down?", "in accordance with laws", "message contains", "no age restrictions", 
 "no disappointment", "no inventory", "no purchase necessary", "no strings attached", "obligation",
 "per day", "prize", "reserves the right", "terms and conditions", "trial", "vacation",
 "we honor all", "who really wins?", "winning", "you have been selected",
 "compare", "give it away", "see for yourself", "free access", "free dvd", "free hosting",
 "free investment", "free money", "free priority mail", "free trial",
 "all new", "congratulations", "for free", "outstanding values", "risk free",
 "act now!", "call free", "do it today", "for instant access", "get it now",
 "info you requested", "limited time", "now only", "one time", "order today",
 "supplies are limited", "urgent", "beverage", "cable converter", "copy dvds", "luxury car",
 "rolex", "buy direct", "order", "shopper", "score with babes", "compete for your business",
 "earn extra cash", "extra income", "homebased business", "make money", "online degree", 
 "university diplomas", "work from home", "bargain", "big bucks", "cashcashcash",  "check",
 "compare rates", "credit bureaus", "easy terms", 'for just "$xxx"',  "income",  "investment",
 "million dollars", "mortgage", "no fees", "pennies a day", "pure profit",  "save $",
 "serious cash", "unsecured credit", "why pay more?", "check or money order", "full refund",
 "no hidden costs", "sent in compliance", "stock pick", "collect child support",
 "eliminate bad credit", "get out of debt", "lower monthly payment", "pre-approved",
 "your income", "avoid", "freedom", "home",  "lose", "miracle", "problem", "sample",
 "stop", "wife", "hello", "bulk email", "click here", "direct marketing", "form",
 "increase your sales", "marketing", "member", "multi level marketing", "online marketing", 
 "performance", "sales", "subscribe", "this isn't spam", "visit our website", 
 "will not believe your eyes", "fast viagra delivery", "lose weight",
 "no medical exams", "reverses aging", "viagra", "xanax", "100% satisfied",  "billion", 
 "join millions of americans",  "thousands", "call", "deal", "giving away",
 "if only it were that easy", "long distance phone offer", "name brand", "no catch",
 "no experience", "no middleman", "no questions asked",  "no-obligation", "off shore", "per week", 
 "prizes", "shopping spree", "the best rates", "unlimited", "vacation offers",  "weekend getaway",
 "win", "won", "you’re a winner!", "copy accurately", "print form signature",
 "sign up free today", "free cell phone", "free gift", "free installation",
 "free leads", "free offer", "free quote", "free website",  "amazing",  "drastically reduced",
 "guaranteed", "promise you", "satisfaction guaranteed", "apply now",
 "call now", "don't delete", "for only", "get started now",  "information you requested",
 "new customers only", "offer expires", "only", "please read",
 "take action now", "while supplies last", "bonus", "casino",
 "laser printer", "new domain extensions", "stainless steel"
 );
?>

<div class="row">
	<div class="col-12">
		<h3><div class="well text-center"><?php echo "Keyword & Meta Information - ".$domain_info[0]['domain_name']; ?></div></h3>
	</div>
</div>

<div class="row">
	<div class="col-12" >
		<ul class="list-group pdf-list-group">
			<li class="list-group-item pdf-list-group-item-active"><?php echo $this->lang->line('TITLE & METATAGS'); ?></li>
			<?php if(is_array($meta_tag_information) && count($meta_tag_information) > 0) : ?>
				<?php foreach($meta_tag_information as $key=>$value): ?>
				<li class="list-group-item">
					<div style="font-size: 14px;font-weight: bold;color:#6777ef"><?php echo ucfirst($key); ?></div>
					<div style="word-spacing: 3px;margin-bottom: 5px;"><?php echo $value; ?></div>
				</li>
				<?php endforeach; ?>
			<?php else : ?>
				<a class="list-group-item list-group-item-action flex-column align-items-start pointer border-right-0 border-left-0">
					<div class="d-block w-100  text-center justify-content-between">
						<h4 class="mb-1 text-danger"><?php echo $this->lang->line('No data Found !'); ?></h4>
					</div>
				</a>
			<?php endif; ?>
		</ul>	
	</div>
</div>

<div class="row">
	<ul class="list-group pdf-list-group">
		<div class="col-6" style="float:left; margin-bottom:8px;font-size: 13px;">
			<li class="list-group-item" style="border:none;border-top: 1px solid #6777ef;">
				<strong><?php echo $this->lang->line('BLOCKED BY ROBOTS.TXT'); ?> : </strong> 
				<span style="color:#4a2fb5">
					<?php 
						if($blocked_by_robot_txt == 'No') echo 'No'; 
						if($blocked_by_robot_txt == 'Yes') echo 'Yes'; 
					?>
				</span>			
			</li>
		</div>	
		<div class="col-6" style="float:right; margin-bottom:8px;font-size: 13px;">
			<li class="list-group-item" style="border:none;border-top: 1px solid #6777ef;">
				<strong><?php echo $this->lang->line('BLOCKED BY META-ROBOTS'); ?> : </strong> 	
				<span style="color:#4a2fb5">
					<?php 
						if($blocked_by_meta_robot == 'No') echo 'No'; 
						if($blocked_by_meta_robot == 'Yes') echo 'Yes'; 
					?>
					
				</span>		
			</li>
		</div>	
		<div class="col-6" style="float:left;font-size: 13px;">
			<li class="list-group-item" style="border:none;border-top: 1px solid #6777ef;">
				<strong><?php echo $this->lang->line('LINKS NOFOLLOWED BY META-ROBOTS'); ?> : </strong> 
				<span style="color:#4a2fb5">
					<?php 
						if($nofollowed_by_meta_robot == 'No') echo 'No'; 
						if($nofollowed_by_meta_robot == 'Yes') echo 'Yes'; 
					?>
				</span>		   
			</li>
		</div>

		<div class="col-6" style="float:right;font-size: 13px;">
			<li class="list-group-item" style="border:none;border-top: 1px solid #6777ef;">
				<strong><?php echo $this->lang->line('TOTAL KEYWORDS'); ?> : </strong> 
				<span style="color:#4a2fb5">
					<?php echo $total_words; ?>
				</span>
			</li>
		</div>	
	</ul>	
</div>

<div class="row">
	<div class="col-12">
		<ul class="list-group pdf-list-group">
			<li class="list-group-item pdf-list-group-item-active"><?php echo $this->lang->line('HTML HEADINGS'); ?></li>
			
			<li class="list-group-item" style="border:none !important;">
				<ul class="list-group pdf-list-group" style="padding: 0 30px !important">
					<li style="font-weight:bold;color:#191919;border: solid 1px #E1E1E1;padding: 10px;background-color:#E1E1E1;list-style:none !important">
						H1(<?php echo count($h1) ?>)
					</li>
					<?php $h1_no = 1; ?>
					<?php if(is_array($h1) && count($h1) > 0) { ?>
						<?php foreach($h1 as $key=>$value): ?>
						<li class="list-group-item">
							<div style="text-align:left;word-spacing: 3px;margin-bottom: 5px;"><?php echo $h1_no.'. '.$value; ?></div>
							<?php $h1_no++; ?>
						</li>
						<?php endforeach; ?>
					<?php } else { ?>
						<li class="list-group-item" style="text-align: center;color:#fc544b !important"><?php echo $this->lang->line('No h1 tag found'); ?></li>
					<?php } ?>
				</ul>
			</li>

			<li class="list-group-item" style="border:none !important;">
				<ul class="list-group pdf-list-group" style="padding: 0 30px !important">
					<li style="font-weight:bold;color:#191919;border: solid 1px #E1E1E1;padding: 10px;background-color:#E1E1E1;list-style:none !important">
						H2(<?php echo count($h2) ?>)
					</li>
					<?php $h2_no = 1; ?>
					<?php if(is_array($h2) && count($h2) > 0) { ?>
						<?php foreach($h2 as $key=>$value): ?>
						<li class="list-group-item">
							<div style="text-align:left;word-spacing: 3px;margin-bottom: 5px;"><?php echo $h2_no.'. '.$value; ?></div>
							<?php $h2_no++; ?>
						</li>
						<?php endforeach; ?>
					<?php } else { ?>
						<li class="list-group-item" style="text-align: center;color:#fc544b !important"><?php echo $this->lang->line('No h2 tag found'); ?></li>
					<?php } ?>
				</ul>
			</li>
			<li class="list-group-item" style="border:none !important;">
				<ul class="list-group pdf-list-group" style="padding: 0 30px !important">
					<li style="font-weight:bold;color:#191919;border: solid 1px #E1E1E1;padding: 10px;background-color:#E1E1E1;list-style:none !important">
						H3(<?php echo count($h3) ?>)
					</li>
					<?php $h3_no = 1; ?>
					<?php if(is_array($h3) && count($h3) > 0) { ?>
						<?php foreach($h3 as $key=>$value): ?>
						<li class="list-group-item">
							<div style="text-align:left;word-spacing: 3px;margin-bottom: 5px;"><?php echo $h3_no.'. '.$value; ?></div>
							<?php $h3_no++; ?>
						</li>
						<?php endforeach; ?>
					<?php } else { ?>
						<li class="list-group-item" style="text-align: center;color:#fc544b !important"><?php echo $this->lang->line('No h3 tag found'); ?></li>
					<?php } ?>
				</ul>
			</li>
			<li class="list-group-item" style="border:none !important;">
				<ul class="list-group pdf-list-group" style="padding: 0 30px !important">
					<li style="font-weight:bold;color:#191919;border: solid 1px #E1E1E1;padding: 10px;background-color:#E1E1E1;list-style:none !important">
						H4(<?php echo count($h4) ?>)
					</li>
					<?php $h4_no = 1; ?>
					<?php if(is_array($h4) && count($h4) > 0) { ?>
						<?php foreach($h4 as $key=>$value): ?>
						<li class="list-group-item">
							<div style="text-align:left;word-spacing: 3px;margin-bottom: 5px;"><?php echo $h4_no.'. '.$value; ?></div>
							<?php $h4_no++; ?>
						</li>
						<?php endforeach; ?>
					<?php } else { ?>
						<li class="list-group-item" style="text-align: center;color:#fc544b !important"><?php echo $this->lang->line('No h4 tag found'); ?></li>
					<?php } ?>
				</ul>
			</li>
			<li class="list-group-item" style="border:none !important;">
				<ul class="list-group pdf-list-group" style="padding: 0 30px !important">
					<li style="font-weight:bold;color:#191919;border: solid 1px #E1E1E1;padding: 10px;background-color:#E1E1E1;list-style:none !important">
						H5(<?php echo count($h5) ?>)
					</li>
					<?php $h5_no = 1; ?>
					<?php if(is_array($h5) && count($h5) > 0) { ?>
						<?php foreach($h5 as $key=>$value): ?>
						<li class="list-group-item">
							<div style="text-align:left;word-spacing: 3px;margin-bottom: 5px;"><?php echo $h5_no.'. '.$value; ?></div>
							<?php $h5_no++; ?>
						</li>
						<?php endforeach; ?>
					<?php } else { ?>
						<li class="list-group-item" style="text-align: center;color:#fc544b !important"><?php echo $this->lang->line('No h5 tag found'); ?></li>
					<?php } ?>
				</ul>
			</li>
			<li class="list-group-item" style="border:none !important;">
				<ul class="list-group pdf-list-group" style="padding: 0 30px !important">
					<li style="font-weight:bold;color:#191919;border: solid 1px #E1E1E1;padding: 10px;background-color:#E1E1E1;list-style:none !important">
						H6(<?php echo count($h6) ?>)
					</li>
					<?php $h6_no = 1; ?>
					<?php if(is_array($h6) && count($h6) > 0) { ?>
						<?php foreach($h6 as $key=>$value): ?>
						<li class="list-group-item">
							<div style="text-align:left;word-spacing: 3px;margin-bottom: 5px;"><?php echo $h6_no.'. '.$value; ?></div>
							<?php $h6_no++; ?>
						</li>
						<?php endforeach; ?>
					<?php } else { ?>
						<li class="list-group-item" style="text-align: center;color:#fc544b !important"><?php echo $this->lang->line('No h6 tag found'); ?></li>
					<?php } ?>
				</ul>
			</li>
		</ul>
	</div>
</div>

<div class="row">
	<div class="col-12">
		<ul class="list-group pdf-list-group">
			<li class="list-group-item pdf-list-group-item-active"><?php echo $this->lang->line('KEYWORD ANALYSIS'); ?></li>
			
			<li class="list-group-item" style="border:none !important;">
				<ul class="list-group pdf-list-group" style="padding: 0 30px !important;">
					<li class="table-styles">== <?php echo $this->lang->line('Single Word Keywords'); ?> ==</li>
					<li class="list-group-item" style="padding:0 !important;border:none !important;">
						<div class="table-responsive">
							<table class="table table-bordered">
								<thead>
									<tr>
										<th>SINGLE KEYWORDS</th>
										<th>OCCURRENCES</th>
										<th>DENSITY</th>
										<th>POSSIBLE SPAM</th>
									</tr>
								</thead>

								<tbody>
									<?php if(is_array($one_phrase) && count($one_phrase) > 0) : ?>
									<?php foreach ($one_phrase as $key => $value) : ?>
										<tr>
											<td><?php echo $key; ?></td>
											<td><?php echo $value; ?></td>
											<td><?php $occurence = ($value/$total_words)*100; echo round($occurence, 3)." %"; ?></td>
											<td><?php 
													if(in_array(strtolower($key), $array_spam_keyword)) echo "Yes";
													else echo 'No'; 
												?>
											</td>
										</tr>
									<?php endforeach; ?>
									<?php else : ?>
										<tr><td colspan="4" style="text-align: center;color:#fc544b !important"><?php echo $this->lang->line("No data found"); ?></td></tr>
									<?php endif; ?>
								</tbody>
							</table>
						</div>
					</li>
				</ul>
			</li>	

			<li class="list-group-item" style="border:none !important;">
				<ul class="list-group pdf-list-group" style="padding: 0 30px !important;">
					<li class="table-styles">== <?php echo $this->lang->line('Two Words Keywords'); ?> ==</li>
					<li class="list-group-item" style="padding:0 !important;border:none !important;">
						<div class="table-responsive">
							<table class="table table-bordered">
								<thead>
									<tr>
										<th>2 WORD PHRASES</th>
										<th>OCCURRENCES</th>
										<th>DENSITY</th>
										<th>POSSIBLE SPAM</th>
									</tr>
								</thead>

								<tbody>
									<?php if(is_array($two_phrase) && count($two_phrase) > 0) : ?>
									<?php foreach ($two_phrase as $key => $value) : ?>
										<tr>
											<td><?php echo $key; ?></td>
											<td><?php echo $value; ?></td>
											<td><?php $occurence = $value/$total_words*100; echo round($occurence, 3)." %"; ?></td>
											<td><?php 
													if(in_array(strtolower($key), $array_spam_keyword)) echo "Yes";
													else echo 'No'; 
												?>
											</td>
										</tr>
									<?php endforeach; ?>
									<?php else : ?>
										<tr><td colspan="4" style="text-align: center;color:#fc544b !important"><?php echo $this->lang->line("No data found"); ?></td></tr>
									<?php endif; ?>
								</tbody>
							</table>
						</div>
					</li>
				</ul>
			</li>

			<li class="list-group-item" style="border:none !important;">
				<ul class="list-group pdf-list-group" style="padding: 0 30px !important;">
					<li class="table-styles">== <?php echo $this->lang->line('Three Words Keywords'); ?> ==</li>
					<li class="list-group-item" style="padding:0 !important;border:none !important;">
						<div class="table-responsive">
							<table class="table table-bordered">
								<thead>
									<tr>
										<th>3 WORD PHRASES</th>
										<th>OCCURRENCES</th>
										<th>DENSITY</th>
										<th>POSSIBLE SPAM</th>
									</tr>
								</thead>

								<tbody>
									<?php if(is_array($three_phrase) &&count($three_phrase) > 0) : ?>
									<?php foreach ($three_phrase as $key => $value) : ?>
										<tr>
											<td><?php echo $key; ?></td>
											<td><?php echo $value; ?></td>
											<td><?php $occurence = $value/$total_words*100; echo round($occurence, 3)." %"; ?></td>
											<td><?php 
													if(in_array(strtolower($key), $array_spam_keyword)) echo "Yes";
													else echo 'No'; 
												?>
											</td>
										</tr>
									<?php endforeach; ?>
									<?php else : ?>
										<tr><td colspan="4" style="text-align: center;color:#fc544b !important"><?php echo $this->lang->line("No data found"); ?></td></tr>
									<?php endif; ?>
								</tbody>
							</table>
						</div>
					</li>
				</ul>
			</li>

			<li class="list-group-item" style="border:none !important;">
				<ul class="list-group pdf-list-group" style="padding: 0 30px !important;">
					<li class="table-styles">== <?php echo $this->lang->line('Four Words Keywords'); ?> ==</li>
					<li class="list-group-item" style="padding:0 !important;border:none !important;">
						<div class="table-responsive">
							<table class="table table-bordered">
								<thead>
									<tr>
										<th>4 WORD PHRASES</th>
										<th>OCCURRENCES</th>
										<th>DENSITY</th>
										<th>POSSIBLE SPAM</th>
									</tr>
								</thead>

								<tbody>
									<?php if(is_array($four_phrase) &&count($four_phrase) > 0) : ?>
									<?php foreach ($four_phrase as $key => $value) : ?>
										<tr>
											<td><?php echo $key; ?></td>
											<td><?php echo $value; ?></td>
											<td><?php $occurence = $value/$total_words*100; echo round($occurence, 3)." %"; ?></td>
											<td><?php 
													if(in_array(strtolower($key), $array_spam_keyword)) echo "Yes";
													else echo 'No'; 
												?>
											</td>
										</tr>
									<?php endforeach; ?>
									<?php else : ?>
										<tr><td colspan="4" style="text-align: center;color:#fc544b !important"><?php echo $this->lang->line("No data found"); ?></td></tr>
									<?php endif; ?>
								</tbody>
							</table>
						</div>
					</li>
				</ul>
			</li>

		</ul>
	</div>
</div>

<!-- ************************************************************************************************************** -->



<!-- **********************************************alexa data****************************************** -->
<?php 
	$alexa_data_full=array();
	$alexa_data_full["domain_name"]="";
	$alexa_data_full["alexa_rank"]="";
	$alexa_data_full["site_search_traffic"]="";
	$alexa_data_full["alexa_rank_spend_time"]="";
	$alexa_data_full["bounce_rate"]="";
	$alexa_data_full["total_sites_linking_in"]="";
	$alexa_data_full["total_keyword_opportunities_breakdown"]=array();
	$alexa_data_full["keyword_opportunitites_values"]=array();
	$alexa_data_full["similar_sites"]=array();
	$alexa_data_full["similar_site_overlap"]=array();
	$alexa_data_full["keyword_top"]=array();
	$alexa_data_full["search_traffic"]=array();
	$alexa_data_full["share_voice"]=array();
	$alexa_data_full["keyword_gaps"]=array();
	$alexa_data_full["keyword_gaps_trafic_competitor"]=array();
	$alexa_data_full["keyword_gaps_search_popularity"]=array();
	$alexa_data_full["easyto_rank_keyword"]=array();
	$alexa_data_full["easyto_rank_relevence"]=array();
	$alexa_data_full["easyto_rank_search_popularity"]=array();
	$alexa_data_full["buyer_keyword"]=array();
	$alexa_data_full["buyer_keyword_traffic_to_competitor"]=array();
	$alexa_data_full["buyer_keyword_organic_competitor"]=array();
	$alexa_data_full["optimization_opportunities"]=array();
	$alexa_data_full["optimization_opportunities_search_popularity"]=array();
	$alexa_data_full["optimization_opportunities_organic_share_of_voice"]=array();
	$alexa_data_full["refferal_sites"]=array();
	$alexa_data_full["refferal_sites_links"]=array();
	$alexa_data_full["top_keywords"]=array();
	$alexa_data_full["top_keywords_search_traficc"]=array();
	$alexa_data_full["top_keywords_share_of_voice"]=array();
	$alexa_data_full["site_overlap_score"]=array();
	$alexa_data_full["similar_to_this_sites"]=array();
	$alexa_data_full["similar_to_this_sites_alexa_rank"]=array();
	$alexa_data_full["card_geography_country"]=array();
	$alexa_data_full["card_geography_countryPercent"]=array();
	$alexa_data_full["site_metrics"]=array();
	$alexa_data_full["site_metrics_domains"]=array();
	if(array_key_exists(0,$alexa_data))
		$alexa_data_full=$alexa_data[0];



	$domain =$alexa_data_full["domain_name"];
	$alexa_rank =$alexa_data_full["alexa_rank"];
	$alexa_rank_spend_time =$alexa_data_full["alexa_rank_spend_time"];
	$site_search_traffic =$alexa_data_full["site_search_traffic"];
	$bounce_rate =$alexa_data_full["bounce_rate"];
	$total_sites_linking_in =$alexa_data_full["total_sites_linking_in"];
	$keyword_opportunitites_values = json_decode($alexa_data_full["keyword_opportunitites_values"],true);

	$keyword_opportunitites_values_pie_chart = array(
		$this->lang->line("Keyword Gaps") => isset($keyword_opportunitites_values[0]) ? $keyword_opportunitites_values[0] : 0,
		$this->lang->line("Easy-to-Rank Keywords") => isset($keyword_opportunitites_values[1]) ? $keyword_opportunitites_values[1] : 0,
		$this->lang->line("Optimization Opportunities") => isset($keyword_opportunitites_values[2]) ? $keyword_opportunitites_values[2] : 0,
		$this->lang->line("Buyer Keywords") => isset($keyword_opportunitites_values[3]) ? $keyword_opportunitites_values[3] : 0,
	);


	$total_keyword_opportunities_breakdown = $alexa_data_full["total_keyword_opportunities_breakdown"];
	$alexa_similar_sites = json_decode($alexa_data_full["similar_sites"],true);
	$similar_site_overlap = json_decode($alexa_data_full["similar_site_overlap"],true);
	$keyword_top = json_decode($alexa_data_full["keyword_top"],true);
	$search_traffic = json_decode($alexa_data_full["search_traffic"],true);
	$share_voice = json_decode($alexa_data_full["share_voice"],true);
	$keyword_gaps = json_decode($alexa_data_full["keyword_gaps"],true);
	$keyword_gaps_trafic_competitor = json_decode($alexa_data_full["keyword_gaps_trafic_competitor"],true);
	$keyword_gaps_search_popularity = json_decode($alexa_data_full["keyword_gaps_search_popularity"],true);
	$easyto_rank_keyword = json_decode($alexa_data_full["easyto_rank_keyword"],true);
	$easyto_rank_relevence = json_decode($alexa_data_full["easyto_rank_relevence"],true);
	$easyto_rank_search_popularity = json_decode($alexa_data_full["easyto_rank_search_popularity"],true);
	$buyer_keyword = json_decode($alexa_data_full["buyer_keyword"],true);
	$buyer_keyword_traffic_to_competitor = json_decode($alexa_data_full["buyer_keyword_traffic_to_competitor"],true);
	$buyer_keyword_organic_competitor = json_decode($alexa_data_full["buyer_keyword_organic_competitor"],true);
	$optimization_opportunities = json_decode($alexa_data_full["optimization_opportunities"],true);
	$optimization_opportunities_search_popularity = json_decode($alexa_data_full["optimization_opportunities_search_popularity"],true);
	$optimization_opportunities_organic_share_of_voice = json_decode($alexa_data_full["optimization_opportunities_organic_share_of_voice"],true);
	$refferal_sites = json_decode($alexa_data_full["refferal_sites"],true);
	$similar_site_overlap = json_decode($alexa_data_full["similar_site_overlap"],true);
	$site_overlap_score = json_decode($alexa_data_full["site_overlap_score"],true);
	$similar_to_this_sites = json_decode($alexa_data_full["similar_to_this_sites"],true);
	$similar_to_this_sites_alexa_rank = json_decode($alexa_data_full["similar_to_this_sites_alexa_rank"],true);
	$card_geography_country = json_decode($alexa_data_full["card_geography_country"],true);
	$card_geography_countryPercent = json_decode($alexa_data_full["card_geography_countryPercent"],true);
	$site_metrics = json_decode($alexa_data_full["site_metrics"],true);
	$site_metrics_domains = json_decode($alexa_data_full["site_metrics_domains"],true);

?>

<div class="row">
	<div class="col-12">
		<h3><div class="well text-center"><?php echo $this->lang->line('Alexa Information').' - '.$domain_info[0]['domain_name']; ?></div></h3>
	</div>
</div>

<div class="row">
	<div class="col-12">
		<ul class="list-group pdf-list-group">
			<li class="list-group-item pdf-list-group-item-active"><?php echo $this->lang->line('General Information'); ?></li>
			
			<li class="list-group-item">
				<div class="col-6" style="float:left;text-align: left;">
					<b style="color:#6777ef !important;"><?php echo $this->lang->line('Domain Name'); ?> : </b>
					<?php echo $domain; ?>
				</div>
				<div class="col-6" style="float:right;text-align: left;">
					<b style="color:#63ed7a !important;"><?php echo $this->lang->line('Global Rank'); ?> : </b>
					<?php echo $alexa_rank; ?>
				</div>
			</li>
			<li class="list-group-item">
				<div class="col-6" style="float:left;text-align: left;">
					<b style="color:#ffa426 !important;"><?php echo $this->lang->line('Daily Time on Site'); ?> : </b>
					<?php echo $alexa_rank_spend_time; ?>
				</div>
				<div class="col-6" style="float:right;text-align: left;">
					<b style="color:#3abaf4 !important;"><?php echo $this->lang->line('Search Traffic'); ?> : </b>
					<?php echo $site_search_traffic; ?>
				</div>

			</li>
			<li class="list-group-item">
				<div class="col-6" style="float:left;text-align: left;">
					<b style="color:#fc544b !important;"><?php echo $this->lang->line('Bounce Rate'); ?> : </b>
					<?php echo $bounce_rate; ?>
				</div>
				<div class="col-6" style="float:right;text-align: left;">
					<b style="color:#a45fff !important;"><?php echo $this->lang->line('Total Sites Link In'); ?> : </b>
					<?php echo $total_sites_linking_in; ?>
				</div>
			</li>
		</ul>	
	</div>
</div>

<div class="row">
	<div class="col-12">
		<ul class="list-group pdf-list-group">
			<li class="list-group-item pdf-list-group-item-active"><?php echo $this->lang->line('Top 5 Similar Sites by Audience Overlap'); ?></li>
			<li class="list-group-item" style="padding:0 !important;text-align:center;border:none !important;">
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th><?php echo $this->lang->line('SL'); ?></th>
								<th><?php echo $this->lang->line('Similar sites'); ?></th>
								<th><?php echo $this->lang->line('Overlap score'); ?></th>
							</tr>
						</thead>

						<tbody>
							<?php 
								$sl=0;                  
					            if(is_array($alexa_similar_sites) && is_array($similar_site_overlap))
					            {
 				                    foreach ($alexa_similar_sites as $key => $value) {
 				                    	$sl++;
                    	                    if(array_key_exists($key, $alexa_similar_sites) && array_key_exists($key, $similar_site_overlap) )
                    	                    {
                    	                    	echo "<tr><td>".$sl."</td>";
                    		                    echo "<td>".$alexa_similar_sites[$key]."</td>";
                    		                    echo "<td>".$similar_site_overlap[$key]."</td></tr>";
                    		                   
                    		                }
 				                    }

					                if(count($alexa_similar_sites)==0 || count($similar_site_overlap)==0  )
					                echo "<tr><td>No data found!</td></tr>";
					            }
					            else
					            {
					            	echo "<tr><td>No data found!</td></tr>";
					            }

							?>
						</tbody>
					</table>
				</div>
			</li>
			
		</ul>
	</div>
</div>

<div class="row">
	<div class="col-12">
		<ul class="list-group pdf-list-group">
			<li class="list-group-item pdf-list-group-item-active"><?php echo $this->lang->line('Top 5 Keywords By Traffic'); ?></li>
			<li class="list-group-item" style="padding:0 !important;text-align:center;border:none !important;">
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th scope="col"><?php echo $this->lang->line('Keywords'); ?></th>
								<th scope="col"><?php echo $this->lang->line('Search Traffic'); ?></th>
								<th scope="col"><?php echo $this->lang->line('Share of Voice'); ?></th>
							</tr>
						</thead>

						<tbody>
							<?php      
					            if(is_array($keyword_top) && is_array($search_traffic) && is_array($share_voice)) {

 				                    foreach ($keyword_top as $key => $value) {
 				                    	
                    	                    if(array_key_exists($key, $keyword_top) && array_key_exists($key, $search_traffic) && array_key_exists($key, $share_voice))
                    	                    {
                    	                    	echo "<tr><td>".$keyword_top[$key]."</td>";
                    		                    echo "<td>".$search_traffic[$key]."</td>";
                    		                    echo "<td>".$share_voice[$key]."</td></tr>";
                    		                   
                    		                }
 				                    }

					                if(count($keyword_top)==0 || count($search_traffic)==0 || count($share_voice) ==0 )
					                	echo "<tr><td colspan='3'>No data found!</td></tr>";
					            }
					            else
					            {
					            	echo "<tr><td colspan='3'>No data found!</td></tr>";
					            }

							?>
						</tbody>
					</table>
				</div>
			</li>
			
		</ul>
	</div>
</div>

<div class="row">
	<div class="col-12">
		<ul class="list-group pdf-list-group">
			<li class="list-group-item pdf-list-group-item-active"><?php echo $this->lang->line('Top 4 Keyword Gaps'); ?></li>
			<li class="list-group-item" style="padding:0 !important;text-align:center;border:none !important;">
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th><?php echo $this->lang->line('Keywords driving traffic to competitors, but not to this site'); ?></th>
								<th><?php echo $this->lang->line('Avg. Traffic to Competitors'); ?></th>
								<th><?php echo $this->lang->line('Search Popularity'); ?></th>
							</tr>
						</thead>

						<tbody>
							<?php 
								           
					            if(is_array($keyword_gaps) && is_array($keyword_gaps_trafic_competitor) && is_array($keyword_gaps_search_popularity))
					            {
 				                    foreach ($keyword_gaps as $key => $value) {
 				                    	
                    	                    if(array_key_exists($key, $keyword_gaps) && array_key_exists($key, $keyword_gaps_trafic_competitor) && array_key_exists($key, $keyword_gaps_search_popularity))
                    	                    {
                    	                    	echo "<tr><td>".$keyword_gaps[$key]."</td>";
                    		                    echo "<td>".$keyword_gaps_trafic_competitor[$key]."</td>";
                    		                    echo "<td>".$keyword_gaps_search_popularity[$key]."</td></tr>";
                    		                   
                    		                }
 				                    }

					                if(count($keyword_gaps)==0 || count($keyword_gaps_trafic_competitor)==0 || count($keyword_gaps_search_popularity) ==0 )
					                echo "<tr><td colspan='3'>No data found!</td></tr>";
					            }
					            else
					            {
					            	echo "<tr><td colspan='3'>No data found!</td></tr>";
					            }

							?>
						</tbody>
					</table>
				</div>
			</li>
			
		</ul>
	</div>
</div>

<div class="row">
	<div class="col-12">
		<ul class="list-group pdf-list-group">
			<li class="list-group-item pdf-list-group-item-active"><?php echo $this->lang->line('Top 4 Easy-to-Rank Keywords'); ?></li>
			<li class="list-group-item" style="padding:0 !important;text-align:center;border:none !important;">
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th><?php echo $this->lang->line('Popular keywords within this site`s competitive power'); ?></th>
								<th><?php echo $this->lang->line('Relevance to this site'); ?></th>
								<th><?php echo $this->lang->line('Search Popularity'); ?></th>
							</tr>
						</thead>

						<tbody>
							<?php 
								           
					            if(is_array($easyto_rank_keyword) && is_array($easyto_rank_relevence) && is_array($easyto_rank_search_popularity))
					            {
					                    foreach ($easyto_rank_keyword as $key => $value) {
					                    	
	                	                    if(array_key_exists($key, $easyto_rank_keyword) && array_key_exists($key, $easyto_rank_relevence) && array_key_exists($key, $easyto_rank_search_popularity))
	                	                    {
	                	                    	echo "<tr><td>".$easyto_rank_keyword[$key]."</td>";
	                		                    echo "<td>".$easyto_rank_relevence[$key]."</td>";
	                		                    echo "<td>".$easyto_rank_search_popularity[$key]."</td></tr>";
	                		                   
	                		                }
					                    }

					                if(count($easyto_rank_keyword)==0 || count($easyto_rank_relevence)==0 || count($easyto_rank_search_popularity) ==0 )
					                echo "<tr><td colspan='4'>No data found!</td></tr>";
					            }
					            else
					            {
					            	echo "<tr><td colspan='4'>No data found!</td></tr>";
					            }

							?>
						</tbody>
					</table>
				</div>
			</li>
			
		</ul>
	</div>
</div>

<div class="row">
	<div class="col-12">
		<ul class="list-group pdf-list-group">
			<li class="list-group-item pdf-list-group-item-active"><?php echo $this->lang->line('Top 4 Buyer Keywords'); ?></li>
			<li class="list-group-item" style="padding:0 !important;text-align:center;border:none !important;">
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th><?php echo $this->lang->line('Keywords that show a high purchase intent'); ?></th>
								<th><?php echo $this->lang->line('Avg. Traffic to Competitors'); ?></th>
								<th><?php echo $this->lang->line('Organic Competition'); ?></th>
							</tr>
						</thead>

						<tbody>
							<?php 
								           
					            if(is_array($buyer_keyword) && is_array($buyer_keyword_traffic_to_competitor) && is_array($buyer_keyword_organic_competitor))
					            {
 				                    foreach ($buyer_keyword as $key => $value) {
 				                    	
                    	                    if(array_key_exists($key, $buyer_keyword) && array_key_exists($key, $buyer_keyword_traffic_to_competitor) && array_key_exists($key, $buyer_keyword_organic_competitor))
                    	                    {
                    	                    	echo "<tr><td>".$buyer_keyword[$key]."</td>";
                    		                    echo "<td>".$buyer_keyword_traffic_to_competitor[$key]."</td>";
                    		                    echo "<td>".$buyer_keyword_organic_competitor[$key]."</td></tr>";
                    		                   
                    		                }
 				                    }

					                if(count($buyer_keyword)==0 || count($buyer_keyword_traffic_to_competitor)==0 || count($buyer_keyword_organic_competitor) ==0 )
					                echo "<tr><td colspan='3'>No data found!</td></tr>";
					            }
					            else
					            {
					            	echo "<tr><td colspan='3'>No data found!</td></tr>";
					            }

							?>
						</tbody>
					</table>
				</div>
			</li>
			
		</ul>
	</div>
</div>

<div class="row">
	<div class="col-12">
		<ul class="list-group pdf-list-group">
			<li class="list-group-item pdf-list-group-item-active"><?php echo $this->lang->line('Top 4 Optimization Opportunities'); ?></li>
			<li class="list-group-item" style="padding:0 !important;text-align:center;border:none !important;">
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th><?php echo $this->lang->line('Very popular keywords already driving some traffic to this site'); ?></th>
								<th><?php echo $this->lang->line('Search Popularity'); ?></th>
								<th><?php echo $this->lang->line('Organic Share of Voice'); ?></th>
							</tr>
						</thead>

						<tbody>
							<?php 
								           
					            if(is_array($optimization_opportunities) && is_array($optimization_opportunities_search_popularity) && is_array($optimization_opportunities_organic_share_of_voice))
					            {
 				                    foreach ($optimization_opportunities as $key => $value) {
 				                    	
                    	                    if(array_key_exists($key, $optimization_opportunities) && array_key_exists($key, $optimization_opportunities_search_popularity) && array_key_exists($key, $optimization_opportunities_organic_share_of_voice))
                    	                    {
                    	                    	echo "<tr><td>".$optimization_opportunities[$key]."</td>";
                    		                    echo "<td>".$optimization_opportunities_search_popularity[$key]."</td>";
                    		                    echo "<td>".$optimization_opportunities_organic_share_of_voice[$key]."</td></tr>";
                    		                   
                    		                }
 				                    }

					                if(count($optimization_opportunities)==0 || count($optimization_opportunities_search_popularity)==0 || count($optimization_opportunities_organic_share_of_voice) ==0 )
					                echo "<tr><td colspan='3'>No data found!</td></tr>";
					            }
					            else
					            {
					            	echo "<tr><td colspan='3'>No data found!</td></tr>";
					            }

							?>
						</tbody>
					</table>
				</div>
			</li>
			
		</ul>
	</div>
</div>

<div class="row">
	<div class="col-12">
		<ul class="list-group pdf-list-group">
			<li class="list-group-item pdf-list-group-item-active"><?php echo $this->lang->line('Top 5 Referral Sites'); ?></li>
			<li class="list-group-item" style="padding:0 !important;text-align:center;border:none !important;">
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th scope="col"><?php echo $this->lang->line('Sites by how many other sites drive traffic to them'); ?></th>
								<th scope="col"><?php echo $this->lang->line('Referral Sites'); ?></th>
							</tr>
						</thead>

						<tbody>
							<?php         
					            if(is_array($refferal_sites) && is_array($similar_site_overlap) )
					            {
 				                    foreach ($refferal_sites as $key => $value) {
 				                    	
                    	                    if(array_key_exists($key, $refferal_sites) && array_key_exists($key, $similar_site_overlap) )
                    	                    {
                    	                    	echo "<tr><td>".$refferal_sites[$key]."</td>";
                    		                    echo "<td>".$similar_site_overlap[$key]."</td> </tr>";
                    		             
                    		                   
                    		                }
 				                    }

					                if(count($refferal_sites)==0 || count($similar_site_overlap)==0 )
					                echo "<tr><td colspan='2'>No data found!</td></tr>";
					            }
					            else
					            {
					            	echo "<tr><td colspan='2'>No data found!</td></tr>";
					            }

							?>
						</tbody>
					</table>
				</div>
			</li>
			
		</ul>
	</div>
</div>

<div class="row">
	<div class="col-12">
		<ul class="list-group pdf-list-group">
			<li class="list-group-item pdf-list-group-item-active"><?php echo $this->lang->line('Site Flow'); ?></li>
			<li class="list-group-item" style="padding:0 !important;text-align:center;border:none !important;">
				<div class="table-responsive">
					<table class="table table-bordered" style="text-align:left;">
						<thead>
							<tr>
								<th><?php echo $this->lang->line('Visited just before & right after domain'); ?></th>
								<th><?php echo $this->lang->line('Visited just before & right after domain Percentage'); ?></th>
							</tr>
						</thead>

						<tbody>
							<?php           
					            if(is_array($site_metrics_domains) && is_array($site_metrics) )
					            {
 				                    foreach ($site_metrics_domains as $key => $value) {
 				                    	
                    	                    if(array_key_exists($key, $site_metrics_domains) && array_key_exists($key, $site_metrics))
                    	                    {
                    	                    	echo "<tr><td>".$site_metrics_domains[$key]."</td>";
                    		                    echo "<td>".$site_metrics[$key]."</td></tr>";
                    		                   
                    		                   
                    		                }
 				                    }

					                if(count($site_metrics_domains)==0 || count($site_metrics)==0 )
					                echo "<tr><td colspan='2'>No data found!</td></tr>";
					            }
					            else
					            {
					            	echo "<tr><td colspan='2'>No data found!</td></tr>";
					            }

							?>
						</tbody>
					</table>
				</div>
			</li>
			
		</ul>
	</div>
</div>

<div class="row">
	<div class="col-12">
		<ul class="list-group pdf-list-group">
			<li class="list-group-item pdf-list-group-item-active"><?php echo $this->lang->line('Top 5 Audience Overlap'); ?></li>
			<li class="list-group-item" style="padding:0 !important;text-align:center;border:none !important;">
				<div class="table-responsive">
					<table class="table table-bordered" style="text-align:left;">
						<thead>
							<tr>
								<th><?php echo $this->lang->line('Similar Sites to This Site'); ?></th>
								<th><?php echo $this->lang->line('Site’s Overlap Score'); ?></th>
								<th><?php echo $this->lang->line('Alexa Rank'); ?></th>
							</tr>
						</thead>

						<tbody>
							<?php 
								           
					            if(is_array($site_overlap_score) && is_array($similar_to_this_sites) && is_array($similar_to_this_sites_alexa_rank))
					            {
					                    foreach ($similar_to_this_sites as $key => $value) {
					                    	
	                	                    if(array_key_exists($key, $similar_to_this_sites) && array_key_exists($key, $site_overlap_score)&& array_key_exists($key, $similar_to_this_sites_alexa_rank))
	                	                    {
	                	                    	echo "<tr><td>".$similar_to_this_sites[$key]."</td>";
	                		                    echo "<td>".$site_overlap_score[$key]."</td>";
	                		                    echo "<td>".$similar_to_this_sites_alexa_rank[$key]."</td></tr>";
	                		                   
	                		                   
	                		                }
					                    }

					                if(count($similar_to_this_sites)==0 || count($site_overlap_score)==0 || count($similar_to_this_sites_alexa_rank) == 0 )
					                echo "<tr><td colspan='3'>No data found!</td></tr>";
					            }
					            else
					            {
					            	echo "<tr><td colspan='3'>No data found!</td></tr>";
					            }

							?>
						</tbody>
					</table>
				</div>
			</li>
			
		</ul>
	</div>
</div>

<div class="row">
	<div class="col-12">
		<ul class="list-group pdf-list-group">
			<li class="list-group-item pdf-list-group-item-active"><?php echo $this->lang->line('Top 3 Audience Geography'); ?></li>
			<li class="list-group-item" style="padding:0 !important;text-align:center;border:none !important;">
				<div class="table-responsive">
					<table class="table table-bordered" style="text-align:left;">
						<thead>
							<tr>
								<th><?php echo $this->lang->line('Visitors by Country'); ?></th>
								<th><?php echo $this->lang->line('Visitors by Country Percentage'); ?></th>
							</tr>
						</thead>

						<tbody>
							<?php 
								           
					            if(is_array($card_geography_country) && is_array($card_geography_countryPercent) )
					            {
 				                    foreach ($card_geography_country as $key => $value) {
 				                    	
                    	                    if(array_key_exists($key, $card_geography_country) && array_key_exists($key, $site_metrics))
                    	                    {
                    	                    	echo "<tr><td>".$card_geography_country[$key]."</td>";
                    		                    echo "<td>".$card_geography_countryPercent[$key]."</td></tr>";
                    		                   
                    		                   
                    		                }
 				                    }

					                if(count($card_geography_country)==0 || count($card_geography_countryPercent)==0 )
					                echo "<tr><td colspan='2'>No data found!</td></tr>";
					            }
					            else
					            {
					            	echo "<tr><td colspan='2'>No data found!</td></tr>";
					            }

							?>
						</tbody>
					</table>
				</div>
			</li>
			
		</ul>
	</div>
</div>


<style type="text/css" media="screen">
	th{font-family:Arial;}
	.box-body{min-height:270px !important;}
	.progress{margin-bottom:10px;}
</style>




<?php echo "</body></html>"; ?>

<script src="<?php echo base_url(); ?>plugins/knob/jquery.knob.js"></script>
<script>
    $(function() {
        $(".dial").knob();
    });
</script>