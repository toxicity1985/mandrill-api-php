<?php

class Mandrill_Messages {
    public function __construct(Mandrill $master) {
        $this->master = $master;
    }

    /**
     * Send a new transactional message through Mandrill
     * @param struct $message the information on the message to send
     *     - html string the full HTML content to be sent
     *     - text string optional full text content to be sent
     *     - subject string the message subject
     *     - from_email string the sender email address.
     *     - from_name string optional from name to be used
     *     - to array an array of recipient information.
     *         - to[] struct a single recipient's information.
     *             - email string the email address of the recipient
     *             - name string the optional display name to use for the recipient
     *     - headers struct optional extra headers to add to the message (currently only Reply-To and X-* headers are allowed)
     *     - important boolean whether or not this message is important, and should be delivered ahead of non-important messages
     *     - track_opens boolean whether or not to turn on open tracking for the message
     *     - track_clicks boolean whether or not to turn on click tracking for the message
     *     - auto_text boolean whether or not to automatically generate a text part for messages that are not given text
     *     - auto_html boolean whether or not to automatically generate an HTML part for messages that are not given HTML
     *     - inline_css boolean whether or not to automatically inline all CSS styles provided in the message HTML - only for HTML documents less than 256KB in size
     *     - url_strip_qs boolean whether or not to strip the query string from URLs when aggregating tracked URL data
     *     - preserve_recipients boolean whether or not to expose all recipients in to "To" header for each email
     *     - bcc_address string an optional address to receive an exact copy of each recipient's email
     *     - tracking_domain string a custom domain to use for tracking opens and clicks instead of mandrillapp.com
     *     - signing_domain string a custom domain to use for SPF/DKIM signing instead of mandrill (for "via" or "on behalf of" in email clients)
     *     - merge boolean whether to evaluate merge tags in the message. Will automatically be set to true if either merge_vars or global_merge_vars are provided.
     *     - global_merge_vars array global merge variables to use for all recipients. You can override these per recipient.
     *         - global_merge_vars[] struct a single global merge variable
     *             - name string the global merge variable's name. Merge variable names are case-insensitive and may not start with _
     *             - content string the global merge variable's content
     *     - merge_vars array per-recipient merge variables, which override global merge variables with the same name.
     *         - merge_vars[] struct per-recipient merge variables
     *             - rcpt string the email address of the recipient that the merge variables should apply to
     *             - vars array the recipient's merge variables
     *                 - vars[] struct a single merge variable
     *                     - name string the merge variable's name. Merge variable names are case-insensitive and may not start with _
     *                     - content string the merge variable's content
     *     - tags array an array of string to tag the message with.  Stats are accumulated using tags, though we only store the first 100 we see, so this should not be unique or change frequently.  Tags should be 50 characters or less.  Any tags starting with an underscore are reserved for internal use and will cause errors.
     *         - tags[] string a single tag - must not start with an underscore
     *     - google_analytics_domains array an array of strings indicating for which any matching URLs will automatically have Google Analytics parameters appended to their query string automatically.
     *     - google_analytics_campaign array|string optional string indicating the value to set for the utm_campaign tracking parameter. If this isn't provided the email's from address will be used instead.
     *     - metadata array metadata an associative array of user metadata. Mandrill will store this metadata and make it available for retrieval. In addition, you can select up to 10 metadata fields to index and make searchable using the Mandrill search api.
     *     - recipient_metadata array Per-recipient metadata that will override the global values specified in the metadata parameter.
     *         - recipient_metadata[] struct metadata for a single recipient
     *             - rcpt string the email address of the recipient that the metadata is associated with
     *             - values array an associated array containing the recipient's unique metadata. If a key exists in both the per-recipient metadata and the global metadata, the per-recipient metadata will be used.
     *     - attachments array an array of supported attachments to add to the message
     *         - attachments[] struct a single supported attachment
     *             - type string the MIME type of the attachment
     *             - name string the file name of the attachment
     *             - content string the content of the attachment as a base64-encoded string
     *     - images array an array of embedded images to add to the message
     *         - images[] struct a single embedded image
     *             - type string the MIME type of the image - must start with "image/"
     *             - name string the Content ID of the image - use <img src="cid:THIS_VALUE"> to reference the image in your HTML content
     *             - content string the content of the image as a base64-encoded string
     * @param boolean $async enable a background sending mode that is optimized for bulk sending. In async mode, messages/send will immediately return a status of "queued" for every recipient. To handle rejections when sending in async mode, set up a webhook for the 'reject' event. Defaults to false for messages with no more than 10 recipients; messages with more than 10 recipients are always sent asynchronously, regardless of the value of async.
     * @return array of structs for each recipient containing the key "email" with the email address and "status" as either "sent", "queued", or "rejected"
     *     - return[] struct the sending results for a single recipient
     *         - email string the email address of the recipient
     *         - status string the sending status of the recipient - either "sent", "queued", "rejected", or "invalid"
     *         - _id string the message's unique id
     */
    public function send($message, $async=false) {
        $_params = array("message" => $message, "async" => $async);
        return $this->master->call('messages/send', $_params);
    }

    /**
     * Send a new transactional message through Mandrill using a template
     * @param string $template_name the name of a template that exists in the user's account
     * @param array $template_content an array of template content to send.  Each item in the array should be a struct with two keys - name: the name of the content block to set the content for, and content: the actual content to put into the block
     *     - template_content[] struct the injection of a single piece of content into a single editable region
     *         - name string the name of the mc:edit editable region to inject into
     *         - content string the content to inject
     * @param struct $message the other information on the message to send - same as /messages/send, but without the html content
     *     - html string optional full HTML content to be sent if not in template
     *     - text string optional full text content to be sent
     *     - subject string the message subject
     *     - from_email string the sender email address.
     *     - from_name string optional from name to be used
     *     - to array an array of recipient information.
     *         - to[] struct a single recipient's information.
     *             - email string the email address of the recipient
     *             - name string the optional display name to use for the recipient
     *     - headers struct optional extra headers to add to the message (currently only Reply-To and X-* headers are allowed)
     *     - important boolean whether or not this message is important, and should be delivered ahead of non-important messages
     *     - track_opens boolean whether or not to turn on open tracking for the message
     *     - track_clicks boolean whether or not to turn on click tracking for the message
     *     - auto_text boolean whether or not to automatically generate a text part for messages that are not given text
     *     - auto_html boolean whether or not to automatically generate an HTML part for messages that are not given HTML
     *     - inline_css boolean whether or not to automatically inline all CSS styles provided in the message HTML - only for HTML documents less than 256KB in size
     *     - url_strip_qs boolean whether or not to strip the query string from URLs when aggregating tracked URL data
     *     - preserve_recipients boolean whether or not to expose all recipients in to "To" header for each email
     *     - bcc_address string an optional address to receive an exact copy of each recipient's email
     *     - tracking_domain string a custom domain to use for tracking opens and clicks instead of mandrillapp.com
     *     - signing_domain string a custom domain to use for SPF/DKIM signing instead of mandrill (for "via" or "on behalf of" in email clients)
     *     - merge boolean whether to evaluate merge tags in the message. Will automatically be set to true if either merge_vars or global_merge_vars are provided.
     *     - global_merge_vars array global merge variables to use for all recipients. You can override these per recipient.
     *         - global_merge_vars[] struct a single global merge variable
     *             - name string the global merge variable's name. Merge variable names are case-insensitive and may not start with _
     *             - content string the global merge variable's content
     *     - merge_vars array per-recipient merge variables, which override global merge variables with the same name.
     *         - merge_vars[] struct per-recipient merge variables
     *             - rcpt string the email address of the recipient that the merge variables should apply to
     *             - vars array the recipient's merge variables
     *                 - vars[] struct a single merge variable
     *                     - name string the merge variable's name. Merge variable names are case-insensitive and may not start with _
     *                     - content string the merge variable's content
     *     - tags array an array of string to tag the message with.  Stats are accumulated using tags, though we only store the first 100 we see, so this should not be unique or change frequently.  Tags should be 50 characters or less.  Any tags starting with an underscore are reserved for internal use and will cause errors.
     *         - tags[] string a single tag - must not start with an underscore
     *     - google_analytics_domains array an array of strings indicating for which any matching URLs will automatically have Google Analytics parameters appended to their query string automatically.
     *     - google_analytics_campaign array|string optional string indicating the value to set for the utm_campaign tracking parameter. If this isn't provided the email's from address will be used instead.
     *     - metadata array metadata an associative array of user metadata. Mandrill will store this metadata and make it available for retrieval. In addition, you can select up to 10 metadata fields to index and make searchable using the Mandrill search api.
     *     - recipient_metadata array Per-recipient metadata that will override the global values specified in the metadata parameter.
     *         - recipient_metadata[] struct metadata for a single recipient
     *             - rcpt string the email address of the recipient that the metadata is associated with
     *             - values array an associated array containing the recipient's unique metadata. If a key exists in both the per-recipient metadata and the global metadata, the per-recipient metadata will be used.
     *     - attachments array an array of supported attachments to add to the message
     *         - attachments[] struct a single supported attachment
     *             - type string the MIME type of the attachment
     *             - name string the file name of the attachment
     *             - content string the content of the attachment as a base64-encoded string
     *     - images array an array of embedded images to add to the message
     *         - images[] struct a single embedded image
     *             - type string the MIME type of the image - must start with "image/"
     *             - name string the Content ID of the image - use <img src="cid:THIS_VALUE"> to reference the image in your HTML content
     *             - content string the content of the image as a base64-encoded string
     * @param boolean $async enable a background sending mode that is optimized for bulk sending. In async mode, messages/send will immediately return a status of "queued" for every recipient. To handle rejections when sending in async mode, set up a webhook for the 'reject' event. Defaults to false for messages with no more than 10 recipients; messages with more than 10 recipients are always sent asynchronously, regardless of the value of async.
     * @return array of structs for each recipient containing the key "email" with the email address and "status" as either "sent", "queued", or "rejected"
     *     - return[] struct the sending results for a single recipient
     *         - email string the email address of the recipient
     *         - status string the sending status of the recipient - either "sent", "queued", "rejected", or "invalid"
     *         - _id string the message's unique id
     */
    public function sendTemplate($template_name, $template_content, $message, $async=false) {
        $_params = array("template_name" => $template_name, "template_content" => $template_content, "message" => $message, "async" => $async);
        return $this->master->call('messages/send-template', $_params);
    }

    /**
     * Search the content of recently sent messages and optionally narrow by date range, tags and senders
     * @param string $query the search terms to find matching messages for
     * @param string $date_from start date
     * @param string $date_to end date
     * @param array $tags an array of tag names to narrow the search to, will return messages that contain ANY of the tags
     * @param array $senders an array of sender addresses to narrow the search to, will return messages sent by ANY of the senders
     * @param integer $limit the maximum number of results to return, defaults to 100, 1000 is the maximum
     * @return array of structs for each matching message
     *     - return[] struct the information for a single matching message
     *         - ts integer the Unix timestamp from when this message was sent
     *         - _id string the message's unique id
     *         - sender string the email address of the sender
     *         - subject string the message's subject link
     *         - email string the recipient email address
     *         - tags array list of tags on this message
     *             - tags[] string individual tag on this message
     *         - opens integer how many times has this message been opened
     *         - clicks integer how many times has a link been clicked in this message
     *         - state string sending status of this message: sent, bounced, rejected
     *         - metadata struct any custom metadata provided when the message was sent
     */
    public function search($query='*', $date_from=null, $date_to=null, $tags=null, $senders=null, $limit=100) {
        $_params = array("query" => $query, "date_from" => $date_from, "date_to" => $date_to, "tags" => $tags, "senders" => $senders, "limit" => $limit);
        return $this->master->call('messages/search', $_params);
    }

    /**
     * Parse the full MIME document for an email message, returning the content of the message broken into its constituent pieces
     * @param string $raw_message the full MIME document of an email message
     * @return struct the parsed message
     *     - subject string the subject of the message
     *     - from_email string the email address of the sender
     *     - from_name string the alias of the sender (if any)
     *     - to array an array of any recipients in the message
     *         - to[] struct the information on a single recipient
     *             - email string the email address of the recipient
     *             - name string the alias of the recipient (if any)
     *     - headers struct the key-value pairs of the MIME headers for the message's main document
     *     - text string the text part of the message, if any
     *     - html string the HTML part of the message, if any
     *     - attachments array an array of any attachments that can be found in the message
     *         - attachments[] struct information about an individual attachment
     *             - name string the file name of the attachment
     *             - type string the MIME type of the attachment
     *             - binary boolean if this is set to true, the attachment is not pure-text, and the content will be base64 encoded
     *             - content string the content of the attachment as a text string or a base64 encoded string based on the attachment type
     *     - images array an array of any embedded images that can be found in the message
     *         - images[] struct information about an individual image
     *             - name string the Content-ID of the embedded image
     *             - type string the MIME type of the image
     *             - content string the content of the image as a base64 encoded string
     */
    public function parse($raw_message) {
        $_params = array("raw_message" => $raw_message);
        return $this->master->call('messages/parse', $_params);
    }

    /**
     * Take a raw MIME document for a message, and send it exactly as if it were sent over the SMTP protocol
     * @param string $raw_message the full MIME document of an email message
     * @param string|null $from_email optionally define the sender address - otherwise we'll use the address found in the provided headers
     * @param string|null $from_name optionally define the sender alias
     * @param array|null $to optionally define the recipients to receive the message - otherwise we'll use the To, Cc, and Bcc headers provided in the document
     *     - to[] string the email address of the recipint
     * @param boolean $async enable a background sending mode that is optimized for bulk sending. In async mode, messages/sendRaw will immediately return a status of "queued" for every recipient. To handle rejections when sending in async mode, set up a webhook for the 'reject' event. Defaults to false for messages with no more than 10 recipients; messages with more than 10 recipients are always sent asynchronously, regardless of the value of async.
     * @return array of structs for each recipient containing the key "email" with the email address and "status" as either "sent", "queued", or "rejected"
     *     - return[] struct the sending results for a single recipient
     *         - email string the email address of the recipient
     *         - status string the sending status of the recipient - either "sent", "queued", "rejected", or "invalid"
     *         - _id string the message's unique id
     */
    public function sendRaw($raw_message, $from_email=null, $from_name=null, $to=null, $async=false) {
        $_params = array("raw_message" => $raw_message, "from_email" => $from_email, "from_name" => $from_name, "to" => $to, "async" => $async);
        return $this->master->call('messages/send-raw', $_params);
    }

}


