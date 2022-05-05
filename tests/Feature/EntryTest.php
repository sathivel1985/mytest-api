<?php

use App\Models\Entry;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;

class EntryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Testing for store  - validate empty input
     */
    public function test_save_data_throw_error_for_empty_input()
    {
        $response = $this->postJson('api/object');
        $response
            ->assertStatus(422);
    }

    /**
     * Testing for save  - validate empty key and value input
     * @route object/
     */
    public function test_save_data_throw_error_if_key_and_value_are_empty()
    {
        $data = ["" => ""];
        $response = $this->postJson('api/object', $data);
        $response
            ->assertStatus(422);
    }


    /**
     * Testing for save date  - validate empty key input
     */
    public function test_save_data_throw_error_if_key_is_empty()
    {
        $data = ["" => "value1"];
        $response = $this->postJson('api/object', $data);
        $response
            ->assertStatus(422);
    }

    /**
     * Testing for retrieve key object  - throw error if given key is not found in DB
     */
    public function test_retrieve_data_throw_error_if_given_key_is_not_found()
    {
        $response = $this->get('api/object/12312312312121', ['X-API-VALUE' => '12345']);

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [],
                'message' => 'No record found'
            ]);
    }

    /**
     * Testing for store  - validate key length
     * @route object/
     */

    public function test_save_data_throw_error_if_key_length_greater_than_255()
    {
        $data = [
            Str::random(300)
            => Str::random(15)
        ];
        $response = $this->postJson('api/object', $data);
        $response
            ->assertStatus(422);
    }

    /**
     * Testing for store  - Store data with valid input
     * @route object/
     */

    public function test_save_data_key_value_pair_with_valid_json_input()
    {
        $response = $this->postJson('api/object', [
            "my-key" => "my-key-value"
        ]);
        $response
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    "key" => "my-key",
                    "value" => "my-key-value"
                ]
            ]);
    }

    public function test_retrieve_latest_value_for_existing_key()
    {
        $this->postJson('api/object', ["test" => "value one"]);
        sleep(1);
        $this->postJson('api/object', ["test" => "value two"]);
        sleep(1);
        $this->postJson('api/object', ["test" => "value three"]);
        $response = $this->get('api/object/test', ['X-API-VALUE' => '12345']);

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    "key" => "test",
                    "value" => "value three"
                ]
            ]);
    }

    /**
     * Testing for get key object with timestamp
     * @route object/{key}?timestamp={timestamp}
     */
    public function test_retrieve_data_for_existing_key_for_given_timestamp()
    {
        $time = Carbon::now()->timestamp;
        $entry = Entry::create(['name' => 'mykey', 'value' => 'myvalue', 'updated_at' => $time]);
        $response = $this->get('api/object/mykey?timestamp=' .  $time, ['X-API-VALUE' => '12345']);
        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'key' => $entry->name,
                    'value' => $entry->value,
                    'updated_at' => $entry->updated_at
                ]
            ]);
    }


    /**
     * Testing for retrieve  object with timestamp request
     * Throw error if key object does not have for a given timestamp
     */
    public function test_retrieve_data_throw_error_if_value_is_not_found_for_given_key_and_timestamp()
    {
        $entry = Entry::factory()->create();
        sleep(1);
        $response = $this->get('api/object/' . $entry->name . '?timestamp=' . time(), ['X-API-VALUE' => '12345']);
        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [],
                'message' => 'No record found'
            ]);
    }


    /**
     * Testing for display all  entries
     */

    public function test_get_all_entries()
    {
        $response = $this->get('api/object/get_all_records', ['X-API-VALUE' => '12345']);
        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'key',
                    'value',
                    'updated_at'
                ]
            ]
        ]);;
    }

    /**
     * Testing for display all  entries
     */

    public function test_cache_entries()
    {
        $response = $this->get('api/object/get_all_records', ['X-API-VALUE' => '12345']);

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'key',
                    'value',
                    'updated_at'
                ]
            ]
        ]);;
    }
}
