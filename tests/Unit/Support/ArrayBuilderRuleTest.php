<?php

namespace KY\AdminPanel\Tests\Unit\Support;

use KY\AdminPanel\Support\ArrayBuilderRule;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\Support\ArrayBuilderRule
 */
class ArrayBuilderRuleTest extends TestCase
{
    /**
     * @covers ::required
     */
    public function test_required_sets_required_flag_and_default_message(): void
    {
        $rule = new ArrayBuilderRule();

        $this->assertSame($rule, $rule->required());

        $this->assertSame([
            'required' => true,
            'message' => 'Обязательное поле',
            'trigger' => 'blur',
        ], $rule->toArray());
    }

    /**
     * @covers ::trigger
     */
    public function test_trigger_sets_trigger(): void
    {
        $rule = (new ArrayBuilderRule())->trigger('change');

        $this->assertSame('change', $rule->toArray()['trigger']);
    }

    /**
     * @covers ::toArray
     */
    public function test_to_array_returns_rule_state(): void
    {
        $rule = (new ArrayBuilderRule())->required(false)->trigger('change');

        $this->assertSame([
            'required' => false,
            'message' => 'Обязательное поле',
            'trigger' => 'change',
        ], $rule->toArray());
    }
}
