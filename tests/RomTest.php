<?php

use ALttP\Item;
use ALttP\Rom;
use ALttP\World;
use ALttP\Support\ItemCollection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RomTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->rom = new Rom;
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->rom);
    }

    public function testSaveBuild()
    {
        $build = Rom::saveBuild([]);

        $this->assertNotNull($build);
    }

    public function testConstructorThrowsWhenSourceNotReadable()
    {
        $this->expectException(\Exception::class);

        $rom = new Rom('thisfileisnotreable');
    }

    public function testConstructorReadsFile()
    {
        $rom = new Rom(base_path('tests/samples/testrom.sfc'));

        $this->assertEquals([1, 2, 3], $rom->read(1, 3));
    }

    public function testResize()
    {
        $rom = new Rom(base_path('tests/samples/testrom.sfc'));

        $rom->resize(1036);

        $rom->save('test_rom.sfc');

        $this->assertEquals(1036, filesize('test_rom.sfc'));

        unlink('test_rom.sfc');
    }

    public function testCheckMD5WithNoBaseFile()
    {
        $this->assertFalse($this->rom->checkMD5());
    }

    public function testUpdateChecksum()
    {
        $this->rom->resize();

        $this->rom->write(0, pack('C*', 0, 1, 2, 3, 4, 5, 6, 7));

        $this->rom->updateChecksum();

        $this->assertEquals([229, 253, 26, 2], $this->rom->read(0x7FDC, 4));
    }

    public function testSetSubstitutionsWithEmpty()
    {
        $this->rom->setSubstitutions();

        $this->assertEquals([0xFF, 0xFF, 0xFF, 0xFF], $this->rom->read(0x184000, 4));
    }

    public function testSetSubstitutionsWithLamp()
    {
        $this->rom->setSubstitutions([
            0x12, 0x01, 0x35, 0xFF
        ]);

        $this->assertEquals([0x12, 0x01, 0x35, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF], $this->rom->read(0x184000, 8));
    }

    /**
     * @dataProvider startingEquipmentDataProvider
     *
     * @return void
     */
    public function testSetStartingEquipment(array $result, array $items)
    {
        $world = World::factory();
        $object_items = array_map(function ($item_name) use ($world) {
            return Item::get($item_name, $world);
        }, $items);

        $item_collection = new ItemCollection($object_items);

        $this->rom->setStartingEquipment($item_collection);

        foreach ($result as $offset => $bytes) {
            $this->assertEquals($bytes, $this->rom->read($offset));
        }
    }

    public function startingEquipmentDataProvider(): array
    {
        return [
            [
                [0x183019 => 0x01, 0x271BF => 0x01],
                ['L1Sword'],
            ],
            [
                [
                    0x183019 => 0x01, 0x271BF => 0x01,
                    0x18301A => 0x01, 0x271C0 => 0x01,
                ],
                ['L1SwordAndShield'],
            ],
            [
                [0x183019 => 0x02, 0x271BF => 0x02],
                ['L2Sword'],
            ],
            [
                [0x183019 => 0x02, 0x271BF => 0x02],
                ['MasterSword'],
            ],
            [
                [0x183019 => 0x03, 0x271BF => 0x03],
                ['L3Sword'],
            ],
            [
                [0x183019 => 0x04, 0x271BF => 0x04],
                ['L4Sword'],
            ],
            [
                [0x18301A => 0x01, 0x271C0 => 0x01],
                ['BlueShield'],
            ],
            [
                [0x18301A => 0x02, 0x271C0 => 0x02],
                ['RedShield'],
            ],
            [
                [0x18301A => 0x03, 0x271C0 => 0x03],
                ['MirrorShield'],
            ],
            [
                [0x183005 => 0x01, 0x271AB => 0x01],
                ['FireRod'],
            ],
            [
                [0x183006 => 0x01, 0x271AC => 0x01],
                ['IceRod'],
            ],
            [
                [0x18300B => 0x01, 0x271B1 => 0x01],
                ['Hammer'],
            ],
            [
                [0x183002 => 0x01, 0x271A8 => 0x01],
                ['Hookshot'],
            ],
            [
                [0x183000 => 0x01, 0x271A6 => 0x01, 0x18304E => 0x80],
                ['Bow'],
            ],
            [
                [0x183000 => 0x02, 0x271A6 => 0x02, 0x18304E => 0x80],
                ['BowAndArrows'],
            ],
            [
                [0x18304E => 0x40],
                ['SilverArrowUpgrade'],
            ],
            [
                [0x183000 => 0x04, 0x271A6 => 0x04, 0x18304E => 0xC0],
                ['BowAndSilverArrows'],
            ],
            [
                [0x183000 => 0x01, 0x271A6 => 0x01, 0x18304E => 0xC0],
                ['Bow', 'SilverArrowUpgrade'],
            ],
            [
                [0x183001 => 0x01, 0x271A7 => 0x01, 0x18304C => 0x80],
                ['Boomerang'],
            ],
            [
                [0x183001 => 0x02, 0x271A7 => 0x02, 0x18304C => 0x40],
                ['RedBoomerang'],
            ],
            [
                [0x183001 => 0x02, 0x271A7 => 0x02, 0x18304C => 0xC0],
                ['Boomerang', 'RedBoomerang'],
            ],
            [
                [0x183004 => 0x01, 0x271AA => 0x01, 0x18304C => 0x28],
                ['Mushroom'],
            ],
            [
                [0x183004 => 0x02, 0x271AA => 0x02, 0x18304C => 0x10],
                ['Powder'],
            ],
            [
                [0x183004 => 0x02, 0x271AA => 0x02, 0x18304C => 0x38],
                ['Mushroom', 'Powder'],
            ],
            [
                [0x183007 => 0x01, 0x271AD => 0x01],
                ['Bombos'],
            ],
            [
                [0x183008 => 0x01, 0x271AE => 0x01],
                ['Ether'],
            ],
            [
                [0x183009 => 0x01, 0x271AF => 0x01],
                ['Quake'],
            ],
            [
                [0x18300A => 0x01, 0x271B0 => 0x01],
                ['Lamp'],
            ],
            [
                [0x18300C => 0x01, 0x271B2 => 0x01, 0x18304C => 0x04],
                ['Shovel'],
            ],
            [
                [0x18300C => 0x02, 0x271B2 => 0x02, 0x18304C => 0x02],
                ['OcarinaInactive'],
            ],
            [
                [0x18300C => 0x03, 0x271B2 => 0x03, 0x18304C => 0x01],
                ['OcarinaActive'],
            ],
            [
                [0x18300C => 0x02, 0x271B2 => 0x02, 0x18304C => 0x06],
                ['Shovel', 'OcarinaInactive'],
            ],
            [
                [0x183010 => 0x01, 0x271B6 => 0x01],
                ['CaneOfSomaria'],
            ],
            [
                [
                    0x18300F => 0x01, 0x271B5 => 0x01,
                    0x18301C => 0x02, 0x271C2 => 0x02,
                ],
                ['Bottle'],
            ],
            [
                [
                    0x18300F => 0x01, 0x271B5 => 0x01,
                    0x18301C => 0x03, 0x271C2 => 0x03,
                ],
                ['BottleWithRedPotion'],
            ],
            [
                [
                    0x18300F => 0x01, 0x271B5 => 0x01,
                    0x18301C => 0x04, 0x271C2 => 0x04,
                ],
                ['BottleWithGreenPotion'],
            ],
            [
                [
                    0x18300F => 0x01, 0x271B5 => 0x01,
                    0x18301C => 0x05, 0x271C2 => 0x05,
                ],
                ['BottleWithBluePotion'],
            ],
            [
                [
                    0x18300F => 0x01, 0x271B5 => 0x01,
                    0x18301C => 0x07, 0x271C2 => 0x07,
                ],
                ['BottleWithBee'],
            ],
            [
                [
                    0x18300F => 0x01, 0x271B5 => 0x01,
                    0x18301C => 0x06, 0x271C2 => 0x06,
                ],
                ['BottleWithFairy'],
            ],
            [
                [
                    0x18300F => 0x01, 0x271B5 => 0x01,
                    0x18301C => 0x08, 0x271C2 => 0x08,
                ],
                ['BottleWithGoldBee'],
            ],
            [
                [
                    0x18300F => 0x02, 0x271B5 => 0x02,
                    0x18301C => 0x02, 0x271C2 => 0x02,
                    0x18301D => 0x03, 0x271C3 => 0x03,
                ],
                ['Bottle', 'BottleWithRedPotion'],
            ],
            [
                [
                    0x18300F => 0x04, 0x271B5 => 0x04,
                    0x18301C => 0x02, 0x271C2 => 0x02,
                    0x18301D => 0x03, 0x271C3 => 0x03,
                    0x18301E => 0x04, 0x271C4 => 0x04,
                    0x18301F => 0x04, 0x271C5 => 0x04,
                ],
                ['Bottle', 'BottleWithRedPotion', 'BottleWithGreenPotion', 'BottleWithGreenPotion', 'BottleWithBee'],
            ],
            [
                [0x183011 => 0x01, 0x271B7 => 0x01],
                ['CaneOfByrna'],
            ],
            [
                [0x183012 => 0x01, 0x271B8 => 0x01],
                ['Cape'],
            ],
            [
                [0x183013 => 0x02, 0x271B9 => 0x02],
                ['MagicMirror'],
            ],
            [
                [0x183014 => 0x01, 0x271BA => 0x01],
                ['PowerGlove'],
            ],
            [
                [0x183014 => 0x02, 0x271BA => 0x02],
                ['TitansMitt'],
            ],
            [
                [0x18300E => 0x01, 0x271B4 => 0x01],
                ['BookOfMudora'],
            ],
            [
                [0x183016 => 0x01, 0x271BC => 0x01, 0x183039 => 0x6A],
                ['Flippers'],
            ],
            [
                [0x183017 => 0x01, 0x271BD => 0x01],
                ['MoonPearl'],
            ],
            [
                [0x18300D => 0x01, 0x271B3 => 0x01],
                ['BugCatchingNet'],
            ],
            [
                [0x18301B => 0x01, 0x271C1 => 0x01],
                ['BlueMail'],
            ],
            [
                [0x18301B => 0x02, 0x271C1 => 0x02],
                ['RedMail'],
            ],
            [
                [0x183003 => 0x01, 0x271A9 => 0x01],
                ['Bomb'],
            ],
            [
                [0x183003 => 0x03, 0x271A9 => 0x03],
                ['ThreeBombs'],
            ],
            [
                [0x183003 => 0x0A, 0x271A9 => 0x0A],
                ['TenBombs'],
            ],
            [
                [0x183003 => 0x63, 0x271A9 => 0x63],
                ['TenBombs', 'TenBombs', 'TenBombs', 'TenBombs', 'TenBombs', 'TenBombs', 'TenBombs', 'TenBombs', 'TenBombs', 'TenBombs'],
            ],
            [
                [0x18303A => 0x02, 0x271E0 => 0x02],
                ['Crystal1'],
            ],
            [
                [0x18303A => 0x10, 0x271E0 => 0x10],
                ['Crystal2'],
            ],
            [
                [0x18303A => 0x40, 0x271E0 => 0x40],
                ['Crystal3'],
            ],
            [
                [0x18303A => 0x20, 0x271E0 => 0x20],
                ['Crystal4'],
            ],
            [
                [0x18303A => 0x04, 0x271E0 => 0x04],
                ['Crystal5'],
            ],
            [
                [0x18303A => 0x01, 0x271E0 => 0x01],
                ['Crystal6'],
            ],
            [
                [0x18303A => 0x08, 0x271E0 => 0x08],
                ['Crystal7'],
            ],
            [
                [0x18303A => 0x12, 0x271E0 => 0x12],
                ['Crystal1', 'Crystal2'],
            ],
        ];
    }

    public function testSetHeartBeepSpeedOff()
    {
        $this->rom->setHeartBeepSpeed('off');

        $this->assertEquals(0x00, $this->rom->read(0x180033));
    }

    public function testSetHeartBeepSpeedNormal()
    {
        $this->rom->setHeartBeepSpeed('normal');

        $this->assertEquals(0x20, $this->rom->read(0x180033));
    }

    public function testSetHeartBeepSpeedHalf()
    {
        $this->rom->setHeartBeepSpeed('half');

        $this->assertEquals(0x40, $this->rom->read(0x180033));
    }

    public function testSetHeartBeepSpeedQuarter()
    {
        $this->rom->setHeartBeepSpeed('quarter');

        $this->assertEquals(0x80, $this->rom->read(0x180033));
    }

    public function testSetHeartBeepSpeedDouble()
    {
        $this->rom->setHeartBeepSpeed('double');

        $this->assertEquals(0x10, $this->rom->read(0x180033));
    }

    public function testSetHeartBeepSpeedUnknownSetsNormal()
    {
        $this->rom->setHeartBeepSpeed('testing');

        $this->assertEquals(0x20, $this->rom->read(0x180033));
    }

    public function testSetRupoor()
    {
        $this->rom->setRupoorValue(40);

        $this->assertEquals([0x28, 0x00], $this->rom->read(0x180036, 2));
    }

    public function testSetRupoorLarge()
    {
        $this->rom->setRupoorValue(999);

        $this->assertEquals([0xE7, 0x03], $this->rom->read(0x180036, 2));
    }

    public function testSetByrnaCaveSpikeDamageDefault()
    {
        $this->rom->setByrnaCaveSpikeDamage();

        $this->assertEquals(0x08, $this->rom->read(0x180168));
    }

    public function testSetByrnaCaveSpikeDamage()
    {
        $this->rom->setByrnaCaveSpikeDamage(0x02);

        $this->assertEquals(0x02, $this->rom->read(0x180168));
    }

    public function testSetClockModeDefault()
    {
        $this->rom->setClockMode();

        $this->assertEquals([0x00, 0x00, 0x00], $this->rom->read(0x180190, 3));
    }

    public function testSetClockModeCountdownStopNoReset()
    {
        $this->rom->setClockMode('countdown-stop');

        $this->assertEquals([0x01, 0x00, 0x00], $this->rom->read(0x180190, 3));
    }

    public function testSetClockModeCountdownContinueNoReset()
    {
        $this->rom->setClockMode('countdown-continue');

        $this->assertEquals([0x01, 0x01, 0x00], $this->rom->read(0x180190, 3));
    }

    public function testSetClockModeStopwatchNoReset()
    {
        $this->rom->setClockMode('stopwatch');

        $this->assertEquals([0x02, 0x01, 0x00], $this->rom->read(0x180190, 3));
    }

    public function testSetClockModeCountdownStopReset()
    {
        $this->rom->setClockMode('countdown-stop', true);

        $this->assertEquals([0x01, 0x00, 0x01], $this->rom->read(0x180190, 3));
    }

    public function testSetClockModeCountdownContinueReset()
    {
        $this->rom->setClockMode('countdown-continue', true);

        $this->assertEquals([0x01, 0x01, 0x01], $this->rom->read(0x180190, 3));
    }

    public function testSetClockModeStopwatchReset()
    {
        $this->rom->setClockMode('stopwatch', true);

        $this->assertEquals([0x02, 0x01, 0x01], $this->rom->read(0x180190, 3));
    }

    public function testSetClockModeCountdownOhko()
    {
        $this->rom->setClockMode('countdown-ohko');

        $this->assertEquals([0x01, 0x02, 0x01], $this->rom->read(0x180190, 3));
    }

    public function testSetClockModeCountdownEnd()
    {
        $this->rom->setClockMode('countdown-end');

        $this->assertEquals([0x01, 0x03, 0x00], $this->rom->read(0x180190, 3));
    }

    public function testSetStartingTime()
    {
        // 5 hours
        $this->rom->setStartingTime(5 * 60 * 60);

        $this->assertEquals([0xC0, 0x7A, 0x10, 0x00], $this->rom->read(0x18020C, 4));
    }

    public function testSetRedClock()
    {
        $this->rom->setRedClock(5 * 60);

        $this->assertEquals([0x50, 0x46, 0x00, 0x00], $this->rom->read(0x180200, 4));
    }

    public function testSetBlueClock()
    {
        $this->rom->setBlueClock(5 * 60);

        $this->assertEquals([0x50, 0x46, 0x00, 0x00], $this->rom->read(0x180204, 4));
    }

    public function testSetGreenClock()
    {
        $this->rom->setGreenClock(5 * 60);

        $this->assertEquals([0x50, 0x46, 0x00, 0x00], $this->rom->read(0x180208, 4));
    }

    public function testSetMaxArrows()
    {
        $this->rom->setMaxArrows(40);

        $this->assertEquals(40, $this->rom->read(0x180035));
    }

    public function testSetDiggingGameRng()
    {
        $this->rom->setDiggingGameRng(40);

        $this->assertEquals(40, $this->rom->read(0x180020));
        $this->assertEquals(40, $this->rom->read(0xEFD95));
    }

    public function testSetMaxBombs()
    {
        $this->rom->setMaxBombs(40);

        $this->assertEquals(40, $this->rom->read(0x180034));
    }

    public function testSetCapacityUpgradeFills()
    {
        $this->rom->setCapacityUpgradeFills([1, 2, 0, 0, 20]);

        $this->assertEquals([1, 2, 0, 0], $this->rom->read(0x180080, 4));
    }

    public function setBottleFills()
    {
        $this->rom->setCapacityUpgradeFills([1, 2, 0, 0, 20]);

        $this->assertEquals([1, 2], $this->rom->read(0x180084, 2));
    }

    public function testSetGoalRequiredCount()
    {
        $this->rom->setGoalRequiredCount(4);

        $this->assertEquals(4, $this->rom->read(0x180167));
    }

    public function testSetGoalIconDefault()
    {
        $this->rom->setGoalIcon('nothing');

        $this->assertEquals([0x0D, 0x28], $this->rom->read(0x180165, 2));
    }

    public function testSetGoalIconTriforce()
    {
        $this->rom->setGoalIcon('triforce');

        $this->assertEquals([0x0E, 0x28], $this->rom->read(0x180165, 2));
    }

    public function testSetGoalIconStar()
    {
        $this->rom->setGoalIcon('star');

        $this->assertEquals([0x0D, 0x28], $this->rom->read(0x180165, 2));
    }

    public function testSetLimitProgressiveSword()
    {
        $this->rom->setLimitProgressiveSword(2, 23);

        $this->assertEquals([2, 23], $this->rom->read(0x180090, 2));
    }

    public function testSetLimitProgressiveShield()
    {
        $this->rom->setLimitProgressiveShield(2, 23);

        $this->assertEquals([2, 23], $this->rom->read(0x180092, 2));
    }

    public function testSetLimitProgressiveArmor()
    {
        $this->rom->setLimitProgressiveArmor(2, 23);

        $this->assertEquals([2, 23], $this->rom->read(0x180094, 2));
    }

    public function testSetLimitBottle()
    {
        $this->rom->setLimitBottle(2, 23);

        $this->assertEquals([2, 23], $this->rom->read(0x180096, 2));
    }

    public function testSetLimitBow()
    {
        $this->rom->setLimitProgressiveBow(2, 23);

        $this->assertEquals([2, 23], $this->rom->read(0x180098, 2));
    }

    public function testSetGanonInvincibleCrystals()
    {
        $this->rom->setGanonInvincible('crystals');

        $this->assertEquals(0x03, $this->rom->read(0x18003E));
    }

    public function testSetGanonInvincibleDungeons()
    {
        $this->rom->setGanonInvincible('dungeons');

        $this->assertEquals(0x02, $this->rom->read(0x18003E));
    }

    public function testSetGanonInvincibleYes()
    {
        $this->rom->setGanonInvincible('yes');

        $this->assertEquals(0x01, $this->rom->read(0x18003E));
    }

    public function testSetGanonInvincibleCustom()
    {
        $this->rom->setGanonInvincible('custom');

        $this->assertEquals(0x04, $this->rom->read(0x18003E));
    }

    public function testSetGanonInvincibleNo()
    {
        $this->rom->setGanonInvincible('no');

        $this->assertEquals(0x00, $this->rom->read(0x18003E));
    }

    public function testSetHeartColorsBlue()
    {
        $this->rom->setHeartColors('blue');

        $this->assertHeartColorSetting('blue');
    }

    public function testSetHeartColorsGreen()
    {
        $this->rom->setHeartColors('green');

        $this->assertHeartColorSetting('green');
    }

    public function testSetHeartColorsYellow()
    {
        $this->rom->setHeartColors('yellow');

        $this->assertHeartColorSetting('yellow');
    }

    public function testSetHeartColorsRed()
    {
        $this->rom->setHeartColors('red');

        $this->assertHeartColorSetting('red');
    }

    public function testSetHeartColorsDefault()
    {
        $this->rom->setHeartColors('some invalid string value');

        $this->assertHeartColorSetting('red');
    }

    private function assertHeartColorSetting($expectedColor)
    {
        switch ($expectedColor) {
            case 'blue':
                $expectedByte = 0x2C;
                $expectedFileByte = 0x0D;
                break;
            case 'green':
                $expectedByte = 0x3C;
                $expectedFileByte = 0x19;
                break;
            case 'yellow':
                $expectedByte = 0x28;
                $expectedFileByte = 0x09;
                break;
            case 'red':
                $expectedByte = 0x24;
                $expectedFileByte = 0x05;
                break;
            default:
                $expectedByte = 0x00;
                $expectedFileByte = 0x00;
        }

        $this->assertEquals($expectedByte, $this->rom->read(0x6FA1E));
        $this->assertEquals($expectedByte, $this->rom->read(0x6FA20));
        $this->assertEquals($expectedByte, $this->rom->read(0x6FA22));
        $this->assertEquals($expectedByte, $this->rom->read(0x6FA24));
        $this->assertEquals($expectedByte, $this->rom->read(0x6FA26));
        $this->assertEquals($expectedByte, $this->rom->read(0x6FA28));
        $this->assertEquals($expectedByte, $this->rom->read(0x6FA2A));
        $this->assertEquals($expectedByte, $this->rom->read(0x6FA2C));
        $this->assertEquals($expectedByte, $this->rom->read(0x6FA2E));
        $this->assertEquals($expectedByte, $this->rom->read(0x6FA30));
        
        $this->assertEquals($expectedFileByte, $this->rom->read(0x65561));
    }

    public function testSetText()
    {
        $this->rom->setText('set_cursor', '1');

        $this->rom->writeText();

        $this->assertEquals(0xA1, $this->rom->read(0xE0001));
    }

    public function testWriteText()
    {
        $this->rom->writeText();

        $this->assertEquals([0xFB, 0xFC, 0x00, 0xF9, 0xFF], $this->rom->read(0xE0000, 5));
    }

    public function testSetCredit()
    {
        $this->rom->setCredit('castle', 'a');

        $this->rom->writeCredits();

        $this->assertEquals(0x1A, $this->rom->read(0x181504));
    }

    public function testWriteCredits()
    {
        $this->rom->writeCredits();

        $this->assertEquals([0x2D, 0x21, 0x1E, 0x9F, 0x2B], $this->rom->read(0x181504, 5));
    }

    public function testSetMenuSpeedInstant()
    {
        $this->rom->setMenuSpeed('instant');

        $this->assertEquals(0xE8, $this->rom->read(0x180048));
        $this->assertEquals(0x20, $this->rom->read(0x6DD9A));
        $this->assertEquals(0x20, $this->rom->read(0x6DF2A));
        $this->assertEquals(0x20, $this->rom->read(0x6E0E9));
    }

    public function testSetMenuSpeedFast()
    {
        $this->rom->setMenuSpeed('fast');

        $this->assertEquals(0x10, $this->rom->read(0x180048));
        $this->assertEquals(0x11, $this->rom->read(0x6DD9A));
        $this->assertEquals(0x12, $this->rom->read(0x6DF2A));
        $this->assertEquals(0x12, $this->rom->read(0x6E0E9));
    }

    public function testSetMenuSpeedNormal()
    {
        $this->rom->setMenuSpeed('normal');

        $this->assertEquals(0x08, $this->rom->read(0x180048));
        $this->assertEquals(0x11, $this->rom->read(0x6DD9A));
        $this->assertEquals(0x12, $this->rom->read(0x6DF2A));
        $this->assertEquals(0x12, $this->rom->read(0x6E0E9));
    }

    public function testSetMenuSpeedSlow()
    {
        $this->rom->setMenuSpeed('slow');

        $this->assertEquals(0x04, $this->rom->read(0x180048));
        $this->assertEquals(0x11, $this->rom->read(0x6DD9A));
        $this->assertEquals(0x12, $this->rom->read(0x6DF2A));
        $this->assertEquals(0x12, $this->rom->read(0x6E0E9));
    }

    public function testSetQuickSwapOn()
    {
        $this->rom->setQuickSwap(true);

        $this->assertEquals(0x01, $this->rom->read(0x18004B));
    }

    public function testSetQuickSwapOff()
    {
        $this->rom->setQuickSwap(false);

        $this->assertEquals(0x00, $this->rom->read(0x18004B));
    }

    public function testSetSmithyFreeTravelOn()
    {
        $this->rom->setSmithyFreeTravel(true);

        $this->assertEquals(0x01, $this->rom->read(0x18004C));
    }

    public function testSetSmithyFreeTravelOff()
    {
        $this->rom->setSmithyFreeTravel(false);

        $this->assertEquals(0x00, $this->rom->read(0x18004C));
    }

    public function testSetProgrammable1Bees()
    {
        $this->rom->setProgrammable1('bees');

        $code = [
            0xA9, 0x79, 0x22, 0x5D, 0xF6, 0x1D, 0x30, 0x14,
            0xA5, 0x22, 0x99, 0x10, 0x0D, 0xA5, 0x23, 0x99,
            0x30, 0x0D, 0xA5, 0x20, 0x99, 0x00, 0x0D, 0xA5,
            0x21, 0x99, 0x20, 0x0D, 0x6B,
        ];
        $pointer = [0x00, 0x80, 0x3B];

        $this->assertEquals($code, $this->rom->read(0x1D8000, 29));
        $this->assertEquals($pointer, $this->rom->read(0x180061, 3));
    }

    public function testSetRandomizerSeedTypeOverworldGlitches()
    {
        $this->rom->setRandomizerSeedType('OverworldGlitches');

        $this->assertEquals(0x02, $this->rom->read(0x180210));
    }

    public function testSetRandomizerSeedTypeMajorGlitches()
    {
        $this->rom->setRandomizerSeedType('MajorGlitches');

        $this->assertEquals(0x01, $this->rom->read(0x180210));
    }

    public function testSetRandomizerSeedTypeNormal()
    {
        $this->rom->setRandomizerSeedType('NoGlitches');

        $this->assertEquals(0x00, $this->rom->read(0x180210));
    }

    public function testSetRandomizerSeedTypeDefaultsToNoGlitches()
    {
        $this->rom->setRandomizerSeedType('badType');

        $this->assertEquals(0x00, $this->rom->read(0x180210));
    }

    public function testSetRandomizerSeedTypeOff()
    {
        $this->rom->setRandomizerSeedType('off');

        $this->assertEquals(0xFF, $this->rom->read(0x180210));
    }

    public function testSetGameTypeEnemizer()
    {
        $this->rom->setGameType('enemizer');

        $this->assertEquals(0b00000101, $this->rom->read(0x180211));
    }

    public function testSetGameTypeEntrance()
    {
        $this->rom->setGameType('entrance');

        $this->assertEquals(0b00000110, $this->rom->read(0x180211));
    }

    public function testSetGameTypeRoom()
    {
        $this->rom->setGameType('room');

        $this->assertEquals(0b00001000, $this->rom->read(0x180211));
    }

    public function testSetGameTypeItem()
    {
        $this->rom->setGameType('item');

        $this->assertEquals(0b00000100, $this->rom->read(0x180211));
    }

    public function testSetPlandomizerAuthor()
    {
        $this->rom->setPlandomizerAuthor('123456789012345678901');

        $this->assertEquals([49, 50, 51, 52, 53, 54, 55, 56, 57, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 48, 49], $this->rom->read(0x180220, 31));
    }

    public function testSetTournamentTypeStandard()
    {
        $this->rom->setTournamentType('standard');

        $this->assertEquals([0x01, 0x00], $this->rom->read(0x180213, 2));
    }

    public function testSetTournamentTypeNone()
    {
        $this->rom->setTournamentType('none');

        $this->assertEquals([0x00, 0x01], $this->rom->read(0x180213, 2));
    }

    public function testSetStartScreenHash()
    {
        $this->rom->setStartScreenHash([0x00, 0x02, 0x04, 0x06, 0x07]);

        $this->assertEquals([0x00, 0x02, 0x04, 0x06, 0x07], $this->rom->read(0x180215, 5));
    }

    public function testRemoveUnclesShield()
    {
        $this->rom->removeUnclesShield();

        $this->assertEquals([0x00, 0x00, 0xf6, 0xff, 0x00, 0x0E], $this->rom->read(0x6D253, 6));
    }

    public function testRemoveUnclesSword()
    {
        $this->rom->removeUnclesSword();

        $this->assertEquals([0x00, 0x00, 0xf6, 0xff, 0x00, 0x0E], $this->rom->read(0x6D263, 6));
    }

    public function testSetStunnedSpritePrize()
    {
        $this->rom->setStunnedSpritePrize(0x4E);

        $this->assertEquals(0x4E, $this->rom->read(0x37993));
    }

    public function testSetPowderedSpriteFairyPrize()
    {
        $this->rom->setPowderedSpriteFairyPrize(0x4E);

        $this->assertEquals(0x4E, $this->rom->read(0x36DD0));
    }

    public function testSetPullTreePrizes()
    {
        $this->rom->setPullTreePrizes(0x00, 0x01, 0x02);

        $this->assertEquals([0x00, 0x01, 0x02], $this->rom->read(0xEFBD4, 3));
    }

    public function testSetRupeeCrabPrizes()
    {
        $this->rom->setRupeeCrabPrizes(0x01, 0x02);

        $this->assertEquals(0x01, $this->rom->read(0x329C8));
        $this->assertEquals(0x02, $this->rom->read(0x329C4));
    }

    public function testSetFishSavePrize()
    {
        $this->rom->setFishSavePrize(0x4E);

        $this->assertEquals(0x4E, $this->rom->read(0xE82CC));
    }

    public function testSetOverworldBonkPrizesEmpty()
    {
        $this->rom->setOverworldBonkPrizes();

        $this->assertEquals(0x03, $this->rom->read(0x4CF6C));
    }

    public function testSetOverworldBonkPrizes()
    {
        $this->rom->setOverworldBonkPrizes(array_fill(0, 64, 0x05));

        $this->assertEquals(0x05, $this->rom->read(0x4CF6C));
    }

    public function testSetOverworldDigPrizes()
    {
        $this->rom->setOverworldDigPrizes(array_fill(0, 64, 0x05));

        $this->assertEquals(0x05, $this->rom->read(0x180100));
    }

    public function testSetHardModeUnknownValueThrowsException()
    {
        $this->expectException(\Exception::class);

        $this->rom->setHardMode(1000000);
    }

    public function testSetHardMode2ChangesCapeMagicUsage()
    {
        $this->rom->setHardMode(2);

        $this->assertEquals([0x02, 0x04, 0x08], $this->rom->read(0x3ADA7, 3));
    }

    public function testSetHardMode1ChangesCapeMagicUsage()
    {
        $this->rom->setHardMode(1);

        $this->assertEquals([0x02, 0x04, 0x08], $this->rom->read(0x3ADA7, 3));
    }

    public function testSetHardMode0ChangesCapeMagicUsage()
    {
        $this->rom->setHardMode(0);

        $this->assertEquals([0x04, 0x08, 0x10], $this->rom->read(0x3ADA7, 3));
    }

    public function testSetHardMode3ChangesBubbleTransform()
    {
        $this->rom->setHardMode(3);

        $this->assertEquals(0x79, $this->rom->read(0x36DD0));
    }

    public function testSetHardMode2ChangesBubbleTransform()
    {
        $this->rom->setHardMode(2);

        $this->assertEquals(0xD8, $this->rom->read(0x36DD0));
    }

    public function testSetHardMode1ChangesBubbleTransform()
    {
        $this->rom->setHardMode(1);

        $this->assertEquals(0xD8, $this->rom->read(0x36DD0));
    }

    public function testSetHardMode0ChangesBubbleTransform()
    {
        $this->rom->setHardMode(0);

        $this->assertEquals(0xE3, $this->rom->read(0x36DD0));
    }

    public function testSetSmithyQuickItemGiveOn()
    {
        $this->rom->setSmithyQuickItemGive(true);

        $this->assertEquals(0x01, $this->rom->read(0x180029));
    }

    public function testSetSmithyQuickItemGiveOff()
    {
        $this->rom->setSmithyQuickItemGive(false);

        $this->assertEquals(0x00, $this->rom->read(0x180029));
    }

    public function testSetPyramidFairyChestsOn()
    {
        $this->rom->setPyramidFairyChests(true);

        $this->assertEquals([0xB1, 0xC6, 0xF9, 0xC9, 0xC6, 0xF9], $this->rom->read(0x1FC16, 6));
    }

    public function testSetPyramidFairyChestsOff()
    {
        $this->rom->setPyramidFairyChests(false);

        $this->assertEquals([0xA8, 0xB8, 0x3D, 0xD0, 0xB8, 0x3D], $this->rom->read(0x1FC16, 6));
    }

    public function testSetOpenModeOn()
    {
        $this->rom->setOpenMode(true);

        $this->assertEquals(0x01, $this->rom->read(0x180032));
    }

    public function testSetOpenModeOff()
    {
        $this->rom->setOpenMode(false);

        $this->assertEquals(0x00, $this->rom->read(0x180032));
    }

    public function testSetSewersLampConeOn()
    {
        $this->rom->setSewersLampCone(true);

        $this->assertEquals(0x01, $this->rom->read(0x180038));
    }

    public function testSetSewersLampConeOff()
    {
        $this->rom->setSewersLampCone(false);

        $this->assertEquals(0x00, $this->rom->read(0x180038));
    }

    public function testSetLightWorldLampConeOn()
    {
        $this->rom->setLightWorldLampCone(true);

        $this->assertEquals(0x01, $this->rom->read(0x180039));
    }

    public function testSetLightWorldLampConeOff()
    {
        $this->rom->setLightWorldLampCone(false);

        $this->assertEquals(0x00, $this->rom->read(0x180039));
    }

    public function testSetDarkWorldLampConeOn()
    {
        $this->rom->setDarkWorldLampCone(true);

        $this->assertEquals(0x01, $this->rom->read(0x18003A));
    }

    public function testSetDarkWorldLampConeOff()
    {
        $this->rom->setDarkWorldLampCone(false);

        $this->assertEquals(0x00, $this->rom->read(0x18003A));
    }

    public function testSetMirrorlessSaveAndQuitToLightWorldOn()
    {
        $this->rom->setMirrorlessSaveAndQuitToLightWorld(true);

        $this->assertEquals(0x01, $this->rom->read(0x1800A0));
    }

    public function testSetMirrorlessSaveAndQuitToLightWorldOff()
    {
        $this->rom->setMirrorlessSaveAndQuitToLightWorld(false);

        $this->assertEquals(0x00, $this->rom->read(0x1800A0));
    }

    public function testSetSwampWaterLevelOn()
    {
        $this->rom->setSwampWaterLevel(true);

        $this->assertEquals(0x01, $this->rom->read(0x1800A1));
    }

    public function testSetSwampWaterLevelOff()
    {
        $this->rom->setSwampWaterLevel(false);

        $this->assertEquals(0x00, $this->rom->read(0x1800A1));
    }

    public function testSetPreAgahnimDarkWorldDeathInDungeonOn()
    {
        $this->rom->setPreAgahnimDarkWorldDeathInDungeon(true);

        $this->assertEquals(0x01, $this->rom->read(0x1800A2));
    }

    public function testSetPreAgahnimDarkWorldDeathInDungeonOff()
    {
        $this->rom->setPreAgahnimDarkWorldDeathInDungeon(false);

        $this->assertEquals(0x00, $this->rom->read(0x1800A2));
    }

    public function testSetSeedString()
    {
        $this->rom->setSeedString('123456789012345678901');

        $this->assertEquals([49, 50, 51, 52, 53, 54, 55, 56, 57, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 48, 49], $this->rom->read(0x7FC0, 21));
    }

    public function testSetSeedStringNotLongerThan21Chars()
    {
        $this->rom->setSeedString('aaaaaaaaaaaaaaaaaaaaaaaaa');

        $this->assertEquals([97, 97, 97, 97, 97, 97, 97, 97, 97, 97, 97, 97, 97, 97, 97, 97, 97, 97, 97, 97, 97], $this->rom->read(0x7FC0, 25));
    }
}
