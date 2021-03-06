<?php

namespace Tests\FrameworkBundle\Unit\Component\Transformers;

use PHPUnit\Framework\TestCase;
use Shopsys\FrameworkBundle\Form\Transformers\ScriptPlacementToBooleanTransformer;
use Shopsys\FrameworkBundle\Model\Script\Script;
use stdClass;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ScriptPlacementToBooleanTransformerTest extends TestCase
{
    public function testTransform()
    {
        $allPagesPlacement = Script::PLACEMENT_ALL_PAGES;
        $orderPagePlacement = Script::PLACEMENT_ORDER_SENT_PAGE;
        $transformer = new ScriptPlacementToBooleanTransformer();

        $this->assertFalse($transformer->transform($allPagesPlacement));
        $this->assertTrue($transformer->transform($orderPagePlacement));
    }

    /**
     * @dataProvider reverseTransformDataProvider
     * @param mixed $scriptHasOrderPlacement
     * @param mixed $scriptPlacement
     */
    public function testReverseTransform($scriptHasOrderPlacement, $scriptPlacement)
    {
        $transformer = new ScriptPlacementToBooleanTransformer();

        $this->assertSame($scriptPlacement, $transformer->reverseTransform($scriptHasOrderPlacement));
    }

    /**
     * @dataProvider reverseTransformExceptionDataProvider
     * @param mixed $param
     */
    public function testReverseTransformException($param)
    {
        $transformer = new ScriptPlacementToBooleanTransformer();

        $this->expectException(TransformationFailedException::class);
        $transformer->reverseTransform($param);
    }

    public function reverseTransformDataProvider()
    {
        return [
            [true, Script::PLACEMENT_ORDER_SENT_PAGE],
            [false, Script::PLACEMENT_ALL_PAGES],
        ];
    }

    public function reverseTransformExceptionDataProvider()
    {
        return [
            ['string'],
            [456],
            [[]],
            [new stdClass()],
        ];
    }
}
