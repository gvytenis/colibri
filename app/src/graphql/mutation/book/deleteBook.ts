import { gql } from 'graphql-tag'

export const DELETE_BOOK = id => `
    mutation DeleteBook {
        deleteBook(id: ` + id + `) {
            code
            message
        }
    }
`;
