<?php

it('if the driver is equal to default', function () {
    /** @var \MichaelNabil230\Setting\SettingManager $instants */
    $instants = setting();

    static::assertSame(config('setting.default'), $instants->getDefaultDriver());
});

it('if the driver is not equal to default', function () {
    config()->set('setting.default', 'another-driver');

    /** @var \MichaelNabil230\Setting\SettingManager $instants */
    $instants = setting();

    $this->assertNotSame('json', $instants->getDefaultDriver());
});

it('value the same', function () {
    setting(['foo' => 'bar'])->save();

    $this->assertSame('bar', setting('foo'));

    setting()->forgetAll();
});

it('key doesn\'t have in setting but equal the default value', function () {
    setting(['foo1' => 'bar'])->save();

    $this->assertSame('bar', setting('foo', 'bar'));

    setting()->forgetAll();
});

it('value does not equal the value in setting', function () {
    setting(['foo' => 'bar'])->save();

    $this->assertNotSame('barz', setting('foo'));

    setting()->forgetAll();
});

it('if all strings are equal', function () {
    $settings = setting(['foo.bar' => 'baz'])->save();

    $this->assertEquals($settings, setting()->all());

    setting()->forgetAll();
});

it('forget key', function () {
    setting(['foo' => 'bar'])->save();

    $this->assertTrue(setting()->forget('foo'));

    setting()->forgetAll();
});

it('doesn\'t forget key', function () {
    setting(['foo' => 'bar'])->save();

    $this->assertFalse(setting()->forget('bar'));

    setting()->forgetAll();
});

it('forget all keys', function () {
    setting(['foo' => 'bar'])->save();

    $this->assertTrue(setting()->forgetAll());

    setting()->forgetAll();
});

it('flip value', function () {
    setting(['foo' => false])->save();

    setting()->flip('foo')->save();

    $this->assertTrue(setting('foo'));

    setting()->forgetAll();
});

it('test enable', function () {
    setting(['foo' => false])->save();

    setting()->enable('foo')->save();

    $this->assertTrue(setting('foo'));

    setting()->forgetAll();
});

it('test disable', function () {
    setting(['foo' => true])->save();

    setting()->disable('foo')->save();

    $this->assertFalse(setting('foo'));

    setting()->forgetAll();
});
