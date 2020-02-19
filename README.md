# Type-Converter

## Introduce
Because when the app (Android and iOS) is connected to the api written in php, there are often confusing or flashbacks in the connection because the php data type is not fixed, so I hope to do a type conversion before outputting the api.

## Installation
```
composer require fishingboy/type-converter
```

## Usage

Import class
```php
use fishingboy\type_converter\Type_Converter;
```

convert data before api output
```php
$converter = new Type_Converter(
'{
    "users":[
        {
            "name":"string",
            "height":"float",
            "age":"integer",
            "adult":"boolean"
        }
    ]
}');
$response = $converter->convert([
    "users" => [
        ["name" => "leo", "height" => 173.5, "age" => "12", "adult" => false],
        ["name" => "rain"],
        ["name" => 819040, "age" => 14],
    ]
]);
```

output your api
```php
echo json_encode($response);
```

## type
1. integer
2. float
3. boolean
4. string