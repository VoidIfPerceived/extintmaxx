<?php
function xmldb_extintmaxx_upgrade($oldversion): bool {
    global $CFG, $DB;

    $dbman = $DB->get_manager(); // Loads ddl manager and xmldb classes.

    if ($oldversion < 2025032900) {

    // Define field providercourseguid to be added to extintmaxx.
    $table = new xmldb_table('extintmaxx');
    $field = new xmldb_field('providercourseguid', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null, 'provider');

    // Conditionally launch add field providercourseguid.
    if (!$dbman->field_exists($table, $field)) {
        $dbman->add_field($table, $field);
    }

    // Extintmaxx savepoint reached.
    upgrade_mod_savepoint(true, 2025032900, 'extintmaxx');
    }

    // Define DB changes before this line.

    if ($oldversion < 2025032901) {

        // Define table extintmaxx_provider to be created.
        $table = new xmldb_table('extintmaxx_provider');

        // Adding fields to table extintmaxx_provider.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('provider', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('providercourseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('courseguid', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('providercoursename', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('providercoursedesc', XMLDB_TYPE_TEXT, null, null, null, null, null);

        // Adding keys to table extintmaxx_provider.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for extintmaxx_provider.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Extintmaxx savepoint reached.
        upgrade_mod_savepoint(true, 2025032901, 'extintmaxx');
    }

    if ($oldversion < 2025032902) {

        // Define field remembertoken to be added to extintmaxx_admin.
        $table = new xmldb_table('extintmaxx_admin');
        $field = new xmldb_field('remembertoken', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null, 'apitoken');
    
        // Conditionally launch add field remembertoken.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
    
        // Extintmaxx savepoint reached.
        upgrade_mod_savepoint(true, 2025032902, 'extintmaxx');
        }

    if ($oldversion < 2025040400) {

        // Define table extintmaxx to be created.
        $table = new xmldb_table('extintmaxx');

        // Adding fields to table extintmaxx.
        $table->add_field('referraltypeid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);


        // Conditionally launch create table for extintmaxx.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Extintmaxx savepoint reached.
        upgrade_mod_savepoint(true, 2025040400, 'extintmaxx');
    }

    if ($oldversion < 2025040400) {

        // Define table extintmaxx to be created.
        $table = new xmldb_table('extintmaxx_provider');

        // Adding fields to table extintmaxx_provider.
        $table->add_field('referraltypeid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null, 'id');


        // Conditionally launch create table for extintmaxx_provider.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Extintmaxx savepoint reached.
        upgrade_mod_savepoint(true, 2025040400, 'extintmaxx');
    }

    if ($oldversion < 2025072302) {

        // Define field url to be added to extintmaxx_admin.
        $table = new xmldb_table('extintmaxx_admin');
        $field = new xmldb_field('url', XMLDB_TYPE_CHAR, '256', null, null, null, null, 'timemodified');

        // Conditionally launch add field url.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
    }

    if ($oldversion < 2025081200) {

        // Define field url to be added to extintmaxx_admin.
        $table = new xmldb_table('extintmaxx_admin');
        $field = new xmldb_field('name', XMLDB_TYPE_CHAR, '256', null, null, null, null, 'url');

        // Conditionally launch add field url.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
    }

    if ($oldversion < 2025081800 ) {

        // Define field url to be added to extintmaxx_admin.
        $table = new xmldb_table('extintmaxx_provider');
        $field = new xmldb_field('profile_id', XMLDB_TYPE_CHAR, '256', null, null, null, null, 'providercoursedesc');

        // Conditionally launch add field url.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
    }

    return true;
};