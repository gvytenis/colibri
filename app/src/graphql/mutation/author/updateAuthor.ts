import { gql } from 'graphql-tag'

export const UPDATE_AUTHOR = (id, name) => `
    mutation UpdateAuthor {
        updateAuthor(
            id: ` + id + `,
            author: {
                name: "` + name + `"
            }
        ) {
            code
            message
        }
    }
`;
