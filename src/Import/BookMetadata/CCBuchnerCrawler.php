<?php

namespace App\Import\BookMetadata;

use App\Helper\IsbnHelper;
use Imagick;
use Psr\Log\LoggerInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

class CCBuchnerCrawler implements CrawlerInterface {

    private const string IsbnPrefix = '978-3-661';
    private const string SearchUrlPattern = 'https://www.ccbuchner.de/suche?s={isbn}';
    private const string BaseUrl = 'https://www.ccbuchner.de/';
    private const string PublisherName = 'C.C. Buchner';

    public function __construct(private readonly HttpClientInterface $client,
                                private readonly IsbnHelper $isbnHelper,
                                private readonly LoggerInterface $logger) {

    }

    private function getSearchUrlForIsbn(string $isbn): string {
        return str_replace('{isbn}', $isbn, self::SearchUrlPattern);
    }

    public function supports(string $isbn): bool {
        return $this->isbnHelper->hasPrefix($isbn, self::IsbnPrefix);
    }

    public function crawl(string $isbn): BookMetadata {
        $productUrl = sprintf('%s/%s', rtrim(self::BaseUrl, '/'), $this->getProductPageUrl($isbn));

        try {
            $response = $this->client->request('GET', $productUrl);
            $dom = new Crawler($response->getContent());

            $metadata = new BookMetadata();
            $metadata->name = $dom->filter('.title-headline')->first()->text();
            $metadata->nameZusatz = $dom->filter('.title-series')->first()->text();
            $metadata->isbn = $isbn;
            $metadata->publisher = self::PublisherName;

            $this->crawlImage($dom, $metadata);

            return $metadata;
        } catch (Throwable $e) {
            $this->logger->error(sprintf('[ccbuchner] Fehler bei der Anfrage (%s)', $productUrl), [
                'exception' => $e
            ]);

            throw new CrawlException('Fehler bei der Abfrage der Produktinformationen', 0, $e);
        }
    }

    private function crawlImage(Crawler $dom, BookMetadata $metadata): void {
        try {
            $url = $dom->filter('.cover img')->first()->attr('src');
            $response = $this->client->request('GET', $url);

            $imagick = new Imagick();
            $imagick->readImageFile($response->toStream());
            $imagick->setImageFormat('png');

            $metadata->image = base64_encode($imagick->getImageBlob());
        } catch (Throwable $e) {
            $this->logger->error(sprintf('[ccbuchner] Fehler beim Download des Bildes (ISBN: %s)', $metadata->isbn), [
                'exception' => $e
            ]);
        }
    }

    private function getProductPageUrl(string $isbn): string {
        $response = $this->client->request('GET', $this->getSearchUrlForIsbn($isbn));
        $dom = new Crawler($response->getContent());

        $firstTitle = $dom->filter('.ccb-title');

        if($firstTitle->count() === 0) {
            throw new CrawlException(sprintf('[ccbuchner] Fehler bei der Suche nach der ISBN %s', $isbn));
        }

        $url = $dom->filter('.ccb-title')->first()->filter('a.title-link')->first()->attr('href');

        return ltrim($url, '/');
    }

    public function getPriority(): int {
        return 10;
    }
}