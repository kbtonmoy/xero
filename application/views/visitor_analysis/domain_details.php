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
	<div class="section-header">
		<h1><i class="fas fa-chart-line"></i> <?php echo $page_title; ?></h1>
		<div class="section-header-breadcrumb">
			<div class="breadcrumb-item"><a href="<?php echo base_url("menu_loader/analysis_tools"); ?>"><?php echo $this->lang->line("Analysis Tools"); ?></a></div>
			<div class="breadcrumb-item active"><a href="<?php echo base_url('visitor_analysis/index'); ?>"><?php echo $this->lang->line("Visitor Analytics"); ?></a></div>
			<div class="breadcrumb-item"><?php echo $this->lang->line('Report'); ?></div>
		</div>
	</div>

	<div class="section-body">
		<div class="card">

			<div class="card-header">
				<h4><i class="fas fa-globe"></i> <?php echo "Domain Name"; ?> - <span class='red domain_name'></span></h4>
			</div>
			
			<div class="card-body">
				<ul class="nav nav-tabs" id="myTab" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" id="traffic_source-tab" data-toggle="tab" href="#traffic_source" role="tab" aria-controls="traffic_source" aria-selected="true"><?php echo $this->lang->line('Traffic Source'); ?></a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="overview-tab" data-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="false"><?php echo $this->lang->line('Overview'); ?></a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="visitor_type-tab" data-toggle="tab" href="#visitor_type" role="tab" aria-controls="visitor_type" aria-selected="false"><?php echo $this->lang->line('Visitor Type'); ?></a>
					</li>
					<!-- <li class="nav-item">
						<a class="nav-link" id="content_overview-tab" data-toggle="tab" href="#content_overview" role="tab" aria-controls="content_overview" aria-selected="false"><?php echo $this->lang->line('Content Overview'); ?></a>
					</li> -->
					<li class="nav-item">
						<a class="nav-link" id="country_wise_report-tab" data-toggle="tab" href="#country_wise_report" role="tab" aria-controls="country_wise_report" aria-selected="false"><?php echo $this->lang->line('Country Wise Report'); ?></a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="browser_report-tab" data-toggle="tab" href="#browser_report" role="tab" aria-controls="browser_report" aria-selected="false"><?php echo $this->lang->line('Browser Report'); ?></a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="os_report-tab" data-toggle="tab" href="#os_report" role="tab" aria-controls="os_report" aria-selected="false"><?php echo $this->lang->line('OS Report'); ?></a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="device_report-tab" data-toggle="tab" href="#device_report" role="tab" aria-controls="device_report" aria-selected="false"><?php echo $this->lang->line('Device Report'); ?></a>
					</li>
				</ul>
				<div class="tab-content tab-bordered" id="myTab3Content">
					<div class="tab-pane fade show active" id="traffic_source" role="tabpanel" aria-labelledby="traffic_source-tab">
						<?php $this->load->view('visitor_analysis/traffic_source'); ?>
					</div>
					<div class="tab-pane fade" id="overview" role="tabpanel" aria-labelledby="overview-tab">
						<?php $this->load->view('visitor_analysis/overview'); ?>
					</div>
					<div class="tab-pane fade" id="visitor_type" role="tabpanel" aria-labelledby="visitor_type-tab">
						<?php $this->load->view('visitor_analysis/visitor_type'); ?>
					</div>
					<div class="tab-pane fade" id="content_overview" role="tabpanel" aria-labelledby="content_overview-tab">
						<?php $this->load->view('visitor_analysis/content_overview'); ?>	
					</div>
					<div class="tab-pane fade" id="country_wise_report" role="tabpanel" aria-labelledby="country_wise_report-tab">
						<?php $this->load->view('visitor_analysis/country_wise_report'); ?>
					</div>
					<div class="tab-pane fade" id="browser_report" role="tabpanel" aria-labelledby="browser_report-tab">
						<?php $this->load->view('visitor_analysis/browser_report'); ?>
					</div>
					<div class="tab-pane fade" id="os_report" role="tabpanel" aria-labelledby="os_report-tab">
						<?php $this->load->view('visitor_analysis/os_report'); ?>
					</div>
					<div class="tab-pane fade" id="device_report" role="tabpanel" aria-labelledby="device_report-tab">
						<?php $this->load->view('visitor_analysis/device_report'); ?>
					</div>
				</div>
			</div>

		</div>
	</div>
</section>

<script type="text/javascript">
	$('.reservation').daterangepicker();
	$(".reservation").val('');

	var function_name='traffic_source';
	var first_load = 1;

	$("document").ready(function(){
		$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
			e.preventDefault();
			first_load = 0;
			var target = $(e.target).attr("href");
			function_name = target.replace('#','');
			ajax_call(function_name);		

		}); // end of $('a[data-toggle="tab"]')


		$(document.body).on('click','.search_button',function(){
			ajax_call(function_name);			
		});

		if(function_name == 'traffic_source' && first_load == 1){
			ajax_call(function_name);
		}


		function ajax_call(function_name)
		{
			var domain_id = $("#domain_id").val();
			var date_range = $("#"+function_name+"_date").val();

			if(function_name == 'visitor_analysis')
				date_range = $("#overview_date").val();

			var base_url="<?php echo base_url(); ?>";
			var data_type = "JSON";
			if(function_name == 'alexa_info' || function_name == 'general' || function_name == 'similarweb_info')
				data_type = '';
			$('#'+function_name+'_success_msg').html('<img class="center-block" style="margin-top:10px;" src="'+base_url+'assets/pre-loader/Fancy pants.gif" alt="Searching...">');

			$.ajax({
				type: "POST",
				url : "<?php echo site_url('visitor_analysis/ajax_get_"+function_name+"_data'); ?>",
				data:{domain_id:domain_id,date_range:date_range},
				dataType: data_type,
				async: true,
				success:function(response){
					$('#'+function_name+'_success_msg').html('');
					$("#"+function_name+"_name").html(response);
					$(".domain_name").text(response.domain_name);


					/***************** overview page ******************/
					if (function_name == 'overview') {
						$('#overview_from_date').text(response.from_date);
						$('#overview_to_date').text(response.to_date);

				        var overview_data = document.getElementById("line-chart").getContext('2d');
				        var overview_data_chart = new Chart(overview_data, {
				          type: 'line',
				          data: {
				            labels: response.line_chart_dates,
				            datasets: [{
				              label: "<?php echo $this->lang->line('New User'); ?>",
				              data: response.line_chart_values,
				              borderWidth: 1,
				              borderColor: '#36a2eb',
				              backgroundColor: 'transparent',
				              pointBackgroundColor: '#fff',
				              pointBorderColor: '#36a2eb',
				              pointRadius: 2
				            }]
				          },
				          options: {
				            legend: {
				              display: false
				            },
				            scales: {
				              yAxes: [{
				                gridLines: {
				                  display: false,
				                  drawBorder: false,
				                },
				                ticks: {
				                  stepSize: response.step_count
				                }
				              }],
				              xAxes: [{
				                gridLines: {
				                  color: '#fbfbfb',
				                  lineWidth: 2
				                },
				                type: 'time',
				                   time: {

				                       displayFormats: {
				                           quarter: 'MMM YYYY'
				                       }
				                   },
				              }],

				            },
				          }
				        });

				        $('#total_page_view').text(response.total_page_view);
				        $('#total_unique_visitor').text(response.total_unique_visitor);
				        $('#average_stay_time').text(response.average_stay_time);
				        $('#average_visit').text(response.average_visit);
				        $("#bounce_rate").text(response.bounce_rate);
					}
					/********************* end of overview page *****************/


					/******************** for traffic source page *******************/ 
					if(function_name=='traffic_source') {
						
						$('#traffic_source_from_date').text(response.from_date);
						$('#traffic_source_to_date').text(response.to_date);
						/*** daily traffic line chart ***/

						var traffic_line_chart_data = document.getElementById("traffic_line-chart").getContext('2d');
						var traffic_line_chart_data_preview = new Chart(traffic_line_chart_data, {
						  type: 'line',
						  data: {
						    labels: response.traffic_line_chart_dates,
						    datasets: [{
						      label: '<?php echo $this->lang->line("Direct Link"); ?>',
						      data: response.traffic_direct_link,
						      borderColor: 'transparent',
						      backgroundColor: '#99CC99',
						      hidden: false
						    },
						    {
						      label: '<?php echo $this->lang->line("Search Engine"); ?>',
						      data: response.traffic_search_link,
						      borderColor: 'transparent',
						      backgroundColor: '#FFFFCC',
						      fill: '-1'
						    },{
						      label: '<?php echo $this->lang->line("Social Network"); ?>',
						      data: response.traffic_social_link,
						      borderColor: 'transparent',
						      backgroundColor: '#9999CC',
						      fill: '-1'
						    },{
						      label: '<?php echo $this->lang->line("Referal"); ?>',
						      data: response.traffic_referrer_link,
						      borderColor: 'transparent',
						      backgroundColor: '#CCCC99',
						      fill: '-1'

						    }]
						  },
						  options: {
						    legend: {
						      display: false
						    },
						    animation: {
						    	animateScale: true,
						    	animateRotate: true
						    },
						    maintainAspectRatio: false,
						    elements: {
						    	line: {
						    		tension: 0.5
						    	}
						    },
						    scales: {
						    	yAxes: [{
						    		stacked: true,
						    		gridLines: {
						    		  display: false,
						    		  drawBorder: false,
						    		},
						    		ticks: {
					                  beginAtZero: true,
					                  stepSize: response.traffic_daily_line_step_count
					                }

						    	}],
						    	xAxes: [{
						    	  gridLines: {
						    	    color: '#fbfbfb',
						    	    lineWidth: 1
						    	  },
						    	  type: 'time',
						    	     time: {
						    	     		
						    	         displayFormats: {
						    	             quarter: 'MMM YYYY'
						    	         }
						    	     },

						    	}]
						    },
						    plugins: {
						    	filler: {
						    		propagate: true
						    	},

						    }
						  }
						});

						/****************************/

				        var traffic_bar_chart_data = document.getElementById("traffic_bar_chart").getContext('2d');
				        var traffic_bar_chart_data_preview = new Chart(traffic_bar_chart_data, {
				            type: 'bar',
				            data: {
									labels: ['Visitor'],
									datasets: [{
										label: 'Direct Link',
										backgroundColor: '#FF9966',
										borderColor: '#FF9966',
										borderWidth: 1,
										data: [response.traffic_bar_direct_link_count]
									}, {
										label: 'Search Engine',
										backgroundColor: '#FFCC99',
										borderColor: '#FFCC99',
										borderWidth: 1,
										data: [response.traffic_bar_search_link_count]
									},{
										label: 'Social Network',
										backgroundColor: '#3399FF',
										borderColor: '#3399FF',
										borderWidth: 1,
										data: [response.traffic_bar_social_link_count]
									},{
										label: 'Referal',
										backgroundColor: '#003366',
										borderColor: '#003366',
										borderWidth: 1,
										data: [response.traffic_bar_referrer_link_count]
									}]

								},
				            options: {
								responsive: true,
								legend: {
									position: 'top',
								},
								title: {
									display: false,
									text: 'Chart.js Bar Chart'
								},
								scales: {
						            yAxes: [{
						                ticks: {
						                  beginAtZero: true,
						                  stepSize: response.traffic_bar_step_count
						                }
						            }]
						        }
							}
				        });
						/******************************/

						var top_referrer_chart_data = document.getElementById("top_referrer_chart").getContext('2d');
						var top_referrer_chart_data_preview = new Chart.PolarArea(top_referrer_chart_data, {
						  data: {
			  				datasets: [{
			  					data: response.top_referrer_present_value,
			  					backgroundColor: [
			  						'red',
			  						'orange',
			  						'yellow',
			  						'green',
			  						'purple',
			  					],
			  					label: 'My dataset' // for legend
			  				}],
			  				labels: response.top_referrer_present_label
				  			},
				  			options: {
				  				responsive: true,
				  				legend: {
				  					position: 'right',
				  				},
				  				title: {
				  					display: true,
				  					text: '<?php echo $this->lang->line("Top five referrers in percentage"); ?>'
				  				},
				  				scale: {
				  					ticks: {
				  						beginAtZero: true
				  					},
				  					reverse: false
				  				},
				  				animation: {
				  					animateRotate: false,
				  					animateScale: true
				  				}
				  			}
						  
						});


						var search_enginge_traffic_data = document.getElementById("search_enginge_traffic").getContext('2d');
						var search_enginge_traffic_data_preview = new Chart(search_enginge_traffic_data, {
							type: 'doughnut',
							data: {
								datasets: [{
									data: response.search_engine_values,
									backgroundColor: response.search_engine_colors,
									label: 'Dataset 1'
								}],
								labels: response.search_engine_labels
							},
							options: {
								responsive: true,
								legend: {
									position: 'bottom',
								},
								title: {
									display: true,
									text: '<?php echo $this->lang->line("Visitors from different search engine"); ?>'
								},
								animation: {
									animateScale: true,
									animateRotate: true
								}
							}
						});

						/*****************************************/
						var social_network_traffic_data = document.getElementById("social_network_traffic").getContext('2d');
						var social_network_traffic_data_preview = new Chart(social_network_traffic_data, {
							type: 'doughnut',
							data: {
								datasets: [{
									data: response.social_network_values,
									backgroundColor: response.social_network_colors,
									label: 'Dataset 1'
								}],
								labels: response.social_network_labels
							},
							options: {
								responsive: true,
								legend: {
									position: 'bottom',
								},
								title: {
									display: true,
									text: '<?php echo $this->lang->line("Visitors from different social networks"); ?>'
								},
								animation: {
									animateScale: true,
									animateRotate: true
								},
								rotation: 1 * Math.PI,
						 		circumference: 1 * Math.PI
							}
						});

					}
					
					/****************** end of traffic source page ***********************/



					/****************** for visitor type page ****************************/
					if(function_name == 'visitor_type'){
						$('#visitor_type_from_date').text(response.from_date);
						$('#content_overview_from_date').text(response.from_date);
						$('#visitor_type_to_date').text(response.to_date);
						$('#content_overview_to_date').text(response.to_date);
						$("#content_overview_data").html(response.progress_bar_data);
						
				        var visitor_type_bar_chart_data = document.getElementById("visitor_type_bar_chart").getContext('2d');
				        var visitor_type_bar_chart_data_preview = new Chart(visitor_type_bar_chart_data, {
				            type: 'bar',
				            data: {
									labels: response.new_vs_returning_dates,
									datasets: [{
										label: "<?php echo $this->lang->line('New User'); ?>",
										backgroundColor: '#2F4E6F',
										borderColor: '#2F4E6F',
										borderWidth: 1,
										data: response.new_vs_returning_new_user
									}, {
										label: "<?php echo $this->lang->line('Returning User'); ?>",
										backgroundColor: '#E15119',
										borderColor: '#E15119',
										borderWidth: 1,
										data: response.new_vs_returning_returning_user
									}]

								},
				            options: {
								responsive: true,
								legend: {
									position: 'top',
								},
								title: {
									display: false,
									text: 'Chart.js Bar Chart'
								},
								scales: {
						            yAxes: [{
						                ticks: {
						                  beginAtZero: true,
						                  stepSize: response.new_vs_returning_step_count
						                }
						            }]
						        }
							}
				        });

				        var visitor_type_pieChart_data = document.getElementById("visitor_type_pieChart").getContext('2d');
						var visitor_type_pieChart_data_preview = new Chart.PolarArea(visitor_type_pieChart_data, {
						  data: {
			  				datasets: [{
			  					data: response.total_new_returning_values,
			  					backgroundColor: [
			  						'#C8EBE5',
			  						'#F5A196',
			  					],
			  					label: 'My dataset' // for legend
			  				}],
			  				labels: response.total_new_returning_labels
				  			},
				  			options: {
				  				responsive: true,
				  				legend: {
				  					position: 'right',
				  				},
				  				title: {
				  					display: false,
				  					text: '<?php echo $this->lang->line("Top five referrers in percentage"); ?>'
				  				},
				  				scale: {
				  					ticks: {
				  						beginAtZero: true
				  					},
				  					reverse: false
				  				},
				  				animation: {
				  					animateRotate: false,
				  					animateScale: true
				  				}
				  			}
						  
						});

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
						
						google.charts.load('current', {
						      'packages':['geochart']
						     
						    });
						google.charts.setOnLoadCallback(drawRegionsMap);

						function drawRegionsMap() {
						   var data = google.visualization.arrayToDataTable(response.country_graph_data);
						   var options = {};
						   var chart = new google.visualization.GeoChart(document.getElementById('regions_div'));
						   chart.draw(data, options);
						}
					}
					/**************** end country wise report page ********************/

					if(function_name == 'browser_report'){
						$("#browser_table_from_date").text(response.from_date);
						$("#browser_table_to_date").text(response.to_date);
						$("#browser_report_name").html(response.browser_report_name);
					}

					if(function_name == 'os_report'){
						$("#os_table_from_date").text(response.from_date);
						$("#os_table_to_date").text(response.to_date);
						$("#os_report_name").html(response.os_report_name);
					}

					if(function_name == 'device_report'){
						$("#device_table_from_date").text(response.from_date);
						$("#device_table_to_date").text(response.to_date);
						$("#device_report_name").html(response.device_report_name);
					}
					
				} //end of success

			}); // end of ajax
		} //end of function ajax_call



		$(document.body).on('click','.browser_name',function(){
			var domain_id = $("#domain_id").val();
			var browser_name = $(this).attr("data");
			var date_range = $("#browser_report_date").val();
			var base_url="<?php echo base_url(); ?>";
			$("#individual_browser_data_table").html('');
			$("#id_for_browser_name").text(browser_name);
			$("#modal_for_browser_report").modal();
			$('#modal_waiting_browser_name').html('<div class="text-center waiting"><i class="fas fa-spinner fa-spin blue text-center" style="font-size: 40px;"></i></div>');

			$.ajax({
				type: "POST",
				url : "<?php echo site_url('visitor_analysis/ajax_get_individual_browser_data'); ?>",
				data:{domain_id:domain_id,date_range:date_range,browser_name:browser_name},
				dataType: 'JSON',
				async: false,
				success:function(response){
					$("#modal_waiting_browser_name").html('');
					$("#browser_name_from_date").text(response.from_date);
					$("#browser_name_to_date").text(response.to_date);
					$("#individual_browser_data_table").html(response.browser_version);
					
					var browser_name_line_chart_data = document.getElementById("browser_name_line_chart").getContext('2d');
					var browser_name_line_chart_data_preview = new Chart(browser_name_line_chart_data, {
					  type: 'line',
					  data: {
					    labels: response.browser_daily_session_dates,
					    datasets: [{
					      label: "<?php echo $this->lang->line('Sessions'); ?>",
					      data: response.browser_daily_session_values,
					      borderWidth: 1,
					      borderColor: 'red',
					      borderDash: [5, 5],
					      backgroundColor: 'transparent',
					      pointBackgroundColor: '#fff',
					      pointBorderColor: 'red',
					      pointRadius: 2
					    }]
					  },
					  options: {
					    legend: {
					      display: false
					    },
					    scales: {
					      yAxes: [{
					        gridLines: {
					          display: false,
					          drawBorder: false,
					        },
					        ticks: {
					          stepSize: response.browser_daily_session_steps
					        }
					      }],
					      xAxes: [{
					        gridLines: {
					          color: '#fbfbfb',
					          lineWidth: 2
					        },
					        type: 'time',
					           time: {

					               displayFormats: {
					                   quarter: 'MMM YYYY'
					               }
					           },
					      }],

					    },
					  }
					});

				} //end of success
			});
		}); // end of browser name click function



		$(document.body).on('click','.os_name',function(){
			var domain_id = $("#domain_id").val();
			var os_name = $(this).attr("data");
			var date_range = $("#os_report_date").val();
			var base_url="<?php echo base_url(); ?>";
			$("#id_for_os_name").text(os_name);
			$("#modal_for_os_report").modal();
			$('#modal_waiting_os_name').html('<div class="text-center waiting"><i class="fas fa-spinner fa-spin blue text-center" style="font-size: 40px;"></i></div>');

			$.ajax({
				type: "POST",
				url : "<?php echo site_url('visitor_analysis/ajax_get_individual_os_data'); ?>",
				data:{domain_id:domain_id,date_range:date_range,os_name:os_name},
				dataType: 'JSON',
				async: false,
				success:function(response){
					$("#modal_waiting_os_name").html('');
					$("#os_name_from_date").text(response.from_date);
					$("#os_name_to_date").text(response.to_date);
					
					var os_name_line_chart_data = document.getElementById("os_name_line_chart").getContext('2d');
					var os_name_line_chart_data_preview = new Chart(os_name_line_chart_data, {
					  type: 'line',
					  data: {
					    labels: response.os_daily_session_dates,
					    datasets: [{
					      label: "<?php echo $this->lang->line('Sessions'); ?>",
					      data: response.os_daily_session_values,
					      borderWidth: 1,
					      borderColor: '#00AAA0',
					      borderDash: [5, 5],
					      backgroundColor: 'transparent',
					      pointBackgroundColor: '#fff',
					      pointBorderColor: '#00AAA0',
					      pointRadius: 2
					    }]
					  },
					  options: {
					    legend: {
					      display: false
					    },
					    scales: {
					      yAxes: [{
					        gridLines: {
					          display: false,
					          drawBorder: false,
					        },
					        ticks: {
					          stepSize: response.os_daily_session_steps
					        }
					      }],
					      xAxes: [{
					        gridLines: {
					          color: '#fbfbfb',
					          lineWidth: 2
					        },
					        type: 'time',
					           time: {

					               displayFormats: {
					                   quarter: 'MMM YYYY'
					               }
					           },
					      }],

					    },
					  }
					});					
				} //end of success
			});
		}); //end of os name click function



		$(document.body).on('click','.device_name',function(){
			var domain_id = $("#domain_id").val();
			var device_name = $(this).attr("data");
			var date_range = $("#device_report_date").val();
			var base_url="<?php echo base_url(); ?>";
			$("#id_for_device_name").text(device_name);
			$("#modal_for_device_report").modal();
			$('#modal_waiting_device_name').html('<div class="text-center waiting"><i class="fas fa-spinner fa-spin blue text-center" style="font-size: 40px;"></i></div>');

			$.ajax({
				type: "POST",
				url : "<?php echo site_url('visitor_analysis/ajax_get_individual_device_data'); ?>",
				data:{domain_id:domain_id,date_range:date_range,device_name:device_name},
				dataType: 'JSON',
				async: false,
				success:function(response){
					$("#modal_waiting_device_name").html('');
					$("#device_name_from_date").text(response.from_date);
					$("#device_name_to_date").text(response.to_date);
					
					var device_name_line_chart_data = document.getElementById("device_name_line_chart").getContext('2d');
					var device_name_line_chart_data_preview = new Chart(device_name_line_chart_data, {
					  type: 'line',
					  data: {
					    labels: response.device_daily_session_dates,
					    datasets: [{
					      label: "<?php echo $this->lang->line('Sessions'); ?>",
					      data: response.device_daily_session_values,
					      borderWidth: 1,
					      borderColor: '#FF7A5A',
					      borderDash: [5, 5],
					      backgroundColor: 'transparent',
					      pointBackgroundColor: '#fff',
					      pointBorderColor: '#FF7A5A',
					      pointRadius: 2
					    }]
					  },
					  options: {
					    legend: {
					      display: false
					    },
					    scales: {
					      yAxes: [{
					        gridLines: {
					          display: false,
					          drawBorder: false,
					        },
					        ticks: {
					          stepSize: response.device_daily_session_steps
					        }
					      }],
					      xAxes: [{
					        gridLines: {
					          color: '#fbfbfb',
					          lineWidth: 2
					        },
					        type: 'time',
					           time: {

					               displayFormats: {
					                   quarter: 'MMM YYYY'
					               }
					           },
					      }],

					    },
					  }
					});						
				} //end of success
			});
		}); //end of device name click function


		$(document.body).on('click','.country_wise_name',function(){
			var domain_id = $("#domain_id").val();
			var country_name = $(this).attr("data");
			var date_range = $("#country_wise_report_date").val();
			var base_url="<?php echo base_url(); ?>";
			$("#individual_country_data_table").html('');
			$("#id_for_country_name").text(country_name);
			$("#modal_for_country_report").modal();
			$('#modal_waiting_country_name').html('<div class="text-center waiting"><i class="fas fa-spinner fa-spin blue text-center" style="font-size: 40px;"></i></div>');

			$.ajax({
				type: "POST",
				url : "<?php echo site_url('visitor_analysis/ajax_get_individual_country_data'); ?>",
				data:{domain_id:domain_id,date_range:date_range,country_name:country_name},
				dataType: 'JSON',
				async: false,
				success:function(response){
					$("#modal_waiting_country_name").html('');
					$("#country_name_from_date").text(response.from_date);
					$("#country_name_to_date").text(response.to_date);
					$("#individual_country_data_table").html(response.country_city_str);
					
					var country_name_line_chart_data = document.getElementById("country_name_line_chart").getContext('2d');
					var country_name_line_chart_data_preview = new Chart(country_name_line_chart_data, {
					  type: 'line',
					  data: {
					    labels: response.country_daily_session_dates,
					    datasets: [{
					      label: "<?php echo $this->lang->line('Sessions'); ?>",
					      data: response.country_daily_session_values,
					      borderWidth: 1,
					      borderColor: 'red',
					      backgroundColor: 'transparent',
					      pointBackgroundColor: '#fff',
					      pointBorderColor: 'red',
					      pointRadius: 2
					    }]
					  },
					  options: {
					    legend: {
					      display: false
					    },
					    scales: {
					      yAxes: [{
					        gridLines: {
					          display: false,
					          drawBorder: false,
					        },
					        ticks: {
					          stepSize: response.country_daily_session_steps
					        }
					      }],
					      xAxes: [{
					        gridLines: {
					          color: '#fbfbfb',
					          lineWidth: 2
					        },
					        type: 'time',
					           time: {

					               displayFormats: {
					                   quarter: 'MMM YYYY'
					               }
					           },
					      }],

					    },
					  }
					});

				} //end of success
			});
		}); // end of browser name click function


	});
</script>


<!-- country wise individual data -->
<div id="modal_for_country_report" class="modal fade" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<div class="modal-header">
				<h5 id="new_search_details_title" class="modal-title"><i class="fa fa-binoculars"></i> <?php echo $this->lang->line('Details Information About'); ?> <span id="id_for_country_name"></span></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&#215;</span>
				</button>
			</div>

			<div class="modal-body">
				<div class="row"><div class="text-center" id="modal_waiting_country_name"></div></div>

				<div class="row">
					<div class="col-12">
					  <div class="card">
					    <div class="card-header">
					      <h4><i class="far fa-chart-bar"></i> <?php echo $this->lang->line('Day Wise Sessions Report From'); ?> <span id="country_name_from_date"></span> to <span id="country_name_to_date"></span></h4>
					    </div>
					    <div class="card-body">
					      <canvas id="country_name_line_chart" height="134"></canvas>
					    </div>
					  </div>
					</div>
				</div>

				<div class="row">
					<div class="col-12">						
						<div class="table-responsive" id="individual_country_data_table">
							
						</div>
					</div>
				</div>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- end of country wise individual data -->

<!-- individual browser report -->
<div id="modal_for_browser_report" class="modal fade" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<div class="modal-header">
				<h5 id="new_search_details_title" class="modal-title"><i class="fa fa-binoculars"></i> <?php echo $this->lang->line('Details Information About'); ?> <span id="id_for_browser_name"></span></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&#215;</span>
				</button>
			</div>

			<div class="modal-body" id="browser_details_body">
				<div class="row"><div class="text-center" id="modal_waiting_browser_name"></div></div>
				
				<div class="row">
					<div class="col-12">
					  <div class="card">
					    <div class="card-header">
					      <h4><i class="far fa-chart-bar"></i> <?php echo $this->lang->line('Day Wise Sessions Report From'); ?> <span id="browser_name_from_date"></span> to <span id="browser_name_to_date"></span></h4>
					    </div>
					    <div class="card-body">
					      <canvas id="browser_name_line_chart" height="134"></canvas>
					    </div>
					  </div>
					</div>
				</div>

				<div class="row">
					<div class="col-12">						
						<div class="table-responsive" id="individual_browser_data_table">
							
						</div>
					</div>
				</div>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- end of individual browser report -->

<!-- individual os data -->
<div id="modal_for_os_report" class="modal fade" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<div class="modal-header">
				<h5 id="new_search_details_title" class="modal-title"><i class="fa fa-binoculars"></i> <?php echo $this->lang->line('Details Information About'); ?> <span id="id_for_os_name"></span></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&#215;</span>
				</button>
			</div>

			<div class="modal-body">
				<div class="row"><div class="text-center" id="modal_waiting_os_name"></div></div>

				<div class="row">
					<div class="col-12">
					  <div class="card">
					    <div class="card-header">
					      <h4><i class="far fa-chart-bar"></i> <?php echo $this->lang->line('Day Wise Sessions Report From'); ?> <span id="os_name_from_date"></span> to <span id="os_name_to_date"></span></h4>
					    </div>
					    <div class="card-body">
					      <canvas id="os_name_line_chart" height="134"></canvas>
					    </div>
					  </div>
					</div>
				</div>

			</div>


			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- end of individual os data -->

<!-- individual device report -->
<div id="modal_for_device_report" class="modal fade" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<div class="modal-header">
				<h5 id="new_search_details_title" class="modal-title"><i class="fa fa-binoculars"></i> <?php echo $this->lang->line('Details Information About'); ?> <span id="id_for_device_name"></span></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&#215;</span>
				</button>
			</div>

			<div class="modal-body">
				<div class="row"><div class="text-center" id="modal_waiting_device_name"></div></div>
				<div class="row">
					<div class="col-12">
					  <div class="card">
					    <div class="card-header">
					      <h4><i class="far fa-chart-bar"></i> <?php echo $this->lang->line('Day Wise Sessions Report From'); ?> <span id="device_name_from_date"></span> to <span id="device_name_to_date"></span></h4>
					    </div>
					    <div class="card-body">
					      <canvas id="device_name_line_chart" height="134"></canvas>
					    </div>
					  </div>
					</div>
				</div>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- end of individual device report -->
