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
 *
 * @package
 * @copyright Copyright (c)  Chartered College of Teaching. (http://www.charterd.college)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

function xmldb_sectioncomplete_upgrade($oldversion) {
    global $DB;
    $dbman = $DB->get_manager();

    if ($oldversion < 2020081003) {

        // Define field completebtnticked to be added to sectioncomplete.
        $table = new xmldb_table('sectioncomplete');
        $field = new xmldb_field('completebtnticked', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0', 'timemodified');

        // Conditionally launch add field completebtnticked.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Sectioncomplete savepoint reached.
        upgrade_mod_savepoint(true, 2020081003, 'sectioncomplete');
    }

    if ($oldversion < 2020081006) {

        // Define table sectioncomplete_users to be created.
        $table = new xmldb_table('sectioncomplete_users');

        // Adding fields to table sectioncomplete_users.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('sectionid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table sectioncomplete_users.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for sectioncomplete.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Sectioncomplete savepoint reached.
        upgrade_mod_savepoint(true, 2020081006, 'sectioncomplete');
    }
    return true;
}