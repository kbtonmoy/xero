<style>
	.card-copy{border: 0.5px solid #dee2e6;}
	.inline-lists{ border-bottom: 0.5px solid #dee2e6; }
	.inline-lists:last-child{ border-bottom: 0; }
	.custom_card-hero .card-header { padding: 18px !important; }
	.custom_card-hero .card-header .card-icon i { font-size: 50px !important; }
	.custom_card-hero .card-header .card-description{ margin-top: 10px !important; }
	.ticket-item { border-bottom: 0 !important; }
	.wizard-steps .wizard-step:before { content:"";background:transparent; }
	.wizard-steps .wizard-step.wizard-step-active:before { content:"";background:transparent; }
	.otn_info_modal{cursor: pointer;}
    .modal-backdrop{position: unset;}
</style>

<?php 
	$title_start = '<a class="ticket-item"><div class="ticket-title" style="color:#6777ef;"><h4 style="font-size:14px;">';
	$title_end = '</h4></div>';
	$info_start = '<div class="ticket-info" style="word-break:break-all;font-size:11px;"><div>';
	$info_end = '</div></div></a></div>';

	$col6_div_right_start = '<div class="col-12 col-md-6 pr-0">';
	$col6_div_left_start = '<div class="col-12 col-md-6 pl-0">';
	$col12_div_start = '<div class="col-12">';
	$end_div = '<div>';
?>

<div id="general_success_msg" class="text-center" ></div>	
<div id="general_name"></div>

<div id="hide_after_ajax">

	<div class="row">
		<div class="col-12 col-md-6">
			<div class="card card-hero custom_card-hero">
				<div class="card-header">
					<div class="card-icon m-0">
						<i class="fas fa-street-view"></i>
					</div>
					<div class="card-description"><?php echo $this->lang->line('WhoIs Information'); ?></div>
				</div>

				<div class="card-body p-0 whois-body">
					<div class="tickets-list">
						<div class="row">
						<?php 
							// is_registered start
							if($domain_info[0]['whois_is_registered'] == 'Yes') $is_registered = $this->lang->line('Yes'); 
							else $is_registered = $this->lang->line('No');

							echo $col6_div_right_start.$title_start.$this->lang->line('Registered').$title_end;
							echo $info_start.$is_registered.$info_end;
							// is_registered start

							// Domain Age Start
							if($domain_info[0]['whois_created_at'] != '0000-00-00'){									
								$end = date("Y-m-d");
								$start = date("Y-m-d",strtotime($domain_info[0]['whois_created_at']));
							} else {
								$end = $domain_info[0]['whois_created_at'] ;
								$start = $domain_info[0]['whois_created_at'] ;
							}

							echo $col6_div_left_start.$title_start.$this->lang->line('Domain Age').$title_end;
							echo $info_start.calculate_date_differece($end,$start).$info_end;
							// Domain Age end

							// Name Servers start
							echo $col12_div_start.$title_start.$this->lang->line('Name Servers').$title_end;
							echo $info_start.$domain_info[0]['whois_name_servers'].$info_end;
							// Name Servers end

							// Created At start
							if($domain_info[0]['whois_created_at'] != '0000-00-00') {
								$domain_info[0]['whois_created_at'] = date("d-M-Y",strtotime($domain_info[0]['whois_created_at']));
							}
							echo $col6_div_right_start.$title_start.$this->lang->line('Created At').$title_end;
							echo $info_start.$domain_info[0]['whois_created_at'].$info_end;
							// Created At end

							// changed At start
							if($domain_info[0]['whois_changed_at'] != '0000-00-00') {
								$domain_info[0]['whois_changed_at'] = date("d-M-Y",strtotime($domain_info[0]['whois_changed_at']));
							}
							echo $col6_div_left_start.$title_start.$this->lang->line('Changed At').$title_end;
							echo $info_start.$domain_info[0]['whois_changed_at'].$info_end;
							// changed At end

							// Expire At  start
							if($domain_info[0]['whois_expire_at'] != '0000-00-00') {
								$domain_info[0]['whois_expire_at'] = date("d-M-Y",strtotime($domain_info[0]['whois_expire_at']));
							}
							echo $col6_div_right_start.$title_start.$this->lang->line('Expire At').$title_end;
							echo $info_start.$domain_info[0]['whois_expire_at'].$info_end;
							// Expire At end

							// Registrant Url  start
							echo $col6_div_left_start.$title_start.$this->lang->line('Registrant Url').$title_end;
							echo $info_start.$domain_info[0]['whois_registrar_url'].$info_end;
							// Registrant Url end

						?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-12 col-md-6">
			<div class="row">
				<div class="col-12">
					<div class="card card-hero custom_card-hero">
						<div class="card-header">
							<div class="card-icon m-0">
								<i class="fab fa-medium"></i>
							</div>
							<div class="card-description"><?php echo $this->lang->line('MOZ Information'); ?></div>
						</div>

						<div class="card-body p-0 moz-body">
							<div class="tickets-list">
								<div class="row">
								<?php 
									// is_registered start
									echo $col6_div_right_start.$title_start.$this->lang->line('Subdomain Normalized').$title_end;
									echo $info_start.$domain_info[0]['moz_subdomain_normalized'].$info_end;
									// is_registered start

									// is_registered start
									echo $col6_div_left_start.$title_start.$this->lang->line('Subdomain Raw').$title_end;
									echo $info_start.$domain_info[0]['moz_subdomain_raw'].$info_end;
									// is_registered start

									// is_registered start
									echo $col6_div_right_start.$title_start.$this->lang->line('URL Normalized').$title_end;
									echo $info_start.$domain_info[0]['moz_url_normalized'].$info_end;
									// is_registered start

									// is_registered start
									echo $col6_div_left_start.$title_start.$this->lang->line('URL Raw').$title_end;
									echo $info_start.$domain_info[0]['moz_url_raw'].$info_end;
									// is_registered start

									// is_registered start
									echo $col6_div_right_start.$title_start.$this->lang->line('HTTP Status Code').$title_end;
									echo $info_start.$domain_info[0]['moz_http_status_code'].$info_end;
									// is_registered start

									// is_registered start
									echo $col6_div_left_start.$title_start.$this->lang->line('Domain Authority').$title_end;
									echo $info_start.$domain_info[0]['moz_domain_authority'].$info_end;
									// is_registered start

									// is_registered start
									echo $col6_div_right_start.$title_start.$this->lang->line('Page Authority').$title_end;
									echo $info_start.$domain_info[0]['moz_page_authority'].$info_end;
									// is_registered start

									// is_registered start
									echo $col6_div_left_start.$title_start.$this->lang->line('External Quality Link').$title_end;
									echo $info_start.$domain_info[0]['moz_external_equity_links'].$info_end;
									// is_registered start

									// is_registered start
									echo $col6_div_right_start.$title_start.$this->lang->line('Links').$title_end;
									echo $info_start.$domain_info[0]['moz_links'].$info_end;
									// is_registered start

								?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Link and pagepeeker -->
	<div class="row">
		<div class="col-12 col-md-6">
			<?php
				$total_link_count = $domain_info[0]['moz_links'];
				if($total_link_count=="") $total_link_count=0;
			 	$total_links = number_format($total_link_count); 
			?>
			<div class="list-group">
				<a class="list-group-item list-group-item-action active text-white pointer"><i class="fas fa-link"></i> <?php echo $this->lang->line('Link Informations'); ?>
				</a>
				<a class="list-group-item d-flex justify-content-between align-items-center list-group-item-action pointer">
					<?php echo $this->lang->line('Total Link Count'); ?>
					<span class="badge badge-primary badge-pill"><?php echo $total_links; ?></span>
				</a>
				<a class="list-group-item d-flex justify-content-between align-items-center list-group-item-action pointer">
					<?php echo $this->lang->line('BackLink Count'); ?>
					<span class="badge badge-primary badge-pill"><?php echo $domain_info[0]['google_back_link_count']; ?></span>
				</a>
				<a class="list-group-item d-flex justify-content-between align-items-center list-group-item-action pointer">
					<?php echo $this->lang->line('MozRank'); ?>
					<span class="badge badge-primary badge-pill"><?php echo round($domain_info[0]['moz_url_normalized'])."/10"; ?></span>
				</a>
			</div>

		</div>
		<div class="col-12 col-md-6">
			<?php if ($domain_info[0]['screenshot'] != ""): ?>
				<img src='<?php echo $domain_info[0]['screenshot']; ?>' class='img-responsive' style='border-radius: 0px;width:100%' />
			<?php else: ?>
				<div class="alert alert-warning alert-has-icon">
				  <div class="alert-icon"><i class="far fa-lightbulb"></i> </div>
				  <div class="alert-body" style="word-break: break-word">
				    <div class="alert-title"> <?php echo $this->lang->line("Warning"); ?></div>
				    <?php echo isset($domain_info[0]['screenshot_error']) ? $domain_info[0]['screenshot_error'] : ""; ?><br>
				    <a target='_BLANK' href="https://console.developers.google.com/apis/library"><?php echo $this->lang->line("Enable Google PageInsights API from here"); ?></a>
				  </div>
				</div>
			<?php endif; ?>
			
		</div>
	</div><br><br>

	<!-- Mobile Friendly section -->
	<div class="row">
		<div class="col-12">
			<div class="card card-primary">
				<div class="card-header">
					<h4><i class="fas fa-mobile-alt"></i> <?php echo $this->lang->line('Mobile Friendly Check'); ?></h4>
					<div class="card-header-action">
						<a data-collapse="#mobile-collapse" href="#"><i class="fas fa-minus"></i></a>
					</div>
				</div>

				<div class="card-body" id="mobile-collapse">
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
								<div class="col-12 col-md-6">						
									<p style="text-align: center;position: relative;">
									    <div style="display:inline;width:120px;height:120px;"><canvas width="120" height="120"></canvas><input type="text" class="dial knob" data-readonly="true" value="<?php echo $final_score; ?>" data-width="120" data-height="120" data-fgcolor="#6777ef" data-thickness=".1" readonly="readonly" style="width: 64px; height: 40px; position: absolute; vertical-align: middle; margin-top: 40px; margin-left: -92px; border: 0px; background: none; font: bold 24px Arial; text-align: center; color: rgb(103, 119, 239); padding: 0px; -webkit-appearance: none;"></div>
									</p>
									<h4 class="text-warning" style="margin-left: 21%"><?php echo $this->lang->line('Performance'); ?></h4>
								</div>
								<div class="col-12 col-md-6">
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
                                <div class="list-group">
                                    <a class="list-group-item list-group-item-action active text-white pointer"> <?php echo $this->lang->line('Field Data'); ?> <i data-description="<h2 class='section-title'><?php echo $this->lang->line('Field Data'); ?></h2> <p style='font-size: 12px;'><?php echo $this->lang->line('Over the last 30 days, the field data shows that this page has an <b>Moderate</b> speed compared to other pages in the') ?> <b><a target='_BLANK' href='https://developers.google.com/web/tools/chrome-user-experience-report/'></b> <?php echo $this->lang->line('Chrome User Experience Report') ?></a>. <?php echo $this->lang->line('We are showing') ?> <b> <a target='_BLANK' href='https://developers.google.com/speed/docs/insights/v5/about#faq'><?php echo $this->lang->line('the 75th percentile of FCP') ?></b> <b></a> and <a target='_BLANK' href='https://developers.google.com/speed/docs/insights/v5/about#faq'><?php echo $this->lang->line('the 95th percentile of FID') ?></a></b></p>" class="fas fa-info-circle field_data_modal" style="color: #fff;"></i>
                                    </a>
                                    <a class="list-group-item d-flex justify-content-between align-items-center list-group-item-action pointer">
                                        <?php echo $this->lang->line('First Contentful Paint (FCP)'); ?>
                                        <span class="badge badge-primary badge-pill">
                                           <?php 
                                           if(isset($loadingexperience_metrics['metrics']['FIRST_CONTENTFUL_PAINT_MS']['percentile']))
                                               echo $loadingexperience_metrics['metrics']['FIRST_CONTENTFUL_PAINT_MS']['percentile'].' ms';
                                            ?>
                                                
                                        </span>
                                    </a>
                                    <a class="list-group-item d-flex justify-content-between align-items-center list-group-item-action pointer">
                                        <?php echo $this->lang->line('FCP Metric Category'); ?>
                                        <span class="badge badge-primary badge-pill">
                                            <?php 
                                            if(isset($loadingexperience_metrics['metrics']['FIRST_CONTENTFUL_PAINT_MS']['category']))
                                                echo $loadingexperience_metrics['metrics']['FIRST_CONTENTFUL_PAINT_MS']['category'];
                                             ?>    
                                        </span>
                                    </a>
                                    <a class="list-group-item d-flex justify-content-between align-items-center list-group-item-action pointer">
                                        <?php echo $this->lang->line('First Input Delay (FID)'); ?>
                                        <span class="badge badge-primary badge-pill">
                                           <?php 

                                           if(isset($loadingexperience_metrics['metrics']['FIRST_INPUT_DELAY_MS']['percentile']))
                                               echo $loadingexperience_metrics['metrics']['FIRST_INPUT_DELAY_MS']['percentile'].' ms';
                                            ?>
                                                
                                        </span>
                                    </a>                                    
                                    <a class="list-group-item d-flex justify-content-between align-items-center list-group-item-action pointer">
                                        <?php echo $this->lang->line('FID Metric Category'); ?>
                                        <span class="badge badge-primary badge-pill">
                                           <?php 

                                           if(isset($loadingexperience_metrics['metrics']['FIRST_INPUT_DELAY_MS']['category']))
                                               echo $loadingexperience_metrics['metrics']['FIRST_INPUT_DELAY_MS']['category'];
                                            ?>
                                                
                                        </span>
                                    </a>                                    
                                    <a class="list-group-item d-flex justify-content-between align-items-center list-group-item-action pointer">
                                        <?php echo $this->lang->line('Overall Category'); ?>
                                        <span class="badge badge-primary badge-pill">
                                            <?php 
                                            if(isset($loadingexperience_metrics['overall_category']))
                                                echo $loadingexperience_metrics['overall_category'];
                                             ?>
                                                
                                        </span>
                                    </a>
                                </div>
							</div>
							<div class="col-12 col-md-4 pl-4">
								<div style="padding-left:12px;min-height:530px;background: url('<?php echo base_url("assets/images/mobile.png");?>') no-repeat !important;">
									<?php 
															
									if(isset($lighthouseresult_audits['final-screenshot']['details']['data']))
									{

										echo '<img src="'.$lighthouseresult_audits['final-screenshot']['details']['data'].'" style="max-width:225px !important;margin-top:52px;" class="img-thumbnail">';
									} 

									?>
								</div>
							</div>
						</div>

						<div class="row mt-2">
							<div class="col-12 col-md-6">
                                <div class="list-group">
                                    <a class="list-group-item list-group-item-action active text-white pointer"> <?php echo $this->lang->line('Origin Summary'); ?> <i data-description="<h2 class='section-title'><?php echo $this->lang->line('Origin Summary Data'); ?></h2><p style='font-size: 12px;'> <?php echo $this->lang->line('All pages served from this origin have a <b>Slow</b> speed compared to other pages in the'); ?> <a target='_BLANK' href='https://developers.google.com/web/tools/chrome-user-experience-report/'><?php echo $this->lang->line('Chrome User Experience Report') ?></a> <?php echo $this->lang->line('over the last 30 days.To view suggestions tailored to each page, analyze individual page URLs.') ?></p>" class="fas fa-info-circle field_data_modal" style="color: #fff;"></i>
                                    </a>
                                    <a class="list-group-item d-flex justify-content-between align-items-center list-group-item-action pointer">
                                        <?php echo $this->lang->line('First Contentful Paint (FCP)'); ?>
                                        <span class="badge badge-primary badge-pill">
                                            <?php 
                                            if(isset($originloadingexperience_metrics['metrics']['FIRST_CONTENTFUL_PAINT_MS']['percentile']))
                                                echo $originloadingexperience_metrics['metrics']['FIRST_CONTENTFUL_PAINT_MS']['percentile'].' ms';
                                             ?>
                                                
                                        </span>
                                    </a>
                                    <a class="list-group-item d-flex justify-content-between align-items-center list-group-item-action pointer">
                                        <?php echo $this->lang->line('FCP Metric Category'); ?>
                                        <span class="badge badge-primary badge-pill">
                                            <?php 
                                            if(isset($originloadingexperience_metrics['metrics']['FIRST_CONTENTFUL_PAINT_MS']['category']))
                                                echo $originloadingexperience_metrics['metrics']['FIRST_CONTENTFUL_PAINT_MS']['category'];
                                             ?>  
                                        </span>
                                    </a>
                                    <a class="list-group-item d-flex justify-content-between align-items-center list-group-item-action pointer">
                                        <?php echo $this->lang->line('First Input Delay (FID)'); ?>
                                        <span class="badge badge-primary badge-pill">
                                           <?php 

                                           if(isset($originloadingexperience_metrics['metrics']['FIRST_INPUT_DELAY_MS']['percentile']))
                                               echo $originloadingexperience_metrics['metrics']['FIRST_INPUT_DELAY_MS']['percentile'].' ms';
                                            ?>
                                                
                                        </span>
                                    </a>                                    
                                    <a class="list-group-item d-flex justify-content-between align-items-center list-group-item-action pointer">
                                        <?php echo $this->lang->line('FID Metric Category'); ?>
                                        <span class="badge badge-primary badge-pill">
                                           <?php 
                                           if(isset($originloadingexperience_metrics['metrics']['FIRST_INPUT_DELAY_MS']['category']))
                                               echo $originloadingexperience_metrics['metrics']['FIRST_INPUT_DELAY_MS']['category'];
                                            ?>
                                                
                                        </span>
                                    </a>                                    
                                    <a class="list-group-item d-flex justify-content-between align-items-center list-group-item-action pointer">
                                        <?php echo $this->lang->line('Overall Category'); ?>
                                        <span class="badge badge-primary badge-pill">
                                            <?php 
                                            if(isset($originloadingexperience_metrics['overall_category']))
                                                echo $originloadingexperience_metrics['overall_category'];
                                             ?>
                                                
                                        </span>
                                    </a>
                                </div>

							</div>
							<div class="col-12 col-md-6">
                                <div class="list-group">
                                    <a class="list-group-item list-group-item-action active text-white pointer"> <?php echo $this->lang->line('Lab Data'); ?> 
                                    </a>
                                    <a class="list-group-item d-flex justify-content-between align-items-center list-group-item-action pointer">
                                        <?php echo $this->lang->line('First Contentful Paint'); ?><i data-description="<h2 class='section-title'><?php echo $this->lang->line('First Contentful Paint'); ?></h2> <p style='font-size: 12px;line-height: initial;'><?php echo $this->lang->line('First Contentful Paint marks the time at which the first text or image is painted.'); ?> <b><a target='_BLANK' class='text-danger' href='https://web.dev/first-contentful-paint/?utm_source=lighthouse&utm_medium=unknown'><?php echo $this->lang->line('Learn more'); ?></a></b> </p>" class="fas fa-info-circle field_data_modal float-left" style="margin-right: 187px;"></i>
                                        <span class="badge badge-primary badge-pill">
                                            <?php 
                                            if(isset($lighthouseresult_audits['first-contentful-paint']['displayValue']))
                                                echo $lighthouseresult_audits['first-contentful-paint']['displayValue'];
                                             ?>
                                                
                                        </span>
                                    </a>
                                    <a class="list-group-item d-flex justify-content-between align-items-center list-group-item-action pointer">
                                        <?php echo $this->lang->line('First Meaningful Paint'); ?><i data-description="<h2 class='section-title'><?php echo $this->lang->line('First Meaningful Paint'); ?></h2>
                                            <p style='font-size: 12px;line-height: initial;'><?php echo $this->lang->line('First Meaningful Paint measures when the primary content of a page is visible.'); ?> <b><a target='_BLANK' class='text-danger' href='https://web.dev/first-meaningful-paint?utm_source=lighthouse&utm_medium=unknown'><?php echo $this->lang->line('Learn more'); ?></a></b> </p>" class="fas fa-info-circle field_data_modal" style="margin-right: 187px;"></i>
                                        <span class="badge badge-primary badge-pill">
                                            <?php 
                                            if(isset($lighthouseresult_audits['first-meaningful-paint']['displayValue']))
                                                echo $lighthouseresult_audits['first-meaningful-paint']['displayValue'];
                                            ?>
                                            
                                        </span>
                                    </a>
                                    <a class="list-group-item d-flex justify-content-between align-items-center list-group-item-action pointer">
                                        <?php echo $this->lang->line('Speed Index'); ?> <i data-description="<h2 class='section-title'><?php echo $this->lang->line('Speed Index'); ?></h2>
                                        <p style='font-size: 12px;line-height: initial;'><?php echo $this->lang->line('Speed Index shows how quickly the contents of a page are visibly populated.'); ?> <b><a target='_BLANK' class='text-danger' href='https://web.dev/speed-index?utm_source=lighthouse&utm_medium=unknown'><?php echo $this->lang->line('Learn more'); ?></a></b> </p>" class="fas fa-info-circle field_data_modal" style="margin-right: 230px"></i>
                                        <span class="badge badge-primary badge-pill">
                                           <?php 

                                           if(isset($lighthouseresult_audits['speed-index']['displayValue']))
                                             echo $lighthouseresult_audits['speed-index']['displayValue'];
                                            ?>
                                                
                                        </span>
                                    </a>                                    
                                    <a class="list-group-item d-flex justify-content-between align-items-center list-group-item-action pointer">
                                        <?php echo $this->lang->line('First CPU Idle'); ?> <i data-description="<h2 class='section-title'><?php echo $this->lang->line('First CPU Idle'); ?></h2>
                                        <p style='font-size: 12px;line-height: initial;'><?php echo $this->lang->line('First CPU Idle marks the first time at which the page main thread is quiet enough to handle input.'); ?> <b><a target='_BLANK' class='text-danger' href='https://web.dev/first-cpu-idle?utm_source=lighthouse&utm_medium=unknown'><?php echo $this->lang->line('Learn more'); ?></a></b> </p>" class="fas fa-info-circle field_data_modal" style="margin-right: 220px"></i>
                                        <span class="badge badge-primary badge-pill">
                                           <?php 
                                           if(isset($lighthouseresult_audits['first-cpu-idle']['displayValue']))
                                               echo $lighthouseresult_audits['first-cpu-idle']['displayValue'];
                                            ?>
                                                
                                        </span>
                                    </a>                                    
                                    <a class="list-group-item d-flex justify-content-between align-items-center list-group-item-action pointer">
                                        <?php echo $this->lang->line('Time to Interactive'); ?> <i class="fas fa-info-circle field_data_modal" style="margin-right: 187px;" data-description="<h2 class='section-title'><?php echo $this->lang->line('Time to Interactive'); ?></h2>
                                        <p style='font-size: 12px;line-height: initial;'><?php echo $this->lang->line('Time to interactive is the amount of time it takes for the page to become fully interactive.'); ?> <b><a target='_BLANK' class='text-danger' href='https://web.dev/interactive/?utm_source=lighthouse&utm_medium=unknown'><?php echo $this->lang->line('Learn more'); ?></a></b> </p>"></i>
                                        <span class="badge badge-primary badge-pill">
                                            <?php 
                                            if(isset($lighthouseresult_audits['interactive']['displayValue']))
                                                echo $lighthouseresult_audits['interactive']['displayValue'];
                                             ?>
                                                
                                        </span>
                                    </a>                                    

                                    <a class="list-group-item d-flex justify-content-between align-items-center list-group-item-action pointer">
                                        <?php echo $this->lang->line('Max Potential First Input Delay'); ?> <i class="fas fa-info-circle field_data_modal" style="margin-right: 100px;" data-description="<h2 class='section-title'><?php echo $this->lang->line('Max Potential First Input Delay'); ?></h2>
                                            <p style='font-size: 12px;line-height: initial;'><?php echo $this->lang->line('The maximum potential First Input Delay that your users could experience is the duration, in milliseconds, of the longest task.'); ?> <b><a target='_BLANK' class='text-danger' href='https://web.dev/fid/'><?php echo $this->lang->line('Learn more'); ?></a></b> </p>"></i>
                                        <span class="badge badge-primary badge-pill">
                                            <?php 
                                            if(isset($lighthouseresult_audits['max-potential-fid']['displayValue']))
                                                echo $lighthouseresult_audits['max-potential-fid']['displayValue'];
                                             ?>
                                                
                                        </span>
                                    </a>
                                </div>
							</div>
						</div>

                        <div class="row mt-5">
                            <div class="col-12">
                                <div class="card card-hero custom_card-hero">
                                    <div class="card-header">
                                        <div class="card-icon m-0">
                                            <i class="fab fa-google"></i>
                                        </div>
                                        <h5><?php echo $this->lang->line('Audit Data'); ?></h5>

                                    </div>

                                    <div class="card-body p-0 moz-body">
                                        <div class="tickets-list">
                                            <div class="row">
                                                <div class="col-12 col-md-4 pr-0">
                                                    <div class="ticket-item">
                                                        <div class="ticket-title" style="color:#6777ef;">
                                                            <h4>
                                                                <?php 
                                                                 if(isset($lighthouseresult_audits['resource-summary']['title']))
                                                                    echo $lighthouseresult_audits['resource-summary']['title']; 
                                                                 ?>
                                                              <i class="fas fa-info-circle field_data_modal"
                                                              data-description='
                                                              <?php

                                                              if(isset($lighthouseresult_audits['resource-summary']['title']))
                                                              echo '<h2 class="section-title">'.$lighthouseresult_audits['resource-summary']['title'].' </h2>';

                                                              if(isset($lighthouseresult_audits['resource-summary']['description'])){

                                                              $resource_sum = explode('[',$lighthouseresult_audits['resource-summary']['description']);

                                                              echo '<p style="font-size: 12px;line-height: initial;">'.$resource_sum[0].'<b><a class="text-danger" target="_BLANK" href="https://developers.google.com/web/tools/lighthouse/audits/budgets">'.$this->lang->line("Learn More").'</a></b></p>';
                                                              }

                                                              ?>
                                                              '>
                                                                  
                                                              </i>
                                                            </h4>
                                                            <div class="ticket-info" style="word-break:break-all;font-size:11px;">
                                                                <div>
                                                                    <?php 
                                                                    if(isset($lighthouseresult_audits['resource-summary']['displayValue']))
                                                                    echo $lighthouseresult_audits['resource-summary']['displayValue'];
                                                                     ?>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>                                                    
                                                </div>                                              
                                                <div class="col-12 col-md-4 pr-0">
                                                    <div class="ticket-item">
                                                        <div class="ticket-title" style="color:#6777ef;">
                                                            <h4>
                                                                <?php 
                                                                if(isset($lighthouseresult_audits['time-to-first-byte']['title']))
                                                                   echo $lighthouseresult_audits['time-to-first-byte']['title']; 
                                                                 ?>
                                                              <i class="fas fa-info-circle field_data_modal"
                                                              data-description='
                                                              <?php

                                                              if(isset($lighthouseresult_audits['time-to-first-byte']['title']))
                                                              echo '<h2 class="section-title">'.$lighthouseresult_audits['time-to-first-byte']['title'].' </h2>';

                                                              if(isset($lighthouseresult_audits['time-to-first-byte']['description'])){

                                                              $time_to_first_byte = explode('[',$lighthouseresult_audits['time-to-first-byte']['description']);

                                                              echo '<p style="font-size: 12px;line-height: initial;">'.$time_to_first_byte[0].'<b><a class="text-danger" target="_BLANK" href="https://web.dev/time-to-first-byte">'.$this->lang->line("Learn More").'</a></b></p>';
                                                              }

                                                              ?>
                                                              '>
                                                                  
                                                              </i>
                                                            </h4>
                                                            <div class="ticket-info" style="word-break:break-all;font-size:11px;">
                                                                <div>
                                                                    <?php 
                                                                    if(isset($lighthouseresult_audits['time-to-first-byte']['displayValue']))
                                                                    echo $lighthouseresult_audits['time-to-first-byte']['displayValue'];
                                                                     ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                </div>                                                 
                                                <div class="col-12 col-md-4 pr-0">
                                                    <div class="ticket-item">
                                                        <div class="ticket-title" style="color:#6777ef;">
                                                            <h4>
                                                                <?php 
                                                                if(isset($lighthouseresult_audits['render-blocking-resources']['title']))
                                                                   echo $lighthouseresult_audits['render-blocking-resources']['title']; 
                                                                 ?>
                                                              <i class="fas fa-info-circle field_data_modal"
                                                              data-description='
                                                              <?php

                                                              if(isset($lighthouseresult_audits['render-blocking-resources']['title']))
                                                              echo '<h2 class="section-title">'.$lighthouseresult_audits['render-blocking-resources']['title'].' </h2>';

                                                              if(isset($lighthouseresult_audits['render-blocking-resources']['description'])){

                                                              $render_blocking = explode('[',$lighthouseresult_audits['render-blocking-resources']['description']);

                                                              echo '<p style="font-size: 12px;line-height: initial;">'.$render_blocking[0].'<b><a class="text-danger" target="_BLANK" href="https://web.dev/render-blocking-resources">'.$this->lang->line("Learn More").'</a></b></p>';
                                                              }

                                                              ?>
                                                              '>
                                                                  
                                                              </i>
                                                            </h4>
                                                            <div class="ticket-info" style="word-break:break-all;font-size:11px;">
                                                                <div>
                                                                    <?php 
                                                                    if(isset($lighthouseresult_audits['render-blocking-resources']['displayValue']))
                                                                    echo $lighthouseresult_audits['render-blocking-resources']['displayValue'];
                                                                     ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                </div>                                                 
                                                <div class="col-12 col-md-4 pr-0">
                                                    <div class="ticket-item">
                                                        <div class="ticket-title" style="color:#6777ef;">
                                                            <h4>
                                                                <?php 
                                                                if(isset($lighthouseresult_audits['uses-optimized-images']['title']))
                                                                   echo $lighthouseresult_audits['uses-optimized-images']['title']; 
                                                                 ?>
                                                              <i class="fas fa-info-circle field_data_modal"
                                                              data-description='
                                                              <?php

                                                              if(isset($lighthouseresult_audits['uses-optimized-images']['title']))
                                                              echo '<h2 class="section-title">'.$lighthouseresult_audits['uses-optimized-images']['title'].' </h2>';

                                                              if(isset($lighthouseresult_audits['uses-optimized-images']['description'])){

                                                              $optimizaed = explode('[',$lighthouseresult_audits['uses-optimized-images']['description']);

                                                              echo '<p style="font-size: 12px;line-height: initial;">'.$optimizaed[0].'<b><a class="text-danger" target="_BLANK" href="https://web.dev/uses-optimized-images">'.$this->lang->line("Learn More").'</a></b></p>';
                                                              }

                                                              ?>
                                                              '>
                                                                  
                                                              </i>
                                                            </h4>
                                                            <div class="ticket-info" style="word-break:break-all;font-size:11px;">
                                                                <div>
                                                                    <?php 
                                                                    if(isset($lighthouseresult_audits['uses-optimized-images']['displayValue']))
                                                                    echo $lighthouseresult_audits['uses-optimized-images']['displayValue'];
                                                                     ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                </div>                                                 
                                                <div class="col-12 col-md-4 pr-0">
                                                    <div class="ticket-item">
                                                        <div class="ticket-title" style="color:#6777ef;">
                                                            <h4>
                                                                <?php 
                                                                if(isset($lighthouseresult_audits['uses-text-compression']['title']))
                                                                   echo $lighthouseresult_audits['uses-text-compression']['title']; 
                                                                 ?>
                                                              <i class="fas fa-info-circle field_data_modal"
                                                              data-description='
                                                              <?php

                                                              if(isset($lighthouseresult_audits['uses-text-compression']['title']))
                                                              echo '<h2 class="section-title">'.$lighthouseresult_audits['uses-text-compression']['title'].' </h2>';

                                                              if(isset($lighthouseresult_audits['uses-text-compression']['description'])){

                                                              $text_compresseion = explode('[',$lighthouseresult_audits['uses-text-compression']['description']);

                                                              echo '<p style="font-size: 12px;line-height: initial;">'.$text_compresseion[0].'<b><a class="text-danger" target="_BLANK" href="https://web.dev/uses-text-compression">'.$this->lang->line("Learn More").'</a></b></p>';
                                                              }

                                                              ?>
                                                              '>
                                                                  
                                                              </i>
                                                            </h4>
                                                            <div class="ticket-info" style="word-break:break-all;font-size:11px;">
                                                                <div>
                                                                    <?php 
                                                                    if(isset($lighthouseresult_audits['uses-text-compression']['displayValue']))
                                                                    echo $lighthouseresult_audits['uses-text-compression']['displayValue'];
                                                                     ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                </div>                                                 
                                                <div class="col-12 col-md-4 pr-0">
                                                    <div class="ticket-item">
                                                        <div class="ticket-title" style="color:#6777ef;">
                                                            <h4>
                                                                <?php 
                                                                if(isset($lighthouseresult_audits['uses-long-cache-ttl']['title']))
                                                                   echo $lighthouseresult_audits['uses-long-cache-ttl']['title']; 
                                                                 ?>
                                                              <i class="fas fa-info-circle field_data_modal"
                                                              data-description='
                                                              <?php

                                                              if(isset($lighthouseresult_audits['uses-long-cache-ttl']['title']))
                                                              echo '<h2 class="section-title">'.$lighthouseresult_audits['uses-long-cache-ttl']['title'].' </h2>';

                                                              if(isset($lighthouseresult_audits['uses-long-cache-ttl']['description'])){

                                                              $uses_long_cache = explode('[',$lighthouseresult_audits['uses-long-cache-ttl']['description']);

                                                              echo '<p style="font-size: 12px;line-height: initial;">'.$uses_long_cache[0].'<b><a class="text-danger" target="_BLANK" href="https://web.dev/uses-long-cache-ttl">'.$this->lang->line("Learn More").'</a></b></p>';
                                                              }

                                                              ?>
                                                              '>
                                                                  
                                                              </i>
                                                            </h4>
                                                            <div class="ticket-info" style="word-break:break-all;font-size:11px;">
                                                                <div>
                                                                    <?php 
                                                                    if(isset($lighthouseresult_audits['uses-long-cache-ttl']['displayValue']))
                                                                    echo $lighthouseresult_audits['uses-long-cache-ttl']['displayValue'];
                                                                     ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                </div>                                                 
                                                <div class="col-12 col-md-4 pr-0">
                                                    <div class="ticket-item">
                                                        <div class="ticket-title" style="color:#6777ef;">
                                                            <h4>
                                                                <?php 
                                                                if(isset($lighthouseresult_audits['third-party-summary']['title']))
                                                                   echo $lighthouseresult_audits['third-party-summary']['title']; 
                                                                 ?>
                                                              <i class="fas fa-info-circle field_data_modal"
                                                              data-description='
                                                              <?php

                                                              if(isset($lighthouseresult_audits['third-party-summary']['title']))
                                                              echo '<h2 class="section-title">'.$lighthouseresult_audits['third-party-summary']['title'].' </h2>';

                                                              if(isset($lighthouseresult_audits['third-party-summary']['description'])){

                                                              $third_party_summary = explode('[',$lighthouseresult_audits['third-party-summary']['description']);

                                                              echo '<p style="font-size: 12px;line-height: initial;">'.$third_party_summary[0].'<b><a class="text-danger" target="_BLANK" href="https://developers.google.com/web/fundamentals/performance/optimizing-content-efficiency/loading-third-party-javascript">'.$this->lang->line("Learn More").'</a></b></p>';
                                                              }

                                                              ?>
                                                              '>
                                                                  
                                                              </i>
                                                            </h4>
                                                            <div class="ticket-info" style="word-break:break-all;font-size:11px;">
                                                                <div>
                                                                    <?php 
                                                                    if(isset($lighthouseresult_audits['third-party-summary']['displayValue']))
                                                                    echo $lighthouseresult_audits['third-party-summary']['displayValue'];
                                                                     ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                </div>                                                 
                                                <div class="col-12 col-md-4 pr-0">
                                                    <div class="ticket-item">
                                                        <div class="ticket-title" style="color:#6777ef;">
                                                            <h4>
                                                                <?php 
                                                                if(isset($lighthouseresult_audits['estimated-input-latency']['title']))
                                                                   echo $lighthouseresult_audits['estimated-input-latency']['title']; 
                                                                 ?>
                                                              <i class="fas fa-info-circle field_data_modal"
                                                              data-description='
                                                              <?php

                                                              if(isset($lighthouseresult_audits['estimated-input-latency']['title']))
                                                              echo '<h2 class="section-title">'.$lighthouseresult_audits['estimated-input-latency']['title'].' </h2>';

                                                              if(isset($lighthouseresult_audits['estimated-input-latency']['description'])){

                                                              $estimated_latency = explode('[',$lighthouseresult_audits['estimated-input-latency']['description']);

                                                              echo '<p style="font-size: 12px;line-height: initial;">'.$estimated_latency[0].'<b><a class="text-danger" target="_BLANK" href="https://developers.google.com/web/fundamentals/performance/optimizing-content-efficiency/loading-third-party-javascript">'.$this->lang->line("Learn More").'</a></b></p>';
                                                              }

                                                              ?>
                                                              '>
                                                                  
                                                              </i>
                                                            </h4>
                                                            <div class="ticket-info" style="word-break:break-all;font-size:11px;">
                                                                <div>
                                                                    <?php 
                                                                    if(isset($lighthouseresult_audits['estimated-input-latency']['displayValue']))
                                                                    echo $lighthouseresult_audits['estimated-input-latency']['displayValue'];
                                                                     ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                </div> 
                                                <div class="col-12 col-md-4 pr-0">
                                                    <div class="ticket-item">
                                                        <div class="ticket-title" style="color:#6777ef;">
                                                            <h4>
                                                                <?php 
                                                                if(isset($lighthouseresult_audits['first-contentful-paint-3g']['title']))
                                                                   echo $lighthouseresult_audits['first-contentful-paint-3g']['title']; 
                                                                 ?>
                                                              <i class="fas fa-info-circle field_data_modal"
                                                              data-description='
                                                              <?php

                                                              if(isset($lighthouseresult_audits['first-contentful-paint-3g']['title']))
                                                              echo '<h2 class="section-title">'.$lighthouseresult_audits['first-contentful-paint-3g']['title'].' </h2>';

                                                              if(isset($lighthouseresult_audits['first-contentful-paint-3g']['description'])){

                                                              $fcp3g = explode('[',$lighthouseresult_audits['first-contentful-paint-3g']['description']);

                                                              echo '<p style="font-size: 12px;line-height: initial;">'.$fcp3g[0].'<b><a class="text-danger" target="_BLANK" href="https://developers.google.com/web/tools/lighthouse/audits/first-contentful-paint">'.$this->lang->line("Learn More").'</a></b></p>';
                                                              }

                                                              ?>
                                                              '>
                                                                  
                                                              </i>
                                                            </h4>
                                                            <div class="ticket-info" style="word-break:break-all;font-size:11px;">
                                                                <div>
                                                                    <?php 
                                                                    if(isset($lighthouseresult_audits['first-contentful-paint-3g']['displayValue']))
                                                                    echo $lighthouseresult_audits['first-contentful-paint-3g']['displayValue'];
                                                                     ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                </div>                                                  
                                                <div class="col-12 col-md-4 pr-0">
                                                    <div class="ticket-item">
                                                        <div class="ticket-title" style="color:#6777ef;">
                                                            <h4>
                                                                <?php 
                                                                if(isset($lighthouseresult_audits['total-blocking-time']['title']))
                                                                   echo $lighthouseresult_audits['total-blocking-time']['title']; 
                                                                 ?>
                                                              <i class="fas fa-info-circle field_data_modal"
                                                              data-description='
                                                              <?php

                                                              if(isset($lighthouseresult_audits['total-blocking-time']['title']))
                                                              echo '<h2 class="section-title">'.$lighthouseresult_audits['total-blocking-time']['title'].' </h2>';

                                                              if(isset($lighthouseresult_audits['total-blocking-time']['description'])){

                                                              $total_blocking_time1 = explode('[',$lighthouseresult_audits['total-blocking-time']['description']);

                                                              echo '<p style="font-size: 12px;line-height: initial;">'.$total_blocking_time1[0].'</p>';
                                                              }

                                                              ?>
                                                              '>
                                                                  
                                                              </i>
                                                            </h4>
                                                            <div class="ticket-info" style="word-break:break-all;font-size:11px;">
                                                                <div>
                                                                    <?php 
                                                                    if(isset($lighthouseresult_audits['total-blocking-time']['displayValue']))
                                                                    echo $lighthouseresult_audits['total-blocking-time']['displayValue'];
                                                                     ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                </div>                                                 
                                                <div class="col-12 col-md-4 pr-0">
                                                    <div class="ticket-item">
                                                        <div class="ticket-title" style="color:#6777ef;">
                                                            <h4>
                                                                <?php 
                                                                if(isset($lighthouseresult_audits['bootup-time']['title']))
                                                                   echo $lighthouseresult_audits['bootup-time']['title']; 
                                                                 ?>
                                                              <i class="fas fa-info-circle field_data_modal"
                                                              data-description='
                                                              <?php

                                                              if(isset($lighthouseresult_audits['bootup-time']['title']))
                                                              echo '<h2 class="section-title">'.$lighthouseresult_audits['bootup-time']['title'].' </h2>';

                                                              if(isset($lighthouseresult_audits['bootup-time']['description'])){

                                                              $boottime = explode('[',$lighthouseresult_audits['bootup-time']['description']);

                                                              echo '<p style="font-size: 12px;line-height: initial;">'.$boottime[0].'<b><a class="text-danger" target="_BLANK" href="https://web.dev/bootup-time">'.$this->lang->line("Learn More").'</a></b></p>';
                                                              }

                                                              ?>
                                                              '>
                                                                  
                                                              </i>
                                                            </h4>
                                                            <div class="ticket-info" style="word-break:break-all;font-size:11px;">
                                                                <div>
                                                                    <?php 
                                                                    if(isset($lighthouseresult_audits['bootup-time']['displayValue']))
                                                                    echo $lighthouseresult_audits['bootup-time']['displayValue'];
                                                                     ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                </div>                                                  
                                                <div class="col-12 col-md-4 pr-0">
                                                    <div class="ticket-item">
                                                        <div class="ticket-title" style="color:#6777ef;">
                                                            <h4>
                                                                <?php 
                                                                if(isset($lighthouseresult_audits['offscreen-images']['title']))
                                                                   echo $lighthouseresult_audits['offscreen-images']['title']; 
                                                                 ?>
                                                              <i class="fas fa-info-circle field_data_modal"
                                                              data-description='
                                                              <?php

                                                              if(isset($lighthouseresult_audits['offscreen-images']['title']))
                                                              echo '<h2 class="section-title">'.$lighthouseresult_audits['offscreen-images']['title'].' </h2>';

                                                              if(isset($lighthouseresult_audits['offscreen-images']['description'])){

                                                              $total_blocking_time1 = explode('[',$lighthouseresult_audits['offscreen-images']['description']);

                                                              echo '<p style="font-size: 12px;line-height: initial;">'.$third_party_summary[0].'<b><a class="text-danger" target="_BLANK" href="https://web.dev/offscreen-images">'.$this->lang->line("Learn More").'</a></b></p>';
                                                              }

                                                              ?>
                                                              '>
                                                                  
                                                              </i>
                                                            </h4>
                                                            <div class="ticket-info" style="word-break:break-all;font-size:11px;">
                                                                <div>
                                                                    <?php 
                                                                    if(isset($lighthouseresult_audits['offscreen-images']['displayValue']))
                                                                    echo $lighthouseresult_audits['offscreen-images']['displayValue'];
                                                                     ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                </div>                                               
                                                <div class="col-12 col-md-4 pr-0">
                                                    <div class="ticket-item">
                                                        <div class="ticket-title" style="color:#6777ef;">
                                                            <h4>
                                                                <?php 
                                                                if(isset($lighthouseresult_audits['network-server-latency']['title']))
                                                                   echo $lighthouseresult_audits['network-server-latency']['title']; 
                                                                 ?>
                                                              <i class="fas fa-info-circle field_data_modal"
                                                              data-description="<?php

                                                                if(isset($lighthouseresult_audits['network-server-latency']['title']))
                                                                echo "<h2 class='section-title'>{$lighthouseresult_audits['network-server-latency']['title']} </h2>";

                                                                if(isset($lighthouseresult_audits['network-server-latency']['description'])){

                                                                $total_blocking_time1 = explode('[',$lighthouseresult_audits['network-server-latency']['description']);

                                                                    
                                                                echo "<p style='font-size: 12px;line-height: initial;'>{$total_blocking_time1[0]}<b><a class='text-danger' target='_BLANK' href='https://hpbn.co/primer-on-web-performance/#analyzing-the-resource-waterfall'>{$this->lang->line('Learn More')}</a></b></p>";
                                                                }

                                                                ?>">
                                                                  
                                                              </i>
                                                            </h4>
                                                            <div class="ticket-info" style="word-break:break-all;font-size:11px;">
                                                                <div>
                                                                    <?php 
                                                                    if(isset($lighthouseresult_audits['network-server-latency']['displayValue']))
                                                                    echo $lighthouseresult_audits['network-server-latency']['displayValue'];
                                                                     ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                </div>                                                  
                                                <div class="col-12 col-md-4 pr-0">
                                                    <div class="ticket-item">
                                                        <div class="ticket-title" style="color:#6777ef;">
                                                            <h4>
                                                                <?php 
                                                                if(isset($lighthouseresult_audits['uses-responsive-images']['title']))
                                                                   echo $lighthouseresult_audits['uses-responsive-images']['title']; 
                                                                 ?>
                                                              <i class="fas fa-info-circle field_data_modal"
                                                              data-description="<?php

                                                                if(isset($lighthouseresult_audits['uses-responsive-images']['title']))
                                                                echo "<h2 class='section-title'>{$lighthouseresult_audits['uses-responsive-images']['title']} </h2>";

                                                                if(isset($lighthouseresult_audits['uses-responsive-images']['description'])){

                                                                $responsive = explode('[',$lighthouseresult_audits['uses-responsive-images']['description']);

                                                                    
                                                                echo "<p style='font-size: 12px;line-height: initial;'>{$responsive[0]}<b><a class='text-danger' target='_BLANK' href='https://web.dev/uses-responsive-images?utm_source=lighthouse&utm_medium=unknown'>{$this->lang->line('Learn More')}</a></b></p>";
                                                                }

                                                                ?>">
                                                                  
                                                              </i>
                                                            </h4>
                                                            <div class="ticket-info" style="word-break:break-all;font-size:11px;">
                                                                <div>
                                                                    <?php 
                                                                    if(isset($lighthouseresult_audits['uses-responsive-images']['displayValue']))
                                                                    echo $lighthouseresult_audits['uses-responsive-images']['displayValue'];
                                                                     ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                </div>                                                 
                                                <div class="col-12 col-md-4 pr-0">
                                                    <div class="ticket-item">
                                                        <div class="ticket-title" style="color:#6777ef;">
                                                            <h4>
                                                                <?php 
                                                                if(isset($lighthouseresult_audits['unused-css-rules']['title']))
                                                                   echo $lighthouseresult_audits['unused-css-rules']['title']; 
                                                                 ?>
                                                              <i class="fas fa-info-circle field_data_modal"
                                                              data-description="<?php

                                                                if(isset($lighthouseresult_audits['unused-css-rules']['title']))
                                                                echo "<h2 class='section-title'>{$lighthouseresult_audits['unused-css-rules']['title']} </h2>";

                                                                if(isset($lighthouseresult_audits['unused-css-rules']['description'])){

                                                                $unused_css = explode('[',$lighthouseresult_audits['unused-css-rules']['description']);

                                                                    
                                                                echo "<p style='font-size: 12px;line-height: initial;'>{$unused_css[0]}<b><a class='text-danger' target='_BLANK' href='https://web.dev/unused-css-rules'>{$this->lang->line('Learn More')}</a></b></p>";
                                                                }

                                                                ?>">
                                                                  
                                                              </i>
                                                            </h4>
                                                            <div class="ticket-info" style="word-break:break-all;font-size:11px;">
                                                                <div>
                                                                    <?php 
                                                                    if(isset($lighthouseresult_audits['unused-css-rules']['displayValue']))
                                                                    echo $lighthouseresult_audits['unused-css-rules']['displayValue'];
                                                                     ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                </div>                                                   
                                                <div class="col-12 col-md-4 pr-0">
                                                    <div class="ticket-item">
                                                        <div class="ticket-title" style="color:#6777ef;">
                                                            <h4>
                                                                <?php 
                                                                if(isset($lighthouseresult_audits['total-byte-weight']['title']))
                                                                   echo $lighthouseresult_audits['total-byte-weight']['title']; 
                                                                 ?>
                                                              <i class="fas fa-info-circle field_data_modal"
                                                              data-description="<?php

                                                                if(isset($lighthouseresult_audits['total-byte-weight']['title']))
                                                                echo "<h2 class='section-title'>{$lighthouseresult_audits['total-byte-weight']['title']} </h2>";

                                                                if(isset($lighthouseresult_audits['total-byte-weight']['description'])){

                                                                $total_byte = explode('[',$lighthouseresult_audits['total-byte-weight']['description']);

                                                                    
                                                                echo "<p style='font-size: 12px;line-height: initial;'>{$total_byte[0]}<b><a class='text-danger' target='_BLANK' href='https://web.dev/total-byte-weight'>{$this->lang->line('Learn More')}</a></b></p>";
                                                                }

                                                                ?>">
                                                                  
                                                              </i>
                                                            </h4>
                                                            <div class="ticket-info" style="word-break:break-all;font-size:11px;">
                                                                <div>
                                                                    <?php 
                                                                    if(isset($lighthouseresult_audits['total-byte-weight']['displayValue']))
                                                                    echo $lighthouseresult_audits['total-byte-weight']['displayValue'];
                                                                     ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                </div>                                                  
                                                <div class="col-12 col-md-4 pr-0">
                                                    <div class="ticket-item">
                                                        <div class="ticket-title" style="color:#6777ef;">
                                                            <h4>
                                                                <?php 
                                                                if(isset($lighthouseresult_audits['mainthread-work-breakdown']['title']))
                                                                   echo $lighthouseresult_audits['mainthread-work-breakdown']['title']; 
                                                                 ?>
                                                              <i class="fas fa-info-circle field_data_modal"
                                                              data-description="<?php

                                                                if(isset($lighthouseresult_audits['mainthread-work-breakdown']['title']))
                                                                echo "<h2 class='section-title'>{$lighthouseresult_audits['mainthread-work-breakdown']['title']} </h2>";

                                                                if(isset($lighthouseresult_audits['mainthread-work-breakdown']['description'])){

                                                                $mainthred_work = explode('[',$lighthouseresult_audits['mainthread-work-breakdown']['description']);

                                                                    
                                                                echo "<p style='font-size: 12px;line-height: initial;'>{$mainthred_work[0]}<b><a class='text-danger' target='_BLANK' href='https://web.dev/mainthread-work-breakdown'>{$this->lang->line('Learn More')}</a></b></p>";
                                                                }

                                                                ?>">
                                                                  
                                                              </i>
                                                            </h4>
                                                            <div class="ticket-info" style="word-break:break-all;font-size:11px;">
                                                                <div>
                                                                    <?php 
                                                                    if(isset($lighthouseresult_audits['mainthread-work-breakdown']['displayValue']))
                                                                    echo $lighthouseresult_audits['mainthread-work-breakdown']['displayValue'];
                                                                     ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                </div>                                                  
                                                <div class="col-12 col-md-4 pr-0">
                                                    <div class="ticket-item">
                                                        <div class="ticket-title" style="color:#6777ef;">
                                                            <h4>
                                                                <?php 
                                                                if(isset($lighthouseresult_audits['uses-webp-images']['title']))
                                                                   echo $lighthouseresult_audits['uses-webp-images']['title']; 
                                                                 ?>
                                                              <i class="fas fa-info-circle field_data_modal"
                                                              data-description="<?php

                                                                if(isset($lighthouseresult_audits['uses-webp-images']['title']))
                                                                echo "<h2 class='section-title'>{$lighthouseresult_audits['uses-webp-images']['title']} </h2>";

                                                                if(isset($lighthouseresult_audits['uses-webp-images']['description'])){

                                                                $mainthred_work = explode('[',$lighthouseresult_audits['uses-webp-images']['description']);

                                                                    
                                                                echo "<p style='font-size: 12px;line-height: initial;'>{$mainthred_work[0]}<b><a class='text-danger' target='_BLANK' href='https://web.dev/uses-webp-images'>{$this->lang->line('Learn More')}</a></b></p>";
                                                                }

                                                                ?>">
                                                                  
                                                              </i>
                                                            </h4>
                                                            <div class="ticket-info" style="word-break:break-all;font-size:11px;">
                                                                <div>
                                                                    <?php 
                                                                    if(isset($lighthouseresult_audits['uses-webp-images']['displayValue']))
                                                                    echo $lighthouseresult_audits['uses-webp-images']['displayValue'];
                                                                     ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                </div>                                                  
                                                <div class="col-12 col-md-4 pr-0">
                                                    <div class="ticket-item">
                                                        <div class="ticket-title" style="color:#6777ef;">
                                                            <h4>
                                                                <?php 
                                                                if(isset($lighthouseresult_audits['critical-request-chains']['title']))
                                                                   echo $lighthouseresult_audits['critical-request-chains']['title']; 
                                                                 ?>
                                                              <i class="fas fa-info-circle field_data_modal"
                                                              data-description="<?php

                                                                if(isset($lighthouseresult_audits['critical-request-chains']['title']))
                                                                echo "<h2 class='section-title'>{$lighthouseresult_audits['critical-request-chains']['title']} </h2>";

                                                                if(isset($lighthouseresult_audits['critical-request-chains']['description'])){

                                                                $req_chain = explode('[',$lighthouseresult_audits['critical-request-chains']['description']);

                                                                    
                                                                echo "<p style='font-size: 12px;line-height: initial;'>{$req_chain[0]}<b><a class='text-danger' target='_BLANK' href='https://web.dev/critical-request-chains'>{$this->lang->line('Learn More')}</a></b></p>";
                                                                }

                                                                ?>">
                                                                  
                                                              </i>
                                                            </h4>
                                                            <div class="ticket-info" style="word-break:break-all;font-size:11px;">
                                                                <div>
                                                                    <?php 
                                                                    if(isset($lighthouseresult_audits['critical-request-chains']['displayValue']))
                                                                    echo $lighthouseresult_audits['critical-request-chains']['displayValue'];
                                                                     ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                </div>                                                 
                                                <div class="col-12 col-md-4 pr-0">
                                                    <div class="ticket-item">
                                                        <div class="ticket-title" style="color:#6777ef;">
                                                            <h4>
                                                                <?php 
                                                                if(isset($lighthouseresult_audits['dom-size']['title']))
                                                                   echo $lighthouseresult_audits['dom-size']['title']; 
                                                                 ?>
                                                              <i class="fas fa-info-circle field_data_modal"
                                                              data-description="<?php

                                                                if(isset($lighthouseresult_audits['dom-size']['title']))
                                                                echo "<h2 class='section-title'>{$lighthouseresult_audits['dom-size']['title']} </h2>";

                                                                if(isset($lighthouseresult_audits['dom-size']['description'])){

                                                                $dom_size1 = explode('[',$lighthouseresult_audits['dom-size']['description']);

                                                                    
                                                                echo "<p style='font-size: 12px;line-height: initial;'>{$dom_size1[0]}<b><a class='text-danger' target='_BLANK' href='https://developers.google.com/web/fundamentals/performance/rendering/reduce-the-scope-and-complexity-of-style-calculations'>{$this->lang->line('Learn More')}</a></b></p>";
                                                                }

                                                                ?>">
                                                                  
                                                              </i>
                                                            </h4>
                                                            <div class="ticket-info" style="word-break:break-all;font-size:11px;">
                                                                <div>
                                                                    <?php 
                                                                    if(isset($lighthouseresult_audits['dom-size']['displayValue']))
                                                                    echo $lighthouseresult_audits['dom-size']['displayValue'];
                                                                     ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                </div>                                                  
                                                <div class="col-12 col-md-4 pr-0">
                                                    <div class="ticket-item">
                                                        <div class="ticket-title" style="color:#6777ef;">
                                                            <h4>
                                                                <?php 
                                                                if(isset($lighthouseresult_audits['redirects']['title']))
                                                                   echo $lighthouseresult_audits['redirects']['title']; 
                                                                 ?>
                                                              <i class="fas fa-info-circle field_data_modal"
                                                              data-description="<?php

                                                                if(isset($lighthouseresult_audits['redirects']['title']))
                                                                echo "<h2 class='section-title'>{$lighthouseresult_audits['redirects']['title']} </h2>";

                                                                if(isset($lighthouseresult_audits['redirects']['description'])){

                                                                $dom_size1 = explode('[',$lighthouseresult_audits['redirects']['description']);

                                                                    
                                                                echo "<p style='font-size: 12px;line-height: initial;'>{$dom_size1[0]}<b><a class='text-danger' target='_BLANK' href='https://web.dev/redirects'>{$this->lang->line('Learn More')}</a></b></p>";
                                                                }

                                                                ?>">
                                                                  
                                                              </i>
                                                            </h4>
                                                            <div class="ticket-info" style="word-break:break-all;font-size:11px;">
                                                                <div>
                                                                    <?php 
                                                                    if(isset($lighthouseresult_audits['redirects']['displayValue']))
                                                                    echo $lighthouseresult_audits['redirects']['displayValue'];
                                                                     ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                </div>                                                
                                                <div class="col-12 col-md-4 pr-0">
                                                    <div class="ticket-item">
                                                        <div class="ticket-title" style="color:#6777ef;">
                                                            <h4>
                                                                <?php 
                                                                if(isset($lighthouseresult_audits['unminified-javascript']['title']))
                                                                   echo $lighthouseresult_audits['unminified-javascript']['title']; 
                                                                 ?>
                                                              <i class="fas fa-info-circle field_data_modal"
                                                              data-description="<?php

                                                                if(isset($lighthouseresult_audits['unminified-javascript']['title']))
                                                                echo "<h2 class='section-title'>{$lighthouseresult_audits['unminified-javascript']['title']} </h2>";

                                                                if(isset($lighthouseresult_audits['unminified-javascript']['description'])){

                                                                $dom_size1 = explode('[',$lighthouseresult_audits['unminified-javascript']['description']);

                                                                    
                                                                echo "<p style='font-size: 12px;line-height: initial;'>{$dom_size1[0]}<b><a class='text-danger' target='_BLANK' href='https://web.dev/unminified-javascript'>{$this->lang->line('Learn More')}</a></b></p>";
                                                                }

                                                                ?>">
                                                                  
                                                              </i>
                                                            </h4>
                                                            <div class="ticket-info" style="word-break:break-all;font-size:11px;">
                                                                <div>
                                                                    <?php 
                                                                    if(isset($lighthouseresult_audits['unminified-javascript']['displayValue']))
                                                                    echo $lighthouseresult_audits['unminified-javascript']['displayValue'];
                                                                     ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                </div>                                                  
                                                <div class="col-12 col-md-4 pr-0">
                                                    <div class="ticket-item">
                                                        <div class="ticket-title" style="color:#6777ef;">
                                                            <h4>
                                                                <?php 
                                                                if(isset($lighthouseresult_audits['user-timings']['title']))
                                                                   echo $lighthouseresult_audits['user-timings']['title']; 
                                                                 ?>
                                                              <i class="fas fa-info-circle field_data_modal"
                                                              data-description="<?php

                                                                if(isset($lighthouseresult_audits['user-timings']['title']))
                                                                echo "<h2 class='section-title'>{$lighthouseresult_audits['user-timings']['title']} </h2>";

                                                                if(isset($lighthouseresult_audits['user-timings']['description'])){

                                                                $dom_size1 = explode('[',$lighthouseresult_audits['user-timings']['description']);

                                                                    
                                                                echo "<p style='font-size: 12px;line-height: initial;'>{$dom_size1[0]}<b><a class='text-danger' target='_BLANK' href='https://web.dev/user-timings'>{$this->lang->line('Learn More')}</a></b></p>";
                                                                }

                                                                ?>">
                                                                  
                                                              </i>
                                                            </h4>
                                                            <div class="ticket-info" style="word-break:break-all;font-size:11px;">
                                                                <div>
                                                                    <?php 
                                                                    if(isset($lighthouseresult_audits['user-timings']['displayValue']))
                                                                    echo $lighthouseresult_audits['user-timings']['displayValue'];
                                                                     ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                </div>                                                  
                                                <div class="col-12 col-md-4 pr-0">
                                                    <div class="ticket-item">
                                                        <div class="ticket-title" style="color:#6777ef;">
                                                            <h4>
                                                                <?php 
                                                                if(isset($lighthouseresult_audits['network-rtt']['title']))
                                                                   echo $lighthouseresult_audits['network-rtt']['title']; 
                                                                 ?>
                                                              <i class="fas fa-info-circle field_data_modal"
                                                              data-description="<?php

                                                                if(isset($lighthouseresult_audits['network-rtt']['title']))
                                                                echo "<h2 class='section-title'>{$lighthouseresult_audits['network-rtt']['title']} </h2>";

                                                                if(isset($lighthouseresult_audits['network-rtt']['description'])){

                                                                $dom_size1 = explode('[',$lighthouseresult_audits['network-rtt']['description']);

                                                                    
                                                                echo "<p style='font-size: 12px;line-height: initial;'>{$dom_size1[0]}<b><a class='text-danger' target='_BLANK' href='https://web.dev/user-timings'>{$this->lang->line('Learn More')}</a></b></p>";
                                                                }

                                                                ?>">
                                                                  
                                                              </i>
                                                            </h4>
                                                            <div class="ticket-info" style="word-break:break-all;font-size:11px;">
                                                                <div>
                                                                    <?php 
                                                                    if(isset($lighthouseresult_audits['network-rtt']['displayValue']))
                                                                    echo $lighthouseresult_audits['network-rtt']['displayValue'];
                                                                     ?>
                                                                </div>
                                                            </div>
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
		</div>
	</div>
	
	<div class="row mb-5">
		<div class="col-12 col-md-6">
			<!-- Ip information -->
			<ul class="list-group ip-info">
				<li class="list-group-item bg-primary text-white border-0"><i class="fas fa-map-marker-alt"></i>&nbsp; <?php echo $this->lang->line('IP Information'); ?>
				</li>

				<li class="list-group-item"><b><i class="fas fa-asterisk text-primary"></i>&nbsp; <?php echo $this->lang->line('ISP'); ?> : </b>
					<?php echo $domain_info[0]['ipinfo_isp']; ?>
				</li>
				<li class="list-group-item"><b><i class="fas fa-asterisk text-primary"></i>&nbsp; <?php echo $this->lang->line('IP'); ?> : </b>
					<?php echo $domain_info[0]['ipinfo_ip']; ?>
				</li>
				<li class="list-group-item"><b><i class="fas fa-asterisk text-primary"></i>&nbsp; <?php echo $this->lang->line('Country'); ?> : </b>
					<?php $x= trim(strtoupper($domain_info[0]['ipinfo_country']));?>
					<img style="height: 15px; width: 15px; margin-top: -3px;" alt=" " src="<?php $s_country = array_search($x, $country_list); echo base_url().'assets/images/flags/'.$s_country.'.png'; ?>">&nbsp;<?php echo $x; ?>
				</li>
				<li class="list-group-item"><b><i class="fas fa-asterisk text-primary"></i>&nbsp; <?php echo $this->lang->line('City'); ?> : </b>
					<?php echo $domain_info[0]['ipinfo_city']; ?>
				</li>
				<li class="list-group-item"><b><i class="fas fa-asterisk text-primary"></i>&nbsp; <?php echo $this->lang->line('Region'); ?> : </b>
					<?php echo $domain_info[0]['ipinfo_region']; ?>
				</li>
				<li class="list-group-item"><b><i class="fas fa-asterisk text-primary"></i>&nbsp; <?php echo $this->lang->line('Timezone'); ?> : </b>
					<?php echo $domain_info[0]['ipinfo_time_zone']; ?>
				</li>
				<li class="list-group-item"><b><i class="fas fa-asterisk text-primary"></i>&nbsp; <?php echo $this->lang->line('Latitude'); ?> : </b>
					<?php echo $domain_info[0]['ipinfo_latitude']; ?>
				</li>
				<li class="list-group-item"><b><i class="fas fa-asterisk text-primary"></i>&nbsp; <?php echo $this->lang->line('Longitude'); ?> : </b>
					<?php echo $domain_info[0]['ipinfo_longitude']; ?>
				</li>
			</ul>
		</div>

		<div class="col-12 col-md-6">
			<!-- search engine index info -->
			<ul class="list-group mb-5 search_engine_index">
				<li class="list-group-item bg-info text-white border-0"><i class="fa fa-sort-numeric-asc"></i> <?php echo $this->lang->line('Search Engine Index Info'); ?></li>
				<li class="list-group-item"><b><i class="fab fa-google text-info"></i>&nbsp; 
					<?php echo $this->lang->line('Google Index'); ?></b> : <?php echo $domain_info[0]['google_index_count']; ?>
				</li>
				<li class="list-group-item"><b><i class="fab fa-windows text-info"></i>&nbsp; 
					<?php echo $this->lang->line('Bing Index'); ?></b> : <?php echo $domain_info[0]['bing_index_count']; ?>
				</li>
				<li class="list-group-item"><b><i class="fab fa-yahoo text-info"></i>&nbsp; 
					<?php echo $this->lang->line('Yahoo Index'); ?></b> : <?php echo $domain_info[0]['yahoo_index_count']; ?>
				</li>
			</ul>
			
			<!-- Malware Scan Info -->
			<ul class="list-group">
				<li class="list-group-item bg-danger text-white border-0"><i class="fa fa-shield"></i> <?php echo $this->lang->line('Malware Scan Info'); ?></li>
				<li class="list-group-item"><b><i class="fab fa-google text-danger"></i>&nbsp; 
					<?php echo $this->lang->line('Google Safe Browser'); ?></b> : <?php echo $domain_info[0]['google_safety_status']; ?>
				</li>
				<li class="list-group-item"><b><i class="fa fa-shield text-danger"></i>&nbsp; 
					<?php echo $this->lang->line('Norton'); ?></b> : <?php echo $domain_info[0]['norton_status']; ?>
				</li>
				<li class="list-group-item"><b><i class="fab fa-typo3 text-danger"></i>&nbsp; 
					<?php echo $this->lang->line('Mcafee'); ?></b> : <?php echo $domain_info[0]['macafee_status']; ?>
				</li>
			</ul>
		</div>
	</div>

	<!-- Sites in same ip & simiral websites -->
	<div class="row">
		<div class="col-12 col-md-6">
			<div class="card card-hero custom_card-hero">
				<div class="card-header">
					<div class="card-icon m-0">
						<i class="fas fa-sitemap"></i>
					</div>
					<div class="card-description"><?php echo $this->lang->line('Sites in Same IP'); ?></div>
				</div>
				<div class="card-body p-0">
					<div class="tickets-list">
						<div class="row">
							<?php 
								$sites_in_same_ip = json_decode($domain_info[0]["sites_in_same_ip"],true);
								if(is_array($sites_in_same_ip) && !empty($sites_in_same_ip))	{

									$sites_in_same_ip=array_slice($sites_in_same_ip,1,18);
									$i = 0;
									foreach($sites_in_same_ip as $key=>$value) {
										$i++;
										echo '<div class="col-12 col-md-6">
												<a class="ticket-item border-bottom-0">
													<div class="ticket-title">
														<h6 style="font-size:12px;color:#6777ef;"><i class="fas fa-map-marked"></i> '.strip_tags($value).'</h6>
													</div>
												</a>
											</div>';

										if($i%2 == 0 && $i != 0) {
											echo '</div><div class="row">'; 
										}
									}
								} else if(count($sites_in_same_ip)==0) {
									echo "<div class='col-12'><div class='text-center p-3 h5'>No data to show</div></div>";
								}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-12 col-md-6">
			<div class="card card-hero custom_card-hero">
				<div class="card-header">
					<div class="card-icon m-0">
						<i class="fas fa-globe"></i>
					</div>
					<div class="card-description"><?php echo $this->lang->line('Related Websites'); ?></div>
				</div>
				<div class="card-body p-0">
					<div class="tickets-list">
						<div class="tickets-list">
							<div class="row">
								<?php 
									$similar_sites = json_decode($domain_info[0]['similar_sites'],true);

									if(is_array($similar_sites) && !empty($similar_sites)){
										$i = 0;
										foreach($similar_sites as $key=>$value) {

											$i++;
											echo '<div class="col-12 col-md-6">
													<a class="ticket-item border-bottom-0">
														<div class="ticket-title">
															<h6 style="font-size:12px;color:#6777ef;"><i class="fas fa-globe"></i> '.$value.'</h6>
														</div>
													</a>
												</div>';

											if($i%2 == 0 && $i != 0) {
												echo '</div><div class="row">'; 
											}
										}
									}
									else if(count($similar_sites)==0) {
										echo "<div class='col-12'><div class='text-center h5 p-3'>".$this->lang->line('No data to show')."</div></div>";
									}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="<?php echo base_url(); ?>plugins/knob/jquery.knob.js"></script>
<script>
    $(function() {
        $(".dial").knob();
    });
</script>

<script type="text/javascript">
  $("document").ready(function(){

    $(document).on('click','.field_data_modal',function(e){
        e.preventDefault();
        $("#field_data_modal").modal();
        var data_description = $(this).attr("data-description");
        $('.modal_value').html(data_description);
      });    
    
  });
</script>

<div class="modal" id="field_data_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"> <i class="fab fa-google"></i> <?php echo $this->lang->line("Google PageSpeed Insights");?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>

      <div class="modal-body">    
        <div class="section modal_value">                
            

        </div>
      </div>
      <div class="modal-footer text-center">
              <a class="btn btn-outline-secondary" data-dismiss="modal"><i class="fas fa-times"></i> <?php echo $this->lang->line("Close") ?></a>
      </div>
    </div>
  </div>
</div>



