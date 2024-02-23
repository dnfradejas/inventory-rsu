import {createSlice} from '@reduxjs/toolkit';

const storeSlice = createSlice({
    name: 'store',
    initialState: {
        stores: []
    },
    reducers: {
        replaceStores(state, action) {
            state.stores = action.payload.stores;
        }
    }
});

export const storeSliceActions = storeSlice.actions;

export default storeSlice;