<?php

function gravatar_url(String $email)
{
    $email = md5($email);

    return "https://gravatar.com/avatar/{$email}?".http_build_query([
        's' => 60,
        'd' => rand()%2?'robohash':'wavatar'
    ]);
}
