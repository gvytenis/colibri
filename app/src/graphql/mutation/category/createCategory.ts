import { gql } from 'graphql-tag'

export const CREATE_CATEGORY = name => `
    mutation CreateCategory {
        createCategory(
            category: {
                name: "` + name + `"
            }
        ) {
            code
            message
        }
    }
`;
