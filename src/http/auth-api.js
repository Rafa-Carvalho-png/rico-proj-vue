import api from "./api";

export const post = (path, data = {}) => api.post(path, data);
export const get = (path) => api.get(path);
