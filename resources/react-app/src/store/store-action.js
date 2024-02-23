import {isInArray} from '../utils/helpers';

import { storeSliceActions } from './store-slice';
import {retryRequestWhenTokenIsRefreshed} from '../utils/helpers';
import api from '../request/api';

export const fetchStores = (search = null) => {

    return async(dispatch) => {

        const retrieve = async() => {
            const url = search ? `/v1/stores?q=${search}` : `/v1/stores`;
            const response = await api.get(url);

            return response.data;
        };

        try {

            let response = await retrieve();
            
            const cond = response.hasOwnProperty('status') && isInArray(response.status, [401, 400]);

            const newResponse = await retryRequestWhenTokenIsRefreshed(async () => {
                return await retrieve();
            }, cond);

            response = newResponse !== false ? newResponse.data : response;
            
            dispatch(storeSliceActions.replaceStores({
                stores: response
            }));
        } catch(e) {

        }

    };
};