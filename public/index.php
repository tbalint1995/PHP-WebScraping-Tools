<?php 

define('IN_WS', true);

include(__DIR__.'/../listing.php');

$uriparts = explode('/',$_SERVER["REQUEST_URI"]);
$curr_url = '/'.trim($uriparts[ sizeof($uriparts) - 1 ], '/');

$listing = new listing;

switch( $curr_url ) {
    case '/':
        $listing->searchView();
        break;
    case '/search':
        $listing->search();
        break;
    default: 
        http_response_code(404);
        exit;
        break;
}