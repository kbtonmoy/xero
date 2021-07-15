<section class="section">
  <div class="section-header">
    <h1><i class="fas fa-trash"></i> <?php echo $page_title;?></h1>
    <div class="section-header-breadcrumb">
    	<div class="breadcrumb-item"><?php echo $this->lang->line("System");?></div>
      <div class="breadcrumb-item"><?php echo $page_title;?></div>
    </div>
  </div>
  <div class="section-body">
  		<div class="row">
  			<div class="col-12 col-md-6 offset-md-3">
  				<div class="card">
  					<div class="card-body">
						<div class="text-center">
							<div class="buttons">
								<p><b><?php echo $this->lang->line('You have'); ?> <?php echo $total_files; ?> <?php echo $this->lang->line('junk files'); ?> (<?php echo $total_file_size." KB"; ?>)</b></p>
							 
							  <button type="button" class="btn btn-danger btn-icon icon-left text-center junk_delete">
							    <i class="fas fa-trash"></i> <?php echo $this->lang->line('Delete'); ?>
							  </button>
							  <div id="success_msg"></div>
							</div>
						</div>
	                  </div>
  				</div>
  			</div>
  		</div>
  </div>
</section>

<script type="text/javascript">

  $("document").ready(function(){

    $(document).on('click', '.junk_delete', function(event) {
      event.preventDefault();


      $(".junk_delete").addClass('btn-progress');
      	  var RTG; 	
	      $.ajax({
	        url:'<?php echo site_url('admin/delete_junk_file_action'); ?>',
	        type:'POST',
	        data:{RTG:4545},
	        async: false,
	        success:function(response){ 
	          $(".junk_delete").removeClass('btn-progress');
	          $("#success_msg").html(response);

	        }

	      });
        
    });





  });  

</script>