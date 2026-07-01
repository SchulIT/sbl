<?php

namespace App\Import\BookMetadata;

use App\Helper\IsbnHelper;
use Imagick;
use Psr\Log\LoggerInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

class KlettCrawler implements CrawlerInterface {

    private const string IsbnPrefix = '978-3-12';

    private const string UrlPattern = 'https://www.klett.de/produkt/isbn/{isbn}';

    private const string PublisherName = 'Klett';

    public function __construct(private readonly HttpClientInterface $client, private readonly IsbnHelper $isbnHelper, private readonly LoggerInterface $logger) {

    }

    public function getPriority(): int {
        return 1;
    }

    private function getUrlForIsbn(string $isbn): string {
        return str_replace('{isbn}', $this->isbnHelper->getHyphenatedIsbn13($isbn, self::IsbnPrefix), self::UrlPattern);
    }

    public function supports(string $isbn): bool {
        if($this->isbnHelper->hasPrefix($isbn, self::IsbnPrefix) !== true) {
            return false;
        }

        try {
            $response = $this->client->request('GET', $this->getUrlForIsbn($isbn));

            return $response->getStatusCode() === Response::HTTP_OK;
        } catch (Throwable $e) {
            $this->logger->error(sprintf('[klett] Fehler bei der Anfrage (%s)', $this->getUrlForIsbn($isbn)), [
                'exception' => $e
            ]);
            return false;
        }
    }

    /**
     * @throws CrawlException
     */
    public function crawl(string $isbn): BookMetadata {
        try {
            $response = $this->client->request('GET', $this->getUrlForIsbn($isbn));
            $dom = new Crawler($response->getContent());

            $metadata = new BookMetadata();
            $metadata->isbn = $isbn;
            $metadata->name = $dom->filter('.col-12.col-lg-6 h1')->first()->innerText();
            $metadata->nameZusatz = $dom->filter('.col-12.col-lg-6 h1 span.h2')->first()->innerText();
            $metadata->publisher = self::PublisherName;

            $this->crawlImage($dom, $metadata);

            return $metadata;
        } catch (Throwable $e) {
            $this->logger->error(sprintf('[klett] Fehler der Abfrage der Produktinformationen (ISBN: %s)', $this->getUrlForIsbn($metadata->isbn)), [
                'exception' => $e
            ]);

            throw new CrawlException('Fehler bei der Abfrage der Produktinformationen', 0, $e);
        }
    }

    private function crawlImage(Crawler $dom, BookMetadata $metadata): void {
        try {
            $url = $dom->filter('.col-12.col-lg-6 img')->attr('src');
            $response = $this->client->request('GET', $url);
            $imagick = new Imagick();
            $imagick->readImageFile($response->toStream());
            $imagick->setImageFormat('png');
            $metadata->image = base64_encode($imagick->getImageBlob());
        } catch (Throwable $e) {
            $this->logger->error(sprintf('[klett] Fehler beim Download des Bildes (ISBN: %s)', $this->getUrlForIsbn($metadata->isbn)), [
                'exception' => $e
            ]);
        }
    }
}