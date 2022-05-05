<?php

return [

    /*
     *  The default queue that should be used to send webhook requests.
     */
    'queue' => 'default',

    /*
     * The default http verb to use.
     */
    'http_verb' => 'post',

    /*
     * This is the name of the header where the signature will be added.
     */
    'signature_header_name' => 'Signature',

    /*
     * These are the headers that will be added to all webhook requests.
     */
    'headers' => [],

    /*
     * If a call to a webhook takes longer that this amount of seconds
     * the attempt will be considered failed.
     */
    'timeout_in_seconds' => 3,

    /*
     * The amount of times the webhook should be called before we give up.
     */
    'tries' => 3,

    /*
     * By default we will verify that the ssl certificate of the destination
     * of the webhook is valid.
     */
    'verify_ssl' => true,

    'tags' => [],
];
