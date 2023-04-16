<?php

declare(strict_types=1);

namespace App\Tests\Functional\Mutation;

use App\Tests\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;

class CategoryMutationTest extends ApiTestCase implements BaseMutationTestInterface
{
    /** @test */
    public function should_not_create_object_if_not_authenticated(): void
    {
        $this->sendPostRequest('/', $this->getCreateQuery());
        $this->assertStatusCodeUnauthorized();
    }

    /** @test */
    public function should_not_update_object_if_not_authenticated(): void
    {
        $this->sendPostRequest('/', $this->getUpdateQuery());
        $this->assertStatusCodeUnauthorized();
    }

    /** @test */
    public function should_not_delete_object_if_not_authenticated(): void
    {
        $this->sendPostRequest('/', $this->getDeleteQuery());
        $this->assertStatusCodeUnauthorized();
    }

    /** @test */
    public function should_not_create_object_if_unauthorized(): void
    {
        $this->loginAsUser();
        $this->sendPostRequest('/', $this->getCreateQuery());

        $results = $this->getArrayResponse();
        $this->assertEquals('Access denied to this field.', $results['errors'][0]['message']);
    }

    /** @test */
    public function should_not_update_object_if_unauthorized(): void
    {
        $this->loginAsUser();
        $this->sendPostRequest('/', $this->getUpdateQuery());

        $results = $this->getArrayResponse();
        $this->assertEquals('Access denied to this field.', $results['errors'][0]['message']);
    }

    /** @test */
    public function should_not_delete_object_if_unauthorized(): void
    {
        $this->loginAsUser();
        $this->sendPostRequest('/', $this->getDeleteQuery());

        $results = $this->getArrayResponse();
        $this->assertEquals('Access denied to this field.', $results['errors'][0]['message']);
    }

    /** @test */
    public function should_create_object_if_authenticated_and_authorized(): void
    {
        $this->loginAsAdmin();
        $this->sendPostRequest('/', $this->getCreateQuery());

        $results = $this->getArrayResponse()['data']['createCategory'];

        $this->assertEquals('success', $results['message']);
        $this->assertEquals(Response::HTTP_OK, $results['code']);
    }

    /** @test */
    public function should_update_object_if_authenticated_and_authorized(): void
    {
        $this->loginAsAdmin();
        $this->sendPostRequest('/', $this->getUpdateQuery());

        $results = $this->getArrayResponse()['data']['updateCategory'];

        $this->assertEquals('success', $results['message']);
        $this->assertEquals(Response::HTTP_OK, $results['code']);
    }

    /** @test */
    public function should_delete_object_if_authenticated_and_authorized(): void
    {
        $this->loginAsAdmin();
        $this->sendPostRequest('/', $this->getDeleteQuery());

        $results = $this->getArrayResponse()['data']['deleteCategory'];

        $this->assertEquals('success', $results['message']);
        $this->assertEquals(Response::HTTP_OK, $results['code']);
    }

    private function getCreateQuery(): array
    {
        return [
            'query' => 'mutation CreateCategory {
                createCategory(category: {name: "Category"}) {
                    code
                    message
                }
            }',
        ];
    }

    private function getUpdateQuery(): array
    {
        return [
            'query' => 'mutation UpdateCategory {
                updateCategory(id: 1, category: {name: "New category"}) {
                    code
                    message
                }
            }',
        ];
    }

    private function getDeleteQuery(): array
    {
        return [
            'query' => 'mutation DeleteCategory {
                deleteCategory(id: 3) {
                    code
                    message
                }
            }',
        ];
    }
}
