import React, { Fragment, useEffect } from 'react';
import {useSelector, useDispatch} from 'react-redux';
import {useCookies} from 'react-cookie';

import MainHeader from '../Layout/MainHeader';

import ProductItem from './ProductItem';

import {fetchProducts} from '../../store/product-actions';

const Products = () => {
    
    const [cookies, setCookie] = useCookies(['store_cookie']);
    const products = useSelector((state) => state.products.products);
    const storeId = cookies.store_cookie;
    const dispatch = useDispatch();
    useEffect(() => {
        dispatch(fetchProducts(storeId));
    }, [dispatch, storeId]);
    
    const hasProducts = products.length > 0;
    
    return (
        <MainHeader>

            <section className="section-name padding-y-sm">
                <div className="container">
                    {hasProducts ? 
                    <>
                        <header className="section-heading">
                            {/* <a href="./store.html" className="btn btn-outline-primary float-right">See all</a> */}
                            <h3 className="section-title">Popular products</h3>
                        </header>

                        <div className="row">
                            {products.map((product) => <ProductItem key={product.id} product={product}/>)}
                        </div>
                    </>
                    : <>
                    <article className="card mb-4">
                        <div className="card-body">
                            <p>No products available</p>
                        </div>
                    </article>
                    </>}
                </div>
            </section>
        </MainHeader>
    );


};

export default Products;