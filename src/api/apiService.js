import axios from 'axios';

const baseURL = 'http://localhost:8000/api'; // Replace with your backend URL

const apiService = {
  register: async (username, email, password) => {
    try {
      const response = await axios.post(`${baseURL}/register`, { username, email, password });
      return response.data;
    } catch (error) {
      throw error; // Re-throw the error for handling in components
    }
  },

  login: async (email, password) => {
    try {
      const response = await axios.post(`${baseURL}/login`, { email, password });
      return response.data;
    } catch (error) {
      throw error; // Re-throw the error for handling in components
    }
  },

  me: async () => {
    const token = localStorage.getItem('token');
    if (!token) {
      throw new Error('No authentication token found.');
    }

    try {
      const response = await axios.get(`${baseURL}/me`, {
        headers: {
          Authorization: `Bearer ${token}`, // Add the token to the headers
        },
      });
      return response.data;
    } catch (error) {
      throw error; // Re-throw the error for handling in components
    }
  },

  logout: () => {
    localStorage.removeItem('token');
  },
};

export default apiService;
