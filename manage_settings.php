<?php

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require('./manage_form.php');

use core_calendar\local\event\forms\update;
use mod_extintmaxx\providers\provider_api_method_chains;
use mod_extintmaxx\providers\acci;

$profile = optional_param('profile', null, PARAM_INT);
$provider = optional_param('provider', 'acci', PARAM_TEXT);

// Temporary debug: fetch the script file that RequireJS should load for
// mod_extintmaxx/formupdate and print its contents to the console. Remove
// this block once debugging is complete.

admin_externalpage_setup('manageextintmaxx');
$actionurl = new moodle_url('/mod/extintmaxx/manage_settings.php');

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('extintmaxx_settings', 'extintmaxx'));

$methodchains = new provider_api_method_chains();
$acci = new acci();

function get_matching_provider_records($formdata) {
    global $DB;
    $records = $DB->get_records(
        'extintmaxx_admin',
        [
            'provider' => $formdata->provider,
            'providerusername' => $formdata->providerusername,
            'providerpassword' => $formdata->providerpassword,
            'url' => $formdata->url
        ]
    );
    $length = count($records);
    if ($length > 1) {
        foreach ($records as $record) {
            if ($record->name != $formdata->name) {
                $DB->delete_records(
                'extintmaxx_admin',
                [
                    'id' => $record->id
                ]
                );
            }
        }
    }
}

function update_provider_information($formdata, $record = null) {
    global $DB;
    if ($record) {
        get_matching_provider_records($formdata);
        $id = $record->id;
        $formdata->id = $id;
        $formdata->timemodified = time();
        $existscheck = $DB->record_exists(
            'extintmaxx_admin',
            ['id' => $id]
        );
        if ($existscheck == true) {
            $id = $DB->update_record(
                'extintmaxx_admin',
                $formdata
            );
            return $id;
        } else {
            $id = $DB->insert_record(
                'extintmaxx_admin',
                $formdata
            );
            return $id;
        }
    } else if (!$record) {
        $formdata->timecreated = time();
        $formdata->timemodified = time();
        $id = $DB->insert_record(
            'extintmaxx_admin',
            $formdata
        );
        return $id;
    }
}

function to_form_data($profile = null) {
    global $DB;
    $provider = 'acci';

    $providerexists = $DB->record_exists(
        'extintmaxx_admin',
        ['provider' => $provider]
    );

    if ($providerexists == true) {
        $records = $DB->get_records(
            'extintmaxx_admin',
            ['provider' => $provider]
        );

        // If no profile parameter provided, pick the first record's id. We
        // store and return the record id so it matches the select option keys.
        if ($profile === null) {
            $firstkey = array_key_first($records);
            $record = $records[$firstkey];
        } else {
            // $profile is expected to be the record id (key)
            $record = $records[$profile];
        }

        // Return the id for the select element value.
        $profileid = $record->id;
        $name = $record->name;
        $providerusername = $record->providerusername;
        $providerpassword = $record->providerpassword;
        $url = $record->url;

    } else {

        $profile = '';
        $name = '';
        $providerusername = '';
        $providerpassword = '';
        $url = '';

    };

    return array(
        'profile' => $profileid ?? '',
        'name' => $name,
        'provider' => $provider,
        'providerusername' => $providerusername,
        'providerpassword' => $providerpassword,
        'url' => $url,
    );
}

function process_form($formdata) {
    global $DB;
    $methodchains = new provider_api_method_chains();

    $providerexists = $DB->record_exists(
        'extintmaxx_admin',
        ['name' => $formdata->name]
    );

    if ($providerexists == true) {
        $record = $DB->get_record(
            'extintmaxx_admin',
            ['name' => $formdata->name]
        );
        update_provider_information($formdata, $record);

        $methodchains->update_provider_courses($record);

        echo "<br><h4>Provider Information Updated. ".timestamp_formdata()."</h4>";
    } else {
        $record = update_provider_information($formdata);

        $methodchains->update_provider_courses($record);

        echo "<br><h4>New Provider Information Received. ".timestamp_formdata()."</h4>";
    }
}

$profiledata = array(
    'profile' => $profile,
    'provider' => $provider
);

$toform = to_form_data($profile);

// Ensure the form receives the profile id (not the profile name) as
// customdata so setSelected() can match the select keys.
$profiledata['profile'] = $toform['profile'];

$PAGE->requires->js('/mod/extintmaxx/lib/amd/src/formupdate.js');
$mform = new mod_extintmaxx_manage_form($actionurl, $profiledata);

function timestamp_formdata() {
    $time = new DateTime("now", new DateTimeZone('UTC'));
    return $time->format("F j, Y, g:i:s a T");
}

if ($mform->is_cancelled()) {
    
} else if ($formdata = $mform->get_data()) {
    process_form($formdata);

    $mform->display();
} else {
    $mform->set_data($toform);
    $mform->display();
}



echo $OUTPUT->footer();