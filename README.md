# Introduction

This package allows you to connect and get data from MongoDB.

# Installation

## By downloading .zip file

1. [Download](https://www.koolreport.com/packages/mongodb)
2. Unzip the zip file
3. Copy the folder `mongodb` into `koolreport` folder so that look like below

```bash
koolreport
├── core
├── mongodb
```

## By composer

```
composer require koolreport/mongodb
```

# Documentation

### Settings

|Name|type|default|description|
|----------|---------|---------|----------------|
|class|string||	Must set to `'\koolreport\mongodb\MongoDataSource'`|
|connectionString|string||Define connection string to MongoDB. If you use connectionString, you do not need to use properties host, username and password.|
|host|string||MongoDB host|
|username|string||Username|
|password|string||Password|
|database|string||The name of database you want to connect|

### Example

```

<?php
class MyReport extends \koolreport\KoolReport
{
    public function settings()
    {
        return array(
            "dataSources"=>array(
                "mongo_purchase"=>array(
                    "class"=>'\koolreport\mongodb\MongoDataSource',
                    "connectionString"=>"mongo://johndoe:secret_password@localhost:65432",
                    "database"=>"dbpurchase"
                ),
            )
        );
    }
    public function setup()
    {
        $this->src('mongo_purchase')
        ->query(array("collection"=>"cPurchases"))
        ->pipe(..)
        ->pipe(...)
        ...
        ->pipe($this->dataStore('mongo_purchases'));
    }
}
```

## Support

Please use our forum if you need support, by this way other people can benefit as well. If the support request need privacy, you may send email to us at __support@koolreport.com__.