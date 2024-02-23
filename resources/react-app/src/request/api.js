

import axios from "axios";
import {apiHost} from '../utils/helpers';
import {setupInterceptorsTo} from '../request/axiosinterceptors';

const api = setupInterceptorsTo(
    axios.create({
        baseURL: apiHost(),
        headers: {
            "Accept": "application/json",
            "Content-Type": "application/json"
        },
    })
);


export default api;