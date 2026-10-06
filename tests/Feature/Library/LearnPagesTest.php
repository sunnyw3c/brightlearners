<?php

use Inertia\Testing\AssertableInertia as Assert;

test('the class landing page renders', function () {
    ['class' => $class] = createPublishedFreeResource();

    $this->get("/{$class->slug}")
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component('learn/class'));
});

test('the subject landing page renders and lists the subject topics, even without class_topic copy', function () {
    ['class' => $class, 'subject' => $subject, 'topic' => $topic] = createPublishedFreeResource();

    $this->get("/{$class->slug}/{$subject->slug}")
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('learn/subject')
            ->where('topics.0.slug', $topic->slug));
});

test('the topic landing page renders even without a class_topic row', function () {
    ['class' => $class, 'subject' => $subject, 'topic' => $topic, 'skill' => $skill] = createPublishedFreeResource();

    $this->get("/{$class->slug}/{$subject->slug}/{$topic->slug}")
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('learn/topic')
            ->where('seo.noindex', false)
            ->where('skills.0.slug', $skill->slug));
});

test('an unknown class is not found', function () {
    $this->get('/class-does-not-exist')->assertNotFound();
});

test('an unknown subject under a real class is not found', function () {
    ['class' => $class] = createPublishedFreeResource();

    $this->get("/{$class->slug}/subject-does-not-exist")->assertNotFound();
});
