<?php

use core\analytics\analyser\student_enrolments;
use core\session\exception;
use core_reportbuilder\external\columns\sort\get;
use mod_extintmaxx\providers\acci;
use mod_extintmaxx\providers\provider_api_method_chains;
use mod_extintmaxx\task\acci_grade_check;

require_once(__DIR__ . '/../../config.php');
//Instance View Page

/**
 * Variable Declaration:
 * $acci = instanciate acci class from acci.php
 * $cmid = id of course module (**************Predifined?)
 * $cm = get course module from id
 */

$acci = new acci();
$methodchains = new provider_api_method_chains();

global $USER, $DB;
$cmid = required_param('id', PARAM_INT);
$cm = get_coursemodule_from_id('extintmaxx', $cmid, 0, false, MUST_EXIST);
$course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
$module = $DB->get_record('extintmaxx', array('id' => $cm->instance), '*', MUST_EXIST);
$profile = $DB->get_record('extintmaxx_admin', array('id' => $module->profile_id), '*');
$providercourse = $methodchains->provider_record_exists($profile->provider, $module->providercourseid);

$PAGE->set_context(context_system::instance());

function admin_actions($courseid, $provider, $profileid) {
    $reportingurl = "/mod/extintmaxx/reporting.php?courseid=$courseid&provider=$provider&profileid=$profileid";
    echo "<a href='$reportingurl'>View Reporting</a>";
}

function view_reporting() {

}

function return_to_course_url() {

}

function acci_course_url($providerstudent, $providercourse, $providerrecord) {
    $methodchains = new provider_api_method_chains();
    $acci = new acci();
    $adminlogin = $acci->admin_login($providerrecord->providerusername, $providerrecord->providerpassword, $providerrecord->url);
    $studentcoursedata = $methodchains->get_students_course_data($adminlogin, $providerrecord->provider, $providercourse->providercourseid, [$providerstudent->provideruserid], $providerrecord->url);
    $studentcompletion = $studentcoursedata[0]->coursedata->data->studentcourses->percentage_completed;
    if ($providerrecord->url == NULL) {
        $url = 'https://www.lifeskillslink.com';
    } else {
        $url = $providerrecord->url;
    }
    if ($studentcompletion > 0) {
        $studentcourses = $studentcoursedata[0]->coursedata->data->studentcourses;
        $currentframeid = $studentcourses->frame_id;
        $nextframeid = $studentcourses->next_frame_id;
        $previousframeid = $studentcourses->previous_frame_id;
        $courseforwardurl = "$url/studentcourse?id=$providercourse->providercourseid&fid=$currentframeid&next_frame_id=$nextframeid&previous_frame_id=$previousframeid";
    } else {
        $courseforwardurl = "$url/studentcourse?id=$providercourse->providercourseid&student_id=$providerstudent->provideruserid";
    }

    return $courseforwardurl;
}

function get_redirect_url($providerstudent) {
    if (isguestuser() == true) {
        $redirecturl = 'invalidlogin';
    } else {
        $redirecturl = $providerstudent->redirecturl;
    }
    return $redirecturl;
}

function update_completion_data($provider) {
    if ($provider == 'acci') {
        $accigradecheck = new acci_grade_check();
        echo "acci gradecheck through adhoc:";
        \core\task\manager::queue_adhoc_task($accigradecheck, true);
        echo "acci gradecheck through direct execute:";
        $accigradecheck->execute();
    }
}

function generate_iframe($redirecturl, $courseforwardurl) {
    if ($redirecturl == 'invalidlogin') {
        $viewurl = "<h2>Invalid Login, Please Log In.</h2>";
        return $viewurl;
    } else {
        echo $viewurl = 
        "<div style=\"
            position: relative; 
            overflow:hidden;
            position:relative;
            top:0px;
            width:100%;
            height:740px;\"
        >
            <iframe id=\"viewurl\" 
                style=\"
                position:absolute;
                top:-60px;
                height:740px;
                width:100%;
                left:0;
                scrolling:no;\"
                allow:autoplay;
                src=\"$redirecturl\">
            </iframe>
        </div>
        ";
    }
}

function iframe_course_redirect($courseforwardurl) {
    return "<script>
            iframe = document.getElementById('viewurl');
            addEventListener('load', function() {
                iframe.src = '$courseforwardurl';
            });
            </script>";
}

function view_page($redirecturl, $courseforwardurl) {
    $iframe = generate_iframe($redirecturl, $courseforwardurl);
    return $iframe;
}

function exit_activity_button($courseid, $instanceid) {
    global $USER;
    $returnurl = new moodle_url("/mod/extintmaxx/process_grade_update.php", array('id' => $courseid, 'instance' => $instanceid, 'userid' => $USER->id));
    return "<a href=\"$returnurl\" style=\"
    position:relative
    z-index:1
    \"
    class=\"btn btn-primary btn-md\">
    Exit Activity</a>";
}

$PAGE->set_url('/mod/extintmaxx/view.php', array('id' => $cm->id));
$PAGE->set_title('External Integration for Maxx Content');

echo $OUTPUT->header();

if (has_capability('mod/extintmaxx:basicreporting', $context = context_course::instance($cm->course))) {
    $PAGE->set_context($context);
    $PAGE->set_pagelayout('standard');
    admin_actions($course->id, $module->provider, $module->profile_id);
} else {
    $adminlogin = $acci->admin_login($profile->providerusername, $profile->providerpassword, $profile->url);
    $acciuserid = $DB->get_record(
        'extintmaxx_user',
        array('userid' => $USER->id, 'provider' => $profile->provider, 'instanceid' => $module->id)
    );
    // $logout = $acci->student_logout($acciuserid->provideruserid, $adminlogin->data->user->superadmin->consumer_key, $profile->url);
    // print_r($logout);
    $providerstudent = $methodchains->student_login($USER->id, $profile->provider, $module, $module->id, $profile->url);
    $redirecturl = get_redirect_url($providerstudent);
    $courseforwardurl = acci_course_url($providerstudent, $module, $profile);
    $PAGE->set_context(context_system::instance());
    $PAGE->set_pagelayout('incourse');
    echo exit_activity_button($cm->course, $module->id);
    echo view_page($redirecturl, $courseforwardurl);
    echo iframe_course_redirect($courseforwardurl);
}

echo $OUTPUT->footer();