<?php
$basePath = dirname(__DIR__);
$fh = fopen($basePath . '/data.csv', 'r');
$head = fgetcsv($fh, 2048);
$q = array(
    '衛部藥製' => '01',
    '衛部藥輸' => '02',
    '衛部成製' => '03',
    '衛部中藥輸' => '04',
    '衛部成輸' => '05',
    '衛署藥製' => '01',
    '衛署藥輸' => '02',
    '衛署成製' => '03',
    '衛署中藥輸' => '04',
    '衛署成輸' => '05',
    '內衛藥製' => '12',
    '內衛藥輸' => '13',
    '內衛成製' => '14',
);
$jsonPath = $basePath . '/json';
foreach($q AS $code) {
    $thePath = $jsonPath . '/' . $code;
    if(!file_exists($thePath)) {
        mkdir($thePath, 0777, true);
    }
}
$now = date('Y-m-d H:i:s');
while($line = fgetcsv($fh, 2048)) {
    $parts = explode('字第', $line[0]);
    $parts[1] = preg_replace('/[^0-9]/i', '', $parts[1]);
    $data = array(
        'code' => "{$q[$parts[0]]}{$parts[1]}",
        'url' => "https://service.mohw.gov.tw/DOCMAP/CusSite/TCMLResultDetail.aspx?LICEWORDID={$q[$parts[0]]}&LICENUM={$parts[1]}",
        'time' => $now,
    );
    $jsonFile = "{$jsonPath}/{$q[$parts[0]]}/{$parts[1]}.json";
    $raw = array_combine($head, $line);
    $name = explode("\n", $raw['藥品名稱']);
    $part1 = explode('效能：', $raw['適應症及效能']);
    $part2 = explode('適應症：', $part1[0]);
    $part3 = explode("\n", $raw['劑型與類別']);
    $data['中文品名'] = $name[0];
    $data['英文品名'] = $name[1];
    $data['許可證字號'] = trim($raw['許可證字號']);
    $data['發證日期'] = $raw['發證日期'];
    $data['有效日期'] = $raw['有效日期'];
    $data['製造商名稱'] = $raw['製造商名稱'];
    $data['製造商地址'] = $raw['製造商地址'];
    $data['申請商名稱'] = $raw['申請商名稱'];
    $data['效能'] = trim($part1[1]);
    $data['適應症'] = trim($part2[1]);
    $data['劑型'] = trim($part3[0]);
    $data['類別'] = trim($part3[1]);
    $data['包裝'] = trim($raw['包裝']);
    $data['單複方'] = trim($raw['單/複方']);
    $data['限制項目'] = trim($raw['限制項目']);
    $data['ingredients'] = array();
    $lines = explode("\n", $raw['處方成分']);
    $lines[0] = str_replace('處方:', '', $lines[0]);
    $firstLine = true;
    foreach($lines AS $line) {
        if($firstLine) {
            $parts = array($line);
            $firstLine = false;
        } else {
            $parts = preg_split('/[ \\(\\)]/', $line);
        }
        
        $ingredient = array(
            '成分名稱' => '',
            '含量描述' => '',
            '含量' => '',
            '單位' => '',
        );
        if(!empty($parts[2])) {
            $ingredient['成分名稱'] = $parts[0];
            $ingredient['含量'] = $parts[2];
            $ingredient['單位'] = $parts[3];
            $data['ingredients'][] = $ingredient;
        } elseif(!empty($parts[0])) {
            $ingredient['含量描述'] = $parts[0];
            $data['ingredients'][] = $ingredient;
        }
    }
    file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}
