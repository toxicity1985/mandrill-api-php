<?php

namespace Mandrill;

class Mandrill {
    
    public $apikey;
    public $ch;
    public $root = 'https://mandrillapp.com/api/1.0';
    public $debug = false;
    private Request\Templates $templates;
    private Request\Exports $exports;
    private Request\Users $users;
    private Request\Rejects $rejects;
    private Request\Inbound $inbound;
    private Request\Tags $tags;
    private Request\Messages $messages;
    private Request\Whitelists $whitelists;
    private Request\Ips $ips;
    private Request\Internal $internal;
    private Request\Subaccounts $subaccounts;
    private Request\Urls $urls;
    private Request\Webhooks $webhooks;
    private Request\Senders $senders;
    private Request\Metadata $metadata;

    public static $error_map = array(
        "ValidationError" => Exception\ValidationError::class,
        "Invalid_Key" => Exception\InvalidKey::class,
        "PaymentRequired" => Exception\PaymentRequired::class,
        "Unknown_Subaccount" => Exception\UnknownSubaccount::class,
        "Unknown_Template" => Exception\UnknownTemplate::class,
        "ServiceUnavailable" => Exception\ServiceUnavailable::class,
        "Unknown_Message" => Exception\UnknownMessage::class,
        "Invalid_Tag_Name" => Exception\InvalidTagName::class,
        "Invalid_Reject" => Exception\InvalidReject::class,
        "Unknown_Sender" => Exception\UnknownSender::class,
        "Unknown_Url" => Exception\UnknownUrl::class,
        "Unknown_TrackingDomain" => Exception\UnknownTrackingDomain::class,
        "Invalid_Template" => Exception\InvalidTemplate::class,
        "Unknown_Webhook" => Exception\UnknownWebhook::class,
        "Unknown_InboundDomain" => Exception\UnknownInboundDomain::class,
        "Unknown_InboundRoute" => Exception\UnknownInboundRoute::class,
        "Unknown_Export" => Exception\UnknownExport::class,
        "IP_ProvisionLimit" => Exception\IPProvisionLimit::class,
        "Unknown_Pool" => Exception\UnknownPool::class,
        "NoSendingHistory" => Exception\NoSendingHistory::class,
        "PoorReputation" => Exception\PoorReputation::class,
        "Unknown_IP" => Exception\UnknownIP::class,
        "Invalid_EmptyDefaultPool" => Exception\InvalidEmptyDefaultPool::class,
        "Invalid_DeleteDefaultPool" => Exception\InvalidDeleteDefaultPool::class,
        "Invalid_DeleteNonEmptyPool" => Exception\InvalidDeleteNonEmptyPool::class,
        "Invalid_CustomDNS" => Exception\InvalidCustomDNS::class,
        "Invalid_CustomDNSPending" => Exception\InvalidCustomDNSPending::class,
        "Metadata_FieldLimit" => Exception\MetadataFieldLimit::class,
        "Unknown_MetadataField" => Exception\UnknownMetadataField::class
    );

    public function __construct($apikey=null) {
        if(!$apikey) $apikey = getenv('MANDRILL_APIKEY');
        if(!$apikey) $apikey = $this->readConfigs();
        if(!$apikey) throw new Exception\Error('You must provide a Mandrill API key');
        $this->apikey = $apikey;

        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_USERAGENT, 'Mandrill-PHP/1.0.55');
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->ch, CURLOPT_HEADER, false);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, 600);

        $this->root = rtrim($this->root, '/') . '/';

        $this->templates = new Request\Templates($this);
        $this->exports = new Request\Exports($this);
        $this->users = new Request\Users($this);
        $this->rejects = new Request\Rejects($this);
        $this->inbound = new Request\Inbound($this);
        $this->tags = new Request\Tags($this);
        $this->messages = new Request\Messages($this);
        $this->whitelists = new Request\Whitelists($this);
        $this->ips = new Request\Ips($this);
        $this->internal = new Request\Internal($this);
        $this->subaccounts = new Request\Subaccounts($this);
        $this->urls = new Request\Urls($this);
        $this->webhooks = new Request\Webhooks($this);
        $this->senders = new Request\Senders($this);
        $this->metadata = new Request\Metadata($this);
    }

    public function __destruct() {
        curl_close($this->ch);
    }

    public function call($url, $params) {
        $params['key'] = $this->apikey;
        $params = json_encode($params);
        $ch = $this->ch;

        curl_setopt($ch, CURLOPT_URL, $this->root . $url . '.json');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_VERBOSE, $this->debug);

        $start = microtime(true);
        $this->log('Call to ' . $this->root . $url . '.json: ' . $params);
        if($this->debug) {
            $curl_buffer = fopen('php://memory', 'w+');
            curl_setopt($ch, CURLOPT_STDERR, $curl_buffer);
        }

        $response_body = curl_exec($ch);
        $info = curl_getinfo($ch);
        $time = microtime(true) - $start;
        if($this->debug) {
            rewind($curl_buffer);
            $this->log(stream_get_contents($curl_buffer));
            fclose($curl_buffer);
        }
        $this->log('Completed in ' . number_format($time * 1000, 2) . 'ms');
        $this->log('Got response: ' . $response_body);

        if(curl_error($ch)) {
            throw new Exception\HttpError("API call to $url failed: " . curl_error($ch));
        }
        $result = json_decode($response_body, true);
        if($result === null) throw new Exception\Error('We were unable to decode the JSON response from the Mandrill API: ' . $response_body);
        
        if(floor($info['http_code'] / 100) >= 4) {
            throw $this->castError($result);
        }

        return $result;
    }

    public function readConfigs() {
        $paths = array('~/.mandrill.key', '/etc/mandrill.key');
        foreach($paths as $path) {
            if(file_exists($path)) {
                $apikey = trim(file_get_contents($path));
                if($apikey) return $apikey;
            }
        }
        return false;
    }

    public function castError($result) {
        if($result['status'] !== 'error' || !$result['name']) throw new Exception\Error('We received an unexpected error: ' . json_encode($result));

        $class = (isset(self::$error_map[$result['name']])) ? self::$error_map[$result['name']] : Exception\Error::class;
        return new $class($result['message'], $result['code']);
    }

    public function log($msg) {
        if($this->debug) error_log($msg);
    }
}
