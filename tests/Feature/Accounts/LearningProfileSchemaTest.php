<?php

use Illuminate\Support\Facades\Schema;

test('learning_profiles never gains a child-identifying column', function () {
    $allowed = [
        'id',
        'user_id',
        'nickname',
        'class_id',
        'avatar_key',
        'interests',
        'active',
        'created_at',
        'updated_at',
    ];

    expect(Schema::getColumnListing('learning_profiles'))
        ->toEqualCanonicalizing($allowed);
});
