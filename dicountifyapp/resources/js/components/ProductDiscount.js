import React from 'react';
import ReactDOM from 'react-dom';

function ProductDiscount() {
    return (
        <div className="container">
            <div className="row justify-content-center">
                <div className="col-md-8">
                    <div className="card">
                        <div className="card-header">Product Discount Page</div>

                        <div className="card-body">I'm an Discount component!</div>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default ProductDiscount;

if (document.getElementById('productdiscount')) {
    ReactDOM.render(<ProductDiscount />, document.getElementById('productdiscount'));
}
