<?php $this->load->view('admin/theme/message'); ?>
<style>
  ::placeholder{color:#adadad !important;}
  .ml-10{margin-left: 10px;}
  #keyword_searching{max-width: 50% !important;}
  @media (max-width: 575.98px) {
    #keyword_searching{max-width: 77% !important;}
  }
  .waiting {height: 100%;width:100%;display: table;}
  .waiting i{font-size:60px;display: table-cell; vertical-align: middle;padding:10px 0;}
  .waiting {padding-top: 50px;}
  .bbw{border-bottom-width: thin !important;border-bottom:solid .5px #f9f9f9 !important;padding-bottom:20px;}
</style>

<section class="section section_custom">
  <div class="section-header">
    <h1><i class="fas fa-trophy"></i> <?php echo $page_title; ?></h1>
      <div class="section-header-button">
        <a class="btn btn-primary" id="add_new_keyword" href="#">
          <i class="fas fa-plus-circle"></i> <?php echo $this->lang->line("Add Keyword"); ?>
        </a> 
      </div>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><a href="<?php echo base_url('menu_loader/keyword_position_tracking') ?>"><?php echo $this->lang->line('Keyword Tracking'); ?></a></div>
      <div class="breadcrumb-item"><?php echo $page_title; ?></div>
    </div>
  </div>

  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body data-card">
            <div class="row">

                <div class="col-md-6 col-12">
                    <div class="input-group float-left" id="searchbox">

                        <input type="text" class="form-control" id="keyword_searching" name="keyword_searching" placeholder="<?php echo $this->lang->line('Keyword'); ?>" aria-label="" aria-describedby="basic-addon2">
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <a href="javascript:;" id="post_date_range" class="btn btn-primary btn-lg icon-left float-right btn-icon"><i class="fas fa-calendar"></i> <?php echo $this->lang->line("Choose Date");?></a><input type="hidden" id="keyword_post_date_range_val">
                    <a class="btn btn-lg btn-outline-danger float-right delet_all_keywords mr-1" href=""><i class="fas fa-trash-alt"></i> <?php echo $this->lang->line('Delete'); ?>
                    </a>
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
                    <th><?php echo $this->lang->line("Keyword"); ?></th>         
                    <th><?php echo $this->lang->line("Website"); ?></th>         
                    <th><?php echo $this->lang->line("Country"); ?></th>         
                    <th><?php echo $this->lang->line("Language"); ?></th>         
                    <th><?php echo $this->lang->line("Created At"); ?></th>         
                    <th><?php echo $this->lang->line("Actions"); ?></th>

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


<div class="modal fade" id="new_keyword_modal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bbw">
        <h5 class="modal-title blue"><i class="fas fa-plus-circle"></i> <?php echo $this->lang->line('Keyword Position Tracking'); ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-12">
            <form action="#" id="keyword_tracking_form">
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <label><?php echo $this->lang->line('Keyword'); ?></label>
                    <input type="text" class="form-control" id="keyword" name="keyword">
                  </div>
                </div>

                <div class="col-12">
                  <div class="form-group">
                    <label><?php echo $this->lang->line('Website'); ?></label>
                    <input type="text" class="form-control" id="website" name="website">
                  </div>
                </div>

                <div class="col-12">
                  <div class="form-group">
                    <label><?php echo $this->lang->line('Country'); ?></label>
                    <?php  
                    $country_name['']=$this->lang->line("Select Country");
                    echo form_dropdown('country',$country_name,set_value('country'),' style="width:100%" class="form-control select2" id="country" style="width:100%"');  
                    ?>
                  </div>
                </div>

                <div class="col-12">
                  <div class="form-group">
                    <label><?php echo $this->lang->line('Language'); ?></label>
                    <?php  
                    $language_name['']=$this->lang->line("Select Language");
                    echo form_dropdown('language',$language_name,set_value('language'),' style="width:100%" class="form-control select2" id="language" style="width:100%"');  
                    ?>
                  </div>
                </div>
              </div>  
            </form>
          </div>
        </div>
      </div>
        <div class="modal-footer bg-whitesmoke">
          <button type="button" class="btn btn-primary" id="new_search_button"><i class="fas fa-save"></i> <?php echo $this->lang->line('Save'); ?></button>
          <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times"></i> <?php echo $this->lang->line('Close'); ?></button>
        </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function($) {

  var base_url = '<?php echo base_url(); ?>';
  var Doyouwanttodeletethisrecordfromdatabase = "<?php echo $this->lang->line('Do you want to detete this record?'); ?>";
  var Doyouwanttodeletealltheserecordsfromdatabase = "<?php echo $this->lang->line('Do you want to detete all the records from the database?'); ?>";

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
      $('#keyword_post_date_range_val').val(start.format('YYYY-M-D') + '|' + end.format('YYYY-M-D')).change();
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
        "url": base_url+'keyword_position_tracking/keyword_list_data',
        "type": 'POST',
  	    data: function ( d )
  	    {
  	        d.searching = $('#keyword_searching').val();
  	        d.post_date_range = $('#keyword_post_date_range_val').val();
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
          	targets: [0,1,2,4,5,6,7,8],
          	className: 'text-center'
          },
          {
          	targets: [0,1,2,3,4,5,6,7,8],
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
  // End of datatable section


  $(document).on('keyup', '#keyword_searching', function(event) {
    event.preventDefault(); 
    table.draw();
  });

  $(document).on('change', '#keyword_post_date_range_val', function(event) {
      event.preventDefault(); 
      table.draw();
  });

  $(document).on('click', '#add_new_keyword', function(event) {
    event.preventDefault();
    $("#new_keyword_modal").modal();
  });


  $(document).on('click', '#new_search_button', function(event) {
    event.preventDefault();

    var keyword = $("#keyword").val();
    var website = $("#website").val();
    var country = $("#country").val();
    var language = $("#language").val();
    
    if(keyword == "" || website == "" || country == "" || language == "") {
      swal('<?php echo $this->lang->line("Warning")?>', '<?php echo $this->lang->line("All Fields are required, please fill all required fields.") ?>', 'warning');
      return false;
    }

    $(this).addClass('btn-progress');
    var that = $(this);

    var redirect_url = '<?php echo base_url("keyword_position_tracking/keyword_list"); ?>';

    var alldatas = new FormData($("#keyword_tracking_form")[0]);

    $.ajax({
      url: base_url+'keyword_position_tracking/keyword_tracking_settings_action',
      type: 'POST',
      dataType: 'JSON',
      data: alldatas,
      cache: false,
      contentType: false,
      processData: false,
      success:function(response)
      {
        $(that).removeClass('btn-progress');

        if(response.status == "1")
        {
          var success_message = response.msg+" <a href='"+redirect_url+"'><?php echo $this->lang->line('See Report'); ?></a>";

          var span = document.createElement("span");
          span.innerHTML = success_message;
          swal({ title:'<?php echo $this->lang->line("Keyword Created"); ?>', content:span,icon:'success'}).then((value) => {window.location.href=redirect_url;});

        } else if(response.status == "2") {

          var report_url = '<?php echo site_url("payment/usage_history") ?>';
          var success_message = response.msg+" <a href='"+report_url+"'><?php echo $this->lang->line('see usage log.'); ?></a>";

          var span = document.createElement("span");
          span.innerHTML = success_message;
          swal({ title:'<?php echo $this->lang->line("Usage Warning"); ?>', content:span,icon:'success'}).then((value) => {window.location.href=report_url;});

        } else if(response.status == "3") {

          var report_url = '<?php echo site_url("payment/usage_history") ?>';
          var success_message = response.msg+" <a href='"+report_url+"'><?php echo $this->lang->line('see usage log.'); ?></a>";

          var span = document.createElement("span");
          span.innerHTML = success_message;
          swal({ title:'<?php echo $this->lang->line("Usage Warning"); ?>', content:span,icon:'success'}).then((value) => {window.location.href=report_url;});

        } else {

          var success_message = response.msg+" <a href='"+redirect_url+"'><?php echo $this->lang->line('See Report'); ?></a>";

          var span = document.createElement("span");
          span.innerHTML = success_message;
          swal({ title:'<?php echo $this->lang->line("Keyword Rejected"); ?>', content:span,icon:'success'}).then((value) => {window.location.href=redirect_url;});
        }

      }
    })


  });



  $(document).on('click','.delete_keyword',function(e){
      e.preventDefault();
      swal({
          title: '<?php echo $this->lang->line("Are you sure?"); ?>',
          text: Doyouwanttodeletethisrecordfromdatabase,
          icon: 'warning',
          buttons: true,
          dangerMode: true,
      })
      .then((willDelete) => {
          if (willDelete) 
          {
              var table_id = $(this).attr('table_id');

              $.ajax({
                  context: this,
                  type:'POST',
                  url:"<?php echo base_url('keyword_position_tracking/delete_keyword_action')?>",
                  data:{table_id:table_id},
                  success:function(response){ 
                    if(response == '1')
                    {
                      iziToast.success({title: '',message: '<?php echo $this->lang->line('Domain has been Deleted Successfully.'); ?>',position: 'bottomRight'});
                    } else
                    {
                      iziToast.error({title: '',message: '<?php echo $this->lang->line('Something went wrong, please try once again.'); ?>',position: 'bottomRight'});
                    }
                    table.draw();
                  }
              });
          } 
      });
  });

  $(document).on('click', '.delet_all_keywords', function(event) {
    event.preventDefault();

    var keyword_ids = [];
    $(".datatableCheckboxRow:checked").each(function ()
    {
      keyword_ids.push(parseInt($(this).val()));
    });
    
    if(keyword_ids.length==0) {

      swal('<?php echo $this->lang->line("Warning")?>', '<?php echo $this->lang->line("Please select keyword to delete.") ?>', 'warning');
      return false;

    }
    else {

      swal({title: '<?php echo $this->lang->line("Are you sure?"); ?>',text: Doyouwanttodeletealltheserecordsfromdatabase,icon: 'warning',buttons: true,dangerMode: true,})
      .then((willDelete) => {

          if (willDelete) {

              $(this).addClass('btn-progress');
              $.ajax({
                  context: this,
                  type:'POST',
                  url: base_url+"keyword_position_tracking/delete_selected_keyword_action",
                  data:{info:keyword_ids},
                  success:function(response){
                      $(this).removeClass('btn-progress');

                      if(response == '1') {

                          iziToast.success({title: '',message: '<?php echo $this->lang->line('Selected Keyword has been deleted Successfully.'); ?>',position: 'bottomRight'});

                      } else {

                          iziToast.error({title: '',message: '<?php echo $this->lang->line('Something went wrong, please try once again.'); ?>',position: 'bottomRight'});

                      }

                      table.draw();
                  }
              });

          } 
      });
    }

  });
  // End of reply table



  $("#new_keyword_modal").on('hidden.bs.modal', function(event) {
    event.preventDefault();
    table.draw();
  });

		
});

</script>