import React, { Fragment, useEffect, useState} from 'react';
import {useDispatch, useSelector} from 'react-redux';
import { fetchStores } from '../../store/store-action';
import {useCookies} from 'react-cookie';
import Footer from '../Layout/Footer';


const Header = () => {

    const dispatch = useDispatch();
    const [search, setSearch] = useState(null);


    // display stores when search keyword is deleted from input
    useEffect(() => {

        let time = setTimeout(() => {
			// display all products when user remove text in search input
			if (search === '') {
				dispatch(fetchStores());
			}
		}, 800);
		return () => {
			clearTimeout(time);
		};

    }, [search, dispatch]);


    const onKeyupSearchHandler = e => {
        setSearch(e.target.value);
    };

    const onClickSearchStoreHandler = e => {
        dispatch(fetchStores(search));
    };

    return (
        <header className="main_header">

                <div className="main_header--form">
                    <div class="main_header--logo">
                        <i class="main_header--logo-1"></i>
                    </div>

                    <div className="main_header--form--search">
                        <input type="text" className="main_header--form--search-input" onKeyUp={onKeyupSearchHandler} placeholder="Search for a store"/>
                        <div className="main_header--form--search-input--icon-cont" onClick={onClickSearchStoreHandler}>
                            <i className="main_header--form--search-input--icon"></i>
                        </div>
                    </div>
                </div>

                <div className="main_header--title">
                    <h3 className="color-white -bold">Welcome to MIMS Store!</h3>
                    <p className="color-white">Please choose a store to get started.</p>
                </div>
            </header>
    );

};

const StoreList = ({stores, storeClickHandler}) => {
    return (
        <div className="main_content container">
                <div className="main_content--stores">
                    <div className="row">


                    {stores.map((store) =>
                            <div key={store.id} onClick={()=> storeClickHandler(store)} className="col-md-3">
                                <div className="card -text-center main_content--stores-column">
                                    <div className="card-body">
                                    <img src={store.image_url} alt={store.store_name}/>
                                        <div className="main_content--stores--info">
                                            <h6>{store.store_name}</h6>
                                            <p className="color-pale">{store.address}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        )}
                    </div>
                </div>
            </div>
    );
};

const Stores = () => {

    const stores = useSelector((state) => state.store.stores);

    const [cookies, setCookie, removeCookie] = useCookies(['store_cookie']);
    const dispatch = useDispatch();    
    useEffect(() => {
        dispatch(fetchStores());
    }, [dispatch]);

    const storeClickHandler = store => {
        removeCookie('store_cookie', {path: '/'});
        setCookie('store_cookie', store.id, {path: '/'});
        window.location.href = "/products";
    };

    return (
        <Fragment>
            
            <div className="page-container">
                <div className="content-wrap">
                    <Header/>
                    <StoreList stores={stores} storeClickHandler={storeClickHandler}/>
                </div>
                <footer className="footer">
                    <div className="footer-copyright">Copyright &copy; 2022</div>
                    <div className="footer-info -text-center">info@mis.rsu.edu.ph</div>
                </footer>
            </div>
        </Fragment>
    );
};

export default Stores;