<?php

class Mandrill_Templates {
    public function __construct(Mandrill $master) {
        $this->master = $master;
    }

    /**
     * Add a new template
     * @param string $name the name for the new template - must be unique
     * @param string $code the HTML code for the template with mc:edit attributes for the editable elements
     * @param boolean $publish set to false to add a draft template without publishing
     * @return struct the information saved about the new template
     *     - slug string the immutable unique code name of the template
     *     - name string the name of the template
     *     - code string the full HTML code of the template, with mc:edit attributes marking the editable elements - draft version
     *     - publish_name string the same as the template name - kept as a separate field for backwards compatibility
     *     - publish_code string the full HTML code of the template, with mc:edit attributes marking the editable elements that are available as published, if it has been published
     *     - published_at string the date and time the template was last published as a UTC string in YYYY-MM-DD HH:MM:SS format, or null if it has not been published
     *     - created_at string the date and time the template was first created as a UTC string in YYYY-MM-DD HH:MM:SS format
     *     - updated_at string the date and time the template was last modified as a UTC string in YYYY-MM-DD HH:MM:SS format
     */
    public function add($name, $code, $publish=true) {
        $_params = array("name" => $name, "code" => $code, "publish" => $publish);
        return $this->master->call('templates/add', $_params);
    }

    /**
     * Get the information for an existing template
     * @param string $name the immutable name of an existing template
     * @return struct the requested template information
     *     - slug string the immutable unique code name of the template
     *     - name string the name of the template
     *     - code string the full HTML code of the template, with mc:edit attributes marking the editable elements - draft version
     *     - publish_name string the same as the template name - kept as a separate field for backwards compatibility
     *     - publish_code string the full HTML code of the template, with mc:edit attributes marking the editable elements that are available as published, if it has been published
     *     - published_at string the date and time the template was last published as a UTC string in YYYY-MM-DD HH:MM:SS format, or null if it has not been published
     *     - created_at string the date and time the template was first created as a UTC string in YYYY-MM-DD HH:MM:SS format
     *     - updated_at string the date and time the template was last modified as a UTC string in YYYY-MM-DD HH:MM:SS format
     */
    public function info($name) {
        $_params = array("name" => $name);
        return $this->master->call('templates/info', $_params);
    }

    /**
     * Update the code for an existing template
     * @param string $name the immutable name of an existing template
     * @param string $code the new code for the template
     * @param boolean $publish set to false to update the draft version of the template without publishing
     * @return struct the template that was updated
     *     - slug string the immutable unique code name of the template
     *     - name string the name of the template
     *     - code string the full HTML code of the template, with mc:edit attributes marking the editable elements - draft version
     *     - publish_name string the same as the template name - kept as a separate field for backwards compatibility
     *     - publish_code string the full HTML code of the template, with mc:edit attributes marking the editable elements that are available as published, if it has been published
     *     - published_at string the date and time the template was last published as a UTC string in YYYY-MM-DD HH:MM:SS format, or null if it has not been published
     *     - created_at string the date and time the template was first created as a UTC string in YYYY-MM-DD HH:MM:SS format
     *     - updated_at string the date and time the template was last modified as a UTC string in YYYY-MM-DD HH:MM:SS format
     */
    public function update($name, $code, $publish=true) {
        $_params = array("name" => $name, "code" => $code, "publish" => $publish);
        return $this->master->call('templates/update', $_params);
    }

    /**
     * Publish the content for the template. Any new messages sent using this template will start using the content that was previously in draft.
     * @param string $name the immutable name of an existing template
     * @return struct the template that was published
     *     - slug string the immutable unique code name of the template
     *     - name string the name of the template
     *     - code string the full HTML code of the template, with mc:edit attributes marking the editable elements - draft version
     *     - publish_name string the same as the template name - kept as a separate field for backwards compatibility
     *     - publish_code string the full HTML code of the template, with mc:edit attributes marking the editable elements that are available as published, if it has been published
     *     - published_at string the date and time the template was last published as a UTC string in YYYY-MM-DD HH:MM:SS format, or null if it has not been published
     *     - created_at string the date and time the template was first created as a UTC string in YYYY-MM-DD HH:MM:SS format
     *     - updated_at string the date and time the template was last modified as a UTC string in YYYY-MM-DD HH:MM:SS format
     */
    public function publish($name) {
        $_params = array("name" => $name);
        return $this->master->call('templates/publish', $_params);
    }

    /**
     * Delete a template
     * @param string $name the immutable name of an existing template
     * @return struct the template that was deleted
     *     - slug string the immutable unique code name of the template
     *     - name string the name of the template
     *     - code string the full HTML code of the template, with mc:edit attributes marking the editable elements - draft version
     *     - publish_name string the same as the template name - kept as a separate field for backwards compatibility
     *     - publish_code string the full HTML code of the template, with mc:edit attributes marking the editable elements that are available as published, if it has been published
     *     - published_at string the date and time the template was last published as a UTC string in YYYY-MM-DD HH:MM:SS format, or null if it has not been published
     *     - created_at string the date and time the template was first created as a UTC string in YYYY-MM-DD HH:MM:SS format
     *     - updated_at string the date and time the template was last modified as a UTC string in YYYY-MM-DD HH:MM:SS format
     */
    public function delete($name) {
        $_params = array("name" => $name);
        return $this->master->call('templates/delete', $_params);
    }

    /**
     * Return a list of all the templates available to this user
     * @return array an array of structs with information about each template
     *     - return[] struct the information on each template in the account
     *         - slug string the immutable unique code name of the template
     *         - name string the name of the template
     *         - code string the full HTML code of the template, with mc:edit attributes marking the editable elements - draft version
     *         - publish_name string the same as the template name - kept as a separate field for backwards compatibility
     *         - publish_code string the full HTML code of the template, with mc:edit attributes marking the editable elements that are available as published, if it has been published
     *         - published_at string the date and time the template was last published as a UTC string in YYYY-MM-DD HH:MM:SS format, or null if it has not been published
     *         - created_at string the date and time the template was first created as a UTC string in YYYY-MM-DD HH:MM:SS format
     *         - updated_at string the date and time the template was last modified as a UTC string in YYYY-MM-DD HH:MM:SS format
     */
    public function getList() {
        $_params = array();
        return $this->master->call('templates/list', $_params);
    }

    /**
     * Return the recent history (hourly stats for the last 30 days) for a template
     * @param string $name the name of an existing template
     * @return array the array of history information
     *     - return[] struct the stats for a single hour
     *         - time string the hour as a UTC date string in YYYY-MM-DD HH:MM:SS format
     *         - sent integer the number of emails that were sent during the hour
     *         - hard_bounces integer the number of emails that hard bounced during the hour
     *         - soft_bounces integer the number of emails that soft bounced during the hour
     *         - rejects integer the number of emails that were rejected during the hour
     *         - complaints integer the number of spam complaints received during the hour
     *         - opens integer the number of emails opened during the hour
     *         - unique_opens integer the number of unique opens generated by messages sent during the hour
     *         - clicks integer the number of tracked URLs clicked during the hour
     *         - unique_clicks integer the number of unique clicks generated by messages sent during the hour
     */
    public function timeSeries($name) {
        $_params = array("name" => $name);
        return $this->master->call('templates/time-series', $_params);
    }

    /**
     * Inject content and optionally merge fields into a template, returning the HTML that results
     * @param string $template_name the immutable name of a template that exists in the user's account
     * @param array $template_content an array of template content to render.  Each item in the array should be a struct with two keys - name: the name of the content block to set the content for, and content: the actual content to put into the block
     *     - template_content[] struct the injection of a single piece of content into a single editable region
     *         - name string the name of the mc:edit editable region to inject into
     *         - content string the content to inject
     * @param array $merge_vars optional merge variables to use for injecting merge field content.  If this is not provided, no merge fields will be replaced.
     *     - merge_vars[] struct a single merge variable
     *         - name string the merge variable's name. Merge variable names are case-insensitive and may not start with _
     *         - content string the merge variable's content
     * @return struct the result of rendering the given template with the content and merge field values injected
     *     - html string the rendered HTML as a string
     */
    public function render($template_name, $template_content, $merge_vars=null) {
        $_params = array("template_name" => $template_name, "template_content" => $template_content, "merge_vars" => $merge_vars);
        return $this->master->call('templates/render', $_params);
    }

}


