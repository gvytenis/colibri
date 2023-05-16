import { gql } from 'graphql-tag'

export const GET_COLLECTION_BOOKS = `
    query GetBooks {
        getBooks(limit: null, orderBy: null, criteria: null) {
            books {
                id
                title
                year
                category {
                    id
                    name
                }
                author {
                    id
                    name
                }
            }
        }
    }
`;
