import { baseQuery } from "@/graphql/baseQuery";

export const graphQlMutation = (baseUrl, mutation, token) => baseQuery(baseUrl, mutation, token);
