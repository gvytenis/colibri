import { baseQuery } from "@/graphql/baseQuery";

export const graphQlQuery = (baseUrl, query, token) => baseQuery(baseUrl, query, token);
