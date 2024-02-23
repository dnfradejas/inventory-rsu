import React, {Fragment, useEffect} from 'react';
import {useDispatch, useSelector} from 'react-redux';
import {Link} from 'react-router-dom';

import {useCookies} from 'react-cookie';

import CartItem from './CartItem';
import NoCart from './NoCart';

import { addToCart } from '../../store/cart-actions';
import {resetNotification} from '../../utils/helpers';

import MainHeader from '../Layout/MainHeader';


const Cart = () => {

    const cart = useSelector((state) => state.cart);
    // TODO:: refactor this cookie since we're using
    // this in difference places
    const [cookies, setCookie] = useCookies(['store_cookie']);
    
    const dispatch = useDispatch();
    const {items, grandTotal} = cart;
    
    // need to reset notification so we can redirect
    // back to product detail page when we click
    // product from cart item list
    resetNotification(dispatch);
    
    const changeInputHandler = (event, item) => {
        const value = event.target.value;
        if (value) {
            dispatch(addToCart({
                store: cookies.store_cookie,
                product: item.product_id,
                color: item.color_id,
                size: item.size_id,
                quantity: value,
            }));
        }
    };
    
    return (
        <MainHeader>
            <section className="section-content padding-y bg">
                <div className="container">
                    <div className="row">
                        {grandTotal > 0 ? <CartDetails items={items} grandTotal={grandTotal} changeInputHandler={changeInputHandler}/> : <><aside className='col-lg-12'><NoCart text='You cart is empty! Please add some!'/></aside></>}
                    </div>
                </div>
            </section>
        </MainHeader>
    );
};



export const CartDetails = ({items, grandTotal, changeInputHandler}) => {

    return (
        <Fragment>
            <aside className="col-lg-9">
                <div className="card">
                    <table className="table table-borderless table-shopping-cart">
                        <thead className="text-muted">
                            <tr className="small text-uppercase">
                                <th scope="col">Product</th>
                                <th scope="col" width="120">Quantity</th>
                                <th scope="col" width="120">Price</th>
                                <th scope="col" className="text-right" width="200"> </th>
                            </tr>
                        </thead>
                        <tbody>
                            {items.map((item) => <CartItem key={item.id} item={item} changeInputHandler={changeInputHandler}/>)}
                        </tbody>
                    </table>
                </div>
            </aside>
            <aside className="col-lg-3">
                <div className="card">
                    <div className="card-body">
                        <dl className="dlist-align">
                            <dt>Total:</dt>
                            <dd className="text-right text-dark b"><strong>PHP{grandTotal}</strong></dd>
                        </dl>
                        <hr/>
                        <Link to='/checkout' className="btn btn-primary btn-block"> Checkout</Link>
                        <Link to={`/products`} className="btn btn-light btn-block">Continue Shopping</Link>
                    </div>
                </div>
            </aside> 
        </Fragment>
    );
};

export default Cart;