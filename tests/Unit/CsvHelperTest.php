<?php

namespace Tests\Feature;

use Tests\TestCase;
use Artisan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Traits\CsvHelper;

class CsvHelperTest extends TestCase
{
    use CsvHelper;

    /** @test */
    public function test_remove_special_character()
    {
        $header = array(
            0 => '﻿code',
            1 => 'name',
            2 => 'description'
        );
        $this->assertIsArray($header);
        $result = $this->removeSpecialCharacter($header);
        $this->assertEquals('code', $result[0]);
    }

    /** @test */
    public function test_merge_header_with_rows()
    {
        $header = array(
            0 => 'code',
            1 => 'name',
            2 => 'description'
        );
        $row = array(
            array(
                0 => '12345',
                1 => 'test',
                2 => 'lorem ipssum'
            )
        );
        $this->assertIsArray($header);
        $this->assertIsArray($row);
        $result = $this->mergeHeaderWithRows($row, $header);
        $expectedResult = array(
            array(
                'code' => '12345',
                'name' => 'test',
                'description' => 'lorem ipssum'
            )
        );
        $this->assertEquals($result, $expectedResult);
    }
}
