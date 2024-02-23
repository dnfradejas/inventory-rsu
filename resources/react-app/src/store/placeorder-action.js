import {retryRequestWhenTokenIsRefreshed} from '../utils/helpers';
import {isInArray} from '../utils/helpers';
import {notifActions} from './notification-slice';

import api from '../request/api';


export const postPlaceOrder = (userInfo, storeId) => {

    let data = userInfo;
    data.store = storeId;

    return async (dispatch) => {
        const createOrder = async () => {
            const response = await api.post(`/v1/place-order`, data);
            return response;
        }

        try {

            let response = await createOrder();

            const cond = response.hasOwnProperty('status') && isInArray(response.status, [401, 400]);

            const newResponse = await retryRequestWhenTokenIsRefreshed(async () => {
                return await createOrder();
            }, cond);

            response = newResponse !== false ? newResponse : response;

            dispatch(notifActions.showNotification({
                title: 'Success',
                message: 'Your order has been placed!',
                success: true,

            }));
            
        } catch (e) {
            dispatch(notifActions.showNotification({
                title: 'Failed',
                message: 'Oops. We are unable to process your order. Please try again',
                success: false,

            }));
        }
    }

};