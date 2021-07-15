<li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
  <img src="<?php echo $this->session->userdata("brand_logo"); ?>" class="rounded-circle mr-1">
  <div class="d-sm-none d-lg-inline-block"><?php echo $this->session->userdata('username'); ?></div></a>
  <div class="dropdown-menu dropdown-menu-right">

    <div class="dropdown-title"><?php echo $this->config->item("product_short_name")." - ".$this->lang->line($this->session->userdata("user_type")); ?></div>
    <a href="<?php echo base_url('member/edit_profile'); ?>" class="dropdown-item has-icon">
      <i class="far fa-user"></i> <?php echo $this->lang->line("Profile"); ?>
    </a>
    <a href="<?php echo base_url('change_password/reset_password_form'); ?>" class="dropdown-item has-icon">
      <i class="fas fa-key"></i> <?php echo $this->lang->line("Change Password"); ?>
    </a>  

    <a href="<?php echo base_url('home/logout'); ?>" class="dropdown-item has-icon text-danger">
      <i class="fas fa-sign-out-alt"></i> <?php echo $this->lang->line("Logout"); ?>
    </a>

  </div>
</li>