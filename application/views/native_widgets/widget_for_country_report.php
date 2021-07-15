<!-- Bootstrap 3.3.4 -->
<link href="<?php echo base_url();?>assets/modules/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<!-- FontAwesome 4.3.0 -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/fontawesome/css/all.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/fontawesome/css/v4-shims.min.css">
<!-- Theme style -->
<link href="<?php echo base_url();?>assets/css/style.css" rel="stylesheet" type="text/css" />
<!-- jQuery 2.1.4 -->
<script src="<?php echo base_url(); ?>assets/modules/jquery.min.js"></script>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<style>body{background-color:unset;}</style>

<div class="card border">
	<div class="card-header">
		<h4><i class="fa fa-flag"></i> <?php echo $this->lang->line('Country Wise New Visitor Report For Last 30 Days'); ?></h4>
	</div>
	<?php if($data_found == 'yes') : ?>
	<div class="card-body">
		<div id="country_regions_div" style="height: 450px;"></div>
		<textarea id="country_json_data" style="display: none;"><?php echo $country_graph_data; ?></textarea> 
	</div>
	<?php else : ?>
	<div class="card-body text-center">
		<img class="img-fluid" src="<?php echo base_url("assets/img/drawkit/drawkit-nature-man-colour.svg"); ?>" style="height: 200px" alt="image">
		<h4><?php echo $this->lang->line('We couldn`t find any data'); ?></h4>
	</div>
	<?php endif; ?>
</div>

<?php if($data_found == 'yes') : ?>
<script>
	$("document").ready(function(){

		var country_json_data = $("#country_json_data").html();
		
		google.charts.load('current', {
		    'packages':['geochart']
		});

		google.charts.setOnLoadCallback(drawRegionsMap);
		function drawRegionsMap() {

		   var data = google.visualization.arrayToDataTable(JSON.parse(country_json_data));
		   var options = {};
		   var chart = new google.visualization.GeoChart(document.getElementById('country_regions_div'));
		   chart.draw(data, options);
		}
	});
</script>
<?php endif; ?>