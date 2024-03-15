<?php

namespace App\Http\Controllers\Admin;

use App\Jobs\CreateMysql;
use Illuminate\Support\Facades\Bus;

class QueueController extends BaseController
{
    public function setQueueTest()
    {
        // CreateMysql::dispatch('pppppqqqq');
        Bus::chain([
            new CreateMysql(['0000', 'bbb', 'cccc'])
        ])->catch(function (\Throwable $e) {
            echo 'job is error:' . $e->getMessage();
        })->onQueue('testcreatemysql')->dispatch();
    }
}
