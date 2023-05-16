import { gql } from 'graphql-tag'

export const GET_AUTHOR_BY_ID = id => `
    query GetAuthor {
        getAuthor(id: ` + id + `) {
            id
            name
        }
    }
`;
