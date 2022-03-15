<!DOCTYPE html>
<html lang="en">
<!-- begin::Head -->
<head>
<base href="<?php echo base_url(); ?>">
<meta charset="utf-8" />
<title>Debt Monster | Messaging</title>
<meta name="description" content="Group chat example">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!--begin::Fonts -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700">

<!--end::Fonts -->

<!--begin::Global Theme Styles(used by all pages) -->
<link rel="stylesheet" href="assets/css/bootstrap.min.css" />
<link href="assets/public/css/bootstrap-theme.min.css" rel="stylesheet">

<link href="assets/chat/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
<link href="assets/chat/css/style.bundle.css" rel="stylesheet" type="text/css" />

<!--end::Global Theme Styles -->

<!--begin::Layout Skins(used by all pages) -->
<link href="assets/chat/css/skins/header/base/light.css" rel="stylesheet" type="text/css" />
<link href="assets/chat/css/skins/header/menu/light.css" rel="stylesheet" type="text/css" />
<link href="assets/chat/css/skins/brand/dark.css" rel="stylesheet" type="text/css" />
<link href="assets/chat/css/skins/aside/dark.css" rel="stylesheet" type="text/css" />

<!--end::Layout Skins -->
<link rel="shortcut icon" href="assets/chat/media/logos/favicon.png" />

<link rel="stylesheet" type="text/css" href="assets/sweetalerts/sweetalert2.scss">
<link href="assets/sweetalerts/sweetalert.css" rel="stylesheet" type="text/css" />

<link href="assets/datatables/datatables.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/datatables/dataTables.customLoader.walker.css" rel="stylesheet" type="text/css"/>
<link href="assets/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css"/>

<link rel="stylesheet" href="assets/font-awesome/4.5.0/css/font-awesome.min.css" />

<script type="text/javascript">
    var base_url = '<?php echo base_url();?>';
</script>
<script src="assets/js/jquery-2.1.4.min.js"></script>

</head>

<!-- end::Head -->

<!-- begin::Body -->
<body class="kt-app__aside--left kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-aside--enabled kt-aside--fixed kt-page--loading">

<!-- begin:: Page -->

<!-- begin:: Header Mobile -->
<div id="kt_header_mobile" class="kt-header-mobile  kt-header-mobile--fixed ">
<div class="kt-header-mobile__logo">
<a href="index.html">
<img alt="Logo" src="assets/chat/media/logos/logo-light.png" />
</a>
</div>
<div class="kt-header-mobile__toolbar">
<button class="kt-header-mobile__toggler kt-header-mobile__toggler--left" id="kt_aside_mobile_toggler"><span></span></button>
<button class="kt-header-mobile__toggler" id="kt_header_mobile_toggler"><span></span></button>
<button class="kt-header-mobile__topbar-toggler" id="kt_header_mobile_topbar_toggler"><i class="flaticon-more"></i></button>
</div>
</div>

<!-- end:: Header Mobile -->
<div class="kt-grid kt-grid--hor kt-grid--root">
<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">

<!-- begin:: Aside -->

<!-- Uncomment this to display the close button of the panel
<button class="kt-aside-close " id="kt_aside_close_btn"><i class="la la-close"></i></button>
-->
<div class="kt-aside  kt-aside--fixed  kt-grid__item kt-grid kt-grid--desktop kt-grid--hor-desktop" id="kt_aside">

<!-- begin:: Aside -->
<div class="kt-aside__brand kt-grid__item " id="kt_aside_brand">
<div class="kt-aside__brand-logo">
<a href="index.html">
<img alt="Logo" src="assets/chat/media/logos/logo-dm.png" />
</a>
</div>
<div class="kt-aside__brand-tools">
<button class="kt-aside__brand-aside-toggler" id="kt_aside_toggler">
<span><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
<polygon points="0 0 24 0 24 24 0 24" />
<path d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z" fill="#000000" fill-rule="nonzero" transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999) " />
<path d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999) " />
</g>
</svg></span>
<span><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
<polygon points="0 0 24 0 24 24 0 24" />
<path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero" />
<path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) " />
</g>
</svg></span>
</button>

<!--
<button class="kt-aside__brand-aside-toggler kt-aside__brand-aside-toggler--left" id="kt_aside_toggler"><span></span></button>
-->
</div>
</div>

<!-- end:: Aside -->

<!-- begin:: Aside Menu -->
<div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">
<div id="kt_aside_menu" class="kt-aside-menu " data-ktmenu-vertical="1" data-ktmenu-scroll="1" data-ktmenu-dropdown-timeout="500">
<ul class="kt-menu__nav ">

<li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--open kt-menu__item--here" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle"><span class="kt-menu__link-icon"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
	<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
		<rect x="0" y="0" width="24" height="24" />
		<rect fill="#000000" x="4" y="4" width="7" height="7" rx="1.5" />
		<path d="M5.5,13 L9.5,13 C10.3284271,13 11,13.6715729 11,14.5 L11,18.5 C11,19.3284271 10.3284271,20 9.5,20 L5.5,20 C4.67157288,20 4,19.3284271 4,18.5 L4,14.5 C4,13.6715729 4.67157288,13 5.5,13 Z M14.5,4 L18.5,4 C19.3284271,4 20,4.67157288 20,5.5 L20,9.5 C20,10.3284271 19.3284271,11 18.5,11 L14.5,11 C13.6715729,11 13,10.3284271 13,9.5 L13,5.5 C13,4.67157288 13.6715729,4 14.5,4 Z M14.5,13 L18.5,13 C19.3284271,13 20,13.6715729 20,14.5 L20,18.5 C20,19.3284271 19.3284271,20 18.5,20 L14.5,20 C13.6715729,20 13,19.3284271 13,18.5 L13,14.5 C13,13.6715729 13.6715729,13 14.5,13 Z" fill="#000000" opacity="0.3" />
	</g>
</svg></span><span class="kt-menu__link-text">Application</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
<div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
<ul class="kt-menu__subnav">
<li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">Applications</span></span></li>
	<li class="kt-menu__item " aria-haspopup="true"><a href="inbox.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Inbox</span></a></li>
	<li class="kt-menu__item " aria-haspopup="true"><a href="<?php echo base_url('keywords/');?>" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Keywords</span></a></li>
<li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="javascript:;" class="kt-menu__link kt-menu__toggle kt-menu__item--active"><i class="kt-menu__link-bullet kt-menu__link-bullet--line"><span></span></i><span class="kt-menu__link-text">Chat</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
	<div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
		<ul class="kt-menu__subnav">
			<li class="kt-menu__item " aria-haspopup="true"><a href="chat/private.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Private</span></a></li>
			<li class="kt-menu__item kt-menu__item--active" aria-haspopup="true"><a href="chat/group.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Group</span></a></li>
			<li class="kt-menu__item " aria-haspopup="true"><a href="chat/popup.html" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Popup</span></a></li>
		</ul>
	</div>
</li>

</ul>
</div>
</li>

</ul>
</div>
</div>

<!-- end:: Aside Menu -->
</div>

<!-- end:: Aside -->

<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">

<!-- begin:: Header -->
<div id="kt_header" class="kt-header kt-grid__item  kt-header--fixed ">

<!-- begin:: Header Menu -->

<!-- Uncomment this to display the close button of the panel
<button class="kt-header-menu-wrapper-close" id="kt_header_menu_mobile_close_btn"><i class="la la-close"></i></button>
-->
<div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper">
<div id="kt_header_menu" class="kt-header-menu kt-header-menu-mobile  kt-header-menu--layout-default ">
<ul class="kt-menu__nav ">
<li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel kt-menu__item--active" data-ktmenu-submenu-toggle="click" aria-haspopup="true"><h3 class="kt-menu__link-text">Debt Monster Messaging (Chat)</h3>
</li>
						</ul>
</div>
</div>

<!-- end:: Header Menu -->


</div>

<!-- end:: Header -->

{_yield}

<!-- begin:: Footer -->
<div class="kt-footer  kt-grid__item kt-grid kt-grid--desktop kt-grid--ver-desktop" id="kt_footer">
<div class="kt-container  kt-container--fluid ">
<div class="kt-footer__copyright">
2020&nbsp;&copy;&nbsp;<a href="#" target="_blank" class="kt-link">Debt Monster</a>
</div>
<div class="kt-footer__menu">
<a href="#">About</a>
<a href="#" target="_blank" class="kt-footer__menu-link kt-link">Terms</a>
<a href="#" target="_blank" class="kt-footer__menu-link kt-link">Contact</a>
</div>
</div>
</div>

<!-- end:: Footer -->			</div>
</div>
</div>

<!-- end:: Page -->

<!-- begin::Quick Panel -->
<div id="kt_quick_panel" class="kt-quick-panel">
<a href="#" class="kt-quick-panel__close" id="kt_quick_panel_close_btn"><i class="flaticon2-delete"></i></a>
<div class="kt-quick-panel__nav">
<ul class="nav nav-tabs nav-tabs-line nav-tabs-bold nav-tabs-line-3x nav-tabs-line-brand  kt-notification-item-padding-x" role="tablist">
<li class="nav-item active">
<a class="nav-link active" data-toggle="tab" href="#kt_quick_panel_tab_notifications" role="tab">Notifications</a>
</li>
<li class="nav-item">
<a class="nav-link" data-toggle="tab" href="#kt_quick_panel_tab_logs" role="tab">Audit Logs</a>
</li>
<li class="nav-item">
<a class="nav-link" data-toggle="tab" href="#kt_quick_panel_tab_settings" role="tab">Settings</a>
</li>
</ul>
</div>
<div class="kt-quick-panel__content">
<div class="tab-content">
<div class="tab-pane fade show kt-scroll active" id="kt_quick_panel_tab_notifications" role="tabpanel">
<div class="kt-notification">
<a href="#" class="kt-notification__item">
<div class="kt-notification__item-icon">
<i class="flaticon2-line-chart kt-font-success"></i>
</div>
<div class="kt-notification__item-details">
<div class="kt-notification__item-title">
New order has been received
</div>
<div class="kt-notification__item-time">
2 hrs ago
</div>
</div>
</a>
<a href="#" class="kt-notification__item">
<div class="kt-notification__item-icon">
<i class="flaticon2-box-1 kt-font-brand"></i>
</div>
<div class="kt-notification__item-details">
<div class="kt-notification__item-title">
New customer is registered
</div>
<div class="kt-notification__item-time">
3 hrs ago
</div>
</div>
</a>
<a href="#" class="kt-notification__item">
<div class="kt-notification__item-icon">
<i class="flaticon2-chart2 kt-font-danger"></i>
</div>
<div class="kt-notification__item-details">
<div class="kt-notification__item-title">
Application has been approved
</div>
<div class="kt-notification__item-time">
3 hrs ago
</div>
</div>
</a>
<a href="#" class="kt-notification__item">
<div class="kt-notification__item-icon">
<i class="flaticon2-image-file kt-font-warning"></i>
</div>
<div class="kt-notification__item-details">
<div class="kt-notification__item-title">
New file has been uploaded
</div>
<div class="kt-notification__item-time">
5 hrs ago
</div>
</div>
</a>
<a href="#" class="kt-notification__item">
<div class="kt-notification__item-icon">
<i class="flaticon2-drop kt-font-info"></i>
</div>
<div class="kt-notification__item-details">
<div class="kt-notification__item-title">
New user feedback received
</div>
<div class="kt-notification__item-time">
8 hrs ago
</div>
</div>
</a>
<a href="#" class="kt-notification__item">
<div class="kt-notification__item-icon">
<i class="flaticon2-pie-chart-2 kt-font-success"></i>
</div>
<div class="kt-notification__item-details">
<div class="kt-notification__item-title">
System reboot has been successfully completed
</div>
<div class="kt-notification__item-time">
12 hrs ago
</div>
</div>
</a>
<a href="#" class="kt-notification__item">
<div class="kt-notification__item-icon">
<i class="flaticon2-favourite kt-font-danger"></i>
</div>
<div class="kt-notification__item-details">
<div class="kt-notification__item-title">
New order has been placed
</div>
<div class="kt-notification__item-time">
15 hrs ago
</div>
</div>
</a>
<a href="#" class="kt-notification__item kt-notification__item--read">
<div class="kt-notification__item-icon">
<i class="flaticon2-safe kt-font-primary"></i>
</div>
<div class="kt-notification__item-details">
<div class="kt-notification__item-title">
Company meeting canceled
</div>
<div class="kt-notification__item-time">
19 hrs ago
</div>
</div>
</a>
<a href="#" class="kt-notification__item">
<div class="kt-notification__item-icon">
<i class="flaticon2-psd kt-font-success"></i>
</div>
<div class="kt-notification__item-details">
<div class="kt-notification__item-title">
New report has been received
</div>
<div class="kt-notification__item-time">
23 hrs ago
</div>
</div>
</a>
<a href="#" class="kt-notification__item">
<div class="kt-notification__item-icon">
<i class="flaticon-download-1 kt-font-danger"></i>
</div>
<div class="kt-notification__item-details">
<div class="kt-notification__item-title">
Finance report has been generated
</div>
<div class="kt-notification__item-time">
25 hrs ago
</div>
</div>
</a>
<a href="#" class="kt-notification__item">
<div class="kt-notification__item-icon">
<i class="flaticon-security kt-font-warning"></i>
</div>
<div class="kt-notification__item-details">
<div class="kt-notification__item-title">
New customer comment recieved
</div>
<div class="kt-notification__item-time">
2 days ago
</div>
</div>
</a>
<a href="#" class="kt-notification__item">
<div class="kt-notification__item-icon">
<i class="flaticon2-pie-chart kt-font-warning"></i>
</div>
<div class="kt-notification__item-details">
<div class="kt-notification__item-title">
New customer is registered
</div>
<div class="kt-notification__item-time">
3 days ago
</div>
</div>
</a>
</div>
</div>
<div class="tab-pane fade kt-scroll" id="kt_quick_panel_tab_logs" role="tabpanel">
<div class="kt-notification-v2">
<a href="#" class="kt-notification-v2__item">
<div class="kt-notification-v2__item-icon">
<i class="flaticon-bell kt-font-brand"></i>
</div>
<div class="kt-notification-v2__itek-wrapper">
<div class="kt-notification-v2__item-title">
5 new user generated report
</div>
<div class="kt-notification-v2__item-desc">
Reports based on sales
</div>
</div>
</a>
<a href="#" class="kt-notification-v2__item">
<div class="kt-notification-v2__item-icon">
<i class="flaticon2-box kt-font-danger"></i>
</div>
<div class="kt-notification-v2__itek-wrapper">
<div class="kt-notification-v2__item-title">
2 new items submited
</div>
<div class="kt-notification-v2__item-desc">
by Grog John
</div>
</div>
</a>
<a href="#" class="kt-notification-v2__item">
<div class="kt-notification-v2__item-icon">
<i class="flaticon-psd kt-font-brand"></i>
</div>
<div class="kt-notification-v2__itek-wrapper">
<div class="kt-notification-v2__item-title">
79 PSD files generated
</div>
<div class="kt-notification-v2__item-desc">
Reports based on sales
</div>
</div>
</a>
<a href="#" class="kt-notification-v2__item">
<div class="kt-notification-v2__item-icon">
<i class="flaticon2-supermarket kt-font-warning"></i>
</div>
<div class="kt-notification-v2__itek-wrapper">
<div class="kt-notification-v2__item-title">
$2900 worth producucts sold
</div>
<div class="kt-notification-v2__item-desc">
Total 234 items
</div>
</div>
</a>
<a href="#" class="kt-notification-v2__item">
<div class="kt-notification-v2__item-icon">
<i class="flaticon-paper-plane-1 kt-font-success"></i>
</div>
<div class="kt-notification-v2__itek-wrapper">
<div class="kt-notification-v2__item-title">
4.5h-avarage response time
</div>
<div class="kt-notification-v2__item-desc">
Fostest is Barry
</div>
</div>
</a>
<a href="#" class="kt-notification-v2__item">
<div class="kt-notification-v2__item-icon">
<i class="flaticon2-information kt-font-danger"></i>
</div>
<div class="kt-notification-v2__itek-wrapper">
<div class="kt-notification-v2__item-title">
Database server is down
</div>
<div class="kt-notification-v2__item-desc">
10 mins ago
</div>
</div>
</a>
<a href="#" class="kt-notification-v2__item">
<div class="kt-notification-v2__item-icon">
<i class="flaticon2-mail-1 kt-font-brand"></i>
</div>
<div class="kt-notification-v2__itek-wrapper">
<div class="kt-notification-v2__item-title">
System report has been generated
</div>
<div class="kt-notification-v2__item-desc">
Fostest is Barry
</div>
</div>
</a>
<a href="#" class="kt-notification-v2__item">
<div class="kt-notification-v2__item-icon">
<i class="flaticon2-hangouts-logo kt-font-warning"></i>
</div>
<div class="kt-notification-v2__itek-wrapper">
<div class="kt-notification-v2__item-title">
4.5h-avarage response time
</div>
<div class="kt-notification-v2__item-desc">
Fostest is Barry
</div>
</div>
</a>
</div>
</div>
<div class="tab-pane kt-quick-panel__content-padding-x fade kt-scroll" id="kt_quick_panel_tab_settings" role="tabpanel">
<form class="kt-form">
<div class="kt-heading kt-heading--sm kt-heading--space-sm">Customer Care</div>
<div class="form-group form-group-xs row">
<label class="col-8 col-form-label">Enable Notifications:</label>
<div class="col-4 kt-align-right">
<span class="kt-switch kt-switch--success kt-switch--sm">
<label>
<input type="checkbox" checked="checked" name="quick_panel_notifications_1">
<span></span>
</label>
</span>
</div>
</div>
<div class="form-group form-group-xs row">
<label class="col-8 col-form-label">Enable Case Tracking:</label>
<div class="col-4 kt-align-right">
<span class="kt-switch kt-switch--success kt-switch--sm">
<label>
<input type="checkbox" name="quick_panel_notifications_2">
<span></span>
</label>
</span>
</div>
</div>
<div class="form-group form-group-last form-group-xs row">
<label class="col-8 col-form-label">Support Portal:</label>
<div class="col-4 kt-align-right">
<span class="kt-switch kt-switch--success kt-switch--sm">
<label>
<input type="checkbox" checked="checked" name="quick_panel_notifications_2">
<span></span>
</label>
</span>
</div>
</div>
<div class="kt-separator kt-separator--space-md kt-separator--border-dashed"></div>
<div class="kt-heading kt-heading--sm kt-heading--space-sm">Reports</div>
<div class="form-group form-group-xs row">
<label class="col-8 col-form-label">Generate Reports:</label>
<div class="col-4 kt-align-right">
<span class="kt-switch kt-switch--sm kt-switch--danger">
<label>
<input type="checkbox" checked="checked" name="quick_panel_notifications_3">
<span></span>
</label>
</span>
</div>
</div>
<div class="form-group form-group-xs row">
<label class="col-8 col-form-label">Enable Report Export:</label>
<div class="col-4 kt-align-right">
<span class="kt-switch kt-switch--sm kt-switch--danger">
<label>
<input type="checkbox" name="quick_panel_notifications_3">
<span></span>
</label>
</span>
</div>
</div>
<div class="form-group form-group-last form-group-xs row">
<label class="col-8 col-form-label">Allow Data Collection:</label>
<div class="col-4 kt-align-right">
<span class="kt-switch kt-switch--sm kt-switch--danger">
<label>
<input type="checkbox" checked="checked" name="quick_panel_notifications_4">
<span></span>
</label>
</span>
</div>
</div>
<div class="kt-separator kt-separator--space-md kt-separator--border-dashed"></div>
<div class="kt-heading kt-heading--sm kt-heading--space-sm">Memebers</div>
<div class="form-group form-group-xs row">
<label class="col-8 col-form-label">Enable Member singup:</label>
<div class="col-4 kt-align-right">
<span class="kt-switch kt-switch--sm kt-switch--brand">
<label>
<input type="checkbox" checked="checked" name="quick_panel_notifications_5">
<span></span>
</label>
</span>
</div>
</div>
<div class="form-group form-group-xs row">
<label class="col-8 col-form-label">Allow User Feedbacks:</label>
<div class="col-4 kt-align-right">
<span class="kt-switch kt-switch--sm kt-switch--brand">
<label>
<input type="checkbox" name="quick_panel_notifications_5">
<span></span>
</label>
</span>
</div>
</div>
<div class="form-group form-group-last form-group-xs row">
<label class="col-8 col-form-label">Enable Customer Portal:</label>
<div class="col-4 kt-align-right">
<span class="kt-switch kt-switch--sm kt-switch--brand">
<label>
<input type="checkbox" checked="checked" name="quick_panel_notifications_6">
<span></span>
</label>
</span>
</div>
</div>
</form>
</div>
</div>
</div>
</div>

<!-- end::Quick Panel -->

<!-- begin::Scrolltop -->
<div id="kt_scrolltop" class="kt-scrolltop">
<i class="fa fa-arrow-up"></i>
</div>

<!-- end::Scrolltop -->


<!--Begin:: Chat-->
<div class="modal fade- modal-sticky-bottom-right" id="kt_chat_modal" role="dialog" data-backdrop="false">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="kt-chat">
<div class="kt-portlet kt-portlet--last">
<div class="kt-portlet__head">
<div class="kt-chat__head ">
<div class="kt-chat__left">
<div class="kt-chat__label">
<a href="#" class="kt-chat__title">Jason Muller</a>
<span class="kt-chat__status">
	<span class="kt-badge kt-badge--dot kt-badge--success"></span> Active
</span>
</div>
</div>
<div class="kt-chat__right">
<div class="dropdown dropdown-inline">
<button type="button" class="btn btn-clean btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	<i class="flaticon-more-1"></i>
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
<button type="button" class="btn btn-clean btn-sm btn-icon" data-dismiss="modal">
<i class="flaticon2-cross"></i>
</button>
</div>
</div>
</div>
<div class="kt-portlet__body">
<div class="kt-scroll kt-scroll--pull" data-height="410" data-mobile-height="225">
<div class="kt-chat__messages kt-chat__messages--solid">
<div class="kt-chat__message kt-chat__message--success">
<div class="kt-chat__user">
	<span class="kt-media kt-media--circle kt-media--sm">
		<img src="assets/chat/media/users/100_12.jpg" alt="image">
	</span>
	<a href="#" class="kt-chat__username">Jason Muller</span></a>
	<span class="kt-chat__datetime">2 Hours</span>
</div>
<div class="kt-chat__text">
	How likely are you to recommend our company<br> to your friends and family?
</div>
</div>
<div class="kt-chat__message kt-chat__message--right kt-chat__message--brand">
<div class="kt-chat__user">
	<span class="kt-chat__datetime">30 Seconds</span>
	<a href="#" class="kt-chat__username">You</span></a>
	<span class="kt-media kt-media--circle kt-media--sm">
		<img src="assets/chat/media/users/300_21.jpg" alt="image">
	</span>
</div>
<div class="kt-chat__text">
	Hey there, we’re just writing to let you know that you’ve<br> been subscribed to a repository on GitHub.
</div>
</div>
<div class="kt-chat__message kt-chat__message--success">
<div class="kt-chat__user">
	<span class="kt-media kt-media--circle kt-media--sm">
		<img src="assets/chat/media/users/100_12.jpg" alt="image">
	</span>
	<a href="#" class="kt-chat__username">Jason Muller</span></a>
	<span class="kt-chat__datetime">30 Seconds</span>
</div>
<div class="kt-chat__text">
	Ok, Understood!
</div>
</div>
<div class="kt-chat__message kt-chat__message--right kt-chat__message--brand">
<div class="kt-chat__user">
	<span class="kt-chat__datetime">Just Now</span>
	<a href="#" class="kt-chat__username">You</span></a>
	<span class="kt-media kt-media--circle kt-media--sm">
		<img src="assets/chat/media/users/300_21.jpg" alt="image">
	</span>
</div>
<div class="kt-chat__text">
	You’ll receive notifications for all issues, pull requests!
</div>
</div>
<div class="kt-chat__message kt-chat__message--success">
<div class="kt-chat__user">
	<span class="kt-media kt-media--circle kt-media--sm">
		<img src="assets/chat/media/users/100_12.jpg" alt="image">
	</span>
	<a href="#" class="kt-chat__username">Jason Muller</span></a>
	<span class="kt-chat__datetime">2 Hours</span>
</div>
<div class="kt-chat__text">
	You were automatically <b class="kt-font-brand">subscribed</b> <br>because you’ve been given access to the repository
</div>
</div>
<div class="kt-chat__message kt-chat__message--right kt-chat__message--brand">
<div class="kt-chat__user">
	<span class="kt-chat__datetime">30 Seconds</span>
	<a href="#" class="kt-chat__username">You</span></a>
	<span class="kt-media kt-media--circle kt-media--sm">
		<img src="assets/chat/media/users/300_21.jpg" alt="image">
	</span>
</div>
<div class="kt-chat__text">
	You can unwatch this repository immediately <br>by clicking here: <a href="#" class="kt-font-bold kt-link"></a>
</div>
</div>
<div class="kt-chat__message kt-chat__message--success">
<div class="kt-chat__user">
	<span class="kt-media kt-media--circle kt-media--sm">
		<img src="assets/chat/media/users/100_12.jpg" alt="image">
	</span>
	<a href="#" class="kt-chat__username">Jason Muller</span></a>
	<span class="kt-chat__datetime">30 Seconds</span>
</div>
<div class="kt-chat__text">
	Discover what students who viewed Learn <br>Figma - UI/UX Design Essential Training also viewed
</div>
</div>
<div class="kt-chat__message kt-chat__message--right kt-chat__message--brand">
<div class="kt-chat__user">
	<span class="kt-chat__datetime">Just Now</span>
	<a href="#" class="kt-chat__username">You</span></a>
	<span class="kt-media kt-media--circle kt-media--sm">
		<img src="assets/chat/media/users/300_21.jpg" alt="image">
	</span>
</div>
<div class="kt-chat__text">
	Most purchased Business courses during this sale!
</div>
</div>
</div>
</div>
</div>
<div class="kt-portlet__foot">
<div class="kt-chat__input">
<div class="kt-chat__editor">
<textarea placeholder="Type here..." style="height: 50px"></textarea>
</div>
<div class="kt-chat__toolbar">
<div class="kt_chat__tools">
<a href="#"><i class="flaticon2-link"></i></a>
<a href="#"><i class="flaticon2-photograph"></i></a>
<a href="#"><i class="flaticon2-photo-camera"></i></a>
</div>
<div class="kt_chat__actions">
<button type="button" class="btn btn-brand btn-md  btn-font-sm btn-upper btn-bold kt-chat__reply">reply</button>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

<!--ENd:: Chat-->

<!-- begin::Global Config(global config for global JS sciprts) -->
<script>
var KTAppOptions = {
"colors": {
"state": {
"brand": "#5d78ff",
"dark": "#282a3c",
"light": "#ffffff",
"primary": "#5867dd",
"success": "#34bfa3",
"info": "#36a3f7",
"warning": "#ffb822",
"danger": "#fd3995"
},
"base": {
"label": [
"#c5cbe3",
"#a1a8c3",
"#3d4465",
"#3e4466"
],
"shape": [
"#f0f3ff",
"#d9dffa",
"#afb4d4",
"#646c9a"
]
}
}
};
</script>

<!-- end::Global Config -->

<!--begin::Global Theme Bundle(used by all pages) -->
<script src="assets/public/js/bootstrap.min.js"></script>
<script src="assets/js/jquery-ui.custom.min.js"></script>

<script src="assets/chat/plugins/global/plugins.bundle.js" type="text/javascript"></script>
<script src="assets/chat/js/scripts.bundle.js" type="text/javascript"></script>

<!--end::Global Theme Bundle -->

<!--begin::Page Scripts(used by this page) -->
<script src="assets/chat/js/pages/custom/chat/chat.js" type="text/javascript"></script>

<script src="assets/datatables/datatables.min.js" type="text/javascript"></script>
<script src="assets/datatables/datatables.all.min.js" type="text/javascript"></script>
<script src="assets/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>

<script src="assets/sweetalerts/sweetalert2.js"></script>
<script src="assets/sweetalerts/sweetalert.min.js" type="text/javascript"></script>
<script src="assets/sweetalerts/ui-sweetalert.min.js" type="text/javascript"></script>

<script src="assets/developer_js/sfs_functions.js" type="text/javascript"></script>
<script type="text/javascript" src="assets/bootbox/bootbox.min.js"></script>
    
<!--end::Page Scripts -->
</body>

<!-- end::Body -->
</html>