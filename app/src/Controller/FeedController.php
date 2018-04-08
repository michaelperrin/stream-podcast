<?php

namespace App\Controller;

use App\Feed\PodcastGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FeedController
{
    public function nprMorningEdition(Request $request, PodcastGenerator $podcastGenerator)
    {
        $feed = $podcastGenerator->getFeed(
            [
                'title'       => 'NPR Morning edition',
                'link'        => 'https://www.npr.org',
                'feedLink'    => $request->getUri(),
                'description' => 'NPR Morning edition',
            ]
        );

        $response = new Response($feed);
        $response->headers->set('Content-Type', 'xml');

        return $response;
    }
}
