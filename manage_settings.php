<?php

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require('./manage_form.php');

use core_calendar\local\event\forms\update;
use mod_extintmaxx\providers\provider_api_method_chains;
use mod_extintmaxx\providers\acci;

admin_externalpage_setup('manageextintmaxx');
$actionurl = new moodle_url('/mod/extintmaxx/manage_settings.php');

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('extintmaxx_settings', 'extintmaxx'));

$methodchains = new provider_api_method_chains();
$acci = new acci();
$mform = new mod_extintmaxx_manage_form($actionurl);

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

if ($mform->is_cancelled()) {
    
} else if ($formdata = $mform->get_data()) {
    $providerusername = $formdata->providerusername;
    $providerpassword = $formdata->providerpassword;
    $provider = $formdata->provider;
    $url = rtrim($formdata->url);
    $name = $formdata->name;
    $apitoken = 0;
    // $acci->admin_login($providerusername, $providerpassword);
    // $apitoken = $acci->data->token;
    $formdata->apitoken = $apitoken;

    $providerexists = $DB->record_exists(
        'extintmaxx_admin',
        ['name' => $name]
    );

    if ($providerexists == true) {
        $record = $DB->get_record(
            'extintmaxx_admin',
            ['name' => $name]
        );
        update_provider_information($formdata, $record);

        $methodchains->update_provider_courses($record);

        $mform->display();
        $time = new DateTime("now", new DateTimeZone('UTC'));
        $time->format("F j, Y, g:i a T");
        echo "<br><h4>Provider Information Updated. ".$time->format("F j, Y, g:i:s a T")."</h4>";
    } else {
        $record = update_provider_information($formdata);

        $methodchains->update_provider_courses($record);

        $mform->display();
        $time = new DateTime("now", new DateTimeZone('UTC'));
        $time->format("F j, Y, g:i a T");
        echo "<br><h4>New Provider Information Received. ".$time->format("F j, Y, g:i:s a T")."</h4>";
    }
} else {
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
        $record = $records[array_key_first($records)];
        $name = $record->name;
        $providerusername = $record->providerusername;
        $providerpassword = $record->providerpassword;
        $url = $record->url;
    } else {
        $name = '';
        $providerusername = '';
        $providerpassword = '';
        $url = '';
    }
    $toform = array(
        'name' => $name,
        'provider' => $provider,
        'providerusername' => $providerusername,
        'providerpassword' => $providerpassword,
        'url' => $url,
    );

    $mform->set_data($toform);
    $mform->display();
}

echo $OUTPUT->footer();