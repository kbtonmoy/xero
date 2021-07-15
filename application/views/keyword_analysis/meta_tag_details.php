<?php
	$h1 = $meta_tag_info['h1'];
	$h2 = $meta_tag_info['h2'];
	$h3 = $meta_tag_info['h3'];
	$h4 = $meta_tag_info['h4'];
	$h5 = $meta_tag_info['h5'];
	$h6 = $meta_tag_info['h6'];
	$meta_tag_information = $meta_tag_info['meta_tag_information'];
	$blocked_by_robot_txt = $meta_tag_info['blocked_by_robot_txt'];
	$blocked_by_meta_robot = $meta_tag_info['blocked_by_meta_robot'];
	$nofollowed_by_meta_robot = $meta_tag_info['nofollowed_by_meta_robot'];
	$one_phrase = $meta_tag_info['one_phrase'];
	$two_phrase = $meta_tag_info['two_phrase'];
	$three_phrase = $meta_tag_info['three_phrase'];
	$four_phrase = $meta_tag_info['four_phrase'];
	$total_words = $meta_tag_info['total_words'];
	$domain_name = $domain_name;

	$array_spam_keyword = array( "as seen on","buying judgments", "order status", "dig up dirt on friends",
 "additional income", "double your", "earn per week", "home based", "income from home", "money making",
 "opportunity", "while you sleep", "$$$", "beneficiary", "cash", "cents on the dollar", "claims",
 "cost", "discount", "f r e e", "hidden assets", "incredible deal", "loans", "money",
 "mortgage rates", "one hundred percent free", "price", "quote", "save big money", "subject to credit",
 "unsecured debt", "accept credit cards", "credit card offers", "investment decision",
 "no investment", "stock alert", "avoid bankruptcy", "consolidate debt and credit",
 "eliminate debt", "get paid", "lower your mortgage rate", "refinance home", "acceptance",
 "chance", "here", "leave", "maintained", "never", "remove", "satisfaction", "success", 
 "dear [email/friend/somebody]", "ad", "click", "click to remove", "email harvest", "increase sales",
 "internet market", "marketing solutions", "month trial offer", "notspam",
 "open", "removal instructions", "search engine listings", "the following form", "undisclosed recipient",
 "we hate spam", "cures baldness", "human growth hormone", "lose weight spam", "online pharmacy", 
 "stop snoring", "vicodin", "#1", "4u", "billion dollars", "million", "being a member",
 "cannot be combined with any other offer", "financial freedom", "guarantee",
 "important information regarding", "mail in order form", "nigerian", "no claim forms", "no gimmick", 
 "no obligation", "no selling", "not intended", "offer", "priority mail", "produced and sent out",
 "stuff on sale", "they’re just giving it away", "unsolicited", "warranty", "what are you waiting for?",
 "winner", "you are a winner!", "cancel at any time", "get", "print out and fax", "free", 
 "free consultation", "free grant money", "free instant", "free membership", "free preview ",
  "free sample ", "all natural", "certified", "fantastic deal", "it’s effective",  "real thing",
 "access", "apply online", "can't live without", "don't hesitate", "for you", "great offer", "instant", 
 "now", "once in lifetime", "order now", "special promotion", "time limited", "addresses on cd",
 "brand new pager", "celebrity", "legal", "phone", "buy", "clearance", "orders shipped by", 
 "meet singles", "be your own boss", "earn $", "expect to earn", "home employment", "make $",
 "online biz opportunity", "potential earnings", "work at home", "affordable",
 "best price", "cash bonus", "cheap", "collect", "credit", "earn", "fast cash",
 "hidden charges", "insurance", "lowest price", "money back", "no cost", "only '$'", "profits", 
 "refinance",  "save up to",  "they keep your money -- no refund!",  "us dollars",
 "cards accepted", "explode your business", "no credit check", "requires initial investment",
 "stock disclaimer statement ", "calling creditors", "consolidate your debt", "financially independent",
 "lower interest rate", "lowest insurance rates", "social security number", "accordingly", "dormant",
 "hidden", "lifetime", "medium", "passwords", "reverses", "solution", "teen", "friend",
 "auto email removal", "click below", "direct email", "email marketing",
 "increase traffic", "internet marketing", "mass email", "more internet traffic", "one time mailing",
 "opt in", "sale", "search engines", "this isn't junk", "unsubscribe",
 "web traffic", "diagnostics", "life insurance", "medicine", "removes wrinkles",
 "valium", "weight loss", "100% free", "50% off", "join millions",
 "one hundred percent guaranteed", "billing address", "confidentially on all orders", "gift certificate",
 "have you been turned down?", "in accordance with laws", "message contains", "no age restrictions", 
 "no disappointment", "no inventory", "no purchase necessary", "no strings attached", "obligation",
 "per day", "prize", "reserves the right", "terms and conditions", "trial", "vacation",
 "we honor all", "who really wins?", "winning", "you have been selected",
 "compare", "give it away", "see for yourself", "free access", "free dvd", "free hosting",
 "free investment", "free money", "free priority mail", "free trial",
 "all new", "congratulations", "for free", "outstanding values", "risk free",
 "act now!", "call free", "do it today", "for instant access", "get it now",
 "info you requested", "limited time", "now only", "one time", "order today",
 "supplies are limited", "urgent", "beverage", "cable converter", "copy dvds", "luxury car",
 "rolex", "buy direct", "order", "shopper", "score with babes", "compete for your business",
 "earn extra cash", "extra income", "homebased business", "make money", "online degree", 
 "university diplomas", "work from home", "bargain", "big bucks", "cashcashcash",  "check",
 "compare rates", "credit bureaus", "easy terms", 'for just "$xxx"',  "income",  "investment",
 "million dollars", "mortgage", "no fees", "pennies a day", "pure profit",  "save $",
 "serious cash", "unsecured credit", "why pay more?", "check or money order", "full refund",
 "no hidden costs", "sent in compliance", "stock pick", "collect child support",
 "eliminate bad credit", "get out of debt", "lower monthly payment", "pre-approved",
 "your income", "avoid", "freedom", "home",  "lose", "miracle", "problem", "sample",
 "stop", "wife", "hello", "bulk email", "click here", "direct marketing", "form",
 "increase your sales", "marketing", "member", "multi level marketing", "online marketing", 
 "performance", "sales", "subscribe", "this isn't spam", "visit our website", 
 "will not believe your eyes", "fast viagra delivery", "lose weight",
 "no medical exams", "reverses aging", "viagra", "xanax", "100% satisfied",  "billion", 
 "join millions of americans",  "thousands", "call", "deal", "giving away",
 "if only it were that easy", "long distance phone offer", "name brand", "no catch",
 "no experience", "no middleman", "no questions asked",  "no-obligation", "off shore", "per week", 
 "prizes", "shopping spree", "the best rates", "unlimited", "vacation offers",  "weekend getaway",
 "win", "won", "you’re a winner!", "copy accurately", "print form signature",
 "sign up free today", "free cell phone", "free gift", "free installation",
 "free leads", "free offer", "free quote", "free website",  "amazing",  "drastically reduced",
 "guaranteed", "promise you", "satisfaction guaranteed", "apply now",
 "call now", "don't delete", "for only", "get started now",  "information you requested",
 "new customers only", "offer expires", "only", "please read",
 "take action now", "while supplies last", "bonus", "casino",
 "laser printer", "new domain extensions", "stainless steel"
 );
?>

<style>
	.title_meta_div .list-group-item,.html_tags_div .list-group-item { border: 1px solid #f9f9f9 !important; }
	.title_meta_div .list-group-item:first-child{border-top:0 !important;}
	.title_meta_div .list-group-item h4,.html_tags_div .list-group-item h4 { font-size:16px !important; }
	.title_meta_div .list-group-item p,.html_tags_div .list-group-item p { font-size:12px !important; }
</style>

<script>
	$(document).ready(function($) {
		// $('.accordionExamplee i').on('click', function() {
		// 	event.preventDefault();
		// 	var that = $("accordionExamplee");
		// 	if(!$(that).hasClass('collapsed')) {
		// 		$(this).removeClass('fas fa-minus');
		// 		$(this).addClass('fas fa-plus');
		// 	}  

		// 	if($(that).hasClass('collapsed')) {
		// 		$(this).addClass('fas fa-minus');
		// 		$(this).removeClass('fas fa-plus');
		// 	}
		// });
		
	});
</script>
<div class="row">
	<div class="col-12 col-md-6">
		<div class="card card-hero">
			<div class="card-header" style="border-radius:0;">
				<div class="card-description"><?php echo $this->lang->line('TITLE & METATAGS'); ?></div>
			</div>

			<div class="card-body p-0">
				<div class="list-group title_meta_div">
				<?php foreach($meta_tag_information as $key=>$value): ?>
					<a class="list-group-item list-group-item-action flex-column align-items-start pointer border-right-0 border-left-0">
						<div class="d-flex w-100 justify-content-between">
							<h4 class="mb-1" style="color:#6777ef;"><?php echo ucfirst($key); ?></h4>
						</div>
							<p class="mb-0"><?php echo $value; ?></p>
					</a>
				<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>

	<div class="col-12 col-md-6">
		<div class="card card-hero">
			<div class="card-header">
				<div class="card-description"><?php echo $this->lang->line('HTML HEADINGS'); ?></div>
			</div>

			<div class="card-body p-0">
				<div class="list-group html_tags_div">
					<a class="list-group-item list-group-item-action flex-column align-items-start pointer border-top-0 border-right-0 border-left-0">
						<div class="d-flex w-100 justify-content-between">
							<h4 class="mb-1" style="color:#6777ef;">H1</h4>
							<small class="badge badge-primary badge-pill"><?php echo count($h1); ?></small>
						</div>
						<?php foreach($h1 as $key=>$value): ?>
							<p class="mb-0"><?php echo $value; ?></p>
						<?php endforeach; ?>
					</a>
					<a class="list-group-item list-group-item-action flex-column align-items-start pointer border-right-0 border-left-0">
						<div class="d-flex w-100 justify-content-between">
							<h4 class="mb-1" style="color:#6777ef;">H2</h4>
							<small class="badge badge-primary badge-pill"><?php echo count($h2); ?></small>
						</div>
						<?php foreach($h2 as $key=>$value): ?>
						<p class="mb-0"><?php echo $value; ?></p>
						<?php endforeach; ?>
					</a>
					<a class="list-group-item list-group-item-action flex-column align-items-start pointer border-right-0 border-left-0">
						<div class="d-flex w-100 justify-content-between">
							<h4 class="mb-1" style="color:#6777ef;">H3</h4>
							<small class="badge badge-primary badge-pill"><?php echo count($h3); ?></small>
						</div>
						<?php foreach($h3 as $key=>$value): ?>
						<p class="mb-0"><?php echo $value; ?></p>
						<?php endforeach; ?>
					</a>
					<a class="list-group-item list-group-item-action flex-column align-items-start pointer border-right-0 border-left-0">
						<div class="d-flex w-100 justify-content-between">
							<h4 class="mb-1" style="color:#6777ef;">H4</h4>
							<small class="badge badge-primary badge-pill"><?php echo count($h4); ?></small>
						</div>
						<?php foreach($h4 as $key=>$value): ?>
						<p class="mb-0"><?php echo $value; ?></p>
						<?php endforeach; ?>
					</a>
					<a class="list-group-item list-group-item-action flex-column align-items-start pointer border-right-0 border-left-0">
						<div class="d-flex w-100 justify-content-between">
							<h4 class="mb-1" style="color:#6777ef;">H5</h4>
							<small class="badge badge-primary badge-pill"><?php echo count($h5); ?></small>
						</div>
						<?php foreach($h5 as $key=>$value): ?>
						<p class="mb-0"><?php echo $value; ?></p>
						<?php endforeach; ?>
					</a>
					<a class="list-group-item list-group-item-action flex-column align-items-start pointer border-right-0 border-left-0">
						<div class="d-flex w-100 justify-content-between">
							<h4 class="mb-1" style="color:#6777ef;">H6</h4>
							<small class="badge badge-primary badge-pill"><?php echo count($h6); ?></small>
						</div>
						<?php foreach($h6 as $key=>$value): ?>
						<p class="mb-0"><?php echo $value; ?></p>
						<?php endforeach; ?>
					</a>
				</div>
			</div>
		</div>
	</div>

	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4><i class="fas fa-info-circle"></i> <?php echo $this->lang->line('Other Informations'); ?></h4>
			</div>

			<div class="card-body" id="">
				<ul class="list-group">
					<div class="row">
						<div class="col-12 col-md-4">
							<li class="list-group-item d-flex justify-content-between align-items-center mb-2">
								<span><?php echo $this->lang->line('BLOCKED BY ROBOTS.TXT'); ?></span>
								<span class="badge badge-primary badge-pill">
									<?php 
									if($blocked_by_robot_txt == 'No') 
										echo 'No'; 
									if($blocked_by_robot_txt == 'Yes') 
										echo 'Yes';
									?> 
								</span>
							</li>
						</div>
						<div class="col-12 col-md-4">
							<li class="list-group-item d-flex justify-content-between align-items-center mb-2">
								<span><?php echo $this->lang->line('BLOCKED BY META-ROBOTS'); ?></span>
								<span class="badge badge-primary badge-pill">
									<?php 
									if($blocked_by_meta_robot == 'No') 
										echo 'No</span>'; 
									if($blocked_by_meta_robot == 'Yes') 
										echo 'Yes';
									?> 
								</span>
							</li>
						</div>
						<div class="col-12 col-md-4">
							<li class="list-group-item d-flex justify-content-between align-items-center mb-2">
								<span><?php echo $this->lang->line('LINKS NOFOLLOWED BY META-ROBOTS'); ?></span>
								<span class="badge badge-primary badge-pill">
									<?php 
									if($nofollowed_by_meta_robot == 'No') 
										echo 'No'; 
									if($nofollowed_by_meta_robot == 'Yes') 
										echo 'Yes';
									?>
								</span>
							</li>
						</div>
						<div class="col-12 col-md-4">
							<li class="list-group-item d-flex justify-content-between align-items-center mb-2">
								<span><?php echo $this->lang->line('TOTAL KEYWORDS'); ?></span>
								<span class="badge badge-primary badge-pill"><?php echo $total_words; ?></span>
							</li>
						</div>
					</div>
				</ul>
			</div>
		</div>
	</div>

	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4><i class="fas fa-key"></i> <?php echo $this->lang->line('Keyword Analysis'); ?></h4>
				<div class="card-header-action">
					<a id="accordionExample" data-toggle="collapse" data-target="#keywordbody-collapse" aria-expanded="true" aria-controls="keywordbody-collapse" href="#" class="accordionExamplee"><i class="fas fa-minus"></i></a>
				</div>
			</div>
			
			<div id="keywordbody-collapse" class="collapse show" aria-labelledby="keywordbody" data-parent="#accordionExample1">
				<div class="card-body keyword_analysis_body">
					<div class="row">

						<div class="col-12">
							<div class="card card-primary">
								<div class="card-header">
									<h4><?php echo $this->lang->line('Single Word Phrases'); ?></h4>
									<div class="card-header-action">
										<a data-toggle="collapse" data-target="#singleword-collapse" aria-expanded="true" aria-controls="singleword-collapse" href="#"><i class="fas fa-minus"></i></a>
									</div>
								</div>
								
								<div id="singleword-collapse" class="collapse show" aria-labelledby="singleword" data-parent="#accordionExample1">
									<div class="card-body">
										<div class="table-responsive" style="height: 300px;">
											<table class="table table-hover table-striped table-bordered text-center">
												<thead class="thead-light thead-primary">
													<tr>
														<th scope="col"><?php echo $this->lang->line('Single Keyword'); ?></th>
														<th scope="col"><?php echo $this->lang->line('Occurrences'); ?></th>
														<th scope="col"><?php echo $this->lang->line('Density'); ?></th>
														<th scope="col"><?php echo $this->lang->line('Possible Spam'); ?></th>
													</tr>
												</thead>
												<tbody>
													<?php foreach ($one_phrase as $key1 => $value1) : ?>
														<tr>
															<td><?php echo $key1; ?></td>
															<td><?php echo $value1; ?></td>
															<td><?php $occurence = ($value1/$total_words)*100; echo round($occurence, 3)." %"; ?></td>
															<td><?php 
																	if(in_array(strtolower($key1), $array_spam_keyword)) echo "Yes";
																	else echo 'No'; 
																?>
															</td>
														</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-12">
							<div class="card card-primary">
								<div class="card-header">
									<h4><?php echo $this->lang->line('2 Word Phrases'); ?></h4>
									<div class="card-header-action">
										<a data-toggle="collapse" data-target="#doubleword-collapse" aria-expanded="true" aria-controls="doubleword-collapse" href="#"><i class="fas fa-minus"></i></a>
									</div>
								</div>
								
								<div id="doubleword-collapse" class="collapse show" aria-labelledby="doubleword" data-parent="#accordionExample2">
									<div class="card-body">
										<div class="table-responsive" style="height: 300px;">
											<table class="table table-hover table-striped table-bordered text-center">
												<thead class="thead-light thead-primary">
													<tr>
														<th scope="col"><?php echo $this->lang->line('2 WORD PHRASES'); ?></th>
														<th scope="col"><?php echo $this->lang->line('Occurrences'); ?></th>
														<th scope="col"><?php echo $this->lang->line('Density'); ?></th>
														<th scope="col"><?php echo $this->lang->line('Possible Spam'); ?></th>
													</tr>
												</thead>
												<tbody>
													<?php foreach ($two_phrase as $key2 => $value2) : ?>
														<tr>
															<td><?php echo $key2; ?></td>
															<td><?php echo $value2; ?></td>
															<td><?php $occurence = $value2/$total_words*100; echo round($occurence, 3)." %"; ?></td>
															<td><?php 
																	if(in_array(strtolower($key2), $array_spam_keyword)) echo "Yes";
																	else echo 'No'; 
																?>
															</td>
														</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-12">
							<div class="card card-primary">
								<div class="card-header">
									<h4><?php echo $this->lang->line('3 WORD PHRASES'); ?></h4>
									<div class="card-header-action">
										<a id="accordionExample3" data-toggle="collapse" data-target="#tripleeword-collapse" aria-expanded="true" aria-controls="tripleeword-collapse" href="#"><i class="fas fa-minus"></i></a>
									</div>
								</div>
								
								<div id="tripleeword-collapse" class="collapse show" aria-labelledby="tripleeword" data-parent="#accordionExample3">
									<div class="card-body">
										<div class="table-responsive" style="height: 300px;">
											<table class="table table-hover table-striped table-bordered text-center">
												<thead class="thead-light thead-primary">
													<tr>
														<th scope="col"><?php echo $this->lang->line('3 WORD PHRASES'); ?></th>
														<th scope="col"><?php echo $this->lang->line('Occurrences'); ?></th>
														<th scope="col"><?php echo $this->lang->line('Density'); ?></th>
														<th scope="col"><?php echo $this->lang->line('Possible Spam'); ?></th>
													</tr>
												</thead>
												<tbody>
													<?php foreach ($three_phrase as $key3 => $value3) : ?>
														<tr>
															<td><?php echo $key3; ?></td>
															<td><?php echo $value3; ?></td>
															<td><?php $occurence = $value3/$total_words*100; echo round($occurence, 3)." %"; ?></td>
															<td><?php 
																	if(in_array(strtolower($key3), $array_spam_keyword)) echo "Yes";
																	else echo 'No'; 
																?>
															</td>
														</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-12">
							<div class="card card-primary">
								<div class="card-header">
									<h4><?php echo $this->lang->line('4 WORD PHRASES'); ?></h4>
									<div class="card-header-action">
										<a id="accordionExample4" data-toggle="collapse" data-target="#fourword-collapse" aria-expanded="true" aria-controls="fourword-collapse" href="#"><i class="fas fa-minus"></i></a>
									</div>
								</div>
								
								<div id="fourword-collapse" class="collapse show" aria-labelledby="fourword" data-parent="#accordionExample4">
									<div class="card-body">
										<div class="table-responsive" style="height: 300px;">
											<table class="table table-hover table-striped table-bordered text-center">
												<thead class="thead-light thead-primary">
													<tr>
														<th scope="col"><?php echo $this->lang->line('4 WORD PHRASES'); ?></th>
														<th scope="col"><?php echo $this->lang->line('Occurrences'); ?></th>
														<th scope="col"><?php echo $this->lang->line('Density'); ?></th>
														<th scope="col"><?php echo $this->lang->line('Possible Spam'); ?></th>
													</tr>
												</thead>
												<tbody>
													<?php foreach ($four_phrase as $key4 => $value4) : ?>
														<tr>
															<td><?php echo $key4; ?></td>
															<td><?php echo $value4; ?></td>
															<td><?php $occurence = $value4/$total_words*100; echo round($occurence, 3)." %"; ?></td>
															<td><?php 
																	if(in_array(strtolower($key4), $array_spam_keyword)) echo "Yes";
																	else echo 'No'; 
																?>
															</td>
														</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>