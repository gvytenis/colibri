import { gql } from 'graphql-tag'

export const DELETE_CATEGORY = id => `
    mutation DeleteCategory {
        deleteCategory(id: ` + id + `) {
            code
            message
        }
    }
`;
