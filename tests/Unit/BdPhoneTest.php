<?php

use App\Support\BdPhone;

it('normalises all accepted Bangladesh formats to 01XXXXXXXXX', function (string $input) {
    expect(BdPhone::normalize($input))->toBe('01712345678');
})->with([
    '01712345678',        // 11-digit local
    '8801712345678',      // 13-digit country code
    '+8801712345678',     // 14-char international
    '+88 01712-345678',   // with spaces / dashes
    ' 01712 345 678 ',    // padded with spaces
]);

it('rejects invalid numbers', function (?string $input) {
    expect(BdPhone::normalize($input))->toBeNull();
})->with([
    '0171234567',     // too short
    '017123456789',   // too long
    '01212345678',    // invalid operator prefix (012)
    '02712345678',    // does not start with 01
    'abcdefghijk',    // non-numeric
    '',               // empty
    null,             // null
]);

it('validates an already-normalised number', function () {
    expect(BdPhone::isValid('01812345678'))->toBeTrue();
    expect(BdPhone::isValid('01112345678'))->toBeFalse(); // 011 not a valid operator
});

it('returns the last 10 digits as the matching suffix', function () {
    expect(BdPhone::suffix('+8801712345678'))->toBe('1712345678');
    expect(BdPhone::suffix('bad'))->toBeNull();
});
