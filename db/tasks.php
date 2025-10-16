<?php

defined('MOODLE_INTERNAL') || die();

$tasks = [
    [
        'classname' => 'mod_extintmaxx\task\timed_grade_check',
        'blocking' => 0,
        'minute' => '*/30',
        'hour' => '*',
        'day' => '*',
        'dayofweek' => '*',
        'month' => '*'
    ]
];