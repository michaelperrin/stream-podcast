<?php

namespace App\Controller;

use App\Feed\PodcastGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FeedController
{
    public function podcast(PodcastGenerator $podcastGenerator, string $name)
    {
        try {
            $feed = $podcastGenerator->getFeed($name);
        } catch (NotFoundException $e) {
            throw new NotFoundHttpException('The product does not exist');
        }

        $response = new Response($feed);
        $response->headers->set('Content-Type', 'xml');

        return $response;
    }
}
