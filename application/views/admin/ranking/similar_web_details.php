<?php 
$similar_web_data=array();
$similar_web_data["domain_name"]="";
$similar_web_data["global_rank"]="";
$similar_web_data["country_rank"]="";
$similar_web_data["country"]="";
$similar_web_data["category_rank"]="";
$similar_web_data["category"]="";
$similar_web_data["total_visit"]="";
$similar_web_data["time_on_site"]="";
$similar_web_data["page_views"]="";
$similar_web_data["bounce_rate"]="";
$similar_web_data["traffic_country"]=array();
$similar_web_data["traffic_country_percentage"]=array();
$similar_web_data["direct_traffic"]="";
$similar_web_data["referral_traffic"]="";
$similar_web_data["search_traffic"]="";
$similar_web_data["social_traffic"]="";
$similar_web_data["mail_traffic"]="";
$similar_web_data["display_traffic"]="";
$similar_web_data["top_referral_site"]=array();
$similar_web_data["top_destination_site"]=array();
$similar_web_data["organic_search_percentage"]="";
$similar_web_data["paid_search_percentage"]="";
$similar_web_data["top_organic_keyword"]=array();
$similar_web_data["top_paid_keyword"]=array();
$similar_web_data["social_site_name"]=array();
$similar_web_data["social_site_percentage"]=array();

if(array_key_exists(0,$similar_web))
$similar_web_data=$similar_web[0];


$domain =$similar_web_data["domain_name"];
$global_rank =$similar_web_data["global_rank"];
$country_rank =$similar_web_data["country_rank"];
$country =$similar_web_data["country"];
$category_rank=$similar_web_data["category_rank"];
$category=$similar_web_data["category"];
$total_visit=$similar_web_data["total_visit"];
$time_on_site=$similar_web_data["time_on_site"];
$page_views=$similar_web_data["page_views"];
$bounce=$similar_web_data["bounce_rate"];

$traffic_country=json_decode($similar_web_data["traffic_country"],true);
$traffic_country_percentage=json_decode($similar_web_data["traffic_country_percentage"],true);

$direct_traffic=$similar_web_data["direct_traffic"];
$referral_traffic=$similar_web_data["referral_traffic"];
$search_traffic=$similar_web_data["search_traffic"];
$social_traffic=$similar_web_data["social_traffic"];
$mail_traffic=$similar_web_data["mail_traffic"];
$display_traffic=$similar_web_data["display_traffic"];

if($direct_traffic=="0" 	|| $direct_traffic=="") 	$direct_traffic="0%";
if($referral_traffic=="0" 	|| $referral_traffic=="") 	$referral_traffic="0%";
if($search_traffic=="0" 	|| $search_traffic=="") 	$search_traffic="0%";
if($social_traffic=="0" 	|| $social_traffic=="") 	$social_traffic="0%";
if($mail_traffic=="0" 		|| $mail_traffic=="") 		$mail_traffic="0%";
if($display_traffic=="0" 	|| $display_traffic=="") 	$display_traffic="0%";

$top_referral_site=json_decode($similar_web_data["top_referral_site"],true);
$top_destination_site=json_decode($similar_web_data["top_destination_site"],true);

$organic_search_percentage=$similar_web_data["organic_search_percentage"];
$paid_search_percentage=$similar_web_data["paid_search_percentage"];

$top_organic_keyword=json_decode($similar_web_data["top_organic_keyword"],true);
$top_paid_keyword=json_decode($similar_web_data["top_paid_keyword"],true);

$social_site_name=json_decode($similar_web_data["social_site_name"],true);
$social_site_percentage=json_decode($similar_web_data["social_site_percentage"],true);
         


?>

<style>
	.info-box-icon{
		background: #fff;
		border-right: 1px solid #eee;
	}

	.info-box .progress {
	    background: #fff;
	    
	}
</style>
<div class="well well_border_left">
	<h4 class="text-center"> <i class="fa fa-globe"></i> <?php echo "SimilarWeb Data- ".$domain; ?></h4>
</div>
<div class="container-fluid">



	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
			<div style="border-bottom:2px solid #fff;" class="info-box">
				<span class="info-box-icon"><i class="fa fa-tag blue"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Category Rank</span>
					<span  class="info-box-number"><?php echo $category; ?> # <?php echo $category_rank; ?></span>
				</div><!-- /.info-box-content -->
			</div>
		</div>		

		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
			<div style="border-bottom:2px solid #fff;" class="info-box">
				<span class="info-box-icon"><i class="fa fa-globe green"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Global Rank</span>
					<span  class="info-box-number"># <?php echo $global_rank; ?></span>
				</div><!-- /.info-box-content -->
			</div>
		</div>	

		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
			<div style="border-bottom:2px solid #fff;" class="info-box">
				<span class="info-box-icon"><i class="fa fa-star orange"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Top Country Rank</span>
					<span  class="info-box-number"><?php echo $country; ?> # <?php echo $country_rank; ?></span>
				</div><!-- /.info-box-content -->
			</div>
		</div>			
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
			<div class="info-box">
				<span class="info-box-icon"><i class="fa fa-chrome blue"></i></span>
				<div class="info-box-content">
					<!-- <span class="info-box-text">Inventory</span> -->
					<span class="info-box-number"><?php echo $total_visit; ?></span>
					<div class="progress">
						<div style="width: 70%" class="progress-bar"></div>
					</div>
					<span class="progress-description">
						Total Visit
					</span>
				</div><!-- /.info-box-content -->
			</div>
		</div>

		<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
			<div class="info-box">
				<span class="info-box-icon"><i class="fa fa-clock-o green"></i></span>
				<div class="info-box-content">
					<!-- <span class="info-box-text">Inventory</span> -->
					<span class="info-box-number"><?php echo $time_on_site; ?></span>
					<div class="progress">
						<div style="width: 70%" class="progress-bar"></div>
					</div>
					<span class="progress-description">
						Time on Site
					</span>
				</div><!-- /.info-box-content -->
			</div>
		</div>

		<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
			<div class="info-box">
				<span class="info-box-icon"><i class="fa fa-newspaper-o blue2"></i></span>
				<div class="info-box-content">
					<!-- <span class="info-box-text">Inventory</span> -->
					<span class="info-box-number"><?php echo $page_views; ?></span>
					<div class="progress">
						<div style="width: 70%" class="progress-bar"></div>
					</div>
					<span class="progress-description">
						Page Views
					</span>
				</div><!-- /.info-box-content -->
			</div>
		</div>

		<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
			<div class="info-box">
				<span class="info-box-icon"><i class="fa fa-sign-out orange"></i></span>
				<div class="info-box-content">
					<!-- <span class="info-box-text">Inventory</span> -->
					<span class="info-box-number"><?php echo $bounce; ?></span>
					<div class="progress">
						<div style="width: 70%" class="progress-bar"></div>
					</div>
					<span class="progress-description">
						Bounce Rate
					</span>
				</div><!-- /.info-box-content -->
			</div>
		</div>
	</div>


	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 style="color: blue; word-spacing: 5px;" class="box-title"><i class="fa fa-globe"></i> Traffic by Countries</h3>
					<div class="box-tools pull-right">
						<button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
						<button data-widget="remove" class="btn btn-box-tool"><i class="fa fa-times"></i></button>
					</div>
				</div>
				<div class="box-body chart-responsive table-responsive">
					<table class="table table-condensed">
						<thead>
							<tr>
								<th><h4>SL</h4></th>
								<th><h4>Country</h4></th>
								<th><h4>Traffic %</h4></th>
							</tr>
						</thead>
						<tbody>
							<?php 
								$sl=0;                  
					            if(is_array($traffic_country) && is_array($traffic_country_percentage))
					            {
					                foreach($traffic_country as $key=>$val)
					                {                  
					                    $sl++;
					                    if(array_key_exists($key, $traffic_country) && array_key_exists($key, $traffic_country_percentage))
					                    {
					                    	echo "<tr><td>".$sl."</td>";
						                    echo "<td>".$traffic_country[$key]."</td>";
						                    echo "<td>".$traffic_country_percentage[$key]."</td>";
						                }
					                }
					                if(count($traffic_country)==0 || count($traffic_country_percentage)==0  )
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
			</div> <!-- end box -->			
		</div>
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 style="color: blue; word-spacing: 5px;" class="box-title"><i class="fa fa-share-alt"></i> Social Media Traffic</h3>
					<div class="box-tools pull-right">
						<button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
						<button data-widget="remove" class="btn btn-box-tool"><i class="fa fa-times"></i></button>
					</div>
				</div>
				<div class="box-body chart-responsive">
					<?php                  
			            if(is_array($social_site_name) && is_array($social_site_percentage))
			            {
			                foreach($social_site_name as $key=>$val)
			                {   
			                    if(array_key_exists($key, $social_site_name) && array_key_exists($key, $social_site_percentage))
			                    {
			      	             	echo $social_site_name[$key]." <span class='pull-right'><b>".$social_site_percentage[$key]."</b></span>";
			                    	echo 
			                    	'<div class="progress">					                    	
									  <div class="progress-bar progress-bar-striped " role="progressbar" aria-valuenow="'.$social_site_percentage[$key].'" aria-valuemin="0" aria-valuemax="100" style="width:'.$social_site_percentage[$key].'">
									  </div>
									</div>';
				                }
				                if(count($social_site_name)==0 || count($social_site_percentage)==0 )
			                	echo "No data found!";
			                }
			            }
			            else
			            {
			            	echo "No data found!";
			            }
					?>
				</div>
			</div> <!-- end box -->			
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
			<div style="border-bottom:2px solid #fff;" class="info-box">
				<span class="info-box-icon"><i class="fa fa-arrow-right blue2"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Direct Traffic</span>
					<span  class="info-box-number"><?php echo $direct_traffic; ?></span>
				</div><!-- /.info-box-content -->
			</div>
		</div>	
		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
			<div style="border-bottom:2px solid #fff;" class="info-box">
				<span class="info-box-icon"><i class="fa fa-arrow-down red"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Referral Traffic</span>
					<span  class="info-box-number"><?php echo $referral_traffic; ?></span>
				</div><!-- /.info-box-content -->
			</div>
		</div>
		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
			<div style="border-bottom:2px solid #fff;" class="info-box">
				<span class="info-box-icon"><i class="fa fa-search green"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Search Traffic</span>
					<span  class="info-box-number"><?php echo $search_traffic; ?></span>
				</div><!-- /.info-box-content -->
			</div>
		</div>	
		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
			<div style="border-bottom:2px solid #fff;" class="info-box">
				<span class="info-box-icon"><i class="fa fa-share-alt blue"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Social Traffic</span>
					<span  class="info-box-number"><?php echo $social_traffic; ?></span>
				</div><!-- /.info-box-content -->
			</div>
		</div>	
		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
			<div style="border-bottom:2px solid #fff;" class="info-box">
				<span class="info-box-icon"><i class="fa fa-envelope orange"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Mail Traffic</span>
					<span  class="info-box-number"><?php echo $mail_traffic; ?></span>
				</div><!-- /.info-box-content -->
			</div>
		</div>	
		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
			<div style="border-bottom:2px solid #fff;" class="info-box">
				<span class="info-box-icon"><i class="fa fa-laptop blue2"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Display Traffic</span>
					<span  class="info-box-number"><?php echo $display_traffic; ?></span>
				</div><!-- /.info-box-content -->
			</div>
		</div>	
	

	</div>



	


	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 style="color: blue; word-spacing: 5px;" class="box-title"><i class="fa fa-arrow-down"></i> Top Referral Sites</h3>
					<div class="box-tools pull-right">
						<button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
						<button data-widget="remove" class="btn btn-box-tool"><i class="fa fa-times"></i></button>
					</div>
				</div>
				<div class="box-body chart-responsive table-responsive">
					<table class="table table-condensed">
						<thead>
							<tr>
								<th><h4>SL</h4></th>
								<th><h4>Top Referral Site</h4></th>
							</tr>
						</thead>
						<tbody>
							<?php 
								$sl=0;                  
					            if(is_array($top_referral_site) && count($top_referral_site)>0)
					            {
					                foreach($top_referral_site as $key=>$val)
					                {                  
					                    $sl++;
					                    echo "<tr><td>".$sl."</td>";
						                echo "<td>".$top_referral_site[$key]."</td>";					                
					                }
					            }
					            else
					            {
					            	echo "<tr><td colspan='2'>No data found!</td></tr>";
					            }

							?>
						</tbody>
					</table>
				</div>
			</div> <!-- end box -->	
		</div>
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">	
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 style="color: blue; word-spacing: 5px;" class="box-title"><i class="fa fa-arrow-up"></i> Top Destination Sites</h3>
					<div class="box-tools pull-right">
						<button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
						<button data-widget="remove" class="btn btn-box-tool"><i class="fa fa-times"></i></button>
					</div>
				</div>
				<div class="box-body chart-responsive table-responsive">
					<table class="table table-condensed">
						<thead>
							<tr>
								<th><h4>SL</h4></th>
								<th><h4>Top Destination Site</h4></th>
							</tr>
						</thead>
						<tbody>
							<?php 
								$sl=0;                  
					            if(is_array($top_destination_site) && count($top_destination_site)>0)
					            {
					                foreach($top_destination_site as $key=>$val)
					                {                  
					                    $sl++;
					                    echo "<tr><td>".$sl."</td>";
						                echo "<td>".$top_destination_site[$key]."</td>";					                
					                }
					            }
					            else
					            {
					            	echo "<tr><td colspan='2'>No data found!</td></tr>";
					            }

							?>
						</tbody>
					</table>
				</div>
			</div> <!-- end box -->		
		</div>
	</div>


	<div class="row">
		<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
			<div class="info-box">
				<span class="info-box-icon"><i class="fa fa-search blue2"></i></span>
				<div class="info-box-content">
					<!-- <span class="info-box-text">Inventory</span> -->
					<span class="info-box-number"><?php echo $organic_search_percentage; ?></span>
					<div class="progress">
						<div style="width: 70%" class="progress-bar"></div>
					</div>
					<span class="progress-description">
						<b>Organic Search</b>
					</span>
				</div><!-- /.info-box-content -->
			</div>
		</div>

		<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
			<div class="info-box">
				<span class="info-box-icon"><i class="fa fa-usd orange"></i></span>
				<div class="info-box-content">
					<!-- <span class="info-box-text">Inventory</span> -->
					<span class="info-box-number"><?php echo $paid_search_percentage; ?></span>
					<div class="progress">
						<div style="width: 70%" class="progress-bar"></div>
					</div>
					<span class="progress-description">
						<b>Paid Search</b>
					</span>
				</div><!-- /.info-box-content -->
			</div>
		</div>
	</div>



	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 style="color: blue; word-spacing: 5px;" class="box-title"><i class="fa fa-tags"></i> Top Organic Keywords</h3>
					<div class="box-tools pull-right">
						<button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
						<button data-widget="remove" class="btn btn-box-tool"><i class="fa fa-times"></i></button>
					</div>
				</div>
				<div class="box-body chart-responsive table-responsive">
					<table class="table table-condensed">
						<thead>
							<tr>
								<th><h4>SL</h4></th>
								<th><h4>Keyword</h4></th>
							</tr>
						</thead>
						<tbody>
							<?php 
								$sl=0;                  
					            if(is_array($top_organic_keyword) && count($top_organic_keyword)>0)
					            {
					                foreach($top_organic_keyword as $key=>$val)
					                {                  
					                    $sl++;
					                    echo "<tr><td>".$sl."</td>";
						                echo "<td>".$top_organic_keyword[$key]."</td>";					                
					                }
					            }
					            else
					            {
					            	echo "<tr><td colspan='2'>No data found!</td></tr>";
					            }

							?>
						</tbody>
					</table>
				</div>
			</div> <!-- end box -->	
		</div>
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">	
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 style="color: blue; word-spacing: 5px;" class="box-title"><i class="fa fa-tags"></i> Top Paid Keywords</h3>
					<div class="box-tools pull-right">
						<button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
						<button data-widget="remove" class="btn btn-box-tool"><i class="fa fa-times"></i></button>
					</div>
				</div>
				<div class="box-body chart-responsive table-responsive">
					<table class="table table-condensed">
						<thead>
							<tr>
								<th><h4>SL</h4></th>
								<th><h4>Keyword</h4></th>
							</tr>
						</thead>
						<tbody>
							<?php 
								$sl=0;                  
					            if(is_array($top_paid_keyword) && count($top_paid_keyword)>0)
					            {
					                foreach($top_paid_keyword as $key=>$val)
					                {                  
					                    $sl++;
					                    echo "<tr><td>".$sl."</td>";
						                echo "<td>".$top_paid_keyword[$key]."</td>";					                
					                }
					            }
					            else
					            {
					            	echo "<tr><td colspan='2'>No data found!</td></tr>";
					            }

							?>
						</tbody>
					</table>
				</div>
			</div> <!-- end box -->		
		</div>
	</div>



	
	
</div>




<style type="text/css" media="screen">
	th{font-family:Arial;}
	.box-body{min-height:270px !important;}
	.progress{margin-bottom:10px;}
</style>

</style>