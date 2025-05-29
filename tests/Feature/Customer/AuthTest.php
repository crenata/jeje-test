<?php

namespace Tests\Feature\Customer;

//use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\DataStore;
use Tests\TestCase;

class AuthTest extends TestCase {
    public function test_cannot_register_when_confirm_password_not_match_with_password() {
        $response = $this->post(DataStore::$basePathCustomer . "/auth/register", [
            "name" => "Customer 1",
            "email" => DataStore::$customerEmail,
            "phone" => DataStore::$customerPhone,
            "password" => DataStore::$customerPassword,
            "confirm_password" => "1234567"
        ]);
        $response->assertBadRequest();
    }

    public function test_register_with_correct_data() {
        $response = $this->post(DataStore::$basePathCustomer . "/auth/register", [
            "name" => "Customer 1",
            "email" => DataStore::$customerEmail,
            "phone" => DataStore::$customerPhone,
            "password" => DataStore::$customerPassword,
            "confirm_password" => DataStore::$customerPassword
        ]);
        $response->assertSuccessful();
    }

    public function test_cannot_register_with_same_email() {
        $response = $this->post(DataStore::$basePathCustomer . "/auth/register", [
            "name" => "Customer 2",
            "email" => DataStore::$customerEmail,
            "phone" => "+6281234567891",
            "password" => DataStore::$customerPassword,
            "confirm_password" => DataStore::$customerPassword
        ]);
        $response->assertBadRequest();
    }

    public function test_cannot_register_with_same_phone() {
        $response = $this->post(DataStore::$basePathCustomer . "/auth/register", [
            "name" => "Customer 2",
            "email" => "customer2@gmail.com",
            "phone" => DataStore::$customerPhone,
            "password" => DataStore::$customerPassword,
            "confirm_password" => DataStore::$customerPassword
        ]);
        $response->assertBadRequest();
    }

    public function test_cannot_login_with_email_invalid() {
        $response = $this->post(DataStore::$basePathCustomer . "/auth/login", [
            "email" => "wrong.email@gmail.com",
            "password" => DataStore::$customerPassword
        ]);
        $response->assertBadRequest();
    }

    public function test_cannot_login_with_incorrect_credential() {
        $response = $this->post(DataStore::$basePathCustomer . "/auth/login", [
            "email" => DataStore::$customerEmail,
            "password" => "12345678"
        ]);
        $response->assertUnauthorized();
    }

    public function test_login_with_correct_credential() {
        $response = $this->post(DataStore::$basePathCustomer . "/auth/login", [
            "email" => DataStore::$customerEmail,
            "password" => DataStore::$customerPassword
        ]);
        DataStore::$customerToken = $response->collect()->get("data")["token"];
        $response->assertSuccessful();
    }

    public function test_cannot_get_self_data_without_token() {
        $response = $this->get(DataStore::$basePathCustomer . "/auth/self");
        $response->assertUnauthorized();
    }

    public function test_get_self_data_with_token() {
        $response = $this->get(DataStore::$basePathCustomer . "/auth/self", [
            "Authorization" => "Bearer " . DataStore::$customerToken
        ]);
        $response->assertSuccessful();
    }

    public function test_logout() {
        $response = $this->post(DataStore::$basePathCustomer . "/auth/login", [
            "email" => DataStore::$customerEmail,
            "password" => DataStore::$customerPassword
        ]);
        $token = $response->collect()->get("data")["token"];
        $response = $this->get(DataStore::$basePathCustomer . "/auth/logout", [
            "Authorization" => "Bearer $token"
        ]);
        $response->assertSuccessful();
    }
}
