import axios from 'axios';

import { productSliceActions } from './product-slice';
import {apiHost} from '../utils/helpers';


import api from '../request/api';

// Creator thunk
export const fetchProducts = store_id => {

    return async (dispatch) => {
        
        const fetchProductData = async() => {

            const response = await api.get(`/v1/products?store_id=${store_id}`);
            return response.data;
        };

        try {
            const products = await fetchProductData();
            
            dispatch(productSliceActions.setProducts(products));
        } catch (error) {

        }
    };
};

export const searchProducts = (store_id, q) => {
    return async (dispatch) => {
        const fetchSearchResult = async() => {
            const response = await api.get(`/v1/products?store_id=${store_id}&q=${q}`);
            return response.data;
        };

        try {
            const products = await fetchSearchResult();
            dispatch(productSliceActions.setProducts(products));
        } catch (error) {

        }
    };
};

export const fetchProductDetail = (slug) => {
    
    const makeRequest = async () => {

        const response = await api.get(`/v1/products/1/${slug}`);
            return response.data;
    };

    return makeRequest();
};