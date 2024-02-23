import {configureStore} from '@reduxjs/toolkit';

import cartSlice from './cart-slice';
import productSlice from './product-slice';
import notifSlice from './notification-slice';
import storeSlice from './store-slice';

const store = configureStore({
    reducer: {
        cart: cartSlice.reducer,
        products: productSlice.reducer,
        notification: notifSlice.reducer,
        store: storeSlice.reducer,
    }
});

export default store;