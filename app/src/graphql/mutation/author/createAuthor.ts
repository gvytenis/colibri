import { gql } from 'graphql-tag'

export const CREATE_AUTHOR = name => `
    mutation CreateAuthor {
        createAuthor(
            author: {
                name: "` + name + `"
            }
        ) {
            code
            message
        }
    }
`;
