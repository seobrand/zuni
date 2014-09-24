<?php
    //API Key - see http://admin.mailchimp.com/account/api
    $apikey = '20d166cc30a62a01a63b72a5d8ca8b2d-us7';//'YOUR MAILCHIMP APIKEY';
    
    // A List Id to run examples against. use lists() to view all
    // Also, login to MC account, go to List, then List Tools, and look for the List ID entry
    $listId = ''; //'YOUR MAILCHIMP LIST ID - see lists() method';
    
    // A Campaign Id to run examples against. use campaigns() to view all
    $campaignId = ''; //'YOUR MAILCHIMP CAMPAIGN ID - see campaigns() method';

    //some email addresses used in the examples:
    $my_email = '4007@dothejob.org';//'INVALID@example.org';
    $boss_man_email = '105dv@dothejob.org';//'INVALID@example.com';

    //just used in xml-rpc examples
    $apiUrl = 'http://api.mailchimp.com/1.3/';
    
?>
