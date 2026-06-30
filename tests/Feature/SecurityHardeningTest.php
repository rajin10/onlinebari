<?php

use Illuminate\Http\UploadedFile;

it('rejects a non-image contact upload (blocks upload-to-webroot RCE)', function () {
    $response = $this->post('/contact/create', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'phone' => '01700000000',
        'message' => 'hello there',
        'cover_photo' => UploadedFile::fake()->create('evil.php', 10, 'application/x-php'),
    ]);

    $response->assertSessionHasErrors('cover_photo');
});

it('blocks guests from the send-notification blast endpoint', function () {
    $response = $this->post('/send-notification', ['title' => 'x', 'body' => 'y']);

    $response->assertRedirect(route('login'));
});
