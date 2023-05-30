import { gql } from 'graphql-tag'

export const CHANGE_PASSWORD = (id, currentPassword, newPassword, confirmNewPassword) => `
    mutation ChangePassword {
        changePassword(
            id: ` + id + `
            password: {
                current: "` + currentPassword + `"
                new: "` + newPassword + `"
                confirm: "` + confirmNewPassword + `"
            }
        ) {
            code
            message
        }
    }
`;
