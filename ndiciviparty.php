<?php

require_once 'ndiciviparty.civix.php';

function ndiciviparty_civicrm_alterContent( &$content, $context, $tplName, &$object ) {
/*** Replaces old template overrides ***/
  switch($tplName) {
    case "CRM/Activity/Form/Activity.tpl":
      $content .= '<script type="text/javascript">cj(".crm-activity-form-block-attachment").hide(); </script>';
      break;
    case "CRM/Event/Form/ManageEvent/EventInfo.tpl":
      $content .= '<script type="text/javascript">
                     cj(".crm-event-manage-eventinfo-form-block-is_map").hide();
                           cj(".crm-event-manage-eventinfo-form-block-is_public").hide();
                     cj(".crm-event-manage-eventinfo-form-block-is_share").hide();
                   </script>';
      break;
    case "CRM/Admin/Page/Tag.tpl":
      $content .= '<script type="text/javascript">
                     cj(".crm-tag-used_for").hide();
                     cj("th:contains(\'Used For\')").hide();
                     cj(".crm-tag-is_reserved").hide();
                     cj("th:contains(\'Reserved?\')").hide()
                  </script>';
      break;
    case "CRM/Contact/Form/Contact.tpl":
      $content .= '<script type="text/javascript">
                     cj("label[for=\'external_identifier\']").parent().hide();
                     cj("label[for=\'image_URL\']").parent().hide();
                     cj("label[for=\'internal_identifier_display\']").text("Internal Identifier");
                     cj("div.collapsible-title:contains(\'Signature\')").hide();
                     cj("label[for=\'prefix_id\']").parent().hide();
                     cj("label[for=\'suffix_id\']").parent().hide();
                     cj("label[for=\'nick_name\']").parent().hide();
                     cj("label[for=\'contact_sub_type\']").parent().hide();
                     cj("span:contains(\'Suffix\')").hide();
                     cj("input[name*=\'postal_code_suffix\']").hide();
                     cj("input[name*=\'[street_address]\']").parent().append(\'<br>\');
                  </script>';
      break;
  }

}


function ndiciviparty_add_default_dashboard($contactid){
  $result = civicrm_api3('Dashboard', 'get', array(
    'name' => "contact_per_month",
  ));
  $exists=0;
$dashletid=0;
  if($result['count']>0){
    $dashletid = $result['id'];
    $result = civicrm_api3('DashboardContact','get',array(
      'contact_id' =>$contactid,
'return' => array("dashboard_id", "contact_id"),
    ));
    if($result['count']>0){
      foreach ($result['values'] as $key => $value){
        if($value['dashboard_id']==$dashletid){
          $exists = 1; 
        }
      }
    }
    if($exists!=1){
$tx = new CRM_Core_Transaction();
$dashlet = array(
        'dashboard_id' => $dashletid,
        'contact_id' => $contactid,
        'is_active' => 1,
        'column_no' => 0,
        'is_minimized' => 0,
        'is_fullscreen' => 0,
        'weight' => 0,
      );

try {
      $add=civicrm_api3('DashboardContact', 'create', $dashlet);
} catch (CiviCRM_API3_Exception $e) {
  $tx->rollback();
  echo get_class($e) . ' -- ' . $e->getMessage() . "\n";
  echo $e->getTraceAsString() . "\n";
  print_r($e->getExtraParams());
}
    }//end if exists
  }//end if dashlet found
}

function ndiciviparty_remove_default_dashboard($contactid){
  $result = civicrm_api3('Dashboard', 'get', array(
    'name' => "contact_per_month",
  ));
  $exists=0;
  if($result['count']>0){
    $dashletid = $result['id'];
    $result = civicrm_api3('DashboardContact','get',array(
      'contact_id' =>$contactid,
'return' => array("dashboard_id", "contact_id"),
    ));
    foreach ($result['values'] as $key => $value){
      if($value['dashboard_id'==$dashletid]){
        civicrm_api3('DashboardContact','delete',array(
          'id'=>$value['id']
        ));
      }
    }
  }//end if dashlet found
}


/**
 * Implementation of hook_civicrm_install
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function ndiciviparty_civicrm_install() {
  //set homepage = civicrm
  variable_set('site_frontpage','civicrm');

$result = civicrm_api3('Setting', 'create', array(
  'address_options' => array("1", "2", "4","5","7","8","9"),
  'address_format' => "{contact.address_name}\\n{contact.street_address}\\n{contact.supplemental_address_1}\\n{contact.city}{, }{contact.state_province}{ }{contact.postal_code}\\n{contact.county}{ }{contact.country}",
  'dashboardCacheTimeout' => 1));

  return _ndiciviparty_civix_civicrm_install();
}

/**
 * Implementation of hook_civicrm_uninstall
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function ndiciviparty_civicrm_uninstall() {
  return _ndiciviparty_civix_civicrm_uninstall();
}

/**
 * Implementation of hook_civicrm_pageRun
 */
function ndiciviparty_civicrm_pageRun(&$page ) {
$pageName = $page->getVar('_name');
  if ($pageName == 'CRM_Case_Page_DashBoard') { 
}
}

/**
 * Implementation of hook_civicrm_pageRun
 */
function ndiciviparty_civicrm_dashboard_defaults($availableDashlets, &$defaultDashlets){
$contactID = CRM_Core_Session::singleton()->get('userID');
/*
  try{
   $dashlet = civicrm_api3('DashboardContact', 'get', array(
      'dashboard_id'  =>  $availableDashlets['contact_per_month']['id'],
      'contact_id' => $contactID,
      'is_active' => 1,
   ));
}
catch (CiviCRM_API3_Exception $e) {
   $error = $e->getMessage();
}

  $defaultDashlets[] = array(
    'dashboard_id' => $availableDashlets['contact_per_month']['id'],
    'is_active' => 1,
    'column_no' => 1,
    'contact_id' => $contactID,
  );
*/
}

/**
 * Implementation of hook_civicrm_dashboard
 */
function ndiciviparty_civicrm_dashboard( $contactID, &$contentPlacement ) {
ndiciviparty_add_default_dashboard($contactID);
  //Communication
  $sendMailing = CRM_Utils_System::url('civicrm/mailing/send', $query = 'reset=1' );
    CRM_Core_Resources::singleton()->addStyleFile('org.ndi.ndiciviparty', 'css/bootstrap.min.css');
    CRM_Core_Resources::singleton()->addStyleUrl('http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css');
  //Manage Contacts
  $newIndLink = CRM_Utils_System::url('civicrm/contact/add', $query = 'reset=1&ct=Individual' );
  $browseContacts = CRM_Utils_System::url('civicrm/contact/search', $query = 'reset=1&force=1' );
  $manageGroupLink = CRM_Utils_System::url('civicrm/group', $query = 'reset=1' );
  $viewAllReports = CRM_Utils_System::url('civicrm/report/list', $query = 'reset=1' );

  //Manage Events
  $newEvent = CRM_Utils_System::url('civicrm/event/add', $query = 'reset=1&action=add' );
  $manageEvents = CRM_Utils_System::url('civicrm/event/manage', $query = 'reset=1' );
  $searchParticipants = CRM_Utils_System::url('civicrm/event/search', $query = 'reset=1' );
  $registerParticipant = CRM_Utils_System::url('civicrm/participant/add', $query = 'reset=1&action=add&context=standalone' );
  $scheduleReminder = CRM_Utils_System::url('civicrm/admin/scheduleReminders', $query = 'reset=1' );




  $contentPlacement =2;
  return array(
 //'<h2>Welcome</h2>' => "<p>Welcome to your CiviCRM Dashboard<p>",

                  '<h2>'.ts("Contacts",array('domain' => 'org.ndi.ndiciviparty')).'</h2>' =>
                    "<p>
                    <a href='".$newIndLink."'><button type=\"button\" class=\"btn btn-primary\">".ts('Create New Individual',array('domain' => 'org.ndi.ndiciviparty'))."</button></a>
                    <a href='".$browseContacts."'><button type=\"button\" class=\"btn btn-primary\">".ts('Browse Contacts',array('domain' => 'org.ndi.ndiciviparty'))."</button></a>
                   <a href='".$manageGroupLink."'><button type=\"button\" class=\"btn btn-primary\">".ts('Manage Groups',array('domain' => 'org.ndi.ndiciviparty'))."</button></a>
                    <a href='".$viewAllReports."'><button type=\"button\" class=\"btn btn-primary\">".ts('View All Reports',array('domain' => 'org.ndi.ndiciviparty'))."</button></a>
                    <a href='".$sendMailing."'><button type=\"button\" class=\"btn btn-primary\">".ts('Send Mailing',array('domain' => 'org.ndi.ndiciviparty'))."</button></a>
                    </p>
                  ",
                  '<h2>'.ts("Events",array('domain' => 'org.ndi.ndiciviparty')).'</h2>' =>
                    "<p>
                    <a href='".$newIndLink."'><button type=\"button\" class=\"btn btn-success\">".ts('Organize Event',array('domain' => 'org.ndi.ndiciviparty'))."</button></a>
                    <a href='".$manageEvents."'><button type=\"button\" class=\"btn btn-success\">".ts('All Events',array('domain' => 'org.ndi.ndiciviparty'))."</button></a>
                   <a href='".$searchParticipants."'><button type=\"button\" class=\"btn btn-success\">".ts('Search Participants',array('domain' => 'org.ndi.ndiciviparty'))."</button></a>
                    <a href='".$registerParticipant."'><button type=\"button\" class=\"btn btn-success\">".ts('Register Participant',array('domain' => 'org.ndi.ndiciviparty'))."</button></a>
                      <a href='".$scheduleReminder."'><button type=\"button\" class=\"btn btn-success\">".ts('Schedule Reminder',array('domain' => 'org.ndi.ndiciviparty'))."</button></a>
                  </p>
                  ",
    );
}

function disable_components(){
  $result = civicrm_api3('Setting', 'create', array(
  	'debug' => 1,
  	'sequential' => 1,
  	'enable_components' => array("CiviEvent","CiviMail","CiviReport","CiviCase"),
	));
	CRM_Core_DAO::executeQuery('UPDATE civicrm_navigation SET is_active = 0 WHERE id = 5');//Search -> Full-text Search
	CRM_Core_DAO::executeQuery('UPDATE civicrm_navigation SET is_active = 0 WHERE id = 6');//Search -> Search Builder
	CRM_Core_DAO::executeQuery('UPDATE civicrm_navigation SET is_active = 0 WHERE id = 9');//Search -> Find Mailings
	CRM_Core_DAO::executeQuery('UPDATE civicrm_navigation SET is_active = 0 WHERE id = 11');//Search -> Find Participants
	CRM_Core_DAO::executeQuery('UPDATE civicrm_navigation SET is_active = 0 WHERE id = 13');//Search -> Find Activites
	CRM_Core_DAO::executeQuery('UPDATE civicrm_navigation SET is_active = 0 WHERE id = 14');//Search -> custom searches
	CRM_Core_DAO::executeQuery('UPDATE civicrm_navigation SET is_active = 0 WHERE id = 17');//Contacts -> new Household
	CRM_Core_DAO::executeQuery('UPDATE civicrm_navigation SET is_active = 0 WHERE id = 60');//Event -> Personal Campaign Pages
	CRM_Core_DAO::executeQuery('UPDATE civicrm_navigation SET is_active = 0 WHERE id = 62');//Events -> new price set
	CRM_Core_DAO::executeQuery('UPDATE civicrm_navigation SET is_active = 0 WHERE id = 63');//Events -> manage price set
}

function enable_components(){
   $result = civicrm_api3('Setting', 'create', array(
  	'debug' => 1,
  	'sequential' => 1,
  	'enable_components' => array("CiviEvent","CiviMail","CiviReport", "CiviContribute", "CiviMember", "CiviPledge","CiviCase"),
	));
	CRM_Core_DAO::executeQuery('UPDATE civicrm_navigation SET is_active = 1 WHERE id = 5');//Search -> Full-text Search
	CRM_Core_DAO::executeQuery('UPDATE civicrm_navigation SET is_active = 1 WHERE id = 6');//Search -> Search Builder
	CRM_Core_DAO::executeQuery('UPDATE civicrm_navigation SET is_active = 1 WHERE id = 9');//Search -> Find Mailings
	CRM_Core_DAO::executeQuery('UPDATE civicrm_navigation SET is_active = 1 WHERE id = 11');//Search -> Find Participants
	CRM_Core_DAO::executeQuery('UPDATE civicrm_navigation SET is_active = 1 WHERE id = 13');//Search -> Find Activites
	CRM_Core_DAO::executeQuery('UPDATE civicrm_navigation SET is_active = 1 WHERE id = 14');//Search -> custom searches
	CRM_Core_DAO::executeQuery('UPDATE civicrm_navigation SET is_active = 1 WHERE id = 17');//Contacts -> new Household
	CRM_Core_DAO::executeQuery('UPDATE civicrm_navigation SET is_active = 1 WHERE id = 60');//Event -> Personal Campaign Pages
	CRM_Core_DAO::executeQuery('UPDATE civicrm_navigation SET is_active = 1 WHERE id = 62');//Events -> new price set
	CRM_Core_DAO::executeQuery('UPDATE civicrm_navigation SET is_active = 1 WHERE id = 63');//Events -> manage price set
}


/**
 * Implementation of hook_civicrm_config
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function ndiciviparty_civicrm_config(&$config) {
include_once 'CRM/Core/BAO/Setting.php';
$address_options =
            CRM_Core_BAO_Setting::getItem(
            'CiviCRM Preferences',
            'address_options',
            NULL,
            NULL,
            NULL,
            1
          );
/*$address_options =
            CRM_Core_BAO_Setting::setItem(
            '145789',
            'CiviCRM Preferences',
            'address_options',
            NULL,
            NULL,
            NULL,
            1
          );
*/
  _ndiciviparty_civix_civicrm_config($config);
}

/**
 * Implementation of hook_civicrm_xmlMenu
 *
 * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function ndiciviparty_civicrm_xmlMenu(&$files) {
  _ndiciviparty_civix_civicrm_xmlMenu($files);
}


/**
 * Implementation of hook_civicrm_enable
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function ndiciviparty_civicrm_enable() {
disable_components();
  return _ndiciviparty_civix_civicrm_enable();
}

/**
 * Implementation of hook_civicrm_disable
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function ndiciviparty_civicrm_disable() {
enable_components();
  return _ndiciviparty_civix_civicrm_disable();
}

/**
 * Implementation of hook_civicrm_upgrade
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed  based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function ndiciviparty_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _ndiciviparty_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implementation of hook_civicrm_managed
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
/**
 * Implementation of hook_civicrm_managed
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 */
function ndiciviparty_civicrm_managed(&$entities) {
  $entities[] = array(
    'module' => 'org.ndi.ndiciviparty',
    'name' => 'contactpermonth',
    'entity' => 'Dashboard',
    'params' => array(
      'version' => 3,
    "domain_id" => "1",
    "name" => "contact_per_month",
    "label" => "Recently Added",
    "url" => "civicrm/dashlets/contactpermonth?snippet=1",
    "column_no" => "0",
    "is_minimized" => "0",
    "is_fullscreen" => "0",
    "is_active" => "1",
    "is_reserved" => "1",
    "weight" => "0"
    ),
  );

  return _ndiciviparty_civix_civicrm_managed($entities);
}

/**
 * Implementation of hook_civicrm_caseTypes
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function ndiciviparty_civicrm_caseTypes(&$caseTypes) {
  _ndiciviparty_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implementation of hook_civicrm_alterSettingsFolders
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function ndiciviparty_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _ndiciviparty_civix_civicrm_alterSettingsFolders($metaDataFolders);
}
