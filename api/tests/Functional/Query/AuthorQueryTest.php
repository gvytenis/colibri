<?php

declare(strict_types=1);

namespace App\Tests\Functional\Query;

use App\Tests\ApiTestCase;

class AuthorQueryTest extends ApiTestCase implements BaseQueryTestInterface
{
    /** @test */
    public function should_not_get_object_if_not_authenticated(): void
    {
        $query = [
            'query' => 'query GetAuthor {
                getAuthor(id: 2) {
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
            'query' => 'query GetAuthors {
                getAuthors(limit: null, orderBy: null, criteria: null) {
                    authors {
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
            'query' => 'query GetAuthors {
                getAuthors(limit: null, orderBy: null, criteria: null) {
                    authors {
                        id
                        name
                    }
                }
            }',
        ];

        $this->loginAsUser();
        $this->sendGetRequest('/', $query);

        $response = $this->getArrayResponse();
        $results = $response['data']['getAuthors']['authors'];

        $this->assertCount(3, $results);
        $this->assertEquals('Martin Fowler', $results[0]['name']);
        $this->assertEquals('Kent Beck', $results[1]['name']);
        $this->assertStatusCodeSuccess();
    }

    /** @test */
    public function should_get_object_list_with_limit(): void
    {
        $query = [
            'query' => 'query GetAuthors {
                getAuthors(limit: 1, orderBy: null, criteria: null) {
                    authors {
                        id
                        name
                    }
                }
            }',
        ];

        $this->loginAsUser();
        $this->sendGetRequest('/', $query);

        $response = $this->getArrayResponse();
        $results = $response['data']['getAuthors']['authors'];

        $this->assertCount(1, $results);
        $this->assertEquals('Martin Fowler', $results[0]['name']);
        $this->assertStatusCodeSuccess();
    }

    /** @test */
    public function should_get_object_list_ordered_by_field(): void
    {
        $query = [
            'query' => 'query GetAuthors {
                getAuthors(limit: null, orderBy: "name", criteria: "asc") {
                    authors {
                        id
                        name
                    }
                }
            }',
        ];

        $this->loginAsUser();
        $this->sendGetRequest('/', $query);

        $response = $this->getArrayResponse();
        $results = $response['data']['getAuthors']['authors'];

        $this->assertCount(3, $results);
        $this->assertEquals('Eric Evans', $results[0]['name']);
        $this->assertEquals('Kent Beck', $results[1]['name']);
        $this->assertStatusCodeSuccess();
    }

    /** @test */
    public function should_get_object_by_id(): void
    {
        $query = [
            'query' => 'query GetAuthor {
                getAuthor(id: 2) {
                    id
                    name
                }
            }',
        ];

        $this->loginAsUser();
        $this->sendGetRequest('/', $query);

        $response = $this->getArrayResponse();
        $result = $response['data']['getAuthor'];

        $this->assertEquals('Kent Beck', $result['name']);
        $this->assertStatusCodeSuccess();
    }
}
