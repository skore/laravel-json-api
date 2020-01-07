<?php

return [

    'pagination' => [
        /*
         * The default number of results that will be returned
         * when using the JSON API paginator.
         */
        'default_size' => 50,
    ],

    'included' => 'included',

    /**
     * Bypass package built-in authorisation
     * with true it won't check policies (view & viewAny).
     */
    'authorisable' => [
        'viewAny' => false,
        'view' => false,
    ],

];
