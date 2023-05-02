<?php

namespace Chomsky\Tests\Validation;

use Chomsky\Validation\Exceptions\RuleParseException;
use Chomsky\Validation\Rules\EmailRule;
use Chomsky\Validation\Rules\LessThanRule;
use Chomsky\Validation\Rules\NumberRule;
use Chomsky\Validation\Rules\RequiredRule;
use Chomsky\Validation\Rules\RequiredWhenRule;
use Chomsky\Validation\Rules\RequiredWithRule;
use PHPUnit\Framework\TestCase;

class ValidationRulesTest extends TestCase
{
    public function emails()
    {
        return [
            ["test@test.com", true],
            ["antonio@mastermind.ac", true],
            ["test@testcom", false],
            ["test@test.", false],
            ["antonio@", false],
            ["antonio@.", false],
            ["antonio", false],
            ["@", false],
            ["", false],
            [null, false],
            [4, false],
        ];
    }

    /**
     * @dataProvider emails
     */
    public function test_email($email, $expected)
    {
        $data = ['email' => $email];
        $rule = new EmailRule();
        $this->assertEquals($expected, $rule->isValid('email', $data));
    }

    public function requiredData()
    {
        return [
            ["", false],
            [null, false],
            [5, true],
            ["test", true],
        ];
    }

    /**
     * @dataProvider requiredData
     */
    public function test_required($value, $expected)
    {
        $data = ['test' => $value];
        $rule = new RequiredRule();
        $this->assertEquals($expected, $rule->isValid('test', $data));
    }

    public function test_required_with()
    {
        $rule = new RequiredWithRule('other');
        $data = ['other' => 10, 'test' => 5];
        $this->assertTrue($rule->isValid('test', $data));
        $data = ['other' => 10];
        $this->assertFalse($rule->isValid('test', $data));
    }

    public function lessThanData()
    {
        return [
            [5, 5, false],
            [5, 6, false],
            [5, 3, true],
            [5, null, false],
            [5, "", false],
            [5, "test", false],
        ];
    }

    /**
     * @dataProvider lessThanData
     */
    public function test_less_than($value, $check, $expected)
    {
        $rule = new LessThanRule($value);
        $data = ["test" => $check];
        $this->assertEquals($expected, $rule->isValid("test", $data));
    }

    public function numbers()
    {
        return [
            [0, true],
            [1, true],
            [1.5, true],
            [-1, true],
            [-1.5, true],
            ["0", true],
            ["1", true],
            ["1.5", true],
            ["-1", true],
            ["-1.5", true],
            ["test", false],
            ["1test", false],
            ["-5test", false],
            ["", false],
            [null, false],
        ];
    }

    /**
     * @dataProvider numbers
     */
    public function test_number($n, $expected)
    {
        $rule = new NumberRule();
        $data = ["test" => $n];
        $this->assertEquals($expected, $rule->isValid("test", $data));
    }

    public function requiredWhenData()
    {
        return [
            ["other", "=", "value", ["other" => "value"], "test", false],
            ["other", "=", "value", ["other" => "value", "test" => 1], "test", true],
            ["other", "=", "value", ["other" => "not value"], "test", true],
            ["other", ">", 5, ["other" => 1], "test", true],
            ["other", ">", 5, ["other" => 6], "test", false],
            ["other", ">", 5, ["other" => 6, "test" => 1], "test", true],
        ];
    }

    /**
     * @dataProvider requiredWhenData
     */
    public function test_required_when($other, $operator, $compareWith, $data, $field, $expected)
    {
        $rule = new RequiredWhenRule($other, $operator, $compareWith);
        $this->assertEquals($expected, $rule->isValid($field, $data));
    }

    public function test_required_when_throws_parse_rule_exception_when_operator_is_invalid()
    {
        $rule = new RequiredWhenRule("other", "|||", "test");
        $data = ["other" => 5, "test" => 1];
        $this->expectException(RuleParseException::class);
        $rule->isValid("test", $data);
    }
}
