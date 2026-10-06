<?php

/**
 * Placeholder Class 1–3 curriculum map, one row per skill.
 *
 * This is NOT the teacher-approved map phase-00-scope-freeze.md asks for —
 * that doesn't exist yet. It exists to exercise CurriculumSeeder,
 * CurriculumTree and the admin screens end to end. Replace this file
 * wholesale once the real map is approved (decision T-17 in
 * docs/tracking/decisions.md); CurriculumSeeder's updateOrCreate-by-slug
 * design makes that a data swap, not a code change.
 *
 * Topic names for Class 2 Maths are the mockup's illustrative list, per
 * phase-00-scope-freeze.md. Everything else here is a reasonable filler
 * to give every class and subject at least one topic and skill.
 *
 * @return list<array{class: string, subject: string, topic: string, skill: string, objective: string, difficulty: string}>
 */
return [
    [
        'class' => 'Class 1',
        'subject' => 'Maths',
        'topic' => 'Numbers up to 100',
        'skill' => 'Count forwards to 100',
        'objective' => 'Counts forwards from any number up to 100',
        'difficulty' => 'core',
    ],
    [
        'class' => 'Class 1',
        'subject' => 'Maths',
        'topic' => 'Addition and Subtraction',
        'skill' => 'Add two 1-digit numbers',
        'objective' => 'Adds two single-digit numbers with a sum up to 18',
        'difficulty' => 'core',
    ],
    [
        'class' => 'Class 1',
        'subject' => 'English',
        'topic' => 'Letters and Sounds',
        'skill' => 'Recognise all lower-case letters',
        'objective' => 'Names the sound of every lower-case letter',
        'difficulty' => 'core',
    ],
    [
        'class' => 'Class 1',
        'subject' => 'EVS',
        'topic' => 'My Body',
        'skill' => 'Name the five senses',
        'objective' => 'Names each of the five senses and one body part used for it',
        'difficulty' => 'core',
    ],
    [
        'class' => 'Class 2',
        'subject' => 'Maths',
        'topic' => 'Numbers up to 1000',
        'skill' => 'Read and write numbers up to 1000',
        'objective' => 'Reads and writes 3-digit numbers in figures and words',
        'difficulty' => 'core',
    ],
    [
        'class' => 'Class 2',
        'subject' => 'Maths',
        'topic' => 'Addition and Subtraction',
        'skill' => 'Add two 2-digit numbers without regrouping',
        'objective' => 'Adds two 2-digit numbers where no column sums past 9',
        'difficulty' => 'core',
    ],
    [
        'class' => 'Class 2',
        'subject' => 'Maths',
        'topic' => 'Addition and Subtraction',
        'skill' => 'Add two 2-digit numbers with regrouping',
        'objective' => 'Adds two 2-digit numbers, carrying over where a column sums past 9',
        'difficulty' => 'stretch',
    ],
    [
        'class' => 'Class 2',
        'subject' => 'Maths',
        'topic' => 'Shapes and Geometry',
        'skill' => 'Identify basic 2D shapes',
        'objective' => 'Names circles, squares, rectangles and triangles by their properties',
        'difficulty' => 'core',
    ],
    [
        'class' => 'Class 2',
        'subject' => 'English',
        'topic' => 'Reading Comprehension',
        'skill' => 'Answer literal questions about a short passage',
        'objective' => 'Answers who/what/where questions from a passage of up to 100 words',
        'difficulty' => 'core',
    ],
    [
        'class' => 'Class 2',
        'subject' => 'EVS',
        'topic' => 'Plants Around Us',
        'skill' => 'Name parts of a plant',
        'objective' => 'Labels root, stem, leaf and flower on a simple diagram',
        'difficulty' => 'core',
    ],
    [
        'class' => 'Class 3',
        'subject' => 'Maths',
        'topic' => 'Multiplication',
        'skill' => 'Recall multiplication tables up to 10',
        'objective' => 'Recalls the multiplication tables of 2 to 10 without counting on fingers',
        'difficulty' => 'core',
    ],
    [
        'class' => 'Class 3',
        'subject' => 'Maths',
        'topic' => 'Measurement',
        'skill' => 'Convert between metres and centimetres',
        'objective' => 'Converts a length between metres and centimetres using 1 m = 100 cm',
        'difficulty' => 'stretch',
    ],
    [
        'class' => 'Class 3',
        'subject' => 'English',
        'topic' => 'Grammar',
        'skill' => 'Use past and present tense correctly',
        'objective' => 'Rewrites a present-tense sentence correctly in the past tense',
        'difficulty' => 'core',
    ],
    [
        'class' => 'Class 3',
        'subject' => 'EVS',
        'topic' => 'Our Environment',
        'skill' => 'Sort waste into wet and dry',
        'objective' => 'Sorts ten everyday waste items correctly into wet and dry bins',
        'difficulty' => 'core',
    ],
];
