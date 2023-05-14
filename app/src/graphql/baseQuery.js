export const baseQuery = (baseUrl, query, token) => fetch(baseUrl, {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': 'Bearer ' + token,
  },
  body: JSON.stringify({
    variables: {},
    query: query,
  }),
}).then(result => result.json());
