import { gql } from 'graphql-tag'

export const UPDATE_CATEGORY = (id, name) => `
    mutation UpdateCategory {
        updateCategory(
            id: ` + id + `,
            category: {
                name: "` + name + `"
            }
        ) {
            code
            message
        }
    }
`;
