<?php

namespace Cudev\OrdinaryMail;

interface CredentialsInterface
{
    /** @return string */
    public function getAddress();

    /** @return string */
    public function getName();
}
