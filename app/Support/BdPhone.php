<?php

namespace App\Support;

/**
 * Bangladesh mobile number helper.
 *
 * Accepts the three formats requested by the checkout spec and normalises
 * them all to the canonical 11-digit local form (01XXXXXXXXX):
 *   - 01XXXXXXXXX      (11 digits)
 *   - 8801XXXXXXXXX    (13 digits)
 *   - +8801XXXXXXXXX   (14 chars)
 *
 * Spaces, dashes and other separators are stripped automatically.
 */
class BdPhone
{
    /**
     * Normalise any accepted input to 01XXXXXXXXX, or null when it is not a
     * valid Bangladesh mobile number.
     */
    public static function normalize(?string $raw): ?string
    {
        if ($raw === null) {
            return null;
        }

        // Strip everything that is not a digit (spaces, dashes, +, etc.).
        $digits = preg_replace('/\D+/', '', $raw) ?? '';

        if ($digits === '') {
            return null;
        }

        // 8801XXXXXXXXX / +8801XXXXXXXXX -> 01XXXXXXXXX
        if (strlen($digits) === 13 && str_starts_with($digits, '88')) {
            $digits = substr($digits, 2);
        } elseif (strlen($digits) === 12 && str_starts_with($digits, '880')) {
            // 880 followed by 1XXXXXXXXX (missing leading zero)
            $digits = '0'.substr($digits, 3);
        } elseif (strlen($digits) === 10 && $digits[0] === '1') {
            // Local number typed without the leading zero (1XXXXXXXXX)
            $digits = '0'.$digits;
        }

        return self::isValid($digits) ? $digits : null;
    }

    /**
     * Validate an already-normalised 01XXXXXXXXX number.
     */
    public static function isValid(string $normalized): bool
    {
        return (bool) preg_match('/^01[3-9]\d{8}$/', $normalized);
    }

    /**
     * Accept any of the supported raw formats?
     */
    public static function accepts(?string $raw): bool
    {
        return self::normalize($raw) !== null;
    }

    /**
     * Last 10 digits — used for robust DB matching across stored formats
     * (matches both 017... and +88017...).
     */
    public static function suffix(?string $raw): ?string
    {
        $normalized = self::normalize($raw);

        return $normalized ? substr($normalized, -10) : null;
    }
}
