import {notifActions} from '../store/notification-slice';

export const apiHost = () => {
    return process.env.REACT_APP_API_HOST;
};


// need to reset notification so we can redirect
// back to product detail page when we click
// product from cart item list
export const resetNotification = (dispatch) => {
    dispatch(notifActions.resetNotification());
};

export const cookieStoreName = () => {
    return process.env.REACT_APP_STORE_COOKIE;
};


export const isInArray = (value, array) => {
    return array.indexOf(value) > -1;
};


export const retryRequestWhenTokenIsRefreshed = (fn, cond = false) => {

    if (cond) {
        
        return fn();
    }

    return false;
};