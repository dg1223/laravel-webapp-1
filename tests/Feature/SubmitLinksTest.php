<?php

namespace Tests\Feature;

use Illuminate\Validation\ValidationException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;


class SubmitLinksTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     * @return void
     */
    function guest_can_submit_a_valid_link() {
        $response = $this->post('/submit', [
            'title' => 'Example title',
            'url' => 'http://example.com',
            'description' => 'Example description.',
        ]);

        $this->assertDatabaseHas('links', [
            'title' => 'Example title'
        ]);

        $response
        ->assertStatus(302)
        ->assertHeader('Location', url('/'));

        $this
        ->get('/')
        ->assertSee('Example title');
    }

    /**
     * @test
     *
     * @return void
     */
    function link_is_not_created_if_validation_fails() {
        $response = $this->post('/submit');

        $response->assertSessionHasErrors(['title', 'url', 'description']);
    }

    /**
     * @test
     *
     * @return void
     */
    function link_is_not_created_with_an_invalid_url() {

        $this->withoutExceptionHandling();

        $cases = ['//invalid-url.com', '/invalid-url', 'foo.com'];

        foreach ($cases as $case) {
            try {
                $response = $this->post('/submit', [
                    'title' => 'Example title',
                    'url' => $case,
                    'description' => 'Example description',
                ]);
            } catch (ValidationException $e) {
                $this->assertEquals(
                    'The url must be a valid URL.',
                    $e->validator->errors()->first('url')
                );
                continue;                
            }

            $this->fail("The URL $case passed validation when it should have failed.");
        }
    }

    /**
     * @test
     *
     * @return void
     */
    function max_length_fails_when_too_long() {

        $this->withoutExceptionHandling();

        $title = str_repeat('a', 256);
        $description = str_repeat('a', 256);
        $url = 'http://';
        $url .= str_repeat('a', 256 - strlen($url));

        try {
            $this->post('/submit', compact('title', 'url', 'description'));
        } catch (ValidationException $e) {
            $this->assertEquals(
                'The title must not be greater than 255 characters.',
                $e->validator->errors()->first('title')
            );

            $this->assertEquals(
                'The url must not be greater than 255 characters.',
                $e->validator->errors()->first('url')
            );

            $this->assertEquals(
                'The description must not be greater than 255 characters.',
                $e->validator->errors()->first('description')
            );

            return;
        }

        $this->fail('Max length should trigger a ValidationException');
    }

    /**
     * @test
     *
     * @return void
     */
    function max_length_succeeds_when_under_max() {

        $url = 'http://';
        $url .= str_repeat('a', 255 - strlen($url));

        $data = [
            'title' => str_repeat('a', 255),
            'url' => $url,
            'description' => str_repeat('a', 255),
        ];

        $this->post('/submit', $data);

        $this->assertDatabaseHas('links', $data);
    }
}
