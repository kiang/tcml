<?php

require 'vendor/autoload.php';
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use PhpOffice\PhpSpreadsheet\IOFactory;

$client = new Client();
$client->setClient(new GuzzleClient(['verify' => false]));
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