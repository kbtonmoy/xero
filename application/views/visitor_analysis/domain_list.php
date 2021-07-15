<section class="section section_custom">
  <div class="section-header">
    <h1><i class="fas fa-chart-line"></i> <?php echo $page_title; ?></h1>
    <div class="section-header-button">
     <a class="btn btn-primary add_domain_modal" href="#">
        <i class="fas fa-plus-circle"></i> <?php echo $this->lang->line("Add new domain"); ?>
     </a> 
    </div>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><a href="<?php echo base_url("menu_loader/analysis_tools"); ?>"><?php echo $this->lang->line("Analysis Tools"); ?></a></div>
      <div class="breadcrumb-item"><?php echo $page_title; ?></div>
    </div>
  </div>

  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card">

          <div class="card-body data-card">
            <?php echo $this->session->flashdata('dashboard_msg'); ?> <br>
          	<div class="row">
          		<div class="col-md-6 col-12">
              	<div class="input-group mb-3 float-left" id="searchbox">
    	          	
                    <input type="text" class="form-control" id="domain_name" name="domain_name" autofocus placeholder="<?php echo $this->lang->line('Domain Name'); ?>" aria-label="" aria-describedby="basic-addon2">
  	          	  	<div class="input-group-append">
  	          	    	<button class="btn btn-primary" id="search_submit" title="<?php echo $this->lang->line('Search'); ?>" type="button"><i class="fas fa-search"></i> <span class="d-none d-sm-inline"><?php echo $this->lang->line('Search'); ?></span></button>
  	      	 	 	    </div>
            		</div>
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
        						<th><?php echo $this->lang->line("Domin Name"); ?></th>      
        						<th><?php echo $this->lang->line("Domin Code"); ?></th>
        						<th><?php echo $this->lang->line("JavaScript Code"); ?></th>
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


<script>
  $(document).ready(function($){
    var base_url = '<?php echo base_url(); ?>';

    var perscroll;
    var table = $("#mytable").DataTable({
        serverSide: true,
        processing:true,
        bFilter: false,
        order: [[ 1, "desc" ]],
        pageLength: 10,
        ajax: 
        {
          "url": base_url+'visitor_analysis/domain_list_visitor_data',
          "type": 'POST',
          data: function ( d )
          {
              d.domain_name = $('#domain_name').val();
          }
        },
        language: 
        {
          url: "<?php echo base_url('assets/modules/datatables/language/'.$this->language.'.json'); ?>"
        },
        dom: '<"top"f>rt<"bottom"lip><"clear">',
        columnDefs: [
            {
              targets: [0,1,4],
              visible: false
            },
            {
              targets: [2,3,4,5],
              className: 'text-center'
            },
            {
              targets:[3,4,5],
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

    $(document).on('click', '#search_submit', function(event) {
      event.preventDefault(); 
      table.draw();
    });

    $(document).on('click', '.add_domain_modal', function(event) {
      event.preventDefault(); 
      $("#analytic_code").html('');
      $("#domain_name_add").val('');
      $("#add_domain_modal").modal();
    });

    $('#add_domain_modal').on('hidden.bs.modal', function () { 
      table.draw();
    });


    $(document).on('click', '#add_domain', function(event) {
      event.preventDefault(); 
      var domain_name = $("#domain_name_add").val();
      if(domain_name.trim() == '')
      {
        swal('<?php echo $this->lang->line("Warning"); ?>', '<?php echo $this->lang->line("You have to provide a domain name."); ?>', 'warning');
        return;
      }


      var waiting_content = '<div class="text-center waiting"><i class="fas fa-spinner fa-spin blue text-center" style="font-size: 40px;"></i></div>';
      $("#analytic_code").html(waiting_content);
      $(this).addClass('btn-progress');
      $.ajax({
        context: this,
        type:'POST' ,
        url: base_url+"visitor_analysis/add_domain_action",
        data: {domain_name},
        // dataType : 'JSON',
        success:function(response){
          $(this).removeClass('btn-progress');
          $("#analytic_code").html(response);
        }
      });
    });

    $(document).on('click','.delete_template',function(e){
      e.preventDefault();

      swal({
        title: '<?php echo $this->lang->line("Delete!"); ?>',
        text: '<?php echo $this->lang->line("Are you sure about deleting this domain from visitor analysis? All the collected data for this domain will also be deleted."); ?>',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) 
        {
          var base_url = '<?php echo site_url();?>';
          $(this).addClass('btn-danger');
          $(this).addClass('btn-progress');
          var table_id = $(this).attr('table_id');

          $.ajax({
            context: this,
            type:'POST' ,
            url:"<?php echo site_url();?>visitor_analysis/ajax_delete_domain",
            // dataType: 'json',
            data:{table_id:table_id},
            success:function(response){ 
              $(this).removeClass('btn-danger');
              $(this).removeClass('btn-progress');
              if(response=='success')
              {
                iziToast.success({title: '',message: '<?php echo $this->lang->line("Domain and corresponding data has been deleted successfully."); ?>',position: 'bottomRight'});
                table.draw();
              }
              else if(response=='no_match')
              {
                iziToast.error({title: '',message: '<?php echo $this->lang->line("No Domain is found for this user with this ID."); ?>',position: 'bottomRight'});
              }
              else
              {
                iziToast.error({title: '',message: '<?php echo $this->lang->line("Something went wrong, please try again."); ?>',position: 'bottomRight'});
              }
            }
          });
        } 
      });


    });

    $(document).on('click','.delete_30_days_data',function(e){
      e.preventDefault();

      swal({
        title: '<?php echo $this->lang->line("Delete!"); ?>',
        text: '<?php echo $this->lang->line("Are you sure about deleting data except last 30 days for this domain?"); ?>',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) 
        {
          var base_url = '<?php echo site_url();?>';
          $(this).addClass('btn-danger');
          $(this).addClass('btn-progress');
          var table_id = $(this).attr('table_id');

          $.ajax({
            context: this,
            type:'POST' ,
            url:"<?php echo site_url();?>visitor_analysis/ajax_delete_last_30_days_data",
            // dataType: 'json',
            data:{table_id:table_id},
            success:function(response){ 
              $(this).removeClass('btn-danger');
              $(this).removeClass('btn-progress');
              if(response=='success')
              {
                iziToast.success({title: '',message: '<?php echo $this->lang->line("Data except last 30 days has been deleted successfully."); ?>',position: 'bottomRight'});
                table.draw();
              }
              else if(response=='no_match')
              {
                iziToast.error({title: '',message: '<?php echo $this->lang->line("No Domain is found for this user with this ID."); ?>',position: 'bottomRight'});
              }
              else
              {
                iziToast.error({title: '',message: '<?php echo $this->lang->line("Something went wrong, please try again."); ?>',position: 'bottomRight'});
              }
            }
          });
        } 
      });


    });

    $(document).on('click', '.get_js_code', function(event) {
        event.preventDefault(); 
        var waiting_content = '<div class="text-center waiting"><i class="fas fa-spinner fa-spin blue text-center" style="font-size: 40px;"></i></div>';
        var table_id = $(this).attr('table_id');
        $('#get_js_code_modal_body').html(waiting_content);
        $('#get_js_code').modal();
        
        $.ajax({
          context: this,
          type:'POST' ,
          url:"<?php echo base_url('visitor_analysis/get_js_code'); ?>",
          // dataType: 'json',
          data:{table_id:table_id},
          success:function(response){ 
            $('#get_js_code_modal_body').html(response);
          }
        });
      });
    
    $(document).on('click','.show_in_dashboard',function(event){
      event.preventDefault();

      var dashboard = $(this).attr('dashboard');
      var warning_text;
      if (dashboard=='1') {
         warning_text = '<?php echo $this->lang->line("Do you want to remove this domain showing from your dashboard?"); ?>';
      }
      else{
        warning_text = '<?php echo $this->lang->line("Do you want to remove this domain remove from your dashboard?"); ?>';
      }


      swal({
        title: '<?php echo $this->lang->line("Are you sure?"); ?>',
        text: warning_text,
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) 
        {
          var base_url = '<?php echo site_url();?>';
          var table_id = $(this).attr('table_id');
          var dashboard = $(this).attr('dashboard');

          $.ajax({
            context: this,
            type:'POST' ,
            url:"<?php echo site_url();?>visitor_analysis/display_in_dashboard",
            dataType: 'json',
            data:{table_id:table_id,dashboard:dashboard},
            success:function(response){ 
              if (response.status == 'exist') {

                swal('<?php echo $this->lang->line("Warning"); ?>', response.message, 'warning');
                table.draw();
              }
              else if (response.status == 'not_exist')
              {
               
                swal('<?php echo $this->lang->line("success"); ?>', response.message, 'success');
                table.draw();
              }
              else if (response.status == 'remove')
              {
                swal('<?php echo $this->lang->line("success"); ?>', response.message, 'success');
                table.draw();
              }
            }
          });
        } 
      });


    });


  });
</script>

<div class="modal fade" id="add_domain_modal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-plus-circle"></i> <?php echo $this->lang->line('Add a domain for visitor analysis.'); ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <div class="input-group mb-3">
                <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-signature"></i></div></div>
                <input type="text" class="form-control" placeholder="<?php echo $this->lang->line('Write or Paste Domain Name here'); ?>" id="domain_name_add" name="domain_name_add">
                <div class="input-group-append">
                  <button class="btn btn-primary" type="button" id="add_domain"><i class="fas fa-plus-circle"></i> <?php echo $this->lang->line('Add'); ?></button>
                </div>
              </div>
            </div>
          </div>
        </div> 
        <div class="row">
          <div class="col-12" id="analytic_code">
            
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="get_js_code" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center"><i class="fas fa-code"></i> <?php echo $this->lang->line("JS Code"); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body" id="get_js_code_modal_body">                
              
            </div>
        </div>
    </div>
</div>