<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class OptionTest extends TestCase
{
    public function testEqualsSomeSome()
    {
        $this->assertTrue(some(1)->equals(some(1)));
        $this->assertFalse(some(1)->equals(some(2)));

        //以下の二つはPHPStanレベルで型エラーになる
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

    public function testSomeMatchExpression()
    {
        $a = some(1);
        $result = match ($match = $a->tryUnwrap()) {
            null => 'none',
            default => 'ok:' . $match,
        };
        $this->assertSame('ok:1', $result);
    }

    public function testEmptyStringMatchExpression()
    {
        $a = some('');
        $result = match ($match = $a->tryUnwrap()) {
            null => 'none',
            default => 'ok:' . $match,
        };
        $this->assertSame('ok:', $result);
    }

    public function testZeroStringMatchExpression()
    {
        $a = some('0');
        $result = match ($match = $a->tryUnwrap()) {
            null => 'none',
            default => 'ok:' . $match,
        };
        $this->assertSame('ok:0', $result);
    }

    public function testNoneMatchExpression()
    {
        $a = none();
        $result = match ($match = $a->tryUnwrap()) {
            null => 'none',
            default => 'ok:' . $match,
        };
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

    public function testUnwrapOrElse()
    {
        $k = 10;
        $this->assertSame(4, some(4)->unwrapOrElse(fn () => 2 * $k));
        $this->assertSame(20, none()->unwrapOrElse(fn () => 2 * $k));
    }

    public function testMap()
    {
        $maybeSomeString = some('Hello, World!');
        $maybeSomeLen = $maybeSomeString->map(fn ($str) => mb_strlen($str));

        $maybeNoneLen = none()->map(fn ($str) => mb_strlen($str));

        $this->assertTrue(some(13)->equals($maybeSomeLen));
        $this->assertTrue(none()->equals($maybeNoneLen));
    }

    public function testMapOr()
    {
        $x = some('foo');
        $this->assertSame(3, $x->mapOr(42, fn ($v) => mb_strlen($v)));

        $x = none();
        $this->assertSame(42, $x->mapOr(42, fn ($v) => mb_strlen($v)));
    }

    public function testMapOrElse()
    {
        $k = 21;

        $x = some('foo');
        $this->assertSame(3, $x->mapOrElse(fn () => 2 * $k, fn ($v) => mb_strlen($v)));

        $x = none();
        $this->assertSame(42, $x->mapOrElse(fn () => 2 * $k, fn ($v) => mb_strlen($v)));
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
        $then = fn ($x) => $x % 2 === 0 ? some($x * 2) : none();
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
        $nobody = fn () => none();
        $vikings = fn () => some('vikings');

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
        $isEven = fn ($n) => $n % 2 === 0;

        $this->assertTrue(none()->equals(none()->filter($isEven)));
        $this->assertTrue(none()->equals(none()->filter($isEven)));
        $this->assertTrue(some(4)->equals(some(4)->filter($isEven)));
    }
}
