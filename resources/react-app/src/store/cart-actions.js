import {retryRequestWhenTokenIsRefreshed} from '../utils/helpers';
import {isInArray} from '../utils/helpers';


import { notifActions } from './notification-slice';
import { cartActions } from './cart-slice';

import api from '../request/api';


export const addToCart = product => {
    
    return async (dispatch) => {

        const pushCart = async () => {
            const response = await api.post(`/v1/cart`, product);
            return response;
        }
        try {
            let response = await pushCart();

            const cond = response.hasOwnProperty('status') && isInArray(response.status, [401, 400]);

            const newResponse = await retryRequestWhenTokenIsRefreshed(async () => {
                return await pushCart();
            }, cond);

            response = newResponse !== false ? newResponse : response;
            
            dispatch(cartActions.replaceCart({
                grandTotal: response.data.grand_total,
                totalQuantity: response.data.total_quantity,
                items: response.data.products,
            }));

            dispatch(notifActions.showNotification({
                title: 'Success',
                message: 'Product successfully added in your cart!',
                success: true,
            }));

        } catch(e) {
            dispatch(notifActions.showNotification({
                title: 'Error',
                message: e.response.data.message,
                success: false,
            }));
        }
    };
};

export const removeCartItem = item => {
    
    return async (dispatch) => {
        const data = {
            id: item.id,
            store: item.store_id
        };
        const remove = async () => {
            const response = await api.post(`/v1/cart/delete`, data);
            return response;
        }
        try {

            let response = await remove();

            const newResponse = await retryRequestWhenTokenIsRefreshed(async () => {
                return await remove();
            }, response.hasOwnProperty('status') && response.status == 401);

            response = newResponse !== false ? newResponse : response;

            dispatch(cartActions.replaceCart({
                grandTotal: response.data.grand_total,
                totalQuantity: response.data.total_quantity,
                items: response.data.products,
            }));

        } catch(e) {
            dispatch(notifActions.showNotification({
                title: 'Error',
                message: e.response.data.message,
                success: false,
            }));
        }
    };
}

export const retrieveCart = storeId => {
    return async (dispatch) => {
        
        const fetchCart = async () => {

            const response = await api.get(`/v1/cart?store=${storeId}`);
            return response;
        }
        try {

            let response = await fetchCart();

            const newResponse = await retryRequestWhenTokenIsRefreshed(async () => {
                return await fetchCart();
            }, response.hasOwnProperty('status') && response.status == 401);

            response = newResponse !== false ? newResponse : response;
            
            dispatch(cartActions.replaceCart({
                grandTotal: response.data.grand_total,
                totalQuantity: response.data.total_quantity,
                items: response.data.products,
            }));
            
        } catch(e) {
            // const {refreshed_token} = e.response.data;
            // // setAccessToken(refreshed_token);
            // acquireAccessToken();

            // await fetchCart();
        }

    };
};