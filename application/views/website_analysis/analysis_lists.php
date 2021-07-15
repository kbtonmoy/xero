<style>
  ::placeholder{color:#bbb !important;}
  .dropdown-toggle::after{content:none !important;}
  #domain_name{max-width: 57% !important;}
  .bbw{border-bottom-width: thin !important;border-bottom:solid .5px #f9f9f9 !important;padding-bottom:20px;}
  @media (max-width: 575.98px) { #domain_name{max-width: 90% !important;} }
</style>

<section class="section section_custom">
    <div class="section-header">
        <h1><i class="fas fa-globe"></i> <?php echo $page_title; ?></h1>
        <div class="section-header-button">
            <a class="btn btn-primary add_domain_modal" href="#">
                <i class="fas fa-plus-circle"></i> <?php echo $this->lang->line("New Analysis"); ?>
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
                        <div class="row">

                            <div class="col-md-6 col-12">
                                <div class="input-group float-left" id="searchbox">

                                    <input type="text" class="form-control" id="domain_name" name="domain_name" placeholder="<?php echo $this->lang->line('Domain Name'); ?>" aria-label="" aria-describedby="basic-addon2">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" id="search_submit" title="<?php echo $this->lang->line('Search'); ?>" type="button"><i class="fas fa-search"></i> <span class="d-none d-sm-inline"><?php echo $this->lang->line('Search'); ?></span></button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <a href="javascript:;" id="post_date_range" class="btn btn-primary btn-lg icon-left float-right btn-icon"><i class="fas fa-calendar"></i> <?php echo $this->lang->line("Choose Date");?></a><input type="hidden" id="post_date_range_val">
                                <button class="btn btn-lg btn-outline-danger delet_all_domain float-right mr-1" title="<?php echo $this->lang->line('Delete Selected Domains'); ?>"><i class="fas fa-trash-alt"></i> <?php echo $this->lang->line('Delete'); ?></button>
                            </div>

                        </div>
                        <div class="table-responsive2">
                            <table class="table table-bordered" id="mytable">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line("#"); ?></th>   
                                        <th style="vertical-align:middle;width:20px !important;">
                                            <input class="regular-checkbox" id="datatableSelectAllRows" type="checkbox"/>
                                            <label for="datatableSelectAllRows"></label>        
                                        </th>    
                                        <th><?php echo $this->lang->line("ID"); ?></th>      
                                        <th><?php echo $this->lang->line("Domain"); ?></th>    
                                        <th><?php echo $this->lang->line("Search From"); ?></th>    
                                        <th><?php echo $this->lang->line("Searched At"); ?></th>
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

<div class="modal fade" id="new_analysis_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" style="max-width:50% !important;">
        <div class="modal-content">
            <div class="modal-header bbw">
                <h5 class="modal-title blue"><i class="fa fa-hourglass-half"></i> <?php echo $this->lang->line('Analyze Website'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <form action="#" id="domain_analyzing_form">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-signature"></i></div></div>
                                            <input type="text" class="form-control" placeholder="<?php echo $this->lang->line('Write or Paste Domain Name here'); ?>" id="analyzing_domain_name" name="analyzing_domain_name">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button" id="submit_domain"><i class="fa fa-hourglass-half"></i> <?php echo $this->lang->line('Analyze'); ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>  
                        </form>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-none mb-0" id="analysis_progression">
                            <div class="card-body">
                                <div class="text-center">
                                    <span style="font-size: 18px!important;font-weight:bold" id="domain_name_show"></span>
                                </div>
                                <div class="clearfix"></div>
                                <div class="text-center" id="domain_success_msg"></div>    

                                <div class="text-center" id="progress_msg">
                                    <span id="domain_progress_msg_text"></span>
                                    <div class="progress" style="display: none;height:20px;" id="domain_progress_bar_con"> 
                                        <div style="width:3%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="3" role="progressbar" class="progress-bar progress-bar-primary progress-bar-striped progress-bar-animated"><span>1%</span></div> 
                                    </div>
                                </div>
                                <div class="col-12 text-center"><br><h2 id="completed_result_link"></h2></div>
                                <div class="row"><div class="col-12" id="completed_function_str"></div></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-whitesmoke">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> <?php echo $this->lang->line('Close'); ?></button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function($){
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
                "url": base_url+'website_analysis/website_analysis_lists_data',
                "type": 'POST',
                data: function ( d )
                {
                    d.domain_name = $('#domain_name').val();
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
                    targets: [0,1,2,4,5,6],
                    className: 'text-center'
                },
                {
                    targets:[0,1,2,3,4,6],
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

        $(document).on('change', '#post_date_range_val', function(event) {
            event.preventDefault(); 
            table.draw();
        });

        $('#new_analysis_modal').on('hidden.bs.modal', function () {
            table.draw();
        });

        $(document).on('click','.delete_domain',function(e){
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
                        type:'POST' ,
                        url:"<?php echo base_url('website_analysis/delete_website_analysis_domain')?>",
                        data:{table_id:table_id},
                        success:function(response){ 

                            if(response == '1')
                            {
                                iziToast.success({title: '',message: '<?php echo $this->lang->line('Domain has been Deleted Successfully.'); ?>',position: 'bottomRight'});
                                table.draw();
                            } else
                            {
                                iziToast.error({title: '',message: '<?php echo $this->lang->line('Something went wrong, please try once again.'); ?>',position: 'bottomRight'});
                            }
                        }
                    });
                } 
            });
        });


        $(document).on('click', '.delet_all_domain', function(event) {
            event.preventDefault();

            var domain_ids = [];
            $(".datatableCheckboxRow:checked").each(function ()
            {
                domain_ids.push(parseInt($(this).val()));
            });
            
            if(domain_ids.length==0) {

                swal('<?php echo $this->lang->line("Warning")?>', '<?php echo $this->lang->line("Please select domain to delete.") ?>', 'warning');
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
                            url: base_url+"website_analysis/ajax_delete_all_selected_domain",
                            data:{info:domain_ids},
                            success:function(response){
                                $(this).removeClass('btn-progress');

                                if(response == '1') {

                                    iziToast.success({title: '',message: '<?php echo $this->lang->line('Selected Domains has been deleted Successfully.'); ?>',position: 'bottomRight'});

                                } else {

                                    iziToast.success({title: '',message: '<?php echo $this->lang->line('Something went wrong, please try once again.'); ?>',position: 'bottomRight'});

                                }

                                table.draw();
                            }
                        });

                    } 
                });
            }

        });
    });
</script>

<script type="text/javascript">
    var base_url = '<?php echo base_url(); ?>';


    $(document).on('click', '.add_domain_modal', function(event) {
    event.preventDefault();

        var config_data = '<?php echo $has_google_api; ?>';

        if(config_data == false) {

            var success_message = "<?php echo $this->lang->line('You have not added Google API Key, please add your Google API key from'); ?> <a href='"+base_url+"social_apps/connectivity_settings'> <?php echo $this->lang->line('Connectivity Settings'); ?></a>";

            var span = document.createElement("span");
            span.innerHTML = success_message;
            swal({ title:'', content:span,icon:'warning'});
            return;
            
        } else {
            $("#new_analysis_modal").modal();
            $("#analysis_progression").css("display","none");
        }
    });

    var interval="";

    function get_bulk_progress()
    {    
        $.ajax({
            url:base_url+'website_analysis/bulk_scan_progress_count',
            type:'POST',
            dataType:'json',
            success:function(response){
                var search_complete=response.search_complete;
                var search_total=response.search_total;
                var latest_record=response.latest_record;
                var view_details_button = response.view_details_button;

                $("#domain_progress_msg_text").html(search_complete +" / "+ search_total +" <?php echo $this->lang->line('step completed') ?>");
                $("#completed_function_str").html(response.completed_function_str);
                var width=(search_complete*100)/search_total;
                width=Math.round(width);          
                var width_per=width+"%";
                if(width<3)
                {
                    $("#domain_progress_bar_con div").css("width","3%");
                    $("#domain_progress_bar_con div").attr("aria-valuenow","3");
                    $("#domain_progress_bar_con div span").html("1%");
                }
                else
                {
                    $("#domain_progress_bar_con div").css("width",width_per);
                    $("#domain_progress_bar_con div").attr("aria-valuenow",width);
                    $("#domain_progress_bar_con div span").html(width_per);
                }

                if(width==100) 
                {
                    $("#domain_progress_bar_con div").removeClass('progress-bar-animated');
                    $("#domain_progress_msg_text").html("<?php echo $this->lang->line('completed') ?>");
                    $("#domain_success_msg").html('');
                    $("#completed_result_link").html(response.view_details_button);         
                    clearInterval(interval);
                }      

            }
        });
    }

    $(document).on('click', '#submit_domain', function(event) {
        event.preventDefault();

        $("#analysis_progression").css("display","block");
        var domain_name = $('#analyzing_domain_name').val().trim();
        $("#domain_name_show").html(domain_name);
        var base_url="<?php echo site_url(); ?>";

        var reg = /^((?:(?:(?:\w[\.\-\+]?)*)\w)+)((?:(?:(?:\w[\.\-\+]?){0,62})\w)+)\.(\w{2,6})$/i;
        var output = reg.test(domain_name);
        if(output === false)
        {
          swal('<?php echo $this->lang->line("Warning"); ?>', '<?php echo $this->lang->line('Please provide a domain name in valid format.')?>', 'warning');
          return;
        }

        if(domain_name == '') {
            swal('<?php echo $this->lang->line("Warning"); ?>', '<?php echo $this->lang->line("Domain Name is Required"); ?>', 'warning');
            return;

        } else {

            $("#domain_progress_bar_con div").css("width","3%");
            $("#domain_progress_bar_con div").attr("aria-valuenow","3");
            $("#domain_progress_bar_con div span").html("1%");
            $("#domain_progress_msg_text").html("");        
            $("#domain_progress_bar_con").show();       
            interval=setInterval(get_bulk_progress, 10000);

            $("#domain_success_msg").html('<img style="margin-top:20px;" class="center-block" src="'+base_url+'assets/pre-loader/loading-animations.gif" width="150px" height="150px" alt="<?php echo $this->lang->line('please wait'); ?>"><br/>');

            $("#completed_result_link").html('');

            $.ajax({
                type:'POST' ,
                url: "<?php echo site_url(); ?>website_analysis/ajax_domain_analysis_action",
                data:{domain_name:domain_name},
                success:function(response){
                    $("#domain_progress_bar_con div").css("width","100%");
                    $("#domain_progress_bar_con div").attr("aria-valuenow","100");
                    $("#domain_progress_bar_con div span").html("100%");
                    $("#domain_progress_msg_text").html("<?php echo $this->lang->line('completed') ?>");
                    $("#domain_success_msg").html('');
                    $("#completed_result_link").html(response);
                }
            }); 
        }
    });
</script>

