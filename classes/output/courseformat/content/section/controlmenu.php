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

use core\exception\moodle_exception;
use core\output\action_menu\link;
use core\output\action_menu\link_secondary;
use core\output\pix_icon;
use core\url;
use core_courseformat\base as course_format;
use format_topics\output\courseformat\content\section\controlmenu as controlmenu_format_topics;
use section_info;


class controlmenu extends controlmenu_format_topics
{

    /**
     * construct
     *
     * @param course_format $format
     * @param section_info $section
     */
    public function __construct(course_format $format, section_info $section)
    {
        parent::__construct($format, $section);
        $this->baseurl = new \moodle_url("/course/view.php");
    }

    /**
     * Export template name
     *
     * @param \renderer_base $renderer
     * @return string
     */
    public function get_template_name(\renderer_base $renderer): string
    {
        return 'format_buttons/local/content/section/controlmenu';
    }

    /**
     * Items to the control
     *
     * @return array
     * @throws \coding_exception
     * @throws moodle_exception
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

    /**
     * Return move sections item
     *
     * @return link|null
     * @throws \coding_exception
     * @throws moodle_exception
     */
    protected function get_section_movesection_item(): ?link
    {
        if (
            $this->section->sectionnum == 0
            || !has_capability('moodle/course:movesections', $this->coursecontext)
        ) {
            return null;
        }

        $url = new url(
            $this->baseurl,
            [
                'movesection' => $this->section->sectionnum,
                'section' => $this->section->sectionnum,
            ]
        );

        return new link_secondary(
            url: $url,
            icon: new pix_icon('i/dragdrop', ''),
            text: get_string('move'),
            attributes: [
                // This tool requires ajax and will appear only when the frontend state is ready.
                'class' => 'move waitstate',
                'data-action' => 'moveSection',
                'data-id' => $this->section->id,
            ],
        );
    }

    /**
     * Duplicate sections
     *
     * @return link|null
     * @throws \coding_exception
     * @throws \core\exception\moodle_exception
     */
    protected function get_section_duplicate_item(): ?link
    {
        if (
            $this->section->sectionnum == 0
            || !has_capability('moodle/course:update', $this->coursecontext)
        ) {
            return null;
        }

        $url = new url(
            $this->baseurl,
            [
                'id' => $this->course->id,
                'sectionid' => $this->section->id,
                'duplicatesection' => 1,
                'sesskey' => sesskey(),
            ]
        );

        return new link_secondary(
            url: $url,
            icon: new pix_icon('t/copy', ''),
            text: get_string('duplicate'),
            attributes: ['class' => 'duplicate'],
        );
    }


}