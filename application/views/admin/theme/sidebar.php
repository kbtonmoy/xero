<div class="main-sidebar">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>assets/img/logo.png" alt='<?php echo $this->config->item("product_short_name"); ?>'></a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <!-- <a href="<?php echo base_url(); ?>dist/index"><i class="fa fa-prescription"></i></a> -->
      <a href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>assets/img/favicon.png" alt='<?php echo $this->config->item("product_short_name"); ?>'></a>
    </div>
    <ul class="sidebar-menu">
      <li class="menu-header">&nbsp;</li>

      <?php
        $admin_double_level2=array('admin/activity_log','payment/accounts','payment/earning_summary','payment/transaction_log');
        $all_links=array();
        foreach($menus as $single_menu) 
        {              
            $menu_html= '';
            $only_admin = $single_menu['only_admin'];
            $only_member = $single_menu['only_member']; 
            $module_access = explode(',', $single_menu['module_access']);
            $module_access = array_filter($module_access);


            // if($single_menu['url']=='social_apps/index' && $single_menu['only_member']=='1' && $this->config->item('backup_mode')==='0' && $this->session->userdata('user_type')=='Member') continue; // static condition not to
            if($single_menu['header_text']!='') $menu_html .= '<li class="menu-header">'.$this->lang->line($single_menu['header_text']).'</li>';

            $extraText='';
            if($single_menu['add_ons_id']!='0' && $this->is_demo=='1') $extraText=' <label class="label label-warning" style="font-size:9px;padding:4px 3px;">Addon</label>';

            if($single_menu['have_child']=='1') 
            {
              $dropdown_class1="nav-item dropdown";
              $dropdown_class2="has-dropdown";
            }
            else 
            {
              $dropdown_class1="";
              $dropdown_class2="";
            }
            if($single_menu['is_external']=='1') $site_url1=""; else $site_url1=site_url(); // if external link then no need to add site_url()
            if($single_menu['is_external']=='1') $parent_newtab=" target='_BLANK'"; else $parent_newtab=''; // if external link then open in new tab
            $menu_html .= "<li class='".$dropdown_class1."'><a {$parent_newtab} href='".$site_url1.$single_menu['url']."' class='nav-link ".$dropdown_class2."'><i class= '".$single_menu['icon']."'></i> <span>".$this->lang->line($single_menu['name']).$extraText."</span></a>"; 

            array_push($all_links, $site_url1.$single_menu['url']);  

            if(isset($menu_child_1_map[$single_menu['id']]) && count($menu_child_1_map[$single_menu['id']]) > 0)
            {
              $menu_html .= '<ul class="dropdown-menu">';
              foreach($menu_child_1_map[$single_menu['id']] as $single_child_menu)
              {                  
                  $only_admin2 = $single_child_menu['only_admin'];
                  $only_member2 = $single_child_menu['only_member']; 
                  
                  if($this->session->userdata('user_type') == 'Admin' && $this->session->userdata('license_type') != 'double' && in_array($single_child_menu['url'], $admin_double_level2)) continue;

                  if(($only_admin2 == '1' && $this->session->userdata('user_type') == 'Member') || ($only_member2 == '1' && $this->session->userdata('user_type') == 'Admin')) 
                  continue;

                  if($single_child_menu['is_external']=='1') $site_url2=""; else $site_url2=site_url(); // if external link then no need to add site_url()
                  if($single_child_menu['is_external']=='1') $child_newtab=" target='_BLANK'"; else $child_newtab=''; // if external link then open in new tab

                  if($single_child_menu['have_child']=='1') $second_menu_href = '';
                  else $second_menu_href = "href='".$site_url2.$single_child_menu['url']."'";

                  $module_access2 = explode(',', $single_child_menu['module_access']);
                  $module_access2 = array_filter($module_access2);

                  
                  $hide_second_menu = '';
                  if($this->session->userdata('user_type') != 'Admin' && !empty($module_access2) && count(array_intersect($this->module_access, $module_access2))==0) $hide_second_menu = 'hidden';
                  
                  $menu_html .= "<li class='".$hide_second_menu."'><a {$child_newtab} {$second_menu_href} class='nav-link'><i class='".$single_child_menu['icon']."'></i>".$this->lang->line($single_child_menu['name'])."</a>";

                  array_push($all_links, $site_url2.$single_child_menu['url']);

                  if(isset($menu_child_2_map[$single_child_menu['id']]) && count($menu_child_2_map[$single_child_menu['id']]) > 0)
                  {
                    $menu_html .= "<ul class='dropdown-menu2'>";
                    foreach($menu_child_2_map[$single_child_menu['id']] as $single_child_menu_2)
                    { 
                      $only_admin3 = $single_child_menu_2['only_admin'];
                      $only_member3 = $single_child_menu_2['only_member'];
                      if(($only_admin3 == '1' && $this->session->userdata('user_type') == 'Member') || ($only_member3 == '1' && $this->session->userdata('user_type') == 'Admin'))
                        continue;
                      if($single_child_menu_2['is_external']=='1') $site_url3=""; else $site_url3=site_url(); // if external link then no need to add site_url()
                      if($single_child_menu_2['is_external']=='1') $child2_newtab=" target='_BLANK'"; else $child2_newtab=''; // if external link then open in new tab   

                      $menu_html .= "<li><a {$child2_newtab} href='".$site_url3.$single_child_menu_2['url']."' class='nav-link'><i class='".$single_child_menu_2['icon']."'></i> ".$this->lang->line($single_child_menu_2['name'])."</a></li>";

                      array_push($all_links, $site_url3.$single_child_menu_2['url']);
                    }
                    $menu_html .= "</ul>";
                  }
                  $menu_html .= "</li>";
              }
              $menu_html .= "</ul>";
            }

            $menu_html .= "</li>";
            
            if($only_admin == '1') 
            {
              if($this->session->userdata('user_type') == 'Admin') 
              echo $menu_html;
            }
            else if($only_member == '1') 
            {
              if($this->session->userdata('user_type') == 'Member') 
              echo $menu_html;
            }
            else 
            {
              if($this->session->userdata("user_type")=="Admin" || empty($module_access) || count(array_intersect($this->module_access, $module_access))>0 ) 
              echo $menu_html;
            }             
        }

        if($this->session->userdata('license_type') == 'double' && $this->session->userdata('user_type')=='Member')
        {
          echo'
          <li class="menu-header">'.$this->lang->line("Payment").'</li>
          <li class="nav-item dropdown">
            <a href="#" class="nav-link has-dropdown"><i class="fa fa-coins"></i> <span>'.$this->lang->line("Payment").'</span></a>
            <ul class="dropdown-menu">
              <li class=""><a href="'.base_url("payment/buy_package").'" class="nav-link"><i class="fa fa-cart-plus"></i>'.$this->lang->line("Renew Package").'</a></li>
              <li class=""><a href="'.base_url("payment/transaction_log").'" class="nav-link"><i class="fa fa-history"></i>'.$this->lang->line("Transaction Log").'</a></li>
              <li class=""><a href="'.base_url("payment/usage_history").'" class="nav-link"><i class="fa fa-user-clock"></i>'.$this->lang->line("Usage Log").'</a></li>
            </ul>
          </li>
          ';
        }
      ?>
    </ul>

    <?php
    if($this->session->userdata('license_type') == 'double')
      if($this->config->item('enable_support') == '1')
        {
          $support_menu = $this->lang->line("Support Desk");
          $support_icon = "fa fa-headset";
          $support_url = base_url('simplesupport/tickets');
          
          echo '
          <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
            <a href="'.$support_url.'" class="btn btn-primary btn-lg btn-block btn-icon-split">
              <i class="'.$support_icon.'"></i> '.$support_menu.'
            </a>
          </div>';
        }
    ?>

    
  </aside>
</div>



<?php 
$all_links=array_unique($all_links);
$unsetkey = array_search (base_url().'#', $all_links); 
if($unsetkey!=FALSE)
unset($all_links[$unsetkey]); // removing links without a real url

/* 
links that are not in database [custom link = sibebar parent]
No need to add a custom link if it's parent is controller/index
*/
$custom_links=array
(
  base_url("admin/general_settings")=>base_url("admin/settings"),
  base_url("admin/frontend_settings")=>base_url("admin/settings"),
  base_url("admin/smtp_settings")=>base_url("admin/settings"),
  base_url("admin/email_template_settings")=>base_url("admin/settings"),
  base_url("admin/analytics_settings")=>base_url("admin/settings"),
  base_url("admin/advertisement_settings")=>base_url("admin/settings"),
  base_url("social_apps/google_settings")=>base_url("social_apps/settings"),
  base_url("social_apps/facebook_settings")=>base_url("social_apps/settings"),
  base_url("admin/add_user")=>base_url("admin/user_manager"),
  base_url("admin/edit_user")=>base_url("admin/user_manager"),
  base_url("admin/login_log")=>base_url("admin/user_manager"),
  base_url("payment/add_package")=>base_url("payment/package_manager"),
  base_url("payment/update_package")=>base_url("payment/package_manager"),
  base_url("payment/details_package")=>base_url("payment/package_manager"),
  base_url("announcement/add")=>base_url("announcement/full_list"),
  base_url("announcement/edit")=>base_url("announcement/full_list"),
  base_url("announcement/details")=>base_url("announcement/full_list"),
  base_url("addons/upload")=>base_url("addons/lists"),
  base_url("themes/upload") => base_url("themes/lists"),

  base_url("visitor_analysis/index") => base_url("menu_loader/analysis_tools"),
  base_url("visitor_analysis/domain_details") => base_url("menu_loader/analysis_tools"),
  base_url("website_analysis") => base_url("menu_loader/analysis_tools"),
  base_url("website_analysis/index") => base_url("menu_loader/analysis_tools"),
  base_url("website_analysis/analysis_report") => base_url("menu_loader/analysis_tools"),
  base_url("social/social_list") => base_url("menu_loader/analysis_tools"),
  base_url("social/analysis_new") => base_url("menu_loader/analysis_tools"),
  base_url("rank/alexa_rank") => base_url("menu_loader/analysis_tools"),
  base_url("rank/rank_alexa") => base_url("menu_loader/analysis_tools"),
  base_url("rank/alexa_rank_full") => base_url("menu_loader/analysis_tools"),
  base_url("rank/alexa_data") => base_url("menu_loader/analysis_tools"),
  base_url("rank/moz_rank") => base_url("menu_loader/analysis_tools"),
  base_url("rank/moz_rank_analysis") => base_url("menu_loader/analysis_tools"),
  base_url("search_engine_index/index") => base_url("menu_loader/analysis_tools"),
  base_url("search_engine_index/search_engine") => base_url("menu_loader/analysis_tools"),
  base_url("who_is/index") => base_url("menu_loader/analysis_tools"),
  base_url("who_is/who_is") => base_url("menu_loader/analysis_tools"),
  base_url("expired_domain/index") => base_url("menu_loader/analysis_tools"),
  base_url("dns_info/index") => base_url("menu_loader/analysis_tools"),
  base_url("server_info/index") => base_url("menu_loader/analysis_tools"),
  base_url("link_analysis/index") => base_url("menu_loader/analysis_tools"),
  base_url("link_analysis/analysis_new") => base_url("menu_loader/analysis_tools"),
  base_url("page_status/index") => base_url("menu_loader/analysis_tools"),
  base_url("page_status/analysis_new") => base_url("menu_loader/analysis_tools"),
  base_url("ip/index") => base_url("menu_loader/analysis_tools"),
  base_url("ip/domain_info") => base_url("menu_loader/analysis_tools"),
  base_url("ip/analysis_new") => base_url("menu_loader/analysis_tools"),
  base_url("ip/site_this_ip") => base_url("menu_loader/analysis_tools"),
  base_url("ip/sites_same_ip") => base_url("menu_loader/analysis_tools"),
  base_url("ip/ipv6_check") => base_url("menu_loader/analysis_tools"),
  base_url("ip/check_ipv6") => base_url("menu_loader/analysis_tools"),
  base_url("ip/ip_canonical_check") => base_url("menu_loader/analysis_tools"),
  base_url("ip/ip_traceout") => base_url("menu_loader/analysis_tools"),
  base_url("keyword/keyword_analyzer") => base_url("menu_loader/analysis_tools"),
  base_url("keyword/index") => base_url("menu_loader/analysis_tools"),
  base_url("keyword/position_keyword") => base_url("menu_loader/analysis_tools"),
  base_url("keyword/keyword_suggestion") => base_url("menu_loader/analysis_tools"),
  base_url("keyword/auto_keyword") => base_url("menu_loader/analysis_tools"),


  base_url("tools/email_encoder_decoder") => base_url("menu_loader/utlities"),
  base_url("tools/meta_tag_list") => base_url("menu_loader/utlities"),
  base_url("tools/plagarism_check_list") => base_url("menu_loader/utlities"),
  base_url("tools/index") => base_url("menu_loader/utlities"),
  base_url("tools/duplicate_email_filter_list") => base_url("menu_loader/utlities"),
  base_url("tools/url_encode_list") => base_url("menu_loader/utlities"),
  base_url("tools/url_canonical_check") => base_url("menu_loader/utlities"),
  base_url("tools/gzip_check") => base_url("menu_loader/utlities"),
  base_url("tools/base64_encode_list") => base_url("menu_loader/utlities"),
  base_url("tools/robot_code_generator") => base_url("menu_loader/utlities"),


  base_url("url_shortener/index") => base_url("menu_loader/url_shortner"),

  base_url("keyword_position_tracking/index") => base_url("menu_loader/keyword_position_tracking"),

  base_url("antivirus/scan") => base_url("menu_loader/security_tools"),
  base_url("antivirus/malware") => base_url("menu_loader/security_tools"),
  base_url("antivirus/virus_total") => base_url("menu_loader/security_tools"),
  base_url("antivirus/new_scan") => base_url("menu_loader/security_tools"),

  base_url("code_minifier/html_minifier") => base_url("menu_loader/code_minifier"),
  base_url("code_minifier/css_minifier") => base_url("menu_loader/code_minifier"),
  base_url("code_minifier/js_minifier") => base_url("menu_loader/code_minifier"),

  base_url("backlink/backlink_search") => base_url("menu_loader/backlink_ping"),
  base_url("backlink/backlink_new") => base_url("menu_loader/backlink_ping"),
  
  base_url("native_widgets/get_widget") => base_url("native_widgets/get_widget"),



);

$custom_links[base_url("payment/transaction_log_manual")]=base_url("payment/transaction_log");

$custom_links_assoc_str="{";
$loop=0;
foreach ($custom_links as $key => $value) 
{
  $loop++;
  array_push($all_links, $key); // adding custom urls in all urls array

  /* making associative link -> parent array for js, js dont support special chars */
  $custom_links_assoc_str.=str_replace(array('/',':','-','.'), array('FORWARDSLASHES','COLONS','DASHES','DOTS'), $key).":'".$value."'";
  if($loop!=count($custom_links)) $custom_links_assoc_str.=',';
}
$custom_links_assoc_str.="}";
// echo "<pre style='padding-left:300px;'>";
// print_r($all_links);
// echo "</pre>"; 
?>


<script type="text/javascript">

  var all_links_JS = [<?php echo '"'.implode('","', $all_links).'"' ?>]; // all urls includes database & custom urls
  var custom_links_JS= [<?php echo '"'.implode('","', array_keys($custom_links)).'"' ?>]; // only custom urls
  var custom_links_assoc_JS = <?php echo $custom_links_assoc_str?>; // custom urls associative array link -> parent

  var sideBarURL = window.location;
  sideBarURL=String(sideBarURL).trim();
  sideBarURL=sideBarURL.replace('#_=_',''); // redirct from facebook login return extra chars with url

  function removeUrlLastPart(the_url)   // function that remove last segment of a url
  {
      var theurl = String(the_url).split('/');
      theurl.pop();      
      var answer=theurl.join('/');
      return answer;
  }

  // get parent url of a custom url
  function matchCustomUrl(find)
  {
    var parentUrl='';
    var tempu1=find.replace(/\//g, 'FORWARDSLASHES'); // decoding special chars that was encoded to make js array
    tempu1=tempu1.replace(/:/g, 'COLONS');
    tempu1=tempu1.replace(/-/g, 'DASHES');
    tempu1=tempu1.replace(/\./g, 'DOTS');

    if(typeof(custom_links_assoc_JS[tempu1])!=='undefined')
    parentUrl=custom_links_assoc_JS[tempu1]; // getting parent value of custom link

    return parentUrl;
  }

  if(jQuery.inArray(sideBarURL, custom_links_JS) !== -1) // if the current link match custom urls
  {    
    sideBarURL=matchCustomUrl(sideBarURL);
  } 
  else if(jQuery.inArray(sideBarURL, all_links_JS) !== -1) // if the current link match known urls, this check is done later becuase all_links_JS also contains custom urls
  {
     sideBarURL=sideBarURL;
  }
  else // url does not match any of known urls
  {  
    var remove_times=1;
    var temp_URL=sideBarURL;
    var temp_URL2="";
    var tempu2="";
    while(true) // trying to match known urls by remove last part of url or adding /index at the last
    {
      temp_URL=removeUrlLastPart(temp_URL); // url may match after removing last
      temp_URL2=temp_URL+'/index'; // url may match after removing last part and adding /index

      if(jQuery.inArray(temp_URL, custom_links_JS) !== -1) // trimmed url match custom urls
      {
        sideBarURL=matchCustomUrl(temp_URL);
        break;
      }
      else if(jQuery.inArray(temp_URL, all_links_JS) !== -1) //trimmed url match known links
      {
        sideBarURL=temp_URL;
        break;
      }
      else // trimmed url does not match known urls, lets try extending url by adding /index
      {
        if(jQuery.inArray(temp_URL2, custom_links_JS) !== -1) // extended url match custom urls
        {
          sideBarURL=matchCustomUrl(temp_URL2);
          break;
        }
        else if(jQuery.inArray(temp_URL2, all_links_JS) !== -1)  // extended url match known urls
        {
          sideBarURL=temp_URL2;
          break;
        }
      }
      remove_times++;
      if(temp_URL.trim()=="") break;
    }    
  }

  $('ul.sidebar-menu a').filter(function() {
     return this.href == sideBarURL;
  }).parent().addClass('active');
  $('ul.dropdown-menu a').filter(function() {
     return this.href == sideBarURL;
  }).parentsUntil(".sidebar-menu > .dropdown-menu").addClass('active');
</script>