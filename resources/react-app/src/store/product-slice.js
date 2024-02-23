import {createSlice} from '@reduxjs/toolkit';


const productSlice = createSlice({
    name: 'products',
    initialState: {
        products: []
    },
    reducers: {
        setProducts(state, action) {
            state.products = action.payload.products;
        }
    }
});

export const productSliceActions = productSlice.actions;

export default productSlice;