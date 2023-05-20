import { gql } from 'graphql-tag'

export const GET_COLLECTION_RESERVATIONS = `
    query GetReservations {
        getReservations(limit: null, orderBy: null, criteria: null) {
            reservations {
                id
                book {
                    id
                    title
                    year
                    author {
                        id
                        name
                    }
                    category {
                        id
                        name
                    }
                }
                user {
                    id
                    name
                    username
                    email
                    status
                    roles
                }
                dateFrom
                dateTo
            }
        }
    }
`;
