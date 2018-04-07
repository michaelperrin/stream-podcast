<?php

namespace App\Feed;

use Symfony\Component\Finder\Finder;
use Zend\Feed\Writer\Feed;

class PodcastGenerator
{
    private $audioBaseUri;

    public function __construct(string $audioBaseUri)
    {
        $this->audioBaseUri = $audioBaseUri;
    }

    public function getFeed(): string
    {
        $feed = new Feed();

        $feed->setTitle('Tutorial Feed');
        $feed->setLink('https://example.com/');
        $feed->setFeedLink('https://example.com/feed.xml', 'rss');
        $feed->setDescription('This is a tutorial feed for example.com');

        $this->addItems($feed);

        return $feed->export('rss');
    }

    protected function addItems(Feed $feed): void
    {
        $finder = new Finder();
        $finder->name('*.mp3')->sortByName();

        $items = [];

        $latestTime = 0;

        foreach ($finder->in('/audio') as $file) {
            $entry = $feed->createEntry();

            // Set the entry title:
            $entry->setTitle('Title');

            // Set the link to the entry:
            $entry->setLink(sprintf('%s%s.html', $this->audioBaseUri, $file->getBasename()));

            $fileInfo = $file->getFileInfo();
            $entry->setDateCreated(\DateTime::createFromFormat('U', $file->getCTime()));
            $entry->setDateModified(\DateTime::createFromFormat('U', $file->getMTime()));
            $entry->setContent('Test');

            $feed->addEntry($entry);

            $latestTime = $file->getCTime() > $latestTime ? $file->getCTime() : $latestTime;
        }

        $feed->setDateModified(\DateTime::createFromFormat('U', $latestTime));
    }
}
