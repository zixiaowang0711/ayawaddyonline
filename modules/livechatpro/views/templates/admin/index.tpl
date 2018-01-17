{*
* ProQuality (c) All rights reserved.
*
* DISCLAIMER
*
* Do not edit, modify or copy this file.
* If you wish to customize it, contact us at addons4prestashop@gmail.com.
*
* @author    Andrei Cimpean (ProQuality) <addons4prestashop@gmail.com>
* @copyright 2015-2016 ProQuality
* @license   Do not edit, modify or copy this file
*}
{assign var=template value="`$smarty.current_dir`\\`$smarty.template`"}
<div class="bootstrap row">
	<div class="col-lg-2">
		<div class="list-group tabs-menu">
			<a class="list-group-item active" id="tabs-settings-a" href="#tabs-settings"><span class="fa fa-gear"></span> {lang s='Settings'}</a>
			<a class="list-group-item" id="tabs-departments-a" href="#tabs-departments"><span class="fa fa-support"></span> {lang s='Departments'}</a>
			<a class="list-group-item" id="tabs-staff-profiles-a" href="#tabs-staff-profiles"><span class="fa fa-user"></span> {lang s='Staff Profiles'}</a>
			<a class="list-group-item" id="tabs-predefined-messages-a" href="#tabs-predefined-messages"><span class="fa fa-envelope"></span> {lang s='Predefined Messages'}</a>
			<a class="list-group-item" id="tabs-translations-a" href="#tabs-translations"><span class="fa fa-flag"></span> {lang s='Translations'} <span id="lang_ajax_load_span" style="padding-left: 5px;"></span></a>
			<a class="list-group-item" id="tabs-clear-database-a" href="#tabs-clear-database"><span class="fa fa-database"></span> {lang s='Clear the database'}</a>
		</div>

		<div class="lcp panel" style="line-height: 25px;">{lang s='Version:'} <b>{$module_version|escape:'quotes':'UTF-8'}</b></div>
	</div>

<div class="col-lg-10 lcp panel">

	<div id="tabs-settings" class="tab-content">
		
		<div class="row">
			<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 lcp border-right" id="settings_ajax_div">
				
				<div class="row lcp panel-head border-bottom">
					{lang s='Settings'}
				</div>

				<form class="form-horizontal">
		
					<div class="row lcp panel-head border-bottom" style="padding-top: 5px;">
						{lang s='Offline Configuration'}
					</div>
					<br>
					<div class="form-group">
						<label for="offline_messages_go_to" class="col-sm-3 control-label">{lang s='Where should offline messages go?:'}</label>
						<div class="col-sm-9">
							<input type="text" class="form-control fixed-width-xl" name="offline_messages_go_to" id="offline_messages_go_to" placeholder="demo@demo.com">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-3 control-label" style="line-height: 40px; vertical-align: middle;">{lang s='Name field offline:'}</label>
						<div class="col-sm-9">
							<div class="pull-left">
							<div style="text-align: center;">{lang s='Display:'}</div>
							<span class="switch prestashop-switch fixed-width-lg">
								<input type="radio" name="name_field_offline" id="name_field_offline_y" value="Y">
					    		<label class="radioCheck" for="name_field_offline_y">{lang s='Yes'}</label>
					    		<input type="radio" name="name_field_offline" id="name_field_offline_n" value="N">
					    		<label class="radioCheck" for="name_field_offline_n">{lang s='No'}</label>
					    		<a class="slide-button btn"></a>
							</span>
							</div>
							<div class="pull-left" style="padding-left: 10px;">
							<div style="text-align: center;">{lang s='Mandatory:'}</div>
							<span class="switch prestashop-switch fixed-width-lg">
								<input type="radio" name="name_field_offline_mandatory" id="name_field_offline_mandatory_y" value="Y">
					    		<label class="radioCheck" for="name_field_offline_mandatory_y">{lang s='Yes'}</label>
					    		<input type="radio" name="name_field_offline_mandatory" id="name_field_offline_mandatory_n" value="N">
					    		<label class="radioCheck" for="name_field_offline_mandatory_n">{lang s='No'}</label>
					    		<a class="slide-button btn"></a>
							</span>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label" style="line-height: 40px; vertical-align: middle;">{lang s='Email field offline:'}</label>
						<div class="col-sm-9">
							<div class="pull-left">
							<div style="text-align: center;">{lang s='Display:'}</div>
							<span class="switch prestashop-switch fixed-width-lg">
								<input type="radio" name="email_field_offline" id="email_field_offline_y" value="Y">
					    		<label class="radioCheck" for="email_field_offline_y">{lang s='Yes'}</label>
					    		<input type="radio" name="email_field_offline" id="email_field_offline_n" value="N">
					    		<label class="radioCheck" for="email_field_offline_n">{lang s='No'}</label>
					    		<a class="slide-button btn"></a>
							</span>
							</div>
							<div class="pull-left" style="padding-left: 10px;">
							<div style="text-align: center;">{lang s='Mandatory:'}</div>
							<span class="switch prestashop-switch fixed-width-lg">
								<input type="radio" name="email_field_offline_mandatory" id="email_field_offline_mandatory_y" value="Y">
					    		<label class="radioCheck" for="email_field_offline_mandatory_y">{lang s='Yes'}</label>
					    		<input type="radio" name="email_field_offline_mandatory" id="email_field_offline_mandatory_n" value="N">
					    		<label class="radioCheck" for="email_field_offline_mandatory_n">{lang s='No'}</label>
					    		<a class="slide-button btn"></a>
							</span>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label" style="line-height: 40px; vertical-align: middle;">{lang s='Phone field offline:'}</label>
						<div class="col-sm-9">
							<div class="pull-left">
							<div style="text-align: center;">{lang s='Display:'}</div>
							<span class="switch prestashop-switch fixed-width-lg">
								<input type="radio" name="phone_field_offline" id="phone_field_offline_y" value="Y">
					    		<label class="radioCheck" for="phone_field_offline_y">{lang s='Yes'}</label>
					    		<input type="radio" name="phone_field_offline" id="phone_field_offline_n" value="N">
					    		<label class="radioCheck" for="phone_field_offline_n">{lang s='No'}</label>
					    		<a class="slide-button btn"></a>
							</span>
							</div>
							<div class="pull-left" style="padding-left: 10px;">
							<div style="text-align: center;">{lang s='Mandatory:'}</div>
							<span class="switch prestashop-switch fixed-width-lg">
								<input type="radio" name="phone_field_offline_mandatory" id="phone_field_offline_mandatory_y" value="Y">
					    		<label class="radioCheck" for="phone_field_offline_mandatory_y">{lang s='Yes'}</label>
					    		<input type="radio" name="phone_field_offline_mandatory" id="phone_field_offline_mandatory_n" value="N">
					    		<label class="radioCheck" for="phone_field_offline_mandatory_n">{lang s='No'}</label>
					    		<a class="slide-button btn"></a>
							</span>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label" style="line-height: 40px; vertical-align: middle;">{lang s='Department field offline:'}</label>
						<div class="col-sm-9">
							<div class="pull-left">
							<div style="text-align: center;">{lang s='Display:'}</div>
							<span class="switch prestashop-switch fixed-width-lg">
								<input type="radio" name="department_field_offline" id="department_field_offline_y" value="Y">
					    		<label class="radioCheck" for="department_field_offline_y">{lang s='Yes'}</label>
					    		<input type="radio" name="department_field_offline" id="department_field_offline_n" value="N">
					    		<label class="radioCheck" for="department_field_offline_n">{lang s='No'}</label>
					    		<a class="slide-button btn"></a>
							</span>
							</div>
							<div class="pull-left" style="padding-left: 10px;">
							<div style="text-align: center;">{lang s='Mandatory:'}</div>
							<span class="switch prestashop-switch fixed-width-lg">
								<input type="radio" name="department_field_offline_mandatory" id="department_field_offline_mandatory_y" value="Y">
					    		<label class="radioCheck" for="department_field_offline_mandatory_y">{lang s='Yes'}</label>
					    		<input type="radio" name="department_field_offline_mandatory" id="department_field_offline_mandatory_n" value="N">
					    		<label class="radioCheck" for="department_field_offline_mandatory_n">{lang s='No'}</label>
					    		<a class="slide-button btn"></a>
							</span>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label" style="line-height: 40px; vertical-align: middle;">{lang s='Question field offline:'}</label>
						<div class="col-sm-9">
							<div class="pull-left">
							<div style="text-align: center;">{lang s='Display:'}</div>
							<span class="switch prestashop-switch fixed-width-lg">
								<input type="radio" name="question_field_offline" id="question_field_offline_y" value="Y">
					    		<label class="radioCheck" for="question_field_offline_y">{lang s='Yes'}</label>
					    		<input type="radio" name="question_field_offline" id="question_field_offline_n" value="N">
					    		<label class="radioCheck" for="question_field_offline_n">{lang s='No'}</label>
					    		<a class="slide-button btn"></a>
							</span>
							</div>
							<div class="pull-left" style="padding-left: 10px;">
							<div style="text-align: center;">{lang s='Mandatory:'}</div>
							<span class="switch prestashop-switch fixed-width-lg">
								<input type="radio" name="question_field_offline_mandatory" id="question_field_offline_mandatory_y" value="Y">
					    		<label class="radioCheck" for="question_field_offline_mandatory_y">{lang s='Yes'}</label>
					    		<input type="radio" name="question_field_offline_mandatory" id="question_field_offline_mandatory_n" value="N">
					    		<label class="radioCheck" for="question_field_offline_mandatory_n">{lang s='No'}</label>
					    		<a class="slide-button btn"></a>
							</span>
							</div>
						</div>
					</div>


					<br>
					<div class="row lcp panel-head border-bottom">
						{lang s='Online Configuration'}
					</div>
					<br>
					

					<div class="form-group">
						<label class="col-sm-3 control-label" style="line-height: 40px; vertical-align: middle;">{lang s='Name field online:'}</label>
						<div class="col-sm-9">
							<div class="pull-left">
							<div style="text-align: center;">{lang s='Display:'}</div>
							<span class="switch prestashop-switch fixed-width-lg">
								<input type="radio" name="name_field_online" id="name_field_online_y" value="Y">
					    		<label class="radioCheck" for="name_field_online_y">{lang s='Yes'}</label>
					    		<input type="radio" name="name_field_online" id="name_field_online_n" value="N">
					    		<label class="radioCheck" for="name_field_online_n">{lang s='No'}</label>
					    		<a class="slide-button btn"></a>
							</span>
							</div>
							<div class="pull-left" style="padding-left: 10px;">
							<div style="text-align: center;">{lang s='Mandatory:'}</div>
							<span class="switch prestashop-switch fixed-width-lg">
								<input type="radio" name="name_field_online_mandatory" id="name_field_online_mandatory_y" value="Y">
					    		<label class="radioCheck" for="name_field_online_mandatory_y">{lang s='Yes'}</label>
					    		<input type="radio" name="name_field_online_mandatory" id="name_field_online_mandatory_n" value="N">
					    		<label class="radioCheck" for="name_field_online_mandatory_n">{lang s='No'}</label>
					    		<a class="slide-button btn"></a>
							</span>
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-3 control-label" style="line-height: 40px; vertical-align: middle;">{lang s='Email field online:'}</label>
						<div class="col-sm-9">
							<div class="pull-left">
							<div style="text-align: center;">{lang s='Display:'}</div>
							<span class="switch prestashop-switch fixed-width-lg">
								<input type="radio" name="email_field_online" id="email_field_online_y" value="Y">
					    		<label class="radioCheck" for="email_field_online_y">{lang s='Yes'}</label>
					    		<input type="radio" name="email_field_online" id="email_field_online_n" value="N">
					    		<label class="radioCheck" for="email_field_online_n">{lang s='No'}</label>
					    		<a class="slide-button btn"></a>
							</span>
							</div>
							<div class="pull-left" style="padding-left: 10px;">
							<div style="text-align: center;">{lang s='Mandatory:'}</div>
							<span class="switch prestashop-switch fixed-width-lg">
								<input type="radio" name="email_field_online_mandatory" id="email_field_online_mandatory_y" value="Y">
					    		<label class="radioCheck" for="email_field_online_mandatory_y">{lang s='Yes'}</label>
					    		<input type="radio" name="email_field_online_mandatory" id="email_field_online_mandatory_n" value="N">
					    		<label class="radioCheck" for="email_field_online_mandatory_n">{lang s='No'}</label>
					    		<a class="slide-button btn"></a>
							</span>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label" style="line-height: 40px; vertical-align: middle;">{lang s='Phone field online:'}</label>
						<div class="col-sm-9">
							<div class="pull-left">
							<div style="text-align: center;">{lang s='Display:'}</div>
							<span class="switch prestashop-switch fixed-width-lg">
								<input type="radio" name="phone_field_online" id="phone_field_online_y" value="Y">
					    		<label class="radioCheck" for="phone_field_online_y">{lang s='Yes'}</label>
					    		<input type="radio" name="phone_field_online" id="phone_field_online_n" value="N">
					    		<label class="radioCheck" for="phone_field_online_n">{lang s='No'}</label>
					    		<a class="slide-button btn"></a>
							</span>
							</div>
							<div class="pull-left" style="padding-left: 10px;">
							<div style="text-align: center;">{lang s='Mandatory:'}</div>
							<span class="switch prestashop-switch fixed-width-lg">
								<input type="radio" name="phone_field_online_mandatory" id="phone_field_online_mandatory_y" value="Y">
					    		<label class="radioCheck" for="phone_field_online_mandatory_y">{lang s='Yes'}</label>
					    		<input type="radio" name="phone_field_online_mandatory" id="phone_field_online_mandatory_n" value="N">
					    		<label class="radioCheck" for="phone_field_online_mandatory_n">{lang s='No'}</label>
					    		<a class="slide-button btn"></a>
							</span>
							</div>
						</div>
					</div>

				
					<div class="form-group">
						<label class="col-sm-3 control-label" style="line-height: 40px; vertical-align: middle;">{lang s='Department field online:'}</label>
						<div class="col-sm-9">
							<div class="pull-left">
							<div style="text-align: center;">{lang s='Display:'}</div>
							<span class="switch prestashop-switch fixed-width-lg">
								<input type="radio" name="department_field_online" id="department_field_online_y" value="Y">
					    		<label class="radioCheck" for="department_field_online_y">{lang s='Yes'}</label>
					    		<input type="radio" name="department_field_online" id="department_field_online_n" value="N">
					    		<label class="radioCheck" for="department_field_online_n">{lang s='No'}</label>
					    		<a class="slide-button btn"></a>
							</span>
							</div>
							<div class="pull-left" style="padding-left: 10px;">
							<div style="text-align: center;">{lang s='Mandatory:'}</div>
							<span class="switch prestashop-switch fixed-width-lg">
								<input type="radio" name="department_field_online_mandatory" id="department_field_online_mandatory_y" value="Y">
					    		<label class="radioCheck" for="department_field_online_mandatory_y">{lang s='Yes'}</label>
					    		<input type="radio" name="department_field_online_mandatory" id="department_field_online_mandatory_n" value="N">
					    		<label class="radioCheck" for="department_field_online_mandatory_n">{lang s='No'}</label>
					    		<a class="slide-button btn"></a>
							</span>
							</div>
						</div>
					</div>

				
					<div class="form-group">
						<label class="col-sm-3 control-label" style="line-height: 40px; vertical-align: middle;">{lang s='Question field online:'}</label>
						<div class="col-sm-9">
							<div class="pull-left">
							<div style="text-align: center;">{lang s='Display:'}</div>
							<span class="switch prestashop-switch fixed-width-lg">
								<input type="radio" name="question_field_online" id="question_field_online_y" value="Y">
					    		<label class="radioCheck" for="question_field_online_y">{lang s='Yes'}</label>
					    		<input type="radio" name="question_field_online" id="question_field_online_n" value="N">
					    		<label class="radioCheck" for="question_field_online_n">{lang s='No'}</label>
					    		<a class="slide-button btn"></a>
							</span>
							</div>
							<div class="pull-left" style="padding-left: 10px;">
							<div style="text-align: center;">{lang s='Mandatory:'}</div>
							<span class="switch prestashop-switch fixed-width-lg">
								<input type="radio" name="question_field_online_mandatory" id="question_field_online_mandatory_y" value="Y">
					    		<label class="radioCheck" for="question_field_online_mandatory_y">{lang s='Yes'}</label>
					    		<input type="radio" name="question_field_online_mandatory" id="question_field_online_mandatory_n" value="N">
					    		<label class="radioCheck" for="question_field_online_mandatory_n">{lang s='No'}</label>
					    		<a class="slide-button btn"></a>
							</span>
							</div>
						</div>
					</div>

					<br>

					<div class="row lcp panel-head border-bottom">
						{lang s='General settings'}
					</div>
					<br>

  					<div class="form-group">
						<label for="host_type" class="col-sm-3 control-label">{lang s='Host type:'}</label>
						<div class="col-sm-9">
							<span class="switch prestashop-switch fixed-width-xl">
								<input type="radio" name="host_type" id="hosted_self" value="Self" disabled> 
								<label class="radioCheck" for="hosted_self">{lang s='Local'}</label>
								<input type="radio" name="host_type" id="hosted_remote" value="Remote" disabled> 
								<label class="radioCheck" for="hosted_remote">{lang s='Remote'}</label>
								<a class="slide-button btn"></a>
							</span>
					    	<span id="lcp_hosted_self_desc_span" class="help-block">{lang s='If you choose `Local` the chat will be stand-alone it works even without internet connection using ajax requests periodically to send and receive the data to the server and back. This option makes the chat to be synched every 7 seconds by default, this will be the maximum lag time that staff or client will be updated. This option is recommended for small online stores.'}</span>
							<span id="lcp_hosted_remote_desc_span" class="help-block" style="display: none;">{lang s='If you choose `Remote` the chat will send all the messages, encrypted to an external server (www.fanout.io) site that uses sockets to send and receive messages instantly. You have to make a free account to that site and put the (realmid) and the (realmkey) in the fields below in order for the connection to be established. This type has the advantage that it send the data to the fanout.io cloud and unloads your local server, also the chat has no lag. This option is recommended for big online stores with a lot of visitors.'}</span>
					    </div>
					</div>
					<div id="performance_settings_tbody" style="display:none"> 
						<div class="form-group">
							<label for="lcp_sync_chat_interval_backend" class="col-sm-3 control-label">{lang s='Sync Chat Interval Backend:'}</label>
							<div class="col-sm-9">
								<input {if $employee_is_superadmin != 'Y'}disabled{/if} type="text" class="form-control fixed-width-xs pull-left" name="lcp_sync_chat_interval_backend" id="lcp_sync_chat_interval_backend" placeholder="7">
								<div class="pull-left">&nbsp;{lang s='seconds'}</div>
								<div class="pull-left help-block" style="margin: 0px; padding: 0px;">&nbsp;{lang s='(default is 5 seconds)'}</div>
							</div>
						</div>
					
						<div class="form-group">
							<label for="lcp_sync_chat_interval_frontend" class="col-sm-3 control-label">{lang s='Sync Chat Interval Frontend:'}</label>
							<div class="col-sm-9">
								<input {if $employee_is_superadmin != 'Y'}disabled{/if} type="text" class="form-control fixed-width-xs pull-left" name="lcp_sync_chat_interval_frontend" id="lcp_sync_chat_interval_frontend" placeholder="7">
								<div class="pull-left">&nbsp;{lang s='seconds'}</div>
								<div class="pull-left help-block" style="margin: 0px; padding: 0px;">&nbsp;{lang s='(default is 5 seconds)'}</div>
							</div>
						</div>
					</div>

					<div id="remote_settings_tbody" style="display:none"> 
						<div class="form-group">
							<label for="lcp_realm_id" class="col-sm-3 control-label">{lang s='Realm ID:'}</label>
							<div class="col-sm-9">
								<input {if $employee_is_superadmin != 'Y'}disabled{/if} type="text" class="form-control fixed-width-xl" name="lcp_realm_id" id="lcp_realm_id" placeholder="">
							</div>
						</div>

						<div class="form-group">
							<label for="lcp_realm_key" class="col-sm-3 control-label">{lang s='Realm Key:'}</label>
							<div class="col-sm-9">
								<input {if $employee_is_superadmin != 'Y'}disabled{/if} type="text" class="form-control fixed-width-xl" name="lcp_realm_key" id="lcp_realm_key" placeholder="">
							</div>
						</div>
					
					
						<div class="form-group">
						    <div class="col-sm-offset-3 col-sm-9">
						    	<button type="button" class="btn btn-default" name="lcp_test_fanout_connection" id="lcp_test_fanout_connection">{lang s='Test connection'}</button>
						    	<span id="lcp_test_fanout_connection_ajax_load_span" style="padding-left: 0px;"></span>
						    </div>
						</div>

					</div>

					<hr>
					{*
					<div class="form-group">
						<label for="pushover_notifications" class="col-sm-3 control-label">{lang s='Pushover notifications:'}</label>
						<div class="col-sm-9">
							<span class="switch prestashop-switch fixed-width-lg">
								<input type="radio" name="pushover_notifications" id="pushover_notifications_y" value="Y"> 
								<label class="radioCheck" for="pushover_notifications_y">{lang s='Yes'}</label>
								<input type="radio" name="pushover_notifications" id="pushover_notifications_n" value="N"> 
								<label class="radioCheck" for="pushover_notifications_n">{lang s='No'}</label>
								<a class="slide-button btn"></a>
							</span>
					    	<span id="lcp_pushover_notifications_desc_span" class="help-block">{lang s='If you enable this option it allows you to use the www.pushover.net service in order to send real-time notifications when you get a new chat request or a new message. You need to configure a www.pushover.net account and download their app from GooglePlay or AppStore. With this option you can communicate faster and easyer from mobile devices.'}</span>
					    </div>
					</div>

					<div id="pushover_notifications_tbody" style=""> 
						<div class="form-group">
							<label for="lcp_pushover_notifications_user_token" class="col-sm-3 control-label">{lang s='User Token:'}</label>
							<div class="col-sm-9">
								<input type="text" class="form-control fixed-width-xxl pull-left" name="lcp_pushover_notifications_user_token" id="lcp_pushover_notifications_user_token">
							</div>
						</div>
					
						<div class="form-group">
							<label for="lcp_pushover_notifications_app_token" class="col-sm-3 control-label">{lang s='Application Token:'}</label>
							<div class="col-sm-9">
								<input type="text" class="form-control fixed-width-xxl pull-left" name="lcp_pushover_notifications_app_token" id="lcp_pushover_notifications_app_token">
							</div>
						</div>

						<div class="form-group" style="">
							<label for="lcp_pushover_notifications_to_devices" class="col-sm-3 control-label">{lang s='Push To devices:'}</label>
							<div class="col-sm-9">
								<input type="text" class="form-control fixed-width-xxl pull-left" name="lcp_pushover_notifications_to_devices" id="lcp_pushover_notifications_to_devices">
								<div class="pull-left">&nbsp;{lang s='(leave empty to push to all devices)'}</div>
								<div class="pull-left help-block" style="margin: 0px; padding: 0px;">&nbsp;{lang s='Insert here the device or devices list separated by comma that you have configured in your pushover.net account if you want to push the notifications to certain devices, or leave empty for all.'}</div>
							</div>
						</div>
					</div>
					<hr>
					*}

					<div class="form-group">
						<label for="new_chat_sound" class="col-sm-3 control-label">{lang s='New chat sound'}</label>
						<div class="col-sm-9">
							<select name="new_chat_sound" id="new_chat_sound" class="form-control fixed-width-xl">
								<option value="none">{lang s='-no sound-'}</option>
								<option value="new-chat-default.mp3">new-chat-default.mp3</option>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label for="new_message_sound" class="col-sm-3 control-label">{lang s='New message sound'}</label>
						<div class="col-sm-9">
							<select name="new_message_sound" id="new_message_sound" class="form-control fixed-width-xl">
								<option value="none">{lang s='-no sound-'}</option>
								<option value="new-message-default.mp3">new-message-default.mp3</option>
							</select>
						</div>
					</div>

					<div class="form-group" id="">
						<label class="col-sm-3 control-label">{lang s='Show staff / client names:'}</label>
						<div class="col-sm-9">
							<span class="switch prestashop-switch fixed-width-lg">
					    		<input type="radio" name="show_names" id="show_names_y" value="Y">
					    		<label class="radioCheck" for="show_names_y">{lang s='Yes'}</label>
					    		<input type="radio" name="show_names" id="show_names_n" value="N">
					    		<label class="radioCheck" for="show_names_n">{lang s='No'}</label>
								<a class="slide-button btn"></a>
					    	</span>
					    	<p class="help-block">{lang s='This option gives the possibility to display or hide the customer / staff names when they write a message in the chat box.'}</p>
					    </div>
					</div>

					<div class="form-group" id="">
						<label class="col-sm-3 control-label">{lang s='Show staff / client avatars:'}</label>
						<div class="col-sm-9">
							<span class="switch prestashop-switch fixed-width-lg">
					    		<input type="radio" name="show_avatars" id="show_avatars_y" value="Y">
					    		<label class="radioCheck" for="show_avatars_y">{lang s='Yes'}</label>
					    		<input type="radio" name="show_avatars" id="show_avatars_n" value="N">
					    		<label class="radioCheck" for="show_avatars_n">{lang s='No'}</label>
								<a class="slide-button btn"></a>
					    	</span>
					    	<p class="help-block">{lang s='This option gives the possibility to display or hide the customer / staff avatars when they write a message in the chat box.'}</p>
					    </div>
					</div>

					<div class="form-group" id="">
						<label class="col-sm-3 control-label">{lang s='Visitors can upload files:'}</label>
						<div class="col-sm-9">
							<span class="switch prestashop-switch fixed-width-lg">
					    		<input type="radio" name="visitors_can_upload_files" id="visitors_can_upload_files_y" value="Y">
					    		<label class="radioCheck" for="visitors_can_upload_files_y">{lang s='Yes'}</label>
					    		<input type="radio" name="visitors_can_upload_files" id="visitors_can_upload_files_n" value="N">
					    		<label class="radioCheck" for="visitors_can_upload_files_n">{lang s='No'}</label>
								<a class="slide-button btn"></a>
					    	</span>
					    	<p class="help-block">{lang s='Permit or restrict the possibility for the visitors to upload files in chat conversations.'}</p>
					    </div>
					</div>

					<div class="form-group" id="">
						<label class="col-sm-3 control-label">{lang s='Show popup alert on income chats:'}</label>
						<div class="col-sm-9">
							<span class="switch prestashop-switch fixed-width-lg">
					    		<input type="radio" name="popup_alert_on_income_chats" id="popup_alert_on_income_chats_y" value="Y">
					    		<label class="radioCheck" for="popup_alert_on_income_chats_y">{lang s='Yes'}</label>
					    		<input type="radio" name="popup_alert_on_income_chats" id="popup_alert_on_income_chats_n" value="N">
					    		<label class="radioCheck" for="popup_alert_on_income_chats_n">{lang s='No'}</label>
								<a class="slide-button btn"></a>
					    	</span>
					    	<p class="help-block">{lang s='If you choose "Yes", staff will be alerted by a popup when a new chat request is triggered.'}</p>
					    </div>
					</div>

					<div class="form-group" id="">
						<label class="col-sm-3 control-label">{lang s='Staff qualification:'}</label>
						<div class="col-sm-9">
							<span class="switch prestashop-switch fixed-width-lg">
					    		<input type="radio" name="staff_qualification" id="staff_qualification_y" value="Y">
					    		<label class="radioCheck" for="staff_qualification_y">{lang s='Yes'}</label>
					    		<input type="radio" name="staff_qualification" id="staff_qualification_n" value="N">
					    		<label class="radioCheck" for="staff_qualification_n">{lang s='No'}</label>
								<a class="slide-button btn"></a>
					    	</span>
					    	<p class="help-block">{lang s='If you set to "No", the customer is not able to rate the staff members.'}</p>
					    </div>
					</div>
					
					<div class="form-group" id="">
						<label class="col-sm-3 control-label">{lang s='New chat rings to staff:'}</label>
						<div class="col-sm-9">
							<span class="switch prestashop-switch fixed-width-xxl">
					    		<input type="radio" name="new_chat_rings_to" id="new_chat_rings_to_most_available" value="most-available">
					    		<label class="radioCheck" for="new_chat_rings_to_most_available">{lang s='Most Available'}</label>
					    		<input type="radio" name="new_chat_rings_to" id="new_chat_rings_to_all" value="all">
					    		<label class="radioCheck" for="new_chat_rings_to_all">{lang s='All'}</label>
								<a class="slide-button btn"></a>
					    	</span>
					    	<p class="help-block">{lang s='If you set to `All`, the new chats will ring to all staff until someone answers the chat, if you set `Most Available` it will ring to the staff with fewer online chats.'}</p>
					    </div>
					</div>

					<div class="form-group">
						<label for="chat_type" class="col-sm-3 control-label">{lang s='Chat type:'}</label>
						<div class="col-sm-9">
							<span class="switch prestashop-switch fixed-width-lg">
					    		<input type="radio" name="chat_type" id="chat_type_slide" value="Slide">
					    		<label class="radioCheck" for="chat_type_slide">{lang s='Slide'}</label>
					    		<input type="radio" name="chat_type" id="chat_type_popup" value="Popup">
					    		<label class="radioCheck" for="chat_type_popup">{lang s='Popup'}</label>
								<a class="slide-button btn"></a>
					    	</span>
					    	<p class="help-block">{lang s='If you choose the "Slide" chat type, the chat box is fixed (follows scroll) and is located in the bottom of the screen. If you select "Popup" chat type you can customize a button which you can click to open the chat window. You can place the button everywhere on the site.'}</p>
					    </div>
					</div>
					
					<div class="form-group">
						<label for="chat_type_admin" class="col-sm-3 control-label">{lang s='Chat type admin:'}</label>
						<div class="col-sm-9">
							<span class="switch prestashop-switch fixed-width-lg">
					    		<input type="radio" name="chat_type_admin" id="chat_type_admin_slide" value="Slide">
					    		<label class="radioCheck" for="chat_type_admin_slide">{lang s='Slide'}</label>
					    		<input type="radio" name="chat_type_admin" id="chat_type_admin_popup" value="Popup">
					    		<label class="radioCheck" for="chat_type_admin_popup">{lang s='Popup'}</label>
								<a class="slide-button btn"></a>
					    	</span>
					    	<p class="help-block">{lang s='If you choose the "Slide" chat type, the chat box is fixed (follows scroll) and is located in the bottom of the screen. If you select "Popup" chat type you can customize a button which you can click to open the chat window. You can place the button everywhere on the site.'}</p>
					    </div>
					</div>

					<div class="form-group" id="slide_with_image_tr">
						<label for="slide_with_image" class="col-sm-3 control-label">{lang s='Slide with image:'}</label>
						<div class="col-sm-9">
							<span class="switch prestashop-switch fixed-width-lg">
					    		<input type="radio" name="slide_with_image" id="slide_with_image_y" value="Y">
					    		<label class="radioCheck" for="slide_with_image_y">{lang s='Yes'}</label>
					    		<input type="radio" name="slide_with_image" id="slide_with_image_n" value="N">
					    		<label class="radioCheck" for="slide_with_image_n">{lang s='No'}</label>
								<a class="slide-button btn"></a>
					    	</span>
					    	<p class="help-block">{lang s='Instead of clicking the chatbox header to open the chat conversation it gives the possibility to click on an image.'}</p>
					    </div>
					</div>


					<div class="form-group" id="icons_tbody" style="display:none">
						<label class="col-sm-3 control-label">{lang s='Icons:'}</label>
						<div class="col-sm-9">
							<span style="padding-right: 5px;" id="iconsets_ajax_span" class="pull-left">
								{include file="$module_templates_back_dir/ajax.iconsets.tpl"}
							</span>
							<span style="padding-right: 5px;">
								<a href="javascript:{}" name="delete_iconset" id="delete_iconset" class="lcp button" title="Delete"><span class="icon-trash fa fa-trash-o"></span></a>
								<a href="javascript:{}" name="save_iconset" id="save_iconset" class="lcp button" title="Save"><span class="icon-save fa fa-save"></span></a>
								<a href="javascript:{}" name="save_as_iconset" id="save_as_iconset" class="lcp button" title="Save AS"><span class="icon-save fa fa-save"></span> {lang s='AS'}</a>
							</span>
							<span id="iconsets_ajax_load_span" style="padding-left: 0px;"></span>
					    	<div class="clearfix"></div>
					    	<br>
							<div class="row">
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" id="icon_online_div">
									<div class="row lcp panel" style="margin-right: 5px;">
									<div class="col-sm-6 col-sm-offset-3" style="text-align: center;">
									{lang s='Online'}
									<select name="online_img_languages" id="online_img_languages" class="form-control" style="width: auto !important;">
									{foreach from=$languages key=key item=value}
										<option value="{$value.id_lang|escape:'quotes':'UTF-8'}">{$value.iso_code|escape:'quotes':'UTF-8'}</option>
									{/foreach}
									</select>
									<span id="online_img_languages_ajax_load_span" style="padding-left: 5px;"></span>
									{foreach from=$languages key=key item=value}
										<img border="0" src="" id="online_img_{$value.iso_code|escape:'quotes':'UTF-8'}" style="{if $value.id_lang != '1'}display: none;{/if}">
									{/foreach}
									<input type="file" id="online_icon_upload" />
									</div>
									</div>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" id="icon_offline_div">
									<div class="row lcp panel" style="margin-right: 5px;">
									<div class="col-sm-6 col-sm-offset-3" style="text-align: center;">
									{lang s='Offline'}
									<select name="offline_img_languages" id="offline_img_languages" class="form-control" style="width: auto !important;">
									{foreach from=$languages key=key item=value}
										<option value="{$value.id_lang|escape:'quotes':'UTF-8'}">{$value.iso_code|escape:'quotes':'UTF-8'}</option>
									{/foreach}
									</select>
									<span id="offline_img_languages_ajax_load_span" style="padding-left: 5px;"></span>
									{foreach from=$languages key=key item=value}
										<img border="0" src="" id="offline_img_{$value.iso_code|escape:'quotes':'UTF-8'}" style="{if $value.id_lang != '1'}display: none;{/if}">
									{/foreach}
									<input type="file" id="offline_icon_upload" />
									</div>
									</div>
								</div>
							</div>

					    </div>
					</div>

					<div class="form-group" id="start_minimized_tr">
						<label class="col-sm-3 control-label">{lang s='Start minimized:'}</label>
						<div class="col-sm-9">
							<span class="switch prestashop-switch fixed-width-lg">
					    		<input type="radio" name="start_minimized" id="start_minimized_y" value="Y">
					    		<label class="radioCheck" for="start_minimized_y">{lang s='Yes'}</label>
					    		<input type="radio" name="start_minimized" id="start_minimized_n" value="N">
					    		<label class="radioCheck" for="start_minimized_n">{lang s='No'}</label>
								<a class="slide-button btn"></a>
					    	</span>
					    </div>
					</div>

					<div class="form-group" id="hide_chat_window_tr">
						<label class="col-sm-3 control-label">{lang s='Hide chat window when offline:'}</label>
						<div class="col-sm-9">
							<span class="switch prestashop-switch fixed-width-lg">
					    		<input type="radio" name="hide_when_offline" id="hide_when_offline_y" value="Y">
					    		<label class="radioCheck" for="hide_when_offline_y">{lang s='Yes'}</label>
					    		<input type="radio" name="hide_when_offline" id="hide_when_offline_n" value="N">
					    		<label class="radioCheck" for="hide_when_offline_n">{lang s='No'}</label>
								<a class="slide-button btn"></a>
					    	</span>
					    	<p class="help-block">{lang s='This option hides the chat window when all the staff members are offline. The chat window is shown when at least one staff member is online.'}</p>
					    </div>
					</div>

					

					

					<div class="form-group" id="position_tr">
						<label class="col-sm-3 control-label">{lang s='Fixed position (follows scroll):'}</label>
						<div class="col-sm-9">
							<span class="switch prestashop-switch fixed-width-lg">
					    		<input type="radio" name="fixed_position" id="fixed_position_y" value="Y">
					    		<label class="radioCheck" for="fixed_position_y">{lang s='Yes'}</label>
					    		<input type="radio" name="fixed_position" id="fixed_position_n" value="N">
					    		<label class="radioCheck" for="fixed_position_n">{lang s='No'}</label>
								<a class="slide-button btn"></a>
					    	</span>
					    	<p class="help-block">{lang s='If this is set to "Yes", the chat button follows the scroll.'}</p>
					    </div>
					</div>
					
					<div class="form-group" id="orientation_tr">
						<label for="orientation" class="col-sm-3 control-label">{lang s='Orientation:'}</label>
						<div class="col-sm-9">
							<select name="position_slide" id="orientation_slide_select" class="form-control fixed-width-xl">
								<option value="bottom-right">{lang s='bottom-right'}</option>
								<option value="bottom-left">{lang s='bottom-left'}</option>
							</select>
							<select name="position_popup" id="orientation_popup_select" class="form-control fixed-width-xl" style="display: none;">
								<option value="left-top">{lang s='left-top'}</option>
								<option value="left-bottom">{lang s='left-bottom'}</option>
								<option value="right-top">{lang s='right-top'}</option>
								<option value="right-bottom">{lang s='right-bottom'}</option>
							</select>
							<p class="help-block">{lang s='The position of the chat box related to the browser window.'}</p>
						</div>
					</div>

					<div class="form-group" id="offset_tr">
						<label for="orientation" class="col-sm-3 control-label">{lang s='Offset:'}</label>
						<div class="col-sm-9">
							<input type="text" class="form-control fixed-width-xs pull-left" name="offset" id="offset" placeholder="40">&nbsp;px
							<br><br><p class="help-block" style="margin: 0px; padding: 0px;">{lang s='The chat box distance in pixels from the left or right side of the browser window.'}</p>
						</div>
					</div>
					
					<div class="row lcp panel-head border-bottom">
						{lang s='Theme settings'}
					</div>
					<br>
					<div class="form-group" id="">
						<label class="col-sm-3 control-label">{lang s='Theme settings'}</label>
						<div class="col-sm-9">
							<span style="padding-right: 5px;" id="themes_ajax_span" class="pull-left">
								{include file="$module_templates_back_dir/ajax.themes.tpl"}
							</span>
							<span style="padding-right: 0px;">
								<a href="javascript:{}" name="delete_theme" id="delete_theme" class="lcp button" title="Delete"><span class="icon-trash fa fa-trash-o"></span></a>
								<a href="javascript:{}" name="save_theme" id="save_theme" class="lcp button" title="Save"><span class="icon-save fa fa-save"></span></a>
								<a href="javascript:{}" name="save_as_theme" id="save_as_theme" class="lcp button" title="Save AS"><span class="icon-save fa fa-save"></span>
								{lang s='AS'}</a>
							</span>
							<span id="themes_ajax_load_span" style="padding-left: 0px;"></span>
						</div>
					</div>
					
					<div class="form-group" id="">
						<label class="col-sm-3 control-label">{lang s='Width:'}</label>
						<div class="col-sm-9">
							<input type="text" class="form-control fixed-width-xs pull-left" name="chat_width" id="chat_width" placeholder="300">&nbsp;px
						</div>
					</div>

					<div class="form-group" id="">
						<label class="col-sm-3 control-label">{lang s='Height (when chatting):'}</label>
						<div class="col-sm-9">
							<input type="text" class="form-control fixed-width-xs pull-left" name="chat_height" id="chat_height" placeholder="380">&nbsp;px
						</div>
					</div>
					
					<div class="form-group" id="corners_radius_tr">
						<label class="col-sm-3 control-label">{lang s='Corners Radius:'}</label>
						<div class="col-sm-9">
							<input type="text" class="form-control fixed-width-xs pull-left" name="corners_radius" id="corners_radius" placeholder="5">&nbsp;px
						</div>
					</div>

					<div class="form-group" id="chat_box_border_tr">
						<label class="col-sm-3 control-label">{lang s='Chat Box Border:'}</label>
						<div class="col-sm-9">
							<span class="switch prestashop-switch fixed-width-lg">
					    		<input type="radio" name="chat_box_border" id="chat_box_border_y" value="Y">
					    		<label class="radioCheck" for="chat_box_border_y">{lang s='Yes'}</label>
					    		<input type="radio" name="chat_box_border" id="chat_box_border_n" value="N">
					    		<label class="radioCheck" for="chat_box_border_n">{lang s='No'}</label>
								<a class="slide-button btn"></a>
					    	</span>
					    </div>
					</div>

					<div class="form-group" id="chat_box_border_color_tr">
						<label class="col-sm-3 control-label">{lang s='Chat Box Border Color:'}</label>
						<div class="col-sm-9">
							<input type="text" class="form-control fixed-width-sm lcp color" name="chat_box_border_color" id="chat_box_border_color" placeholder="cccccc">
						</div>
					</div>

					<div class="form-group" id="">
						<label class="col-sm-3 control-label">{lang s='Chat Box Background:'}</label>
						<div class="col-sm-9">
							<input type="text" class="form-control fixed-width-sm lcp color" name="chat_box_background" id="chat_box_background" placeholder="ffffff">
						</div>
					</div>

					<div class="form-group" id="">
						<label class="col-sm-3 control-label">{lang s='Chat Box Foreground:'}</label>
						<div class="col-sm-9">
							<input type="text" class="form-control fixed-width-sm lcp color" name="chat_box_foreground" id="chat_box_foreground" placeholder="222222">
						</div>
					</div>

					<div class="form-group" id="">
						<label class="col-sm-3 control-label">{lang s='Chat Bubble Staff Background:'}</label>
						<div class="col-sm-9">
							<input type="text" class="form-control fixed-width-sm lcp color" name="chat_bubble_staff_background" id="chat_bubble_staff_background" placeholder="cccbd1">
						</div>
					</div>

					<div class="form-group" id="">
						<label class="col-sm-3 control-label">{lang s='Chat Bubble Client Background:'}</label>
						<div class="col-sm-9">
							<input type="text" class="form-control fixed-width-sm lcp color" name="chat_bubble_client_background" id="chat_bubble_client_background" placeholder="e0e3e7">
						</div>
					</div>

					<div class="form-group" id="">
						<label class="col-sm-3 control-label">{lang s='Header offline background:'}</label>
						<div class="col-sm-9">
							<input type="text" class="form-control fixed-width-sm lcp color" name="header_offline_background" id="header_offline_background" placeholder="bf3723">
						</div>
					</div>

					<div class="form-group" id="">
						<label class="col-sm-3 control-label">{lang s='Header online background:'}</label>
						<div class="col-sm-9">
							<input type="text" class="form-control fixed-width-sm lcp color" name="header_online_background" id="header_online_background" placeholder="3a99d1">
						</div>
					</div>

					<div class="form-group" id="">
						<label class="col-sm-3 control-label">{lang s='Header offline foreground:'}</label>
						<div class="col-sm-9">
							<input type="text" class="form-control fixed-width-sm lcp color" name="header_offline_foreground" id="header_offline_foreground" placeholder="ffffff">
						</div>
					</div>

					<div class="form-group" id="">
						<label class="col-sm-3 control-label">{lang s='Header online foreground:'}</label>
						<div class="col-sm-9">
							<input type="text" class="form-control fixed-width-sm lcp color" name="header_online_foreground" id="header_online_foreground" placeholder="ffffff">
						</div>
					</div>

					<div class="form-group" id="">
						<label class="col-sm-3 control-label">{lang s='Submit button background:'}</label>
						<div class="col-sm-9">
							<input type="text" class="form-control fixed-width-sm lcp color" name="submit_button_background" id="submit_button_background" placeholder="3a99d1">
						</div>
					</div>

					<div class="form-group" id="">
						<label class="col-sm-3 control-label">{lang s='Submit button foreground:'}</label>
						<div class="col-sm-9">
							<input type="text" class="form-control fixed-width-sm lcp color" name="submit_button_foreground" id="submit_button_foreground" placeholder="ffffff">
						</div>
					</div>

					
					<br>
					<div class="row lcp panel-head border-bottom">
						{lang s='Messages settings'}
					</div>
					<br>
					<div class="form-group" id="">
						<label class="col-sm-3 control-label">{lang s='Offline header:'}</label>
						<div class="col-sm-9">
							<div class="pull-left">
							{foreach from=$languages key=key item=value}
								<input type="text" class="form-control fixed-width-xxl" name="offline_header_{$value.iso_code|escape:'quotes':'UTF-8'}" id="offline_header_{$value.iso_code|escape:'quotes':'UTF-8'}" style="{if $value.id_lang != '1'}display: none;{/if}" value="">
							{/foreach}
							</div>
							<div class="pull-left" style="padding-left: 5px;">
							<select name="offline_header_languages" id="offline_header_languages" class="form-control">
								{foreach from=$languages key=key item=value}
									<option value="{$value.id_lang|escape:'quotes':'UTF-8'}">{$value.iso_code|escape:'quotes':'UTF-8'}</option>
								{/foreach}
							</select>
							<span id="offline_header_languages_ajax_load_span" style="padding-left: 5px;"></span>
							</div>
						</div>
					</div>

					<div class="form-group" id="">
						<label class="col-sm-3 control-label">{lang s='Online header:'}</label>
						<div class="col-sm-9">
							<div class="pull-left">
							{foreach from=$languages key=key item=value}
								<input type="text" class="form-control fixed-width-xxl" name="online_header_{$value.iso_code|escape:'quotes':'UTF-8'}" id="online_header_{$value.iso_code|escape:'quotes':'UTF-8'}" style="{if $value.id_lang != '1'}display: none;{/if}" value="">
							{/foreach}
							</div>
							<div class="pull-left" style="padding-left: 5px;">
							<select name="online_header_languages" id="online_header_languages">
								{foreach from=$languages key=key item=value}
									<option value="{$value.id_lang|escape:'quotes':'UTF-8'}">{$value.iso_code|escape:'quotes':'UTF-8'}</option>
								{/foreach}
							</select>
							<span id="online_header_languages_ajax_load_span" style="padding-left: 5px;"></span>
							</div>
						</div>
					</div>

					<div class="form-group" id="">
						<label class="col-sm-3 control-label">{lang s='Offline welcome message:'}</label>
						<div class="col-sm-9">
							<div class="pull-left">
							{foreach from=$languages key=key item=value}
								<textarea class="form-control fixed-width-xxl" rows="3" name="offline_welcome_message_{$value.iso_code|escape:'quotes':'UTF-8'}" id="offline_welcome_message_{$value.iso_code|escape:'quotes':'UTF-8'}" style="{if $value.id_lang != '1'}display: none;{/if}"></textarea>
							{/foreach}
							</div>
							<div class="pull-left" style="padding-left: 5px;">
							<select name="offline_welcome_message_languages" id="offline_welcome_message_languages">
								{foreach from=$languages key=key item=value}
									<option value="{$value.id_lang|escape:'quotes':'UTF-8'}">{$value.iso_code|escape:'quotes':'UTF-8'}</option>
								{/foreach}
							</select>
							<span id="offline_welcome_message_languages_ajax_load_span" style="padding-left: 5px;"></span>
							</div>
						</div>
					</div>

					<div class="form-group" id="">
						<label class="col-sm-3 control-label">{lang s='Online welcome message:'}</label>
						<div class="col-sm-9">
							<div class="pull-left">
							{foreach from=$languages key=key item=value}
								<textarea class="form-control fixed-width-xxl" rows="3" name="online_welcome_message_{$value.iso_code|escape:'quotes':'UTF-8'}" id="online_welcome_message_{$value.iso_code|escape:'quotes':'UTF-8'}" style="{if $value.id_lang != '1'}display: none;{/if}"></textarea>
							{/foreach}
							</div>
							<div class="pull-left" style="padding-left: 5px;">
							<select name="online_welcome_message_languages" id="online_welcome_message_languages">
								{foreach from=$languages key=key item=value}
									<option value="{$value.id_lang|escape:'quotes':'UTF-8'}">{$value.iso_code|escape:'quotes':'UTF-8'}</option>
								{/foreach}
							</select>
							<span id="online_welcome_message_languages_ajax_load_span" style="padding-left: 5px;"></span>
							</div>
						</div>
					</div>

					<hr>

					<div class="row">
						<div class="col-sm-6 col-sm-offset-3">
							<div class="pull-left">{lang s='Primary settings:'}&nbsp;</div>
							<div class="pull-left">
							<span style="padding-right: 5px;" id="settings_ajax_span">
								{include file="$module_templates_back_dir/ajax.settings.tpl"}
							</span>
							</div>
							<div class="pull-left">
								&nbsp;<span id="settings_ajax_load_span" style="padding-left: 0px;"></span>
							</div>
						</div>
					</div>

					<div class="row">
					<div class="col-sm-6 col-sm-offset-3">
						<a href="javascript:{}" name="delete_settings" id="delete_settings" class="lcp button" title="Delete"><span class="icon-trash fa fa-trash-o"></span> {lang s='Delete'}</a>
						<a href="javascript:{}" name="save_settings" id="save_settings" class="lcp button" title="Save above settings"><span class="icon-save fa fa-save"></span> {lang s='Save above settings'}</a>
						<a href="javascript:{}" name="save_as_settings" id="save_as_settings" class="lcp button" title="Save AS above settings"><span class="icon-save fa fa-save"></span> {lang s='Save AS above settings'}</a>
					</div>
					</div>

				</form>
				<br>


			</div>

			<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
				<div style="/*position: fixed; z-index: 9999; overflow-y: scroll; height: 380px; width: 355px; background: #eff1f2; */ padding: 0px 10px;">
				<div id="preview_slide">
					<div class="lcp panel-head border-bottom">{lang s='Preview chat (offline)'}</div>
					<br>
					<div class="row" id="preview_offline_chat_table">
						
						<div id="preview_offline_chat_header_table" class="row" style="line-height: 30px; vertical-align: middle; margin: 0px; padding: 0px 5px;">
							<div style="line-height: 30px;" class="fa fa-envelope pull-left"></div>
							<div style="line-height: 30px;" id="preview_offline_header_message" class="pull-left">{lang s='Contact us'}</div>
							<div style="line-height: 30px; cursor: hand; cursor: pointer;" title="Close" class="fa fa-times-circle pull-right"></div>
							<div style="line-height: 30px; cursor: hand; cursor: pointer;" title="Minimize" class="fa fa-chevron-down pull-right"></div>
						</div>

						<div id="preview_offline_chat_inner_table" style="padding: 0px 10px;">

						<span id="preview_offline_welcome_message">{lang s='We`re not around right now. But you can send us an email and we`ll get back to you, asap.'}</span>

						<div class="row" id="preview_offline_name_field" style="margin: 5px 0px;">
							<input class="form-control" type="text" name="" placeholder="{lang s='Your name'}">
						</div>

						<div class="row" id="preview_offline_email_field" style="margin: 5px 0px;">
							<input class="form-control" type="text" name="" placeholder="{lang s='E-mail'}">
						</div>

						<div class="row" id="preview_offline_phone_field" style="margin: 5px 0px;">
							<input class="form-control" type="text" name="" placeholder="{lang s='Phone'}">
						</div>

						<div class="row" id="preview_offline_department_field" style="margin: 5px 0px;">
							<select class="form-control" name="preview_offline_departments" id="preview_offline_departments">
								{foreach from=$departments key=key item=value}
								<option value="{$value.id_department|escape:'quotes':'UTF-8'}">{$value.name|escape:'quotes':'UTF-8'}</option>
								{/foreach}
							</select>
						</div>

						<div class="row" id="preview_offline_question_field" style="margin: 5px 0px;">
							<textarea class="form-control" rows="3" placeholder="{lang s='Question'}"></textarea>
						</div>

						<div class="row row-centered" style="margin: 5px 0px;">
							<a href="javascript:{}" class="lcp chat-button"><span class="fa fa-envelope-o"></span> {lang s='Leave message!'}</a>
						</div>

					</div>

					</div>

					
					<br>
					<div class="lcp panel-head border-bottom">{lang s='Preview chat (online)'}</div>
					<br>
					<div class="row" id="preview_online_chat_table">

						<div id="preview_online_chat_header_table" class="row" style="line-height: 30px; vertical-align: middle; margin: 0px; padding: 0px 5px;">
							<div style="line-height: 30px;" class="fa fa-comment pull-left"></div>
							<div style="line-height: 30px;" id="preview_online_header_message" class="pull-left">{lang s='Talk to us'}</div>
							<div style="line-height: 30px; cursor: hand; cursor: pointer;" title="Close" class="fa fa-times-circle pull-right"></div>
							<div style="line-height: 30px; cursor: hand; cursor: pointer;" title="Minimize" class="fa fa-chevron-down pull-right"></div>
						</div>

						<div id="preview_online_chat_inner_table" style="padding: 0px 10px;">

							<span id="preview_online_welcome_message">{lang s='Questions? We`re here. Send us a message!'}</span>
							
							<div class="row" id="preview_online_name_field" style="margin: 5px 0px;">
								<input class="form-control" type="text" name="" placeholder="{lang s='Your name'}">
							</div>

							<div class="row" id="preview_online_email_field" style="margin: 5px 0px;">
								<input class="form-control" type="text" name="" placeholder="{lang s='E-mail'}">
							</div>

							<div class="row" id="preview_online_phone_field" style="margin: 5px 0px;">
								<input class="form-control" type="text" name="" placeholder="{lang s='Phone'}">
							</div>

							<div class="row" id="preview_online_department_field" style="margin: 5px 0px;">
								<select class="form-control" name="preview_online_departments" id="preview_online_departments">
									{foreach from=$departments key=key item=value}
									<option value="{$value.id_department|escape:'quotes':'UTF-8'}">{$value.name|escape:'quotes':'UTF-8'}</option>
									{/foreach}
								</select>
							</div>

							<div class="row" id="preview_online_question_field" style="margin: 5px 0px;">
								<textarea class="form-control" rows="3" name="" placeholder="{lang s='Question'}"></textarea>
							</div>

							<div class="row row-centered" style="margin: 5px 0px;">
								<a href="javascript:{}" class="lcp chat-button"><span class="fa fa-comment-o"></span> {lang s='Start chat!'}</a>
							</div>

						</div>

					</div>

				</div>

				<div id="preview_popup" style="display:none">
					<div class="lcp panel-head border-bottom">{lang s='Preview chat (offline)'}</div>
					<br>

					<table border="0" width="" cellspacing="0" cellpadding="0" id="">
					<tr>
						<td align="left" width="219" style="line-height: 0;"><img border="0" src="{$module_path|escape:'quotes':'UTF-8'}views/img/popup_chat_window/top-left.gif" width="219" height="64"></td>
						<td background="{$module_path|escape:'quotes':'UTF-8'}views/img/popup_chat_window/top-middle.gif" style="line-height: 0;">&nbsp;</td>
						<td align="right" width="98" style="line-height: 0;"><img border="0" src="{$module_path|escape:'quotes':'UTF-8'}views/img/popup_chat_window/top-right.gif" width="98" height="64"></td>
					</tr>
					<tr>
						<td align="left" colspan="3">
							<table border="0" width="100%" cellspacing="0" cellpadding="0" style="">
								<tr>
									<td background="{$module_path|escape:'quotes':'UTF-8'}views/img/popup_chat_window/left-middle.gif" width="7">&nbsp;</td>
									<td align="center">
										<table border="0" id="popup_preview_offline_chat_table">
											<tr>
												<td valign="top" style="padding: 10px" align="center">
													
													<div class="row">
														<span id="popup_preview_offline_welcome_message">{lang s='We`re not around right now. But you can send us an email and we`ll get back to you, asap.'}</span>
													</div>

													<div class="row" id="popup_preview_offline_name_field" style="margin: 5px 0px;">
														<input class="form-control" type="text" name="" placeholder="{lang s='Your name'}">
													</div>

													<div class="row" id="popup_preview_offline_email_field" style="margin: 5px 0px;">
														<input class="form-control" type="text" name="" placeholder="{lang s='E-mail'}">
													</div>

													<div class="row" id="popup_preview_offline_phone_field" style="margin: 5px 0px;">
														<input class="form-control" type="text" name="" placeholder="Phone">
													</div>

													<div class="row" id="popup_preview_offline_department_field" style="margin: 5px 0px;">
														<select class="form-control" name="popup_preview_offline_departments" id="popup_preview_offline_departments">
															{foreach from=$departments key=key item=value}
															<option value="{$value.id_department|escape:'quotes':'UTF-8'}">{$value.name|escape:'quotes':'UTF-8'}</option>
															{/foreach}
														</select>
													</div>

													<div class="row" id="popup_preview_offline_question_field" style="margin: 5px 0px;">
														<textarea class="form-control" rows="3" name="" placeholder="{lang s='Question'}"></textarea>
													</div>

													<div class="row row-centered" style="margin: 5px 0px;">
														<a href="javascript:{}" class="lcp chat-button"><span class="fa fa-envelope-o"></span> {lang s='Leave message!'}</a>
													</div>

												</td>
											</tr>
										</table>
									</td>
									<td background="{$module_path|escape:'quotes':'UTF-8'}views/img/popup_chat_window/right-middle.gif" width="7">&nbsp;</td>
								</tr>
								<tr>
									<td align="left" style="line-height: 0;"><img border="0" src="{$module_path|escape:'quotes':'UTF-8'}views/img/popup_chat_window/bottom-left.gif" width="7" height="7"></td>
									<td background="{$module_path|escape:'quotes':'UTF-8'}views/img/popup_chat_window/bottom-middle.gif" style="line-height: 0;"><img border="0" src="{$module_path|escape:'quotes':'UTF-8'}views/img/popup_chat_window/bottom-middle.gif" width="2" height="7"></td>
									<td align="right" style="line-height: 0;"><img border="0" src="{$module_path|escape:'quotes':'UTF-8'}views/img/popup_chat_window/bottom-right.gif" width="7" height="7"></td>
								</tr>
							</table>
							</td>
						</tr>
					</table>

					<br>
					<div class="lcp panel-head border-bottom">{lang s='Preview chat (online)'}</div>
					<br>

					<table border="0" width="" cellspacing="0" cellpadding="0" id="">
					<tr>
						<td align="left" width="219" style="line-height: 0;"><img border="0" src="{$module_path|escape:'quotes':'UTF-8'}views/img/popup_chat_window/top-left.gif" width="219" height="64"></td>
						<td background="{$module_path|escape:'quotes':'UTF-8'}views/img/popup_chat_window/top-middle.gif" style="line-height: 0;">&nbsp;</td>
						<td align="right" width="98" style="line-height: 0;"><img border="0" src="{$module_path|escape:'quotes':'UTF-8'}views/img/popup_chat_window/top-right.gif" width="98" height="64"></td>
					</tr>
					<tr>
						<td align="left" colspan="3">
							<table border="0" width="100%" cellspacing="0" cellpadding="0" style="">
								<tr>
									<td background="{$module_path|escape:'quotes':'UTF-8'}views/img/popup_chat_window/left-middle.gif" width="7">&nbsp;</td>
									<td align="center">
										<table border="0" id="popup_preview_online_chat_table">
											<tr>
												<td valign="top" style="padding: 10px" align="center">
													
													<div class="row">
														<span id="popup_preview_online_welcome_message">{lang s='Questions? We`re here. Send us a message!'}</span>
													</div>
												
													<div class="row" id="popup_preview_online_name_field" style="margin: 5px 0px;">
														<input class="form-control" type="text" name="" placeholder="{lang s='Name'}">
													</div>

													<div class="row" id="popup_preview_online_email_field" style="margin: 5px 0px;">
														<input class="form-control" type="text" name="" placeholder="{lang s='E-mail'}">
													</div>
												
													<div class="row" id="popup_preview_online_phone_field" style="margin: 5px 0px;">
														<input class="form-control" type="text" name="" placeholder="{lang s='Phone'}">
													</div>
													
													<div class="row" id="popup_preview_online_department_field" style="margin: 5px 0px;">
														<select class="form-control" name="popup_preview_online_departments" id="popup_preview_online_departments">
															{foreach from=$departments key=key item=value}
															<option value="{$value.id_department|escape:'quotes':'UTF-8'}">{$value.name|escape:'quotes':'UTF-8'}</option>
															{/foreach}
														</select>
													</div>

													<div class="row" id="popup_preview_online_question_field" style="margin: 5px 0px;">
														<textarea class="form-control" rows="3" name="" placeholder="{lang s='Question'}"></textarea>
													</div>

													<div class="row row-centered" style="margin: 5px 0px;">
														<a href="javascript:{}" class="lcp chat-button"><span class="fa fa-comment-o"></span> {lang s='Start chat!'}</a>
													</div>

												</td>
											</tr>
										</table>
									</td>
									<td background="{$module_path|escape:'quotes':'UTF-8'}views/img/popup_chat_window/right-middle.gif" width="7">&nbsp;</td>
								</tr>
								<tr>
									<td align="left" style="line-height: 0;"><img border="0" src="{$module_path|escape:'quotes':'UTF-8'}views/img/popup_chat_window/bottom-left.gif" width="7" height="7"></td>
									<td background="{$module_path|escape:'quotes':'UTF-8'}views/img/popup_chat_window/bottom-middle.gif" style="line-height: 0;"><img border="0" src="{$module_path|escape:'quotes':'UTF-8'}views/img/popup_chat_window/bottom-middle.gif" width="2" height="7"></td>
									<td align="right" style="line-height: 0;"><img border="0" src="{$module_path|escape:'quotes':'UTF-8'}views/img/popup_chat_window/bottom-right.gif" width="7" height="7"></td>
								</tr>
							</table>
							</td>
						</tr>
					</table>
					

				</div>

			</div> <!-- follow scroll -->
			</div>
		</div>


	</div>



<div id="tabs-departments" class="tab-content" style="display: none;">
	<div class="row lcp panel-head border-bottom">
		{lang s='Departments'}
	</div>
	<br>
	<table id="departments_grid" class="lcp display compact radius5" cellspacing="0" width="100%" style="border: 1px solid #dddddd;">
		<thead>
			<tr>
				<th align="left">{lang s='Name'}</th>
				<th align="left">{lang s='Status'}</th>
			</tr>
		</thead>
		<tfoot>
		<tr>
			<th align="left">{lang s='Name'}</th>
			<th align="left">{lang s='Status'}</th>
		</tr>
		</tfoot>
	</table>
</div>
<div id="tabs-staff-profiles" class="tab-content" style="display: none;">
	<div class="row lcp panel-head border-bottom">
		{lang s='Staff profiles'}
	</div>
	<br>
	{lang s='To add custom avatar image just copy it (.png format) in this location:'} {$module_path|escape:'quotes':'UTF-8'}views/img/avatars/
	<table id="staffprofiles_grid" class="lcp display compact radius5" cellspacing="0" width="100%" style="border: 1px solid #dddddd;">
		<thead>
			<tr>
				<th>{lang s='Active'}</th>
				<th>{lang s='Firstname'}</th>
				<th>{lang s='Lastname'}</th>
				<th>{lang s='Avatar'}</th>
				<th>{lang s='Departments'}</th>
				<th>{lang s='Welcome message'}</th>
				<th>{lang s='Signature'}</th>
			</tr>
		</thead>
		<tfoot>
		<tr>
			<th>{lang s='Active'}</th>
			<th>{lang s='Firstname'}</th>
			<th>{lang s='Lastname'}</th>
			<th>{lang s='Avatar'}</th>
			<th>{lang s='Departments'}</th>
			<th>{lang s='Welcome message'}</th>
			<th>{lang s='Signature'}</th>
		</tr>
		</tfoot>
	</table>
</div>
<div id="tabs-predefined-messages" class="tab-content" style="display: none;">
<div class="row lcp panel-head border-bottom">
		{lang s='Predefined messages'}
	</div>
	<br>
	<table id="predefinedmessages_grid" class="lcp display compact responsive nowrap radius5" cellspacing="0" width="100%" style="border: 1px solid #dddddd;">
		<thead>
			<tr>
				<th>{lang s='Language'}</th>
				<th>{lang s='Title'}</th>
				<th>{lang s='Message'}</th>
				<th>{lang s='Last update'}</th>
			</tr>
		</thead>
		<tfoot>
		<tr>
			<th>{lang s='Language'}</th>
			<th>{lang s='Title'}</th>
			<th>{lang s='Message'}</th>
			<th>{lang s='Last update'}</th>
		</tr>
		</tfoot>
	</table>
</div>

<div id="tabs-translations" class="tab-content" style="display: none;">
	<div class="row lcp panel-head border-bottom">
		{lang s='Translations'}
	</div>
	<br>
	<div style="line-height: 25px;" class="pull-left">{lang s='Select language:'}</div>
	<select name="translations" id="translations" class="form-control fixed-width-sm pull-left">
		{foreach from=$languages key=key item=value}
			<option value="{$value.id_lang|escape:'quotes':'UTF-8'}">{$value.iso_code|escape:'quotes':'UTF-8'}</option>
		{/foreach}
	</select>
	<a href="javascript:{}" name="save_translations" id="save_translations" class="lcp button" title="Save below translations"><span class="icon-save fa fa-save"></span> {lang s='Save below translations'}</a>
	
	<div id="ajax_translations_div">
		{include file="$module_templates_back_dir/ajax.translations.tpl"}
	</div>
</div>


<div id="tabs-clear-database" class="tab-content" style="display: none;">
	<div class="lcp panel-head border-bottom">{lang s='Clear the database'}</div>
	<br>
	<div class="col-sm-6 col-sm-offset-3" style="text-align: center">
		{lang s='This will clear the following: archive, logs, offline messages, ratings.'}
		<br><br>
		<a href="javascript:{}" name="lcp_clear_database" id="lcp_clear_database" class="lcp button" title="Clear database"><span class="fa fa-database"></span> {lang s='Clear database'}</a><span id="clear_database_ajax_load_span" style="padding-left: 5px;"></span>
	</div>
	<div class="clearfix"></div>
	<br>
</div>


</div>
</div>
<br>