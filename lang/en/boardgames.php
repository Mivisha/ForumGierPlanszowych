<?php

return [
    'attributes' => [
        'id' => 'ID',
        'title' => 'Title',
        'description' => 'Description',
        'age_recommendation' => 'Age recommendation',
        'created_at' => 'Created at',
        'genres' => 'Genres',
        'image' => 'Image',
        'rating' => 'Rating',
        'action' => 'Actions',
        'min_players' => 'Minimum players',
        'max_players' => 'Maximum players',
        'play_time_minutes' => 'Play time (minutes)',
        'year_published' => 'Year published',
        'publisher' => 'Publisher',
    ],
    'actions' => [
        'create' => 'Add new game',
        'edit' => 'Edit',
        'delete' => 'Delete',
    ],
    'form' => [
        'create_title' => 'Create Board Game',
        'save' => 'Save',
        'cancel' => 'Cancel',
    ],
    'placeholders' => [
        'title' => 'Enter board game title',
        'description' => 'Enter board game description',
        'age_recommendation' => 'Minimum age',
        'min_players' => '1',
        'max_players' => '2',
        'play_time_minutes' => '0',
        'year_published' => 'Enter year',
        'publisher' => 'Publisher name',
    ],
    'messages' => [
        'created' => 'Game has been created!',
        'updated' => 'Game has been updated!',
        'deleted' => 'Game has been deleted!',
    ],
];
