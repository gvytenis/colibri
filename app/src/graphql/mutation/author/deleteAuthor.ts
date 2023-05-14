import { gql } from 'graphql-tag'

export const DELETE_AUTHOR = id => `
    mutation DeleteAuthor {
        deleteAuthor(id: ` + id + `) {
            code
            message
        }
    }
`;
