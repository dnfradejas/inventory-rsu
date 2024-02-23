
import {Link} from 'react-router-dom';
import {useCookies} from 'react-cookie';
import React, {Fragment, useState, useEffect} from 'react';
import {useSelector, useDispatch} from 'react-redux';

import {searchProducts, fetchProducts} from '../../store/product-actions';

import '../../css/bootstrap.css';
import '../../css/fontawesome.css';
import '../../css/responsive.css';
import '../../css/ui.css';
import '../../css/style.css';



const MainHeader = props => {
	
	const [search, setSearch] = useState(null);
	const dispatch = useDispatch();
	const cart = useSelector((state) => state.cart);

	const [cookies, setCookie] = useCookies(['store_cookie']);
    const storeId = cookies.store_cookie;


	useEffect(() => {
		let time = setTimeout(() => {
			// display all products when user remove text in search input
			if (search === '') {
				dispatch(fetchProducts(storeId));
			}
		}, 800);
		return () => {
			clearTimeout(time);
		};
	}, [search, dispatch, storeId]);


	const searchHandler = e => {
		e.preventDefault();
		dispatch(searchProducts(storeId, search));
	};

	const searchInputHandler = e => {
		setSearch(e.target.value);
	};
	
	const {totalQuantity} = cart;
    return <Fragment>
        <header className="section-header">
			<nav className="navbar p-md-0 navbar-expand-sm navbar-light border-bottom">
			<div className="container">
			<button className="navbar-toggler" type="button" data-toggle="collapse" data-target="/navbarTop4" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
				<span className="navbar-toggler-icon"></span>
			</button>
			<div className="collapse navbar-collapse" id="navbarTop4">
				<ul className="navbar-nav mr-auto">
					<li className="nav-item dropdown">
						<a href="/" className="nav-link">   Home </a>
						
					</li>
				</ul>
				{/* <ul className="navbar-nav">
					<li><a href="/" className="nav-link"> <i className="fa fa-envelope"></i> Email </a></li>
					<li><a href="/" className="nav-link"> <i className="fa fa-phone"></i> Call us </a></li>
				</ul> */}
			</div>
			</div>
			</nav>

				<section className="header-main border-bottom">
					<div className="container">
						<div className="row align-items-center">
							<div className="col-lg-2 col-md-3 col-6">
								<a href="./" className="brand-wrap">
									<img className="logo" src="./images/logo.png" alt=""/>
								</a>
							</div>
					{/* <div className="col-lg col-sm col-md col-6 flex-grow-0">
						<div className="category-wrap dropdown d-inline-block float-right">
							<button type="button" className="btn btn-primary dropdown-toggle" data-toggle="dropdown"> 
								<i className="fa fa-bars"></i> All category 
							</button>
							<div className="dropdown-menu">
								<a className="dropdown-item" href="/">Machinery / Mechanical Parts / Tools </a>
								<a className="dropdown-item" href="/">Consumer Electronics / Home Appliances </a>
								<a className="dropdown-item" href="/">Auto / Transportation</a>
								<a className="dropdown-item" href="/">Apparel / Textiles / Timepieces </a>
								<a className="dropdown-item" href="/">Home & Garden / Construction / Lights </a>
								<a className="dropdown-item" href="/">Beauty & Personal Care / Health </a> 
							</div>
						</div>
					</div> */}
						<Link to={`/products`} className="btn btn-outline-primary">Shop</Link>
						<div className="col-lg  col-md-6 col-sm-12 col">
							<form className="search">
								<div className="input-group w-100">
									<input type="text" className="form-control" onKeyUp={searchInputHandler} placeholder="Search"/>
									
									<div className="input-group-append">
									<button className="btn btn-primary" type="submit" onClick={searchHandler}>
										<i className="fa fa-search"></i>
									</button>
									</div>
								</div>
							</form>
						</div>
						<div className="col-lg-3 col-sm-6 col-8 order-2 order-lg-3">
									<div className="d-flex justify-content-end mb-3 mb-lg-0">
										<div className="widget-header">
											{/* <small className="title text-muted">Welcome guest!</small>
											<div> 
												<Link to="/products">Sign in <span className="dark-transp"> | </span></Link>
												<Link to="/products"> Register</Link>
											</div> */}
										</div>
										<Link to={`/cart`} className="widget-header pl-3 ml-3">
											<div className="icon icon-sm rounded-circle border"><i className="fa fa-shopping-cart"></i></div>
											<span className="badge badge-pill badge-danger notify">{totalQuantity}</span>
										</Link>
									</div>
								</div>
							</div>
						</div>
					</section>
				</header>
			{props.children}
    </Fragment>

};

export default MainHeader;