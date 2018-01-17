<?php
/**
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
 */

if (call_user_func('session_id') === '')
	call_user_func('session_start');

header('Access-Control-Allow-Origin: *');
ini_set('max_execution_time', '2880');
require_once (dirname(__FILE__).'/classes/Lcp.php');

$session = LcpTools::getValue('session');
if (!empty($session))
	$_SESSION['lcp'] = LcpTools::getValue('session');

#error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));
$type = LcpTools::isSubmit('type') ? LcpTools::getValue('type') : false;
$lcp = Lcp::i();
$filename = __FILE__; # pathinfo(__FILE__, PATHINFO_FILENAME);
$data = LcpTools::getValue('data');
$primary_settings = $lcp->getPrimarySettings();


if ($type != 'popupChat' && $type != 'newChatAlert') 
{
	if (LcpTools::isSubmit('token')) 
	{
		if (!$lcp->isTokenValid(LcpTools::getValue('token'))) 
			die('Invalid Token!');
	} 
	else 
		die('Invalid Token!');
}


switch ($type) 
{
	case 'departments':
	case 'staffprofiles':
	case 'predefinedmessages':
	case 'onlinevisitors':
	case 'archive':
	case 'visitorarchive':
	case 'messages':
	case 'tickets':
	case 'customertickets':
	case 'visitormessages':
	case 'ratings':
	case 'visitorratings':
	case 'logs':
	case 'visitorlogs':
		$response = $lcp->fillGridDataTables($type, $_POST);
		break;

	case 'addUpdateVisitor':
		$response = $lcp->addUpdateVisitor(LcpTools::getValue('data'));
		die($response);

	case 'syncStaffProfiles':
		$response = $lcp->syncStaffProfiles();
		die($response);

	case 'transferVisitor':
		$response = $lcp->transferVisitor(LcpTools::getValue('data'));
		die(LcpTools::jsonEncode($response));

	case 'getTheme':
		$response = $lcp->getTheme(LcpTools::getValue('data'));
		die(LcpTools::jsonEncode($response));

	case 'getIconset':
		$response = $lcp->getIconset(LcpTools::getValue('data'));
		die(LcpTools::jsonEncode($response));

	case 'getSetting':
		$response = $lcp->getSetting(LcpTools::getValue('data'));
		die(LcpTools::jsonEncode($response));

	case 'generateChatWidgetFront':
		$html = $lcp->generateChatWidgetFront(LcpTools::getValue('data'));		
		die($html);

	case 'generateChatWidgetAdmin':
		$html = $lcp->generateChatWidgetAdmin(LcpTools::getValue('data'));		
		die($html);
		
	case 'showVisitorDetails':
		$lcp->assignVisitorDetails(LcpTools::getValue('data'));		
		$response = array(
			'success' => true,
			'response' => array(
				'ajax.visitor_details.tpl' => $lcp->fetch(dirname(__FILE__).'/views/templates/admin/ajax.visitor_details.tpl'),
			)
		);
		die(LcpTools::jsonEncode($response));

	case 'showVisitorVisitedPagesHistory':
		$lcp->assignVisitorVisitedPagesHistory(LcpTools::getValue('data'));		
		$response = array(
			'success' => true,
			'response' => array(
				'ajax.visitor_visited_pages_history.tpl' => $lcp->fetch(dirname(__FILE__).'/views/templates/admin/ajax.visitor_visited_pages_history.tpl'),
			)
		);
		die(LcpTools::jsonEncode($response));

	case 'showVisitorGeoTracking':
		$lcp->assignVisitorGeoTracking(LcpTools::getValue('data'));		
		$response = array(
			'success' => true,
			'response' => array(
				'ajax.visitor_geotracking.tpl' => $lcp->fetch(dirname(__FILE__).'/views/templates/admin/ajax.visitor_geotracking.tpl'),
			)
		);
		die(LcpTools::jsonEncode($response));

	case 'showOnlineInternalUsers':
		$response = $lcp->showOnlineInternalUsers(LcpTools::getValue('data'));
		die($response);

	case 'setCookie':
		$response = $lcp->setCookie(LcpTools::getValue('data'));
		die($response);

	case 'addRating':
		$response = $lcp->addRating(LcpTools::getValue('data'));
		die('001');

	case 'getRating':
		$response = $lcp->getRating(LcpTools::getValue('data'));
		die(LcpTools::jsonEncode($response));

	case 'replyToMessage':
		$response = $lcp->replyToMessage(LcpTools::getValue('data'));
		die(LcpTools::jsonEncode($response));

	case 'getMessage':
		$response = $lcp->getMessage(LcpTools::getValue('data'));
		die(LcpTools::jsonEncode($response));

	case 'getTicket':
		$lcp->getTicket(LcpTools::getValue('data'));
		if ($data['from'] == 'Customer') 
			$response = $lcp->fetch(dirname(__FILE__).'/views/templates/front/ajax.ticket_details.tpl');
		else 
			$response = $lcp->fetch(dirname(__FILE__).'/views/templates/admin/ajax.ticket_details.tpl');
		die($response);

	case 'getCustomerTickets':
		$response = $lcp->fetch(dirname(__FILE__).'/views/templates/front/ajax.tickets_list.tpl');
		die($response);	

	case 'getPredefinedMessage':
		$response = $lcp->getPredefinedMessage(LcpTools::getValue('data'));
		die(LcpTools::jsonEncode($response));

	case 'addMessage':
		$response = $lcp->addMessage(LcpTools::getValue('data'));
		die('002');

	case 'clearDatabase':
		$response = $lcp->clearDatabase();
		die('003');

	case 'deleteSettings':
		$result = $lcp->deleteSettings(LcpTools::getValue('data'));
		if ($result === true) 
			$response = array(
				'success' => true,
				'response' => $lcp->showSettingsDropdown()
			);
		elseif ($result == 'ERROR_DEMO_MODE') 
			$response = array(
				'success' => false,
				'response' => '004',
			);
		die(LcpTools::jsonEncode($response));

	case 'saveSettings':
		$result = $lcp->saveSettings(LcpTools::getValue('data'));
		if ($result === true) 
			$response = array(
				'success' => true,
				'response' => '005',
			);
		elseif ($result == 'ERROR_DEMO_MODE') 
			$response = array(
				'success' => false,
				'response' => '006',
			);
		die(LcpTools::jsonEncode($response));

	case 'saveAsSettings':
		$lcp->saveAsSettings(LcpTools::getValue('data'));
		die($lcp->showSettingsDropdown());

	case 'deleteTheme':
		$result = $lcp->deleteTheme(LcpTools::getValue('data'));
		if ($result === true) 
			$response = array(
				'success' => true,
				'response' => $lcp->showThemesDropdown()
			);
		elseif ($result == 'ERROR_DEMO_MODE') 
			$response = array(
				'success' => false,
				'response' => '007',
			);
		die(LcpTools::jsonEncode($response));

	case 'saveTheme':
		$result = $lcp->saveTheme(LcpTools::getValue('data'));
		if ($result === true) 
			$response = array(
				'success' => true,
				'response' => '008'
			);
		elseif ($result == 'ERROR_DEMO_MODE') 
			$response = array(
				'success' => false,
				'response' => '009'
			);
		die(LcpTools::jsonEncode($response));

	case 'saveAsTheme':
		$lcp->saveAsTheme(LcpTools::getValue('data'));
		die($lcp->showThemesDropdown());

	case 'deleteIconset':
		$result = $lcp->deleteIconset(LcpTools::getValue('data'));
		if ($result === true) 
			$response = array(
				'success' => true,
				'response' => $lcp->showIconsetsDropdown()
			);
		elseif ($result == 'ERROR_DEMO_MODE') 
			$response = array(
				'success' => false,
				'response' => '010'
			);
		die(LcpTools::jsonEncode($response));

	case 'saveIconset':
		$result = $lcp->saveIconset(LcpTools::getValue('data'));
		if ($result === true) 
			$response = array(
				'success' => true,
				'response' => '011'
			);
		elseif ($result == 'ERROR_DEMO_MODE') 
			$response = array(
				'success' => false,
				'response' => '012'
			);
		die(LcpTools::jsonEncode($response));

	case 'saveAsIconset':
		$lcp->saveAsIconset(LcpTools::getValue('data'));
		die($lcp->showIconsetsDropdown());

	case 'getTranslations':
		$lcp->getTranslations(LcpTools::getValue('data'));
		die($lcp->fetch(dirname(__FILE__).'/views/templates/admin/ajax.translations.tpl'));

	case 'saveTranslations':
		$lcp->saveTranslations(LcpTools::getValue('data'));
		$response = array(
			'success' => true,
		);
		die(LcpTools::jsonEncode($response));

	case 'updateMessage':
		$response = $lcp->updateMessage(LcpTools::getValue('data'));
		die('013');

	case 'updateTicket':
		$response = $lcp->updateTicket(LcpTools::getValue('data'));
		die('014');

	case 'addTicketReply':
		$response = $lcp->addTicketReply(LcpTools::getValue('data'));
		die('015');	

	case 'updateRating':
		$response = $lcp->updateRating(LcpTools::getValue('data'));
		die('016');

	case 'getArchive':
		$response = $lcp->getArchive(LcpTools::getValue('data'));
		die(LcpTools::jsonEncode($response));

	case 'chatRequestFromStaffToStaff':
		$lcp->syncOnlineUsers();
		$lcp->chatRequestFromStaffToStaff(LcpTools::getValue('data'));
		$json = $lcp->syncChatDialog();
		$response = array(
			'success' => true,
			'response' => $json,
		);

		die(LcpTools::jsonEncode($response));

	case 'chatAcceptedFromStaff':
		$lcp->syncOnlineUsers();
		$lcp->chatAcceptedFromStaff(LcpTools::getValue('data'));
		$json = $lcp->syncChatDialog();
		#$lcp->syncFrontChatDialog($data);
		$response = array(
			'success' => true,
			'response' => $json,
		);
		
		die(LcpTools::jsonEncode($response));
	
	case 'chatAcceptedFromClient':
		$server_data = $lcp->chatAcceptedFromClient(LcpTools::getValue('data'));
		#$json = $lcp->syncChatDialog();
		#$lcp->syncFrontChatDialog($data);
		$response = array(
			'success' => true,
		);
		die(LcpTools::jsonEncode($response));

	case 'chatDeniedFromClient':
		$server_data = $lcp->chatDeniedFromClient(LcpTools::getValue('data'));
		#$lcp->syncChatDialog();
		$response = array('success' => true);
		die(LcpTools::jsonEncode($response));

	case 'chatClosedFromStaff':
		$lcp->syncOnlineUsers();
		$lcp->chatClosedFromStaff(LcpTools::getValue('data'));
		#$lcp->syncChatDialog();
		#$json = $lcp->syncFrontChatDialog($data);
		$response = array(
			'success' => true,
		);
		die(LcpTools::jsonEncode($response));

	case 'chatMessageFromStaff':
		$lcp->chatMessageFromStaff(LcpTools::getValue('data'));
		#$lcp->syncChatDialog();
		#$lcp->syncFrontChatDialog($data);
		$response = array('success' => true);
		die(LcpTools::jsonEncode($response));

	case 'chatMessageFromClient':
		$server_data = $lcp->chatMessageFromClient(LcpTools::getValue('data'));
		#$lcp->syncChatDialog();
		#$lcp->syncFrontChatDialog($data);
		$response = array('success' => true);
		die(LcpTools::jsonEncode($response));

	case 'chatRequestFromStaff':
		$lcp->syncOnlineUsers();
		$lcp->chatRequestFromStaff(LcpTools::getValue('data'));
		$json = $lcp->syncChatDialog();
		#$lcp->syncFrontChatDialog($data);
		$response = array(
			'success' => true,
			'response' => $json,
		);
		die(LcpTools::jsonEncode($response));

	case 'chatRequestFromClient':
		$server_data = $lcp->chatRequestFromClient(LcpTools::getValue('data'));
		#$json = $lcp->syncFrontChatDialog();
		$response = array(
			'success' => true,
			#'response' => $json,
		);
		die(LcpTools::jsonEncode($response));

	case 'chatClosedFromClient':
		$server_data = $lcp->chatClosedFromClient(LcpTools::getValue('data'));
		#$lcp->syncChatDialog();
		$response = array('success' => true);
		die(LcpTools::jsonEncode($response));

	case 'changeStaffStatus':
		$lcp->syncOnlineUsers();
		$result = $lcp->changeStaffStatus(LcpTools::getValue('data'));
		$json = $lcp->syncChatDialog();
		#$lcp->syncFrontChatDialog($data);
		if ($result == false) 
			$response = array(
				'success' => false,
				'response' => '020'
			);
		else 
			$response = array(
				'success' => true,
				'response' => $json,
			);
		die(LcpTools::jsonEncode($response));	

	case 'syncFrontChatDialog':
		$lcp->syncOnlineVisitors(LcpTools::getValue('data'));
		$json = $lcp->syncFrontChatDialog($data);
		$json = array_merge($json, array(
				'id_visitor' => $data['id_visitor'],
				'is_chat_invitation' => $lcp->isChatInvitation(LcpTools::getValue('data')),
				'status' => $lcp->getChatStatus(),
			));

		$response = array(
			'success' => true,
			'response' => $json,
		);
		die(LcpTools::jsonEncode($response));
	
	case 'syncChatDialog':
		$lcp->syncOnlineUsers();
		$json = $lcp->syncChatDialog();
		$response = array(
			'success' => true,
			'response' => $json,
		);
		die(LcpTools::jsonEncode($response));


	case 'popupChat':
		#die($lcp->popupChat());
		$lcp->smarty->assign('chat_type', $primary_settings['chat_type']);
		$lcp->smarty->assign('media', $lcp->popupChat());
		$response = $lcp->fetch(dirname(__FILE__).'/views/templates/front/chat_window.tpl');
		die($response);
	break;

	case 'newChatAlert':
		die('You have new incoming chat request!');
	default:
		break;
}

?>