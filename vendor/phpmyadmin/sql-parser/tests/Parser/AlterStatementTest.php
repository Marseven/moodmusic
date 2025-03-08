<?php

declare(strict_types=1);

namespace PhpMyAdmin\SqlParser\Tests\Parser;

use PhpMyAdmin\SqlParser\Tests\TestCase;

class AlterStatementTest extends TestCase
{
    /**
     * @dataProvider alterProvider
     */
    public function testAlter(string $test): void
    {
        $this->runParserTest($test);
    }

    /**
     * @return string[][]
     */
    public static function alterProvider(): array
    {
        return [
            ['parser/parseAlter'],
            ['parser/parseAlter2'],
            ['parser/parseAlter3'],
            ['parser/parseAlter4'],
            ['parser/parseAlter5'],
            ['parser/parseAlter6'],
            ['parser/parseAlter7'],
            ['parser/parseAlter8'],
            ['parser/parseAlter9'],
            ['parser/parseAlter10'],
            ['parser/parseAlter11'],
            ['parser/parseAlter12'],
            ['parser/parseAlter13'],
            ['parser/parseAlter14'],
            ['parser/parseAlterErr'],
            ['parser/parseAlterErr2'],
            ['parser/parseAlterErr3'],
            ['parser/parseAlterErr4'],
            ['parser/parseAlterTableRenameIndex1'],
            ['parser/parseAlterTableRenameIndex2'],
            ['parser/parseAlterTableRenameKey1'],
            ['parser/parseAlterTableRenameKey2'],
            ['parser/parseAlterTablePartitionByRange1'],
            ['parser/parseAlterTablePartitionByRange2'],
            ['parser/parseAlterTableCoalescePartition'],
            ['parser/parseAlterTableAddColumnWithCheck'],
            ['parser/parseAlterTableAddSpatialIndex1'],
            ['parser/parseAlterTableAddUniqueKey1'],
            ['parser/parseAlterTableAddUniqueKey2'],
            ['parser/parseAlterTableDropAddIndex1'],
            ['parser/parseAlterTableDropColumn1'],
            ['parser/parseAlterTableModifyColumn'],
            ['parser/parseAlterTableModifyColumnEnum1'],
            ['parser/parseAlterTableModifyColumnEnum2'],
            ['parser/parseAlterTableModifyColumnEnum3'],
            ['parser/parseAlterWithInvisible'],
            ['parser/parseAlterTableCharacterSet1'],
            ['parser/parseAlterTableCharacterSet2'],
            ['parser/parseAlterTableCharacterSet3'],
            ['parser/parseAlterTableCharacterSet4'],
            ['parser/parseAlterTableCharacterSet5'],
            ['parser/parseAlterTableCharacterSet6'],
            ['parser/parseAlterTableCharacterSet7'],
            ['parser/parseAlterUser'],
            ['parser/parseAlterUser1'],
            ['parser/parseAlterUser2'],
            ['parser/parseAlterUser3'],
            ['parser/parseAlterUser4'],
            ['parser/parseAlterUser5'],
            ['parser/parseAlterUser6'],
            ['parser/parseAlterUser7'],
            ['parser/parseAlterUser8'],
            ['parser/parseAlterUser9'],
            ['parser/parseAlterUser10'],
            ['parser/parseAlterEvent'],
            ['parser/parseAlterEvent2'],
            ['parser/parseAlterEvent3'],
            ['parser/parseAlterEvent4'],
            ['parser/parseAlterEvent5'],
            ['parser/parseAlterEvent6'],
            ['parser/parseAlterEvent7'],
            ['parser/parseAlterEvent8'],
            ['parser/parseAlterEvent9'],
            ['parser/parseAlterEventComplete'],
            ['parser/parseAlterEventErr'],
            ['parser/parseAlterEventOnScheduleAt'],
            ['parser/parseAlterEventOnScheduleAt2'],
            ['parser/parseAlterEventOnScheduleEvery'],
            ['parser/parseAlterEventOnScheduleEvery2'],
            ['parser/parseAlterEventOnScheduleEvery3'],
            ['parser/parseAlterEventOnScheduleEvery4'],
            ['parser/parseAlterEventOnScheduleEvery5'],
            ['parser/parseAlterEventOnScheduleEvery6'],
            ['parser/parseAlterEventWithDefiner'],
            ['parser/parseAlterEventWithOtherDefiners'],
            ['parser/parseAlterRenameColumn'],
            ['parser/parseAlterRenameColumns'],
            ['parser/parseAlterFunction1'],
            ['parser/parseAlterFunction2'],
            ['parser/parseAlterFunction3'],
            ['parser/parseAlterFunction4'],
            ['parser/parseAlterFunction5'],
            ['parser/parseAlterFunction6'],
            ['parser/parseAlterProcedure1'],
            ['parser/parseAlterProcedure2'],
            ['parser/parseAlterProcedure3'],
            ['parser/parseAlterProcedure4'],
            ['parser/parseAlterProcedure5'],
            ['parser/parseAlterProcedure6'],
        ];
    }
}
