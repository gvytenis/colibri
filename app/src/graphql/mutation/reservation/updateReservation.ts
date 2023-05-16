import { gql } from 'graphql-tag'

export const UPDATE_RESERVATION = (id, bookId, userId, dateFrom, dateTo) => `
    mutation UpdateReservation {
        updateReservation(
            id: ` + id + `,
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
