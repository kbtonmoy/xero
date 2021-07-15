<style>
  ::placeholder{color:#bbb !important;}
  .dropdown-toggle::after{content:none !important;}
  #proxy_keyword{max-width: 50% !important;}
  .bbw{border-bottom-width: thin !important;border-bottom:solid .5px #f9f9f9 !important;padding-bottom:20px;}
  @media (max-width: 575.98px) { #proxy_keyword{max-width: 90% !important;} }
</style>

<section class="section section_custom">
    <div class="section-header">
        <h1><i class="fas fa-user-secret"></i> <?php echo $page_title; ?></h1>
        <div class="section-header-button">
            <a class="btn btn-primary insert_new_proxy" href="#">
                <i class="fas fa-plus-circle"></i> <?php echo $this->lang->line("New Proxy"); ?>
            </a> 
        </div>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><?php echo $this->lang->line('System'); ?></div>
            <div class="breadcrumb-item"><a href="<?php echo base_url("social_apps/settings"); ?>"><?php echo $this->lang->line("Social Apps & APIs"); ?></a></div>
            <div class="breadcrumb-item"><?php echo $page_title; ?></div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body data-card">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="input-group float-left" id="searchbox">
                                    <input type="text" class="form-control" id="proxy_keyword" name="proxy_keyword" placeholder="<?php echo $this->lang->line('Search'); ?>" aria-label="" aria-describedby="basic-addon2">
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive2">
                            <table class="table table-bordered" id="mytable_proxy">
                                <thead>
                                    <tr>
                                        <th class="centering no-sort"><?php echo $this->lang->line("#"); ?></th> 
                                        <th class="centering no-sort"><?php echo $this->lang->line("ID"); ?></th> 
                                        <th class="centering no-sort"><?php echo $this->lang->line("Proxy"); ?></th>      
                                        <th class="centering no-sort"><?php echo $this->lang->line("Proxy Port"); ?></th>  
                                        <?php if($this->session->userdata("user_type") == "Admin") { ?>
                                        <th class="centering no-sort"><?php echo $this->lang->line("Permisson"); ?></th>
                                        <?php } ?>    
                                        <th class="centering no-sort"><?php echo $this->lang->line("Proxy Username"); ?></th>
                                        <th class="centering no-sort"><?php echo $this->lang->line("Proxy Password"); ?></th>
                                        <th class="centering no-sort"><?php echo $this->lang->line("Actions"); ?></th>
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


<div class="modal fade" id="new_proxy_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" style="max-width:35% !important;">
        <div class="modal-content">
            <div class="modal-header bbw">
                <h5 class="modal-title blue"><i class="fas fa-user-secret"></i> <?php echo $this->lang->line('New Proxy Settings'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <form action="#" method="POST" id="new_proxy_form">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('Proxy'); ?></label>
                                        <input type="text" class="form-control" id="proxy" name="proxy">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('Proxy Port'); ?></label>
                                        <input type="text" class="form-control" id="proxy_port" name="proxy_port">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('Proxy Username'); ?></label>
                                        <input type="text" class="form-control" id="proxy_username" name="proxy_username">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('Proxy Password'); ?></label>
                                        <input type="text" class="form-control" id="proxy_password" name="proxy_password">
                                    </div>
                                </div>

                                <?php if($this->session->userdata("user_type") == "Admin") { ?>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="user_type" > <?php echo $this->lang->line('Proxy Permission');?></label>
                                            <div class="custom-switches-stacked mt-2">
                                                <div class="row">   
                                                    <div class="col-6">
                                                        <label class="custom-switch">
                                                            <input type="radio" name="permission" value="everyone" checked class="user_type custom-switch-input">
                                                            <span class="custom-switch-indicator"></span>
                                                            <span class="custom-switch-description"><?php echo $this->lang->line('Everyone'); ?></span>
                                                        </label>
                                                    </div>                        
                                                    <div class="col-6">
                                                        <label class="custom-switch">
                                                            <input type="radio" name="permission" value="only me" class="user_type custom-switch-input">
                                                            <span class="custom-switch-indicator"></span>
                                                            <span class="custom-switch-description"><?php echo $this->lang->line('Only me'); ?></span>
                                                        </label>
                                                    </div>
                                                </div>                                  
                                            </div>
                                        </div> 
                                    </div>
                                <?php } ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-whitesmoke">
                <button type="button" class="btn btn-primary" id="proxy_save"><i class="fa fa-save"></i> <?php echo $this->lang->line('Save'); ?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> <?php echo $this->lang->line('Close'); ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="update_proxy_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" style="max-width:35% !important;">
        <div class="modal-content">
            <div class="modal-header bbw">
                <h5 class="modal-title blue"><i class="fas fa-user-secret"></i> <?php echo $this->lang->line('Update Proxy Settings'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>

            <div class="modal-body">
                <div class="proxyModalBody">
                    
                </div>
            </div>

            <div class="modal-footer bg-whitesmoke update-proxy-modal-footer">
                <button type="button" class="btn btn-primary" id="proxy_update"><i class="fa fa-edit"></i> <?php echo $this->lang->line('Update'); ?></button>
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

        var perscroll;
        var table_proxy = $("#mytable_proxy").DataTable({
            serverSide: true,
            processing:true,
            bFilter: false,
            order: [[ 1, "desc" ]],
            pageLength: 10,
            ajax: 
            {
                "url": base_url+'social_apps/proxy_settings_data',
                "type": 'POST',
                data: function ( d )
                {
                    d.proxy_keyword = $('#proxy_keyword').val();
                }
            },
            language: 
            {
                url: "<?php echo base_url('assets/modules/datatables/language/'.$this->language.'.json'); ?>"
            },
            dom: '<"top"f>rt<"bottom"lip><"clear">',
            columnDefs: [
                {
                    targets: [1],
                    visible: false
                },
                {
                    targets: 'no-sort', 
                    orderable: false
                },
                {
                    targets: 'centering', 
                    className: 'text-center'
                },
            ],
            fnInitComplete:function(){  // when initialization is completed then apply scroll plugin
                if(areWeUsingScroll)
                {
                    if (perscroll) perscroll.destroy();
                    perscroll = new PerfectScrollbar('#mytable_proxy_wrapper .dataTables_scrollBody');
                }
            },
            scrollX: 'auto',
            fnDrawCallback: function( oSettings ) { //on paginition page 2,3.. often scroll shown, so reset it and assign it again 
                if(areWeUsingScroll)
                { 
                    if (perscroll) perscroll.destroy();
                    perscroll = new PerfectScrollbar('#mytable_proxy_wrapper .dataTables_scrollBody');
                }
            }
        });

        $(document).on('keyup', '#proxy_keyword', function(event) {
          event.preventDefault(); 
          table_proxy.draw();
        });

        $(document).on('click', '.insert_new_proxy', function(event) {
            event.preventDefault();
            $("#new_proxy_modal").modal();
            
        });

        $('#new_proxy_modal').on('hidden.bs.modal', function () {
            event.preventDefault();
            $("#new_proxy_form").trigger('reset');
            table_proxy.draw();
        });

        $(document).on('click','.delete_proxy',function(e){
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
                        url:"<?php echo base_url('social_apps/delete_proxy')?>",
                        data:{table_id:table_id},
                        success:function(response){ 

                            if(response == '1')
                            {
                                iziToast.success({title: '',message: '<?php echo $this->lang->line('Proxy Settings has been Deleted Successfully.'); ?>',position: 'bottomRight'});
                            } else
                            {
                                iziToast.error({title: '',message: '<?php echo $this->lang->line('Something went wrong, please try once again.'); ?>',position: 'bottomRight'});
                            }
                            table_proxy.draw();
                        }
                    });
                } 
            });
        });

        $(document).on('click', '#proxy_save', function(event) {
            event.preventDefault();
            
            var proxy = $("#proxy").val();
            var proxy_port = $("#proxy_port").val();

            if(proxy =='') {
                swal('<?php echo $this->lang->line("Warning"); ?>', "<?php echo $this->lang->line("Proxy is required"); ?>", 'warning');
                return;
            }

            if(proxy_port =='') {
                swal('<?php echo $this->lang->line("Warning"); ?>', "<?php echo $this->lang->line("Proxy Port is required"); ?>", 'warning');
                return;
            }

            $(this).addClass('btn-progress');
            var that = $(this);

            var report_link = base_url+"social_apps/proxy_settings";
            var queryString = new FormData($("#new_proxy_form")[0]);

            $.ajax({
                url:base_url+'social_apps/insert_proxy',
                type:'POST',
                data: queryString,
                dataType: 'JSON',
                cache: false,
                contentType: false,
                processData: false,
                success:function(response)
                {
                    $(that).removeClass('btn-progress');

                    if(response.status=='1')
                    {
                      var span = document.createElement("span");
                      span.innerHTML = response.message;
                      swal({ title:'<?php echo $this->lang->line("Proxy Added"); ?>', content:span,icon:'success'}).then((value) => {window.location.href=report_link;});
                    }
                    else 
                        swal('<?php echo $this->lang->line("Error"); ?>', response.message, 'error').then((value) => {window.location.href=report_link;});
                }
            });
        });

        $(document).on('click', '.edit_proxy', function(event) {
            event.preventDefault();
            
            $("#update_proxy_modal").modal();

            var table_id = $(this).attr("table_id");

            var loading = '<div class="text-center waiting"><i class="fas fa-spinner fa-spin blue text-center" style="font-size:60px"></i></div>';
            $(".proxyModalBody").html(loading);
            $(".update-proxy-modal-footer").addClass('hidden');

            $.ajax({
                url:base_url+'social_apps/ajax_update_proxy_info',
                type:'POST',
                data: {table_id: table_id},
                success:function(response)
                {
                    $(".update-proxy-modal-footer").removeClass('hidden');
                    $(".proxyModalBody").html(response);
                }
            });
        });

        $(document).on('click', '#proxy_update', function(event) {
            event.preventDefault();
            
            var proxy = $("#updated_proxy").val();
            var proxy_port = $("#updated_proxy_port").val();

            if(proxy =='') {
                swal('<?php echo $this->lang->line("Warning"); ?>', "<?php echo $this->lang->line("Proxy is required"); ?>", 'warning');
                return;
            }

            if(proxy_port =='') {
                swal('<?php echo $this->lang->line("Warning"); ?>', "<?php echo $this->lang->line("Proxy Port is required"); ?>", 'warning');
                return;
            }

            $(this).addClass('btn-progress');
            var that = $(this);

            var report_link = base_url+"social_apps/proxy_settings";
            var queryString = new FormData($("#update_proxy_form")[0]);

            $.ajax({
                url:base_url+'social_apps/update_proxy_settings',
                type:'POST',
                data: queryString,
                dataType: 'JSON',
                cache: false,
                contentType: false,
                processData: false,
                success:function(response)
                {
                    $(that).removeClass('btn-progress');

                    if(response.status=='1')
                    {
                      var span = document.createElement("span");
                      span.innerHTML = response.message;
                      swal({ title:'<?php echo $this->lang->line("Proxy Updated"); ?>', content:span,icon:'success'}).then((value) => {window.location.href=report_link;});
                    }
                    else 
                        swal('<?php echo $this->lang->line("Error"); ?>', response.message, 'error').then((value) => {window.location.href=report_link;});
                }
            });
        });

        $('#update_proxy_modal').on('hidden.bs.modal', function () {
            event.preventDefault();
            table_proxy.draw();
        });

    });
</script>

