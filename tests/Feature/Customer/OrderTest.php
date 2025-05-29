<?php

namespace Tests\Feature\Customer;

//use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\DataStore;
use Tests\TestCase;

class OrderTest extends TestCase {
    public function test_cannot_submit_order_without_token() {
        $response = $this->post(DataStore::$basePathCustomer . "/order/submit", [
            "products" => [
                [
                    "id" => 1,
                    "quantity" => 10
                ],
                [
                    "id" => 2,
                    "quantity" => 5
                ]
            ]
        ]);
        $response->assertUnauthorized();
    }

    public function test_cannot_submit_order_with_invalid_product_id() {
        $response = $this->post(DataStore::$basePathCustomer . "/order/submit", [
            "products" => [
                [
                    "id" => 10,
                    "quantity" => 10
                ],
                [
                    "id" => 2,
                    "quantity" => 5
                ]
            ]
        ], [
            "Authorization" => "Bearer " . DataStore::$customerToken
        ]);
        $response->assertBadRequest();
    }

    public function test_submit_order_with_correct_data() {
        $response = $this->post(DataStore::$basePathCustomer . "/order/submit", [
            "products" => [
                [
                    "id" => 1,
                    "quantity" => 10
                ],
                [
                    "id" => 2,
                    "quantity" => 5
                ]
            ]
        ], [
            "Authorization" => "Bearer " . DataStore::$customerToken
        ]);
        $response->assertSuccessful();
    }

    public function test_cannot_get_order_detail_without_token() {
        $response = $this->get(DataStore::$basePathCustomer . "/order/detail/1");
        $response->assertUnauthorized();
    }

    public function test_cannot_get_order_detail_with_incorrect_id() {
        $response = $this->get(DataStore::$basePathCustomer . "/order/detail/10", [
            "Authorization" => "Bearer " . DataStore::$customerToken
        ]);
        $response->assertBadRequest();
    }

    public function test_get_order_detail_with_correct_id() {
        $response = $this->get(DataStore::$basePathCustomer . "/order/detail/1", [
            "Authorization" => "Bearer " . DataStore::$customerToken
        ]);
        $response->assertSuccessful();
    }

    public function test_cannot_pay_without_token() {
        $response = $this->patch(DataStore::$basePathCustomer . "/order/1/pay");
        $response->assertUnauthorized();
    }

    public function test_cannot_pay_with_incorrect_order_id() {
        $response = $this->patch(DataStore::$basePathCustomer . "/order/10/pay", [], [
            "Authorization" => "Bearer " . DataStore::$customerToken
        ]);
        $response->assertBadRequest();
    }

    public function test_pay_with_correct_order_id() {
        $response = $this->patch(DataStore::$basePathCustomer . "/order/1/pay", [], [
            "Authorization" => "Bearer " . DataStore::$customerToken
        ]);
        $response->assertSuccessful();
    }

    public function test_cannot_pay_the_order_has_already_been_paid() {
        $response = $this->patch(DataStore::$basePathCustomer . "/order/1/pay", [], [
            "Authorization" => "Bearer " . DataStore::$customerToken
        ]);
        $response->assertBadRequest();
    }
}
