<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $this->config->item('product_name')." | ".$page_title;?></title>	
        <?php $this->load->view("include/css_include_back");?>
        <?php $this->load->view("include/js_include_back"); ?>  
        <link rel="shortcut icon" href="<?php echo base_url();?>assets/img/favicon.png">    
    </head>


    <body class="bg-light">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 header-sticky">
                    <div class="container" >
                        <a href="<?php echo site_url(); ?>">
                            <img src="<?php echo base_url();?>assets/img/logo.png" style="height:60%;margin-top:12px;" alt="<?php echo $this->config->item('product_name');?>" class="img-responsive">
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container body-div bg-light">
            <!-- page content -->
            <?php $this->load->view($body);?>
            <!-- page content --> 
        </div>

        <footer id="footer" class='sticky_bottom' style="padding-top: 30px; padding-bottom: 30px; color: #fff; background: #002240;">
            <div class="container-fluid text-center">
                <div class="row">
                    <div class="col-12">             
                        <?php echo $this->config->item("product_name")." ".$this->config->item("product_version").' - <a target="_BLANK" href="'.site_url().'"><b>'.$this->config->item("institute_address1").'</b></a>'; ?> 
                    </div>
                </div>
            </div>
        </footer>

    </body>
</html>
<style>
    .body-div {
        padding-top: 80px;
    }
    .header-sticky {
        height: 80px !important;
        background: #fff !important;
        position: fixed !important;
        top: 0px !important;
        bottom: 0 !important;
        z-index: 1000 !important;
        box-shadow: 0px 2px 3px #d2d2d2;
    }
    .card.card-statistic-1 .card-icon i { line-height:80px !important; }
</style>