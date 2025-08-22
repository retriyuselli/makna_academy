<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class ValidEmailDomain implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Basic email format validation
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $fail('Format email tidak valid.');
            return;
        }

        // Extract domain
        $domain = substr(strrchr($value, "@"), 1);
        
        // Check if domain has MX record (real email domain)
        if (!checkdnsrr($domain, 'MX')) {
            $fail('Domain email tidak valid atau tidak memiliki server email.');
            return;
        }

        // Block common disposable email domains
        $disposableDomains = [
            '10minutemail.com',
            'guerrillamail.com',
            'mailinator.com',
            'tempmail.org',
            'yopmail.com',
            'temp-mail.org',
            'throwaway.email',
            'mohmal.com',
            'mailtemp.info',
        ];

        if (in_array(strtolower($domain), $disposableDomains)) {
            $fail('Email sementara/disposable tidak diperbolehkan.');
            return;
        }

        // Block gmail with numbers only (common fake pattern)
        if (strtolower($domain) === 'gmail.com') {
            $localPart = substr($value, 0, strpos($value, '@'));
            if (preg_match('/^\d+$/', $localPart)) {
                $fail('Email dengan format angka saja tidak diperbolehkan.');
                return;
            }
        }
    }
}
