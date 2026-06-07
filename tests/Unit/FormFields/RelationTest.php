<?php

namespace KY\AdminPanel\Tests\Unit\FormFields;

use KY\AdminPanel\DataTables\Filters\SelectFilter;
use KY\AdminPanel\FormFields\Relation;
use KY\AdminPanel\Models\Role;
use KY\AdminPanel\Models\User;
use KY\AdminPanel\Tests\TestCase;

/**
 * @coversDefaultClass \KY\AdminPanel\FormFields\Relation
 */
class RelationTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function test_construct_sets_select_filter_without_handler(): void
    {
        $filter = $this->getNonPublicProperty(new Relation(), 'filter');

        $this->assertInstanceOf(SelectFilter::class, $filter);
        $this->assertFalse($filter->hasHandler());
    }

    /**
     * @covers ::getFilter
     */
    public function test_get_filter_sets_relation_template_and_default_handler(): void
    {
        $filter = Relation::make('role_id')->getFilter();

        $this->assertSame('adminpanel::datatables.filters.relation', $filter->get('template'));
        $this->assertTrue($filter->hasHandler());
    }

    /**
     * @covers ::belongsTo
     * @covers ::model
     * @covers ::relatedModel
     * @covers ::type
     * @covers ::column
     * @covers ::isBelongsTo
     */
    public function test_belongs_to_configures_belongs_to_relation(): void
    {
        $field = Relation::make('role_id')->belongsTo(new User(), new Role());

        $this->assertTrue($field->isBelongsTo());
        $this->assertSame(User::class, $field->get('model'));
        $this->assertSame(Role::class, $field->get('relatedModel'));
        $this->assertSame('roles', $field->get('table'));
        $this->assertSame('id', $field->get('key'));
        $this->assertSame('role_id', $field->get('column'));
    }

    /**
     * @covers ::hasOne
     * @covers ::isHasOne
     */
    public function test_has_one_configures_has_one_relation(): void
    {
        $field = Relation::make('role_id')->hasOne(new User(), new Role());

        $this->assertTrue($field->isHasOne());
    }

    /**
     * @covers ::hasMany
     * @covers ::isHasMany
     */
    public function test_has_many_configures_has_many_relation(): void
    {
        $field = Relation::make('role_id')->hasMany(new User(), new Role());

        $this->assertTrue($field->isHasMany());
    }

    /**
     * @covers ::belongsToMany
     * @covers ::makePivotTable
     * @covers ::pivotTable
     * @covers ::isBelongsToMany
     * @covers ::needSave
     */
    public function test_belongs_to_many_configures_pivot_relation(): void
    {
        $field = Relation::make('roles')->belongsToMany(new User(), new Role());

        $this->assertTrue($field->isBelongsToMany());
        $this->assertSame('role_user', $field->get('pivotTable'));
        $this->assertSame('id', $field->get('column'));
        $this->assertSame('id', $field->get('key'));
        $this->assertFalse($field->needSave());
    }

    /**
     * @covers ::displayedField
     */
    public function test_displayed_field_sets_displayed_field_attribute(): void
    {
        $field = new Relation();

        $this->assertSame($field, $field->displayedField('title'));
        $this->assertSame('title', $field->get('displayedField'));
    }

    /**
     * @covers ::required
     */
    public function test_required_sets_required_attribute(): void
    {
        $field = new Relation();

        $this->assertSame($field, $field->required());
        $this->assertTrue($field->get('required'));
    }

    /**
     * @covers ::getColumnOrderable
     */
    public function test_get_column_orderable_defaults_to_belongs_to_state(): void
    {
        $this->assertFalse((new Relation())->getColumnOrderable());
        $this->assertTrue(Relation::make('role_id')->belongsTo(new User(), new Role())->getColumnOrderable());
    }
}
