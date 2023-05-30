import { gql } from 'graphql-tag'

export const CREATE_USER = (name, username, email, roles, password) => `
    mutation CreateUser {
        createUser(
            user: {
                name: "` + name + `"
                username: "` + username + `"
                email: "` + email + `"
                status: "active"
                roles: "` + roles + `"
                password: "` + password + `"
            }
        ) {
            code
            message
        }
    }
`;
