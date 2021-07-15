<style>
	.box-card .card-statistic-1{border:.5px solid #dee2e6;border-radius: 4px;}
	.box-card .card-icon{ border: .5px solid #dee2e6; }
	.bg-body {background: #FAFDFB !important;}
	.social_shared_icon{ width: 40px;height: 10px; }
	.color_codes_div .media { border-bottom: 0; }
	.bg-direction { background-color: #a45fff !important; }
	.font-12 { font-size:12px !important; }
</style>
<input type="hidden" id="domain_id" value="<?php echo $id; ?>"/>

<section class="section">
	<div style="padding:20px;margin-bottom:20px;"></div>

	<div class="section-body">
		<div class="card">
			<div class="card-header">
				<h4><i class="fas fa-globe"></i> <?php echo "Domain Name - <span class='red'>".$domain_info[0]['domain_name']."</span>"; ?></h4>
				<div class="card-header-action">
					<a href="<?php echo base_url('home/frontend_download_pdf/'.$id); ?>" class="btn btn-lg btn-primary"><i class="fas fa-cloud-download-alt"></i> <?php echo $this->lang->line('Download Pdf Report'); ?></a>
				</div>
			</div>
			
			<div class="card-body">
				<ul class="nav nav-tabs" id="myTab" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true"><?php echo $this->lang->line('General'); ?></a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="social_network-tab" data-toggle="tab" href="#social_network" role="tab" aria-controls="social_network" aria-selected="false"><?php echo $this->lang->line('Social Network Information'); ?></a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="meta_tag_info-tab" data-toggle="tab" href="#meta_tag_info" role="tab" aria-controls="meta_tag_info" aria-selected="false"><?php echo $this->lang->line('Keyword & Meta Information'); ?></a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="alexa_info-tab" data-toggle="tab" href="#alexa_info" role="tab" aria-controls="alexa_info" aria-selected="false"><?php echo $this->lang->line('Alexa Information'); ?></a>
					</li>
				</ul>
				<div class="tab-content tab-bordered" id="myTab3Content">
					<div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
						<?php $this->load->view('website_analysis/general'); ?>
					</div>
					<div class="tab-pane fade" id="social_network" role="tabpanel" aria-labelledby="social_network-tab">
						<?php $this->load->view("website_analysis/social_network"); ?>
					</div>
					<div class="tab-pane fade" id="meta_tag_info" role="tabpanel" aria-labelledby="meta_tag_info-tab">
						<?php $this->load->view("website_analysis/meta_tag_info"); ?>
					</div>
					<div class="tab-pane fade" id="alexa_info" role="tabpanel" aria-labelledby="alexa_info-tab">
						<?php $this->load->view('website_analysis/alexa_info'); ?>	
					</div>

				</div>
			</div>

		</div>
	</div>
</section>


<script type="text/javascript">
	$('.reservation').daterangepicker();

	var function_name;

	$("document").ready(function(){
		$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
			e.preventDefault();
			var target = $(e.target).attr("href");
			function_name = target.replace('#','');
			ajax_call(function_name);		

		}); // end of $('a[data-toggle="tab"]')


		$(document).on('click','.search_button',function(){
			ajax_call(function_name);			
		});	


		function ajax_call(function_name)
		{
			var domain_id = $("#domain_id").val();
			var date_range = $("#"+function_name+"_date").val();

			if(function_name == 'visitor_analysis')
				date_range = $("#overview_date").val();

			var base_url="<?php echo base_url(); ?>";
			var data_type = "JSON";
			if(function_name == 'alexa_info' || function_name == 'general' || function_name == 'browser_report' || function_name == 'similarweb_info' || function_name == 'os_report' || function_name == 'device_report' || function_name == 'meta_tag_info')
				data_type = '';
			$('#'+function_name+'_success_msg').html('<img class="center-block" style="margin-top:10px;" src="'+base_url+'assets/pre-loader/Fancy pants.gif" alt="Searching...">');

			$.ajax({
				type: "POST",
				url : "<?php echo site_url('home/front_ajax_get_"+function_name+"_data/'); ?>",
				data:{domain_id:domain_id,date_range:date_range},
				dataType: data_type,
				async: false,
				success:function(response){
					$('#'+function_name+'_success_msg').html('');
					$("#"+function_name+"_name").html(response);
					var pieOptions = {
					    //Boolean - Whether we should show a stroke on each segment
					    segmentShowStroke: true,
					    //String - The colour of each segment stroke
					    segmentStrokeColor: "#fff",
					    //Number - The width of each segment stroke
					    segmentStrokeWidth: 1,
					    //Number - The percentage of the chart that we cut out of the middle
					    percentageInnerCutout: 30, // This is 0 for Pie charts
					    //Number - Amount of animation steps
					    animationSteps: 100,
					    //String - Animation easing effect
					    animationEasing: "easeOutBounce",
					    //Boolean - Whether we animate the rotation of the Doughnut
					    animateRotate: true,
					    //Boolean - Whether we animate scaling the Doughnut from the centre
					    animateScale: false,
					    //Boolean - whether to make the chart responsive to window resizing
					    responsive: true,
					    // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
					    maintainAspectRatio: false,
					    //String - A tooltip template
					    tooltipTemplate: "<%=value %> <%=label%>"
					};

					/*************** for general page **********************/
					if(function_name == 'general'){
						$("#hide_after_ajax").hide();
					}
					/************** end of general page *******************/

					// for alexa information page
					// if (function_name == 'alexa_info') {

					// }
					// end of alexa information

					/********************* for social network page *************************/
					if (function_name == 'social_network') {
						var social_network_shared_config = document.getElementById('social_network_shared_data').getContext('2d');

						var only_keys = Object.keys(response.social_network_info);
						var only_values = Object.values(response.social_network_info);

						var social_network_shared_chart_data = {
						 	type: 'doughnut',
						 	data: {
						 		datasets: [{
						 			data: only_values,
						 			backgroundColor: [
						 				'#003f5c',
						 				'#4571ef',
						 				'#ce6f45',
						 				'#00a8c0',
						 				'#58508d',
						 				'#bc5090',
						 				'#ff6361',
						 				'#ffa600',
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

						var social_network_info_my_chart = new Chart(social_network_shared_config, social_network_shared_chart_data);

						$(".domain_name").text(response.domain_name);

						$("#color_codes").html(response.color_codes);
						$("#fb_total_reaction").text(response.fb_total_reaction);
						$("#fb_total_comment").text(response.fb_total_comment);
						$("#fb_total_share").text(response.fb_total_share);
						$("#fb_total_comment_plugin").text(response.fb_total_comment_plugin);

						// $("#stumbleupon_total_like").text(response.stumbleupon_total_like);
						// $("#stumbleupon_total_comment").text(response.stumbleupon_total_comment);
						// $("#stumbleupon_total_view").text(response.stumbleupon_total_view);
						// $("#stumbleupon_total_list").text(response.stumbleupon_total_list);

						$("#reddit_score").text(response.reddit_score);
						$("#reddit_ups").text(response.reddit_ups);
						$("#reddit_downs").text(response.reddit_downs);

						$("#google_plus_share").text(response.google_plus_share);
						$("#pinterest_pin").text(response.pinterest_pin);
						$("#buffer_share").text(response.buffer_share);
						$("#xing_share").text(response.xing_share);
						$("#linkedin_share").text(response.linkedin_share);


					}
					/******************* end of social network page **********************/


					/********************* visitor analysis page ******************/
					if (function_name == 'visitor_analysis') {
						$('#overview_from_date').text(response.from_date);
						$('#overview_to_date').text(response.to_date);
						//this domain name will be placed at all the pages of visitor analysis tab
				        $(".domain_name").text(response.domain_name);

						/*** daily visitor line chart ***/
				        var line = new Morris.Line({
				          element: 'line-chart',
				          resize: true,
				          data: response.line_chart,
				          xkey: 'date',
				          ykeys: ['user'],
				          labels: ['New User'],
				          lineColors: ['#3c8dbc'],
				          hideHover: 'auto'
				        });
				        /*********************/
				        $('#total_page_view').text(response.total_page_view);
				        $('#total_unique_visitor').text(response.total_unique_visitor);
				        $('#average_stay_time').text(response.average_stay_time);
				        $('#average_visit').text(response.average_visit);
					}
					/**************** end of visitor analysis page ****************/


					/***************** overview page ******************/
					if (function_name == 'overview') {
						$('#overview_from_date').text(response.from_date);
						$('#overview_to_date').text(response.to_date);
						// LINE CHART
				        var line = new Morris.Line({
				          element: 'line-chart',
				          resize: true,
				          data: response.line_chart,
				          xkey: 'date',
				          ykeys: ['user'],
				          labels: ['New User'],
				          lineColors: ['#3c8dbc'],
				          hideHover: 'auto'
				        });
				        $('#total_page_view').text(response.total_page_view);
				        $('#total_unique_visitor').text(response.total_unique_visitor);
				        $('#average_stay_time').text(response.average_stay_time);
				        $('#average_visit').text(response.average_visit);
					}
					/********************* end of overview page *****************/


					/******************** for traffic source page *******************/ 
					if(function_name=='traffic_source') {
						
						$('#traffic_source_from_date').text(response.from_date);
						$('#traffic_source_to_date').text(response.to_date);
						/*** daily traffic line chart ***/
						var area = new Morris.Area({
						    element: 'traffic_line-chart',
						    resize: true,
						    data: response.line_chart_data,
						    xkey: 'date',
						    ykeys: ['direct_link', 'search_link', 'social_link', 'referrer_link'],
						    labels: ['Direct Link', 'Search Engine', 'Social Network', 'Referal'],
						    lineColors: ['#74828F', '#96C0CE', '#BEB9B5', '#C25B56' ],
						    hideHover: 'auto'
						  });
						/****************************/

						/*** Top referrer pie chart ***/
						var donut = new Morris.Donut({
						    element: 'top_referrer_chart',
						    resize: true,
						    colors: response.top_referrer_color,
						    data: response.top_referrer_data,
						    hideHover: 'auto'
						  });
						/******************************/


						/*** Total traffic bar chart ***/
				        //BAR CHART
				        var bar = new Morris.Bar({
				          element: 'bar-chart',
				          resize: true,
				          data: response.bar_chart_data,
				          barColors: ['#F8DEBD'],
				          xkey: 'source_name',
				          ykeys: ['value'],
				          labels: ['Visitor'],
				          hideHover: 'auto'
				        });
						/******************************/


						/**** Traffic from search engines ***/
						var pieChartCanvas = $("#search_engine_traffic_pieChart").get(0).getContext("2d");
						var pieChart = new Chart(pieChartCanvas);
						var PieData = response.search_engine_info;

						pieChart.Doughnut(PieData, pieOptions);
						$("#search_engine_traffic_color_codes").html(response.search_engine_names);
						/*****************************************/

						/**** Traffic from social networks ***/
						var pieChartCanvas = $("#social_network_traffic_pieChart").get(0).getContext("2d");
						var pieChart = new Chart(pieChartCanvas);
						var PieData = response.social_network_info;
						pieChart.Doughnut(PieData, pieOptions);
						$("#social_network_traffic_color_codes").html(response.social_network_names);

					}
					
					/****************** end of traffic source page ***********************/



					/****************** for visitor type page ****************************/
					if(function_name == 'visitor_type'){
						$('#visitor_type_from_date').text(response.from_date);
						$('#visitor_type_to_date').text(response.to_date);
						
						//BAR CHART
				        var bar = new Morris.Bar({
				          element: 'visitor_type_bar-chart',
				          resize: true,
				          data: response.daily_new_vs_returning,
				          barColors: ['#74828F', '#96C0CE'],
				          xkey: 'date',
				          ykeys: ['new_user', 'returning_user'],
				          labels: ['New User', 'Returning User'],
				          hideHover: 'auto'
				        });

				        //-------------
					  //- PIE CHART -
					  //-------------
					  // Get context with jQuery - using jQuery's .get() method.
					  var pieChartCanvas = $("#visitor_type_pieChart").get(0).getContext("2d");
					  var pieChart = new Chart(pieChartCanvas);
					  var PieData = response.total_new_returning;
					  
					  // You can switch between pie and douhnut using the method below.  
					  pieChart.Doughnut(PieData, pieOptions);
					  //-----------------
					  //- END PIE CHART -
					  //-----------------
					}
					/****************** end of visitor type page *************************/

					/************************ content over view page********************************/
					if(function_name == 'content_overview'){
						$('#content_overview_from_date').text(response.from_date);
						$('#content_overview_to_date').text(response.to_date);
						$('#content_overview_data').html(response.progress_bar_data);
					}
					/************************ end content over view page********************************/

					/**************** country wise report page ********************/
					if(function_name == 'country_wise_report'){
						$("#country_wise_visitor_from_date").text(response.from_date);
						$("#country_wise_visitor_to_date").text(response.to_date);
						$("#country_wise_table_data").html(response.country_wise_table_data);
						
						function drawMap() {
							var data = google.visualization.arrayToDataTable(response.country_graph_data);

							var options = {};
							options['dataMode'] = 'regions';

							var container = document.getElementById('regions_div');
							var geomap = new google.visualization.GeoMap(container);

							geomap.draw(data, options);
						};

						google.charts.load('current', {'packages':['geomap']});
						google.charts.setOnLoadCallback(drawMap);
					}
					/**************** end country wise report page ********************/
					
				} //end of success

			}); // end of ajax
		} //end of function ajax_call

		$(document.body).on('click','#download_pdf',function(){
	        $("#download_div").hide();
	        $("#waiting_div").show();
	        $('#modal_for_download').modal();
	        var base_url="<?php echo site_url(); ?>";
	        var download_url =  base_url+'home/frontend_download_pdf';
	        var table_id = $(this).attr('table_id');
	        $.ajax({
	            url: download_url,
	            type: 'POST',
	            data: {table_id:table_id},
	            success:function(response){
	                var link = base_url+response;             
	                $("#ajax_download_div").attr('href',link);
	                $("#download_div").show();
	                $("#waiting_div").hide();    
	            }
	        });
	    });



	});
</script>


<!-- Modal for download -->
<div id="modal_for_download" class="modal fade">
    <div class="modal-dialog" style="width:65%;">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&#215;</span>
                </button>
                <h4 id="" class="modal-title"><i class="fa fa-spinner"></i> <?php echo $this->lang->line('Generating PDF, Please wait.'); ?></h4>
            </div>

            <div class="modal-body">
                <style>
                .download_div_body
                {
                    border:1px solid #ccc;  
                    margin: 0 auto;
                    text-align: center;
                    margin-top:10%;
                    padding-bottom: 20px;
                    background-color: #fffddd;
                    color:#000;
                }
                </style>
                <!-- <div class="container"> -->
                    <div class="row text-center" id="waiting_div"><img style="width: 350px;" src="<?php echo base_url('assets/pre-loader/full-screenshots.gif');?>" alt="Please wait.."></div>
                    <div class="row" id="download_div">
                        <div class="col-xs-12 col-sm-12 col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2">
                            <div class="download_div_body">
                            <h2><?php echo $this->lang->line('your file is ready to download'); ?></h2>
                            <?php 
                                echo '<i class="fa fa-2x fa-thumbs-o-up"style="color:black"></i><br><br>';
                                echo "<a id='ajax_download_div' href='' title='Download' class='btn btn-warning btn-lg' style='width:200px;'><i class='fa fa-cloud-download' style='color:white'></i>".$this->lang->line('download')."</a>";                         
                            ?>
                            </div>      
                            
                        </div>
                    </div>
                <!-- </div>  -->
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('close'); ?></button>
            </div>
        </div>
    </div>
</div>

