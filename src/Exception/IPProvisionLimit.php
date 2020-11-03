<?php

namespace Mandrill\Exception;

/**
 * A dedicated IP cannot be provisioned while another request is pending.
 */
class IPProvisionLimit extends Error
{
}