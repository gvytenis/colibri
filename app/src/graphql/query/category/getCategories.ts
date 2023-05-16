import { gql } from 'graphql-tag'

export const GET_COLLECTION_CATEGORIES = `
    query GetCategories {
        getCategories(limit: null, orderBy: null, criteria: null) {
            categories {
                id
                name
            }
        }
    }
`;
