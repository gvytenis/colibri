import { gql } from 'graphql-tag'

export const CREATE_RESERVATION = (bookId, userId, dateFrom, dateTo) => `
    mutation CreateReservation {
        createReservation(
            reservation: {
                bookId: ` + bookId + `
                userId: ` + userId + `
                dateFrom: "` + dateFrom + `"
                dateTo: "` + dateTo + `"
            }
        ) {
            code
            message
        }
    }
`;
