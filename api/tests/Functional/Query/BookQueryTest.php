<?php

declare(strict_types=1);

namespace App\Tests\Functional\Query;

use App\Tests\ApiTestCase;

class BookQueryTest extends ApiTestCase implements BaseQueryTestInterface
{
    /** @test */
    public function should_not_get_object_if_not_authenticated(): void
    {
        $query = [
            'query' => 'query GetBook {
                getBook(id: 1) {
                    id
                    title
                    year
                    author {
                        id
                        name
                    }
                    category {
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
    public function should_not_get_object_list_if_not_authenticated(): void
    {
        $query = [
            'query' => 'query GetBooks {
                getBooks(criteria: null, orderBy: null, limit: null) {
                    books {
                        id
                        title
                        year
                        category {
                            id
                            name
                        }
                        author {
                            id
                            name
                        }
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
            'query' => 'query GetBooks {
                getBooks(criteria: null, orderBy: null, limit: null) {
                    books {
                        id
                        title
                        year
                        category {
                            id
                            name
                        }
                        author {
                            id
                            name
                        }
                    }
                }
            }',
        ];

        $this->loginAsUser();
        $this->sendGetRequest('/', $query);

        $response = $this->getArrayResponse();
        $results = $response['data']['getBooks']['books'];

        $this->assertCount(2, $results);
        $this->assertEquals('Patterns of Enterprise Application Architecture', $results[0]['title']);
        $this->assertEquals(2000, $results[0]['year']);
        $this->assertEquals('Architecture', $results[0]['category']['name']);
        $this->assertEquals('Kent Beck', $results[0]['author']['name']);
        $this->assertEquals('Domain Driven Design', $results[1]['title']);
        $this->assertEquals(2012, $results[1]['year']);
        $this->assertEquals('Architecture', $results[1]['category']['name']);
        $this->assertEquals('Eric Evans', $results[1]['author']['name']);
        $this->assertStatusCodeSuccess();
    }

    /** @test */
    public function should_get_object_list_with_limit(): void
    {
        $query = [
            'query' => 'query GetBooks {
                getBooks(criteria: null, orderBy: null, limit: 1) {
                    books {
                        id
                        title
                        year
                        category {
                            id
                            name
                        }
                        author {
                            id
                            name
                        }
                    }
                }
            }',
        ];

        $this->loginAsUser();
        $this->sendGetRequest('/', $query);

        $response = $this->getArrayResponse();
        $results = $response['data']['getBooks']['books'];

        $this->assertCount(1, $results);
        $this->assertEquals('Patterns of Enterprise Application Architecture', $results[0]['title']);
        $this->assertEquals(2000, $results[0]['year']);
        $this->assertEquals('Architecture', $results[0]['category']['name']);
        $this->assertEquals('Kent Beck', $results[0]['author']['name']);
        $this->assertStatusCodeSuccess();
    }

    /** @test */
    public function should_get_object_list_ordered_by_field(): void
    {
        $query = [
            'query' => 'query GetBooks {
                getBooks(criteria: "asc", orderBy: "title", limit: null) {
                    books {
                        id
                        title
                        year
                        category {
                            id
                            name
                        }
                        author {
                            id
                            name
                        }
                    }
                }
            }',
        ];

        $this->loginAsUser();
        $this->sendGetRequest('/', $query);

        $response = $this->getArrayResponse();
        $results = $response['data']['getBooks']['books'];

        $this->assertCount(2, $results);
        $this->assertEquals('Domain Driven Design', $results[0]['title']);
        $this->assertEquals(2012, $results[0]['year']);
        $this->assertEquals('Architecture', $results[0]['category']['name']);
        $this->assertEquals('Eric Evans', $results[0]['author']['name']);
        $this->assertEquals('Patterns of Enterprise Application Architecture', $results[1]['title']);
        $this->assertEquals(2000, $results[1]['year']);
        $this->assertEquals('Architecture', $results[1]['category']['name']);
        $this->assertEquals('Kent Beck', $results[1]['author']['name']);
        $this->assertStatusCodeSuccess();
    }

    /** @test */
    public function should_get_object_by_id(): void
    {
        $query = [
            'query' => 'query GetBook {
                getBook(id: 1) {
                    id
                    title
                    year
                    author {
                        id
                        name
                    }
                    category {
                        id
                        name
                    }
                }
            }',
        ];

        $this->loginAsUser();
        $this->sendGetRequest('/', $query);

        $response = $this->getArrayResponse();
        $result = $response['data']['getBook'];

        $this->assertEquals('Domain Driven Design', $result['title']);
        $this->assertEquals(2012, $result['year']);
        $this->assertEquals('Architecture', $result['category']['name']);
        $this->assertEquals('Eric Evans', $result['author']['name']);
        $this->assertStatusCodeSuccess();
    }
}
