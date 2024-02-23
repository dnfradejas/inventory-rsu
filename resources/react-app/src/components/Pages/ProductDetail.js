import React, {Fragment, useState, useEffect} from 'react';
import {useDispatch, useSelector} from 'react-redux';
import { useParams, Navigate } from 'react-router-dom';
import {Radio, Button} from 'antd';

import {useCookies} from 'react-cookie';
import { fetchProductDetail } from '../../store/product-actions';
import { addToCart } from '../../store/cart-actions';

import MainHeader from '../Layout/MainHeader';


const ProductDetail = () => {
    const notification = useSelector((state) => state.notification.notification);
    // TODO:: refactor this cookie since we're using
    // this in difference places
    const [cookies, setCookie] = useCookies(['store_cookie']);
    const dispatch = useDispatch();
    const [product, setProduct] = useState({
        sizes: [],
        colors: []
    });
    const [selectColor, setSelectColor] = useState(null);
    const [selectSize, setSelectSize] = useState(null);

    const params = useParams();
    const productSlug = params.slug;

    useEffect(() => {
        fetchProductDetail(productSlug)
                        .then(response => {
                            setProduct(response.product);
                        });

    }, [productSlug]);


    const handleAddToCart = () => {
        dispatch(addToCart({
            store: cookies.store_cookie,
            product: product.id,
            color: selectColor,
            size: selectSize
        }));
        
    }
    
    const {sizes, colors} = product;
    const hasSizes = sizes.length > 0;
    const hasColor = colors.length > 0;

    if (notification && notification.success === true) {
        
        return <Navigate to="/cart"/>;
    }
    return (
        <MainHeader>
            <section className="section-content padding-y bg">
                <div className="container">
                    <div className="card">
	                    <div className="row no-gutters">
		                    <aside className="col-md-6">
                                <article className="gallery-wrap"> 
	                                <div className="img-big-wrap">
                                        <img src={product.image} alt={product.product_name}/>
	                                </div>
	
                                </article>
		                    </aside>
		                    <main className="col-md-6 border-left">
                                <article className="content-body">
                                    <h2 className="title">{product.product_name}</h2>
                                        <div className="mb-3"> 
	                                        <var className="price h4">PHP{product.price}</var> 
                                        </div> 
                                        {/* <p>Test.</p> */}
                                        {hasColor &&  <ColorsComponent colors={colors} setSelectColor={setSelectColor}/>}
                                        
                                        {hasSizes && <SizesComponent sizes={sizes} setSelectSize={setSelectSize}/>}
	                                    <hr/>
                                        <Button className="btn btn-primary" onClick={handleAddToCart}>Add to cart</Button>
                                        {notification && <p>{notification.message}</p>}
                                </article>
		                    </main>
	                    </div>
                    </div>
                    <br/>


</div>
</section>
        </MainHeader>
    );
};

const ColorsComponent = ({colors, setSelectColor}) => {

    const handleColor = (e) => {
        setSelectColor(e.target.value);
    };
    return (
        <Fragment>
            <hr/>
                <div className="row">
                    <div className="item-option-select">
                        <h6>Choose Color</h6>
                        <div className="btn-group btn-group-sm btn-group-toggle" data-toggle="buttons">
                                    <Radio.Group>
                                    {colors.map((color) => <label key={color[0]} className="btn btn-light">
                                            <Radio.Button onChange={handleColor} value={color[0]}>{color[1]}</Radio.Button>
                                        </label>)}
                                    </Radio.Group>
                        </div> 
                    </div>
                </div>
        </Fragment>
    );
};

const SizesComponent = ({sizes, setSelectSize}) => {
    const handleSize = e => {
        setSelectSize(e.target.value);
    };
    return (
        <Fragment>
            <div className="row">
                <div className="item-option-select">
                    <h6>Select Size</h6>
                    <div className="btn-group btn-group-sm btn-group-toggle" data-toggle="buttons">
                        <Radio.Group>
                            {sizes.map((size) => 
                                <label key={size[0]} className="btn btn-light">
                                    <Radio.Button onChange={handleSize} value={size[0]}>{size[1]}</Radio.Button>
                                </label>
                            )}
                        </Radio.Group>
                    </div> 
                </div>
            </div>
        </Fragment>
    );
};

export default ProductDetail;