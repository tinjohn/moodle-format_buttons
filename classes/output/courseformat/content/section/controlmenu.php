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
 * Code to be executed after the plugin's database scheme has been installed is defined here.
 *
 * @package     format_buttons
 * @category    upgrade
 * @copyright   2023 Jhon Rangel <jrangelardila@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace format_buttons\classes\output\courseformat\content\section;

defined('MOODLE_INTERNAL') || die();

use format_topics\output\courseformat\content\section\controlmenu as controlmenu_format_topics;


class controlmenu extends controlmenu_format_topics
{
    /**
     * Get mustache name
     *
     * @param \renderer_base $renderer
     * @return string
     */
    public function get_template_name(\renderer_base $renderer): string
    {
        return 'format_buttons/local/content/section/controlmenu';
    }

    /**
     * Items to the controlmenu
     *
     * @return array
     */
    public function section_control_items()
    {
        $controls = [];

        $controls['view'] = $this->get_section_view_item();

        if (!$this->section->is_orphan()) {
            $controls['edit'] = $this->get_section_edit_item();
            $controls['duplicate'] = $this->get_section_duplicate_item();
            $controls['visibility'] = $this->get_section_visibility_item();
            $controls['movesection'] = $this->get_section_movesection_item();
            $controls['permalink'] = $this->get_section_permalink_item();
        }

        $controls['delete'] = $this->get_section_delete_item();

        return $controls;
    }
}