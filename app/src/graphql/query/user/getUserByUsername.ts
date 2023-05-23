import { gql } from 'graphql-tag'

export const GET_USER_BY_USERNAME = username => `
    query GetUser {
        getUserByUsername(username: "` + username + `") {
            id
            name
            username
            email
            status
            roles
        }
    }
`;
