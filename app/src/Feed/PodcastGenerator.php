<?php

namespace App\Feed;

use Symfony\Component\Finder\Finder;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Zend\Feed\Writer\Feed;

class PodcastGenerator
{
    private $audioBaseUri;

    public function __construct(string $audioBaseUri)
    {
        $this->audioBaseUri = $audioBaseUri;
    }

    public function getFeed(array $feedProperties, string $filenameFilter): string
    {
        $feedProperties = $this->resolveProperties($feedProperties);

        $feed = new Feed();

        $feed->setTitle($feedProperties['title']);
        $feed->setLink($feedProperties['link']);
        $feed->setFeedLink($feedProperties['feedLink'], $feedProperties['feedType']);
        $feed->setDescription($feedProperties['description']);

        $this->addItems($feed, $filenameFilter);

        return $feed->export('rss');
    }

    protected function resolveProperties(array $properties): array
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired(['title', 'link', 'feedLink', 'description']);
        $resolver->setDefaults([
            'feedType' => 'rss',
        ]);

        return $resolver->resolve($properties);
    }

    protected function addItems(Feed $feed, string $filenameFilter): void
    {
        $finder = new Finder();
        $finder->name('KCRW-*.mp3')->sortByName();

        $items = [];

        $latestTime = 0;

        foreach ($finder->in('/audio') as $file) {
            $fileInfo = $file->getFileInfo();
            $dateCreated = \DateTime::createFromFormat('U', $file->getCTime());

            $entry = $feed->createEntry();
            $entry->setTitle(sprintf('Episode %s', $dateCreated->format('Y-m-d')));
            $entry->setLink(sprintf('%s%s.html', $this->audioBaseUri, $file->getBasename()));
            $entry->setDateCreated($dateCreated);
            $entry->setDateModified(\DateTime::createFromFormat('U', $file->getMTime()));
            $entry->setContent(sprintf('Episode %s', $dateCreated->format('Y-m-d')));

            $feed->addEntry($entry);

            $latestTime = $file->getCTime() > $latestTime ? $file->getCTime() : $latestTime;
        }

        $feed->setDateModified(\DateTime::createFromFormat('U', $latestTime));
    }
}
