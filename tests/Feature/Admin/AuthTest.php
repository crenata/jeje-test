<?php

namespace Tests\Feature\Admin;

use App\Models\AdminModel;
//use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\DataStore;
use Tests\TestCase;

class AuthTest extends TestCase {
    public function test_reset_databases() {
        $this->artisan("migrate:fresh")->assertSuccessful();
    }

    public function test_running_seeders() {
        $this->seed();
        $this->assertDatabaseHas((new AdminModel())->getTable(), [
            "email" => DataStore::$adminEmail
        ]);
    }

    public function test_cannot_login_with_invalid_email() {
        $response = $this->post(DataStore::$basePathAdmin . "/auth/login", [
            "email" => "wrong.email@gmail.com",
            "password" => DataStore::$adminPassword
        ]);
        $response->assertBadRequest();
    }

    public function test_cannot_login_with_incorrect_credential() {
        $response = $this->post(DataStore::$basePathAdmin . "/auth/login", [
            "email" => DataStore::$adminEmail,
            "password" => "12345678"
        ]);
        $response->assertUnauthorized();
    }

    public function test_login_with_correct_credential() {
        $response = $this->post(DataStore::$basePathAdmin . "/auth/login", [
            "email" => DataStore::$adminEmail,
            "password" => DataStore::$adminPassword
        ]);
        DataStore::$adminToken = $response->collect()->get("data")["token"];
        $response->assertSuccessful();
    }

    public function test_cannot_get_self_data_without_token() {
        $response = $this->get(DataStore::$basePathAdmin . "/auth/self");
        $response->assertUnauthorized();
    }

    public function test_get_self_data_with_token() {
        $response = $this->get(DataStore::$basePathAdmin . "/auth/self", [
            "Authorization" => "Bearer " . DataStore::$adminToken
        ]);
        $response->assertSuccessful();
    }

    public function test_logout() {
        $response = $this->post(DataStore::$basePathAdmin . "/auth/login", [
            "email" => DataStore::$adminEmail,
            "password" => DataStore::$adminPassword
        ]);
        $token = $response->collect()->get("data")["token"];
        $response = $this->get(DataStore::$basePathAdmin . "/auth/logout", [
            "Authorization" => "Bearer $token"
        ]);
        $response->assertSuccessful();
    }
}
