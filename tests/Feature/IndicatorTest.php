<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IndicatorTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->int_test(11);
        $this->string_test(11);
        $this->mix_test(10);
    }

    public function int_test(int $count){
        $response = $this->post('/api/indicators', ['type' => 'int', 'length' => $count]);

        $code = $response->json('data.code');
        $this->check_count($code, $count);

        $this->get_response($response);

        $response->assertStatus(200);
    }

    public function mix_test(int $count){
        $response = $this->post('/api/indicators', ['type' => 'mix', 'length' => 10]);

        $code = $response->json('data.code');
        $this->check_count($code, $count);

        if(ctype_digit($code)){
            $this->addWarning('В микс только цифры');
        }

        $this->get_response($response);

        $response->assertStatus(200);
    }

    public function string_test(int $count){
        $response = $this->post('/api/indicators', ['type' => 'string', 'length' => 11]);

        $code = $response->json('data.code');
        $this->check_count($code, $count);

        if($this->check_is_int($code)){
            $this->addWarning('В строке есть цифры');
        }

        $this->get_response($response);

        $response->assertStatus(200);
    }

    protected function check_is_int($str){
        $i = strlen($str);
        while ($i--) {
            if (is_numeric($str[$i])) return true;
        }
        return false;
    }

    protected function check_count($code, $count){
        if(mb_strlen($code) != $count){
            $this->addWarning('Не верное кол-во - '.$count.' - '.mb_strlen($code));
        }
    }

    protected function get_response($response){

        $data = $response->json('data');
        $code = $data['code'];

        $getResponse = $this->get('/api/indicators/'.$data['id']);
        $getResponse->assertStatus(200);

        if(($get = $getResponse->json('data')) && $get['code'] != $code){
            $this->addWarning('Не совпадение кода');
        }
    }
}
