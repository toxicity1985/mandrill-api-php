<?php

class Mandrill_Rejects {
    public function __construct(Mandrill $master) {
        $this->master = $master;
    }

    /**
     * Retrieves your email rejection blacklist. You can provide an email
address to limit the results. Returns up to 1000 results.
     * @param string $email an optional email address to search by
     * @return array Up to 1000 rejection entries
     *     - return[] struct the information for each rejection blacklist entry
     *         - email string the email that is blocked
     *         - reason string the type of event (hard-bounce, soft-bounce, spam, unsub) that caused this rejection
     *         - created_at string when the email was added to the blacklist
     *         - expires_at string when the blacklist entry will expire (this may be in the past)
     *         - expired boolean whether the blacklist entry has expired
     *         - Sender struct sender the sender that this blacklist entry applies to, or null if none.
     */
    public function getList($email=null) {
        $_params = array("email" => $email);
        return $this->master->call('rejects/list', $_params);
    }

    /**
     * Deletes an email rejection. There is no limit to how many rejections
you can remove from your blacklist, but keep in mind that each deletion
has an affect on your reputation.
     * @param string $email an email address
     * @return struct a status object containing the address and whether the deletion succeeded.
     *     - email string the email address that was removed from the blacklist
     *     - deleted boolean whether the address was deleted successfully.
     */
    public function delete($email) {
        $_params = array("email" => $email);
        return $this->master->call('rejects/delete', $_params);
    }

}


