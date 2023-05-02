<?php

namespace Chomsky\Tests\Validation;

use Chomsky\Validation\Exceptions\RuleParseException;
use Chomsky\Validation\Exceptions\UnknownRuleException;
use Chomsky\Validation\Rule;
use Chomsky\Validation\Rules\EmailRule;
use Chomsky\Validation\Rules\LessThanRule;
use Chomsky\Validation\Rules\NumberRule;
use Chomsky\Validation\Rules\RequiredRule;
use Chomsky\Validation\Rules\RequiredWhenRule;
use Chomsky\Validation\Rules\RequiredWithRule;
use PHPUnit\Framework\TestCase;

class RuleParseTest extends TestCase
{
    protected function setUp(): void
    {
        Rule::loadDefaultRules();
    }

    public function basicRules()
    {
        return [
            [EmailRule::class, "email"],
            [RequiredRule::class, "required"],
            [NumberRule::class, "number"],
        ];
    }

    /**
     * @dataProvider basicRules
     */
    public function test_parse_basic_rules($class, $name)
    {
        $this->assertInstanceOf($class, Rule::from($name));
    }

    public function test_parsing_unknown_rules_throws_unkown_rule_exception()
    {
        $this->expectException(UnknownRuleException::class);
        Rule::from("unknown");
    }

    public function rulesWithParameters()
    {
        return [
            [new LessThanRule(5), "less_than:5"],
            [new RequiredWithRule("other"), "required_with:other"],
            [new RequiredWhenRule("other", "=", "test"), "required_when:other,=,test"],
        ];
    }

    /**
     * @dataProvider rulesWithParameters
     */
    public function test_parse_rules_with_parameters($expected, $rule)
    {
        $this->assertEquals($expected, Rule::from($rule));
    }

    public function rulesWithParametersWithError()
    {
        return [
            ["less_than"],
            ["less_than:"],
            ["required_with:"],
            ["required_when"],
            ["required_when:"],
            ["required_when:other"],
            ["required_when:other,"],
            ["required_when:other,="],
            ["required_when:other,=,"],
        ];
    }

    /**
     * @dataProvider rulesWithParametersWithError
     */
    public function test_parsing_rule_with_parameters_without_passing_correct_parameters_throws_rule_parse_exception($rule)
    {
        $this->expectException(RuleParseException::class);
        Rule::from($rule);
    }
}
