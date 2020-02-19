<?php

use PHPUnit\Framework\TestCase;
use fishingboy\type_converter\Type_Converter;

class TypeConvertTest extends TestCase
{
    public function test_convert_to_int()
    {
        $converter = new Type_Converter('{"a" : "int"}');
        $response = $converter->convert(["a" => "123"]);
        $this->assertEquals(123, $response->a);
        $this->assertIsInt($response->a);

        $response = $converter->convert(["a" => "123.456"]);
        $this->assertEquals(123, $response->a);
        $this->assertIsInt($response->a);

        $response = $converter->convert(["a" => "abc"]);
        $this->assertEquals(0, $response->a);
        $this->assertIsInt($response->a);
    }

    public function test_convert_to_float()
    {
        $converter = new Type_Converter('{"a" : "float"}');
        $response = $converter->convert(["a" => "123"]);
        $this->assertEquals(123, $response->a);
        $this->assertIsFloat($response->a);

        $response = $converter->convert(["a" => "123.456"]);
        $this->assertEquals(123.456, $response->a);
        $this->assertIsFloat($response->a);
    }

    public function test_convert_to_bool()
    {
        $converter = new Type_Converter('{"a" : "bool"}');
        $response = $converter->convert(["a" => "123"]);
        $this->assertTrue($response->a);
        $this->assertIsBool($response->a);

        $response = $converter->convert(["a" => "abc"]);
        $this->assertTrue($response->a);
        $this->assertIsBool($response->a);

        $response = $converter->convert(["a" => "0"]);
        $this->assertFalse($response->a);
        $this->assertIsBool($response->a);
    }

    public function test_convert_to_str()
    {
        $converter = new Type_Converter('{"a" : "str"}');
        $response = $converter->convert(["a" => 123]);
        $this->assertEquals("123", $response->a);
        $this->assertIsString($response->a);

        $response = $converter->convert(["a" => true]);
        $this->assertEquals("1", $response->a);
        $this->assertIsString($response->a);
    }

    public function test_convert_to_object_array()
    {
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
        $this->assertIsObject($response);
        $this->assertIsString($response->users[0]->name);
        $this->assertIsInt($response->users[0]->age);
        $this->assertIsString($response->users[1]->name);
        $this->assertNull($response->users[1]->age);
        $this->assertIsString($response->users[2]->name);
        $this->assertIsInt($response->users[2]->age);
    }

    public function test_null_convert_object_array()
    {
        $converter = new Type_Converter('{"users":[{"name":"str","age":"int"}]}');
        $response = $converter->convert(null);
        $this->assertNull($response);
    }

    public function test_str_convert_to_object_should_be_fail()
    {
        $converter = new Type_Converter('{"users":[{"name":"str","age":"int"}]}');

        $fail = false;
        try {
            $response = $converter->convert([
                "users" => ["name" => "leo", "age" => "123"],
            ]);
        } catch (Exception $e) {
            $fail = true;
            $this->assertEquals("Can't convert string to object !!", $e->getMessage());
        }
        $this->assertTrue($fail);
    }
}