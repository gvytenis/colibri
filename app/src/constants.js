export const APP_NAME = import.meta.env.VITE_APP_NAME;

export const API_URL = {
  base: import.meta.env.VITE_BASE_API_URL,
  login: import.meta.env.VITE_BASE_API_URL + '/api/login',
};

export const STORAGE_KEY = {
  token: 'colibri_token',
  user: 'colibri_user',
  userId: 'colibri_user_id',
};

export const ROLES = {
  admin: 'ROLE_ADMIN',
  user: 'ROLE_USER',
};
