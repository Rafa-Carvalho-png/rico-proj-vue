import api from "./api";

export const post = (path, data) => api.post(path, data);
export const get = (path) => api.get(path);

export const sendResetPasswordEmail = (data) => api.post('/auth/forgot-password', data);
export const resetPassword = (data) => api.post('/auth/reset-password', data);
export const register = (data) => api.post('/auth/register', data);
export const login = (data) => api.post('/auth/login', data);
export const logout = () => api.post('/auth/logout'); 
