<?php

require 'vendor/autoload.php';
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use PhpOffice\PhpSpreadsheet\IOFactory;

$client = new Client();
$client->setClient(new GuzzleClient([
    'verify' => false,
    'headers' => [
        'user-agent' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:73.0) Gecko/20100101 Firefox/73.0',
        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
        'Accept-Language' => 'en-US,en;q=0.5',
        'Referer' => 'https://service.mohw.gov.tw/',
        'Pragma' => 'no-cache',
        'Cache-Control' => 'no-cache',
    ]
    ]));
$crawler = $client->request('GET', 'https://service.mohw.gov.tw/DOCMAP/CusSite/TCMLQueryForm.aspx?mode=1');

$form = $crawler->selectButton('送出')->form();
$form->remove('reset');
$form->remove('ctl00$content$send');

$domDocument = new \DOMDocument;
$domInput = $domDocument->createElement('input');
$domInput->setAttribute('name', '__EVENTTARGET');
$domInput->setAttribute('value', 'ctl00$content$LinkButton1');
$formInput = new \Symfony\Component\DomCrawler\Field\InputFormField($domInput);
$form->set($formInput);

$domDocument = new \DOMDocument;
$domInput = $domDocument->createElement('input');
$domInput->setAttribute('name', '__EVENTARGUMENT');
$domInput->setAttribute('value', '');
$formInput = new \Symfony\Component\DomCrawler\Field\InputFormField($domInput);
$form->set($formInput);

$client->submit($form);

file_put_contents(__DIR__ . '/data.xls', $client->getResponse()->getContent());

$spreadsheet = IOFactory::load(__DIR__ . '/data.xls');
$writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
$writer->save("data.csv");