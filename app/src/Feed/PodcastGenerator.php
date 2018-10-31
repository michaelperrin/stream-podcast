<?php

namespace App\Feed;

use App\Feed\Exception\NotFoundException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Zend\Feed\Writer\Entry;
use Zend\Feed\Writer\Feed;

class PodcastGenerator
{
    private $router;
    private $podcasts;
    private $audioBaseUri;

    public function __construct(RouterInterface $router, array $podcasts, string $audioBaseUri)
    {
        $this->router = $router;
        $this->podcasts = $podcasts;
        $this->audioBaseUri = $audioBaseUri;
    }

    /**
     * Retrieves given feed
     *
     * @param string $name
     * @param string $uri
     * @return string
     * @throws NotFoundException
     */
    public function getFeed(string $name): string
    {
        if (!isset($this->podcasts[$name])) {
            throw new NotFoundException(sprintf('Podcast "%s" was not found', $name));
        }

        $properties = $this->podcasts[$name];

        $properties = $this->resolveProperties($properties);

        $feed = new Feed();

        $feed->setTitle($properties['title']);
        $feed->setLink($properties['link']);
        $feed->setFeedLink(
            $this->router->generate('podcast_feed', ['name' => $name]),
            $properties['feedType']
        );
        $feed->setDescription($properties['description']);

        $this->addEntries($feed, $properties['files']);

        return $feed->export($properties['feedType']);
    }

    protected function resolveProperties(array $properties): array
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired(['title', 'link', 'description', 'files']);
        $resolver->setDefaults([
            'feedType' => 'rss',
        ]);

        return $resolver->resolve($properties);
    }

    protected function addEntries(Feed $feed, string $filenameFilter): void
    {
        $finder = new Finder();
        $finder->name($filenameFilter)->sortByName();

        $items = [];

        $latestTime = 0;



        foreach ($finder->in('/audio') as $file) {
            $fileInfo = $file->getFileInfo();

            $entry = $feed->createEntry();
            $this->populateEntryData($entry, $file);

            $feed->addEntry($entry);

            $latestTime = $file->getCTime() > $latestTime ? $file->getCTime() : $latestTime;
        }

        $feed->setDateModified(\DateTime::createFromFormat('U', $latestTime));
    }

    protected function populateEntryData(Entry $entry, \SplFileinfo $file): Entry
    {
        $uri = sprintf('%s/%s', $this->audioBaseUri, $file->getBasename());
        $dateCreated = \DateTime::createFromFormat('U', $file->getCTime());

        $entry->setTitle(sprintf('Episode %s', $dateCreated->format('Y-m-d'))); // Arbitrary title ;-)
        $entry->setLink($uri);
        $entry->setEnclosure(['uri' => $uri, 'type' => 'audio/mpeg', 'length' => $file->getSize()]);
        $entry->setDateCreated($dateCreated);
        $entry->setDateModified(\DateTime::createFromFormat('U', $file->getMTime()));
        $entry->setContent(sprintf('Episode %s', $dateCreated->format('Y-m-d')));

        return $entry;
    }
}
