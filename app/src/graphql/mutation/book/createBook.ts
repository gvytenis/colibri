import { gql } from 'graphql-tag'

export const CREATE_BOOK = (title, authorId, year, categoryId) => `
    mutation CreateBook {
        createBook(
            book: {
                title: "` + title + `"
                author: ` + authorId + `
                year: ` + year + `
                category: ` + categoryId + `
            }
        ) {
            code
            message
        }
    }
`;
