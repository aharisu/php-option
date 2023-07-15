<?php

namespace Tests\Unit;

use Exception;
use PHPUnit\Framework\TestCase;

class OptionTest extends TestCase
{
    public function testEqualsSomeSome()
    {
        $this->assertTrue(some(1)->equals(some(1)));
        $this->assertFalse(some(1)->equals(some(2)));

        //following are type-level errors in PHPStan
        $this->assertFalse(some(1)->equals(some('1'))); //@phpstan-ignore-line
        $this->assertFalse(some(1)->equals(some(1.0))); //@phpstan-ignore-line
    }

    public function testEqualsSomeNone()
    {
        $this->assertFalse(some(1)->equals(none()));
        $this->assertFalse(none()->equals(some(1)));
    }

    public function testEqualsNoneNone()
    {
        $this->assertTrue(none()->equals(none()));
    }

    public function testEqualsSomeNoWrappedValue()
    {
        $this->assertTrue(some(1)->equals(1));
        $this->assertFalse(some(1)->equals(2));

        //following are type-level errors in PHPStan
        $this->assertFalse(some(1)->equals('1')); //@phpstan-ignore-line
        $this->assertFalse(some(1)->equals(1.0)); //@phpstan-ignore-line
    }

    public function testToOption()
    {
        $x = toOption(1);
        $this->assertTrue($x->isSome());
        $this->assertFalse($x->isNone());

        $x = toOption(null);
        $this->assertFalse($x->isSome());
        $this->assertTrue($x->isNone());

        $x = toOption(null, 1);
        $this->assertTrue($x->isSome());
        $this->assertFalse($x->isNone());
    }

    public function testSomeUnwrap()
    {
        $x = some(1);
        $a = $x->unwrap();
        $this->assertSame(1, $a);
    }

    public function testNoneUnwrap()
    {
        $this->expectException(Exception::class);

        $x = none();
        $a = $x->unwrap();
    }

    public function testNoneUnwrapUnchecked()
    {
        $this->expectException(Exception::class);

        $x = none();
        $a = $x->unwrapUnchecked();
    }

    public function testTryUnwrap()
    {
        $x = some(1);
        if (null !== $a = $x->tryUnwrap()) {
            $result = 'same';
        } else {
            $result = 'none';
        }
        $this->assertSame('same', $result);

        $x = none();
        if (null !== $a = $x->tryUnwrap()) {
            $result = 'same';
        } else {
            $result = 'none';
        }
        $this->assertSame('none', $result);
    }

    public function testSomeThen()
    {
        $x = some(1);
        $result = 'none';
        $x->someThen(function ($a) use (&$result) {
            $result = 'some';
        });
        $this->assertSame('some', $result);

        $x = none();
        $result = 'none';
        $x->someThen(function ($a) use (&$result) {
            $result = 'some';
        });
        $this->assertSame('none', $result);
    }

    public function testSomeForEach()
    {
        $a = some(1);
        $loopCount = 0;
        foreach ($a as $v) {
            $loopCount += 1;
        }
        $this->assertSame(1, $loopCount);
    }

    public function testNoneForEach()
    {
        $a = none();
        $loopCount = 0;
        foreach ($a as $v) {
            $loopCount += 1;
        }
        $this->assertSame(0, $loopCount);
    }

    public function testUnwrapOr()
    {
        $this->assertSame('car', some('car')->unwrapOr('bike'));
        $this->assertSame('bike', none()->unwrapOr('bike'));
    }

    public function testUnwrapOrNull()
    {
        $this->assertSame('car', some('car')->unwrapOrNull());
        $this->assertNull(none()->unwrapOrNull());
    }

    public function testUnwrapOrElse()
    {
        $k = 10;
        $func = function () use ($k) {
            return 2 * $k;
        };

        $this->assertSame(4, some(4)->unwrapOrElse($func));
        $this->assertSame(20, none()->unwrapOrElse($func));
    }

    public function testMap()
    {
        $func = function ($str) {
            return mb_strlen($str);
        };

        $maybeSomeString = some('Hello, World!');
        $maybeSomeLen = $maybeSomeString->map($func);

        $maybeNoneLen = none()->map($func);

        $this->assertTrue(some(13)->equals($maybeSomeLen));
        $this->assertTrue(none()->equals($maybeNoneLen));
    }

    public function testMapOr()
    {
        $func = function ($v) {
            return mb_strlen($v);
        };

        $x = some('foo');
        $this->assertSame(3, $x->mapOr(42, $func));

        $x = none();
        $this->assertSame(42, $x->mapOr(42, $func));
    }

    public function testMapOrElse()
    {
        $k = 21;
        $funcDefault = function () use ($k) {
            return 2 * $k;
        };
        $funcElse = function ($v) {
            return mb_strlen($v);
        };

        $x = some('foo');
        $this->assertSame(3, $x->mapOrElse($funcDefault, $funcElse));

        $x = none();
        $this->assertSame(42, $x->mapOrElse($funcDefault, $funcElse));
    }

    public function testAnd()
    {
        $x = some(2);
        $y = none();
        $this->assertTrue(none()->equals($x->and($y)));

        $x = none();
        $y = some('foo');
        $this->assertTrue(none()->equals($x->and($y)));

        $x = some(2);
        $y = some('foo');
        $this->assertTrue(some('foo')->equals($x->and($y)));

        $x = none();
        $y = none();
        $this->assertTrue(none()->equals($x->and($y)));
    }

    public function testAndThen()
    {
        $then = function ($x) {
            return $x % 2 === 0 ? some($x * 2) : none();
        };
        $this->assertTrue(some(4)->equals(some(2)->andThen($then)));
        $this->assertTrue(none()->equals(some(3)->andThen($then)));
        $this->assertTrue(none()->equals(none()->andThen($then)));
    }

    public function testOr()
    {
        $x = some(2);
        $y = none();
        $this->assertTrue(some(2)->equals($x->or($y)));

        $x = none();
        $y = some(100);
        $this->assertTrue(some(100)->equals($x->or($y)));

        $x = some(2);
        $y = some(100);
        $this->assertTrue(some(2)->equals($x->or($y)));

        $x = none();
        $y = none();
        $this->assertTrue(none()->equals($x->or($y)));
    }

    public function testOrElse()
    {
        $nobody = function () {
            return none();
        };
        $vikings = function () {
            return some('vikings');
        };

        $this->assertTrue(some('barbarians')->equals(some('barbarians')->orElse($vikings)));
        $this->assertTrue(some('vikings')->equals(none()->orElse($vikings)));
        $this->assertTrue(none()->equals(none()->orElse($nobody)));
    }

    public function testXor()
    {
        $x = some(2);
        $y = none();
        $this->assertTrue(some(2)->equals($x->xor($y)));

        $x = none();
        $y = some(3);
        $this->assertTrue(some(3)->equals($x->xor($y)));

        $x = some(2);
        $y = some(3);
        $this->assertTrue(none()->equals($x->xor($y)));

        $x = none();
        $y = none();
        $this->assertTrue(none()->equals($x->xor($y)));
    }

    public function testFilter()
    {
        $isEven = function ($n) {
            return $n % 2 === 0;
        };

        $this->assertTrue(none()->equals(none()->filter($isEven)));
        $this->assertTrue(none()->equals(some(3)->filter($isEven)));
        $this->assertTrue(some(4)->equals(some(4)->filter($isEven)));
    }
}
