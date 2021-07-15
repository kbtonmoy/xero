<style>.no_hover{text-decoration: none !important;}</style>
<section class="section">
  <div class="section-header">
    <h1><i class="fas fa-chart-bar"></i> <?php echo $page_title; ?></h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><?php echo $page_title; ?></div>
    </div>
  </div>

  <div class="section-body">
    <div class="row">
      <?php if($this->session->userdata('user_type') == 'Admin' || in_array(1,$this->module_access)) : ?>
      <div class="col-lg-6">
        <div class="card card-large-icons">
          <div class="card-icon text-primary">
            <i class="fas fa-users"></i>
          </div>
          <div class="card-body">
            <h4><?php echo $this->lang->line("Visitor Analytics"); ?></h4>
            <p><?php echo $this->lang->line("Visitor analytics is for analyzing own sites."); ?></p>
            <a href="<?php echo base_url("visitor_analysis/index"); ?>" class="card-cta"><?php echo $this->lang->line("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
          </div>
        </div>
      </div>
      <?php endif; ?>

      <?php if($this->session->userdata('user_type') == 'Admin' || in_array(2,$this->module_access)) : ?>
      <div class="col-lg-6">
        <div class="card card-large-icons">
          <div class="card-icon text-primary">
            <i class="fas fa-globe"></i>
          </div>
          <div class="card-body">
            <h4><?php echo $this->lang->line("Website Analysis"); ?></h4>
            <p><?php echo $this->lang->line("Web analysis is for analyzing any website."); ?></p>
            <a href="<?php echo base_url("website_analysis"); ?>" class="card-cta"><?php echo $this->lang->line("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
          </div>
        </div>
      </div>
      <?php endif; ?>

      <?php if($this->session->userdata('user_type') == 'Admin' || in_array(3,$this->module_access)) : ?>
      <div class="col-lg-6">
        <div class="card card-large-icons">
          <div class="card-icon text-primary">
            <i class="fas fa-share-square"></i>
          </div>
          <div class="card-body">
            <h4><?php echo $this->lang->line("Social Network Analysis"); ?></h4>
            <p><?php echo $this->lang->line("Social Network Analysis Crawls social activities of a website."); ?></p>
            <a href="<?php echo base_url("social/social_list"); ?>" class="card-cta"><?php echo $this->lang->line("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
          </div>
        </div>
      </div>
      <?php endif; ?>

      <?php if($this->session->userdata('user_type') == 'Admin' || in_array(4,$this->module_access)) : ?>
      <div class="col-lg-6">
          <div class="card card-large-icons">
              <div class="card-icon text-primary"><i class="fas fa-trophy"></i></div>
              <div class="card-body">
                  <h4><?php echo $this->lang->line("Rank & Index Analysis"); ?></h4>
                  <p><?php echo $this->lang->line("Alexa Rank, Alexa Data, Social network analysis, Moz Check"); ?></p>
                  <div class="dropdown">
                      <a href="#" data-toggle="dropdown" class="no_hover" style="font-weight: 500;"><?php echo $this->lang->line("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
                      <div class="dropdown-menu">
                          <div class="dropdown-title"><?php echo $this->lang->line("Tools"); ?></div>                        
                          <a class="dropdown-item has-icon" href="<?php echo base_url('rank/alexa_rank'); ?>"><i class="fas fa-plug"></i> <?php echo $this->lang->line("Alexa Rank"); ?></a>
                          <a class="dropdown-item has-icon" href="<?php echo base_url('rank/alexa_rank_full'); ?>"><i class="fas fa-plug"></i> <?php echo $this->lang->line("Alexa Data"); ?></a>
                          <a class="dropdown-item has-icon" href="<?php echo base_url('rank/moz_rank'); ?>"><i class="fas fa-plug"></i> <?php echo $this->lang->line("Moz Check"); ?></a>
                          <a class="dropdown-item has-icon" href="<?php echo base_url('search_engine_index/index'); ?>"><i class="fas fa-plug"></i> <?php echo $this->lang->line("Search Engine Index"); ?></a>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <?php endif; ?>

      <?php if($this->session->userdata('user_type') == 'Admin' || in_array(5,$this->module_access)) : ?>
      <div class="col-lg-6">
          <div class="card card-large-icons">
              <div class="card-icon text-primary"><i class="fas fa-server"></i></div>
              <div class="card-body">
                  <h4><?php echo $this->lang->line("Domain Analysis"); ?></h4>
                  <p><?php echo $this->lang->line("Whois, Auction Domain, DNS, Server Information."); ?></p>
                  <div class="dropdown">
                      <a href="#" data-toggle="dropdown" class="no_hover" style="font-weight: 500;"><?php echo $this->lang->line("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
                      <div class="dropdown-menu">
                          <div class="dropdown-title"><?php echo $this->lang->line("Tools"); ?></div>                        
                          <a class="dropdown-item has-icon" href="<?php echo base_url('who_is/index'); ?>"><i class="fas fa-plug"></i> <?php echo $this->lang->line("Whois Search"); ?></a>
                          <a class="dropdown-item has-icon" href="<?php echo base_url('expired_domain/index'); ?>"><i class="fas fa-plug"></i> <?php echo $this->lang->line("Auction Domain List"); ?></a>
                          <a class="dropdown-item has-icon" href="<?php echo base_url('dns_info/index'); ?>"><i class="fas fa-plug"></i> <?php echo $this->lang->line("DNS Information"); ?></a>
                          <a class="dropdown-item has-icon" href="<?php echo base_url('server_info/index'); ?>"><i class="fas fa-plug"></i> <?php echo $this->lang->line("Server Information"); ?></a>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <?php endif; ?>

      <?php if($this->session->userdata('user_type') == 'Admin' || in_array(7,$this->module_access)) : ?>
      <div class="col-lg-6">
          <div class="card card-large-icons">
              <div class="card-icon text-primary"><i class="fas fa-anchor"></i></div>
              <div class="card-body">
                  <h4><?php echo $this->lang->line("Link Analysis"); ?></h4>
                  <p><?php echo $this->lang->line("Whois, Auction Domain, DNS, Server Information."); ?></p>
                  <div class="dropdown">
                      <a href="#" data-toggle="dropdown" class="no_hover" style="font-weight: 500;"><?php echo $this->lang->line("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
                      <div class="dropdown-menu">
                          <div class="dropdown-title"><?php echo $this->lang->line("Tools"); ?></div>                        
                          <a class="dropdown-item has-icon" href="<?php echo base_url('link_analysis/index'); ?>"><i class="fas fa-plug"></i> <?php echo $this->lang->line("Link Analyzer"); ?></a>
                          <a class="dropdown-item has-icon" href="<?php echo base_url('page_status/index'); ?>"><i class="fas fa-plug"></i> <?php echo $this->lang->line("Page Status Check"); ?></a>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <?php endif; ?>

      <?php if($this->session->userdata('user_type') == 'Admin' || in_array(6,$this->module_access)) : ?>
      <div class="col-lg-6">
          <div class="card card-large-icons">
              <div class="card-icon text-primary"><i class="fas fa-map-marker-alt"></i></div>
              <div class="card-body">
                  <h4><?php echo $this->lang->line("IP Analysis"); ?></h4>
                  <p><?php echo $this->lang->line("IP Address,organization, Region, City, Postal Code, Country."); ?></p>
                  <div class="dropdown">
                      <a href="#" data-toggle="dropdown" class="no_hover" style="font-weight: 500;"><?php echo $this->lang->line("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
                      <div class="dropdown-menu">
                          <div class="dropdown-title"><?php echo $this->lang->line("Tools"); ?></div>                        
                          <a class="dropdown-item has-icon" href="<?php echo base_url('ip/index'); ?>"><i class="fas fa-plug"></i> <?php echo $this->lang->line("My IP Information"); ?></a>
                          <a class="dropdown-item has-icon" href="<?php echo base_url('ip/domain_info'); ?>"><i class="fas fa-plug"></i> <?php echo $this->lang->line("Domain IP Information"); ?></a>
                          <a class="dropdown-item has-icon" href="<?php echo base_url('ip/site_this_ip'); ?>"><i class="fas fa-plug"></i> <?php echo $this->lang->line("Sites in Same IP"); ?></a>
                          <a class="dropdown-item has-icon" href="<?php echo base_url('ip/ipv6_check'); ?>"><i class="fas fa-plug"></i> <?php echo $this->lang->line("Ipv6 Compability Check"); ?></a>
                          <a class="dropdown-item has-icon" href="<?php echo base_url('ip/ip_canonical_check'); ?>"><i class="fas fa-plug"></i> <?php echo $this->lang->line("IP Canonical Check"); ?></a>
                          <a class="dropdown-item has-icon" href="<?php echo base_url('ip/ip_traceout'); ?>"><i class="fas fa-plug"></i> <?php echo $this->lang->line("IP Traceroute"); ?></a>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <?php endif; ?>

      <?php if($this->session->userdata('user_type') == 'Admin' || in_array(8,$this->module_access)) : ?>
      <div class="col-lg-6">
          <div class="card card-large-icons">
              <div class="card-icon text-primary"><i class="fas fa-tags"></i></div>
              <div class="card-body">
                  <h4><?php echo $this->lang->line("Keyword Analysis"); ?></h4>
                  <p><?php echo $this->lang->line("analyze h1, h2, h3, h4, h5, h6 content, Single, 2 phrase, 3 phrase, 4 phrase keywords."); ?></p>
                  <div class="dropdown">
                      <a href="#" data-toggle="dropdown" class="no_hover" style="font-weight: 500;"><?php echo $this->lang->line("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
                      <div class="dropdown-menu">
                          <div class="dropdown-title"><?php echo $this->lang->line("Tools"); ?></div>                        
                          <a class="dropdown-item has-icon" href="<?php echo base_url('keyword/keyword_analyzer'); ?>"><i class="fas fa-plug"></i> <?php echo $this->lang->line("Keyword Analyzer"); ?></a>
                          <a class="dropdown-item has-icon" href="<?php echo base_url('keyword/index'); ?>"><i class="fas fa-plug"></i> <?php echo $this->lang->line("Position Analysis"); ?></a>
                          <a class="dropdown-item has-icon" href="<?php echo base_url('keyword/keyword_suggestion'); ?>"><i class="fas fa-plug"></i> <?php echo $this->lang->line("Auto Suggestion"); ?></a>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <?php endif; ?>

      <?php  if($this->basic->is_exist("modules",array("id"=>84))) {  ?>
      <?php  if($this->session->userdata('user_type') == 'Admin' || in_array(84,$this->module_access)) {  ?>
      <div class="col-lg-6">
          <div class="card card-large-icons">
              <div class="card-icon text-primary"><i class="fas fa-stethoscope"></i></div>
              <div class="card-body">
                  <h4><?php echo $this->lang->line("SiteDoctor"); ?></h4>
                  <p><?php echo $this->lang->line("Website Health Checker"); ?></p>
                  <div class="dropdown">
                      <a href="#" data-toggle="dropdown" class="no_hover" style="font-weight: 500;"><?php echo $this->lang->line("Actions"); ?> <i class="fas fa-chevron-right"></i></a>
                      <div class="dropdown-menu">
                          <div class="dropdown-title"><?php echo $this->lang->line("Tools"); ?></div>                        
                          <a class="dropdown-item has-icon" href="<?php echo base_url('sitedoctor/checked_website_lists'); ?>"><i class="fas fa-stethoscope"></i> <?php echo $this->lang->line("Check Website Health"); ?></a>
                          <a class="dropdown-item has-icon" href="<?php echo base_url('sitedoctor/comparative_check_report'); ?>"><i class="fas fa-compress-arrows-alt"></i> <?php echo $this->lang->line("Check Comparitive Health"); ?></a>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <?php } ?>
      <?php } ?>
    

    </div>
  </div>
</section>