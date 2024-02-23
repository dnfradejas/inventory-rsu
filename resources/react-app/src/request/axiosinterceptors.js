import axios from 'axios';
import {isInArray, apiHost}  from '../utils/helpers';

// Blog for this code can be found at https://webera.blog/implement-refresh-token-with-jwt-in-react-app-using-axios-1910087c3d7

const onRequest = (config) => {

    const token = localStorage.getItem('token');

    config.headers["Authorization"] = `Bearer ${token}`;

    return config;
};

const onRequestError = (error) => {
    return Promise.reject(error);
};

const onResponse = (response) => {
    return response;
};


const onResponseError = async(error) => {
    
    if (error.response) {
        // Access token was expired
        const {status: code} = error.response;

        if (isInArray(code, [401]) ) {
            const {refreshed_token} = error.response.data;
            localStorage.setItem("token", refreshed_token);
            return {
                status: 401,
                message: "Refreshed token",
                token: refreshed_token
            };
        } else if (isInArray(code, [400])) { // token blacklisted, need to request a new one
            const response = await axios.get(`${apiHost()}/v1/guest-token`);
            const {token} = response.data;
            localStorage.setItem("token", token);

            return {
                status: 400,
                message: "Regenerate new token",
                token: token
            };
        }

        return {
            status: 500,
            message: "Server error"
        };
    }
};

export const setupInterceptorsTo = (axiosInstance) => {
    axiosInstance.interceptors.request.use(onRequest, onRequestError);
    axiosInstance.interceptors.response.use(onResponse, onResponseError);

    return axiosInstance;
};