<?php
/* Smarty version 3.1.28, created on 2017-03-30 09:42:41
  from "C:\localhost\htdocs\www\prestashop1615\modules\livechatpro\views\templates\admin\popup_chat_window.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_58dcb6f18d40c7_88250912',
  'file_dependency' => 
  array (
    'd32e09ce75ab9fac1357ba8b4b87bb58050d602d' => 
    array (
      0 => 'C:\\localhost\\htdocs\\www\\prestashop1615\\modules\\livechatpro\\views\\templates\\admin\\popup_chat_window.tpl',
      1 => 1490776429,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_58dcb6f18d40c7_88250912 ($_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '2348358dcb6f1735c53_47050515';
?>

<?php $_smarty_tpl->tpl_vars['template'] = new Smarty_Variable(((string)dirname($_smarty_tpl->source->filepath))."\\".((string)basename($_smarty_tpl->source->filepath)), null);
$_smarty_tpl->ext->_updateScope->updateScope($_smarty_tpl, 'template', 0);?>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<audio id="newchat" src=""></audio>
<audio id="newmessage" src=""></audio>

<span id="dialog_chat_window_ajax_load_span"></span>

<div id='root' class="bootstrap">

<div class="row">
	<div class="col-sm-4 col-md-3 col-lg-2">
		<div class="row lcp panel">
				<div class="col-lg-2 lcp panel-head"><?php echo smartyFunctionTranslate(array('s'=>'Status:'),$_smarty_tpl);?>
</div>
				<div class="col-lg-9">
					<select name="status_select" id="status_select" class="form-control">
						<option value="Offline" style="color: #606060; background-color: white; font-weight: bolder; padding: 10px;" <?php if ($_smarty_tpl->tpl_vars['staff_chat_status']->value == 'offline') {?>selected<?php }?>><?php echo smartyFunctionTranslate(array('s'=>'Offline'),$_smarty_tpl);?>
</option>
						<option value="Online" style="color: #3fc813; background-color: white; font-weight: bolder;  padding: 10px;" <?php if ($_smarty_tpl->tpl_vars['staff_chat_status']->value == 'online') {?>selected<?php }?>><?php echo smartyFunctionTranslate(array('s'=>'Online'),$_smarty_tpl);?>
</option>
					</select>
				</div>
				<div class="col-lg-1" style="line-height: 25px; vertical-align: middle;"><span id="status_ajax_load_span"><img id="status_img" border="0" src="<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['module_path']->value);?>
views/img/<?php if ($_smarty_tpl->tpl_vars['staff_chat_status']->value == 'online') {?>online<?php } else { ?>offline<?php }?>-ico.png"></span></div>
		</div>

		<div class="row lcp panel" style="margin: 10px 0px !important;">
			<div id='online_users'>
				<span id="ajax_onlineusers_load_span"></span>
				<div id="ajax_onlineusers_div">
					
				</div>
			</div>
		</div>

	</div>

	<div class="col-sm-8 col-md-9 col-lg-10">
		<div class="row lcp panel">

			<span id="ajax_chats_load_span"></span>
			<div id="ajax_chats_div">
				
			</div>
			
			<div id="ajax_chats_textarea_div" style="<?php if (empty($_smarty_tpl->tpl_vars['active_pending_archives']->value)) {?>display:none;<?php }?> background-color: white;">
					
					<div class="pull-left" style="padding-right: 5px;"><input type="text" name="msg_admin" id="msg_admin" value="" class="form-control fixed-width-xxl" placeholder="<?php echo smartyFunctionTranslate(array('s'=>'press enter key to chat'),$_smarty_tpl);?>
"></div>
					<div class="pull-left" style="padding-right: 5px;"><a href="javascript:{}" name="send_msg_admin_a" id="send_msg_admin_a" class="lcp button"><span class="icon-paper-plane-o fa fa-paper-plane-o"></span></a></div>
					<div class="pull-left" style="padding-right: 5px;"><a href="javascript:{}" name="show_hide_emoticons_admin" id="show_hide_emoticons_admin" class="lcp button"><span class="icon-smile-o fa fa-smile-o"></span></a></div>
					<div class="pull-left" style="padding-right: 5px;"><a href="javascript:{}" name="predefined_messages" id="predefined_messages" class="lcp button" title="<?php echo smartyFunctionTranslate(array('s'=>'Predefined messages'),$_smarty_tpl);?>
"><span class="icon-keyboard-o fa fa-keyboard-o"></span> <?php echo smartyFunctionTranslate(array('s'=>'Predefined messages'),$_smarty_tpl);?>
</a></div>
					<div class="pull-left" style="padding-right: 5px;"><a href="javascript:{}" name="transfer_visitor" id="transfer_visitor" class="lcp button" title="<?php echo smartyFunctionTranslate(array('s'=>'Transfer visitor'),$_smarty_tpl);?>
"><span class="icon-exchange fa fa-exchange"></span> <?php echo smartyFunctionTranslate(array('s'=>'Transfer visitor'),$_smarty_tpl);?>
</a></div>
					<div class="pull-left" style="padding-right: 5px;"><input type="file" class="" id="send_file_upload" /></div>

			</div>

			<div id="tabs-visitor-details" style="display: none; padding-top: 5px;">
				<?php $_smarty_tpl->tpl_vars["full_width"] = new Smarty_Variable('N', null);
$_smarty_tpl->ext->_updateScope->updateScope($_smarty_tpl, "full_width", 0);?>
				<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, ((string)$_smarty_tpl->tpl_vars['module_templates_back_dir']->value)."/ajax.visitor_details.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

			</div>
			<div id="tabs-visitor-visitedpageshistory" style="display: none; padding-top: 5px;">
				<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, ((string)$_smarty_tpl->tpl_vars['module_templates_back_dir']->value)."/ajax.visitor_visited_pages_history.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

			</div>
			<div id="tabs-visitor-geotracking" style="display: none; padding-top: 5px;">
				<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, ((string)$_smarty_tpl->tpl_vars['module_templates_back_dir']->value)."/ajax.visitor_geotracking.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

			</div>
			<div id="tabs-visitor-archive" style="display: none; padding-top: 5px;">
				<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, ((string)$_smarty_tpl->tpl_vars['module_templates_back_dir']->value)."/ajax.visitor_archive.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

			</div>
			<div id="tabs-visitor-messages" style="display: none; padding-top: 5px;">
				<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, ((string)$_smarty_tpl->tpl_vars['module_templates_back_dir']->value)."/ajax.visitor_messages.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

			</div>
			<div id="tabs-visitor-ratings" style="display: none; padding-top: 5px;">
				<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, ((string)$_smarty_tpl->tpl_vars['module_templates_back_dir']->value)."/ajax.visitor_ratings.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

			</div>
			<div id="tabs-visitor-logs" style="display: none; padding-top: 5px;">
				<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, ((string)$_smarty_tpl->tpl_vars['module_templates_back_dir']->value)."/ajax.visitor_logs.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="lcp panel" id='statistics'>
		<div id="ajax_statistics_div">
			<div class="row lcp panel-head border-bottom">
				<?php echo smartyFunctionTranslate(array('s'=>'Statistics'),$_smarty_tpl);?>
 <span id="statistics_ajax_load_span" style="padding-left: 5px;"></span>
			</div>
			<div class="row">
					<div id="tabs-statistics">
							<ul>
								<li><a id="tabs-visitors-a" href="#tabs-visitors"><span style="cursor: hand; cursor: pointer;"><?php echo smartyFunctionTranslate(array('s'=>'Visitors online'),$_smarty_tpl);?>
 <!--(<span id="count_online_visitors_ajax_load_span"><?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['count_online_visitors']->value);?>
</span>) --></span></a><span id="online_visitors_ajax_load_span"></span></li>
								<li><a id="tabs-archive-a" href="#tabs-archive"><span style="cursor: hand; cursor: pointer;"><?php echo smartyFunctionTranslate(array('s'=>'Archive'),$_smarty_tpl);?>
</span></a><span id="archive_ajax_load_span"></span></li>
								<li><a id="tabs-messages-a" href="#tabs-messages"><span style="cursor: hand; cursor: pointer;"><?php echo smartyFunctionTranslate(array('s'=>'Messages'),$_smarty_tpl);?>
</span></a><span id="messages_ajax_load_span"></span></li>
								<li><a id="tabs-tickets-a" href="#tabs-tickets"><span style="cursor: hand; cursor: pointer;"><?php echo smartyFunctionTranslate(array('s'=>'Tickets'),$_smarty_tpl);?>
</span></a><span id="tickets_ajax_load_span"></span></li>
								<li><a id="tabs-ratings-a" href="#tabs-ratings"><span style="cursor: hand; cursor: pointer;"><?php echo smartyFunctionTranslate(array('s'=>'Ratings'),$_smarty_tpl);?>
</span></a><span id="ratings_ajax_load_span"></span></li>
								<li><a id="tabs-logs-a" href="#tabs-logs"><span style="cursor: hand; cursor: pointer;"><?php echo smartyFunctionTranslate(array('s'=>'Logs'),$_smarty_tpl);?>
</span></a><span id="logs_ajax_load_span"></span></li>
							</ul>
							<div id="tabs-visitors">
								<table id="onlinevisitors_grid" class="lcp display compact nowrap radius5" cellspacing="0" width="100%" style="border: 1px solid #dddddd;">
									<thead>
										<tr>
											<th><?php echo smartyFunctionTranslate(array('s'=>'ID'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Country'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'City'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Language'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Visits'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Current page'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Host'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'IP'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Browser'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Timezone'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Resolution'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Referrer'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Page count'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'OS'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Last visit'),$_smarty_tpl);?>
</th>
										</tr>
									</thead>
									<tfoot>
									<tr>
										<th><?php echo smartyFunctionTranslate(array('s'=>'ID'),$_smarty_tpl);?>
</th>
										<th><?php echo smartyFunctionTranslate(array('s'=>'Country'),$_smarty_tpl);?>
</th>
										<th><?php echo smartyFunctionTranslate(array('s'=>'City'),$_smarty_tpl);?>
</th>
										<th><?php echo smartyFunctionTranslate(array('s'=>'Language'),$_smarty_tpl);?>
</th>
										<th><?php echo smartyFunctionTranslate(array('s'=>'Visits'),$_smarty_tpl);?>
</th>
										<th><?php echo smartyFunctionTranslate(array('s'=>'Current page'),$_smarty_tpl);?>
</th>
										<th><?php echo smartyFunctionTranslate(array('s'=>'Host'),$_smarty_tpl);?>
</th>
										<th><?php echo smartyFunctionTranslate(array('s'=>'IP'),$_smarty_tpl);?>
</th>
										<th><?php echo smartyFunctionTranslate(array('s'=>'Browser'),$_smarty_tpl);?>
</th>
										<th><?php echo smartyFunctionTranslate(array('s'=>'Timezone'),$_smarty_tpl);?>
</th>
										<th><?php echo smartyFunctionTranslate(array('s'=>'Resolution'),$_smarty_tpl);?>
</th>
										<th><?php echo smartyFunctionTranslate(array('s'=>'Referrer'),$_smarty_tpl);?>
</th>
										<th><?php echo smartyFunctionTranslate(array('s'=>'Page count'),$_smarty_tpl);?>
</th>
										<th><?php echo smartyFunctionTranslate(array('s'=>'OS'),$_smarty_tpl);?>
</th>
										<th><?php echo smartyFunctionTranslate(array('s'=>'Last visit'),$_smarty_tpl);?>
</th>
									</tr>
									</tfoot>
								</table>
							</div>
							<div id="tabs-archive">
								<table id="archive_grid" class="lcp display compact nowrap radius5" cellspacing="0" width="100%" style="border: 1px solid #dddddd;">
									<thead>
										<tr>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Date add'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'ID Chat'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Name'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Internal'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Department'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Email'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Phone'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Company'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Language'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Country'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'IP'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Host'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Duration'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Log entries'),$_smarty_tpl);?>
</th>
										</tr>
									</thead>
									<tfoot>
									<tr>
										<th><?php echo smartyFunctionTranslate(array('s'=>'Date add'),$_smarty_tpl);?>
</th>
										<th><?php echo smartyFunctionTranslate(array('s'=>'ID Chat'),$_smarty_tpl);?>
</th>
										<th><?php echo smartyFunctionTranslate(array('s'=>'Name'),$_smarty_tpl);?>
</th>
										<th><?php echo smartyFunctionTranslate(array('s'=>'Internal'),$_smarty_tpl);?>
</th>
										<th><?php echo smartyFunctionTranslate(array('s'=>'Department'),$_smarty_tpl);?>
</th>
										<th><?php echo smartyFunctionTranslate(array('s'=>'Email'),$_smarty_tpl);?>
</th>
										<th><?php echo smartyFunctionTranslate(array('s'=>'Phone'),$_smarty_tpl);?>
</th>
										<th><?php echo smartyFunctionTranslate(array('s'=>'Company'),$_smarty_tpl);?>
</th>
										<th><?php echo smartyFunctionTranslate(array('s'=>'Language'),$_smarty_tpl);?>
</th>
										<th><?php echo smartyFunctionTranslate(array('s'=>'Country'),$_smarty_tpl);?>
</th>
										<th><?php echo smartyFunctionTranslate(array('s'=>'IP'),$_smarty_tpl);?>
</th>
										<th><?php echo smartyFunctionTranslate(array('s'=>'Host'),$_smarty_tpl);?>
</th>
										<th><?php echo smartyFunctionTranslate(array('s'=>'Duration'),$_smarty_tpl);?>
</th>
										<th><?php echo smartyFunctionTranslate(array('s'=>'Log entries'),$_smarty_tpl);?>
</th>
									</tr>
									</tfoot>
								</table>
							</div>
							<div id="tabs-messages">
								<table id="messages_grid" class="lcp display compact nowrap radius5" cellspacing="0" width="100%" style="border: 1px solid #dddddd;">
									<thead>
										<tr>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Date add'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Name'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Email'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Phone'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Department'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Question'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'IP'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Status'),$_smarty_tpl);?>
</th>
										</thead>
										<tfoot>
										<tr>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Date add'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Name'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Email'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Phone'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Department'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Question'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'IP'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Status'),$_smarty_tpl);?>
</th>
										</tr>
										</tfoot>
									</table>
								</div>
								<div id="tabs-tickets">
								<table id="tickets_grid" class="lcp display compact nowrap radius5" cellspacing="0" width="100%" style="border: 1px solid #dddddd;">
									<thead>
										<tr>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Date add'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Department'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Subject'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Staff'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Client'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Last reply'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Priority'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Status'),$_smarty_tpl);?>
</th>
										</thead>
										<tfoot>
										<tr>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Date add'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Department'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Subject'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Staff'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Client'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Last reply'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Priority'),$_smarty_tpl);?>
</th>
											<th><?php echo smartyFunctionTranslate(array('s'=>'Status'),$_smarty_tpl);?>
</th>
										</tr>
										</tfoot>
									</table>
								</div>
								<div id="tabs-ratings">
									<table id="ratings_grid" class="lcp display compact nowrap radius5" cellspacing="0" width="100%" style="border: 1px solid #dddddd;">
										<thead>
											<tr>
												<th><?php echo smartyFunctionTranslate(array('s'=>'Politness'),$_smarty_tpl);?>
</th>
												<th><?php echo smartyFunctionTranslate(array('s'=>'Qualification'),$_smarty_tpl);?>
</th>
												<th><?php echo smartyFunctionTranslate(array('s'=>'Date add'),$_smarty_tpl);?>
</th>
												<th><?php echo smartyFunctionTranslate(array('s'=>'Internal'),$_smarty_tpl);?>
</th>
												<th><?php echo smartyFunctionTranslate(array('s'=>'Name'),$_smarty_tpl);?>
</th>
												<th><?php echo smartyFunctionTranslate(array('s'=>'Email'),$_smarty_tpl);?>
</th>
												<th><?php echo smartyFunctionTranslate(array('s'=>'Company'),$_smarty_tpl);?>
</th>
												<th><?php echo smartyFunctionTranslate(array('s'=>'Comment'),$_smarty_tpl);?>
</th>
												<th><?php echo smartyFunctionTranslate(array('s'=>'Status'),$_smarty_tpl);?>
</th>
											</thead>
											<tfoot>
											<tr>
												<th><?php echo smartyFunctionTranslate(array('s'=>'Politness'),$_smarty_tpl);?>
</th>
												<th><?php echo smartyFunctionTranslate(array('s'=>'Qualification'),$_smarty_tpl);?>
</th>
												<th><?php echo smartyFunctionTranslate(array('s'=>'Date add'),$_smarty_tpl);?>
</th>
												<th><?php echo smartyFunctionTranslate(array('s'=>'Internal'),$_smarty_tpl);?>
</th>
												<th><?php echo smartyFunctionTranslate(array('s'=>'Name'),$_smarty_tpl);?>
</th>
												<th><?php echo smartyFunctionTranslate(array('s'=>'Email'),$_smarty_tpl);?>
</th>
												<th><?php echo smartyFunctionTranslate(array('s'=>'Company'),$_smarty_tpl);?>
</th>
												<th><?php echo smartyFunctionTranslate(array('s'=>'Comment'),$_smarty_tpl);?>
</th>
												<th><?php echo smartyFunctionTranslate(array('s'=>'Status'),$_smarty_tpl);?>
</th>
											</tr>
											</tfoot>
										</table>
									</div>
									<div id="tabs-logs">
										<table id="logs_grid" class="lcp display compact nowrap radius5" cellspacing="0" width="100%" style="border: 1px solid #dddddd;">
											<thead>
												<tr>
													<th><?php echo smartyFunctionTranslate(array('s'=>'Date add'),$_smarty_tpl);?>
</th>
													<th align="left"><?php echo smartyFunctionTranslate(array('s'=>'Message'),$_smarty_tpl);?>
</th>
												</thead>
												<tfoot>
												<tr>
													<th><?php echo smartyFunctionTranslate(array('s'=>'Date add'),$_smarty_tpl);?>
</th>
													<th align="left"><?php echo smartyFunctionTranslate(array('s'=>'Message'),$_smarty_tpl);?>
</th>
												</tr>
												</tfoot>
											</table>
									</div>
						</div>
			
			</div>



	</div>
</div>
</div>


<div style="line-height: 30px; text-align: center;">
	<a href="<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['module_link']->value);?>
"><?php echo smartyFunctionTranslate(array('s'=>'Go to module configuration page'),$_smarty_tpl);?>
</a>
</div>






<div id="dialog-form-visitordetails" title="<?php echo smartyFunctionTranslate(array('s'=>'View visitor details'),$_smarty_tpl);?>
" style="display:none" class="bootstrap">
	<div id="tabs-visitordetails">
		<ul>
			<li>
				<a href="#tabs-visitordetails-details"><?php echo smartyFunctionTranslate(array('s'=>'Details:'),$_smarty_tpl);?>
</a> 
			</li>
			<li>
				<a href="#tabs-visitordetails-visitedpageshistory"><?php echo smartyFunctionTranslate(array('s'=>'Visited pages history'),$_smarty_tpl);?>
</a>
			</li>
			<li>
				<a href="#tabs-visitordetails-geotracking" id="tabs-visitordetails-geotracking-a"><?php echo smartyFunctionTranslate(array('s'=>'GeoTracking'),$_smarty_tpl);?>
</a>
			</li>
		</ul>
		<div id="tabs-visitordetails-details">
			<?php $_smarty_tpl->tpl_vars["full_width"] = new Smarty_Variable('Y', null);
$_smarty_tpl->ext->_updateScope->updateScope($_smarty_tpl, "full_width", 0);?>
			<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, ((string)$_smarty_tpl->tpl_vars['module_templates_back_dir']->value)."/ajax.visitor_details.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

		</div>
		<div id="tabs-visitordetails-visitedpageshistory">
			<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, ((string)$_smarty_tpl->tpl_vars['module_templates_back_dir']->value)."/ajax.visitor_visited_pages_history.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

		</div>
		<div id="tabs-visitordetails-geotracking">
			<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, ((string)$_smarty_tpl->tpl_vars['module_templates_back_dir']->value)."/ajax.visitor_geotracking.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

		</div>
	</div>
</div>


<div id="dialog-form-archive" title="<?php echo smartyFunctionTranslate(array('s'=>'View archive details'),$_smarty_tpl);?>
" style="display:none">
	<div style="width: 700px; height:300px; overflow-y: scroll; background: white;" class="bootstrap">
		<table border="0" width="100%" height="100%">
			<tr>
				<td valign="top"><div id="archive_message">&nbsp;</div></td>
			</tr>
		</table>
	</div>
</div>

<div id="dialog-form-messages" title="<?php echo smartyFunctionTranslate(array('s'=>'View Message'),$_smarty_tpl);?>
" style="display:none" class="bootstrap">
	
	
	<div class="row">
		<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 lcp panel">
			<div class="lcp panel-head border-bottom"><?php echo smartyFunctionTranslate(array('s'=>'Date'),$_smarty_tpl);?>
</div>
			<div id="messages_date">&nbsp;</div>
		</div>

		<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 lcp panel">
			<div class="lcp panel-head border-bottom"><?php echo smartyFunctionTranslate(array('s'=>'Department'),$_smarty_tpl);?>
</div>
			<div id="messages_department">&nbsp;</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 lcp panel">
			<div class="lcp panel-head border-bottom"><?php echo smartyFunctionTranslate(array('s'=>'Sender'),$_smarty_tpl);?>
</div>
			<div><?php echo smartyFunctionTranslate(array('s'=>'Name:'),$_smarty_tpl);?>
 <b><span id="messages_sender_name"></span></b></div>
			<div><?php echo smartyFunctionTranslate(array('s'=>'Email:'),$_smarty_tpl);?>
 <b><span id="messages_sender_email"></span></b></div>
			<div><?php echo smartyFunctionTranslate(array('s'=>'Phone:'),$_smarty_tpl);?>
 <b><span id="messages_sender_phone"></span></b></div>
			<div><?php echo smartyFunctionTranslate(array('s'=>'Company:'),$_smarty_tpl);?>
 <b><span id="messages_sender_company"></span></b></div>
			<div><?php echo smartyFunctionTranslate(array('s'=>'Message:'),$_smarty_tpl);?>
 <b><span id="messages_message"></span></b></div>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 lcp panel" id="messages_reply_tr">
			<div class="lcp panel-head border-bottom"><?php echo smartyFunctionTranslate(array('s'=>'Reply'),$_smarty_tpl);?>
</div>
			<textarea id="messages_reply" name="messages_reply" rows="2" class="form-control" style="width: 100%;"></textarea><br>
			<a href="javascript:{}" name="messages_reply_send_a" id="messages_reply_send_a" class="lcp button" title="<?php echo smartyFunctionTranslate(array('s'=>'Send'),$_smarty_tpl);?>
"><span class="fa fa-paper-plane"></span> <?php echo smartyFunctionTranslate(array('s'=>'Send'),$_smarty_tpl);?>
</a>
		</div>
	</div>

</div>

<div id="dialog-form-ticket-details" title="<?php echo smartyFunctionTranslate(array('s'=>'View ticket details'),$_smarty_tpl);?>
" style="display:none">
	<div style="width: 1000px; height:500px; overflow-y: scroll;" id="ajax_ticket_details_div" class="bootstrap">
		<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, ((string)$_smarty_tpl->tpl_vars['module_templates_back_dir']->value)."/ajax.ticket_details.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

	</div>
</div>

<div id="dialog-form-ratings" title="<?php echo smartyFunctionTranslate(array('s'=>'View Rating'),$_smarty_tpl);?>
" style="display:none" class="bootstrap">
	
	<div class="row">
		<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 lcp panel">
			<div class="lcp panel-head"><?php echo smartyFunctionTranslate(array('s'=>'Politness'),$_smarty_tpl);?>
</div>
			<div id="ratings_politness">&nbsp;</div>
		</div>
		<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 lcp panel">
			<div class="lcp panel-head"><?php echo smartyFunctionTranslate(array('s'=>'Qualification'),$_smarty_tpl);?>
</div>
			<div id="ratings_qualification">&nbsp;</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 lcp panel">
			<div class="lcp panel-head border-bottom"><?php echo smartyFunctionTranslate(array('s'=>'Date'),$_smarty_tpl);?>
</div>
			<div id="ratings_date">&nbsp;</div>
		</div>
		<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 lcp panel">
			<div class="lcp panel-head border-bottom"><?php echo smartyFunctionTranslate(array('s'=>'Internal'),$_smarty_tpl);?>
</div>
			<div id="ratings_internal">&nbsp;</div>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 lcp panel">
			<div class="lcp panel-head border-bottom"><?php echo smartyFunctionTranslate(array('s'=>'Sender'),$_smarty_tpl);?>
</div>
			<div><?php echo smartyFunctionTranslate(array('s'=>'Name:'),$_smarty_tpl);?>
 <b><span id="ratings_sender_name"></span></b></div>
			<div><?php echo smartyFunctionTranslate(array('s'=>'Email:'),$_smarty_tpl);?>
 <b><span id="ratings_sender_email"></span></b></div>
			<div><?php echo smartyFunctionTranslate(array('s'=>'Phone:'),$_smarty_tpl);?>
 <b><span id="ratings_sender_phone"></span></b></div>
			<div><?php echo smartyFunctionTranslate(array('s'=>'Company:'),$_smarty_tpl);?>
 <b><span id="ratings_sender_company"></span></b></div>
			<div><?php echo smartyFunctionTranslate(array('s'=>'Comment:'),$_smarty_tpl);?>
 <b><span id="ratings_comment"></span></b></div>
		</div>
	</div>

</div>

<div id="dialog-online-internal-users" title="<?php echo smartyFunctionTranslate(array('s'=>'Online Staff'),$_smarty_tpl);?>
" style="display:none">
	<div id="ajax_online_internal_users_div">
		<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, ((string)$_smarty_tpl->tpl_vars['module_templates_back_dir']->value)."/ajax.online_internal_users.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

	</div>
</div>

<div id="dialog-predefined-messages" title="<?php echo smartyFunctionTranslate(array('s'=>'Predefined Messages'),$_smarty_tpl);?>
" style="display:none">
	<?php if ((!empty($_smarty_tpl->tpl_vars['predefined_messages']->value))) {?>
	<select name="predefined_messages_select" id="predefined_messages_select" class="lcp formfield form-control">
		<?php
$_from = $_smarty_tpl->tpl_vars['predefined_messages']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_value_0_saved_item = isset($_smarty_tpl->tpl_vars['value']) ? $_smarty_tpl->tpl_vars['value'] : false;
$__foreach_value_0_saved_key = isset($_smarty_tpl->tpl_vars['key']) ? $_smarty_tpl->tpl_vars['key'] : false;
$_smarty_tpl->tpl_vars['value'] = new Smarty_Variable();
$__foreach_value_0_total = $_smarty_tpl->smarty->ext->_foreach->count($_from);
if ($__foreach_value_0_total) {
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable();
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['value']->value) {
$__foreach_value_0_saved_local_item = $_smarty_tpl->tpl_vars['value'];
?>
			<option value="<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value']->value['id_predefinedmessage']);?>
" style="color: #606060; background-color: white;"><?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value']->value['title']);?>
</option>
		<?php
$_smarty_tpl->tpl_vars['value'] = $__foreach_value_0_saved_local_item;
}
}
if ($__foreach_value_0_saved_item) {
$_smarty_tpl->tpl_vars['value'] = $__foreach_value_0_saved_item;
}
if ($__foreach_value_0_saved_key) {
$_smarty_tpl->tpl_vars['key'] = $__foreach_value_0_saved_key;
}
?>
	</select>
	
	<?php } else { ?>
		<?php echo smartyFunctionTranslate(array('s'=>'There are no predefined messages.'),$_smarty_tpl);?>

	<?php }?>
</div>

<div id="menu-emoticons" style="position:absolute; z-index: 9999; display:none; float:left; clear:both; background: white;" class="lcp panel">
	

	<table border="0" width="100%" cellspacing="0" cellpadding="0" class="lcp emoticon-table">
	<tr>
	<?php
$__section_id_0_saved = isset($_smarty_tpl->tpl_vars['__smarty_section_id']) ? $_smarty_tpl->tpl_vars['__section_id'] : false;
$__section_id_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['emoticons']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_id_0_total = $__section_id_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_id'] = new Smarty_Variable(array());
if ($__section_id_0_total != 0) {
for ($_smarty_tpl->tpl_vars['__smarty_section_id']->value['iteration'] = 1, $_smarty_tpl->tpl_vars['__smarty_section_id']->value['index'] = 0; $_smarty_tpl->tpl_vars['__smarty_section_id']->value['iteration'] <= $__section_id_0_total; $_smarty_tpl->tpl_vars['__smarty_section_id']->value['iteration']++, $_smarty_tpl->tpl_vars['__smarty_section_id']->value['index']++){
?>
	   <td align="center" style="text-align: center; width: 40px;">
	   	<input type="hidden" name="emoticon_code" id="emoticon_code_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['emoticons']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_id']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_id']->value['index'] : null)]['id_emoticon']);?>
" value='<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['emoticons']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_id']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_id']->value['index'] : null)]['code']);?>
'>
	   	<img title='<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['emoticons']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_id']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_id']->value['index'] : null)]['code']);?>
' border="0" src="<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['module_path']->value);?>
views/img/emoticons/<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['emoticons']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_id']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_id']->value['index'] : null)]['filename']);?>
" id="emoticon_img_admin_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['emoticons']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_id']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_id']->value['index'] : null)]['id_emoticon']);?>
" class="lcp emoticon-img"></td>
	   <?php if ((isset($_smarty_tpl->tpl_vars['__smarty_section_id']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_id']->value['iteration'] : null)%5 == 0) {?>
	       </tr><tr>
	   <?php }?>
	<?php
}
}
if ($__section_id_0_saved) {
$_smarty_tpl->tpl_vars['__smarty_section_id'] = $__section_id_0_saved;
}
?>
	</tr>
	</table> 	
	
</div>

</div> <!-- END root -->

<?php echo '<script'; ?>
 type="text/javascript">
$( "#dialog-form-archive" ).dialog({
	autoOpen: false,
	/*height: 300,*/
	width: 715,
	modal: false,
	close: function() {
		/*allFields.val( "" ).removeClass( "ui-state-error" );*/
	}
});


$( "#dialog-form-messages" ).dialog({
	autoOpen: false,
	/*height: 300,*/
	width: 500,
	modal: false,
	close : function() {
		/*allFields.val( "" ).removeClass( "ui-state-error" );*/
	}
});

$( "#dialog-form-ticket-details" ).dialog({
	autoOpen: false,
	/*height: 300,*/
	width: 1015,
	modal: false,
	close : function() {
		/*allFields.val( "" ).removeClass( "ui-state-error" );*/
	}
});

$( "#dialog-form-ratings" ).dialog({
	autoOpen: false,
	/*height: 300,*/
	width: 500,
	modal: false,
	close: function() {
		/*allFields.val( "" ).removeClass( "ui-state-error" );*/
	}
});

$('input[id^="send_file_upload"]').uploadifive({
	'multi'    : false,
	"formData" : {
		"location" : "uploads",
	},
	'buttonText' : lcp.l('Send file'),
	"uploadScript"     : lcp_path+"libraries/uploadify/uploadifive.php",
	"onUploadComplete" : function(file, data)
	{
	//alert(data);
		if (data == "error1") alert(lcp.l("File exists, choose different filename."));
		else if (data == "error2") alert(lcp.l("Invalid file type."));
		else
		{
			var e = $.Event("keydown");
					e.which = 13;
					e.keyCode = 13;
			$("#msg_admin").val(lcp_url+"uploads/"+data).trigger(e);
		}
	},
	height     : 26,
	//width      : 100,
});
		
<?php echo '</script'; ?>
>




<?php }
}
