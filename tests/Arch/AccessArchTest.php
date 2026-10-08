<?php

test('Controllers do not directly call Storage temporaryUrl on resources disk')
    ->expect('App\Http\Controllers')
    ->not->toUse('Storage::disk');
