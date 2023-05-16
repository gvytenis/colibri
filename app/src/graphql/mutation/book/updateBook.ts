import { gql } from 'graphql-tag'

export const UPDATE_BOOK = (id, title, authorId, year, categoryId) => `
    mutation UpdateBook {
        updateBook(
            id: ` + id + `
            book: {
                title: "` + title + `",
                author: ` + authorId + `,
                year: ` + year + `,
                category: ` + categoryId + `
            }
        ) {
            code
            message
        }
    }
`;
