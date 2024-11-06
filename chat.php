<?php
/**
 * Edit user administration panel.
 *
 * @package WordPress
 * @subpackage Administration
 */

/** WordPress Administration Bootstrap */
require_once __DIR__ . '/admin.php';
global $wpdb;
	require_once dirname( __DIR__ ) . '/wp-load.php';

	$wp_chat_data = $wpdb->get_results( "SELECT * FROM wp_chat_setting" );
/*print_r($wp_chat_data);*/
$chat_value_data = unserialize($wp_chat_data[1]->chat_value);
// print_r($chat_value_data);
$holiday_data = unserialize($wp_chat_data[0]->chat_value);
// print_r($holiday_data);

// logging($holiday_data);
/*echo "<pre>";
print_r($chat_value_data);
echo "</pre>";*/
/*
if ( current_user_can( 'edit_users' ) && ! is_user_admin() ) {
	$parent_file = 'users.php';
} else {
	$parent_file = 'profile.php';
}*/

$profile_help = '<p>' . __( 'Your profile contains information about you (your &#8220;account&#8221;) as well as some personal options related to using WordPress.' ) . '</p>' .
	'<p>' . __( 'You can change your password, turn on keyboard shortcuts, change the color scheme of your WordPress administration screens, and turn off the WYSIWYG (Visual) editor, among other things. You can hide the Toolbar (formerly called the Admin Bar) from the front end of your site, however it cannot be disabled on the admin screens.' ) . '</p>' .
	'<p>' . __( 'You can select the language you wish to use while using the WordPress administration screen without affecting the language site visitors see.' ) . '</p>' .
	'<p>' . __( 'Your username cannot be changed, but you can use other fields to enter your real name or a nickname, and change which name to display on your posts.' ) . '</p>' .
	'<p>' . __( 'You can log out of other devices, such as your phone or a public computer, by clicking the Log Out Everywhere Else button.' ) . '</p>' .
	'<p>' . __( 'Required fields are indicated; the rest are optional. Profile information will only be displayed if your theme is set up to do so.' ) . '</p>' .
	'<p>' . __( 'Remember to click the Update Profile button when you are finished.' ) . '</p>';

get_current_screen()->add_help_tab(
	array(
		'id'      => 'overview',
		'title'   => __( 'Chat' ),
		'content' => $profile_help,
	)
);

get_current_screen()->set_help_sidebar(
	'<p><strong>' . __( 'For more information:' ) . '</strong></p>' .
	'<p>' . __( '<a href="https://wordpress.org/support/article/users-your-profile-screen/">Documentation on User Profiles</a>' ) . '</p>' .
	'<p>' . __( '<a href="https://wordpress.org/support/">Support</a>' ) . '</p>'
);

$user_can_edit = current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages' );

/**
 * Filters whether to allow administrators on Multisite to edit every user.
 *
 * Enabling the user editing form via this filter also hinges on the user holding
 * the 'manage_network_users' cap, and the logged-in user not matching the user
 * profile open for editing.
 *
 * The filter was introduced to replace the EDIT_ANY_USER constant.
 *
 * @since 3.0.0
 *
 * @param bool $allow Whether to allow editing of any user. Default true.
 */
if ( is_multisite()
	&& ! current_user_can( 'manage_network_users' )
	&& $user_id !== $current_user->ID
	&& ! apply_filters( 'enable_edit_any_user_configuration', true )
) {
	wp_die( __( 'Sorry, you are not allowed to edit this user.' ) );
}
		require_once ABSPATH . 'wp-admin/admin-header.php';
		?>


		<?php if ( isset( $_GET['updated'] ) ) : ?>
			<div id="message" class="updated notice is-dismissible">
				<?php if ( IS_PROFILE_PAGE ) : ?>
					<p><strong><?php _e( 'Profile updated.' ); ?></strong></p>
				<?php else : ?>
					<p><strong><?php _e( 'User updated.' ); ?></strong></p>
				<?php endif; ?>
				<?php if ( $wp_http_referer && false === strpos( $wp_http_referer, 'user-new.php' ) && ! IS_PROFILE_PAGE ) : ?>
					<p><a href="<?php echo esc_url( wp_validate_redirect( esc_url_raw( $wp_http_referer ), self_admin_url( 'users.php' ) ) ); ?>"><?php _e( '&larr; Go to Users' ); ?></a></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( isset( $_GET['error'] ) ) : ?>
			<div class="notice notice-error">
			<?php if ( 'new-email' === $_GET['error'] ) : ?>
				<p><?php _e( 'Error while saving the new email address. Please try again.' ); ?></p>
			<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( isset( $errors ) && is_wp_error( $errors ) ) : ?>
			<div class="error">
				<p><?php echo implode( "</p>\n<p>", $errors->get_error_messages() ); ?></p>
			</div>
		<?php endif; ?>

<!--				<h2><?php _e( 'Chat & Contact' ); ?></h2>-->
<style type="text/css">
input.toggleCheck {
	    -webkit-appearance: none !important;
    appearance: none !important;
    padding: 11px 24px !important;
    border-radius: 12px !important;
    background: radial-gradient(circle 8px, white 100%, transparent calc(100% + 1px)) #ccc -14px !important;
    transition: 0.3s ease-in-out !important;
}

input.toggleCheck:checked {
  background-color: dodgerBlue !important;
  background-position: 14px !important;
}
input[type=text], textarea {
	width: 80%;
}
.form-table th {    padding: 5px 10px;
}
.form-table td {padding: 5px 10px !important;
}
</style>
<script>
  function disableContactForms(el){
  	var contact_page_status = document.getElementsByName(el.name);
  //	alert(contact_page_status);
  	//alert(el.checked);
  	if(el.checked == false){
  		//alert("1");
    //jQuery("contact_form").checked = "";  
    jQuery("#contact_forms").removeAttr("checked"); 
    jQuery("#contact_forms").attr("disabled","true"); 
    jQuery("#contact_addresses").removeAttr("checked"); 
    jQuery("#contact_addresses").attr("disabled","true");
} else {
	//alert("2"); 
    jQuery("#contact_forms").removeAttr("disabled");
    jQuery("#contact_addresses").removeAttr("disabled");
}
  /*if (el.checked == "false") {
    //document.getElementsByName("contact_form").removeAttr("disabled");
    document.getElementsByName("contact_form")[0].disabled = true;
  } else {
    document.getElementsByName("contact_form")[0].disabled = false;
    //document.getElementsByName("contact_form").removeAttr("checked");
  }*/
  }
  	</script>
			<form id="chatForm" action="#" method="post">
				<table class="form-table" role="presentation" style="width:auto;">
					<tr class="user-nickname-wrap">
						<th colspan="2" style="border: 1px solid;padding: 5px 10px;"><label for="nickname" style="font-size: 18px;"><?php _e( 'Contact Settings' ); ?></label></th>
					</tr>
					<tr class="user-nickname-wrap">
						<td style="padding:10px 10px 10px 10px;border-left: 1px solid black;border-right: 1px solid black;" colspan="2"><label for="nickname" style="font-size: 15px;font-weight:600;color:#1d2327;"><?php _e( 'Enable Contact Page' ); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <?php
                                                                if($chat_value_data['contact_page'] == "on") {
                                                        ?>

                                                                  <input type="checkbox" class="toggleCheck" name="contact_page" checked="checked" style="padding-top:10px !important" onclick="disableContactForms(this)">
                                                        <?php
                                                                } else {
                                                        ?>
                                                                  <input type="checkbox" class="toggleCheck" name="contact_page" style="padding-top:10px !important" onclick="disableContactForms(this)">
                                                        <?php } ?>
							<br>

						</td>
					</tr>
					<tr class="user-nickname-wrap">
						<td style="padding:10px 10px 10px 10px;border-left: 1px solid black;border-right: 1px solid black;" colspan="2"><label for="nickname" style="font-weight:600;color:#1d2327;font-size: 15px;"><?php _e( 'Enable Contact Address' ); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<?php
								if(isset($chat_value_data['contact_page']) && isset($chat_value_data['contact_address'])) {
							?>

								  <input type="checkbox" class="toggleCheck" name="contact_address" id="contact_addresses" checked="checked" style="padding-top:10px !important">
							<?php
								} else if(isset($chat_value_data['contact_page'])=='' && isset($chat_value_data['contact_address'])=='1') {
							?>

								  <input type="checkbox" class="toggleCheck" name="contact_address" id="contact_addresses" disabled="disabled" style="padding-top:10px !important">
							<?php
								} else if(isset($chat_value_data['contact_page'])=='' && isset($chat_value_data['contact_address'])=='') {
							?>

								  <input type="checkbox" class="toggleCheck" name="contact_address" id="contact_addresses" disabled="disabled" style="padding-top:10px !important">
							<?php
								} else {
							?>
								  <input type="checkbox" class="toggleCheck" name="contact_address" id="contact_addresses" style="padding-top:10px !important">
							<?php } ?>

						</td>
					</tr>
					<tr class="user-nickname-wrap">
						<td style="padding:10px 10px 10px 10px;border-left: 1px solid black;border-right: 1px solid black;" colspan="2"><label for="nickname" style="font-weight:600;color:#1d2327;font-size: 15px;"><?php _e( 'Enable Contact Form' ); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<?php
								if(isset($chat_value_data['contact_page']) && isset($chat_value_data['contact_form'])) {
							?>

								  <input type="checkbox" class="toggleCheck" name="contact_form" id="contact_forms" checked="checked" style="padding-top:10px !important">
							<?php
								} else if(isset($chat_value_data['contact_page'])=='' && isset($chat_value_data['contact_form'])=='1') {
							?>

								  <input type="checkbox" class="toggleCheck" name="contact_form" id="contact_forms" disabled="disabled" style="padding-top:10px !important">
							<?php
								} else if(isset($chat_value_data['contact_page'])=='' && isset($chat_value_data['contact_form'])=='') {
							?>

								  <input type="checkbox" class="toggleCheck" name="contact_form" id="contact_forms" disabled="disabled" style="padding-top:10px !important">
							<?php
								} else {
							?>
								  <input type="checkbox" class="toggleCheck" name="contact_form" id="contact_forms" style="padding-top:10px !important">
							<?php } ?>

						</td>
					</tr>
					<tr class="user-nickname-wrap">
						<td style="padding:10px 10px 10px 10px;border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;" colspan="2"><label for="nickname" style="font-weight:600;color:#1d2327;font-size: 15px;"><?php _e( 'Enable Contact Number' ); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<?php
								if($chat_value_data['contact_number'] == "on") {
							?>

								  <input type="checkbox" class="toggleCheck" name="contact_number" checked="checked" style="padding-top:10px !important">
							<?php
								} else {
							?>
								  <input type="checkbox" class="toggleCheck" name="contact_number" style="padding-top:10px !important">
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td></td>
					</tr>
					<tr class="user-nickname-wrap" style="border: 1px solid black;">
						<th colspan="2" style="padding: 5px 10px;"><label for="nickname" style="font-size: 18px;"><?php _e( 'Chat Settings' ); ?></label></th>
					</tr>
					<tr class="user-nickname-wrap" style="border-left: 1px solid black;border-right: 1px solid black;">
						<td style="padding:10px 10px 10px 10px;" colspan="2"><label for="nickname" style="font-weight:600;color:#1d2327;font-size: 15px;"><?php _e( 'Enable Chat' ); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<?php
								if($chat_value_data['disable_chat'] == "on") {
							?>

								  <input type="checkbox" class="toggleCheck" name="disable_chat" checked="checked" style="padding-top:10px !important">
							<!--<input type="radio" id="Yes" name="disable_chat" checked value="y">
							<label for="Yes">Yes</label>-->
							<?php
								} else {
							?>
								  <input type="checkbox" class="toggleCheck" name="disable_chat" style="padding-top:10px !important">
							<!--<input type="radio" id="Yes" name="disable_chat" value="y">
							<label for="Yes">Yes</label>-->
							<?php } ?>
						</td>
					</tr>
					<br>
					<tr class="user-user-login-wrap"  style="border-left: 1px solid black;border-right: 1px solid black;">
						<th style="font-size: 15px;padding: 5px 10px;">Chat time settings</th>
					</tr>
					<tr>
       
					  <?php
					  $array_of_times = array();
					  for($i=1;$i<=12;$i++){
						  if($i<10) $v='0'.$i; else $v=$i;
						  $array_of_times[] = array('id'=>$v,'title'=>$v);
					  }


					  $array_of_minutes = array();
					  for($k=0;$k<=59;$k++){
						  if($k<10) $v='0'.$k; else $v=$k;
						  $array_of_minutes[] = array('id'=>$v,'title'=>$v);
					  }

					 $time_am_pm = array(
					    0 => array(
					      'id' => 'AM',
					      'title' => 'AM',
					    ),
					    1 => array(
					      'id' => 'PM',
					      'title' => 'PM',
					    ));
					  ?>
                                          </tr>

                                          </tr>
				        <tr style="border-left: 1px solid black;border-right: 1px solid black;">
				        	<td><b>Monday:	 </b></td>
				        	<td>
							  <?php if(isset($chat_value_data['mon_status']) && $chat_value_data['mon_status']=='on'){
				
								?>

								  <input type="checkbox" class="toggleCheck" name="mon_status" checked="checked" style="padding-top:10px !important">
								<?php } else { ?>
									<input type="checkbox" class="toggleCheck" name="mon_status" style="padding-top:10px !important">
								<?php } ?>
						</td><td>
								<select name="mon_start" id="mon_start" onchange="monStartVal();">
									<?php 

										if($chat_value_data['mon_start'] != ''){

											
												$getHour = explode(":", $chat_value_data['mon_start']);

												if(isset($getHour[0])){
													echo '<option value="'.$getHour[0].'">'.$getHour[0].'</option>';
												} else {
													echo '<option value="'.$chat_value_data['mon_start'].'">'.$chat_value_data['mon_start'].'</option>';
												}
										}
									?>
									<?php
										foreach ($array_of_times as $key_times => $value_times) {
											?>
												<option value="<?php echo $value_times['title']; ?>"><?php echo $value_times['title']; ?></option>
											<?php
										}
									?>
								</select>


								<select name="mon_start_min" id="mon_start_min" onchange="monStartVal();">
									<?php 

												if(isset($getHour[1])){
													echo '<option value="'.$getHour[1].'">'.$getHour[1].'</option>';
												} else if($chat_value_data['mon_start_min'] != ''){
											echo '<option value="'.$chat_value_data['mon_start_min'].'">'.$chat_value_data['mon_start_min'].'</option>';
										}
									?>
									<?php
										foreach ($array_of_minutes as $key_minutes => $value_minutes) {
											?>
												<option value="<?php echo $value_minutes['title']; ?>"><?php echo $value_minutes['title']; ?></option>
											<?php
										}
									?>
								</select>
								
								<select name="mon_start_ap" id="mon_start_ap" onchange="monStartVal();">
									<?php 
										if($chat_value_data['mon_start_ap'] != ''){
											echo '<option value="'.$chat_value_data['mon_start_ap'].'">'.$chat_value_data['mon_start_ap'].'</option>';
										}
									?>
									<?php
										foreach ($time_am_pm as $key_time => $value_time) {
											if($chat_value_data['mon_start_ap'] != '' && $chat_value_data['mon_start_ap'] != $value_time['title']){
											?>
												<option value="<?php echo $value_time['title']; ?>"><?php echo $value_time['title']; ?></option>
											<?php
										} if($chat_value_data['mon_start_ap'] == '') {
											?>
												<option value="<?php echo $value_time['title']; ?>"><?php echo $value_time['title']; ?></option>
											<?php
										}
										}
									?>
								</select>
								to
								<select name="mon_end" id="mon_end" onchange="monEndVal();">
									<?php 
										if($chat_value_data['mon_end'] != ''){
												$getHour = explode(":", $chat_value_data['mon_end']);
												if(isset($getHour[0])){
													echo '<option value="'.$getHour[0].'">'.$getHour[0].'</option>';
												} else {
											echo '<option value="'.$chat_value_data['mon_end'].'">'.$chat_value_data['mon_end'].'</option>';
												}
										}
									?>
									<?php
										foreach ($array_of_times as $key_times => $value_times) {
											?>
												<option value="<?php echo $value_times['title']; ?>"><?php echo $value_times['title']; ?></option>
											<?php
										}
									?>
								</select>


								<select name="mon_end_min" id="mon_end_min" onchange="monEndVal();">
									<?php 
									if(isset($getHour[1])){
													echo '<option value="'.$getHour[1].'">'.$getHour[1].'</option>';
												} else if($chat_value_data['mon_end_min'] != ''){
											echo '<option value="'.$chat_value_data['mon_end_min'].'">'.$chat_value_data['mon_end_min'].'</option>';
										}
									?>
									<?php
										foreach ($array_of_minutes as $key_minutes => $value_minutes) {
											?>
												<option value="<?php echo $value_minutes['title']; ?>"><?php echo $value_minutes['title']; ?></option>
											<?php
										}
									?>
								</select>
								
								<select name="mon_end_ap" id="mon_end_ap" onchange="monEndVal();">
									<?php 
										if($chat_value_data['mon_end_ap'] != ''){
											echo '<option value="'.$chat_value_data['mon_end_ap'].'">'.$chat_value_data['mon_end_ap'].'</option>';
										}
										echo '<option value="PM">PM</option>';
									?>
									<?php
										foreach ($time_am_pm as $key_time => $value_time) {
											if($chat_value_data['mon_end_ap'] != '' && $chat_value_data['mon_end_ap'] != $value_time['title']){
											?>
												<option value="<?php echo $value_time['title']; ?>"><?php echo $value_time['title']; ?></option>
											<?php
										} if($chat_value_data['mon_end_ap'] == '') {
											?>
												<option value="<?php echo $value_time['title']; ?>"><?php echo $value_time['title']; ?></option>
											<?php
										}
										}
									?>
								</select>
							</td>
					  	</tr>
				  	<tr style="border-left: 1px solid black;border-right: 1px solid black;">
					  	 <td><b>Tuesday: </b></td>
					  	 <td>
							  <?php if(isset($chat_value_data['tue_status']) && $chat_value_data['tue_status']=='on'){ ?>
								  <input type="checkbox" class="toggleCheck" name="tue_status" checked="checked" style="padding-top:10px !important">
								<?php } else { ?>
									<input type="checkbox" class="toggleCheck" name="tue_status" style="padding-top:10px !important">
								<?php } ?>

                                                </td><td>
								<select name="tue_start" id="tue_start" onchange="tueStartVal();">
									<?php 
										if($chat_value_data['tue_start'] != ''){

												$getHour = explode(":", $chat_value_data['tue_start']);
												if(isset($getHour[0])){
													echo '<option value="'.$getHour[0].'">'.$getHour[0].'</option>';
												} else {
											echo '<option value="'.$chat_value_data['tue_start'].'">'.$chat_value_data['tue_start'].'</option>';
												}
										}
									?>
									<?php
										foreach ($array_of_times as $key_times => $value_times) {
											?>
												<option value="<?php echo $value_times['title']; ?>"><?php echo $value_times['title']; ?></option>
											<?php
										}
									?>
								</select>

								<select name="tue_start_min" id="tue_start_min" onchange="tueStartVal();">
									<?php 
										if(isset($getHour[1])){
													echo '<option value="'.$getHour[1].'">'.$getHour[1].'</option>';
												} else if($chat_value_data['tue_start_min'] != ''){
											echo '<option value="'.$chat_value_data['tue_start_min'].'">'.$chat_value_data['tue_start_min'].'</option>';
										}
									?>
									<?php
										foreach ($array_of_minutes as $key_minutes => $value_minutes) {
											?>
												<option value="<?php echo $value_minutes['title']; ?>"><?php echo $value_minutes['title']; ?></option>
											<?php
										}
									?>
								</select>
								
								<select name="tue_start_ap" id="tue_start_ap" onchange="tueStartVal();">
									<?php 
										if($chat_value_data['tue_start_ap'] != ''){
											echo '<option value="'.$chat_value_data['tue_start_ap'].'">'.$chat_value_data['tue_start_ap'].'</option>';
										}
									?>
									<?php
										foreach ($time_am_pm as $key_time => $value_time) {
											if($chat_value_data['tue_start_ap'] != '' && $chat_value_data['tue_start_ap'] != $value_time['title']){
											?>
												<option value="<?php echo $value_time['title']; ?>"><?php echo $value_time['title']; ?></option>
											<?php
										} if($chat_value_data['tue_start_ap'] == '') {
											?>
												<option value="<?php echo $value_time['title']; ?>"><?php echo $value_time['title']; ?></option>
											<?php
										}
										}
									?>
								</select>
								to
								<select name="tue_end" id="tue_end"  onchange="tueEndVal();">
									<?php 
										if($chat_value_data['tue_end'] != ''){

												$getHour = explode(":", $chat_value_data['tue_end']);
												if(isset($getHour[0])){
													echo '<option value="'.$getHour[0].'">'.$getHour[0].'</option>';
												} else {
											echo '<option value="'.$chat_value_data['tue_end'].'">'.$chat_value_data['tue_end'].'</option>';
												}
										}
									?>
									<?php
										foreach ($array_of_times as $key_times => $value_times) {
											?>
												<option value="<?php echo $value_times['title']; ?>"><?php echo $value_times['title']; ?></option>
											<?php
										}
									?>
								</select>

								<select name="tue_end_min" id="tue_end_min"  onchange="tueEndVal();">
									<?php 
										if(isset($getHour[1])){
													echo '<option value="'.$getHour[1].'">'.$getHour[1].'</option>';
												} else if($chat_value_data['tue_end_min'] != ''){
											echo '<option value="'.$chat_value_data['tue_end_min'].'">'.$chat_value_data['tue_end_min'].'</option>';
										}
									?>
									<?php
										foreach ($array_of_minutes as $key_minutes => $value_minutes) {
											?>
												<option value="<?php echo $value_minutes['title']; ?>"><?php echo $value_minutes['title']; ?></option>
											<?php
										}
									?>
								</select>
								
								<select name="tue_end_ap" id="tue_end_ap"  onchange="tueEndVal();">
									<?php 
										if($chat_value_data['tue_end_ap'] != ''){
											echo '<option value="'.$chat_value_data['tue_end_ap'].'">'.$chat_value_data['tue_end_ap'].'</option>';
										}
                                                                                echo '<option value="PM">PM</option>';
									?>
									<?php
										foreach ($time_am_pm as $key_time => $value_time) {
											if($chat_value_data['tue_end_ap'] != '' && $chat_value_data['tue_end_ap'] != $value_time['title']){
											?>
												<option value="<?php echo $value_time['title']; ?>"><?php echo $value_time['title']; ?></option>
											<?php
										} if($chat_value_data['tue_end_ap'] == '') {
											?>
												<option value="<?php echo $value_time['title']; ?>"><?php echo $value_time['title']; ?></option>
											<?php
										}
										}
									?>
								</select>
						</td>
			        </tr>
			        <tr style="border-left: 1px solid black;border-right: 1px solid black;">
			         	<td><b>Wednesday: </b></td>
			         	<td>
							  <?php if(isset($chat_value_data['wed_status']) && $chat_value_data['wed_status']=='on'){ ?>
								  <input type="checkbox" class="toggleCheck" name="wed_status" checked="checked" style="padding-top:10px !important">
								<?php } else { ?>
									<input type="checkbox" class="toggleCheck" name="wed_status" style="padding-top:10px !important">
								<?php } ?>

                                                </td><td>
								<select name="wed_start" id="wed_start" onchange="wedStartVal();">
									<?php 
										if($chat_value_data['wed_start'] != ''){

												$getHour = explode(":", $chat_value_data['wed_start']);
												if(isset($getHour[0])){
													echo '<option value="'.$getHour[0].'">'.$getHour[0].'</option>';
												} else {
											echo '<option value="'.$chat_value_data['wed_start'].'">'.$chat_value_data['wed_start'].'</option>';
												}
										}
									?>
									<?php
										foreach ($array_of_times as $key_times => $value_times) {
											?>
												<option value="<?php echo $value_times['title']; ?>"><?php echo $value_times['title']; ?></option>
											<?php
										}
									?>
								</select>

								<select name="wed_start_min" id="wed_start_min" onchange="wedStartVal();">
									<?php 
										if(isset($getHour[1])){
													echo '<option value="'.$getHour[1].'">'.$getHour[1].'</option>';
												} else if($chat_value_data['wed_start_min'] != ''){
											echo '<option value="'.$chat_value_data['wed_start_min'].'">'.$chat_value_data['wed_start_min'].'</option>';
										}
									?>
									<?php
										foreach ($array_of_minutes as $key_minutes => $value_minutes) {
											?>
												<option value="<?php echo $value_minutes['title']; ?>"><?php echo $value_minutes['title']; ?></option>
											<?php
										}
									?>
								</select>
								
								<select name="wed_start_ap" id="wed_start_ap" onchange="wedStartVal();">
									<?php 
										if($chat_value_data['wed_start_ap'] != ''){
											echo '<option value="'.$chat_value_data['wed_start_ap'].'">'.$chat_value_data['wed_start_ap'].'</option>';
										}
									?>
									<?php
										foreach ($time_am_pm as $key_time => $value_time) {
											if($chat_value_data['wed_start_ap'] != '' && $chat_value_data['wed_start_ap'] != $value_time['title']){
											?>
												<option value="<?php echo $value_time['title']; ?>"><?php echo $value_time['title']; ?></option>
											<?php
										} if($chat_value_data['wed_start_ap'] == '') {
											?>
												<option value="<?php echo $value_time['title']; ?>"><?php echo $value_time['title']; ?></option>
											<?php
										}
										}
									?>
								</select>
								to
								<select name="wed_end" id="wed_end" onchange="wedEndVal();">
									<?php 
										if($chat_value_data['wed_end'] != ''){

												$getHour = explode(":", $chat_value_data['wed_end']);
												if(isset($getHour[0])){
													echo '<option value="'.$getHour[0].'">'.$getHour[0].'</option>';
												} else {
											echo '<option value="'.$chat_value_data['wed_end'].'">'.$chat_value_data['wed_end'].'</option>';
												}
										}
									?>
									<?php
										foreach ($array_of_times as $key_times => $value_times) {
											?>
												<option value="<?php echo $value_times['title']; ?>"><?php echo $value_times['title']; ?></option>
											<?php
										}
									?>
								</select>

								<select name="wed_end_min" id="wed_end_min" onchange="wedEndVal();">
									<?php 
										if(isset($getHour[1])){
													echo '<option value="'.$getHour[1].'">'.$getHour[1].'</option>';
												} else if($chat_value_data['wed_end_min'] != ''){
											echo '<option value="'.$chat_value_data['wed_end_min'].'">'.$chat_value_data['wed_end_min'].'</option>';
										}
									?>
									<?php
										foreach ($array_of_minutes as $key_minutes => $value_minutes) {
											?>
												<option value="<?php echo $value_minutes['title']; ?>"><?php echo $value_minutes['title']; ?></option>
											<?php
										}
									?>
								</select>
								
								<select name="wed_end_ap" id="wed_end_ap" onchange="wedEndVal();">
									<?php 
										if($chat_value_data['wed_end_ap'] != ''){
											echo '<option value="'.$chat_value_data['wed_end_ap'].'">'.$chat_value_data['wed_end_ap'].'</option>';
										}
                                                                                echo '<option value="PM">PM</option>';
									?>
									<?php
										foreach ($time_am_pm as $key_time => $value_time) {
											if($chat_value_data['wed_end_ap'] != '' && $chat_value_data['wed_end_ap'] != $value_time['title']){
											?>
												<option value="<?php echo $value_time['title']; ?>"><?php echo $value_time['title']; ?></option>
											<?php
										} if($chat_value_data['wed_end_ap'] == '') {
											?>
												<option value="<?php echo $value_time['title']; ?>"><?php echo $value_time['title']; ?></option>
											<?php
										}
										}
									?>
								</select>
						</td>
				  	</tr>
			        <tr style="border-left: 1px solid black;border-right: 1px solid black;">
			         	<td><b>Thursday: </b> </td>
			         	<td>
							  <?php if(isset($chat_value_data['thu_status']) && $chat_value_data['thu_status']=='on'){ ?>
								  <input type="checkbox" class="toggleCheck" name="thu_status" checked="checked" style="padding-top:10px !important">
								<?php } else { ?>
									<input type="checkbox" class="toggleCheck" name="thu_status" style="padding-top:10px !important">
								<?php } ?>

                                                </td><td>
								<select name="thu_start" id="thu_start" onchange="thuStartVal();">
									<?php 
										if($chat_value_data['thu_start'] != ''){

												$getHour = explode(":", $chat_value_data['thu_start']);
												if(isset($getHour[0])){
													echo '<option value="'.$getHour[0].'">'.$getHour[0].'</option>';
												} else {
											echo '<option value="'.$chat_value_data['thu_start'].'">'.$chat_value_data['thu_start'].'</option>';
												}
										}
									?>
									<?php
										foreach ($array_of_times as $key_times => $value_times) {
											?>
												<option value="<?php echo $value_times['title']; ?>"><?php echo $value_times['title']; ?></option>
											<?php
										}
									?>
								</select>


								<select name="thu_start_min" id="thu_start_min" onchange="thuStartVal();">
									<?php 
										if(isset($getHour[1])){
													echo '<option value="'.$getHour[1].'">'.$getHour[1].'</option>';
												} else if($chat_value_data['thu_start_min'] != ''){
											echo '<option value="'.$chat_value_data['thu_start_min'].'">'.$chat_value_data['thu_start_min'].'</option>';
										}
									?>
									<?php
										foreach ($array_of_minutes as $key_minutes => $value_minutes) {
											?>
												<option value="<?php echo $value_minutes['title']; ?>"><?php echo $value_minutes['title']; ?></option>
											<?php
										}
									?>
								</select>
								
								<select name="thu_start_ap" id="thu_start_ap" onchange="thuStartVal();">
									<?php 
										if($chat_value_data['thu_start_ap'] != ''){
											echo '<option value="'.$chat_value_data['thu_start_ap'].'">'.$chat_value_data['thu_start_ap'].'</option>';
										}
									?>
									<?php
										foreach ($time_am_pm as $key_time => $value_time) {
											if($chat_value_data['thu_start_ap'] != '' && $chat_value_data['thu_start_ap'] != $value_time['title']){
											?>
												<option value="<?php echo $value_time['title']; ?>"><?php echo $value_time['title']; ?></option>
											<?php
										} if($chat_value_data['thu_start_ap'] == '') {
											?>
												<option value="<?php echo $value_time['title']; ?>"><?php echo $value_time['title']; ?></option>
											<?php
										}
										}
									?>
								</select>
								to
								<select name="thu_end" id="thu_end" onchange="thuEndVal();">
									<?php 
										if($chat_value_data['thu_end'] != ''){

												$getHour = explode(":", $chat_value_data['thu_end']);
												if(isset($getHour[0])){
													echo '<option value="'.$getHour[0].'">'.$getHour[0].'</option>';
												} else {
											echo '<option value="'.$chat_value_data['thu_end'].'">'.$chat_value_data['thu_end'].'</option>';
												}	
										}
									?>
									<?php
										foreach ($array_of_times as $key_times => $value_times) {
											?>
												<option value="<?php echo $value_times['title']; ?>"><?php echo $value_times['title']; ?></option>
											<?php
										}
									?>
								</select>

								<select name="thu_end_min" id="thu_end_min" onchange="thuEndVal();">
									<?php 
										if(isset($getHour[1])){
													echo '<option value="'.$getHour[1].'">'.$getHour[1].'</option>';
												} else if($chat_value_data['thu_end_min'] != ''){
											echo '<option value="'.$chat_value_data['thu_end_min'].'">'.$chat_value_data['thu_end_min'].'</option>';
										}
									?>
									<?php
										foreach ($array_of_minutes as $key_minutes => $value_minutes) {
											?>
												<option value="<?php echo $value_minutes['title']; ?>"><?php echo $value_minutes['title']; ?></option>
											<?php
										}
									?>
								</select>
								
								<select name="thu_end_ap" id="thu_end_ap" onchange="thuEndVal();">
									<?php 
										if($chat_value_data['thu_end_ap'] != ''){
											echo '<option value="'.$chat_value_data['thu_end_ap'].'">'.$chat_value_data['thu_end_ap'].'</option>';
										}
                                                                                echo '<option value="PM">PM</option>';
									?>
									<?php
										foreach ($time_am_pm as $key_time => $value_time) {
											if($chat_value_data['thu_end_ap'] != '' && $chat_value_data['thu_end_ap'] != $value_time['title']){
											?>
												<option value="<?php echo $value_time['title']; ?>"><?php echo $value_time['title']; ?></option>
											<?php
										} if($chat_value_data['thu_end_ap'] == '') {
											?>
												<option value="<?php echo $value_time['title']; ?>"><?php echo $value_time['title']; ?></option>
											<?php
										}
										}
									?>
								</select>
						</td>
			        </tr>
			        <tr style="border-left: 1px solid black;border-right: 1px solid black;">
			         	<td><b>Friday: </b> </td>
			         	<td>
							  <?php if(isset($chat_value_data['fri_status']) && $chat_value_data['fri_status']=='on'){ ?>
								  <input type="checkbox" class="toggleCheck" name="fri_status" checked="checked" style="padding-top:10px !important">
								<?php } else { ?>
									<input type="checkbox" class="toggleCheck" name="fri_status" style="padding-top:10px !important">
								<?php } ?>

                                                </td><td>
								<select name="fri_start" id="fri_start" onchange="friStartVal();">
									<?php 
										if($chat_value_data['fri_start'] != ''){

												$getHour = explode(":", $chat_value_data['fri_start']);
												if(isset($getHour[0])){
													echo '<option value="'.$getHour[0].'">'.$getHour[0].'</option>';
												} else {
											echo '<option value="'.$chat_value_data['fri_start'].'">'.$chat_value_data['fri_start'].'</option>';
												}
										}
									?>
									<?php
										foreach ($array_of_times as $key_times => $value_times) {
											?>
												<option value="<?php echo $value_times['title']; ?>"><?php echo $value_times['title']; ?></option>
											<?php
										}
									?>
								</select>

								<select name="fri_start_min" id="fri_start_min" onchange="friStartVal();">
									<?php 
										if(isset($getHour[1])){
													echo '<option value="'.$getHour[1].'">'.$getHour[1].'</option>';
												} else if($chat_value_data['fri_start_min'] != ''){
											echo '<option value="'.$chat_value_data['fri_start_min'].'">'.$chat_value_data['fri_start_min'].'</option>';
										}
									?>
									<?php
										foreach ($array_of_minutes as $key_minutes => $value_minutes) {
											?>
												<option value="<?php echo $value_minutes['title']; ?>"><?php echo $value_minutes['title']; ?></option>
											<?php
										}
									?>
								</select>
								
								<select name="fri_start_ap" id="fri_start_ap" onchange="friStartVal();">
									<?php 
										if($chat_value_data['fri_start_ap'] != ''){
											echo '<option value="'.$chat_value_data['fri_start_ap'].'">'.$chat_value_data['fri_start_ap'].'</option>';
										}
									?>
									<?php
										foreach ($time_am_pm as $key_time => $value_time) {
											if($chat_value_data['fri_start_ap'] != '' && $chat_value_data['fri_start_ap'] != $value_time['title']){
											?>
												<option value="<?php echo $value_time['title']; ?>"><?php echo $value_time['title']; ?></option>
											<?php
										} if($chat_value_data['fri_start_ap'] == '') {
											?>
												<option value="<?php echo $value_time['title']; ?>"><?php echo $value_time['title']; ?></option>
											<?php
										}
										}
									?>
								</select>
								to
								<select name="fri_end" id="fri_end" onchange="friEndVal();">
									<?php 
										if($chat_value_data['fri_end'] != ''){

												$getHour = explode(":", $chat_value_data['fri_end']);
												if(isset($getHour[0])){
													echo '<option value="'.$getHour[0].'">'.$getHour[0].'</option>';
												} else {
											echo '<option value="'.$chat_value_data['fri_end'].'">'.$chat_value_data['fri_end'].'</option>';
												}
										}
									?>
									<?php
										foreach ($array_of_times as $key_times => $value_times) {
											?>
												<option value="<?php echo $value_times['title']; ?>"><?php echo $value_times['title']; ?></option>
											<?php
										}
									?>
								</select>

								<select name="fri_end_min" id="fri_end_min" onchange="friEndVal();">
									<?php 
										if(isset($getHour[1])){
													echo '<option value="'.$getHour[1].'">'.$getHour[1].'</option>';
												} else if($chat_value_data['fri_end_min'] != ''){
											echo '<option value="'.$chat_value_data['fri_end_min'].'">'.$chat_value_data['fri_end_min'].'</option>';
										}
									?>
									<?php
										foreach ($array_of_minutes as $key_minutes => $value_minutes) {
											?>
												<option value="<?php echo $value_minutes['title']; ?>"><?php echo $value_minutes['title']; ?></option>
											<?php
										}
									?>
								</select>
								
								<select name="fri_end_ap" id="fri_end_ap" onchange="friEndVal();">
									<?php 
										if($chat_value_data['fri_end_ap'] != ''){
											echo '<option value="'.$chat_value_data['fri_end_ap'].'">'.$chat_value_data['fri_end_ap'].'</option>';
										}
                                                                                echo '<option value="PM">PM</option>';
									?>
									<?php
										foreach ($time_am_pm as $key_time => $value_time) {
											if($chat_value_data['fri_end_ap'] != '' && $chat_value_data['fri_end_ap'] != $value_time['title']){
											?>
												<option value="<?php echo $value_time['title']; ?>"><?php echo $value_time['title']; ?></option>
											<?php
										} if($chat_value_data['fri_end_ap'] == '') {
											?>
												<option value="<?php echo $value_time['title']; ?>"><?php echo $value_time['title']; ?></option>
											<?php
										}
										}
									?>
								</select>
						</td>
			        </tr>
			        <tr style="border-left: 1px solid black;border-right: 1px solid black;">
			        	 <td><b>Saturday: </b></td>
			        	 <td> 
							  <?php if(isset($chat_value_data['sat_status']) && $chat_value_data['sat_status']=='on'){ ?>
								  <input type="checkbox" class="toggleCheck" name="sat_status" checked="checked" style="padding-top:10px !important">
								<?php } else { ?>
									<input type="checkbox" class="toggleCheck" name="sat_status" style="padding-top:10px !important">
								<?php } ?>

                                                </td><td>
								<select name="sat_start" id="sat_start" onchange="satStartVal();">
									<?php 
										if($chat_value_data['sat_start'] != ''){
												$getHour = explode(":", $chat_value_data['sat_start']);
												if(isset($getHour[0])){
													echo '<option value="'.$getHour[0].'">'.$getHour[0].'</option>';
												} else {
											echo '<option value="'.$chat_value_data['sat_start'].'">'.$chat_value_data['sat_start'].'</option>';
												}
										}
									?>
									<?php
										foreach ($array_of_times as $key_times => $value_times) {
											?>
												<option value="<?php echo $value_times['title']; ?>"><?php echo $value_times['title']; ?></option>
											<?php
										}
									?>
								</select>

								<select name="sat_start_min" id="sat_start_min" onchange="satStartVal();">
									<?php 
										if(isset($getHour[1])){
													echo '<option value="'.$getHour[1].'">'.$getHour[1].'</option>';
												} else if($chat_value_data['sat_start_min'] != ''){
											echo '<option value="'.$chat_value_data['sat_start_min'].'">'.$chat_value_data['sat_start_min'].'</option>';
										}
									?>
									<?php
										foreach ($array_of_minutes as $key_minutes => $value_minutes) {
											?>
												<option value="<?php echo $value_minutes['title']; ?>"><?php echo $value_minutes['title']; ?></option>
											<?php
										}
									?>
								</select>
								
								<select name="sat_start_ap" id="sat_start_ap" onchange="satStartVal();">
									<?php 
										if($chat_value_data['sat_start_ap'] != ''){
											echo '<option value="'.$chat_value_data['sat_start_ap'].'">'.$chat_value_data['sat_start_ap'].'</option>';
										}
									?>
									<?php
										foreach ($time_am_pm as $key_time => $value_time) {
											if($chat_value_data['sat_start_ap'] != '' && $chat_value_data['sat_start_ap'] != $value_time['title']){
											?>
												<option value="<?php echo $value_time['title']; ?>"><?php echo $value_time['title']; ?></option>
											<?php
										} if($chat_value_data['sat_start_ap'] == '') {
											?>
												<option value="<?php echo $value_time['title']; ?>"><?php echo $value_time['title']; ?></option>
											<?php
										}
										}
									?>
								</select>
								to
								<select name="sat_end" id="sat_end" onchange="satEndVal();">
									<?php 
										if($chat_value_data['sat_end'] != ''){

												$getHour = explode(":", $chat_value_data['sat_end']);
												if(isset($getHour[0])){
													echo '<option value="'.$getHour[0].'">'.$getHour[0].'</option>';
												} else {
											echo '<option value="'.$chat_value_data['sat_end'].'">'.$chat_value_data['sat_end'].'</option>';
											}
										}
									?>
									<?php
										foreach ($array_of_times as $key_times => $value_times) {
											?>
												<option value="<?php echo $value_times['title']; ?>"><?php echo $value_times['title']; ?></option>
											<?php
										}
									?>
								</select>

								<select name="sat_end_min" id="sat_end_min" onchange="satEndVal();">
									<?php 
										if(isset($getHour[1])){
													echo '<option value="'.$getHour[1].'">'.$getHour[1].'</option>';
												} else if($chat_value_data['sat_end_min'] != ''){
											echo '<option value="'.$chat_value_data['sat_end_min'].'">'.$chat_value_data['sat_end_min'].'</option>';
										}
									?>
									<?php
										foreach ($array_of_minutes as $key_minutes => $value_minutes) {
											?>
												<option value="<?php echo $value_minutes['title']; ?>"><?php echo $value_minutes['title']; ?></option>
											<?php
										}
									?>
								</select>
								
								<select name="sat_end_ap" id="sat_end_ap" onchange="satEndVal();">
									<?php 
										if($chat_value_data['sat_end_ap'] != ''){
											echo '<option value="'.$chat_value_data['sat_end_ap'].'">'.$chat_value_data['sat_end_ap'].'</option>';
										}
                                                                                echo '<option value="PM">PM</option>';
									?>
									<?php
										foreach ($time_am_pm as $key_time => $value_time) {
											if($chat_value_data['sat_end_ap'] != '' && $chat_value_data['sat_end_ap'] != $value_time['title']){
											?>
												<option value="<?php echo $value_time['title']; ?>"><?php echo $value_time['title']; ?></option>
											<?php
										} if($chat_value_data['sat_end_ap'] == '') {
											?>
												<option value="<?php echo $value_time['title']; ?>"><?php echo $value_time['title']; ?></option>
											<?php
										}
										}
									?>
								</select> 
						</td>
					 </tr>
					 <tr style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;">
					 	<td><b>Sunday: </b> </td>
					 	<td>
							  <?php if(isset($chat_value_data['sun_status']) && $chat_value_data['sun_status']=='on'){ ?>
								  <input type="checkbox" class="toggleCheck" name="sun_status" checked="checked" style="padding-top:10px !important">
								<?php } else { ?>
									<input type="checkbox" class="toggleCheck" name="sun_status" style="padding-top:10px !important">
								<?php } ?>

                                                </td><td>
								<select name="sun_start" id="sun_start" onchange="sunStartVal();">
									<?php 
										if($chat_value_data['sun_start'] != ''){

												$getHour = explode(":", $chat_value_data['sat_start']);
												if(isset($getHour[0])){
													echo '<option value="'.$getHour[0].'">'.$getHour[0].'</option>';
												} else {
											echo '<option value="'.$chat_value_data['sun_start'].'">'.$chat_value_data['sun_start'].'</option>';
												}
										}
									?>
									<?php
										foreach ($array_of_times as $key_times => $value_times) {
											?>
												<option value="<?php echo $value_times['title']; ?>"><?php echo $value_times['title']; ?></option>
											<?php
										}
									?>
								</select>

								<select name="sun_start_min" id="sun_start_min" onchange="sunStartVal();">
									<?php 
										if(isset($getHour[1])){
													echo '<option value="'.$getHour[1].'">'.$getHour[1].'</option>';
												} else if($chat_value_data['sun_start_min'] != ''){
											echo '<option value="'.$chat_value_data['sun_start_min'].'">'.$chat_value_data['sun_start_min'].'</option>';
										}
									?>
									<?php
										foreach ($array_of_minutes as $key_minutes => $value_minutes) {
											?>
												<option value="<?php echo $value_minutes['title']; ?>"><?php echo $value_minutes['title']; ?></option>
											<?php
										}
									?>
								</select>
								
								<select name="sun_start_ap" id="sun_start_ap" onchange="sunStartVal();">
									<?php 
										if($chat_value_data['sun_start_ap'] != ''){
											echo '<option value="'.$chat_value_data['sun_start_ap'].'">'.$chat_value_data['sun_start_ap'].'</option>';
										}
									?>
									<?php
										foreach ($time_am_pm as $key_time => $value_time) {
											if($chat_value_data['sun_start_ap'] != '' && $chat_value_data['sun_start_ap'] != $value_time['title']){
											?>
												<option value="<?php echo $value_time['title']; ?>"><?php echo $value_time['title']; ?></option>
											<?php
										} if($chat_value_data['sun_start_ap'] == '') {
											?>
												<option value="<?php echo $value_time['title']; ?>"><?php echo $value_time['title']; ?></option>
											<?php
										}
										}
									?>
								</select>
								to
								<select name="sun_end" id="sun_end" onchange="sunEndVal();">
									<?php 
										if($chat_value_data['sun_end'] != ''){

												$getHour = explode(":", $chat_value_data['sun_end']);
												if(isset($getHour[0])){
													echo '<option value="'.$getHour[0].'">'.$getHour[0].'</option>';
												} else {
											echo '<option value="'.$chat_value_data['sun_end'].'">'.$chat_value_data['sun_end'].'</option>';
												}
										}
									?>
									<?php
										foreach ($array_of_times as $key_times => $value_times) {
											?>
												<option value="<?php echo $value_times['title']; ?>"><?php echo $value_times['title']; ?></option>
											<?php
										}
									?>
								</select>

								<select name="sun_end_min" id="sun_end_min" onchange="sunEndVal();">
									<?php 
										if(isset($getHour[1])){
													echo '<option value="'.$getHour[1].'">'.$getHour[1].'</option>';
												} else if($chat_value_data['sun_end_min'] != ''){
											echo '<option value="'.$chat_value_data['sun_end_min'].'">'.$chat_value_data['sun_end_min'].'</option>';
										}
									?>
									<?php
										foreach ($array_of_minutes as $key_minutes => $value_minutes) {
											?>
												<option value="<?php echo $value_minutes['title']; ?>"><?php echo $value_minutes['title']; ?></option>
											<?php
										}
									?>
								</select>
								
								<select name="sun_end_ap" id="sun_end_ap" onchange="sunEndVal();">
									<?php 
										if($chat_value_data['sun_end_ap'] != ''){
											echo '<option value="'.$chat_value_data['sun_end_ap'].'">'.$chat_value_data['sun_end_ap'].'</option>';
										}
                                                                                echo '<option value="PM">PM</option>';
									?>
									<?php
										foreach ($time_am_pm as $key_time => $value_time) {
											if($chat_value_data['sun_end_ap'] != '' && $chat_value_data['sun_end_ap'] != $value_time['title']){
											?>
												<option value="<?php echo $value_time['title']; ?>"><?php echo $value_time['title']; ?></option>
											<?php
										} if($chat_value_data['sun_end_ap'] == '') {
											?>
												<option value="<?php echo $value_time['title']; ?>"><?php echo $value_time['title']; ?></option>
											<?php
										}
										}
									?>
								</select>
						</td>
					 </tr>

					<tr class="user-first-name-wrap">
						<td colspan="4">
							<label for="first_name"><b style="font-size: 15px;"><?php _e( 'Chat Message' ); ?></b></label>
						
							<p>
								<?php $tooltip_text=($chat_value_data['tooltip_text']) != '' ? $chat_value_data['tooltip_text'] : '';?>
								<label>Tooltip Text</label><br>
								<?php
									echo '<input type="text" class="" name="tooltip_text" disabled="disabled" value="'.$tooltip_text.'">'; 
								?>
								       <br>
								<span><b>[Note: This text will appear as tooltip(title) after Available/Unavailable.]</b></span>
								</p>
						</td>
					</tr>

					<tr class="user-last-name-wrap">
						<td colspan="4">
							<label for="last_name" style="font-size: 15px;font-weight: 600;"><?php _e( 'Message Text' ); ?></label>
							<p>
								<?php if ($chat_value_data['description'] != ''){
									$chat_message = $chat_value_data['description'];
						  		echo '<textarea name="description" id="description" rows="5" cols="30">'.$chat_message.'</textarea>'; 
						} else {
						  		echo '<textarea name="description" id="description" rows="5" cols="30"><p>Balancepro chat is currently unavailable. Our normal business hours are in (UTC-08:00) Pacific Time (US):</p><p> You may call us at 1-888-456-2227 during normal business hours.</p></textarea>'; 
						}
						  		?>
						   <br>
						    <span><b>[Note: This text message will appear in new window when chat is unavailable.]</b></span>
						    </p>
						</td>
					</tr>
                                </table>
			    <button onclick="editChatForm()" type="button" id="editChat" class="button button-primary" style="margin-left: 10px">Edit</button>
                            <input type="submit" class="button button-primary button-large formSubmit"  value="UPDATE" style="margin-left: 10px">
                        </form>

                        
 <form id="holidayForm1" action="addchat.php" method="post">
                                <table class="form-table" role="presentation">
                                        <tr>
                                                <td colspan="5"><hr class="custom-divider"></td>
					</tr>
                                      <tr class="user-user-login-wrap">
                                                <th style="font-size: 18px;">Holiday List</th>
                                        </tr>
                                        <tr><table id="myTable1" class="form-table">
					<tr>
						<th style="font-size: 15px;text-align: left;">Federal Holidays</th>
					</tr>

<?php

function calculateHolidayDates($year) {
	$holidays = array(
		'New Year\'s Day' => date('Y-m-d', strtotime("$year-01-01")),
		'Martin Luther King Birthday' => date('Y-m-d', strtotime("third Monday of January $year")),
		'Presidents\' Day' => date('Y-m-d', strtotime("third Monday of February $year")),
		'Memorial Day' => date('Y-m-d', strtotime("last Monday of May $year")),
		'Independence Day' => date('Y-m-d', strtotime("$year-07-04")),
		'Labor Day' => date('Y-m-d', strtotime("first Monday of September $year")),
		'Columbus Day' => date('Y-m-d', strtotime("second Monday of October $year")),
		'Veterans\' Day' => date('Y-m-d', strtotime("$year-11-11")),
		'Thanksgiving Day' => date('Y-m-d', strtotime("fourth Thursday of November $year")),
		'Christmas Day' => date('Y-m-d', strtotime("$year-12-25"))
	);
	return $holidays;
}    

  // Get current year and today's date
  $currentYear = date('Y');
  $today = date('Y-m-d');

  // Get holiday dates for the current year
  $holidays = calculateHolidayDates($currentYear);

  // Check if today's date is past each holiday, and if so, update it for the next year
  foreach ($holidays as $holiday => $date) {
	if ($today > $date) {
		// Update holiday to next year's date
		$holidays[$holiday] = calculateHolidayDates($currentYear + 1)[$holiday];
	}
  }


$currentYear = date("Y");
?>

<?php
// Output holiday name, date, and short day name (Mon, Tue, etc.)
foreach ($holidays as $holiday => $date) {
  // Get the short day name for the holiday
  $shortDayName = strtolower(date('D', strtotime($date))); // 'D' gives short textual representation of the day (e.g., 'Mon')
  ?>
  <!-- <div style="display: grid; grid-template-columns: auto auto; justify-content: space-between; width:35%;">
    <label><b><?php //echo $holiday; ?></b></label>
    <input style="width:auto !important; color:#898989;" type="text" value="<?php //echo $date; ?>" readonly><br>
  </div> -->

  <td>
	<tr>
	<td><b><?php echo $holiday; ?></b></td>
	<td colspan="3">
		<input type="checkbox" class="toggleCheck tc" style="display:none;" name="federal_holiday[]" <?php
			if($holiday_data['federal_holiday'][0]=="on" ){?>checked="checked"
		<?php } ?> style="padding-top:10px !important">
		<b></b> <input type="text" disabled="disabled" value="<?php echo date("d/m/Y", strtotime($date));?>" name="fromDatef[]"
			style="width: 130px;background: rgba(255, 255, 255, .5);border-color: rgba(220, 220, 222, .75);box-shadow: inset 0 1px 2px rgba(0, 0, 0, .04);color: rgba(44, 51, 56, .5);cursor: context-menu;">
		<b></b> <input type="hidden" name="toDatef[]" value="<?php  echo date("d/m/Y", strtotime($date));?>"
			style="width: 130px;background: rgba(255, 255, 255, .5);border-color: rgba(220, 220, 222, .75);box-shadow: inset 0 1px 2px rgba(0, 0, 0, .04);color: rgba(44, 51, 56, .5);cursor: context-menu;">
	</td>
	</tr>
			</td>

<?php
}
?>
		<td colspan="5"><hr class="custom-divider"></td>
<style>
.custom-divider {
    border: none;          /* Remove default border */
    height: 2px;           /* Set the thickness */
    background-color: #333; /* Set the color */
    margin: 0px 0;        /* Add space around the line */
}
#btn {
    display: inline-block;
    padding: 5px 10px; 
    font-size: 14px; 
    color: #fff; 
    background-color: #007bff; /* Background color (blue) */
    border: 1px solid #007bff; 
    border-radius: 5px; 
    cursor: pointer; 
    text-align: center; 
    transition: background-color 0.3s, border-color 0.3s;
}

#btn:hover {
    background-color: #0056b3; 
    border-color: #0056b3; 
}
.saveButton{
	display: inline-block;
    padding: 8px 20px;
    font-size: 14px;
    color: #fff;
    background-color: #4CAF50;
    border: 1px solid #4CAF50;
    border-radius: 5px;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    transition: background-color 0.3s, border-color 0.3s;
}
.saveButton:hover{
	background-color: #c82333; 
    border-color: #c82333; 
}
.saveButton:active{
	background-color: #bd2130; 
    border-color: #bd2130; 
}
.remove-holiday-button {
    display: inline-block; 
    padding: 8px 10px;
    font-size: 14px;
    color: #fff; 
    background-color: #dc3545; 
    border: 1px solid #dc3545; 
    border-radius: 5px;
    cursor: pointer; 
    text-align: center;
    text-decoration: none;
    transition: background-color 0.3s, border-color 0.3s; 
}

.remove-holiday-button:hover {
    background-color: #c82333; 
    border-color: #c82333; 
}

.remove-holiday-button:active {
    background-color: #bd2130; 
    border-color: #bd2130; 
}
</style>
					</tr>
                                        <tr>
                                                <th style="font-size: 15px;text-align:left;"></th>
                                        </tr>
                <tr>

                                <td><p><span id="btn" onclick="addRow()" id="btn">Add Holidays</span></p></td>
                </tr>
<?php 
if(isset($holiday_data['fromDate'])){
	for ($holiday = 0; $holiday < count($holiday_data['fromDate']); $holiday++) {
	$holidayName = $holiday_data['holidayName'][$holiday];
	$fromDate = $holiday_data['fromDate'][$holiday];
?>
		<tr>
				
			<td colspan="3">
				<b>Holiday Name:</b> <input type="text" class="holidayName" value="<?php echo $holidayName;?>" name="holidayName[]" style="width: 200px;" autocomplete="off" readonly>
				<b>&nbsp;&nbsp;&nbsp;&nbsp;On:</b> <input type="date" class="fromDate" value="<?php echo $fromDate;?>" name="fromDate[]" class="datetimepicker" style="width: 150px;" autocomplete="off" readonly>
				<button type="button" value="1" class="remove-holiday-button" onclick="jQuery(this).parent().remove();switchUpdate();removeHoliday();">Remove</button></td>
		</tr>
<?php			
	}
} else {
?>
                                              <tr class="hiderow">  <td colspan="3">
                                                        <b>Holiday Name:</b> <input type="text" class="holidayName" name="holidayName[]" style="width: 200px;" autocomplete="off">
							<b>&nbsp;&nbsp;&nbsp;On:</b> <input type="date" class="fromDate" name="fromDate[]"  class="datetimepicker" style="width: 150px;" autocomplete="off">
							<button type="button" value="1" class="saveButton" onclick="saveChanges()">Save</button></td>
					</tr></table>
                                        </tr>
<?php } ?>
				</table>
				<p style="font-weight:bold;color:#f00;display:none;" id="chatError">Please specify Holiday Name with Date.</p>
				<p style="font-weight:bold;color:#f00;display:none;" id="HolidayError">Holiday Already Exists</p>
				<p style="font-weight:bold;color:#f00;display:none;" id="FedHolidayError">Holiday Already Exists in Federal Holidays</p>
			</form>

<script>

function addRow() {
      // Get the table element in which you want to add row
      let table = document.getElementById("myTable1");
   
      // Create a row using the inserRow() method and
      // specify the index where you want to add the row
      let row = table.insertRow(-1); // We are adding at the end
   
      // Create table cells
      let c1 = row.insertCell(0);
      c1.setAttribute("colspan", "3");
   
      // Add data to c1 and c2
    c1.innerHTML = '<tr class="hiderow"><td style="width:100%"><b>Holiday Name:</b> <input type="text" value="" onchange="switchUpdate()" class="holidayName" name="holidayName[]" style="width: 200px;" autocomplete="off"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;On:</b> <input type="date" class="fromDate" name="fromDate[]" onchange="switchUpdate()" class="datetimepicker" style="width: 150px;" autocomplete="off"></td> <td><button type="button" value="1" class="saveButton" onclick="saveChanges()">Save</button></td></tr>';

        jQuery('.formSubmits').attr("disabled","disabled");
   }

jQuery("input[name='holidayName[]']").on("change", switchUpdate);
jQuery("input[name='fromDate[]']").on("change", switchUpdate);
jQuery(".tc").on("change", switchUpdate);

jQuery(".toggleCheck").on("change", function(){
var formdata = jQuery('#chatForm').serialize();
	jQuery.ajax({
           type: "POST",
           url: "addchat.php",
           data: jQuery("#chatForm").serialize(), // serializes the form's elements.
           success: function(data)
           {
//		jQuery(".saveButton").html("Remove");
           }
         });

    return false; // avoid to execute the actual submit of the form.

});


jQuery('.formSubmit').on('click', function(e) {
     e.preventDefault();
var formdata = jQuery('#chatForm').serialize();
        jQuery.ajax({
           type: "POST",
           url: "addchat.php",
           data: jQuery("#chatForm").serialize(), // serializes the form's elements.
           success: function(data)
           {
		location.href="chat.php";
           }
         });

});

// Function to calculate federal holidays for a given year
function getFederalHolidays(year) {
    year = year || new Date().getFullYear(); // Use the current year if not provided

    return [
        { name: "New Year's Day", date: formatDate(new Date(`${year}-01-01`)) },
        { name: "Martin Luther King Birthday", date: getNthWeekdayOfMonth(year, 0, 3, 1) }, // 3rd Monday of January
        { name: "Presidents' Day", date: getNthWeekdayOfMonth(year, 0, 3, 2) }, // 3rd Monday of February
        { name: "Memorial Day", date: getLastWeekdayOfMonth(year, 0, 5) }, // Last Monday of May
        { name: "Independence Day", date: formatDate(new Date(`${year}-07-04`)) },
        { name: "Labor Day", date: getNthWeekdayOfMonth(year, 0, 1, 9) }, // 1st Monday of September
        { name: "Columbus Day", date: getNthWeekdayOfMonth(year, 0, 2, 10) }, // 2nd Monday of October
        { name: "Veterans' Day", date: formatDate(new Date(`${year}-11-11`)) },
        { name: "Thanksgiving Day", date: getNthWeekdayOfMonth(year, 4, 4, 11) }, // 4th Thursday of November
        { name: "Christmas Day", date: formatDate(new Date(`${year}-12-25`)) }
    ];
}

// Helper function to format dates as YYYY-MM-DD
function formatDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`; // YYYY-MM-DD format
}

// Helper function to get the Nth weekday of a month (e.g., 3rd Monday of January)
function getNthWeekdayOfMonth(year, weekday, n, month) {
    let date = new Date(year, month - 1, 1); // Start at the 1st day of the month
    let count = 0;

    while (date.getMonth() === month - 1) {
        if (date.getDay() === weekday) {
            count++;
            if (count === n) break;
        }
        date.setDate(date.getDate() + 1);
    }
    return formatDate(date);
}

// Helper function to get the last weekday of a month (e.g., last Monday of May)
function getLastWeekdayOfMonth(year, weekday, month) {
    let date = new Date(year, month, 0); // Last day of the month

    while (date.getDay() !== weekday) {
        date.setDate(date.getDate() - 1);
    }
    return formatDate(date);
}

function saveChanges() {
    const currentYear = new Date().getFullYear();
    const Fholidays = getFederalHolidays(currentYear); // Get holidays for the current year

    const holidayNames = jQuery("input[name='holidayName[]']").map(function () {
        return jQuery(this).val().trim().toLowerCase();
    }).get();

    const fromDates = jQuery("input[name='fromDate[]']").map(function () {
        return jQuery(this).val().trim();
    }).get();

    const hasEmptyFields = holidayNames.includes('') || fromDates.includes('');
    const hasDuplicates = new Set(holidayNames).size !== holidayNames.length || 
                          new Set(fromDates).size !== fromDates.length;

    console.log('Holiday Names:', holidayNames);
    console.log('From Dates:', fromDates);
    console.log('Federal Holidays:', Fholidays);

    const isFederalHoliday = holidayNames.some((name, index) =>
        Fholidays.some(fedHoliday =>
            fedHoliday.name.toLowerCase() === name ||
            fedHoliday.date === fromDates[index]
        )
    );

    if (isFederalHoliday) {
        jQuery("#FedHolidayError").show();
        jQuery("#chatError, #HolidayError").hide();
        return false;
    }

    if (hasEmptyFields) {
        jQuery("#chatError").show();
        jQuery("#HolidayError, #FedHolidayError").hide();
        return false;
    }

    if (hasDuplicates) {
        jQuery("#HolidayError").show();
        jQuery("#chatError, #FedHolidayError").hide();
        return false;
    }

    jQuery("#chatError, #HolidayError, #FedHolidayError").hide();

    jQuery.ajax({
        type: "POST",
        url: "addHoliday.php",
        data: jQuery('#holidayForm1').serialize(),
        success: function (data) {
            console.log('Data Saved:', data);
			jQuery(".saveButton")
    .html("Remove")
    .css({
        "background-color": "#C82333",
        "padding": "8px 10px",
        "border": "none",
        "color": "#fff",
        "border-radius": "4px",
        "cursor": "pointer"
    })
    .attr("onclick", "jQuery(this).parent().remove();switchUpdate();");
            jQuery(".holidayName, .fromDate").attr("readonly", true);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error('Error Saving Data:', textStatus, errorThrown);
        }
    });

    return false;
}



function removeHoliday(){
var formdata = jQuery('#holidayForm1').serialize();
var holidayName = jQuery("input[name='holidayName[]']").map(function(){return jQuery(this).val();}).get();
var fromDate = jQuery("input[name='fromDate[]']").map(function(){return jQuery(this).val();}).get();
var outputVal = holidayName+"%%"+fromDate+"%%";
console.log(outputVal);
        jQuery.ajax({
           type: "POST",
           url: "addHoliday.php",
           data: jQuery("#holidayForm1").serialize(), // serializes the form's elements.
           success: function(data)
           {
                jQuery(".saveButton").html("Remove");
                jQuery(".saveButton").attr("onclick","jQuery(this).parent().remove();switchUpdate();removeHoliday();");
           }
         });

    return false; // avoid to execute the actual submit of the form.
}

function switchUpdate(){
	
var holidayName = jQuery("input[name='holidayName[]']").map(function(){return jQuery(this).val();}).get();
var fromDate = jQuery("input[name='fromDate[]']").map(function(){return jQuery(this).val();}).get();
var outputVal = holidayName+"%%"+fromDate+"%%";
//alert(outputVal);
if(outputVal == "%%%%"){
        jQuery("#chatError").hide();
//        jQuery('.formSubmits').removeAttr("disabled");
} else {
if(holidayName.slice(0,1) == "" || holidayName.slice(-1) == "" || holidayName.includes(",,") || fromDate.slice(0,1) == "" || fromDate.slice(-1) == "" || fromDate.includes(",,")){
//alert("INCORRECT");
        jQuery("#chatError").show();
        jQuery('.formSubmits').attr("disabled","disabled");
        } else {
//alert("CORRECT");
	jQuery('.formSubmits').removeAttr("disabled");
        jQuery("#chatError").hide();
        }
}
}

jQuery(document).ready(function(){
	jQuery("#chatForm .toggleCheck").prop("disabled", true);
        jQuery("#chatForm select").prop("disabled", true);
        jQuery("#chatForm textarea").prop("disabled", true);
        jQuery(".formSubmit").prop("disabled", true);
});
function editChatForm() {
    // Enable all inputs, selects, and textareas initially
    jQuery("#chatForm .toggleCheck").prop("disabled", false);
    jQuery("#chatForm textarea").prop("disabled", false);
    jQuery("#chatForm select").prop("disabled", false);
    jQuery(".formSubmit").prop("disabled", false);
}

function monStartVal(){
var monstart = jQuery("#mon_start").val()+":"+jQuery("#mon_start_min").val()+jQuery("#mon_start_ap").val();
var monend = jQuery("#mon_end").val()+":"+jQuery("#mon_end_min").val()+jQuery("#mon_end_ap").val();
//alert(monstart+"%%"+monend);
	if(monstart===monend || (monstart<monend && jQuery("#mon_start_ap").val()=="PM") || (monstart>monend && (jQuery("#mon_start_ap").val()===jQuery("#mon_end_ap").val()))){
	jQuery("#mon_start").css("border-color","#f00");
        jQuery("#mon_start_min").css("border-color","#f00");
        jQuery("#mon_start_ap").css("border-color","#f00");
        jQuery(".formSubmit").prop("disabled", true);
        jQuery("#mon_end").css("border-color","#8c8f94");
        jQuery("#mon_end_min").css("border-color","#8c8f94");
        jQuery("#mon_end_ap").css("border-color","#8c8f94");
	} else {
        jQuery("#mon_start").css("border-color","#8c8f94");
        jQuery("#mon_start_min").css("border-color","#8c8f94");
        jQuery("#mon_start_ap").css("border-color","#8c8f94");
        jQuery(".formSubmit").prop("disabled", false);
        jQuery("#mon_end").css("border-color","#8c8f94");
        jQuery("#mon_end_min").css("border-color","#8c8f94");
        jQuery("#mon_end_ap").css("border-color","#8c8f94");
	}
//alert(monstart);
}

function monEndVal(){
var monstart = jQuery("#mon_start").val()+":"+jQuery("#mon_start_min").val()+jQuery("#mon_start_ap").val();
var monend = jQuery("#mon_end").val()+":"+jQuery("#mon_end_min").val()+jQuery("#mon_end_ap").val();

	 if(monstart===monend || (monstart<monend && monend>="12:00PM") || (monstart>monend && jQuery("#mon_end_ap").val()==jQuery("#mon_start_ap").val()) || (monstart>monend && jQuery("#mon_end_ap").val()=="AM" && jQuery("#mon_start_ap").val()=="PM")|| (monstart<monend && jQuery("#mon_start_ap").val()=="PM" && jQuery("#mon_end_ap").val()=="AM") || (monend>="12:00AM" && jQuery("#mon_end_ap").val()=="AM") || monend=="12:00AM" || (monstart>monend && jQuery("#mon_start_ap").val()=="PM" && jQuery("#mon_end_ap").val()=="PM")){
        jQuery("#mon_end").css("border-color","#f00");
        jQuery("#mon_end_min").css("border-color","#f00");
        jQuery("#mon_end_ap").css("border-color","#f00");
        jQuery(".formSubmit").prop("disabled", true);
        jQuery("#mon_start").css("border-color","#8c8f94");
        jQuery("#mon_start_min").css("border-color","#8c8f94");
        jQuery("#mon_start_ap").css("border-color","#8c8f94");
        } else {
        jQuery("#mon_end").css("border-color","#8c8f94");
        jQuery("#mon_end_min").css("border-color","#8c8f94");
        jQuery("#mon_end_ap").css("border-color","#8c8f94");
        jQuery(".formSubmit").prop("disabled", false);
        jQuery("#mon_start").css("border-color","#8c8f94");
        jQuery("#mon_start_min").css("border-color","#8c8f94");
        jQuery("#mon_start_ap").css("border-color","#8c8f94");
        }
//alert(monstart);
}

function tueStartVal(){
var tuestart = jQuery("#tue_start").val()+":"+jQuery("#tue_start_min").val()+jQuery("#tue_start_ap").val();
var tueend = jQuery("#tue_end").val()+":"+jQuery("#tue_end_min").val()+jQuery("#tue_end_ap").val();

	if(tuestart===tueend || (tuestart<tueend && jQuery("#tue_start_ap").val()=="PM") || (tuestart>tueend && (jQuery("#tue_start_ap").val()===jQuery("#tue_end_ap").val()))){
        jQuery("#tue_start").css("border-color","#f00");
        jQuery("#tue_start_min").css("border-color","#f00");
        jQuery("#tue_start_ap").css("border-color","#f00");
        jQuery(".formSubmit").prop("disabled", true);
        jQuery("#tue_end").css("border-color","#8c8f94");
        jQuery("#tue_end_min").css("border-color","#8c8f94");
        jQuery("#tue_end_ap").css("border-color","#8c8f94");
        } else {
        jQuery("#tue_start").css("border-color","#8c8f94");
        jQuery("#tue_start_min").css("border-color","#8c8f94");
        jQuery("#tue_start_ap").css("border-color","#8c8f94");
        jQuery(".formSubmit").prop("disabled", false);
        jQuery("#tue_end").css("border-color","#8c8f94");
        jQuery("#tue_end_min").css("border-color","#8c8f94");
        jQuery("#tue_end_ap").css("border-color","#8c8f94");
        }
//alert(tuestart);
}

function tueEndVal(){
var tuestart = jQuery("#tue_start").val()+":"+jQuery("#tue_start_min").val()+jQuery("#tue_start_ap").val();
var tueend = jQuery("#tue_end").val()+":"+jQuery("#tue_end_min").val()+jQuery("#tue_end_ap").val();

	if(tuestart===tueend || (tuestart<tueend && tueend>="12:00PM") || (tuestart>tueend && jQuery("#tue_end_ap").val()==jQuery("#tue_start_ap").val()) || (tuestart>tueend && jQuery("#tue_end_ap").val()=="AM" && jQuery("#tue_start_ap").val()=="PM")|| (tuestart<tueend && jQuery("#tue_start_ap").val()=="PM" && jQuery("#tue_end_ap").val()=="AM") || (tueend>="12:00AM" && jQuery("#tue_end_ap").val()=="AM") || tueend=="12:00AM" || (tuestart>tueend && jQuery("#tue_start_ap").val()=="PM" && jQuery("#tue_end_ap").val()=="PM")){
        jQuery("#tue_end").css("border-color","#f00");
        jQuery("#tue_end_min").css("border-color","#f00");
        jQuery("#tue_end_ap").css("border-color","#f00");
        jQuery(".formSubmit").prop("disabled", true);
        jQuery("#tue_start").css("border-color","#8c8f94");
        jQuery("#tue_start_min").css("border-color","#8c8f94");
        jQuery("#tue_start_ap").css("border-color","#8c8f94");
        } else {
        jQuery("#tue_end").css("border-color","#8c8f94");
        jQuery("#tue_end_min").css("border-color","#8c8f94");
        jQuery("#tue_end_ap").css("border-color","#8c8f94");
        jQuery(".formSubmit").prop("disabled", false);
        jQuery("#tue_start").css("border-color","#8c8f94");
        jQuery("#tue_start_min").css("border-color","#8c8f94");
        jQuery("#tue_start_ap").css("border-color","#8c8f94");
        }
//alert(tuestart);
}
function wedStartVal(){
var wedstart = jQuery("#wed_start").val()+":"+jQuery("#wed_start_min").val()+jQuery("#wed_start_ap").val();
var wedend = jQuery("#wed_end").val()+":"+jQuery("#wed_end_min").val()+jQuery("#wed_end_ap").val();
//alert(wedstart+"%%"+wedend);
        if(wedstart===wedend || (wedstart<wedend && jQuery("#wed_start_ap").val()=="PM") || (wedstart>wedend && (jQuery("#wed_start_ap").val()===jQuery("#wed_end_ap").val()))){
        jQuery("#wed_start").css("border-color","#f00");
        jQuery("#wed_start_min").css("border-color","#f00");
        jQuery("#wed_start_ap").css("border-color","#f00");
        jQuery(".formSubmit").prop("disabled", true);
        jQuery("#wed_end").css("border-color","#8c8f94");
        jQuery("#wed_end_min").css("border-color","#8c8f94");
        jQuery("#wed_end_ap").css("border-color","#8c8f94");
        } else {
        jQuery("#wed_start").css("border-color","#8c8f94");
        jQuery("#wed_start_min").css("border-color","#8c8f94");
        jQuery("#wed_start_ap").css("border-color","#8c8f94");
        jQuery(".formSubmit").prop("disabled", false);
        jQuery("#wed_end").css("border-color","#8c8f94");
        jQuery("#wed_end_min").css("border-color","#8c8f94");
        jQuery("#wed_end_ap").css("border-color","#8c8f94");
        }
//alert(wedstart);
}

function wedEndVal(){
var wedstart = jQuery("#wed_start").val()+":"+jQuery("#wed_start_min").val()+jQuery("#wed_start_ap").val();
var wedend = jQuery("#wed_end").val()+":"+jQuery("#wed_end_min").val()+jQuery("#wed_end_ap").val();
//alert(wedstart+"%%"+wedend);
var con1 = wedstart<wedend && jQuery("#wed_end_ap").val()>="12:00PM";
var con2 = wedstart>wedend && jQuery("#wed_end_ap").val()==jQuery("#wed_start_ap").val();
var con3 = wedstart>wedend && jQuery("#wed_end_ap").val()=="AM" && jQuery("#wed_start_ap").val()=="PM";
var con4 = wedstart<wedend && jQuery("#wed_start_ap").val()=="PM" && jQuery("#wed_end_ap").val()=="AM";
var con5 = wedend>="12:00AM" && jQuery("#wed_end_ap").val()=="AM";
var con6 = wedstart>wedend && jQuery("#wed_start_ap").val()=="PM" && jQuery("#wed_end_ap").val()=="PM";

//alert(wedstart===wedend);
//alert(con1+"%%"+con2+"%%"+con3+"%%"+con4+"%%"+con5+"%%"+con6);
        if(wedstart===wedend || (wedstart<wedend && wedend>="12:00PM") || (wedstart>wedend && jQuery("#wed_end_ap").val()==jQuery("#wed_start_ap").val()) || (wedstart>wedend && jQuery("#wed_end_ap").val()=="AM" && jQuery("#wed_start_ap").val()=="PM")|| (wedstart<wedend && jQuery("#wed_start_ap").val()=="PM" && jQuery("#wed_end_ap").val()=="AM") || (wedend>="12:00AM" && jQuery("#wed_end_ap").val()=="AM") || wedend=="12:00AM" || (wedstart>wedend && jQuery("#wed_start_ap").val()=="PM" && jQuery("#wed_end_ap").val()=="PM")){
        jQuery("#wed_end").css("border-color","#f00");
        jQuery("#wed_end_min").css("border-color","#f00");
        jQuery("#wed_end_ap").css("border-color","#f00");
        jQuery(".formSubmit").prop("disabled", true);
	jQuery("#wed_start").css("border-color","#8c8f94");
        jQuery("#wed_start_min").css("border-color","#8c8f94");
        jQuery("#wed_start_ap").css("border-color","#8c8f94");
        } else {
        jQuery("#wed_end").css("border-color","#8c8f94");
        jQuery("#wed_end_min").css("border-color","#8c8f94");
        jQuery("#wed_end_ap").css("border-color","#8c8f94");
        jQuery(".formSubmit").prop("disabled", false);
        jQuery("#wed_start").css("border-color","#8c8f94");
        jQuery("#wed_start_min").css("border-color","#8c8f94");
        jQuery("#wed_start_ap").css("border-color","#8c8f94");
        }
//alert(wedstart);
}
function thuStartVal(){
var thustart = jQuery("#thu_start").val()+":"+jQuery("#thu_start_min").val()+jQuery("#thu_start_ap").val();
var thuend = jQuery("#thu_end").val()+":"+jQuery("#thu_end_min").val()+jQuery("#thu_end_ap").val();

	if(thustart===thuend || (thustart<thuend && jQuery("#thu_start_ap").val()=="PM") || (thustart>thuend && (jQuery("#thu_start_ap").val()===jQuery("#thu_end_ap").val()))){
        jQuery("#thu_start").css("border-color","#f00");
        jQuery("#thu_start_min").css("border-color","#f00");
        jQuery("#thu_start_ap").css("border-color","#f00");
        jQuery(".formSubmit").prop("disabled", true);
        jQuery("#thu_end").css("border-color","#8c8f94");
        jQuery("#thu_end_min").css("border-color","#8c8f94");
        jQuery("#thu_end_ap").css("border-color","#8c8f94");
        } else {
        jQuery("#thu_start").css("border-color","#8c8f94");
        jQuery("#thu_start_min").css("border-color","#8c8f94");
        jQuery("#thu_start_ap").css("border-color","#8c8f94");
        jQuery(".formSubmit").prop("disabled", false);
        jQuery("#thu_end").css("border-color","#8c8f94");
        jQuery("#thu_end_min").css("border-color","#8c8f94");
        jQuery("#thu_end_ap").css("border-color","#8c8f94");
        }
//alert(thustart);
}

function thuEndVal(){
var thustart = jQuery("#thu_start").val()+":"+jQuery("#thu_start_min").val()+jQuery("#thu_start_ap").val();
var thuend = jQuery("#thu_end").val()+":"+jQuery("#thu_end_min").val()+jQuery("#thu_end_ap").val();

	if(thustart===thuend || (thustart<thuend && thuend>="12:00PM") || (thustart>thuend && jQuery("#thu_end_ap").val()==jQuery("#thu_start_ap").val()) || (thustart>thuend && jQuery("#thu_end_ap").val()=="AM" && jQuery("#thu_start_ap").val()=="PM")|| (thustart<thuend && jQuery("#thu_start_ap").val()=="PM" && jQuery("#thu_end_ap").val()=="AM") || (thuend>="12:00AM" && jQuery("#thu_end_ap").val()=="AM") || thuend=="12:00AM" || (thustart>thuend && jQuery("#thu_start_ap").val()=="PM" && jQuery("#thu_end_ap").val()=="PM")){
        jQuery("#thu_end").css("border-color","#f00");
        jQuery("#thu_end_min").css("border-color","#f00");
        jQuery("#thu_end_ap").css("border-color","#f00");
        jQuery(".formSubmit").prop("disabled", true);
        jQuery("#thu_start").css("border-color","#8c8f94");
        jQuery("#thu_start_min").css("border-color","#8c8f94");
        jQuery("#thu_start_ap").css("border-color","#8c8f94");
        } else {
        jQuery("#thu_end").css("border-color","#8c8f94");
        jQuery("#thu_end_min").css("border-color","#8c8f94");
        jQuery("#thu_end_ap").css("border-color","#8c8f94");
        jQuery(".formSubmit").prop("disabled", false);
        jQuery("#thu_start").css("border-color","#8c8f94");
        jQuery("#thu_start_min").css("border-color","#8c8f94");
        jQuery("#thu_start_ap").css("border-color","#8c8f94");
        }
//alert(thustart);
}
function friStartVal(){
var fristart = jQuery("#fri_start").val()+":"+jQuery("#fri_start_min").val()+jQuery("#fri_start_ap").val();
var friend = jQuery("#fri_end").val()+":"+jQuery("#fri_end_min").val()+jQuery("#fri_end_ap").val();

	if(fristart===friend || (fristart<friend && jQuery("#fri_start_ap").val()=="PM") || (fristart>friend && (jQuery("#fri_start_ap").val()===jQuery("#fri_end_ap").val()))){
        jQuery("#fri_start").css("border-color","#f00");
        jQuery("#fri_start_min").css("border-color","#f00");
        jQuery("#fri_start_ap").css("border-color","#f00");
        jQuery(".formSubmit").prop("disabled", true);
        jQuery("#fri_end").css("border-color","#8c8f94");
        jQuery("#fri_end_min").css("border-color","#8c8f94");
        jQuery("#fri_end_ap").css("border-color","#8c8f94");
        } else {
        jQuery("#fri_start").css("border-color","#8c8f94");
        jQuery("#fri_start_min").css("border-color","#8c8f94");
        jQuery("#fri_start_ap").css("border-color","#8c8f94");
        jQuery(".formSubmit").prop("disabled", false);
        jQuery("#fri_end").css("border-color","#8c8f94");
        jQuery("#fri_end_min").css("border-color","#8c8f94");
        jQuery("#fri_end_ap").css("border-color","#8c8f94");
        }
//alert(fristart);
}

function friEndVal(){
var fristart = jQuery("#fri_start").val()+":"+jQuery("#fri_start_min").val()+jQuery("#fri_start_ap").val();
var friend = jQuery("#fri_end").val()+":"+jQuery("#fri_end_min").val()+jQuery("#fri_end_ap").val();

	if(fristart===friend || (fristart<friend && friend>="12:00PM") || (fristart>friend && jQuery("#fri_end_ap").val()==jQuery("#fri_start_ap").val()) || (fristart>friend && jQuery("#fri_end_ap").val()=="AM" && jQuery("#fri_start_ap").val()=="PM")|| (fristart<friend && jQuery("#fri_start_ap").val()=="PM" && jQuery("#fri_end_ap").val()=="AM") || (friend>="12:00AM" && jQuery("#fri_end_ap").val()=="AM") || friend=="12:00AM" || (fristart>friend && jQuery("#fri_start_ap").val()=="PM" && jQuery("#fri_end_ap").val()=="PM")){
        jQuery("#fri_end").css("border-color","#f00");
        jQuery("#fri_end_min").css("border-color","#f00");
        jQuery("#fri_end_ap").css("border-color","#f00");
        jQuery(".formSubmit").prop("disabled", true);
        jQuery("#fri_start").css("border-color","#8c8f94");
        jQuery("#fri_start_min").css("border-color","#8c8f94");
        jQuery("#fri_start_ap").css("border-color","#8c8f94");
        } else {
        jQuery("#fri_end").css("border-color","#8c8f94");
        jQuery("#fri_end_min").css("border-color","#8c8f94");
        jQuery("#fri_end_ap").css("border-color","#8c8f94");
        jQuery(".formSubmit").prop("disabled", false);
        jQuery("#fri_start").css("border-color","#8c8f94");
        jQuery("#fri_start_min").css("border-color","#8c8f94");
        jQuery("#fri_start_ap").css("border-color","#8c8f94");
        }
//alert(fristart);
}
function satStartVal(){
var satstart = jQuery("#sat_start").val()+":"+jQuery("#sat_start_min").val()+jQuery("#sat_start_ap").val();
var satend = jQuery("#sat_end").val()+":"+jQuery("#sat_end_min").val()+jQuery("#sat_end_ap").val();

	if(satstart===satend || (satstart<satend && jQuery("#sat_start_ap").val()=="PM") || (satstart>satend && (jQuery("#sat_start_ap").val()===jQuery("#sat_end_ap").val()))){
        jQuery("#sat_start").css("border-color","#f00");
        jQuery("#sat_start_min").css("border-color","#f00");
        jQuery("#sat_start_ap").css("border-color","#f00");
        jQuery(".formSubmit").prop("disabled", true);
        jQuery("#sat_end").css("border-color","#8c8f94");
        jQuery("#sat_end_min").css("border-color","#8c8f94");
        jQuery("#sat_end_ap").css("border-color","#8c8f94");
        } else {
        jQuery("#sat_start").css("border-color","#8c8f94");
        jQuery("#sat_start_min").css("border-color","#8c8f94");
        jQuery("#sat_start_ap").css("border-color","#8c8f94");
        jQuery(".formSubmit").prop("disabled", false);
        jQuery("#sat_end").css("border-color","#8c8f94");
        jQuery("#sat_end_min").css("border-color","#8c8f94");
        jQuery("#sat_end_ap").css("border-color","#8c8f94");
        }
//alert(satstart);
}

function satEndVal(){
var satstart = jQuery("#sat_start").val()+":"+jQuery("#sat_start_min").val()+jQuery("#sat_start_ap").val();
var satend = jQuery("#sat_end").val()+":"+jQuery("#sat_end_min").val()+jQuery("#sat_end_ap").val();

	if(satstart===satend || (satstart<satend && satend>="12:00PM") || (satstart>satend && jQuery("#sat_end_ap").val()==jQuery("#sat_start_ap").val()) || (satstart>satend && jQuery("#sat_end_ap").val()=="AM" && jQuery("#sat_start_ap").val()=="PM")|| (satstart<satend && jQuery("#sat_start_ap").val()=="PM" && jQuery("#sat_end_ap").val()=="AM") || (satend>="12:00AM" && jQuery("#sat_end_ap").val()=="AM") || satend=="12:00AM" || (satstart>satend && jQuery("#sat_start_ap").val()=="PM" && jQuery("#sat_end_ap").val()=="PM")){
        jQuery("#sat_end").css("border-color","#f00");
        jQuery("#sat_end_min").css("border-color","#f00");
        jQuery("#sat_end_ap").css("border-color","#f00");
        jQuery(".formSubmit").prop("disabled", true);
        jQuery("#sat_start").css("border-color","#8c8f94");
        jQuery("#sat_start_min").css("border-color","#8c8f94");
        jQuery("#sat_start_ap").css("border-color","#8c8f94");
        } else {
        jQuery("#sat_end").css("border-color","#8c8f94");
        jQuery("#sat_end_min").css("border-color","#8c8f94");
        jQuery("#sat_end_ap").css("border-color","#8c8f94");
        jQuery(".formSubmit").prop("disabled", false);
        jQuery("#sat_start").css("border-color","#8c8f94");
        jQuery("#sat_start_min").css("border-color","#8c8f94");
        jQuery("#sat_start_ap").css("border-color","#8c8f94");
        }
//alert(satstart);
}
function sunStartVal(){
var sunstart = jQuery("#sun_start").val()+":"+jQuery("#sun_start_min").val()+jQuery("#sun_start_ap").val();
var sunend = jQuery("#sun_end").val()+":"+jQuery("#sun_end_min").val()+jQuery("#sun_end_ap").val();

	if(sunstart===sunend || (sunstart<sunend && jQuery("#sun_start_ap").val()=="PM") || (sunstart>sunend && (jQuery("#sun_start_ap").val()===jQuery("#sun_end_ap").val()))){
        jQuery("#sun_start").css("border-color","#f00");
        jQuery("#sun_start_min").css("border-color","#f00");
        jQuery("#sun_start_ap").css("border-color","#f00");
        jQuery(".formSubmit").prop("disabled", true);
        jQuery("#sun_end").css("border-color","#8c8f94");
        jQuery("#sun_end_min").css("border-color","#8c8f94");
        jQuery("#sun_end_ap").css("border-color","#8c8f94");
        } else {
        jQuery("#sun_start").css("border-color","#8c8f94");
        jQuery("#sun_start_min").css("border-color","#8c8f94");
        jQuery("#sun_start_ap").css("border-color","#8c8f94");
        jQuery(".formSubmit").prop("disabled", false);
        jQuery("#sun_end").css("border-color","#8c8f94");
        jQuery("#sun_end_min").css("border-color","#8c8f94");
        jQuery("#sun_end_ap").css("border-color","#8c8f94");
        }
//alert(sunstart);
}

function sunEndVal(){
var sunstart = jQuery("#sun_start").val()+":"+jQuery("#sun_start_min").val()+jQuery("#sun_start_ap").val();
var sunend = jQuery("#sun_end").val()+":"+jQuery("#sun_end_min").val()+jQuery("#sun_end_ap").val();

	if(sunstart===sunend || (sunstart<sunend && sunend>="12:00PM") || (sunstart>sunend && jQuery("#sun_end_ap").val()==jQuery("#sun_start_ap").val()) || (sunstart>sunend && jQuery("#sun_end_ap").val()=="AM" && jQuery("#sun_start_ap").val()=="PM")|| (sunstart<sunend && jQuery("#sun_start_ap").val()=="PM" && jQuery("#sun_end_ap").val()=="AM") || (sunend>="12:00AM" && jQuery("#sun_end_ap").val()=="AM") || sunend=="12:00AM" || (sunstart>sunend && jQuery("#sun_start_ap").val()=="PM" && jQuery("#sun_end_ap").val()=="PM")){
        jQuery("#sun_end").css("border-color","#f00");
        jQuery("#sun_end_min").css("border-color","#f00");
        jQuery("#sun_end_ap").css("border-color","#f00");
        jQuery(".formSubmit").prop("disabled", true);
        jQuery("#sun_start").css("border-color","#8c8f94");
        jQuery("#sun_start_min").css("border-color","#8c8f94");
        jQuery("#sun_start_ap").css("border-color","#8c8f94");
        } else {
        jQuery("#sun_end").css("border-color","#8c8f94");
        jQuery("#sun_end_min").css("border-color","#8c8f94");
        jQuery("#sun_end_ap").css("border-color","#8c8f94");
        jQuery(".formSubmit").prop("disabled", false);
        jQuery("#sun_start").css("border-color","#8c8f94");
        jQuery("#sun_start_min").css("border-color","#8c8f94");
        jQuery("#sun_start_ap").css("border-color","#8c8f94");
        }
//alert(sunstart);
}

/*
jQuery('.formSubmits').on('click', function(e) {
     e.preventDefault();
//     nameOfFunction();
//alert("BUTTON CLICKED");
//var holidayName = jQuery(".holidayName").val();
var holidayName = jQuery("input[name='holidayName[]']").map(function(){return jQuery(this).val();}).get();
var fromDate = jQuery("input[name='fromDate[]']").map(function(){return jQuery(this).val();}).get();
//var toDate = jQuery("input[name='toDate[]']").map(function(){return jQuery(this).val();}).get();
//var fromDate = jQuery(".fromDate").val();
//var toDate = jQuery(".toDate").val();
//alert(holidays);
//alert(holidayName+"%%"+fromDate+"%%"+toDate);
var outputVal = holidayName+"%%"+fromDate+"%%";
if(outputVal == "%%%%"){
//alert("CORRECT");
        jQuery("#chatForm").submit();
        jQuery("#chatError").hide();
} else {
//	var getDivs = jQuery(".hiderow").length;
//	alert(holidayName);
//alert(holidayName.slice(0,1));
//	if(holidayName.endsWith(",")||holidayName.includes(",,")){
        if(holidayName.slice(0,1) == "" || holidayName.slice(-1) == "" || holidayName.includes(",,") || fromDate.slice(0,1) == "" || fromDate.slice(-1) == "" || fromDate.includes(",,")){
//alert("INCORRECT");
        jQuery("#chatError").show();
	} else {
//alert("CORRECT");
	jQuery("#chatForm").submit();
	jQuery("#chatError").hide();
	}
}
 });*/
</script>
<?php
require_once ABSPATH . 'wp-admin/admin-footer.php';
