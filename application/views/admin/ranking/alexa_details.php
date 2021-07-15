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
		"Keyword Gaps" => isset($keyword_opportunitites_values[0]) ? $keyword_opportunitites_values[0] : 0,
		"Easy-to-Rank Keywords" => isset($keyword_opportunitites_values[1]) ? $keyword_opportunitites_values[1] : 0,
		"Optimization Opportunities" => isset($keyword_opportunitites_values[2]) ? $keyword_opportunitites_values[2] : 0,
		"Buyer Keywords" => isset($keyword_opportunitites_values[3]) ? $keyword_opportunitites_values[3] : 0,
	);


	$total_keyword_opportunities_breakdown = $alexa_data_full["total_keyword_opportunities_breakdown"];
	$similar_sites = json_decode($alexa_data_full["similar_sites"],true);
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
	<div class="col-12 col-md-8">
		<div class="card">
			<div class="card-header">
				<h4><i class="fas fa-bars"></i> <?php echo $this->lang->line('Alexa Data'); ?></h4>
			</div>

			<div class="card-body">
				<div class="row">
					<!-- domain name -->
					<div class="col-12 col-md-6">
						<div class="card card-statistic-1">
							<div class="card-icon bg-primary">
								<i class="fas fa-bullseye"></i>
							</div>
							<div class="card-wrap">
								<div class="card-header">
									<h4><?php echo $this->lang->line('Domain Name'); ?></h4>
								</div>
								<div class="card-body"><h6 class="mt-2"><?php echo $domain; ?></h6></div>
							</div>
						</div>
					</div>

					<!-- global rank -->
					<div class="col-12 col-md-6">
						<div class="card card-statistic-1">
							<div class="card-icon bg-success">
								<i class="fas fa-globe"></i>
							</div>
							<div class="card-wrap">
								<div class="card-header">
									<h4><?php echo $this->lang->line('Global Rank'); ?>
										<a href="#" data-placement="top" data-trigger="focus" data-toggle="popover" title="<?php echo $this->lang->line("Global Rank"); ?>" data-content="<?php echo $this->lang->line("This site ranks in global internet engagement."); ?>"><i class='fa fa-info-circle'></i> </a>
									</h4>
								</div>
								<div class="card-body"><h6 class="mt-2"><?php echo $alexa_rank; ?></h6></div>
							</div>
						</div>
					</div>

					<!-- time on site -->
					<div class="col-12 col-md-6">
						<div class="card card-statistic-1">
							<div class="card-icon bg-warning">
								<i class="far fa-clock"></i>
							</div>
							<div class="card-wrap">
								<div class="card-header">
									<h4><?php echo $this->lang->line('Daily Time on Site'); ?>
										<a href="#" data-placement="top" data-trigger="focus" data-toggle="popover" title="<?php echo $this->lang->line("Daily Time on Site"); ?>" data-content="<?php echo $this->lang->line("Average time in minutes and seconds that a visitor spends on this site each day."); ?>"><i class='fa fa-info-circle'></i> </a>
									</h4>
								</div>
								<div class="card-body"><h6 class="mt-2"><?php echo $alexa_rank_spend_time; ?></h6></div>
							</div>
						</div>
					</div>
					
					<!-- search traffic -->
					<div class="col-12 col-md-6">
						<div class="card card-statistic-1">
							<div class="card-icon bg-info">
								<i class="fas fa-traffic-light"></i>
							</div>
							<div class="card-wrap">
								<div class="card-header">
									<h4><?php echo $this->lang->line('Search Traffic'); ?>
										<a href="#" data-placement="top" data-trigger="focus" data-toggle="popover" title="<?php echo $this->lang->line("Search Traffic"); ?>" data-content="<?php echo $this->lang->line("The percentage of organic search referrals to this site."); ?>"><i class='fa fa-info-circle'></i> </a>
									</h4>
								</div>
								<div class="card-body"><h6 class="mt-2"><?php echo $site_search_traffic; ?></h6></div>
							</div>
						</div>
					</div>

					<!-- bounce rate  -->
					<div class="col-12 col-md-6">
						<div class="card card-statistic-1">
							<div class="card-icon bg-danger">
								<i class="fab fa-buffer"></i>
							</div>
							<div class="card-wrap">
								<div class="card-header">
									<h4><?php echo $this->lang->line('Bounce Rate'); ?>
										<a href="#" data-placement="top" data-trigger="focus" data-toggle="popover" title="<?php echo $this->lang->line("Bounce Rate"); ?>" data-content="<?php echo $this->lang->line("Percentage of visits to the site that consist of a single pageview."); ?>"><i class='fa fa-info-circle'></i> </a>
									</h4>
								</div>
								<div class="card-body"><?php echo $bounce_rate; ?></div>
							</div>
						</div>
					</div>

					<!-- total sites linking -->
					<div class="col-12 col-md-6">
						<div class="card card-statistic-1">
							<div class="card-icon bg-direction">
								<i class="fas fa-link"></i>
							</div>
							<div class="card-wrap">
								<div class="card-header">
									<h4><?php echo $this->lang->line('Total Sites Link In'); ?>
										<a href="#" data-placement="top" data-trigger="focus" data-toggle="popover" title="<?php echo $this->lang->line("Total Sites Link In"); ?>" data-content="<?php echo $this->lang->line("Sites that link to this site, recalculated weekly."); ?>"><i class='fa fa-info-circle'></i> </a>
									</h4>
								</div>
								<div class="card-body"><?php echo $total_sites_linking_in; ?></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-12 col-md-4">
		<div class="card">
			<div class="card-header">
				<h4><i class="fas fa-bug"></i> <?php echo $this->lang->line('Keyword Opportunities Breakdown'); ?>
					<a href="#" data-placement="top" data-trigger="focus" data-toggle="popover" title="<?php echo $this->lang->line("Keyword Opportunities Breakdown"); ?>" data-content="<?php echo $this->lang->line("These are customized keyword recommendations this site could target to drive more traffic."); ?>"><i class='fa fa-info-circle'></i> </a>
				</h4>
			</div>

			<div class="card-body">
				<div class="keyword_opportunities_breakdown_chart_container mt-4">
					<canvas id="keyword_opportunities_breakdown"></canvas>
				</div>
				
				<ul class="list-unstyled list-unstyled-border list-unstyled-noborder mb-0 mt-4">
					<li class="media mb-0 pb-0 border-bottom-0">
						<div class='social_shared_icon' style='margin-top:10px !important;background-color: #ae3d63 !important;'></div>
						<div class="media-body ml-2">
							<div class="media-title font-12"><?php echo $this->lang->line('Keyword Gaps'); ?> <span class="red">(<?php echo $keyword_opportunitites_values_pie_chart['Keyword Gaps']; ?>)</span></div>
						</div>
					</li>
					<li class="media mb-0 pb-0 border-bottom-0">
						<div class='social_shared_icon' style='margin-top:10px !important;background-color: #f45c44 !important;'></div>
						<div class="media-body ml-2">
							<div class="media-title font-12"><?php echo $this->lang->line('Easy-to-Rank Keywords'); ?> <span class="red">(<?php echo $keyword_opportunitites_values_pie_chart['Easy-to-Rank Keywords']; ?>)</span></div>
						</div>
					</li>
					<li class="media mb-0 pb-0 border-bottom-0">
						<div class='social_shared_icon' style='margin-top:10px !important;background-color: #f8b646 !important;'></div>
						<div class="media-body ml-2">
							<div class="media-title font-12"><?php echo $this->lang->line('Optimization Opportunities'); ?> <span class="red">(<?php echo $keyword_opportunitites_values_pie_chart['Optimization Opportunities']; ?>)</span></div>
						</div>
					</li>
					<li class="media mb-0 pb-0 border-bottom-0">
						<div class='social_shared_icon' style='margin-top:10px !important;background-color: #55476a !important;'></div>
						<div class="media-body ml-2">
							<div class="media-title font-12"><?php echo $this->lang->line('Buyer Keywords'); ?> <span class="red">(<?php echo $keyword_opportunitites_values_pie_chart['Buyer Keywords']; ?>)</span></div>
						</div>
					</li>
				</ul>
				
			</div>

			<div class="card-footer pt-0">
				<div class="buttons text-center">
					<button type="button" class="btn btn-primary">
						<i class="fas fa-plane"></i> <?php echo $this->lang->line('Total'); ?> <span class="badge badge-transparent"><?php echo $total_keyword_opportunities_breakdown; ?></span>
					</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<!-- Top 5 Similar Sites by Audience Overlap -->
	<div class="col-12 col-md-6">
		<div class="card data-card card-primary">
			<div class="card-header">
				<h4><i class="fas fa-globe"></i> <?php echo $this->lang->line('Top 5 Similar Sites by Audience Overlap'); ?>
					<a href="#" data-placement="top" data-trigger="focus" data-toggle="popover" title="<?php echo $this->lang->line("Similar Sites by Audience Overlap"); ?>" data-content="<?php echo $this->lang->line("Sites that share the same visitors and search keywords with this site, sorted by most overlap to least overlap."); ?>"><i class='fa fa-info-circle'></i> </a>
				</h4>
			</div>

			<div class="card-body">
				<div class="table-responsive" style="height:400px;">
					<table class="table table-hover table-bordered">
						<thead class="thead-light thead-primary">
							<tr>
								<th scope="col"><?php echo $this->lang->line('SL'); ?></th>
								<th scope="col"><?php echo $this->lang->line('Similar sites'); ?></th>
								<th scope="col"><?php echo $this->lang->line('Overlap score'); ?>
									<a href="#" data-placement="top" data-trigger="focus" data-toggle="popover" title="<?php echo $this->lang->line("Overlap score"); ?>" data-content="<?php echo $this->lang->line("A relative level of audience overlap between this site and similar sites. Audience overlap score is calculated from an analysis of common visitors and/or search keywords.A site with a higher score shows higher audience overlap than a site with lower score."); ?>"><i class='fa fa-info-circle'></i> </a>
								</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								$sl=0;                  
					            if(is_array($similar_sites) && is_array($similar_site_overlap))
					            {
 				                    foreach ($similar_sites as $key => $value) {
 				                    	$sl++;
                    	                    if(array_key_exists($key, $similar_sites) && array_key_exists($key, $similar_site_overlap) )
                    	                    {
                    	                    	echo "<tr><td>".$sl."</td>";
                    		                    echo "<td>".$similar_sites[$key]."</td>";
                    		                    echo "<td>".$similar_site_overlap[$key]."</td></tr>";
                    		                   
                    		                }
 				                    }

					                if(count($similar_sites)==0 || count($similar_site_overlap)==0  )
					                echo "<tr><td colspan='3' class='text-center'>No data found!</td></tr>";
					            }
					            else
					            {
					            	echo "<tr><td colspan='3' class='text-center'>No data found!</td></tr>";
					            }

							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<!-- Top 5 Keywords By Traffic -->
	<div class="col-12 col-md-6">
		<div class="card data-card card-primary">
			<div class="card-header">
				<h4><i class="fas fa-globe"></i> <?php echo $this->lang->line('Top 5 Keywords By Traffic'); ?>
					<a href="#" data-placement="top" data-trigger="focus" data-toggle="popover" title="<?php echo $this->lang->line("Top 5 Keywords By Traffic"); ?>" data-content="<?php echo $this->lang->line("Top organic keywords that are driving traffic to this site."); ?>"><i class='fa fa-info-circle'></i> </a>
				</h4>
			</div>

			<div class="card-body">
				<div class="table-responsive" style="height:400px;">
					<table class="table table-hover table-bordered">
						<thead class="thead-light thead-primary">
							<tr>
								<th scope="col"><?php echo $this->lang->line('Keywords'); ?>
									<a href="#" data-placement="top" data-trigger="focus" data-toggle="popover" title="<?php echo $this->lang->line("Keywords"); ?>" data-content="<?php echo $this->lang->line("Top organic keywords that are driving traffic to this site."); ?>"><i class='fa fa-info-circle'></i> </a>
								</th>
								<th scope="col"><?php echo $this->lang->line('Search Traffic'); ?>
									<a href="#" data-placement="top" data-trigger="focus" data-toggle="popover" title="<?php echo $this->lang->line("Search Traffic"); ?>" data-content="<?php echo $this->lang->line("The percentage of organic search referrals to this site that come from this keyword."); ?>"><i class='fa fa-info-circle'></i> </a>
								</th>
								<th scope="col"><?php echo $this->lang->line('Share of Voice'); ?>
									<a href="#" data-placement="top" data-trigger="focus" data-toggle="popover" title="<?php echo $this->lang->line("Organic Share of Voice"); ?>" data-content="<?php echo $this->lang->line("The percentage of all searches for this keyword that sent traffic to this website."); ?>"><i class='fa fa-info-circle'></i> </a>
								</th>
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
					                	echo "<tr><td colspan='3' class='text-center'>No data found!</td></tr>";
					            }
					            else
					            {
					            	echo "<tr><td colspan='3' class='text-center'>No data found!</td></tr>";
					            }

							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<!-- Top 4 Keyword Gaps -->
	<div class="col-12 col-md-6">
		<div class="card data-card card-primary">
			<div class="card-header">
				<h4><i class="fas fa-globe"></i> <?php echo $this->lang->line('Top 4 Keyword Gaps'); ?></h4>
			</div>

			<div class="card-body">
				<div class="table-responsive" style="height:400px;">
					<table class="table table-hover table-bordered">
						<thead class="thead-light thead-primary">
							<tr>
								<th scope="col"><?php echo $this->lang->line('Keywords driving traffic to competitors, but not to this site'); ?>
									<a href="#" data-placement="top" data-trigger="focus" data-toggle="popover" title="<?php echo $this->lang->line("Keywords driving traffic to competitors, but not to this site"); ?>" data-content="<?php echo $this->lang->line("This site is not gaining any traffic from these keywords.If competitors are gaining traffic from the keyword, this may be a good investment opportunity."); ?>"><i class='fa fa-info-circle'></i> </a>
								</th>
								<th scope="col"><?php echo $this->lang->line('Avg. Traffic to Competitors'); ?>
									<a href="#" data-placement="top" data-trigger="focus" data-toggle="popover" title="<?php echo $this->lang->line("Avg. Traffic to Competitors"); ?>" data-content="<?php echo $this->lang->line("An estimate of the traffic that competitors are getting for this keyword.The score is based on the popularity of the keyword, and how well competitors rank for it.The score ranges from 1 (least traffic) to 100 (most traffic)."); ?>"><i class='fa fa-info-circle'></i> </a>
								</th>
								<th scope="col"><?php echo $this->lang->line('Search Popularity'); ?>
									<a href="#" data-placement="top" data-trigger="focus" data-toggle="popover" title="<?php echo $this->lang->line("Organic Share of Voice"); ?>" data-content="<?php echo $this->lang->line("An estimate of how frequently this keyword is searched across all search engines.The score ranges from 1 (least popular) to 100 (most popular)."); ?>"><i class='fa fa-info-circle'></i> </a>
								</th>
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
					                echo "<tr><td colspan='3' class='text-center'>No data found!</td></tr>";
					            }
					            else
					            {
					            	echo "<tr><td colspan='3' class='text-center'>No data found!</td></tr>";
					            }

							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<!-- Top 4 Easy-to-Rank Keywords -->
	<div class="col-12 col-md-6">
		<div class="card data-card card-primary">
			<div class="card-header">
				<h4><i class="fas fa-globe"></i> <?php echo $this->lang->line('Top 4 Easy-to-Rank Keywords'); ?></h4>
			</div>

			<div class="card-body">
				<div class="table-responsive" style="height:400px;">
					<table class="table table-hover table-bordered">
						<thead class="thead-light thead-primary">
							<tr>
								<th scope="col"><?php echo $this->lang->line('Popular keywords within this site`s competitive power'); ?>
									<a href="#" data-placement="top" data-trigger="focus" data-toggle="popover" title="<?php echo $this->lang->line("Easy-to-Rank Keywords"); ?>" data-content="<?php echo $this->lang->line("Popular keywords within this site's competitive power."); ?>"><i class='fa fa-info-circle'></i> </a>
								</th>
								<th scope="col"><?php echo $this->lang->line('Relevance to this site'); ?>
									<a href="#" data-placement="top" data-trigger="focus" data-toggle="popover" title="<?php echo $this->lang->line("Relevance to this site"); ?>" data-content="<?php echo $this->lang->line("An estimate of how relevant a keyword is to this site.The score is based on the keyword's relevance to other keywords that currently drive traffic to this site.The score ranges from 1 (least relevant) to 100 (most relevant)."); ?>"><i class='fa fa-info-circle'></i> </a>
								</th>
								<th scope="col"><?php echo $this->lang->line('Search Popularity'); ?>
									<a href="#" data-placement="top" data-trigger="focus" data-toggle="popover" title="<?php echo $this->lang->line("Search Popularity"); ?>" data-content="<?php echo $this->lang->line("An estimate of how frequently this keyword is searched across all search engines.The score ranges from 1 (least popular) to 100 (most popular)."); ?>"><i class='fa fa-info-circle'></i> </a>
								</th>
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
					                echo "<tr><td colspan='3' class='text-center'>No data found!</td></tr>";
					            }
					            else
					            {
					            	echo "<tr><td colspan='3' class='text-center'>No data found!</td></tr>";
					            }

							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<!-- Top 4 Buyer Keywords -->
	<div class="col-12 col-md-6">
		<div class="card data-card card-primary">
			<div class="card-header">
				<h4><i class="fas fa-globe"></i> <?php echo $this->lang->line('Top 4 Buyer Keywords'); ?></h4>
			</div>

			<div class="card-body">
				<div class="table-responsive" style="height:400px;">
					<table class="table table-hover table-bordered">
						<thead class="thead-light thead-primary">
							<tr>
								<th scope="col"><?php echo $this->lang->line('Keywords that show a high purchase intent'); ?>
									<a href="#" data-placement="top" data-trigger="focus" data-toggle="popover" title="<?php echo $this->lang->line("Buyer Keyword"); ?>" data-content="<?php echo $this->lang->line("These keywords include certain phrases commonly associated with purchases."); ?>"><i class='fa fa-info-circle'></i> </a>
								</th>
								<th scope="col"><?php echo $this->lang->line('Avg. Traffic to Competitors'); ?>
									<a href="#" data-placement="top" data-trigger="focus" data-toggle="popover" title="<?php echo $this->lang->line("Avg. Traffic to Competitors"); ?>" data-content="<?php echo $this->lang->line("An estimate of the traffic that competitors are getting for this keyword.The score is based on the popularity of the keyword, and how well competitors rank for it.The score ranges from 1 (least traffic) to 100 (most traffic)."); ?>"><i class='fa fa-info-circle'></i> </a>
								</th>
								<th scope="col"><?php echo $this->lang->line('Organic Competition'); ?>
									<a href="#" data-placement="top" data-trigger="focus" data-toggle="popover" title="<?php echo $this->lang->line("Organic Competition"); ?>" data-content="<?php echo $this->lang->line("An estimate of how difficult it is to rank highly for this keyword in organic search.The score ranges from 1 (least competition) to 100 (most competition)."); ?>"><i class='fa fa-info-circle'></i> </a>
								</th>
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
					                echo "<tr><td colspan='3' class='text-center'>No data found!</td></tr>";
					            }
					            else
					            {
					            	echo "<tr><td colspan='3' class='text-center'>No data found!</td></tr>";
					            }

							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<!-- Top 4 Optimization Opportunities -->
	<div class="col-12 col-md-6">
		<div class="card data-card card-primary">
			<div class="card-header">
				<h4><i class="fas fa-globe"></i> <?php echo $this->lang->line('Top 4 Optimization Opportunities'); ?></h4>
			</div>

			<div class="card-body">
				<div class="table-responsive" style="height:400px;">
					<table class="table table-hover table-bordered">
						<thead class="thead-light thead-primary">
							<tr>
								<th scope="col"><?php echo $this->lang->line('Very popular keywords already driving some traffic to this site'); ?>
									<a href="#" data-placement="top" data-trigger="focus" data-toggle="popover" title="<?php echo $this->lang->line("Optimization Opportunities"); ?>" data-content="<?php echo $this->lang->line("Growing traffic for these popular keywords may be easier than trying to rank for brand new keywords."); ?>"><i class='fa fa-info-circle'></i> </a>
								</th>
								<th scope="col"><?php echo $this->lang->line('Search Popularity'); ?>
									<a href="#" data-placement="top" data-trigger="focus" data-toggle="popover" title="<?php echo $this->lang->line("Search Popularity"); ?>" data-content="<?php echo $this->lang->line("An estimate of how frequently this keyword is searched across all search engines.The score ranges from 1 (least popular) to 100 (most popular)."); ?>"><i class='fa fa-info-circle'></i> </a>
								</th>
								<th scope="col"><?php echo $this->lang->line('Organic Share of Voice'); ?>
									<a href="#" data-placement="top" data-trigger="focus" data-toggle="popover" title="<?php echo $this->lang->line("Organic Share of Voice"); ?>" data-content="<?php echo $this->lang->line("The percentage of all searches for this keyword that sent traffic to this website."); ?>"><i class='fa fa-info-circle'></i> </a>
								</th>
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
					                echo "<tr><td colspan='3' class='text-center'>No data found!</td></tr>";
					            }
					            else
					            {
					            	echo "<tr><td colspan='3' class='text-center'>No data found!</td></tr>";
					            }

							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<!-- Top 5 Referral Sites -->
	<div class="col-12 col-md-6">
		<div class="card data-card card-primary">
			<div class="card-header">
				<h4><i class="fas fa-globe"></i> <?php echo $this->lang->line('Top 5 Referral Sites'); ?></h4>
			</div>

			<div class="card-body">
				<div class="table-responsive" style="height:400px;">
					<table class="table table-hover table-bordered">
						<thead class="thead-light thead-primary">
							<tr>
								<th scope="col"><?php echo $this->lang->line('Sites by how many other sites drive traffic to them'); ?>
									<a href="#" data-placement="top" data-trigger="focus" data-toggle="popover" title="<?php echo $this->lang->line("Optimization Opportunities"); ?>" data-content="<?php echo $this->lang->line("Ordered by how many other websites link to them, which can be used to evaluate a site's reputation on the internet."); ?>"><i class='fa fa-info-circle'></i> </a>
								</th>
								<th scope="col"><?php echo $this->lang->line('Referral Sites'); ?>
									<a href="#" data-placement="top" data-trigger="focus" data-toggle="popover" title="<?php echo $this->lang->line("Referral Sites"); ?>" data-content="<?php echo $this->lang->line("Also referred to as 'Sites Linking In', this is the number of sites linking to facebook.com that Alexa's web crawl has found."); ?>"><i class='fa fa-info-circle'></i> </a>
								</th>
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
                    		                    echo "<td>".$similar_site_overlap[$key]."</td></tr>";
                    		             
                    		                   
                    		                }
 				                    }

					                if(count($refferal_sites)==0 || count($similar_site_overlap)==0 )
					                echo "<tr><td colspan='2' class='text-center'>No data found!</td></tr>";
					            }
					            else
					            {
					            	echo "<tr><td colspan='2' class='text-center'>No data found!</td></tr>";
					            }

							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<!-- Site Flow -->
	<div class="col-12 col-md-6">
		<div class="card data-card card-primary">
			<div class="card-header">
				<h4><i class="fas fa-globe"></i> <?php echo $this->lang->line('Site Flow'); ?></h4>
			</div>

			<div class="card-body">
				<div class="table-responsive" style="height:400px;">
					<table class="table table-hover table-bordered">
						<thead class="thead-light thead-primary">
							<tr>
								<th scope="col"><?php echo $this->lang->line('Visited just before & right after domain'); ?>
									<a href="#" data-placement="top" data-trigger="focus" data-toggle="popover" title="<?php echo $this->lang->line("Visited just before domain"); ?>" data-content="<?php echo $this->lang->line("Sites that people visited immediately before this one."); ?>"><i class='fa fa-info-circle'></i> </a>
								</th>
								<th scope="col"><?php echo $this->lang->line('Visited just before & right after domain Percentage'); ?></th>
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
					                echo "<tr><td colspan='2' class='text-center'>No data found!</td></tr>";
					            }
					            else
					            {
					            	echo "<tr><td colspan='2' class='text-center'>No data found!</td></tr>";
					            }

							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<!-- Top 5 Audience Overlap -->
	<div class="col-12 col-md-6">
		<div class="card data-card card-primary">
			<div class="card-header">
				<h4><i class="fas fa-globe"></i> <?php echo $this->lang->line('Top 5 Audience Overlap'); ?></h4>
			</div>

			<div class="card-body">
				<div class="table-responsive" style="height:400px;">
					<table class="table table-hover table-bordered">
						<thead class="thead-light thead-primary">
							<tr>
								<th scope="col"><?php echo $this->lang->line('Similar Sites to This Site'); ?></th>
								<th scope="col"><?php echo $this->lang->line('Site’s Overlap Score'); ?>
									<a href="#" data-placement="top" data-trigger="focus" data-toggle="popover" title="<?php echo $this->lang->line("Site’s Overlap Score"); ?>" data-content="<?php echo $this->lang->line("A relative level of audience overlap between this site and similar sites. Audience overlap score is calculated from an analysis of common visitors and/or search keywords.A site with a higher score shows higher audience overlap than a site with lower score."); ?>"><i class='fa fa-info-circle'></i> </a>
								</th>
								<th scope="col"><?php echo $this->lang->line('Alexa Rank'); ?>
									<a href="#" data-placement="top" data-trigger="focus" data-toggle="popover" title="<?php echo $this->lang->line("Alexa Rank"); ?>" data-content="<?php echo $this->lang->line("An estimate of this site's popularity.The rank is calculated using a combination of average daily visitors to this site and pageviews on this site over the past 3 months. The site with the highest combination of visitors and pageviews is ranked #1.Updated Daily."); ?>"><i class='fa fa-info-circle'></i> </a>
								</th>
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
					                echo "<tr><td colspan='3' class='text-center'>No data found!</td></tr>";
					            }
					            else
					            {
					            	echo "<tr><td colspan='3' class='text-center'>No data found!</td></tr>";
					            }

							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<!-- Top 3 Audience Geography -->
	<div class="col-12 col-md-6">
		<div class="card data-card card-primary">
			<div class="card-header">
				<h4><i class="fas fa-globe"></i> <?php echo $this->lang->line('Top 3 Audience Geography'); ?></h4>
			</div>

			<div class="card-body">
				<div class="table-responsive" style="height:400px;">
					<table class="table table-hover table-bordered">
						<thead class="thead-light thead-primary">
							<tr>
								<th scope="col"><?php echo $this->lang->line('Visitors by Country'); ?></th>
								<th scope="col"><?php echo $this->lang->line('Visitors by Country Percentage'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php 
								           
					            if(is_array($card_geography_country) && is_array($card_geography_countryPercent) )
					            {
 				                    foreach ($card_geography_country as $key => $value) {
 				                    	
                    	                    if(array_key_exists($key, $card_geography_country) && array_key_exists($key, $card_geography_countryPercent))
                    	                    {
                    	                    	echo "<tr><td>".$card_geography_country[$key]."</td>";
                    		                    echo "<td>".$card_geography_countryPercent[$key]."</td></tr>";
                    		                   
                    		                   
                    		                }
 				                    }

					                if(count($card_geography_country)==0 || count($card_geography_countryPercent)==0 )
					                echo "<tr><td colspan='2' class='text-center'>No data found!</td></tr>";
					            }
					            else
					            {
					            	echo "<tr><td colspan='2' class='text-center'>No data found!</td></tr>";
					            }

							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>



<style type="text/css" media="screen">
	th{font-family:Arial;}
	.box-body{min-height:270px !important;}
	.progress{margin-bottom:10px;}
</style>
<script type="text/javascript">
	$('[data-toggle="popover"]').popover();

	var keyword_opportunities_breakdown_config = document.getElementById('keyword_opportunities_breakdown').getContext('2d');

	var only_keys = <?php echo json_encode(array_keys($keyword_opportunitites_values_pie_chart)); ?>;
	var only_values = <?php echo json_encode(array_values($keyword_opportunitites_values_pie_chart)); ?>;

	var keyword_opportunities_breakdown_data = {
	 	type: 'doughnut',
	 	data: {
	 		datasets: [{
	 			data: only_values,
	 			backgroundColor: [
	 				'#ae3d63',
	 				'#f45c44',
	 				'#f8b646',
	 				'#55476a',
	 			],
	 			
	 		}],
	 		labels: only_keys,
	 	},
	 	options: {
	 		responsive: true,
	 		legend: {
	 			display: false,
	 		},
	 		
	 		animation: {
	 			animateScale: true,
	 			animateRotate: true
	 		}
	 	}
	 };

	var keyword_opportunities_breakdown_my_chart = new Chart(keyword_opportunities_breakdown_config, keyword_opportunities_breakdown_data);

</script>

