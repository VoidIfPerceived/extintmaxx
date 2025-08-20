<?php

require_once(__DIR__ . '/lib.php');
require_once(__DIR__ . '../../../config.php');

use mod_extintmaxx\providers\acci;

global $DB;
$acci = new acci();

$courseid = $_REQUEST['id'];
$instanceid = $_REQUEST['instance'];
$userid = $_REQUEST['userid'];

$acciuserid = $DB->get_record('extintmaxx_user', array('userid' => $userid, 'instanceid' => $instanceid), 'provideruserid', MUST_EXIST);
$provider = $DB->get_record('extintmaxx_admin', array('provider' => 'acci'), '*', MUST_EXIST);

$module = $DB->get_record('extintmaxx', array('id' => $instanceid), '*', MUST_EXIST);
$returnurl = new moodle_url('/course/view.php', array('id' => $courseid));

extintmaxx_update_grades($module, $userid);

$logout = $acci->student_logout($acciuserid->provideruserid, $acci->admin_login($provider->providerusername, $provider->providerpassword)->data->user->superadmin->consumer_key, $provider->url);
redirect($returnurl);