import { gql } from 'graphql-tag'

export const GET_COLLECTION_USERS = `
    query GetUsers {
        getUsers(limit: null, orderBy: null, criteria: null) {
            users {
                id
                name
                username
                email
                status
                roles
            }
        }
    }
`;
