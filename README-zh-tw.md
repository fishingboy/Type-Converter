# Type-Converter

## 語言
[en-us](README.md) / 
[zh-tw](README-zh-tw.md)

## 介紹
因為 app 在與 php 撰寫的 api 串接的時候，常常會有因為 php 資料型態不固定，造成串接上的混淆或是閃退，所以希望在輸出 api 之前先做一次型態的轉換。

## 安裝
```
composer require fishingboy/type-converter
```

## Usage

載入 class
```php
use fishingboy\type_converter\Type_Converter;
```

在輸出 api 前，先將資料型態轉換
```php
$converter = new Type_Converter($format_json);
$response = $converter->convert($data);
```

範例如下
```php
$converter = new Type_Converter(
'{
    "users":[
        {
            "name":"str",
            "height":"float",
            "age":"int",
            "adult":"bool"
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

輸出 API Response
```php
echo json_encode($response);
```

## type
1. int
2. float
3. bool
4. str