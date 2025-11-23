import axios from 'axios';

// Create an Axios instance
const axiosInstance = axios.create({
    baseURL: '/', // Laravel routes are relative to app root
    withCredentials: true, // send cookies for auth
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
    },
});

// Add Laravel CSRF token automatically
const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
if (token) {
    axiosInstance.defaults.headers.common['X-CSRF-TOKEN'] = token;
}

export default axiosInstance;