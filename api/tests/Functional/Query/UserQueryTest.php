<?php

declare(strict_types=1);

namespace App\Tests\Functional\Query;

use App\Tests\ApiTestCase;

class UserQueryTest extends ApiTestCase implements BaseQueryTestInterface
{
    /** @test */
    public function should_not_get_object_if_not_authenticated(): void
    {
        $query = [
            'query' => 'query GetUser {
                getUser(id: 1) {
                    id
                    name
                    username
                    email
                    status
                    roles
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
            'query' => 'query GetUsers {
                getUsers(criteria: null, orderBy: null, limit: null) {
                    users {
                        id
                        name
                        username
                        email
                        status
                        roles
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
            'query' => 'query GetUsers {
                getUsers(criteria: null, orderBy: null, limit: null) {
                    users {
                        id
                        name
                        username
                        email
                        status
                        roles
                    }
                }
            }',
        ];

        $this->loginAsAdmin();
        $this->sendGetRequest('/', $query);

        $response = $this->getArrayResponse();
        $results = $response['data']['getUsers']['users'];

        $this->assertCount(2, $results);
        $this->assertEquals('Jane Doe', $results[0]['name']);
        $this->assertEquals('John Doe', $results[1]['name']);
        $this->assertStatusCodeSuccess();
    }

    /** @test */
    public function should_get_object_list_with_limit(): void
    {
        $query = [
            'query' => 'query GetUsers {
                getUsers(criteria: null, orderBy: null, limit: 1) {
                    users {
                        id
                        name
                        username
                        email
                        status
                        roles
                    }
                }
            }',
        ];

        $this->loginAsAdmin();
        $this->sendGetRequest('/', $query);

        $response = $this->getArrayResponse();
        $results = $response['data']['getUsers']['users'];

        $this->assertCount(1, $results);
        $this->assertEquals('Jane Doe', $results[0]['name']);
        $this->assertStatusCodeSuccess();
    }

    /** @test */
    public function should_get_object_list_ordered_by_field(): void
    {
        $query = [
            'query' => 'query GetUsers {
                getUsers(criteria: "asc", orderBy: "name", limit: null) {
                    users {
                        id
                        name
                        username
                        email
                        status
                        roles
                    }
                }
            }',
        ];

        $this->loginAsAdmin();
        $this->sendGetRequest('/', $query);

        $response = $this->getArrayResponse();
        $results = $response['data']['getUsers']['users'];

        $this->assertCount(2, $results);
        $this->assertEquals('Jane Doe', $results[0]['name']);
        $this->assertEquals('John Doe', $results[1]['name']);
        $this->assertStatusCodeSuccess();
    }

    /** @test */
    public function should_get_object_by_id(): void
    {
        $query = [
            'query' => 'query GetUser {
                getUser(id: 1) {
                    id
                    name
                    username
                    email
                    status
                    roles
                }
            }',
        ];

        $this->loginAsAdmin();
        $this->sendGetRequest('/', $query);

        $response = $this->getArrayResponse();
        $result = $response['data']['getUser'];

        $this->assertEquals('John Doe', $result['name']);
        $this->assertEquals('johndoe', $result['username']);
        $this->assertEquals('john@doe.test', $result['email']);
        $this->assertEquals('active', $result['status']);
        $this->assertEquals(['ROLE_USER'], $result['roles']);
        $this->assertStatusCodeSuccess();
    }

    /** @test */
    public function should_not_get_object_if_authenticated_and_not_authorized(): void
    {
        $query = [
            'query' => 'query GetUser {
                getUser(id: 1) {
                    id
                    name
                    username
                    email
                    status
                    roles
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
            'query' => 'query GetUsers {
                getUsers(criteria: null, orderBy: null, limit: null) {
                    users {
                        id
                        name
                        username
                        email
                        status
                        roles
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
