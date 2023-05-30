import { gql } from 'graphql-tag'

export const UPDATE_ACCOUNT = (id, name, email) => `
    mutation UpdateAccount {
        updateAccount(
            id: ` + id + `
            user: {
                name: "` + name + `"
                email: "` + email + `"
            }
        ) {
            code
            message
        }
    }
`;
