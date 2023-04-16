<?php

declare(strict_types=1);

namespace App\Tests\Functional\Query;

use App\Tests\ApiTestCase;

class CategoryQueryTest extends ApiTestCase implements BaseQueryTestInterface
{
    /** @test */
    public function should_not_get_object_if_not_authenticated(): void
    {
        $query = [
            'query' => 'query GetCategory {
                getCategory(id: 2) {
                    id
                    name
                }
            }',
        ];

        $this->sendGetRequest('/', $query);
        $this->assertStatusCodeUnauthorized();
    }

    /** @test */
    public function should_not_get_object_list_if_not_authenticated(): void
    {
        $query = [
            'query' => 'query GetCategories {
                getCategories(limit: null, orderBy: null, criteria: null) {
                    categories {
                        id
                        name
                    }
                }
            }',
        ];

        $this->sendGetRequest('/', $query);
        $this->assertStatusCodeUnauthorized();
    }

    /** @test */
    public function should_get_object_list(): void
    {
        $query = [
            'query' => 'query GetCategories {
                getCategories(limit: null, orderBy: null, criteria: null) {
                    categories {
                        id
                        name
                    }
                }
            }',
        ];

        $this->loginAsUser();
        $this->sendGetRequest('/', $query);

        $response = $this->getArrayResponse();
        $results = $response['data']['getCategories']['categories'];

        $this->assertCount(2, $results);
        $this->assertEquals('Architecture', $results[0]['name']);
        $this->assertEquals('Programming', $results[1]['name']);
        $this->assertStatusCodeSuccess();
    }

    /** @test */
    public function should_get_object_list_with_limit(): void
    {
        $query = [
            'query' => 'query GetCategories {
                getCategories(limit: 1, orderBy: null, criteria: null) {
                    categories {
                        id
                        name
                    }
                }
            }',
        ];

        $this->loginAsUser();
        $this->sendGetRequest('/', $query);

        $response = $this->getArrayResponse();
        $results = $response['data']['getCategories']['categories'];

        $this->assertCount(1, $results);
        $this->assertEquals('Architecture', $results[0]['name']);
        $this->assertStatusCodeSuccess();
    }

    /** @test */
    public function should_get_object_list_ordered_by_field(): void
    {
        $query = [
            'query' => 'query GetCategories {
                getCategories(limit: null, orderBy: "name", criteria: "asc") {
                    categories {
                        id
                        name
                    }
                }
            }',
        ];

        $this->loginAsUser();
        $this->sendGetRequest('/', $query);

        $response = $this->getArrayResponse();
        $results = $response['data']['getCategories']['categories'];

        $this->assertCount(2, $results);
        $this->assertEquals('Architecture', $results[0]['name']);
        $this->assertEquals('Programming', $results[1]['name']);
        $this->assertStatusCodeSuccess();
    }

    /** @test */
    public function should_get_object_by_id(): void
    {
        $query = [
            'query' => 'query GetCategory {
                getCategory(id: 2) {
                    id
                    name
                }
            }',
        ];

        $this->loginAsUser();
        $this->sendGetRequest('/', $query);

        $response = $this->getArrayResponse();
        $result = $response['data']['getCategory'];

        $this->assertEquals('Architecture', $result['name']);
        $this->assertStatusCodeSuccess();
    }
}
