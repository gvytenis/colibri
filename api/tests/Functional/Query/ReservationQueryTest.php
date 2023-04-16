<?php

declare(strict_types=1);

namespace App\Tests\Functional\Query;

use App\Tests\ApiTestCase;

class ReservationQueryTest extends ApiTestCase implements BaseQueryTestInterface
{
    /** @test */
    public function should_not_get_object_if_not_authenticated(): void
    {
        $query = [
            'query' => 'query GetReservation {
                getReservation(id: 1) {
                    id
                    user {
                        id
                        name
                        username
                        email
                        status
                        roles
                    }
                    book {
                        id
                        title
                        year
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
            'query' => 'query GetReservations {
                getReservations(limit: null, orderBy: null, criteria: null) {
                    reservations {
                        id
                        book {
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
                        user {
                            id
                            name
                            username
                            email
                            status
                            roles
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
            'query' => 'query GetReservations {
                getReservations(limit: null, orderBy: null, criteria: null) {
                    reservations {
                        id
                        book {
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
                        user {
                            id
                            name
                            username
                            email
                            status
                            roles
                        }
                    }
                }
            }',
        ];

        $this->loginAsAdmin();
        $this->sendGetRequest('/', $query);

        $response = $this->getArrayResponse();
        $results = $response['data']['getReservations']['reservations'];

        $this->assertCount(2, $results);
        $this->assertEquals('Jane Doe', $results[0]['user']['name']);
        $this->assertEquals('janedoe', $results[0]['user']['username']);
        $this->assertEquals('jane@doe.test', $results[0]['user']['email']);
        $this->assertEquals('active', $results[0]['user']['status']);
        $this->assertEquals(['ROLE_ADMIN'], $results[0]['user']['roles']);
        $this->assertEquals('Implementation Patterns', $results[0]['book']['title']);
        $this->assertEquals(2000, $results[0]['book']['year']);

        $this->assertEquals('John Doe', $results[1]['user']['name']);
        $this->assertEquals('johndoe', $results[1]['user']['username']);
        $this->assertEquals('john@doe.test', $results[1]['user']['email']);
        $this->assertEquals('active', $results[1]['user']['status']);
        $this->assertEquals(['ROLE_USER'], $results[1]['user']['roles']);
        $this->assertEquals('Domain Driven Design', $results[1]['book']['title']);
        $this->assertEquals(2012, $results[1]['book']['year']);

        $this->assertStatusCodeSuccess();
    }

    /** @test */
    public function should_get_object_list_with_limit(): void
    {
        $query = [
            'query' => 'query GetReservations {
                getReservations(limit: 1, orderBy: null, criteria: null) {
                    reservations {
                        id
                        book {
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
                        user {
                            id
                            name
                            username
                            email
                            status
                            roles
                        }
                    }
                }
            }',
        ];

        $this->loginAsAdmin();
        $this->sendGetRequest('/', $query);

        $response = $this->getArrayResponse();
        $results = $response['data']['getReservations']['reservations'];

        $this->assertCount(1, $results);
        $this->assertEquals('Jane Doe', $results[0]['user']['name']);
        $this->assertEquals('janedoe', $results[0]['user']['username']);
        $this->assertEquals('jane@doe.test', $results[0]['user']['email']);
        $this->assertEquals('active', $results[0]['user']['status']);
        $this->assertEquals(['ROLE_ADMIN'], $results[0]['user']['roles']);
        $this->assertEquals('Implementation Patterns', $results[0]['book']['title']);
        $this->assertEquals(2000, $results[0]['book']['year']);
        $this->assertStatusCodeSuccess();
    }

    /** @test */
    public function should_get_object_list_ordered_by_field(): void
    {
        $query = [
            'query' => 'query GetReservations {
                getReservations(limit: null, orderBy: "dateFrom", criteria: "asc") {
                    reservations {
                        id
                        book {
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
                        user {
                            id
                            name
                            username
                            email
                            status
                            roles
                        }
                    }
                }
            }',
        ];

        $this->loginAsAdmin();
        $this->sendGetRequest('/', $query);

        $response = $this->getArrayResponse();
        $results = $response['data']['getReservations']['reservations'];

        $this->assertCount(2, $results);

        $this->assertEquals('John Doe', $results[0]['user']['name']);
        $this->assertEquals('johndoe', $results[0]['user']['username']);
        $this->assertEquals('john@doe.test', $results[0]['user']['email']);
        $this->assertEquals('active', $results[0]['user']['status']);
        $this->assertEquals(['ROLE_USER'], $results[0]['user']['roles']);
        $this->assertEquals('Domain Driven Design', $results[0]['book']['title']);
        $this->assertEquals(2012, $results[0]['book']['year']);

        $this->assertEquals('Jane Doe', $results[1]['user']['name']);
        $this->assertEquals('janedoe', $results[1]['user']['username']);
        $this->assertEquals('jane@doe.test', $results[1]['user']['email']);
        $this->assertEquals('active', $results[1]['user']['status']);
        $this->assertEquals(['ROLE_ADMIN'], $results[1]['user']['roles']);
        $this->assertEquals('Implementation Patterns', $results[1]['book']['title']);
        $this->assertEquals(2000, $results[1]['book']['year']);

        $this->assertStatusCodeSuccess();
    }

    /** @test */
    public function should_get_object_by_id(): void
    {
        $query = [
            'query' => 'query GetReservation {
                getReservation(id: 1) {
                    id
                    user {
                        id
                        name
                        username
                        email
                        status
                        roles
                    }
                    book {
                        id
                        title
                        year
                    }
                }
            }',
        ];

        $this->loginAsAdmin();
        $this->sendGetRequest('/', $query);

        $response = $this->getArrayResponse();
        $result = $response['data']['getReservation'];

        $this->assertEquals('John Doe', $result['user']['name']);
        $this->assertEquals('johndoe', $result['user']['username']);
        $this->assertEquals('john@doe.test', $result['user']['email']);
        $this->assertEquals('active', $result['user']['status']);
        $this->assertEquals(['ROLE_USER'], $result['user']['roles']);
        $this->assertEquals('Domain Driven Design', $result['book']['title']);
        $this->assertEquals(2012, $result['book']['year']);
        $this->assertStatusCodeSuccess();
    }

    /** @test */
    public function should_not_get_object_if_authenticated_and_not_authorized(): void
    {
        $query = [
            'query' => 'query GetReservation {
                getReservation(id: 1) {
                    id
                    user {
                        id
                        name
                        username
                        email
                        status
                        roles
                    }
                    book {
                        id
                        title
                        year
                    }
                }
            }',
        ];

        $this->loginAsUser();
        $this->sendGetRequest('/', $query);

        $results = $this->getArrayResponse();
        $this->assertEquals('Access denied to this field.', $results['extensions']['warnings'][0]['message']);
    }

    /** @test */
    public function should_not_get_object_list_if_authenticated_and_not_authorized(): void
    {
        $query = [
            'query' => 'query GetReservations {
                getReservations(limit: null, orderBy: null, criteria: null) {
                    reservations {
                        id
                        book {
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
                        user {
                            id
                            name
                            username
                            email
                            status
                            roles
                        }
                    }
                }
            }',
        ];

        $this->loginAsUser();
        $this->sendGetRequest('/', $query);

        $results = $this->getArrayResponse();
        $this->assertEquals('Access denied to this field.', $results['extensions']['warnings'][0]['message']);
    }
}
