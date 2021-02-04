<?php

namespace Faza13\OTP\Contracts;

interface OTPInterface
{
    public function request($phone, array $options = []);

    public function validate($phone, $code, $options = []);
}
