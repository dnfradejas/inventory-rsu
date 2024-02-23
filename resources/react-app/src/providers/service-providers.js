import axios from "axios";
import {apiHost} from '../utils/helpers';
import {setupInterceptorsTo} from '../request/axiosinterceptors';

export const boot = () => {

    // addAxiosRequestInterceptors();
    acquireAccessToken();

}



const addAxiosRequestInterceptors = () => {


    axios.create({
        baseURL: apiHost(),
        headers: {
            "Accept": "application/json",
            "Content-Type": "application/json"
        }
    });

    // axios.interceptors.request.use(
    //     config => {
    //         const {origin} = new URL(config.url);
    //         const allowedOrigins = [apiHost()];
    //         const token = localStorage.getItem('token');
            
    //         config.headers.Authorization = `Bearer ${token}`;
    //         config.headers.Accept = 'application/json';
    //         // if (allowedOrigins.includes(origin)) {
    //         // }

    //         return config;
    //     },

    //     error => {
    //         return Promise.reject(error);
    //     }
    // );
};

export const acquireAccessToken = async () => {

    if (!hasAccessToken()) {
        const response = await axios.get(`${apiHost()}/v1/guest-token`);
        setAccessToken(response.data.token);
    }
};


export const getAccessToken = () => {
    return localStorage.getItem('token');
};

export const setAccessToken = (token) => {
    localStorage.setItem('token', token);
};

const hasAccessToken = () => {
    return localStorage.getItem('token') != null;
};