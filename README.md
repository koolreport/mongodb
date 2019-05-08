# Introduction

This package allows you to connect and get data from MongoDB.

#Installation

1. Download the zip file.
2. Unzip
3. Copy folder `mongodb` to the folder `koolreport/packages`

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