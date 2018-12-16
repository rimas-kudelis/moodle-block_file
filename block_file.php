<?php
defined('MOODLE_INTERNAL') || die();

class block_file extends block_base
{

    public function init()
    {
        $this->title = get_string('file', 'block_file');
    }

    public function applicable_formats()
    {
        return array('all' => true);
    }

    public function instance_allow_multiple()
    {
        return true;
    }

    public function specialization()
    {
        if (isset($this->config->title) && $this->config->title !== '') {
            $this->title = format_string($this->config->title, true, ['context' => $this->context]);
        }
    }

    public function instance_config_save($data, $nolongerused = false)
    {
        $data->file = file_save_draft_area_files($data->select_file, $this->context->id, 'block_file', 'file', 0, array('subdirs' => false, 'maxfiles' => 1), '@@PLUGINFILE@@/');

        return parent::instance_config_save($data, $nolongerused);
    }

    public function get_content()
    {
        if ($this->content !== null) {
            return $this->content;
        }
        $this->content = new stdClass;

        $height = isset($this->config->height) && $this->config->height !== '' ? $this->config->height : null;

        $fs = get_file_storage();
        $files = $fs->get_area_files($this->context->id, 'block_file', 'file', 0);

        foreach ($files as $file) {
            if ($file->is_directory()) {
                continue;
            }

            $mimeType = $file->get_mimetype();

            if ($mimeType === 'application/pdf') {
                $content = $this->get_content_text_pdf($file, $height);
                break;
            }

            if (substr($mimeType, 0, 5) === 'video') {
                $content = $this->get_content_text_video($file, $height);
                break;
            }
        }

        $this->content->text = (! empty($content)) ? $content : 'No file has been selected for display!';
        return $this->content;
    }

    protected function get_content_text_pdf($file, $height = null)
    {
        $attributes = [
            'width' => '100%',
            'src' => $this->get_file_url($file),
        ];
        if ($height !== null) {
            $attributes['style'] = 'min-height:' . $height;
        }
        return html_writer::tag('iframe', '', $attributes);
    }

    protected function get_content_text_video($file, $height = null)
    {
        $attributes = [
            'width' => '100%',
            'controls' => '',
            'src' => $this->get_file_url($file),
        ];

        return html_writer::tag('video', '', $attributes);
    }

    protected function get_file_url($file)
    {
        return moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), ($file->get_itemid() !== '0' ? $file->get_itemid() : null), $file->get_filepath(), $file->get_filename());
    }
}
