<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Languages configuration for the mod_extintmaxx plugin.
 *
 * @package   mod_extintmaxx
 * @copyright 2025, Sophi Dickens <sophidickens.e@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Basic Strings:
    $string['pluginname'] = 'Maxx External Integration';
    $string['modulename'] = 'Maxx External Integration';
    $string['pluginspecificheader'] = 'Maxx External Integration Settings';
    $string['manage'] = 'Manage';
    $string['extintmaxx_settings'] = 'Maxx External Integration Settings';
    $string['modulenameplural'] = 'Maxx External Integration';
    $string['pluginadministration'] = 'Maxx External Integration Instance Settings';

// Form Strings:
/** Admin Forms */
    $string['apikey'] = 'API Key';
    $string['apikey_help'] = 'Enter the API Token for the External Provider you would like to integrate with.';
    $string['profile'] = 'Profiles';
    $string['profile_help'] = 'Select a previously saved profile';
    $string['name'] = 'Profile Name';
    $string['name_help'] = 'Enter the name that this set of credentials will be referred to as.';
    $string['providersselection'] = 'Providers';
    $string['providersselection_help'] = 'Select the Provider you would like to integrate with.';
    $string['providercourse'] = 'Provider Course';
    $string['providercourse_help'] = 'Select the Provider Course you would like to use for this instance.';
    $string['environmenturl'] = 'Environment URL (Optional)';
    $string['environmenturl_help'] = 'Enter the URL of the environment you would like to use. (Optional, leave blank for production)';
    $string['grading'] = 'Grading';
    $string['grade'] = 'Maximum Activity Grade';
    $string['grade_help'] = 'Enter the maximum grade for this instance.';
    $string['providerusername'] = 'Provider Username';
    $string['providerusername_help'] = 'Enter the Username for the selected provider you would like to integrate with.';
    $string['providerpassword'] = 'Provider Password';
    $string['providerpassword_help'] = 'Enter the Password for the selected provider you would like to integrate with.';
    $string['insertprovidercredentials'] = 'Insert Provider Credentials';
    $string['providercourse'] = 'Courses';
/** Student Forms */
    $string['studentusername'] = 'Username';
    $string['studentusername_help'] = 'Enter your username.';
    $string['studentpassword'] = 'Password';
    $string['studentpassword_help'] = 'Enter your password.';
    $string['studentemail'] = 'Email';
    $string['studentemail_help'] = 'Enter your email.';
    $string['studentfirstname'] = 'First Name';
    $string['studentfirstname_help'] = 'Enter your first name.';
    $string['studentlastname'] = 'Last Name';
    $string['studentlastname_help'] = 'Enter your last name.';
    $string['studentpasswordconfirmation'] = 'Confirm your Password';
    $string['studentpasswordconfirmation_help'] = 'Please re-enter your Password.';
    $string['studentcasenumber'] = 'Case Number';
    $string['studentcasenumber_help'] = 'Enter your case number.';
    $string['newuserenroll'] = 'Enroll';

// Providers:
    $string['nali'] = 'North American Learning Institute';
    $string['acci'] = 'ACCI Lifeskills';

// Event Strings:
/** Admin Login Method Called Event */
    $string['eventadminlogincalled'] = 'Admin Login Method Called.';
    $string['eventadminlogincalleddesc'] = 'The admin login method was called by userid: "{$userid}".';
    $string['eventadminlogincalledmessage'] = '{$statusmessage}';
/** Get Referral Types By Admin Method Called Event */
    $string['eventgetreferraltypesbyadmincalled'] = 'Get Referral Types by Admin Called';
    $string['eventgetreferraltypesbyadmincalleddesc'] = 'The get referral types by admin method was called by userid: "{$userid}".';
    $string['eventgetreferraltypesbyadmincalledmessage'] = '{$statusmessage}';
/** Get All Courses Method Called Event */
    $string['eventgetallcoursescalled'] = 'Get All Courses Called';
    $string['eventgetallcoursescalleddesc'] = 'The get all courses method was called by userid: "${userid}".';
    $string['eventgetallcoursescalledmessage'] = '{$statusmessage}';
/** Get Students By Admin Method Called Event */
    $string['eventgetstudentsbyadmincalled'] = 'Get Students by Admin Called';
    $string['eventgetstudentsbyadmincalleddesc'] = 'The get students by admin method was called by userid: "${userid}".';
    $string['eventgetstudentsbyadmincalledmessage'] = '{$statusmessage}';
/** Student Self Enrolled Method Called Event */
    $string['eventstudentselfenrolledcalled'] = 'Student Self Enrolled Called';
    $string['eventstudentselfenrolledcalleddesc'] = 'The student self enrolled method was called by userid: "${userid}".';
    $string['eventstudentselfenrolledcalledmessage'] = '{$statusmessage}';
/** Student Auth Method Called Event */
    $string['eventstudentauthcalled'] = 'Student Auth Called';
    $string['eventstudentauthcalleddesc'] = 'The student auth method was called by userid: "${userid}".';
    $string['eventstudentauthcalledmessage'] = '{$statusmessage}';
/** Admin Form Submitted Event */
    $string['eventadminformsubmitted'] = 'Admin form submitted';
    $string['eventadminformsubmitteddesc'] = 'The admin form was submitted by userid: "${userid}".';
/** Module Form Submitted Event */
    $string['eventmoduleformsubmitted'] = 'Module form submitted';
    $string['eventmoduleformsubmitteddesc'] = 'The module form was submitted by userid: "${userid}".';

// Task Strings
/** Regular ACCI Check Task */
    $string['timed_grade_check'] = 'Scheduled ACCI Completion Check';

// Error Strings
    $string['invalidcredentials'] = 'Provided user credentials are invalid, please check username and password.';
    $string['required'] = 'Missing Required Fields';

// Reporting Fields
    $string['firstname'] = 'First Name';
    $string['lastname'] = 'Last Name';
    $string['email'] = 'Email';
    $string['studentcourses:student_id'] = 'Student ID';
    $string['studentcourses:course_id'] = 'Course ID';
    $string['studentcourses:percentage_completed'] = 'Percentage Completed';
    $string['studentcourses:course:title'] = 'Course Name';
    $string['studentcourses:total_timetaken'] = 'Time Spent';
    $string['reporting'] = 'Reporting';