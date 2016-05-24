<?php

namespace Cudev\OrdinaryMail;

/**
 * Just marker interface to find something with name and address
 * Interface CredentialsInterface
 * @package Cudev\OrdinaryMail
 */
interface CredentialsInterface
{
    /** @return string */
    public function getAddress();

    /** @return string */
    public function getName();
}
