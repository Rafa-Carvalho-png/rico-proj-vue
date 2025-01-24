import { get } from "../http/auth-api";

function isAuthenticated() {
  const token = localStorage.getItem("auth_token");
  const expiresAt = localStorage.getItem("token_expires_at");

  if (!token || !expiresAt) {
    return false;
  }

  const currentTime = new Date().getTime();
  if (currentTime > expiresAt) {
    logout();
    return false;
  }

  return true;
}

async function logout() {
  await get('/auth/logout');

  localStorage.removeItem("token_expires_at");
  localStorage.removeItem("auth_token");
  localStorage.removeItem("my_id");
}

export { isAuthenticated, logout };