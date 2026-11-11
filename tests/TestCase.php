<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->assertSame(
            'absensi_siswa_test',
            DB::connection()->getDatabaseName(),
            'Tes harus memakai database absensi_siswa_test. Periksa file .env.testing.'
        );
    }
}