<?php

namespace Tests\Feature\Admin;

//use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\DataStore;
use Tests\TestCase;

class ProductTest extends TestCase {
    public function test_cannot_get_product_without_token() {
        $response = $this->get(DataStore::$basePathAdmin . "/product/get");
        $response->assertUnauthorized();
    }

    public function test_get_product_should_still_empty() {
        $response = $this->get(DataStore::$basePathAdmin . "/product/get", [
            "Authorization" => "Bearer " . DataStore::$adminToken
        ]);
        $this->assertCount(0, $response->collect()->get("data")["data"]);
    }

    public function test_cannot_add_product_without_token() {
        $response = $this->post(DataStore::$basePathAdmin . "/product/add", [
            "name" => "Product 1",
            "price" => 1000,
            "stock" => 100
        ]);
        $response->assertUnauthorized();
    }

    public function test_cannot_add_product_with_zero_price() {
        $response = $this->post(DataStore::$basePathAdmin . "/product/add", [
            "name" => "Product 1",
            "price" => 0,
            "stock" => 100
        ], [
            "Authorization" => "Bearer " . DataStore::$adminToken
        ]);
        $response->assertBadRequest();
    }

    public function test_cannot_add_product_with_zero_stock() {
        $response = $this->post(DataStore::$basePathAdmin . "/product/add", [
            "name" => "Product 1",
            "price" => 1000,
            "stock" => 0
        ], [
            "Authorization" => "Bearer " . DataStore::$adminToken
        ]);
        $response->assertBadRequest();
    }

    public function test_add_product_with_correct_data() {
        $response = $this->post(DataStore::$basePathAdmin . "/product/add", [
            "name" => "Product 1",
            "price" => 1000,
            "stock" => 100
        ], [
            "Authorization" => "Bearer " . DataStore::$adminToken
        ]);
        $response->assertSuccessful();
    }

    public function test_get_product_should_has_one() {
        $response = $this->get(DataStore::$basePathAdmin . "/product/get", [
            "Authorization" => "Bearer " . DataStore::$adminToken
        ]);
        $this->assertSame(1, $response->collect()->get("data")["total"]);
    }

    public function test_cannot_add_product_with_same_name() {
        $response = $this->post(DataStore::$basePathAdmin . "/product/add", [
            "name" => "Product 1",
            "price" => 1000,
            "stock" => 100
        ], [
            "Authorization" => "Bearer " . DataStore::$adminToken
        ]);
        $response->assertBadRequest();
    }

    public function test_add_second_product() {
        $response = $this->post(DataStore::$basePathAdmin . "/product/add", [
            "name" => "Product 2",
            "price" => 1000,
            "stock" => 100
        ], [
            "Authorization" => "Bearer " . DataStore::$adminToken
        ]);
        $response->assertSuccessful();
    }

    public function test_get_product_should_has_two() {
        $response = $this->get(DataStore::$basePathAdmin . "/product/get", [
            "Authorization" => "Bearer " . DataStore::$adminToken
        ]);
        $this->assertSame(2, $response->collect()->get("data")["total"]);
    }
}
