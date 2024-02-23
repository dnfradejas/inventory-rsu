import React from 'react';
import {Link} from 'react-router-dom';

const ProductItem = ({product}) => {
    
    return (
        <div className="col-md-3">
            <div className="card card-product-grid">
                <Link to={`/products/${product.slug}`} className="img-wrap"> <img src={product.image} alt={product.product_name}/> </Link>
                <figcaption className="info-wrap">
                    <Link to={`/products/${product.slug}`} className="title">{product.product_name}</Link>
                    <div className="price mt-1">PHP{product.price}</div>
                </figcaption>
            </div>
        </div>
    );
};

export default ProductItem;