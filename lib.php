<?php

use core_tag\reportbuilder\local\entities\instance;
use mod_extintmaxx\providers\provider_api_method_chains;
use mod_extintmaxx\providers\acci;

function mod_extintmaxx_get_fontawesome_icon_map() {
    return [
        'icon' => 'fa-solid fa-star',
    ];
}

function extintmaxx_add_instance($instancedata, $mform = null) {
    global $DB;
    $methodchains = new provider_api_method_chains();

    $selectedcourse = $DB->get_record('extintmaxx_provider', ['id' => $instancedata->providercourse], '*', MUST_EXIST);

    $adminrecord = $DB->get_record('extintmaxx_admin', ['id' => $selectedcourse->profile_id], '*', MUST_EXIST);

    if (!$adminrecord) {
        return false;
    }

    $instancedata->profile_id = $selectedcourse->profile_id;
    $instancedata->provider = $selectedcourse->provider;
    $instancedata->providercourseid = $selectedcourse->providercourseid;
    $instancedata->providercoursename = $selectedcourse->providercoursename;
    $instancedata->name = get_string($instancedata->provider, 'extintmaxx')." - ".$instancedata->providercoursename;
    $instancedata->timecreated = time();
    $instancedata->timemodified = time();
    $instancedata->introformat = FORMAT_HTML;

    $id = $DB->insert_record('extintmaxx', $instancedata);

    $instancedata->id = $id;
    extintmaxx_grade_item_update($instancedata);

    return $id;
}

function find_array_object_id_by_param_value ($array, $needle, $magnet) {
    foreach ($array as $haystack) {
        $isfound = object_search($needle, $magnet, $haystack);
        if ($isfound != false) {
            return $isfound;
        }
    }
    return false;
}

/**
 * @param int|string $needle The value of the property you are looking for
 * @param int|string $magnet The property you are looking for
 * @param object $haystack The object you want to parse through
 * @return int|string|bool returns the id of the object if needle is found
 */
function object_search($needle, $magnet, $haystack) {
    if ($haystack->$magnet == $needle) {
        return $haystack->id;
    } else {
        return false;
    }
}

function extintmaxx_update_instance($instancedata, $mform): bool {
    global $DB;

    $instancedata->timemodified = time();
    $instancedata->id = $instancedata->instance;
    $instancedata->introformat = $instancedata->introformat ?? $instancedata->introformat || FORMAT_HTML;

    extintmaxx_grade_item_update($instancedata);

    return $DB->update_record('extintmaxx', $instancedata);
}

function extintmaxx_delete_instance($id): bool {
    global $DB;

    return $DB->delete_records('extintmaxx', ['id' => $id]);
}

function extintmaxx_supports($feature) {
    switch ($feature) {
        case FEATURE_GRADE_HAS_GRADE: return true;
        case FEATURE_COMPLETION_TRACKS_VIEWS: return true;
        default: return null;
    }
}

function extintmaxx_grade_item_update($instance, $grades=NULL) {
    global $CFG;
    require_once($CFG->libdir . '/gradelib.php');

    if (property_exists($instance, 'cmidnumber')) { // May not be always present.
        $params = array('itemname' => $instance->name, 'idnumber' => $instance->cmidnumber);
    } else {
        $params = array('itemname' => $instance->name);
    }

    if ($instance->grade > 0) {
        $params['gradetype'] = GRADE_TYPE_VALUE;
        $params['grademax'] = $instance->grade;
        $params['grademin'] = 0;

    } else {
        $params['gradetype'] = GRADE_TYPE_NONE;
    }

    if (gettype($grades) == 'array') {
        foreach ($grades as $grade) {
            grade_update('mod/extintmaxx', $instance->course, 'mod', 'extintmaxx', $instance->id, 0, $grade->grade, $params);

            if ($grade->grade->rawgrade == $instance->grade) {
                $cm = get_coursemodule_from_instance('extintmaxx', $instance->id);
                if ($cm) {
                    $completion = new completion_info(get_course($instance->course));
                    if ($completion->is_enabled($cm)) {
                        $completion->update_state($cm, COMPLETION_COMPLETE, $grade->grade->userid);
                    }
                }
            }
        }
    } else {
        grade_update('mod/extintmaxx', $instance->course, 'mod', 'extintmaxx', $instance->id, 0, null, $params);
    }
}

function extintmaxx_update_grades($instance, $userid = 0, $nullifnone = true) {
    global $CFG, $DB;
    require_once($CFG->libdir . '/gradelib.php');

    if ($instance->grade == 0) {
        extintmaxx_grade_item_update($instance);

    } else if ($grades = extintmaxx_get_user_grades($instance, $userid)) {
        extintmaxx_grade_item_update($instance, $grades);

    } else if ($userid && $nullifnone) {
        $grade = new stdClass();
        $grade->userid = $userid;
        $grade->rawgrade = null;
        extintmaxx_grade_item_update($instance, $grade);

    } else {
        extintmaxx_grade_item_update($instance);
    }
}

function extintmaxx_get_user_grades($instance, $userid = 0) {
    global $DB;
    $acci = new acci();
    $methodchains = new provider_api_method_chains();
    $adminrecord = $methodchains->admin_record_exists($instance->provider, $instance->profile_id);
    if ($adminrecord->url != null || $adminrecord->url != '') {
        $url = $adminrecord->url;
    } else {
        $url = null;
    }
    $studentgrades = array();
    if ($userid != 0 && $userid != null) {
        $studentrecord = $methodchains->student_record_exists($userid, $instance->provider, $instance->providercourseid);
        $studentcoursedata = $methodchains->get_students_course_data($acci->admin_login($adminrecord->providerusername, $adminrecord->providerpassword, $url), $instance->provider, $instance->providercourseid, [$studentrecord['student']->provideruserid], $url);
        $studentcompletion = $studentcoursedata[0]->coursedata->data->studentcourses->percentage_completed;
        if ($studentcompletion > 99) {
            $studentgrades[$userid] = new stdClass;
            $studentgrades[$userid]->grade = new stdClass;
            $studentgrades[$userid]->grade->userid = $userid;
            $studentgrades[$userid]->grade->rawgrade = $instance->grade;
        } else {
            $studentgrades[$userid] = new stdClass;
            $studentgrades[$userid]->grade = new stdClass;
            $studentgrades[$userid]->grade->userid = $userid;
            $studentgrades[$userid]->grade->rawgrade = null;
        };
    } else {
        $students = $DB->get_records('extintmaxx_user', ['providercourseid' => $instance->providercourseid], true);
        $studentids = array();
        foreach ($students as $student) {
            array_push($studentids, $student->provideruserid);
        }
        $studentcoursedata = $methodchains->get_students_course_data($acci->admin_login($adminrecord->providerusername, $adminrecord->providerpassword, $url), $instance->provider, $instance->providercourseid, $studentids, $url);
        $studentcompletion = array();
        foreach ($studentcoursedata as $studentdata) {
            $currentstudentcourseobjectid = find_array_object_id_by_param_value($students, $studentdata->userid, 'provideruserid');
            $currentstudentid = $students[$currentstudentcourseobjectid]->userid;
            $studentcompletion[$currentstudentid] = $studentdata->coursedata->data->studentcourses->percentage_completed;
            if ($studentcompletion[$currentstudentid] > 99) {
                $studentgrades[$currentstudentid]->grade = new stdClass;
                $studentgrades[$currentstudentid]->grade->userid = $currentstudentid;
                $studentgrades[$currentstudentid]->grade->rawgrade = $instance->grade;
            } else {
                $studentgrades[$currentstudentid]->grade = new stdClass;
                $studentgrades[$currentstudentid]->grade->userid = $currentstudentid;
                $studentgrades[$currentstudentid]->grade->rawgrade = null;
            };
        }
    }
    return $studentgrades;
}