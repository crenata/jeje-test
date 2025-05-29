<?php

namespace Tests\Feature\Customer;

//use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\DataStore;
use Tests\TestCase;

class ProductTest extends TestCase {
    public function test_get_product_without_token_should_error() {
        $response = $this->get(DataStore::$basePathCustomer . "/product/get");
        $response->assertUnauthorized();
    }

    public function test_get_product_should_has_two() {
        $response = $this->get(DataStore::$basePathCustomer . "/product/get", [
            "Authorization" => "Bearer " . DataStore::$customerToken
        ]);
        $this->assertSame(2, $response->collect()->get("data")["total"]);
    }
}
