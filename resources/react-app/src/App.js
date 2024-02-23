import React, {Fragment, useEffect, Suspense} from 'react';
import { useDispatch } from 'react-redux';
import {useCookies} from 'react-cookie';
import { Helmet } from 'react-helmet'
import {Routes, Route} from 'react-router-dom';

import Footer from './components/Layout/Footer';

import Cart from './components/Pages/Cart';
import {retrieveCart} from './store/cart-actions';

import {boot} from './providers/service-providers';


const Products = React.lazy(() => import('./components/Pages/Products'));
const ProductDetail = React.lazy(() => import('./components/Pages/ProductDetail'));
const PlaceOrder = React.lazy(() => import('./components/Pages/PlaceOrder'));
const Stores = React.lazy(() => import('./components/Pages/Stores'));

boot();

function App() {
  // TODO:: refactor this cookie since we're using
    // this in difference places
  const [cookies, setCookie] = useCookies(['store_cookie']);
  const dispatch = useDispatch();
  const storeId = cookies.store_cookie;
  useEffect(() => {
    dispatch(retrieveCart(storeId));
  }, [dispatch, storeId]);


  return (
    <Fragment>
        <Helmet>
          <title>Shop</title>
        </Helmet>
      
      <Suspense fallback={<p>Loading...</p>}>
        <Routes>
          <Route path="/" element={<Stores/>} exact/>
          {cookies.store_cookie &&
          <>
            <Route path="/products" element={<Products />} exact/>
            <Route path="/products/:slug" element={<ProductDetail/>} exact/>
            <Route path="/cart" element={<Cart/>} exact/>
            <Route path="/checkout" element={<PlaceOrder/>} exact/>
          </>
          }
          <Route path="*" element={<Stores/>}/>
          
        </Routes>
      </Suspense>
      
    </Fragment>
  );
}


export default App;
