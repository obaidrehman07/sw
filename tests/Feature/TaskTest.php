<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_task_list_page()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_task_list_data()
    {
        $response = $this->post('/get-historical-data');

        $response->assertStatus(200);
    }

    public function test_task_send_email_to_customer()
    {
        $response = $this->post('/sendmail-to-customer', [
            'email'=>"obaidrehman07@gmail.com"
        ]);

        $response->assertStatus(302);
    }

    public function test_task_historical_grap()
    {
        $response = $this->get('/task/barchart');

        $response->assertStatus(200);
    }
}
