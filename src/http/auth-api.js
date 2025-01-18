import api from "./api";

export const login = (path, data) => api.post(path, data);