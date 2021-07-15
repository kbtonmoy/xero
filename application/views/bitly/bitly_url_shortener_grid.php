<?php $this->load->view('admin/theme/message'); ?>
<style>
	.dropdown-toggle::after{content:none !important;}
	.dropdown-toggle::before{content:none !important;}
	.ml-10{margin-left: 10px;}
	#searching{max-width: 30% !important;}
	#page_id{width: 150px !important;}
	#post_type{width: 110px !important;}
	@media (max-width: 575.98px) {
		#page_id{width: 130px !important;}
		#post_type{max-width: 105px !important;}
		#searching{max-width: 77% !important;}
	}
	.waiting {height: 100%;width:100%;display: table;}
	.waiting i{font-size:60px;display: table-cell; vertical-align: middle;padding:10px 0;}
	.waiting {padding-top: 50px;}
</style>

<section class="section section_custom">
	<div class="section-header">
		<h1><i class="fas fa-cut"></i> <?php echo $page_title; ?></h1>
		<div class="section-header-button">
			<a class="btn btn-primary" href="<?php echo base_url("url_shortener/shortener");?>">
				<i class="fas fa-plus-circle"></i> <?php echo $this->lang->line("New Shortener"); ?>
			</a> 
		</div>
		<div class="section-header-breadcrumb">
			<div class="breadcrumb-item"><a href="<?php echo base_url('menu_loader/url_shortner') ?>"><?php echo $this->lang->line('URL Shortner'); ?></a></div>
			<div class="breadcrumb-item"><?php echo $page_title; ?></div>
		</div>
	</div>

	<div class="section-body">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-body data-card">
						<div class="row">
							<div class="col-md-9 col-12">
								<div class="input-group mb-3 float-left" id="searchbox">

									<input type="text" class="form-control" id="searching" name="searching"  autocomplete="false" placeholder="<?php echo $this->lang->line('Search...'); ?>" aria-label="" aria-describedby="basic-addon2">
									<div class="input-group-append">
										<button class="btn btn-primary" id="search_submit" title="<?php echo $this->lang->line('Search'); ?>" type="button"><i class="fas fa-search"></i> <span class="d-none d-sm-inline"><?php echo $this->lang->line('Search'); ?></span></button>


									</div>
									<div class="btn-group dropright float-right ml-10">
										<button type="button" class="btn btn-primary btn-lg dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">  <?php echo $this->lang->line('Options'); ?>  </button>  
										<div class="dropdown-menu dropright" x-placement="left-start" style="position: absolute; transform: translate3d(-202px, 5px, 0px); top: 0px; left: 0px; will-change: transform;"> 
											<a class="dropdown-item has-icon download pointer" id="download_btn"><i class="fa fa-cloud-download-alt"></i> <?php echo $this->lang->line('Download Selected'); ?></a> 
											<a class="dropdown-item has-icon downlaod" id="download_btn_all"><i class="fa fa-cloud-download-alt"></i> <?php echo $this->lang->line('Download All'); ?></a>
											<a target="_BLANK" class="dropdown-item has-icon delete" id="delete_btn"><i class="fa fa-trash"></i> <?php echo $this->lang->line('Delete Selected'); ?></a>
											<a target="_BLANK" class="dropdown-item has-icon delete" id="delete_btn_all"><i class="fa fa-trash"></i><?php echo $this->lang->line('Delete All'); ?></a>
										</div> 
									</div>

								</div>

							</div>
							<div class="col-md-3 col-12">
								<a href="javascript:;" id="post_date_range" class="btn btn-primary btn-lg float-right icon-left btn-icon"><i class="fas fa-calendar"></i> <?php echo $this->lang->line("Choose Date");?></a><input type="hidden" id="post_date_range_val">
							</div>
						</div>
						<div class="table-responsive2">
							<table class="table table-bordered" id="mytable">
								<thead>
									<tr>
										<th>#</th> 
										<th style="vertical-align:middle;width:20px">
											<input class="regular-checkbox" id="datatableSelectAllRows" type="checkbox"/><label for="datatableSelectAllRows"></label>        
										</th> 
										<th><?php echo $this->lang->line("ID"); ?></th>            
										<th><?php echo $this->lang->line("Long URL"); ?></th>         
										<th><?php echo $this->lang->line("Short URL"); ?></th>         
										<th><?php echo $this->lang->line("Short URL ID"); ?></th>                 
										<th><?php echo $this->lang->line("Analytics"); ?></th>                 
										<th><?php echo $this->lang->line("Created At"); ?></th>

									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>             
					</div>
				</div>
			</div>
		</div>

	</div>
</section> 

<script>
$(document).ready(function($) {

  var base_url = '<?php echo base_url(); ?>';

  setTimeout(function(){ 
    $('#post_date_range').daterangepicker({
      ranges: {
        '<?php echo $this->lang->line("Last 30 Days");?>': [moment().subtract(29, 'days'), moment()],
        '<?php echo $this->lang->line("This Month");?>'  : [moment().startOf('month'), moment().endOf('month')],
        '<?php echo $this->lang->line("Last Month");?>'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      },
      startDate: moment().subtract(29, 'days'),
      endDate  : moment()
    }, function (start, end) {
      $('#post_date_range_val').val(start.format('YYYY-M-D') + '|' + end.format('YYYY-M-D')).change();
    });
  }, 2000);


  var perscroll;
  var table = $("#mytable").DataTable({
      serverSide: true,
      processing:true,
      bFilter: false,
      order: [[ 2, "desc" ]],
      pageLength: 10,
      ajax: 
      {
        "url": base_url+'url_shortener/url_shortener_data',
        "type": 'POST',
  	    data: function ( d )
  	    {
  	        d.searching = $('#searching').val();
  	        d.post_date_range = $('#post_date_range_val').val();
  	    }
      },
      language: 
      {
        url: "<?php echo base_url('assets/modules/datatables/language/'.$this->language.'.json'); ?>"
      },
      dom: '<"top"f>rt<"bottom"lip><"clear">',
      columnDefs: [
	     	{
	     	    targets: [2],
	     	    visible: false
	     	},
          {
          	targets: [0,1,2,3,4,5,6],
          	className: 'text-center'
          },
          {
          	targets: [0,1,2,3,4,5,6],
          	sortable: false
          }
      ],
      fnInitComplete:function(){  // when initialization is completed then apply scroll plugin
        if(areWeUsingScroll)
        {
          if (perscroll) perscroll.destroy();
          perscroll = new PerfectScrollbar('#mytable_wrapper .dataTables_scrollBody');
        }
      },
      scrollX: 'auto',
      fnDrawCallback: function( oSettings ) { //on paginition page 2,3.. often scroll shown, so reset it and assign it again 
        if(areWeUsingScroll)
        { 
          if (perscroll) perscroll.destroy();
          perscroll = new PerfectScrollbar('#mytable_wrapper .dataTables_scrollBody');
        }
      }
  });



  $(document).on('change', '#post_date_range_val', function(event) {
    event.preventDefault(); 
    table.draw();
  });

  $(document).on('click', '#search_submit', function(event) {
    event.preventDefault(); 
    table.draw();
  });
  // End of datatable section


  $(document).on('keyup', '#searching', function(event) {
    event.preventDefault(); 
    table.draw();
  });



  // End of reply table

 $(document).on('click','#download_btn',function(event){
 	event.preventDefault();

	 	var ids = [];
	 	$(".datatableCheckboxRow:checked").each(function ()
	 	{
	 	    ids.push(parseInt($(this).val()));
	 	});
	 	
	 	if(ids.length==0) 
	 	{
	 	  swal('<?php echo $this->lang->line("Warning")?>', '<?php echo $this->lang->line("You have to select list from data table") ?>', 'warning');
	 	  return false;
	 	}
	 	var url = "<?php echo site_url('url_shortener/short_url_download');?>";
    	$("#bitly_url_shortener_download_selected").addClass('modal-progress');
   		$("#custom_spinner").html('<div class="text-center waiting"><i class="fas fa-spinner fa-spin blue text-center"></i></div><br/>');
	 	$.ajax({
	 		type:'POST',
	 		url:url,
	 		data:{ids:ids},
	 		success:function(response)
	 		{
	 			if (response !="") {

	 				$("#bitly_url_shortener_download_selected").modal();
	 				$("#total_download_selected").html(response);
          			$("#custom_spinner").html("");
          			$("#bitly_url_shortener_download_selected").removeClass('modal-progress');
	 			}
	 			else {
	 				swal('<?php echo $this->lang->line("Error")?>', '<?php echo $this->lang->line("Something went wrong") ?>', 'error');
	 			}

	 		}
	 	});

 });

 $(document).on('click','#download_btn_all',function(event){
  	event.preventDefault();
  	ids = 0;
 	 	var url = "<?php echo site_url('url_shortener/short_url_download');?>";
    $("#bitly_url_shortener_download_selected").addClass('modal-progress');
 	 	$.ajax({
 	 		type:'POST',
 	 		url:url,
 	 		data:{ids:ids},
 	 		success:function(response)
 	 		{
 	 			if (response !="") {

 	 				$("#bitly_url_shortener_download_selected").modal();
 	 				$("#total_download_selected").html(response);
          			$("#bitly_url_shortener_download_selected").removeClass('modal-progress');
 	 			}
 	 			else {
 	 				swal('<?php echo $this->lang->line("Error")?>', '<?php echo $this->lang->line("Something went wrong") ?>', 'error');
 	 			}

 	 		}
 	 	});

 });

  $(document).on('click','#delete_btn',function(e){
    e.preventDefault();
    swal({
      title: '<?php echo $this->lang->line("Are you sure?"); ?>',
      text: "<?php echo $this->lang->line('Do you really want to delete this item from the database?'); ?>",
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) 
      {
        var ids = [];
        $(".datatableCheckboxRow:checked").each(function ()
        {
            ids.push(parseInt($(this).val()));
        });
        
        if(ids.length==0) 
        {
          swal('<?php echo $this->lang->line("Warning")?>', '<?php echo $this->lang->line("You have to select list from data table") ?>', 'warning');
          return false;
        }

        $.ajax({
          context: this,
          type:'POST' ,
          url:"<?php echo base_url('url_shortener/short_url_delete')?>",
          data:{ids:ids},
          success:function(response){ 
            iziToast.success({title: '',message: '<?php echo $this->lang->line("Your Bitly URL shortener data has been deleted successfully."); ?>',position: 'bottomRight'});
            table.draw();
          }
        });
      } 
    });

  });

  $(document).on('click','#delete_btn_all',function(e){
    e.preventDefault();
    swal({
      title: '<?php echo $this->lang->line("Are you sure?"); ?>',
      text: "<?php echo $this->lang->line('Do you really want to delete all items from the database?'); ?>",
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) 
      {
        var ids = 0;

        $.ajax({
          context: this,
          type:'POST' ,
          url:"<?php echo base_url('url_shortener/short_url_delete')?>",
          data:{ids:ids},
          success:function(response){ 
            iziToast.success({title: '',message: '<?php echo $this->lang->line("Your All Bitly URL shortener data has been deleted successfully."); ?>',position: 'bottomRight'});
            table.draw();
          }
        });
      } 
    });
  });






		
});

</script>




<div class="modal fade show" id="bitly_url_shortener_download_selected">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="background: #fefefe;">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-cut"></i> <?php echo $this->lang->line('Bitly URL Shortener'); ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div id="custom_spinner"></div>
      <div class="modal-body text-center" id="total_download_selected"> 
       
      </div>
      
    </div>
  </div>
</div>
