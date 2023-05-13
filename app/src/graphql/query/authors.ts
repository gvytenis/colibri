import { gql } from 'graphql-tag'

export const GET_COLLECTION_AUTHORS = `
    query GetAuthors {
        getAuthors(limit: null, orderBy: null, criteria: null) {
            authors {
                id
                name
            }
        }
    }
`;
