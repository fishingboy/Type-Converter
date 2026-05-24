# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## 專案簡介

`fishingboy/type-converter` 是一個 PHP Composer 套件，用於在 JSON 輸出前強制轉換 API 回應資料的型別，解決手機端（Android/iOS）因 PHP 型別不固定而造成解析錯誤的問題。

Packagist 安裝：`composer require fishingboy/type-converter`

## Git 規範

Commit 訊息一律使用**中文**撰寫。

## 常用指令

**安裝相依套件：**
```bash
composer install
```

**執行所有測試：**
```bash
./vendor/bin/phpunit tests/
```

**執行單一測試方法：**
```bash
./vendor/bin/phpunit tests/TypeConvertTest.php --filter test_convert_to_int
```

**Git push（會先 rebase 再推送）：**
```bash
make push
```

## 架構說明

整個套件只有一個類別：`src/Type_Converter.php`，命名空間為 `fishingboy\type_converter`。

**運作方式：**

1. 建構子接收一個 JSON 字串作為輸出 schema，定義欄位名稱對應的型別字串（`"int"`、`"float"`、`"bool"`、`"str"`）。
2. `convert($value)` 依據 schema 遞迴處理資料：
   - Schema 為 JSON 物件 → PHP 陣列會轉型為 `stdClass`，缺少的欄位補 `null`
   - Schema 為 JSON 陣列 → 用 `$format[0]` 作為子 schema 逐筆轉換
   - Schema 為型別字串 → 呼叫 PHP 的 `intval`／`floatval`／`strval`／`boolval`
3. 傳入 `null` 時一律回傳 `null`（null 直接穿透）。
4. Schema 期望物件但傳入非陣列時，拋出 `\Exception("Can't convert {$type} to object !!")`。
5. 遇到未定義的型別字串時，拋出 `\Exception("Undefined Type")`。
6. 建構子收到無效 JSON 時，拋出 `\Exception("json format error !!")`。

**重要行為：** 資料中缺少的欄位會補 `null` 而非略過，確保輸出結構與 schema 一致。

## 測試

測試檔位於 `tests/TypeConvertTest.php`，使用 PHPUnit。涵蓋純量型別轉換、巢狀物件陣列結構、null 穿透及例外情境。專案無 `phpunit.xml`，執行時須明確指定測試檔路徑。
