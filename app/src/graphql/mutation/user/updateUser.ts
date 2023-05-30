import { gql } from 'graphql-tag'

export const UPDATE_USER = (id, name, username, email, roles) => `
    mutation UpdateUser {
        updateUser(
            id: ` + id + `,
            user: {
                name: "` + name + `"
                username: "` + username + `"
                email: "` + email + `"
                status: "active"
                roles: "` + roles + `"
                password: "` + roles + `"
            }
        ) {
            code
            message
        }
    }
`;
