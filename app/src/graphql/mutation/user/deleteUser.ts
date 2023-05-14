import { gql } from 'graphql-tag'

export const DELETE_USER = id => `
    mutation DeleteUser {
        deleteUser(id: ` + id + `) {
            code
            message
        }
    }
`;
