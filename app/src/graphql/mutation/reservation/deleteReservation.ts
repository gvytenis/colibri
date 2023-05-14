import { gql } from 'graphql-tag'

export const DELETE_RESERVATION = id => `
    mutation DeleteReservation {
        deleteReservation(id: ` + id + `) {
            code
            message
        }
    }
`;
