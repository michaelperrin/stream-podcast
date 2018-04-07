<?php

namespace App\Controller;

use App\Feed\PodcastGenerator;
use Symfony\Component\HttpFoundation\Response;

class FeedController
{
    public function index(PodcastGenerator $podcastGenerator)
    {
        $feed = $podcastGenerator->getFeed();

        $response = new Response($feed);
        $response->headers->set('Content-Type', 'xml');

        return $response;
    }
}
