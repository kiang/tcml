# Traditional Chinese Medicine License Archive (TCML)

This project archives data from the Ministry of Health and Welfare's Traditional Chinese Medicine License database in Taiwan.

## Data Source

The data is sourced from the [Ministry of Health and Welfare's TCML Query System](https://service.mohw.gov.tw/DOCMAP/CusSite/TCMLQueryForm.aspx?mode=1).

## Frontend

The data can be browsed through the public interface at [https://drugs.olc.tw/](https://drugs.olc.tw/)

## Data Structure

The archived data is stored in JSON format with the following structure:

```json
{
  "code": "License code",
  "url": "Original data URL",
  "中文品名": "Chinese name",
  "英文品名": "English name",
  "許可證字號": "License number",
  "發證日期": "Issue date",
  "有效日期": "Expiry date",
  "製造商名稱": "Manufacturer name",
  "製造商地址": "Manufacturer address",
  "申請商名稱": "Applicant name",
  "效能": "Efficacy",
  "適應症": "Indications",
  "劑型": "Dosage form",
  "類別": "Category",
  "包裝": "Packaging",
  "單複方": "Single/Compound preparation",
  "限制項目": "Restrictions",
  "ingredients": [
    {
      "成分名稱": "Ingredient name",
      "含量描述": "Content description",
      "含量": "Content amount",
      "單位": "Unit"
    }
  ]
}
```

## License Categories

- 01: 衛部藥製/衛署藥製 (MOHW/DOH Pharmaceutical Manufacturing)
- 02: 衛部藥輸/衛署藥輸 (MOHW/DOH Pharmaceutical Import)
- 03: 衛部成製/衛署成製 (MOHW/DOH Finished Product Manufacturing)
- 04: 衛部中藥輸/衛署中藥輸 (MOHW/DOH Chinese Medicine Import)
- 05: 衛部成輸/衛署成輸 (MOHW/DOH Finished Product Import)
- 12: 內衛藥製 (Internal Pharmaceutical Manufacturing)
- 13: 內衛藥輸 (Internal Pharmaceutical Import)
- 14: 內衛成製 (Internal Finished Product Manufacturing)
- 15: 內部註銷 (Internal Cancellation)

## Directory Structure

```
tcml/
├── data.csv          # Raw data file
├── json/            # Processed JSON files
│   ├── 01/         # Category 01 licenses
│   ├── 02/         # Category 02 licenses
│   └── .../
└── scripts/         # Data processing scripts
```

## License

This repository contains both code and data, which are licensed differently:

- **Code**: [MIT License](LICENSE)
- **Data**: [Creative Commons Attribution License (CC BY)](LICENSE-DATA)
