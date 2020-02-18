<?php
namespace fishingboy\type_converter;

/**
 * API Output type converter
 * @author Leo.Kuo <fishingboy@gmail.com>
 */
class Type_Converter
{
    private $format = null;

    public function __construct($format)
    {
        $this->format = json_decode($format);
    }

    /**
     * 轉換型別
     * @param $value
     * @param mixed $format
     * @return mixed
     * @throws \Exception
     */
    public function convert($value, $format = null)
    {
        $format = isset($format) ? $format : $this->format;

        if ($value === null) {
            return null;
        }

        if (is_object($format)) {
            // 物件
            if (is_array($value)) {
                $value = (object) $value;
            } else {
                $type = gettype($value);
                throw new \Exception("Can't convert {$type} to object !!");
            }

            foreach ($format as $key => $sub_format) {
                if (property_exists($value, $key)) {
                    $value->$key = $this->convert($value->$key, $sub_format);
                } else {
                    $value->$key = null;
                }
            }
            return $value;
        } else if (is_array($format)) {
            // 陣列
            if (! is_array($value)) {
                return null;
            }

            $sub_format = $format[0];
            foreach ($value as $i => $val) {
                $value[$i] = $this->convert($value[$i], $sub_format);
            }
            return $value;
        } else {
            // 值
            switch ($format) {
                // 整數
                case "integer":
                    return intval($value);

                // 浮點數
                case "float":
                    return floatval($value);

                // 字串
                case "string":
                    return strval($value);

                // 布林
                case "boolean":
                    return boolval($value);

                // 未定義
                default:
                    return $value;
            }
        }
    }
}
