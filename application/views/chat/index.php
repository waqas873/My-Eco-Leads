<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

<!--Begin::App-->
<div class="kt-grid kt-grid--desktop kt-grid--ver kt-grid--ver-desktop kt-app">

<!--Begin:: App Aside Mobile Toggle-->
<button class="kt-app__aside-close" id="kt_chat_aside_close">
<i class="la la-close"></i>
</button>

<!--End:: App Aside Mobile Toggle-->

<!--Begin:: App Aside-->
<div class="kt-grid__item kt-app__toggle kt-app__aside kt-app__aside--lg kt-app__aside--fit" id="kt_chat_aside">

<!--begin::Portlet-->
<div class="kt-portlet kt-portlet--last">
<div class="kt-portlet__body">
<div class="kt-searchbar">
	<div class="input-group">
		<div class="input-group-prepend"><span class="input-group-text" id="basic-addon1"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
					<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
						<rect x="0" y="0" width="24" height="24" />
						<path d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
						<path d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z" fill="#000000" fill-rule="nonzero" />
					</g>
				</svg></span></div>
		<input type="text" class="form-control" id="search_leads" placeholder="Search by name,email or mobile" aria-describedby="basic-addon1">
	</div>
</div>
<div class="kt-widget kt-widget--users kt-mt-20">
	<div class="kt-scroll kt-scroll--pull">
		<div class="kt-widget__items" id="leads_list">
			<?php 
			   if(!empty($leads)) { 
                  foreach($leads as $lead){
			?>
			<div class="kt-widget__item">
				<span class="kt-media kt-media--circle">
					<img src="assets/chat/media/user.png" alt="image">
				</span>
				<div class="kt-widget__info">
					<div class="kt-widget__section">
						<a href="javascript::" rel="<?php echo $lead['lead_id'];?>" class="kt-widget__username lead_user" id="lead_user_<?php echo $lead['lead_id'];?>">
							<?php echo $lead['first_name'].' '.$lead['last_name'];?>
						</a>
						<span class="kt-badge kt-badge--success kt-badge--dot all_dots" id="single_dot_<?php echo $lead['lead_id']?>"></span>
					</div>
				</div>
			</div>
            <?php } } ?>
		</div>
	</div>
</div>
</div>
</div>

<!--end::Portlet-->
</div>

<!--End:: App Aside-->

<!--Begin:: App Content-->
<div class="kt-grid__item kt-grid__item--fluid kt-app__content" id="kt_chat_content">
<div class="kt-chat">
<div class="kt-portlet kt-portlet--head-lg- kt-portlet--last">
<div class="kt-portlet__head">
	<div class="kt-chat__head ">
		<div class="kt-chat__left">

			<!--begin:: Aside Mobile Toggle -->
			<button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-md kt-hidden-desktop" id="kt_chat_aside_mobile_toggle">
				<i class="flaticon2-open-text-book"></i>
			</button>

			<!--end:: Aside Mobile Toggle-->
			<div class="dropdown dropdown-inline">
				<button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="flaticon-more-1"></i>
				</button>
				<div class="dropdown-menu dropdown-menu-fit dropdown-menu-left dropdown-menu-md">

					<!--begin::Nav-->
					<ul class="kt-nav">
						<li class="kt-nav__item">
							<a href="#" class="kt-nav__link">
								<i class="kt-nav__link-icon flaticon2-group"></i>
								<span class="kt-nav__link-text">New Group</span>
							</a>
						</li>
						<li class="kt-nav__item">
							<a href="#" class="kt-nav__link">
								<i class="kt-nav__link-icon flaticon2-open-text-book"></i>
								<span class="kt-nav__link-text">Contacts</span>
							</a>
						</li>
						<li class="kt-nav__item">
							<a href="#" class="kt-nav__link">
								<i class="kt-nav__link-icon flaticon2-rocket-1"></i>
								<span class="kt-nav__link-text">Groups</span>
								<span class="kt-nav__link-badge">
									<span class="kt-badge kt-badge--brand kt-badge--inline">new</span>
								</span>
							</a>
						</li>
						<li class="kt-nav__item">
							<a href="#" class="kt-nav__link">
								<i class="kt-nav__link-icon flaticon2-bell-2"></i>
								<span class="kt-nav__link-text">Calls</span>
							</a>
						</li>
						<li class="kt-nav__item">
							<a href="#" class="kt-nav__link">
								<i class="kt-nav__link-icon flaticon2-dashboard"></i>
								<span class="kt-nav__link-text">Settings</span>
							</a>
						</li>
						<li class="kt-nav__separator"></li>
						<li class="kt-nav__item">
							<a href="#" class="kt-nav__link">
								<i class="kt-nav__link-icon flaticon2-protected"></i>
								<span class="kt-nav__link-text">Help</span>
							</a>
						</li>
						<li class="kt-nav__item">
							<a href="#" class="kt-nav__link">
								<i class="kt-nav__link-icon flaticon2-bell-2"></i>
								<span class="kt-nav__link-text">Privacy</span>
							</a>
						</li>
					</ul>

					<!--end::Nav-->
				</div>
			</div>
		</div>
		<!-- <div class="kt-chat__center">
			<div class="kt-chat__label kt-hidden">
				<a href="#" class="kt-chat__title">Jason Muller</a>
				<span class="kt-chat__status">
					<span class="kt-badge kt-badge--dot kt-badge--success"></span> Active
				</span>
			</div>
			<div class="kt-chat__pic">
				<span class="kt-media kt-media--sm kt-media--circle" data-toggle="kt-tooltip" data-placement="top" title="Jason Muller" data-original-title="Tooltip title">
					<img src="assets/chat/media/users/300_12.jpg" alt="image">
				</span>
				<span class="kt-media kt-media--sm kt-media--circle" data-toggle="kt-tooltip" data-placement="top" title="Nick Bold" data-original-title="Tooltip title">
					<img src="assets/chat/media/users/300_11.jpg" alt="image">
				</span>
				<span class="kt-media kt-media--sm kt-media--circle" data-toggle="kt-tooltip" data-placement="top" title="Milano Esco" data-original-title="Tooltip title">
					<img src="assets/chat/media/users/100_14.jpg" alt="image">
				</span>
				<span class="kt-media kt-media--sm kt-media--circle" data-toggle="kt-tooltip" data-placement="top" title="Teresa Fox" data-original-title="Tooltip title">
					<img src="assets/chat/media/users/100_4.jpg" alt="image">
				</span>
			</div>
		</div> -->
		<div class="kt-chat__right">
			<div class="dropdown dropdown-inline">
				<button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="flaticon2-add-1"></i>
				</button>
				<div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-md">

					<!--begin::Nav-->
					<ul class="kt-nav">
						<li class="kt-nav__head">
							Messaging
							<i class="flaticon2-information" data-toggle="kt-tooltip" data-placement="right" title="Click to learn more..."></i>
						</li>
						<li class="kt-nav__separator"></li>
						<li class="kt-nav__item">
							<a href="#" class="kt-nav__link">
								<i class="kt-nav__link-icon flaticon2-group"></i>
								<span class="kt-nav__link-text">New Group</span>
							</a>
						</li>
						<li class="kt-nav__item">
							<a href="#" class="kt-nav__link">
								<i class="kt-nav__link-icon flaticon2-open-text-book"></i>
								<span class="kt-nav__link-text">Contacts</span>
								<span class="kt-nav__link-badge">
									<span class="kt-badge kt-badge--brand  kt-badge--rounded-">5</span>
								</span>
							</a>
						</li>
						<li class="kt-nav__item">
							<a href="#" class="kt-nav__link">
								<i class="kt-nav__link-icon flaticon2-bell-2"></i>
								<span class="kt-nav__link-text">Calls</span>
							</a>
						</li>
						<li class="kt-nav__item">
							<a href="#" class="kt-nav__link">
								<i class="kt-nav__link-icon flaticon2-dashboard"></i>
								<span class="kt-nav__link-text">Settings</span>
							</a>
						</li>
						<li class="kt-nav__item">
							<a href="#" class="kt-nav__link">
								<i class="kt-nav__link-icon flaticon2-protected"></i>
								<span class="kt-nav__link-text">Help</span>
							</a>
						</li>
						<li class="kt-nav__separator"></li>
						<li class="kt-nav__foot">
							<a class="btn btn-label-brand btn-bold btn-sm" href="#">Upgrade plan</a>
							<a class="btn btn-clean btn-bold btn-sm" href="#" data-toggle="kt-tooltip" data-placement="right" title="Click to learn more...">Learn more</a>
						</li>
					</ul>

					<!--end::Nav-->
				</div>
			</div>
		</div>
	</div>
</div>
<div class="kt-portlet__body">
	<div class="kt-scroll kt-scroll--pull" data-mobile-height="300">
		<div class="kt-chat__messages" id="chat_box">
            <?php 
			   if(!empty($chat)){ 
                  foreach($chat as $row){
                  if($row['msg_sent']==0 && $row['msg_incoming']==1){
			?>
			<div class="kt-chat__message">
				<div class="kt-chat__user">
					<span class="kt-media kt-media--circle kt-media--sm">
						<img src="assets/chat/media/user.png" alt="image">
					</span>
					<a href="javascript::" class="kt-chat__username">
						<?php echo $row['first_name'].' '.$row['last_name'];?>
					</a>
					<span class="kt-chat__datetime"><?php echo $row['msg_stamp'];?></span>
				</div>
				<div class="kt-chat__text kt-bg-light-success">
					<?php echo $row['msg_content'];?>
				</div>
			</div>
            <?php 
               } 
               if($row['msg_sent']==1 && $row['msg_incoming']==0){
            ?>
			<div class="kt-chat__message kt-chat__message--right">
				<div class="kt-chat__user">
					<span class="kt-chat__datetime"><?php echo $row['msg_stamp'];?></span>
					<a href="javascript::" class="kt-chat__username">You</a>
					<span class="kt-media kt-media--circle kt-media--sm">
						<img src="assets/chat/media/user.png" alt="image">
					</span>
				</div>
				<div class="kt-chat__text kt-bg-light-brand">
					<?php echo $row['msg_content'];?>
				</div>
			</div>
            <?php } } } ?>
		</div>
	</div>
</div>
<div class="kt-portlet__foot">
	<div class="kt-chat__input">
		<input type="hidden" name="lead_id" id="lead_id" value="<?php echo (isset($lead_id))?$lead_id:''; ?>">
        <form id="message_form">
        	<input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" class="csrf_token" />
			<div class="kt-chat__editor">
			<textarea style="height: 50px" placeholder="Type here..." name="msg_content"></textarea>
			</div>
			<div class="kt-chat__toolbar">
				<!-- <div class="kt_chat__tools">
					<a href="#"><i class="flaticon2-link"></i></a>
					<a href="#"><i class="flaticon2-photograph"></i></a>
					<a href="#"><i class="flaticon2-photo-camera"></i></a>
				</div> -->
				<div class="kt_chat__actions">
					<button type="button" class="btn btn-brand btn-md btn-upper btn-bold" id="reply_button">reply</button>
					<h5 id="plz_wait">Please Wait</h5>
				</div>
			</div>
        </form>
	</div>
</div>
</div>
</div>
</div>

<!--End:: App Content-->
</div>

<!--End::App-->
</div>

<!-- end:: Content -->
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('.all_dots').hide();
	var lead_id = '<?php echo (isset($lead_id))?$lead_id:'';?>';
	$('#single_dot_'+lead_id).fadeIn();
	$('#lead_user_'+lead_id).css({"color":"#B269AE"});
});
</script>
<script src="assets/developer_js/my_chat.js" type="text/javascript"></script>