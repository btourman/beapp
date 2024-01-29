import { AuthProvider } from "react-admin";
import inMemoryJWT from "./inMemoryJwt";

/**
 * This authProvider is only for test purposes. Don't use it in production.
 */
export const authProvider: AuthProvider = {
  login: ({ username, password }) => {
    const request: Request = new Request(`${import.meta.env.VITE_REST_URL}/api/login_check`, {
      method: 'POST',
      body: JSON.stringify({ username, password }),
      headers: new Headers({ 'Content-Type': 'application/json' })
    });
    return fetch(request)
        .then((response) => {
          if (response.status < 200 || response.status >= 300) {
            throw new Error(response.statusText);
          }
          return response.json();
        })
        .then(({ token }) => inMemoryJWT.setToken(token));
  },
  logout: () => {
    inMemoryJWT.ereaseToken();
    return Promise.resolve();
  },

  checkAuth: () => {
    return inMemoryJWT.getToken() ? Promise.resolve() : Promise.reject();
  },

  checkError: (error) => {
    const status = error.status;
    if (status === 401 || status === 403) {
      inMemoryJWT.ereaseToken();
      return Promise.reject();
    }
    return Promise.resolve();
  },

  getPermissions: () => {
    return inMemoryJWT.getToken() ? Promise.resolve() : Promise.reject();
  },
};

export default authProvider;
